<?php

namespace App\Http\Controllers;

use App\Career;
use App\Voucher;
use App\Application;
use Illuminate\Http\Request;
use Billow\Contracts\PaymentProcessor;
use App\Http\Requests\VoucherRequest;
use Carbon\Carbon;

use Auth;
use Log;

class VoucherController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin', ['except' => ['search']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('vouchers.index', [
            'vouchers' => Voucher::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $careers = Career::all();

        return view('vouchers.create', compact('careers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  VoucherRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VoucherRequest $request)
    {
        $request->merge([
            'career_id' => ($request->has('career') && $request->career == 'all') ? null : $request->career
        ]);
        Voucher::create($request->all());

        return redirect()->action('VoucherController@index')->with('success', 'Promo code successfully created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function show(Voucher $voucher)
    {
        return abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request, PaymentProcessor $payfast)
    {
        if(!$request->has('promo_code')) {
            return response()->json(['error' => true, 'status' => 404, 'message' => 'No promo code applied']);
        }

        // Attempt to get voucher
        $voucher = Voucher::where('code', $request->promo_code)->first();
        
        if(!$voucher) {
            return response()->json(['error' => true, 'status' => 404, 'message' => 'Invalid promo code']);
        }

        // Check if the promo code has limit and if limit has been reached
        // NULL value indicates unlimited voucher uses
        if(!is_null($voucher->limit)) {
            $applications_count = Application::where('voucher_id', $voucher->id)->where('user_id', Auth::user()->id)->where('paid', 1)->count();
            if($applications_count >= $voucher->limit) {
                return response()->json(['error' => true, 'status' => 404, 'message' => 'Promo limit has been reached']);
            }
        }

        // Check if the date is within the allowed date range of the voucher.
        if(!is_null($voucher->available_from) || !is_null($voucher->available_until)) {
            $today = Carbon::now();
            $available_from = new Carbon($voucher->available_from);
            $available_until = new Carbon($voucher->available_until);

            if(!is_null($voucher->available_from) && $today->isBefore($available_from)) {
                return response()->json(['error' => true, 'status' => 404, 'message' => 'Invalid promo code']);
            }

            if(!is_null($voucher->available_until) && $today->isAfter($available_until)) {
                return response()->json(['error' => true, 'status' => 404, 'message' => 'Invalid promo code']);
            }
        }

        // Payfast
        $application = Application::where('user_id', Auth::user()->id)->where('paid', 0)->with('job')->first();

        if ($voucher->career_id && $voucher->career_id != $application->career) {
            return response()->json(['error' => true, 'status' => 404, 'message' => 'Invalid promo code']);
        }

        $amount = $application->job->amount;
        $discounted_amount = $amount - ($amount * ($voucher->discount / 100));

        $application->update(
            [
                'user_id'    => Auth::user()->id,
                'payment_id' => Auth::user()->id . '' . mt_rand(100, 1000),
                'voucher_id' => $voucher->id,
                'amount'     => $discounted_amount
            ]
        );

        // If the total after discount is R0, we can ignore Payfast
        if($discounted_amount == 0) {
            $application->update(['paid' => 1]);

            return response()->json([
                'error'         => false, 
                'status'        => 200, 
                'message'       => 'Promo code successfully applied', 
                'data'          => $voucher,
                'amount'        => number_format($discounted_amount, 2),
                'application'   => $application,
                'payfast_form'  => '<a href="' . url('/payment/success') . '" class="btn btn-danger">Check out</a>'
            ]);
        }

        // Build up payment Paramaters.
        $name = explode(' ', Auth::user()->name);
        $first_name = $name[0];
        $last_name = (array_key_exists(1, $name)) ? $name[1] : $name[0];
        $payfast->setBuyer($first_name, $last_name, Auth::user()->email);
        $payfast->setAmount($discounted_amount);
        $payfast->setItem('Job Shadow', 'description');
        $payfast->setMerchantReference($application->payment_id);

        return response()->json([
            'error'         => false, 
            'status'        => 200, 
            'message'       => 'Promo code successfully applied', 
            'data'          => $voucher,
            'amount'        => number_format($discounted_amount, 2),
            'application'   => $application,
            'payfast_form'  => $payfast->paymentForm('Check Out')
        ]);
    }

    /**
     * Display the specified resource by voucher code.
     *
     * @param  \App\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function showByVoucherCode(Voucher $voucher, $request)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function edit(Voucher $voucher)
    {
        $voucher = Voucher::find($voucher->id);
        if($voucher === null) {
            return redirect()->back()->withErrors('Invalid voucher or voucher not found!!!');
        }

        $careers = Career::all();

        return view('vouchers.edit', [
            'voucher'   => $voucher,
            'careers'   => $careers
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  VoucherRequest  $request
     * @param  \App\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function update(VoucherRequest $request, Voucher $voucher)
    {
        $request->merge([
            'career_id' => ($request->has('career') && $request->career == 'all') ? null : $request->career
        ]);

        Voucher::find($voucher->id)->update($request->all());

        return redirect()->action('VoucherController@index')->with('success', 'Voucher successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Voucher::find($id)->delete();

        return redirect()->action('VoucherController@index')->with('status', 'Successfully deleted');
    }
}

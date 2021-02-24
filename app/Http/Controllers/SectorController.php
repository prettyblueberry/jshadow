<?php

namespace App\Http\Controllers;

use App\Sector;
use Illuminate\Http\Request;

class SectorController extends Controller
{

    /**
     * SectorController constructor.
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display the listing of resource
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $sectors = Sector::all();
        // dd($jobs->toArray());

        return view('sectors.index',
            [
                'sectors' => $sectors
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('sectors.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $messages = [
            'unique' => 'The :attribute name already exists.',
        ];

        $rules = [
            'sector' => 'required|string|unique:sectors,name'
        ];

        $this->validate($request, $rules, $messages);

        $sector = new Sector([
            'name' => $request->sector,
        ]);

        $sector->save();

        return redirect()->action('SectorController@index')->with('status', 'Successfully added');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function edit($id)
    {
        $sector = Sector::find($id);

        if(!$sector) {
            return abort(404);
        }

        return view('sectors.edit', ['sector' => $sector]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $messages = [
            'unique' => 'The :attribute name already exists.',
        ];

        $rules = [
            'sector' => 'required|string|unique:sectors,name,' . $id
        ];

        $this->validate($request, $rules, $messages);

        $sector = Sector::find($id);
        $sector->update(['name' => $request->sector]);

        return redirect()->action('SectorController@index', ['id' => $id])->with('status', 'Successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        Sector::find($id)->delete();

        return redirect()->action('SectorController@index')->with('status', 'Successfully deleted');
    }
}

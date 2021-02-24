<?php

namespace App\Exports;

use App\Application;
use App\Job;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Schema;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;
use Log;

class ApplicationsExport implements WithHeadings, WithMapping, FromQuery, ShouldAutoSize, WithColumnFormatting
{
    protected $from;
    protected $to;
    protected $search;

    public function __construct($params) {
        $this->from = $params['from'];
        $this->to = $params['to'];
        $this->company = $params['company'];
        $this->search = $params['search'];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'ID no',
            'Email',
            'Contact number',
            'Referral',
            'School',
            'Company',
            'Company Code',
            'Career',
            'Sector',
            'Location',
            'Dates',
            'Paid',
            'Indemnity',
            'Cancelled',
            'School Type',
            'Career Interests',
            'Dietary Requirements',
            'Parent Guardian Name',
            'Parent Guardian Number',
            'Parent Guardian Email',
            'Parent Guardian ID number',
        ];
    }

    /**
    * @var Invoice $invoice
    */
    public function map($application): array
    {
        return [
            $application->id,
            $application->user->name,
            $application->user->profile->id_no ? '"' . $application->user->profile->id_no . '"' : '',
            $application->user->email,
            $application->user->profile->contact_no,
            $application->user->where_hear,
            $application->user->profile->school_of_attendance,
            ($application->job) ? $application->job->company : '',
            ($application->job) ? $application->job->company_code : '',
            ($application->job) ? ucwords($application->job->career->name) : '',
            isset($application->sector) ? ucwords($application->sector->name) : '',
            isset($application->location->city) ? $application->location->city : '',
            $application->dates,
            ($application->paid) ? 'Yes' : 'No',
            ($application->indemnity_file) ? 'Yes' : 'No',
            ($application->deleted_at) ? 'Yes' : 'No',
            $application->user->profile->school_type,
            $application->user->profile->career_interests,
            $application->user->profile->dietary_requirements,
            $application->user->profile->guardian_name,
            $application->user->profile->guardian_contact_no,
            $application->user->profile->guardian_email,
            $application->user->profile->guardian_id_no ? '"' . $application->user->profile->guardian_id_no . '"' : '',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        $from = new Carbon($this->from);
        $to = new Carbon($this->to);

        // $applications = Application::whereNull('deleted_at')
        //     ->where('paid', 1)
        //     ->whereNotNull('job_id');

        $applications = Application::whereNotNull('job_id')->withTrashed();

        if(!is_null($this->from)) {
            $applications->whereDate('created_at', '>=', $from->format('Y-m-d H:i:s'));
        }

        if(!is_null($this->to)) {
            $applications->whereDate('created_at', '<=', $to->format('Y-m-d H:i:s'));
        }

        if(!is_null($this->company)) {
            $jobs = Job::select('id')->where('company', $this->company)->pluck('id')->toArray();
            $applications->whereIn('job_id', $jobs);
        }

        $applications->select('id', 'user_id', 'sector_id', 'career', 'company_code', 'job_id', 'location_id',
            'dates', 'payment_id', 'voucher_id', 'amount', 'paid', 'indemnity_file', 'created_at', 'updated_at',
            'deleted_at')
            ->where(function( $query ) {
                $query->where("dates", "like", '%' . $this->search . '%')
                    ->orWhere('company_code', 'like', '%' . $this->search . '%')
                    ->orWhereHas('user', function($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%')
                            ->orWhere('where_hear', 'like', '%' . $this->search . '%')
                            ->orWhereHas('profile', function($q) {
                                $q->where('id_no', 'like', '%' . $this->search . '%')
                                    ->orWhere('contact_no', 'like', '%' . $this->search . '%')
                                    ->orWhere('school_of_attendance', 'like', '%' . $this->search . '%');
                            });
                    })
                    ->orWhereHas('job', function($q) {
                        $q->where('company', 'like', '%' . $this->search . '%')
                            ->orWhereHas('career', function($q) {
                                $q->where('name', 'like', '%' . $this->search . '%');
                            });
                    })
                    ->orWhereHas('location', function($q) {
                        $q->where('city', 'like', '%' . $this->search . '%');
                    });

            })->with(['location', 'job', 'user.profile', 'voucher', 'sector']);

        return $applications;
    }

    public function columnFormats(): array
    {
        return [
            'C' => '@',
            'W' => '@'
        ];
    }
}

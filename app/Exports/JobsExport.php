<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Job;
use Maatwebsite\Excel\Concerns\WithMapping;

class JobsExport implements WithHeadings, WithMapping, FromQuery
{
    protected $search;
    protected $startDate;
    protected $endDate;

    public function __construct($request) {
        $this->search = null;
        $this->startDate = null;
        $this->endDate = null;

        if (count($request) > 0 && isset($request['search'])) {
            $this->search = $request['search'];
        }

        if (count($request) > 0 && isset($request['start-date'])) {
            $this->startDate = $request['start-date'];
        }

        if (count($request) > 0 && isset($request['end-date'])) {
            $this->endDate = $request['end-date'];
        }
    }

    public function headings(): array
    {
        return [
            'ID',
            'Company',
            'Career',
            'Sector',
            'Dates',
            'Location',
            'Company Code',
            'Address',
            'Website',
            'Job Mentor',
            'Backup Mentor',
            'HR Contact',
            'Applicants',
            'Days Per Shadow',
            'Arrival Time',
            'Collection Time',
            'Total Days',
        ];
    }

    public function map($job): array
    {
        $allPeriodDates = [];
        foreach ($job->availability as $key => $value) {
            if ($value) {
                $value = explode(',', str_replace(' ', '', $value));
                foreach($value as $date) {
                    if(\DateTime::createFromFormat('m/d/Y', $date) !== false) {
                        if($this->startDate && $this->endDate) {
                            if (Carbon::parse($date) >= Carbon::parse($this->startDate) && Carbon::parse($date) <= Carbon::parse($this->endDate)) {
                                $allPeriodDates[] = $date;
                            }
                        } elseif ($this->startDate) {
                            if (Carbon::parse($date) >= Carbon::parse($this->startDate)) {
                                $allPeriodDates[] = $date;
                            }
                        } elseif ($this->endDate) {
                            if (Carbon::parse($date) <= Carbon::parse($this->endDate)) {
                                $allPeriodDates[] = $date;
                            }
                        } else {
                            $allPeriodDates[] = $date;
                        }
                    }
                }
            }
        }
        $job->dates = implode(', ', $allPeriodDates);

        if (count($allPeriodDates) > 0) {
            return [
                $job->id,
                $job->company,
                $job->career_name,
                implode(', ', array_column($job->sectors->toArray(), 'name')),
                $job->dates,
                $job->location,
                $job->company_code,
                $job->address,
                $job->website,
                'Name: ' . ($job->job_mentor['name'] ?? '') . ' ' . PHP_EOL . 'Email: ' .
                ($job->job_mentor['email'] ?? '') . ' ' . PHP_EOL . 'Phone: ' . ($job->job_mentor['telephone'] ?? ''),
                'Name: ' . ($job->backup_job_mentor['name'] ?? '') . ' ' . PHP_EOL .
                'Email: ' . ($job->backup_job_mentor['email'] ?? '') . ' ' . PHP_EOL . 'Phone: ' .
                ($job->backup_job_mentor['telephone'] ?? ''),
                'Name: ' . ($job->hr_contact['name'] ?? '') . ' ' . PHP_EOL . 'Email: ' .
                ($job->hr_contact['email'] ?? '') . ' ' . PHP_EOL . 'Phone: ' . ($job->hr_contact['telephone'] ?? ''),
                $job->max_applicants,
                $job->days_per_job_shadow,
                $job->arrival_time,
                $job->collection_time,
                $job->total_days
            ];
        } else {
            return [];
        }

    }

    public function query()
    {
        return Job::leftJoin('careers', function($join) {
            $join->on('jobs.career_id', '=', 'careers.id');
        })
            ->where("company", "like", '%' . $this->search . '%')
            ->orWhere("location", "like", '%' . $this->search . '%')
            ->orWhere("careers.name", "like", '%' . $this->search . '%')
            ->orWhere("availability", "like", '%' . $this->search . '%')
            ->orWhereHas('sectors', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })->with('sectors')
            ->select(
                'jobs.id',
                'jobs.company',
                'careers.name as career_name',
                'jobs.location',
                'jobs.availability',
                'jobs.company_code',
                'jobs.address',
                'jobs.website',
                'jobs.job_mentor',
                'jobs.backup_job_mentor',
                'jobs.hr_contact',
                'jobs.max_applicants',
                'jobs.days_per_job_shadow',
                'jobs.arrival_time',
                'jobs.collection_time',
                'jobs.total_days'
                );
    }
}

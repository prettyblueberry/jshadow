<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'career_id',
        'description',
        'location',
        'location_id',
        'company',
        'company_code',
        'address',
        'website',
        'job_mentor',
        'backup_job_mentor',
        'hr_contact',
        'availability',
        'total_days',
        'days_per_job_shadow',
        'indemnity_file',
        'arrival_time',
        'collection_time',
        'max_applicants',
        'amount'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'job_mentor'        => 'array',
        'backup_job_mentor' => 'array',
        'hr_contact'        => 'array',
        'availability'      => 'array',
    ];

    /**
     * Get the location record associated with the job.
     */
    public function location()
    {
        return $this->belongsTo('App\Location');
    }

    /**
     * Get the career record associated with the job.
     */
    public function career()
    {
        return $this->belongsTo('App\Career');
    }

    /**
     * Get the sectors for the job.
     */
    public function sectors()
    {
        return $this->belongsToMany('App\Sector')->withTimestamps();
    }


}

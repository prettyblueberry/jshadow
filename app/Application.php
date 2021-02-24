<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 
        'sector',
        'sector_id',
        'career', 
        'company_code', 
        'job_id', 
        'location_id', 
        'location',
        'dates',
        'payment_id',
        'paid',
        'indemnity_file',
        'voucher_id',
        'amount'
    ];

    /**
     * Get the location record associated with the application.
     */
    public function location()
    {
        return $this->belongsTo('App\Location');
    }

    /**
     * Get the job record associated with the application.
     */
    public function job()
    {
        return $this->belongsTo('App\Job');
    }

    /**
     * Get the job record associated with the application.
     */
    public function sector()
    {
        return $this->belongsTo('App\Sector');
    }

    /**
     * Get the user record associated with the application.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the voucher record associated with the application.
     */
    public function voucher()
    {
        return $this->belongsTo('App\Voucher');
    }
}
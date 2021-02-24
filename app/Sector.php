<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = [
        'name'
    ];

    /**
     * Set the sector.
     *
     * @param  string  $value
     * @return void
     */
    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }

    /**
     * Get the sectors for the job.
     */
    public function jobs()
    {
        return $this->belongsToMany('App\Job')->withTimestamps();
    }

    /**
     * Get the sectors for the job.
     */
    public function careers()
    {
        return $this->belongsToMany('App\Career')->withTimestamps();
    }
}

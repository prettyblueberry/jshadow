<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'city',
        'province',
        'country',
        'created_at',
        'updated_at'
    ];

     /**
     * Get the sectors for the job.
     */
    public function jobs()
    {
        return $this->HasMany('App\Job');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get the sector for the career.
     */
    public function sectors()
    {
        return $this->belongsToMany('App\Sector')->withTimestamps();
    }

    /**
     * Get the jobs for the career.
     */
    public function jobs()
    {
        return $this->hasMany('App\Job');
    }
}

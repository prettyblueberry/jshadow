<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'contact_no',
        'id_no',
        'id_path',
        'profile_photo',
        'guardian_name',
        'guardian_contact_no',
        'guardian_email',
        'guardian_id_no',
        'school_of_attendance',
        'school_type',
        'career_interests',
        'dietary_requirements',
        'tc_accepted'
    ];

    /**
     * Get the applications user profile data.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}

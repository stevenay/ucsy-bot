<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable
        = [
            'fb_id',
            'name',
            'roll_no',
            'gender',
            'language',
            'last_seen_date'
        ];

    public static function createFromIncomingMessage(\BotMan\Drivers\Facebook\Extensions\User $user)
    {
        User::updateOrCreate(['fb_id' => $user->getId()], [
            'fb_id'          => $user->getId(),
            'name'           => $user->getFirstName() . $user->getLastName(),
            'gender'         => $user->getGender(),
            'profile_pic'    => $user->getProfilePic(),
            'language'       => 'zaw',
            'last_seen_date' => date('Y-m-d H:i:s')
        ]);
    }


}

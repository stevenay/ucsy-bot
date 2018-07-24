<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 3/11/2018
 * Time: 7:28 PM
 */

namespace App\Middleware;

use App\User;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Interfaces\Middleware\Heard;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

/*
 * Available Facebook user profile information
{
    "first_name": "Peter",
  "last_name": "Chang",
  "profile_pic": "https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xpf1/v/t1.0-1/p200x200/13055603_10105219398495383_8237637584159975445_n.jpg?oh=1d241d4b6d4dac50eaf9bb73288ea192&oe=57AF5C03&__gda__=1470213755_ab17c8c8e3a0a447fed3f272fa2179ce",
  "locale": "en_US",
  "timezone": -7,
  "gender": "male",
  "last_ad_referral": {
    "source": "ADS",
    "type": "OPEN_THREAD",
    "ad_id": "6045246247433"
  }
}*/

class UserRecording implements Heard
{
    /**
     * Handle an incoming message.
     *
     * @param IncomingMessage $message
     * @param callable $next
     * @param BotMan $bot
     *
     * @return mixed
     */
    public function heard(IncomingMessage $message, $next, BotMan $bot)
    {
        $user = $bot->getUser();

        // if we can get the user from the incoming message,
        // we saved it.
        if ($user instanceof \BotMan\Drivers\Facebook\Extensions\User) {
            User::createFromIncomingMessage($user);
        }

        return $next($message);
    }
}
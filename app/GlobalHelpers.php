<?php

use App\User;
use BotMan\Drivers\Facebook\Extensions\ButtonTemplate;
use BotMan\Drivers\Facebook\Extensions\ElementButton;

/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 7/22/2018
 * Time: 12:55 AM
 */

function getEnglishNumber($text)
{
    $conversion = [
        "ဝ" => 0, // Myanmar letter "wa" sometimes used as zero
        "၀" => 0,
        "၁" => 1,
        "၂" => 2,
        "၃" => 3,
        "၄" => 4,
        "၅" => 5,
        "၆" => 6,
        "၇" => 7,
        "၈" => 8,
        "၉" => 9,
        "႐" => 0,
        "႑" => 1,
        "႒" => 2,
        "႓" => 3,
        "႔" => 4,
        "႕" => 5,
        "႖" => 6,
        "႗" => 7,
        "႘" => 8,
        "႙" => 9
    ];

    $englishNumbersOnly = strtr($text, $conversion);
    return $englishNumbersOnly;
}

function getDomainName($url)
{
    $pieces = parse_url($url);
    $domain = isset($pieces['host']) ? $pieces['host'] : $pieces['path'];
    if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
        return $regs['domain'];
    }

    return false;
}

function getCurrentPerson($bot)
{
    $user = $bot->getUser();
    if ($user instanceof \BotMan\Drivers\Facebook\Extensions\User) {
        $person = User::where('fb_id', $user->getId())->first();
        if ($person instanceof User) {
            return $person;
        }
    }

    return null;
}

function IsBotInStopState($bot)
{
    if ($currentUser = getCurrentPerson($bot)) {
        if (Cache::has($currentUser->fb_id)) {
            if ($bot->getDriver()->isPostback()) {
                \Illuminate\Support\Facades\Log::debug("Is Postback");
                $text = "လူႀကီးမင္းက UCSY admin ေတြနဲ႔ စကားေျပာမယ္ဆိုလို႔ Chatbot ကို ခဏ ရပ္ထားပါတယ္။ Chatbot ကို ျပန္သုံးျခင္တယ္ဆိုရင္ ေအာက္က \"stop live chat\" ခလုတ္ေလးကို ႏွိပ္လိုက္ပါဗ်။";

                $buttonTemplate = ButtonTemplate::create($text)
                    ->addButton(ElementButton::create('Stop Live Chat')
                        ->type('postback')
                        ->payload('stop live chat'));

                $bot->reply($buttonTemplate);
            }

            return true;
        }

        return false;
    }
}
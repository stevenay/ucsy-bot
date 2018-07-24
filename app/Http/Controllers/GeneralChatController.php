<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use BotMan\Drivers\Facebook\Extensions\ButtonTemplate;
use BotMan\Drivers\Facebook\Extensions\ElementButton;

class GeneralChatController
{
    public function handleIntroduction(BotMan $bot)
    {
        if (IsBotInStopState($bot))
            return;

        // check existing user
        $user = getCurrentPerson($bot);
        if ($user) {
            return (empty($user->roll_no)) ? $this->handleNoStudentIntro($bot) : $this->handleStudentIntro($bot);
        }

        $this->decideWhetherStudent($bot);
    }

    /***
     * @param BotMan $bot
     *
     * Decide whether the user is student or not.
     */
    public function decideWhetherStudent(Botman $bot)
    {
        if (IsBotInStopState($bot))
            return;

        // building 'Yes', 'No' quick replies
        $yesNo         = ['Yes', 'No'];
        $quick_replies = array_map(function ($item) {
            return [
                'content_type' => 'text',
                'title'        => $item,
                'payload'      => $item . '_whether_student',
            ];

        }, $yesNo);

        // asking whether student
        $bot->reply("ဟုတ္ကဲ့ မဂၤလာပါ။ UCSY ေက်ာင္းေတာ္ၿကီးမွ ၿကိုဆိုပါတယ္။");
        $bot->reply('ခု စကားလာေျပာတဲ့ လူၿကီးမင္းက UCSY က ေက်ာင္းသား/သူ ပါလား ခင္ဗ်ာ။ Yes , No ေလးေျဖေပးပါဦးဗ်။ 😃', ['message' => [
            'quick_replies' => json_encode($quick_replies)
        ]]);
    }

    /***
     * @param BotMan $bot
     *
     * To reply introduction to non-students
     */
    public function handleNoStudentIntro(Botman $bot)
    {
        if (IsBotInStopState($bot))
            return;

        $bot->reply("ဟုတ္ကဲ့ မဂၤလာပါ။");
        $bot->reply(ButtonTemplate::create('ကၽြန္ေတာ္ကေတာ့ UCSY Chatbot ပါ ခင္ဗ်။ ကြီးတို႔၊ မြီးတို႔ လိုအပ္တာေတြကို ကူညီဖို႔ အသင့္ပါဘဲ ခင္ဗ်ာ။ 😃')
            ->addButton(ElementButton::create('📄 သင္ရိုးညႊန္းတမ္း')->type('postback')->payload('academic'))
            ->addButton(ElementButton::create('🚀 ေက်ာင္းေလၽွာက္မယ္')->type('postback')->payload('admission_requirement'))
            ->addButton(ElementButton::create('👦 Admin နဲ႔ ေျပာမယ္')->type('postback')->payload('chat_with_admin'))
        );
    }

    /***
     * @param BotMan $bot
     *
     * To reply introduction to non-students
     */
    public function handleStudentIntro(Botman $bot)
    {
        if (IsBotInStopState($bot))
            return;

        $bot->reply("ဟုတ္ကဲ့ {$this->getGreetingInBurmese()}ေလး ပါ။");
        $bot->reply(ButtonTemplate::create('ကၽြန္ေတာ္ကေတာ့ UCSY Chatbot ပါ ခင္ဗ်။ ကြီးတို႔၊ မြီးတို႔ လိုအပ္တာေတြကို ကူညီဖို႔ အသင့္ပါဘဲ ခင္ဗ်ာ။ 😃')
            ->addButton(ElementButton::create('📄 အခ်ိန္ဇယား')->url(' https://920b1e5e.ngrok.io/timetable')
                ->heightRatio(ElementButton::RATIO_TALL)
                ->enableExtensions())
            ->addButton(ElementButton::create('🚀 Campus လမ္းညႊန္')->type('postback')->payload('campus_guide'))
            ->addButton(ElementButton::create('👦 Admin နဲ႔ ေျပာမယ္')->type('postback')->payload('chat_with_admin'))
        );
    }

    /***
     * @param BotMan $bot
     *
     * To reply Campus Guide
     */
    public function handleCampusGuide(Botman $bot)
    {
        if (IsBotInStopState($bot))
            return;

        $bot->reply("Develop လုပ္ေနဆဲ ျဖစ္သည့္အတြက္ ယခု လုပ္ေဆာင္ခ်က္ကို မရရွိေသးပါဘူးဗ်။ 😓");
    }

    public function getGreetingInBurmese()
    {
        /* This sets the $time variable to the current hour in the 24 hour clock format */
        $time = date("H");

        $greeting = "ေကာင္းေသာ အခ်ိန္";

        /* If the time is less than 1200 hours, show good morning */
        if ($time < "12") {
            $greeting = "ေကာင္းေသာ မနက္ခင္း";
        } else {
            /* If the time is grater than or equal to 1200 hours, but less than 1700 hours, so good afternoon */
            if ($time >= "12" && $time < "17") {
                $greeting = "ေကာင္းေသာ ေန႔လည္ခင္း";
            } else
                /* Should the time be between or equal to 1700 and 1900 hours, show good evening */
                if ($time >= "17" && $time < "19") {
                    $greeting = "ေကာင္းေသာ ညေနခင္း";
                } else
                    /* Finally, show good night if the time is greater than or equal to 1900 hours */
                    if ($time >= "19") {
                        $greeting = "ေကာင္းေသာ ည";
                    }
        }

        return $greeting;

    }
}

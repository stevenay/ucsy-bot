<?php

use App\Http\Controllers\GeneralChatController;
use BotMan\Drivers\Facebook\Extensions\ButtonTemplate;
use BotMan\Drivers\Facebook\Extensions\ElementButton;

$botman = resolve('botman');
$botman->hears('Hi|Hello|GET_STARTED|Get Started', GeneralChatController::class . '@decideWhetherStudent');

$botman->middleware->heard(new \App\Middleware\UserRecording());
$botman->hears("Student", GeneralChatController::class . '@decideWhetherStudent');

$botman->hears('Yes_whether_student', function ($bot) {
    $bot->startConversation(new \App\Conversations\StudentLogInConversation());
});

$botman->hears('No_whether_student', GeneralChatController::class . '@handleNoStudentIntro');
$botman->hears('campus_guide', GeneralChatController::class . '@handleCampusGuide');

// Chat with Admin
$botman->hears('chat_with_admin', \App\Http\Controllers\HumanHandoverProtocol::class . '@handleChatWithAPerson');
$botman->hears('Stop live chat([0-9]+)?', \App\Http\Controllers\HumanHandoverProtocol::class . '@handleStopLiveChat');

// General Conversation
$botman->hears('who are you', function ($bot) {
    if (IsBotInStopState($bot))
        return;

    $bot->types();
    $bot->reply("ကၽြန္ေတာ္ကေတာ့ UCSY Chatbot ပါ ခင္ဗ်။ 😄");
});

$botman->hears('how old are you', function ($bot) {
    if (IsBotInStopState($bot))
        return;

    $bot->reply("အသက္လား??? ကြ်န္ေတာ္ေမြးတာ ရက္ပိုင္းဘဲ ရိွေသးတယ္။ 😁");
});

// This fallback also handles with Wit.ai
$botman->fallback(\App\Http\Controllers\FallbackController::class."@askToWit");

<?php

namespace App\Http\Controllers;

use App\User;
use BotMan\BotMan\BotMan;
use BotMan\Drivers\Facebook\Extensions\ButtonTemplate;
use BotMan\Drivers\Facebook\Extensions\ElementButton;
use BotMan\Drivers\Facebook\FacebookDriver;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class HumanHandoverProtocol extends Controller
{
    public function handleStopLiveChat ($bot, $fb_user_id = null)
    {
        // click from admin
        if ($fb_user_id) {
            // retrieve fullname
            $user   = User::where('fb_id', $fb_user_id);
            $fullname = ($user instanceof User) ? $user->name : "";

            // there is no human chat session with this fb_user_id
            if (!Cache::has($fb_user_id)) {
                $bot->reply("Chat with the user " . $fullname . "is already stopped.");
            } else {
                Cache::forget($fb_user_id);
                $bot->reply("Chat with the user " . $fullname . "is stopped.");
            }

        } else {
            // click from user
            if ($user = getCurrentPerson($bot)) {

                if (!Cache::has($user->fb_id)) {
                    $bot->reply("အခုလက္ရွိ ဘယ္ Admin နဲ႔မွ Live Chat session မဖြင့္ထားပါဘူးဗ်။ Stop စရာမလိုပါဘူး။ UCSY ChatBot ကို စိတ္ႀကိဳက္သုံးလို႔ရပါတယ္။");

                } else {
                    Cache::forget($user->fb_id);
                    $bot->reply("Admin ေတြနဲ႔ စကားေျပာရတာ အဆင္ေျပမယ္လို႔ ေမွ်ာ္လင့္ပါတယ္။ အခုကြၽန္ေတာ္ UCSY Chatbot က ကြီးတို႔ မြီးတို႔ အတြက္ျပန္ေရာက္လာပါၿပီ။ Hooo!!! 😊");

                }
            }
        }
    }

    /**
     * @param BotMan $bot
     */
    public function handleChatWithAPerson(BotMan $bot)
    {
        Log::debug("Chat with a person");
        if (IsBotInStopState($bot))
            return;

        $bot->types();

        // Check is there any active admins
        $isAdminActive = Cache::get('admin-active');
        if ($isAdminActive === 'true') {
            // is admin online
            $bot->reply("ဟုတ္ကဲ့။ ႀကိဳဆိုပါတယ္ဗ်။ UCSY admin team ကိုစၿပီးေခါင္းစားလို႔ရပါၿပီခင္ဗ်။ ?");
        } else {
            // is admin offline
            $bot->reply("UCSY က admin ဘိုးေတာ္ေတြ/ဘြားေတာ္ေတြ Online မွာရွိမေနဘူးခင္ဗ်။ စာေလးပဲခ်န္ထားခဲ့ပါခင္ဗ်၊ သူတို႔ေၾကာ္စားပါလိမ့္မယ္ :D

UCSY ရဲ႕  admin team ကလူႀကီးမင္းကို ျပန္လည္ဆက္သြယ္ပါလိမ့္မယ္ခင္ဗ်။");

            // give hint to reuse Chatbot
            $text = "အကယ္လို႔ Chatbot ကို ျပန္သုံးျခင္တယ္ဆိုရင္ ေအာက္က \"stop live chat\" ခလုတ္ေလးကို ႏွိပ္လိုက္ပါဗ်။ Stop live chat လို႔ ရိုက္လိုက္လည္း ရပါတယ္။";
            $buttonTemplate = ButtonTemplate::create($text)
                ->addButton(ElementButton::create('Stop Live Chat')
                    ->type('postback')
                    ->payload('stop live chat'));

            $bot->reply($buttonTemplate);
        }

        $this->handleHumanHandover($bot);
    }

    public function handleHumanHandover(BotMan $bot)
    {
        if ($user = getCurrentPerson($bot)) {
            // 16 hour cache
            Cache::put($user->fb_id, 'human handover', 60 * 16);

            // Send alert message to admin
//            $bot->say(ButtonTemplate::create('Hello admin, ' . $user->full_name . " want to chat with you.\nTo answer the user go to https://www.facebook.com/MyanExchange-535399726812516/inbox or use Facebook Pages mobile app. \n\nIf you finish chatting with him, you can click on \"Stop Chat\".")
//                ->addButton(ElementButton::create('Stop Live Chat')
//                    ->type('postback')
//                    ->payload('stop live chat' . $user->fb_id)),
//                '1945370465491285',
//                FacebookDriver::class);
        }

        // $bot->handover();
    }
}

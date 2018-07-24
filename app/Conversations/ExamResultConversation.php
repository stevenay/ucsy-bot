<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 7/23/2018
 * Time: 2:51 PM
 */

namespace App\Conversations;


use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\Drivers\Facebook\Extensions\ButtonTemplate;
use BotMan\Drivers\Facebook\Extensions\ElementButton;

class ExamResultConversation extends Conversation
{
    protected $rollNo;

    public function askRollNo()
    {
        $this->ask('ခံုနံပါတ္ေလး ရိုက္ထည့္ေပးပါဦးဗ်။ 😊', function(Answer $answer) {
            // Save result
            $this->rollNo = getEnglishNumber($answer->getText());

            // TODO Check with the database
            if ($this->rollNo === "123") {
                $this->say("ဟုိး!!! 👏👏👏");
                $this->say("ေအာင္ပါတယ္။ ဂုဏ္ယူပါတယ္ဗ်။");

            } else {
                $this->say("ခံုနံပါတ္ကို ေအာင္စာရင္းထဲမွာ မေတြ႕ဘူးဗ်။");
                $this->say("ခံုနံပါတ္ကိုရိုက္တာ မွားေနလား စစ္ၾကည့္ပါဦး။");
                $this->say("ေၾကြးက်န္လည္း တခ်စ္စစ္ၾကည့္ပါဦး။");

            }

            $this->say(ButtonTemplate::create('ေနာက္ခံုနံပါတ္တစ္ခုရဲ႕ ေအာင္စာရင္းကို ထပ္ၾကည့္ဦးမယ္ဆိုရင္ ေအာက္က Exam Result ဆိုတဲ့ ခလုတ္ေလးကို ႏွိပ္လို႔ ရပါတယ္။')
                ->addButton(ElementButton::create('🎓 Exam Result')->type('postback')->payload('exam result'))
            );

        });
    }

    /**
     * @return mixed
     */
    public function run()
    {
        $this->askRollNo();
    }
}
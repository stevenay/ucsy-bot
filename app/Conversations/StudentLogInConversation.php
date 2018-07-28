<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 7/21/2018
 * Time: 4:51 PM
 */

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\BotMan;

/**
 * Class StudentLogInConversation
 * @package App\Conversations
 *
 * Conversation to handle first time Student messaging with ChatBot
 */
class StudentLogInConversation extends Conversation
{
    protected $rollNo;
    protected $fullName;

    public function askRollNo()
    {
        $this->ask('ဒါဆို ခံုနံပါတ္ေလး ရိုက္ထည့္ေပးပါဦးဗ်။', function(Answer $answer) {
            // Save result
            $this->rollNo = $answer->getText();

            $this->say('ဟုတ္ကဲ့။');
            $this->askFullName();
        });
    }

    public function askFullName()
    {
        $this->ask('ေနာက္ဆံုးအေနနဲ႔ နာမည္ကို English လို အျပည့္အစံု ေရးေပးပါဦးေနာ္။', function(Answer $answer) {
            // Save result
            $this->fullName = $answer->getText();

            // TODO
            // Check with database
            // Best to implement with Account Linking Example
            // if ($this->rollNo === '11' and strtolower($this->fullName) === 'nay lin aung')
            $this->say("ေက်းဇူးပါ။ မွတ္ထားလိုက္ပါၿပီ ခံုနံပါတ္ $this->rollNo နဲ႔ $this->fullName ေရ။ ဒါေလာက္ဆို လံုေလာက္ပါၿပီ။ ");

            // resolve Botman
            $botman = resolve('botman');

            // save into the database
            $user = getCurrentPerson($botman);
            $user->roll_no = $this->rollNo;
            $user->name = $this->fullName;
            $user->save();

            $generalChatController = resolve('App\Http\Controllers\GeneralChatController');
            $generalChatController->handleStudentIntro($botman);
        });
    }

    public function run()
    {
        // This will be called immediately
        $this->askRollNo();
    }
}
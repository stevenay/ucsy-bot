<?php

namespace App\Http\Controllers;

use App\User;
use BotMan\Drivers\Facebook\FacebookDriver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    public function sendMessage(Request $request)
    {
        // validation
        $request->validate([
            'audience'   => 'required',
            'message_text' => 'required'
        ]);

        // retrieve info from Request
        $condition = $request->input('audience') === 'student' ? 'whereNotNull' : 'whereNull';
        $users     = User::$condition('roll_no')->get();

        if ($users === null)
            return response()->json([
                // Todo translation
                'message' => 'Valid user not found.'
            ], 404);

        $botman  = app('botman');
        $message = $request->input('message_text');

        $numberOfPeopleSent = $users->filter(function ($user, $key) use ($botman, $message) {
            // send originating message
            try {
                $botman->say($message, $user->fb_id, FacebookDriver::class);
                return true;

            } catch (\Exception $e) {
                Log::error("Cannot sent to fb_id " . $user->fb_id);
                return false;
            }
        });

        // send Success Message
        return response()->json([
            // Todo trans
            'message' => 'Message has been sent to total number of recipients: ' . $numberOfPeopleSent->count() . "."
        ], 201);
    }
}

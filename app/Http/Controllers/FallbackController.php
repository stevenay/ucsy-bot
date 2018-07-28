<?php

namespace App\Http\Controllers;

use App\Wit\ResponseProcessor;
use BotMan\BotMan\BotMan;
use BotMan\Drivers\Facebook\Extensions\ButtonTemplate;
use BotMan\Drivers\Facebook\Extensions\ElementButton;
use GuzzleHttp\Client;
use SteveNay\MyanFont\MyanFont;

/**
 * Class FallbackController
 * @package App\Http\Controllers
 *
 * To handle messages that are out of control of Rules
 */
class FallbackController extends Controller
{
    /**
     * @param BotMan $bot (auto inject from Framework)
     *
     * Send messages to Wit.ai
     */
    public function askToWit(BotMan $bot)
    {
        if (IsBotInStopState($bot))
            return;

        $message = $bot->getMessage()->getText();

        try {
            $witResponse = $this->calltoWit($message);
            $nlg         = new ResponseProcessor($witResponse);

            $this->replyFromResponse($bot, $nlg->getResponse());

        } catch (RequestException $e) {
            Log::debug($e->getMessage());

            $bot->reply("Something went wrong.");
        }
    }

    /***
     * @param BotMan $bot
     * @param array $response
     *
     * Botman reply from NLG response object
     */
    public function replyFromResponse(BotMan $bot, $response)
    {
        if (empty($response)) {
            $this->replyDontUnderstand($bot);
            return;
        }

        // if Wit has answer
        switch ($response["type"]) {
            case "text":
                $bot->reply($response["response"]);
                break;
            case "conversation":
                // TODO Exception Handling
                $conversation = resolve("\App\Conversations\\" . $response["response"]);
                $bot->startConversation($conversation);
                break;
        }
    }

    /**
     * @param $message
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * Make network call to Wit.ai
     */
    public function callToWit($message)
    {
        // convert message to uni first
        // because wit.ai model is trained by Unicode Encoding
        if (MyanFont::fontDetect($message) === "zawgyi") {
            $message = MyanFont::zg2uni($message);
        }

        $client = new Client([
            'base_uri' => 'https://api.wit.ai',
            'timeout'  => 300.0,
        ]);

        // Call Rest api
        // Http Variables, Add-on Path, Headers Variables
        // and Query Variables
        $response = $client->request('GET', 'message', [
            'headers' => [
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer NOON3X5LRL2GHHBTYUKGBSR5Q3UG6735',
            ],
            'query'   => [
                'v' => '20180723',
                'q' => $message,
            ]
        ]);

        // convert response to object
        $witResponse = \GuzzleHttp\json_decode($response->getBody());

        return $witResponse;
    }

    /**
     * @param BotMan $bot
     *
     * To handle the messages that Wit.ai also don't understand to resolve
     */
    public function replyDontUnderstand(Botman $bot)
    {
        $bot->reply("ဝမ္းနည္းပါတယ္။ ကၽြန္ေတာ္ကေတာ့ မူၿကိုအဆင့္ဘဲ ရွိေသးလို႔ ကြီးတို႔၊ မြီးတို႔ ေျပာတာအကုန္ေတာ့ နားမလည္ေသးပါဘူးဗ်။");
        $bot->reply(ButtonTemplate::create('UCSY က Admin ကိုႀကီး၊ မႀကီးေတြနဲ႔ ေျပာလို႔ ရပါတယ္။ 😃')
            ->addButton(ElementButton::create('👦 Admin နဲ႔ ေျပာမယ္')->type('postback')->payload('chat_with_admin'))
        );
    }
}

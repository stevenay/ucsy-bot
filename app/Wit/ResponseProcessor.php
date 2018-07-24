<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2/8/2018
 * Time: 10:18 PM
 */

namespace App\Wit;

use App\Helpers;
use App\Location;
use Illuminate\Support\Facades\Log;

/**
 * Natural Language Generator Processing Unit
 *
 * Class ResponseProcessor
 * @package App\Nlp
 */
class ResponseProcessor
{
    private $nluResponse;
    private $receivedEntities;

    public function __construct($nluResponse)
    {
        $this->nluResponse      = $nluResponse;
        $this->receivedEntities = $nluResponse->entities;
    }

    public function getResponse()
    {
        // check is there any intent
        if ($intent = $this->getMostPossibleIntent()) {
            // then handle the intent
            $response = $this->handleIntent($intent);
        } else {
            // if there is no any intent
            // ask what intent they do want to do
            $response = $this->estimateIntent();
        }

        return $response;
    }

    /***
     * @param $intent
     * @return array
     */
    public function handleIntent($intent)
    {
        $response = [];

        Log::debug("Intent " . $intent->value);

        // TODO Refactor - Strategy Pattern
        if ($intent->value == "exam_result") {
            $result = $this->checkRequiredEntities($intent);
            if ($result) {
                // other response = လာမယ့္ ၂၅ ဇူလိုင္ ေန႔မွာ Tutorial ရိွပါမယ္။ စာေတြၿကိုက်က္ထားၾကပါ။
                // Check with Student ID (roll no)
                $response = [
                    "type" => "conversation",
                    "response" => "ExamResultConversation"
                ];

            }
        } else if ($intent->value == "tutorial_date") {
            $result = $this->checkRequiredEntities($intent);
            if ($result) {
                // other response = လာမယ့္ ၂၅ ဇူလိုင္ ေန႔မွာ Tutorial ရိွပါမယ္။ စာေတြၿကိုက်က္ထားၾကပါ။
                // Check with Student ID (roll no)
                $response = [
                    "type" => "text",
                    "response" => "လက္တေလာ Tutorial မရွိေသးပါဘူး။ "
                ];

            }

        }

        return $response;
    }

    /**
     * Without any Intent, only the Entities are given.
     */
    public function estimateIntent()
    {
        foreach ($this->receivedEntities as $entityName => $entity) {
            if ($entityName == 'sample') {
                // save into the session
            } else {
                // other intent estimation
            }
        }
    }

    /***
     * @param $intent
     * @return EntityResult|bool
     *
     * Some intent has to have the specific entities to be processed
     * This method try to check that
     */
    public function checkRequiredEntities($intent)
    {
        // unknown intent to be checked by required entities
        return true;
    }

    private function getMostPossibleEntity($entityName)
    {
        if ($entities = $this->receivedEntities->$entityName) {
            return $this->maxAttributeInArray($entities, 'confidence');
        }

        return false;
    }

    private function getMostPossibleIntent()
    {
        // check is there any Intents in the natural language understanding
        if ($intents = $this->getIntents()) {
            return $this->maxAttributeInArray($intents, 'confidence');
        }

        return false;
    }

    private function getIntents()
    {
        $nluResponse = $this->nluResponse;

        // check response has entities
        $entities = is_null($nluResponse->entities) ? [] : (array)$nluResponse->entities;

        // if there is entities
        // we check is there any intents
        if (!empty($entities)) {
            if (array_key_exists('intent', $entities)) {
                return $entities['intent'];
            }
        }

        return false;
    }

    private function maxAttributeInArray($array, $prop)
    {
        $result = array_reduce($array, function ($a, $b) use ($prop) {
            return $a ? ($a->$prop > $b->$prop ? $a : $b) : $b;
        });

        return $result;
    }

    public function prepareResponse($message, $quickReplies = [], $secondMessage = "")
    {
        $response = ['chat_message' => [
            'message'        => $message,
            'second_message' => $secondMessage,
            'quick_replies'  => $quickReplies]
        ];

        return $response;
    }


}
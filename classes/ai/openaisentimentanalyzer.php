<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Coursesentiment - Sentiment Analysis AWS implementation
 *
 * This file is part of the block_coursesentiment.
 * Implements the interface for analyzing forum sentiment.
 *
 * @package    block_coursesentiment
 * @copyright  2024 Antoni Bertran <antoni@tresipunt.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_coursesentiment\ai;

defined('MOODLE_INTERNAL') || die;

/**
 * AWS Comprehend Implementation
 */
/**
 * OpenAI Implementation
 */
class openaisentimentanalyzer extends sentimentanalyzer implements sentimentanalyzerinterface {
    private $apiKey;

    public function __construct() {
        global $CFG;
        require_once($CFG->dirroot . '/blocks/coursesentiment/settings.php');

        $this->apiKey = get_config('block_coursesentiment', 'openai_api_key');

        // We fetch defaults for both chat and assistant APIs, even though only one can be active at a time
        // In the past, multiple different completion classes shared API types, so this might happen again
        // Any settings that don't apply to the current API type are just ignored

        $this->prompt = $this->get_setting('prompt', get_string('defaultprompt', 'block_openai_chat'));
        $this->assistantname = $this->get_setting('assistantname', get_string('defaultassistantname', 'block_openai_chat'));
        $this->username = $this->get_setting('username', get_string('defaultusername', 'block_openai_chat'));

        $this->temperature = $this->get_setting('temperature', 0.5);
        $this->maxlength = $this->get_setting('maxlength', 500);
        $this->topp = $this->get_setting('topp', 1);
        $this->frequency = $this->get_setting('frequency', 1);
        $this->presence = $this->get_setting('presence', 1);

        $this->assistant = $this->get_setting('assistant');
        $this->instructions = $this->get_setting('instructions');
    }


    /**
     * Attempt to get the saved value for a setting; if this isn't set, return a passed default instead
     * @param string settingname: The name of the setting to fetch
     * @param mixed default_value: The default value to return if the setting isn't already set
     * @return mixed: The saved or default value
     */
    protected function get_setting($settingname, $default_value = null) {
        $setting = get_config('block_coursesentiment', $settingname);
        if (!$setting && (float) $setting != 0) {
            $setting = $default_value;
        }
        return $setting;
    }

    public function analyze_sentiment(array $messages): array {
        $results = [];
        foreach ($messages as $msg) {

            $language = $msg['language'] ? ('Language: ' . $msg['language']) : 'Detect the Message Language';
            $prompt = 'Analyze the sentiment of this message (in language "' . $language . '""),
             ' . $this->buildmessage($msg) . '
             
             Return as JSON with Sentiment and SentimentScore, Detailed JSON object is: 
            ``{
                "Sentiment": "string",
                "SentimentScore": {
                    "Mixed": number,
                    "Negative": number,
                    "Neutral": number,
                    "Positive": number
                }
            }``';

            $response = $this->make_api_call($prompt);
            $result = false;
            if ($response->id !== 'error') {
                $result = json_decode($response->message);
            }

            $results[] = [
                    'id' => $msg['id'],
                    'subject' => $msg['subject'],
                    'message' => $msg['message'],
                    'sentiment' => $result ? $result['Sentiment'] : '',
                    'sentiment_score' => $result ? $result['SentimentScore'] : ''
            ];
        }
        return $results;
    }


    /**
     * Make the actual API call to OpenAI
     * @return JSON: The response from OpenAI
     */
    private function make_api_call($prompt) {
        $curlbody = [
                "model" => $this->model,
                "prompt" => $prompt,
                "temperature" => (float) $this->temperature,
                "max_tokens" => (int) $this->maxlength,
                "top_p" => (float) $this->topp,
                "frequency_penalty" => (float) $this->frequency,
                "presence_penalty" => (float) $this->presence,
                "stop" => $this->username . ":"
        ];

        $curl = new \curl();
        $curl->setopt(array(
                'CURLOPT_HTTPHEADER' => array(
                        'Authorization: Bearer ' . $this->apikey,
                        'Content-Type: application/json'
                ),
        ));

        $response = $curl->post("https://api.openai.com/v1/chat/completions", json_encode($curlbody));
        $response = json_decode($response);

        $message = null;
        if (property_exists($response, 'error')) {
            $message = 'ERROR: ' . $response->error->message;
        } else {
            $message = $response->choices[0]->message->content;
        }

        return [
                "id" => property_exists($response, 'id') ? $response->id : 'error',
                "message" => $message
        ];
    }
}

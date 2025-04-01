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
 * OpenAI Implementation
 */
class openaisentimentanalyzer extends sentimentanalyzer implements sentimentanalyzerinterface {
    private $apiKey;
    private $model;
    private $temperature;
    private $topp;
    private $frequency;
    private $presence;

    private $prompt;
    private $username;

    public function __construct() {

        $config = get_config('block_coursesentiment');

        $this->apiKey = $config->openai_apikey;
        $this->model = $config->openai_model;
        $this->username = $config->username;

        // We fetch defaults for both chat and assistant APIs, even though only one can be active at a time
        // In the past, multiple different completion classes shared API types, so this might happen again
        // Any settings that don't apply to the current API type are just ignored

        $this->temperature = $this->get_setting('openai_temperature', 0.8);
        $this->maxlength = $this->get_setting('openai_maxlength', 500);
        $this->topp = $this->get_setting('openai_topp', 1);
        $this->frequency = $this->get_setting('openai_frequency', 1);
        $this->presence = $this->get_setting('openai_presence', 1);

        $this->debugmessage = !empty($config->debugmessage);

        $this->prompt = 'You are an expert in analyzing the sentiment of forum messages in online learning environments.

Given a forum message from a course discussion, determine the overall sentiment and provide detailed sentiment scores.

If you detect more than onle language normalize it please

Return only a valid JSON object in the following format:

{
  "Sentiment": "Positive | Negative | Neutral | Mixed",
  "SentimentScore": {
    "MIXED": number with 2 decimals between 0 and 1,
    "NEGATIVE": number with 2 decimals between 0 and 1,
    "NEUTRAL": number with 2 decimals between 0 and 1,
    "POSITIVE": number with 2 decimals between 0 and 1
  },
  "Language": string with the main language "ca" or "es-ES" or "en"
}

If the sentiment cannot be determined or an error occurs, return:

{
  "error": "Brief description of the issue"
}

Do not include any additional explanation or commentary. Return only the JSON. Do not include "```json" or "```" only a valid JSON to do a decode with php using json_decode(content, true)';
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

            $start = microtime(true);
            $txt = strip_tags($msg['message']);

            $language = $msg['language'] ?? null;


            $response = $this->make_api_call($msg);
            if ($response['id'] !== 'error') {
                $result = $response['message'];
                $language = $result['Language'];
                if (is_array($language)) {
                    $language = array_pop($language);
                }
                $results[] = $this->add_results($msg, $txt, $language, $result, $start);
            } else {
                $results[] = $this->add_error_results($msg, $language, $start, $response['message']);
            }

        }
        $this->store_logs($results);
        return $results;
    }


    /**
     * Make the actual API call to OpenAI
     * @return JSON: The response from OpenAI
     */
    private function make_api_call($msg) {
        $history_json = [];
        array_push($history_json, ["role" => "system", "content" => $this->prompt]);
        // TODO add source of sourceoftruth
        // array_push($history_json, ["role" => "system", "content" => $this->sourceoftruth]);

        array_push($history_json, ["role" => "user", "content" => $this->buildmessage($msg)]);

        $curlbody = [
                "model" => $this->model,
                "messages" => $history_json,
                "temperature" => (float) $this->temperature,
                "max_tokens" => (int) $this->maxlength,
                "top_p" => (float) $this->topp,
                "frequency_penalty" => (float) $this->frequency,
                "presence_penalty" => (float) $this->presence,
                "stop" => $this->username . ":"
        ];
        $this->logsentimentmessage('Block block_coursesentiment analyze_sentiment for body: ' . print_r($curlbody, 1));

        $curl = new \curl();
        $curl->setopt(array(
                'CURLOPT_HTTPHEADER' => array(
                        'Authorization: Bearer ' . $this->apiKey,
                        'Content-Type: application/json'
                ),
        ));

        $responsetext = $curl->post("https://api.openai.com/v1/chat/completions", json_encode($curlbody));
        $response = json_decode($responsetext);

        $haserror = property_exists($response, 'id') ? false : true;
        if (property_exists($response, 'error')) {
            $message = 'ERROR: ' . $response->error;
            $haserror = true;
        } else {
            $message = json_decode($response->choices[0]->message->content, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $haserror = true;
                $message = json_last_error_msg();
            } elseif (!empty($message['error'])) {
                $haserror = true;
                $message = $message['error'];
            }
        }

        return [
                "id" => $haserror ? 'error' : $response->id,
                "message" => $message
        ];
    }
}

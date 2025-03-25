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
use  \Aws\Comprehend\ComprehendClient;

/**
 * AWS Comprehend Implementation
 */
class awscomprehendsentimentanalyzer extends sentimentanalyzer implements sentimentanalyzerinterface {
    private $client;

    private $supported_languages = ['en', 'es', 'fr', 'de', 'it', 'pt', 'ja', 'ko', 'hi', 'ar', 'zh'];

    const MAX_BYTES_AWS_COMPRENHEND_MSG = 5000;

    public function __construct() {
        global $CFG;
        require_once($CFG->dirroot . '/blocks/coursesentiment/settings.php');

        $config = get_config('block_coursesentiment');

        $this->debugmessage = !empty($config->aws_debugmessage);
        if (!empty($config->aws_usecredchain)) {
            $this->client = new ComprehendClient([
                    'version' => 'latest',
                    'region' => $config->aws_api_region,
            ]);
        } else {
            $this->client = new ComprehendClient([
                    'version' => 'latest',
                    'region' => $config->aws_api_region,
                    'credentials' => [
                            'key' => $config->aws_api_key,
                            'secret' => $config->aws_api_secret,
                    ],
            ]);
        }
    }

    /**
     * return the lang of the current message
     * @param string $message
     * @return string
     */
    private function get_lang(string $message): string {

        // Detect the dominant language using Comprehend.
        $detectLang = $this->client->detectDominantLanguage(['Text' => $message]);

        $this->logsentimentmessage('Block block_coursesentiment got lang ' . print_r($detectLang, 1) . ' for message "' . $message .'"');

        if (!empty($detectLang['Languages']) && isset($detectLang['Languages'][0]['LanguageCode'])) {
            $language = $detectLang['Languages'][0]['LanguageCode'];
            // Check if the detected language is supported.
            if (in_array($language, $this->supported_languages)) {
                $this->logsentimentmessage('Block block_coursesentiment returned lang: "' . $language .'"');
                return $language;
            }
        }

        // Fallback to 'en' if no valid supported language is detected.
        return '';
    }

    /**
     * AWS Comprehend has a limitation of 5000 bytes
     * @param $text
     * @return array
     */
    private function split_text_for_comprehend(string $text) {
        $fragments = [];
        while (strlen($text) > 0) {
            $fragment = mb_strcut($text, 0, self::MAX_BYTES_AWS_COMPRENHEND_MSG, 'UTF-8');
            $fragments[] = $fragment;
            $text = mb_substr($text, mb_strlen($fragment, 'UTF-8'), null, 'UTF-8');
        }
        return $fragments;
    }

    public function analyze_sentiment(array $messages): array {
        $results = [];
        foreach ($messages as $msg) {
            $start = microtime(true);
            $txt = strip_tags($msg['message']);
            $language = $msg['language'] ?? null;
            if (!$language) {
                $language = $this->get_lang($txt);
            }

            if (in_array($language, $this->supported_languages) ) {

                $fragments = $this->split_text_for_comprehend($txt);
                $sentiments = [];

                foreach ($fragments as $fragment) {
                    $fragment_length = strlen($fragment);
                    $params = [
                            'LanguageCode' => $language,
                            'Text' => $fragment,
                    ];
                    $this->logsentimentmessage('Block block_coursesentiment analyze_sentiment for ' . print_r($params, 1));
                    $result = $this->client->detectSentiment($params);

                    $sentiments[] = [
                            'length' => $fragment_length,
                            'Sentiment' => $result['Sentiment'],
                            'Positive' => $result['SentimentScore']['Positive'] ?? 0,
                            'Negative' => $result['SentimentScore']['Negative'] ?? 0,
                            'Neutral'  => $result['SentimentScore']['Neutral'] ?? 0,
                            'Mixed'    => $result['SentimentScore']['Mixed'] ?? 0,
                    ];
                }
                $result = $this->aggregate_weighted_sentiments($sentiments);

                $this->logsentimentmessage('Block block_coursesentiment analyze_sentiment result ' . print_r($result, 1));
                $results[] = array(
                        'id' => $msg['id'],
                        'type' => $msg['type'],
                        'logid' => $msg['logid'],
                        'subject' => $msg['subject'],
                        'message' => $txt,
                        'language' => $language,
                        'courseid' => $msg['courseid'],
                        'userroles' => $msg['userroles'],
                        'teachermessage' => $msg['teachermessage'],
                        'sentiment' => $result['Sentiment'],
                        'sentiment_score_positive' => $result['SentimentScore']['Positive']??0,
                        'sentiment_score_negative' => $result['SentimentScore']['Negative']??0,
                        'sentiment_score_neutral' => $result['SentimentScore']['Neutral']??0,
                        'sentiment_score_mixed' => $result['SentimentScore']['Mixed']??0,
                        'error' => false,
                        'timeaianalysis' => round((microtime(true) - $start) * 1000, 2),
                        'tokensconsumed' => 0
                );
            } else {

                $results[] = array(
                        'id' => $msg['id'],
                        'type' => $msg['type'],
                        'logid' => $msg['logid'],
                        'subject' => $msg['subject'],
                        'message' => $msg['message'],
                        'language' => $language,
                        'courseid' => $msg['courseid'],
                        'userroles' => $msg['userroles'],
                        'teachermessage' => $msg['teachermessage'],
                        'sentiment' => null,
                        'sentiment_score_positive' => 0,
                        'sentiment_score_negative' => 0,
                        'sentiment_score_neutral' => 0,
                        'sentiment_score_mixed' => 0,
                        'error' => 'Unsupported language',
                        'timeaianalysis' => round((microtime(true) - $start) * 1000, 2),
                        'tokensconsumed' => 0
                );
            }
        }
        $this->store_logs($results);
        return $results;
    }

    private function aggregate_weighted_sentiments(array $sentiments): array {
        $total_length = array_sum(array_column($sentiments, 'length'));

        $aggregated = [
                'Positive' => 0,
                'Negative' => 0,
                'Neutral' => 0,
                'Mixed' => 0,
        ];

        foreach ($sentiments as $sentiment) {
            $weight = $sentiment['length'] / $total_length;
            $aggregated['Positive'] += $sentiment['Positive'] * $weight;
            $aggregated['Negative'] += $sentiment['Negative'] * $weight;
            $aggregated['Neutral']  += $sentiment['Neutral']  * $weight;
            $aggregated['Mixed']    += $sentiment['Mixed']    * $weight;
        }

        // Calcula el sentiment majoritari després de ponderar
        $max_sentiment = array_keys($aggregated, max($aggregated))[0];

        return [
                'Sentiment' => strtoupper($max_sentiment),
                'SentimentScore' => $aggregated,
        ];
    }

}

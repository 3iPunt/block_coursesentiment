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
use block_coursesentiment\persistent\resultlog;

/**
 * SentimentAnalyzer Maoin class
 */
class sentimentanalyzer implements sentimentanalyzerinterface {

    protected $debugmessage = false;

    // Use the logging trait to get some nice, juicy, logging.
    use \core\task\logging_trait;

    protected function logsentimentmessage(string $msg) {
        if ($this->debugmessage) {
            $this->log($msg);
        }
    }

    private function store_log($result) {

        $record = $result['logid'] > 0 ? new resultlog($result['logid']) : null;
        if (!$record) {
            $record = new resultlog();
            $record->set('externalid', $result['id']);
            $record->set('type', $result['type']);
        }

        $record->set('courseid', $result['courseid']);
        $record->set('language', empty($result['language']) ? '-' : $result['language']);
        $record->set('userroles', $result['userroles']);
        $record->set('teachermessage', $result['teachermessage']);
        $record->set('sentiment', empty($result['sentiment']) ? '-' : $result['sentiment']);
        $record->set('sentiment_score_positive', $result['sentiment_score_positive']);
        $record->set('sentiment_score_negative', $result['sentiment_score_negative']);
        $record->set('sentiment_score_neutral', $result['sentiment_score_neutral']);
        $record->set('sentiment_score_mixed', $result['sentiment_score_mixed']);
        $record->set('timeaianalysis', $result['timeaianalysis']);
        $record->set('tokensconsumed', $result['tokensconsumed']);
        $record->set('error', $result['error']);

        if ($record->get('id') > 0) {
            return $record->update();
        } else {
            return $record->create();
        }
    }
    protected function store_logs(array $results) {
        foreach ($results as $result) {
            $this->store_log($result);
        }
    }

    protected function buildmessage(array $msg) {
        return 'user roles (separated by commas): "' . $msg['userroles'] . '",
             posted time: "' . $msg['posttime'] . '",
             subject: "' . $msg['subject'] . '",
             message: "' . $msg['message'] . '"';
    }

    public function analyze_sentiment(array $messages): array {
        return [];
    }
}

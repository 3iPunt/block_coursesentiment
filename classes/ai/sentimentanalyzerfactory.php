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
 * SentimentAnalyzer Factory
 */
class sentimentanalyzerfactory  {

    public static function get_analyzer(): sentimentanalyzerinterface {
        $type = get_config('block_coursesentiment', 'type');

        switch ($type) {
            case 'openai':
                return new openaisentimentanalyzer();
            case 'aws':
            default:
                return new awscomprehendsentimentanalyzer();
        }
    }
}

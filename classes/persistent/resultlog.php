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
 * Class operation logs
 *
 * @package local_usjservices
 * @author 3iPunt <https://www.tresipunt.com/>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright 3iPunt <https://www.tresipunt.com/>
 */

namespace block_coursesentiment\persistent;

use core\persistent;

defined('MOODLE_INTERNAL') || die();

/**
 * Class block_coursesentiment_message_log log
 *
 * @package block_coursesentiment
 * @author 3iPunt <https://www.tresipunt.com/>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright 3iPunt <https://www.tresipunt.com/>
 */
class resultlog extends persistent {

    /** The table name. */
    const TABLE = 'block_coursesentiment_message_log';

    /**
     * Return the definition of the properties of this model.
     *
     * @return array
     */
    protected static function define_properties() {
        return array(
                'externalid' => array(
                        'type' => PARAM_INT,
                        'default' => '',
                        'description' => 'External ID of the object analyzed (e.g., forum post ID ).',
                ),
                'type' => array(
                        'type' => PARAM_TEXT,
                        'default' => '',
                        'description' => 'Object type (e.g., forum).',
                ),
                'courseid' => array(
                        'type' => PARAM_INT,
                        'default' => '',
                        'description' => 'The course id.',
                ),
                'language' => array(
                        'type' => PARAM_TEXT,
                        'default' => 'en',
                        'description' => 'Detected language code (ISO 639-1).',
                ),
                'userroles' => array(
                        'type' => PARAM_TEXT,
                        'default' => '',
                        'description' => 'User roles separated by comma',
                ),
                'teachermessage' => array(
                        'type' => PARAM_INT,
                        'default' => 0,
                        'description' => 'The messages was sent by a teacher (using capabilities)',
                ),
                'sentiment' => array(
                        'type' => PARAM_TEXT,
                        'default' => '',
                        'description' => 'Sentiment label (e.g., POSITIVE, NEGATIVE, NEUTRAL, MIXED).',
                ),
                'sentiment_score_positive' => array(
                        'type' => PARAM_FLOAT,
                        'default' => 0.0,
                        'description' => 'Positive sentiment score as a decimal value.',
                ),
                'sentiment_score_negative' => array(
                        'type' => PARAM_FLOAT,
                        'default' => 0.0,
                        'description' => 'Negative sentiment score as a decimal value.',
                ),
                'sentiment_score_neutral' => array(
                        'type' => PARAM_FLOAT,
                        'default' => 0.0,
                        'description' => 'Neutral sentiment score as a decimal value.',
                ),
                'sentiment_score_mixed' => array(
                        'type' => PARAM_FLOAT,
                        'default' => 0.0,
                        'description' => 'Mixed sentiment score as a decimal value.',
                ),
                'error' => array(
                        'type' => PARAM_TEXT,
                        'default' => null,
                        'null' => NULL_ALLOWED,
                        'description' => 'Error message if sentiment analysis failed (nullable).',
                ),
                'tokensconsumed' => array(
                        'type' => PARAM_INT,
                        'default' => null,
                        'null' => NULL_ALLOWED,
                        'description' => 'Token consumed for the operations if provider returns it',
                ),
                'timeaianalysis' => array(
                        'type' => PARAM_FLOAT,
                        'default' => null,
                        'null' => NULL_ALLOWED,
                        'description' => 'Time took on analysis in milliseconds',
                )
        );
    }

    public static function get_forum_record($id) {
        return self::get_record(['externalid' => $id,
                'type' => 'forum']);
    }

}
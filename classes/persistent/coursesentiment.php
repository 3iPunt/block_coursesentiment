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
class coursesentiment extends persistent {

    /** The table name. */
    const TABLE = 'block_coursesentiment';

    /**
     * Return the definition of the properties of this model.
     *
     * @return array
     */
    protected static function define_properties() {
        return array(
                'courseid' => array(
                        'type' => PARAM_INT,
                        'default' => '',
                        'description' => 'The course id.',
                ),
                'numberforums' => array(
                        'type' => PARAM_INT,
                        'default' => 0,
                        'description' => 'Number of forums.',
                ),
                'numberdiscussions' => array(
                        'type' => PARAM_INT,
                        'default' => 0,
                        'description' => 'Number of discussions.',
                ),
                'numbermessages' => array(
                        'type' => PARAM_INT,
                        'default' => 0,
                        'description' => 'Number of teacher messages.',
                ),
                'numberteachermessages' => array(
                        'type' => PARAM_INT,
                        'default' => 0,
                        'description' => 'Number of teacher messages.',
                ),
                'numbermessagesnotanalyzed' => array(
                        'type' => PARAM_INT,
                        'default' => 0,
                        'description' => 'Number of teacher messages.',
                ),
                'numberpositivemessages' => array(
                        'type' => PARAM_INT,
                        'default' => 0,
                        'description' => 'Number of positive sentiment messages.',
                ),
                'numbernegativemessages' => array(
                        'type' => PARAM_INT,
                        'default' => 0,
                        'description' => 'Number of negative sentiment messages.',
                ),
                'numbermixedmessages' => array(
                        'type' => PARAM_INT,
                        'default' => 0,
                        'description' => 'Number of mixed sentiment messages.',
                ),
                'numberneutralmessages' => array(
                        'type' => PARAM_INT,
                        'default' => 0,
                        'description' => 'Number of neutral sentiment messages.',
                ),
                'avgteachertimereply' => array(
                        'type' => PARAM_FLOAT,
                        'default' => 0.0,
                        'description' => 'Average time that teacher replies.',
                ),
                'sentimentpercentage' => array(
                        'type' => PARAM_FLOAT,
                        'default' => 0.0,
                        'description' => 'Percentage of current course sentiment, 0 is not happy and 100 very happy',
                )
        );
    }
}
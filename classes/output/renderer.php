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

namespace block_coursesentiment\output;

use block_coursesentiment\persistent\coursesentiment;
use moodle_exception;
use plugin_renderer_base;
use stdClass;

defined('MOODLE_INTERNAL') || die();

class renderer extends plugin_renderer_base {
    /**
     * @param int $courseid
     * @return string
     * @throws moodle_exception
     */
    public function get_block_content_html($courseid): string {
        $render = new stdClass();
        $courserecord = coursesentiment::get_record(['courseid' => $courseid]);

        $render->has_course_stats = false;
        if ($courserecord) {
            $render->has_course_stats = true;

            global $OUTPUT;

            $series = new \core\chart_series(get_string('sentiment', 'block_coursesentiment'),
                    [$courserecord->get('numberpositivemessages'),
                            $courserecord->get('numbernegativemessages'), $courserecord->get('numbermixedmessages'),
                            $courserecord->get('numberneutralmessages')]);
            $labels = [get_string('positives', 'block_coursesentiment'),
                    get_string('negatives', 'block_coursesentiment'),
                    get_string('neutral', 'block_coursesentiment'),
                    get_string('mixed', 'block_coursesentiment')];

            $chart = new \core\chart_pie();
            $chart->set_title(get_string('pluginname', 'block_coursesentiment'));
            $chart->add_series($series);
            $chart->set_labels($labels);
            $render->chartoutput = $OUTPUT->render($chart);

            $render->positives = $courserecord->get('numberpositivemessages');
            $render->negatives = $courserecord->get('numbernegativemessages');
            $render->mixed = $courserecord->get('numbermixedmessages');
            $render->neutral = $courserecord->get('numberneutralmessages');

            $render->timemodified = $courserecord->get('timemodified');
            $render->numberforums = $courserecord->get('numberforums');
            $render->numberdiscussions = $courserecord->get('numberdiscussions');
            $render->numbermessages = $courserecord->get('numbermessages');
            $render->numberteachermessages = $courserecord->get('numberteachermessages');
            $render->numbermessagesnotanalyzed = $courserecord->get('numbermessagesnotanalyzed');

        }

        return $this->render_from_template('block_coursesentiment/block_content_html', $render);
    }

}

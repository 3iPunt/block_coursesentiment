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
            $render = $this->add_course_stats($courserecord, $render);
        }

        return $this->render_from_template('block_coursesentiment/block_content_html', $render);
    }

    private function add_course_stats(coursesentiment $courserecord, stdClass $data) : stdClass {

        $data->positives = $courserecord->get('numberpositivemessages');
        $data->negatives = $courserecord->get('numbernegativemessages');
        $data->mixed = $courserecord->get('numbermixedmessages');
        $data->neutral = $courserecord->get('numberneutralmessages');
        $total = $data->positives + $data->negatives + $data->mixed + $data->neutral;
        $data->positivespercent = $total !== 0 ? ($data->positives / $total) * 100 : 0;
        $data->negativespercent = $total !== 0 ? ($data->negatives / $total) * 100 : 0;
        $data->mixedpercent   = $total !== 0 ? ($data->mixed / $total) * 100 : 0;
        $data->neutralpercent     = $total !== 0 ? ($data->neutral / $total) * 100 : 0;


        $data->timemodified = $courserecord->get('timemodified');
        $data->numberforums = $courserecord->get('numberforums');
        $data->numberdiscussions = $courserecord->get('numberdiscussions');
        $data->numbermessages = $courserecord->get('numbermessages');
        $data->numberteachermessages = $courserecord->get('numberteachermessages');
        $data->numbermessagesnotanalyzed = $courserecord->get('numbermessagesnotanalyzed');

        return $data;
    }

    private function has_block_enabled(int $courseid) {
        global $DB;
        $exists = $DB->record_exists('block_instances', [
                'blockname' => 'coursesentiment',
                'parentcontextid' => \context_course::instance($courseid)->id
        ]);
        return $exists;
    }
    /**
     * @return string
     * @throws moodle_exception
     */
    public function get_block_content_manager(): string {
        $render = new stdClass();

        $courserecords = coursesentiment::get_records();


        $courses = [];
        foreach ($courserecords as $courserecord) {
            if ($this->has_block_enabled($courserecord->get('courseid'))) {
                $data = new stdClass();
                $data = $this->add_course_stats($courserecord, $data);
                $course = get_course($courserecord->get('courseid'));
                $data->fullname = $course->fullname;
                $data->id = $course->id;
                $data->courseurl = new \moodle_url('/course/view.php', ['id' => $data->id]);
                $data->imageurl = $this->get_course_image_url($course);
                $courses[] = $data;

            }
        }
        $render->has_course_stats = count($courses) > 0;
        $render->courses = $courses;

        return $this->render_from_template('block_coursesentiment/block_general_view', $render);
    }

    private function get_course_image_url(stdClass $course): ?string {

        $context = \context_course::instance($course->id);
        $fs = get_file_storage();
        $files = $fs->get_area_files($context->id, 'course', 'overviewfiles', 0, 'sortorder', false);

        foreach ($files as $file) {
            $mimetype = $file->get_mimetype();
            if ($mimetype === 'image/jpeg' || $mimetype === 'image/png' || $mimetype === 'image/gif') {

                return \moodle_url::make_pluginfile_url(
                        $file->get_contextid(),
                        $file->get_component(),
                        $file->get_filearea(),
                        null,
                        $file->get_filepath(),
                        $file->get_filename()
                )->out();
            }
        }

        return null; // Si no hi ha imatge
    }

}

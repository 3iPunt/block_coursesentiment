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
 * Task for updating RSS feeds for rss client block
 *
 * @package     block_coursesentiment
 * @copyright   2024 3ipunt <moodle@tresipunt.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_coursesentiment\task;

use block_coursesentiment\ai\sentimentanalyzerfactory;
use block_coursesentiment\persistent\courselog;
use block_coursesentiment\persistent\resultlog;
use core\context\course;
use block_coursesentiment\persistent\coursesentiment;

defined('MOODLE_INTERNAL') || die();

/**
 * Task for updating RSS feeds for rss client block
 *
 * @package     block_coursesentiment
 * @category    task
 * @copyright   2024 3ipunt <moodle@tresipunt.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class analyzeit extends \core\task\scheduled_task {

    // Use the logging trait to get some nice, juicy, logging.
    use \core\task\logging_trait;

    private $course_user_roles = [];

    private  int $numberforums = 0;
    private  int $numbermessages = 0;
    private  int $numberteachermessages = 0;



    /**
     * Name for this task.
     *
     * @return string
     */
    public function get_name() {
        return get_string('forumanalysistask', 'block_coursesentiment');
    }

    /**
     * Get all candidates courses and does the forum analysis
     */
    public function execute() {
        global $CFG, $DB;
        $this->course_user_roles = [];
        require_once("{$CFG->dirroot}/course/lib.php");
        $info = \core_plugin_manager::instance()->get_plugin_info('block_coursesentiment');
        if (is_null($info) || !$info->is_enabled()) {
            $this->log('Block block_coursesentiment is disabled!');
            return false;
        }
        $this->log('Block block_coursesentiment starting process');
        // TODO GET from other site
        $blocklist = $DB->get_field('block', 'id',  ['name' => 'coursesentiment']);

        $courses = $this->get_courses_candidates($blocklist);

        if ($courses) {
            $this->log(" \n ");
            $this->log(" Processing ". count($courses). " courses");
            foreach ($courses as $course) {
                $this->analyze_course($course);
            }
        }
        $this->log('Block block_coursesentiment endeded process');

    }

    private function analyze_course($course) {
        if ($course->__get('visible')) {
            $start = microtime(true);
            $lang = $course->__get('lang');
            global $CFG;
            require_once($CFG->dirroot . '/course/lib.php');
            $this->log(" --------------------------------- ");
            $this->log("Processing course {$course->__get('shortname')} ");

            $modinfo = get_fast_modinfo($course->__get('id'));
            $context = \context_course::instance($course->__get('id'));

            $results = [];
            foreach ($modinfo->cms as $cm) {
                // Exclude all forums that are visible
                if ($cm->uservisible && $cm->modname === 'forum') {
                    $results = $results + $this->analyze_forum($cm, $course->__get('id'), $lang, $context);
                }
            }
            if (count($results) > 0) {
                $this->processcoursestats($course, $start);
            } else {
                $this->log("Updated only last update date course {$course->__get('shortname')} because there aren't new messages");
                $this->updatedatecoursestats($course);
            }
            $this->log("Ended course {$course->__get('shortname')} ");
            $this->log(" --------------------------------- ");
        } else {
            $this->log("Skip course {$course->__get('shortname')} because is hidden");
        }

    }

    private function analyze_forum($cm, $courseid, $lang, course $coursecontext) {
        global $CFG;
        $this->log("Processing forum {$cm->name}");
        require_once($CFG->dirroot.'/mod/forum/lib.php');

        // TODO Manage groups
/*        $groupmode    = groups_get_activity_groupmode($cm);
        $currentgroup = groups_get_activity_group($cm, true);
*/
        $discussions = forum_get_discussions($cm, '', true,
                -1, -1,
                false, -1, 0, FORUM_POSTS_ALL_USER_GROUPS);

        $this->numberforums = 0;
        $this->numberteachermessages = 0;
        $this->numbermessages = 0;
        $analyzer = sentimentanalyzerfactory::get_analyzer();

        $results = $analyzer->analyze_sentiment($this->processdiscussions($discussions, $courseid, $lang, $coursecontext));

        return $results;
    }

    private function processcoursestats($course, $start) {
        $timeaianalysis = round((microtime(true) - $start) * 1000, 2);
        $tokensconsumed = 0;
        $numberpositivemessages = 0;
        $numbernegativemessages = 0;
        $numbermixedmessages = 0;
        $numberneutralmessages = 0;
        // TODO pending
        $avgteachertimereply = 0;
        $sentimentpercentage = 0;

        $results = resultlog::get_records(['courseid' => $course->__get('id')]);

        foreach ($results as $result) {
            $tokensconsumed += $result->get('tokensconsumed');
            switch ($result->get('sentiment')) {
                case 'POSITIVE':
                    $numberpositivemessages ++;
                    break;
                case 'NEGATIVE':
                    $numbernegativemessages ++;
                    break;
                case 'MIXED':
                    $numbermixedmessages ++;
                    break;
                case 'NEUTRAL':
                    $numberneutralmessages ++;
                    break;
            }
        }

        $values = [
                'courseid' => $course->__get('id'),
                'numberforums' => $this->numberforums,
                'numbermessages' => $this->numbermessages,
                'numberteachermessages' => $this->numberteachermessages,
                'numberpositivemessages' => $numberpositivemessages,
                'numbernegativemessages' => $numbernegativemessages,
                'numbermixedmessages' => $numbermixedmessages,
                'numberneutralmessages' => $numberneutralmessages,
                'avgteachertimereply' => $avgteachertimereply,
                'sentimentpercentage' => $sentimentpercentage,
        ];
        $data = (object) $values;
        $datalog = (object) $values;

        $courserecord = coursesentiment::get_record(['courseid' => $course->__get('id')]);
        if (!$courserecord) {
            $courserecord = new coursesentiment(0, $data);
            $courserecord->create();
        } else {
            unset($values['courseid']);
            $courserecord->set_many($values);
            $courserecord->update();
        }

        $datalog->tokensconsumed = $tokensconsumed;
        $datalog->timeaianalysis = $timeaianalysis;
        $courselog = new courselog(0, $datalog);
        $courselog->create();
    }
    private function updatedatecoursestats($course) {
        $courserecord = coursesentiment::get_record(['courseid' => $course->__get('id')]);
        if ($courserecord) {
            $courserecord->update();
        }
    }

    /**
     * Process all discussions from a course
     * @param $discussions
     * @param int $courseid
     * @param $lang
     * @param course $coursecontext
     * @return array
     * @throws \coding_exception
     */
    private function processdiscussions($discussions, int $courseid, $lang, course $coursecontext) : array {
        $messages = [];
        foreach ($discussions as $discussion) {

            if ($discussion->userdeleted) {
                continue;
            }
            $this->numberforums ++;
            $posts = forum_get_all_discussion_posts($discussion->discussion, 'p.created ASC');

            $discussion->subject = $discussion->name;
            $discussion->subject = format_string($discussion->subject, true, $courseid);

            foreach ($posts as $post) {

                $msg = $this->processpost($post, $discussion, $courseid, $lang, $coursecontext);

                if ($msg) {
                    $this->addnummessagesstats($msg);
                    if ($msg['pendingprocess']) {
                        $messages[] = $msg;
                    }
                }
            }
        }
        return $messages;
    }

    /**
     * Adds the number of messages of current process
     * @param $msg
     * @return void
     */
    private function addnummessagesstats($msg) {
        $this->numbermessages ++;
        if ($msg['teachermessage']) {
            $this->numberteachermessages ++;
        }
    }

    /**
     * Process Post
     * @param $post
     * @param $discussion
     * @param int $courseid
     * @param $lang
     * @param course $coursecontext
     * @return array|null
     * @throws \coding_exception
     */
    private function processpost($post, $discussion, int $courseid, $lang, course $coursecontext) {

        if (empty($post->message)) {
            return null;
        }
        $record = resultlog::get_forum_record($post->id);

        $recordid = $record ? $record->get('id') : 0;
        $posttime = $post->modified;
        if (!empty($CFG->forum_enabletimedposts) && ($post->timestart > $posttime)) {
            $posttime = $post->timestart;
        }

        $pendingprocess = true;
        $userroles = '';
        $teachermessage = 0;
        if ($recordid > 0) {
            if ($record->get('timemodified') > $post->modified) {
                $userroles = $record->get('userroles');
                $teachermessage = $record->get('teachermessage');
                $pendingprocess = false;
            }
        }
        if ($pendingprocess) {
            $userroles = $this->feth_user_roles($post->userid, $courseid, $coursecontext);
            $teachermessage = $this->has_teacher_role($post->userid, $coursecontext);
        }

        return ['pendingprocess' => $pendingprocess, 'courseid' => $courseid, 'id' => $post->id, 'userroles' => $userroles, 'teachermessage' => $teachermessage, 'posttime' => $posttime,
                'subject' => $discussion->subject, 'message' => $post->message, 'lang' => $lang, 'logid' => $recordid, 'type' => 'forum'];
    }

    /**
     * Get user roles separated by commas
     * @param $userid
     * @param $courseid
     * @param \core\context\course $coursecontext
     * @return string
     */
    private function feth_user_roles($userid, $courseid, \core\context\course $coursecontext) {

        if (!isset($this->course_user_roles[$courseid])) {
            $this->course_user_roles[$courseid] = [];
        }
        if (!isset($this->course_user_roles[$courseid][$userid])) {
            $userrolesbyid = get_user_roles($coursecontext, $userid, true, 'c.contextlevel DESC, r.sortorder ASC');
            $userroles = [];
            foreach ($userrolesbyid as $role) {
                $userroles[] = empty($role->name) ? $role->shortname : $role->name;
            }

            $this->course_user_roles[$courseid][$userid] = implode(",", $userroles);
        }
        return $this->course_user_roles[$courseid][$userid];
    }

    /**
     * REturns if can grade to check if is a teacher or not
     * @param $userid
     * @param course $coursecontext
     * @return int
     * @throws \coding_exception
     */
    private function has_teacher_role($userid, \core\context\course $coursecontext) {
        return has_capability('mod/assign:grade', $coursecontext, $userid) ? 1 : 0;
    }

    /**
     * Gets the courses candidates that have the block enabled
     * @param $blocklist
     * @param $courses
     * @return array|mixed
     */
    private function get_courses_candidates($blocklist, $courses = []) {
        $search = '';
        $modulelist = '';
        list($coursesdb, $coursescount, $coursestotal) =
                \core_course\management\helper::search_courses($search, $blocklist, $modulelist);
        $courses = $courses + $coursesdb;
        if (count($courses) < $coursestotal) {
            $courses = $this->get_courses_candidates($blocklist, $courses);
        }
        return $courses;

    }
}

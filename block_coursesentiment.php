<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.


/**
 * Block coursesentiment is defined here.
 *
 * @package     block_coursesentiment
 * @copyright   2024 3ipunt <moodle@tresipunt.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_coursesentiment extends block_base {

    /**
     * Initializes class member variables.
     */
    public function init() {
        // Needed by Moodle to differentiate between blocks.
        $this->title = get_string('pluginname', 'block_coursesentiment');
    }

    /**
     * Returns the block contents.
     *
     * @return stdClass The block contents.
     */
    public function get_content() {

        global $COURSE;

        $coursecontext = context_course::instance($COURSE->id);
        if (has_capability('block/coursesentiment:viewcoursecontent', $coursecontext)) {
            if ($this->content !== null) {
                return $this->content;
            }

            $this->content = new stdClass();
            $this->content->items = array();
            $this->content->icons = array();
            $this->content->footer = '';

            $renderer = $this->page->get_renderer('block_coursesentiment');
            if (!self::on_site_page($this->page)) {
                $view = $this->config->viewmode ?? get_config('block_coursesentiment', 'defaultview');
                $this->content->text = $renderer->get_block_content_html($this->page->course->id, $view);
            } else {
                $this->content->text = $renderer->get_block_content_manager();
            }

        } else {
            return '';
        }

        return $this->content;
    }

    /**
     * Checks whether the given page is site-level (Dashboard or Front page) or not.
     *
     * @param moodle_page $page the page to check, or the current page if not passed.
     * @return boolean True when on the Dashboard or Site home page.
     */
    public static function on_site_page($page = null) {
        global $PAGE;   // phpcs:ignore moodle.PHP.ForbiddenGlobalUse.BadGlobal

        $page = $page ?? $PAGE; // phpcs:ignore moodle.PHP.ForbiddenGlobalUse.BadGlobal
        $context = $page->context ?? null;

        if (!$page || !$context) {
            return false;
        } else if ($context->contextlevel === CONTEXT_SYSTEM && $page->requestorigin === 'restore') {
            return false; // When restoring from a backup, pretend the page is course-level.
        } else if ($context->contextlevel === CONTEXT_COURSE && $context->instanceid == SITEID) {
            return true;  // Front page.
        } else if ($context->contextlevel < CONTEXT_COURSE) {
            return true;  // System, user (i.e. dashboard), course category.
        } else {
            return false;
        }
    }

    /**
     * Defines configuration data.
     *
     * The function is called immediately after init().
     */
    public function specialization() {

        // Load user defined title and make sure it's never empty.
        if (empty($this->config->title)) {
            $this->title = get_string('pluginname', 'block_coursesentiment');
        } else {
            $this->title = $this->config->title;
        }
    }

    /**
     * Enables global configuration of the block in settings.php.
     *
     * @return bool True if the global configuration is enabled.
     */
    public function has_config() {
        return true;
    }

    /**
     * Sets the applicable formats for the block.
     *
     * @return string[] Array of pages and permissions.
     */
    public function applicable_formats() {
        return array('all' => true);
    }
}

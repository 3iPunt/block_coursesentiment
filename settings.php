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
 * Plugin administration pages are defined here.
 *
 * @package     block_coursesentiment
 * @category    admin
 * @copyright   2024 3ipunt <moodle@tresipunt.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings = new admin_settingpage('block_coursesentiment_settings', new lang_string('pluginname', 'block_coursesentiment'));

    if ($ADMIN->fulltree) {
        require_once($CFG->dirroot . '/blocks/coursesentiment/lib.php');

        $type = block_coursesentiment_get_type_to_display();

        global $PAGE;
        $PAGE->requires->js_call_amd('block_coursesentiment/settings', 'init');

        $settings->add(new admin_setting_configselect(
            'block_coursesentiment/type',
            get_string('provider', 'block_coursesentiment'),
            get_string('providerdesc', 'block_coursesentiment'),
            'chat',
            ['aws' => 'aws', 'openai' => 'openai']
        ));

        if ($type === 'openai') {
                $settings->add(new admin_setting_configpasswordunmask(
                    'block_coursesentiment/apikey',
                    new lang_string('apikey', 'block_coursesentiment'),
                    new lang_string('apikey_desc', 'block_coursesentiment'),
                    ''
                ));

                $settings->add(new admin_setting_configtext(
                    'block_coursesentiment/orgid',
                    new lang_string('orgid', 'block_coursesentiment'),
                    new lang_string('orgid_desc', 'block_coursesentiment'),
                    '',
                    PARAM_TEXT
                ));

                $settings->add(new admin_setting_configselect(
                    'block_coursesentiment/model',
                    get_string('model', 'block_coursesentiment'),
                    get_string('modeldesc', 'block_coursesentiment'),
                    'gpt-3.5-turbo',
                    block_coursesentiment_get_models()
                ));

                $settings->add(new admin_setting_configtext(
                    'block_coursesentiment/temperature',
                    get_string('temperature', 'block_coursesentiment'),
                    get_string('temperaturedesc', 'block_coursesentiment'),
                    0.8,
                    PARAM_FLOAT
                ));

                $settings->add(new admin_setting_configcheckbox(
                    'block_coursesentiment/enableglobalratelimit',
                    new lang_string('enableglobalratelimit', 'block_coursesentiment'),
                    new lang_string('enableglobalratelimit_desc', 'block_coursesentiment'),
                    0
                ));

                $settings->add(new admin_setting_configtext(
                    'block_coursesentiment/globalratelimit',
                    new lang_string('globalratelimit', 'block_coursesentiment'),
                    new lang_string('globalratelimit_desc', 'block_coursesentiment'),
                    100,
                    PARAM_INT
                ));
                $settings->hide_if('block_coursesentiment/globalratelimit', 'block_coursesentiment/enableglobalratelimit', 'eq', 0);



                $settings->add(new admin_setting_configtext(
                        'block_coursesentiment/maxlength',
                        get_string('maxlength', 'block_coursesentiment'),
                        get_string('maxlengthdesc', 'block_coursesentiment'),
                        500,
                        PARAM_INT
                    ));
        
                $settings->add(new admin_setting_configtext(
                'block_coursesentiment/topp',
                get_string('topp', 'block_coursesentiment'),
                get_string('toppdesc', 'block_coursesentiment'),
                1,
                PARAM_FLOAT
                ));

                $settings->add(new admin_setting_configtext(
                'block_coursesentiment/frequency',
                get_string('frequency', 'block_coursesentiment'),
                get_string('frequencydesc', 'block_coursesentiment'),
                1,
                PARAM_FLOAT
                ));

                $settings->add(new admin_setting_configtext(
                'block_coursesentiment/presence',
                get_string('presence', 'block_coursesentiment'),
                get_string('presencedesc', 'block_coursesentiment'),
                1,
                PARAM_FLOAT
                ));

        } else { // AWS
                $settings->add(new admin_setting_description('info_aws', '', get_string('aws_information', 'block_coursesentiment')));

                $settings->add(new admin_setting_configcheckbox(
                        'block_coursesentiment/aws_usecredchain',
                        get_string('usecredchain', 'block_coursesentiment'),
                        '',
                        0
                ));

                $settings->add(new admin_setting_configtext(
                        'block_coursesentiment/aws_api_key',
                        get_string('api_key', 'block_coursesentiment'),
                        '',
                        '',
                        PARAM_TEXT
                ));

                $settings->add(new admin_setting_configpasswordunmask(
                        'block_coursesentiment/aws_api_secret',
                        get_string('api_secret', 'block_coursesentiment'),
                        '',
                        ''
                ));

                $settings->add(new admin_setting_configtext(
                        'block_coursesentiment/aws_api_region',
                        get_string('api_region', 'block_coursesentiment'),
                        '',
                        'eu-west-1',
                        PARAM_TEXT
                ));


                $settings->add(new admin_setting_configcheckbox(
                        'block_coursesentiment/aws_debugmessage',
                        get_string('debugmessage', 'block_coursesentiment'),
                        '',
                        0
                ));
        }

        $settings->add(new admin_setting_heading(
            'block_coursesentiment/coursecategory',
            get_string('restricttocategory', 'block_coursesentiment'),
            get_string('restricttocategory_help', 'block_coursesentiment')
        ));

        $records = $DB->get_records('course_categories', [], 'sortorder, id', 'id,parent,name');
        $tree = block_coursesentiment_build_category_tree(array_map(fn($record) => (array)$record, $records));
        $settings->add(new admin_setting_description(
            'block_coursesentiment/coursecategories',
            new lang_string('caregories', 'block_coursesentiment'),
            $OUTPUT->render_from_template('block_coursesentiment/categorynode', ['nodes' => $tree])
        ));
    }
}

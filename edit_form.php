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
 * Form for editing coursesentiment block instances.
 *
 * @package     block_coursesentiment
 * @copyright   2024 3ipunt <moodle@tresipunt.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_coursesentiment_edit_form extends block_edit_form {

    /**
     * Extends the configuration form for block_coursesentiment.
     *
     * @param MoodleQuickForm $mform The form being built.
     */
    protected function specific_definition($mform) {

        // Section header title.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $mform->addElement('select', 'config_viewmode',
                get_string('defaultview', 'block_coursesentiment'),
                [
                        'summary' => get_string('view_summary', 'block_coursesentiment'),
                        'chart' => get_string('view_chart', 'block_coursesentiment'),
                        'all' => get_string('view_all', 'block_coursesentiment')
                ]
        );

        // Valor per defecte: si s’està editant, agafa el valor existent; si és nou, agafa de la configuració global
        $default = isset($this->block->config->viewmode) ? $this->block->config->viewmode : get_config('block_coursesentiment', 'defaultview');
        $mform->setDefault('config_viewmode', $default);


    }
}

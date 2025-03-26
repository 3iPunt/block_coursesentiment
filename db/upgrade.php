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
 * Plugin upgrade steps are defined here.
 *
 * @package     block_coursesentiment
 * @category    upgrade
 * @copyright   2024 3ipunt <moodle@tresipunt.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Execute block_coursesentiment upgrade from the given old version.
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_block_coursesentiment_upgrade($oldversion) {
    global $DB;

    // For further information please read {@link https://docs.moodle.org/dev/Upgrade_API}.
    //
    // You will also have to create the db/install.xml file by using the XMLDB Editor.
    // Documentation for the XMLDB Editor can be found at {@link https://docs.moodle.org/dev/XMLDB_editor}.

    $dbman = $DB->get_manager(); // Loads ddl manager and xmldb classes.

    if ($oldversion < 2025022601) {
        // Define field activity to be added to assign.
        $table = new xmldb_table('block_coursesentiment');
        $field = new xmldb_field(
                'numberdiscussions',
                XMLDB_TYPE_INTEGER,
                10,
                null,
                XMLDB_NOTNULL,
                null,
                '0',
                'numberforums'
        );
        // Conditionally launch add field activity.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        $tablelog = new xmldb_table('block_coursesentiment_log');
        $fieldlog = new xmldb_field(
                'numberdiscussions',
                XMLDB_TYPE_INTEGER,
                10,
                null,
                XMLDB_NOTNULL,
                null,
                '0',
                'numberforums'
        );
        // Conditionally launch add field activity.
        if (!$dbman->field_exists($tablelog, $fieldlog)) {
            $dbman->add_field($tablelog, $fieldlog);
        }

        // Assign savepoint reached.
        upgrade_block_savepoint(true, 2025022601, 'coursesentiment');
    }
    if ($oldversion < 2025022602) {
        // Define field activity to be added to assign.
        $table = new xmldb_table('block_coursesentiment');
        $field = new xmldb_field(
                'numbermessagesnotanalyzed',
                XMLDB_TYPE_INTEGER,
                10,
                null,
                XMLDB_NOTNULL,
                null,
                '0',
                'numbermessages'
        );
        // Conditionally launch add field activity.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        $tablelog = new xmldb_table('block_coursesentiment_log');
        $fieldlog = new xmldb_field(
                'numbermessagesnotanalyzed',
                XMLDB_TYPE_INTEGER,
                10,
                null,
                XMLDB_NOTNULL,
                null,
                '0',
                'numbermessages'
        );
        // Conditionally launch add field activity.
        if (!$dbman->field_exists($tablelog, $fieldlog)) {
            $dbman->add_field($tablelog, $fieldlog);
        }

        // Assign savepoint reached.
        upgrade_block_savepoint(true, 2025022602, 'coursesentiment');
    }
    return true;
}

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
 * General plugin functions
 *
 * @package    block_coursesentiment
 * @copyright  2025 Antoni Bertran <antoni@tresipunt.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Fetch the current API type from the database, defaulting to "chat"
 * @return String: the API type (chat|azure|assistant)
 */
function block_coursesentiment_get_type_to_display() {
    $stored_type = get_config('block_coursesentiment', 'type');
    if ($stored_type) {
        return $stored_type;
    }
    
    return 'aws';
}

/**
 * Build category tree.
 *
 * @param array $elements
 * @param int $parentid
 * @return array category tree
 */
function block_coursesentiment_build_category_tree(array $elements, int $parentid = 0): array {
    $branch = [];

    foreach ($elements as $element) {
        if ($element['parent'] == $parentid) {
            $children = block_coursesentiment_build_category_tree($elements, $element['id']);
            if ($children) {
                $element['nodes'] = $children;
                $element['haschildren'] = true;
            } else {
                $element['nodes'] = null;
                $element['haschildren'] = false;
            }
            $branch[] = $element;
        }
    }
    return $branch;
}


/**
 * Return a list of available models.
 * @return Array: The list of model info
 */
function block_coursesentiment_get_models() {
    return [
                    'gpt-4o-2024-05-13' => 'gpt-4o-2024-05-13',
                    'gpt-4o' => 'gpt-4o',
                    'gpt-4-turbo-preview' => 'gpt-4-turbo-preview',
                    'gpt-4-turbo-2024-04-09' => 'gpt-4-turbo-2024-04-09',
                    'gpt-4-turbo' => 'gpt-4-turbo',
                    'gpt-4-32k-0314' => 'gpt-4-32k-0314',
                    'gpt-4-1106-vision-preview' => 'gpt-4-1106-vision-preview',
                    'gpt-4-1106-preview' => 'gpt-4-1106-preview',
                    'gpt-4-0613' => 'gpt-4-0613',
                    'gpt-4-0314' => 'gpt-4-0314',
                    'gpt-4-0125-preview' => 'gpt-4-0125-preview',
                    'gpt-4' => 'gpt-4',
                    'gpt-3.5-turbo-16k-0613' => 'gpt-3.5-turbo-16k-0613',
                    'gpt-3.5-turbo-16k' => 'gpt-3.5-turbo-16k',
                    'gpt-3.5-turbo-1106' => 'gpt-3.5-turbo-1106',
                    'gpt-3.5-turbo-0613' => 'gpt-3.5-turbo-0613',
                    'gpt-3.5-turbo-0301' => 'gpt-3.5-turbo-0301',
                    'gpt-3.5-turbo-0125' => 'gpt-3.5-turbo-0125',
                    'gpt-3.5-turbo' => 'gpt-3.5-turbo'
    ];
}
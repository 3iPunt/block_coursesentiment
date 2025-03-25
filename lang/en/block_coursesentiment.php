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
 * Plugin strings are defined here.
 *
 * @package     block_coursesentiment
 * @category    string
 * @copyright   2024 3ipunt <moodle@tresipunt.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Course sentiment';
$string['privacy:metadata'] = 'The Course sentiment block analyse data but donn\'t store any personal data.';
$string['coursesentiment:addinstance'] = 'Add a new Course Sentiment block';
$string['coursesentiment:myaddinstance'] = 'Add a new Course Sentiment block to the My Moodle page';
$string['coursesentiment:viewcoursecontent'] = 'View Course Sentiment block at course level';

$string['apikey'] = 'OpenAI API key';
$string['apikey_desc'] = 'Get a key from your <a href="https://platform.openai.com/account/api-keys" target="_blank">OpenAI API keys</a>.';
$string['model'] = 'Model';
$string['modeldesc'] = 'The model which will generate the completion';
$string['temperature'] = 'Temperature';
$string['temperaturedesc'] = 'Controls randomness: Lowering results in less random completions. As the temperature approaches zero, the model will become deterministic and repetitive.';
$string['maxlength'] = 'Maximum length';
$string['maxlengthdesc'] = 'The maximum number of token to generate. Requests can use up to 2,048 or 4,000 tokens shared between prompt and completion. The exact limit varies by model. (One token is roughly 4 characters for normal English text)';
$string['topp'] = 'Top P';
$string['toppdesc'] = 'Controls diversity via nucleus sampling: 0.5 means half of all likelihood-weighted options are considered.';
$string['frequency'] = 'Frequency penalty';
$string['frequencydesc'] = 'How much to penalize new tokens based on their existing frequency in the text so far. Decreases the model\'s likelihood to repeat the same line verbatim.';
$string['presence'] = 'Presence penalty';
$string['presencedesc'] = 'How much to penalize new tokens based on whether they appear in the text so far. Increases the model\'s likelihood to talk about new topics.';

$string['enableglobalratelimit'] = 'Set site-wide rate limit';
$string['enableglobalratelimit_desc'] = 'Limit the number of requests that the OpenAI API provider can receive across the entire site every hour.';
$string['globalratelimit'] = 'Maximum number of site-wide requests';
$string['globalratelimit_desc'] = 'The number of site-wide requests allowed per hour.';
$string['orgid'] = 'OpenAI organization ID';
$string['orgid_desc'] = 'Get your OpenAI organization ID from your <a href="https://platform.openai.com/account/org-settings" target="_blank">OpenAI account</a>.';

$string['privacy:metadata'] = 'The Course sentiment block does not store any personal data.';
$string['privacy:metadata:block_coursesentiment:externalpurpose'] = 'This information is sent to the OpenAI API in order for a response to be generated. Your OpenAI account settings may change how OpenAI stores and retains this data. No user data is explicitly sent to OpenAI or stored in Moodle LMS by this plugin.';
$string['privacy:metadata:block_coursesentiment:model'] = 'The model used to generate the response.';
$string['privacy:metadata:block_coursesentiment:forummessages'] = 'The forum messages text used to generate the analysis.';


$string['restricttocategory'] = 'Restrict to category';
$string['restricttocategory_help'] = 'To restrict use of Course Sentiment to courses within a category, select the category or categories from the list.';
$string['caregories'] = 'Categories';

$string['forumanalysistask'] = 'Forum Sentiment Analysis task for courses';

$string['provider'] = 'AI Provider';
$string['providerdesc'] = 'Select AI Provider to use for sentiment analysis.';
$string['usecredchain'] = 'Find AWS credentials using the default provider chain';
$string['api_key'] = 'Access key';
$string['api_region'] = 'Amazon API gateway region';
$string['api_secret'] = 'Secret access key';
$string['aws_information'] = 'Complete the following fields using the information provided by AWS';
$string['debugmessage'] = 'Complete debug logs messages';

$string['therearenodatatoshow'] = 'There aren\'t forums or the data is still processing';
$string['sentiment'] = 'Sentiment';
$string['positives'] = 'Positives';
$string['negatives'] = 'Negatives';
$string['neutral'] = 'Neutral';
$string['mixed'] = 'Mixed';

$string['numbermessages'] = 'Messages number';
$string['numberteachermessages'] = 'Teacher messages number';
$string['numberforums'] = 'Forum number';
$string['lastupdated'] = 'Last update';

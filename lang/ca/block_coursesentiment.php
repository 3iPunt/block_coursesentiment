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

$string['pluginname'] = 'Sentiment del curs';
$string['privacy:metadata'] = 'El bloc de sentiment del curs no desa dades personals.';
$string['coursesentiment:addinstance'] = 'Afegeix un nou bloc de sentiment del curs';
$string['coursesentiment:myaddinstance'] = 'Afegeix un nou bloc de sentiment del curs a la pàgina personal';
$string['coursesentiment:viewcoursecontent'] = 'Veure el bloc de sentiment del curs al nivell del curs';

$string['apikey'] = 'Clau API d\'OpenAI';
$string['apikey_desc'] =
        'Obtén una clau des del teu <a href="https://platform.openai.com/account/api-keys" target="_blank">compte d\'OpenAI</a>.';
$string['model'] = 'Model';
$string['modeldesc'] = 'El model que generarà la resposta';
$string['temperature'] = 'Temperatura';
$string['temperaturedesc'] = 'Controla l\'aleatorietat: com més baixa, més determinista és la resposta.';
$string['maxlength'] = 'Longitud màxima';
$string['maxlengthdesc'] = 'Nombre màxim de tokens a generar.';
$string['topp'] = 'Top P';
$string['toppdesc'] = 'Controla la diversitat mitjançant el mostreig de nucli.';
$string['frequency'] = 'Penalització per freqüència';
$string['frequencydesc'] = 'Penalitza tokens que ja han aparegut.';
$string['presence'] = 'Penalització per presència';
$string['presencedesc'] = 'Penalitza tokens ja existents per afavorir temes nous.';

$string['enableglobalratelimit'] = 'Estableix límit global de peticions';
$string['enableglobalratelimit_desc'] = 'Limita el nombre de peticions a l\'API d\'OpenAI per hora al lloc.';
$string['globalratelimit'] = 'Màxim de peticions globals';
$string['globalratelimit_desc'] = 'Nombre de peticions permeses per hora.';
$string['orgid'] = 'ID de l\'organització d\'OpenAI';
$string['orgid_desc'] =
        'Obté l\'ID de la teva organització des del teu <a href="https://platform.openai.com/account/org-settings" target="_blank">compte d\'OpenAI</a>.';

$string['privacy:metadata:block_coursesentiment:externalpurpose'] =
        'Aquesta informació s\'envia a OpenAI per generar una resposta.';
$string['privacy:metadata:block_coursesentiment:model'] = 'Model usat per generar la resposta.';
$string['privacy:metadata:block_coursesentiment:forummessages'] = 'Missatges del fòrum usats per generar l\'anàlisi.';

$string['restricttocategory'] = 'Restringir a categoria';
$string['restricttocategory_help'] = 'Per restringir l\'ús del bloc a determinades categories, selecciona-les de la llista.';
$string['caregories'] = 'Categories';

$string['forumanalysistask'] = 'Tasca d\'anàlisi de sentiment dels fòrums per als cursos';

$string['provider'] = 'Proveïdor d\'IA';
$string['providerdesc'] = 'Selecciona el proveïdor d\'IA a utilitzar per l\'anàlisi de sentiment.';
$string['usecredchain'] = 'Troba les credencials AWS amb la cadena per defecte';
$string['api_key'] = 'Clau d\'accés';
$string['api_region'] = 'Regió de la passarel·la API d\'Amazon';
$string['api_secret'] = 'Clau secreta';
$string['aws_information'] = 'Omple els camps següents amb la informació d\'AWS';
$string['debugmessage'] = 'Missatges detallats de depuració';

$string['therearenodatatoshow'] = 'No hi ha fòrums o les dades encara s\'estan processant';
$string['sentiment'] = 'Sentiment';
$string['positives'] = 'Positius';
$string['negatives'] = 'Negatius';
$string['neutral'] = 'Neutral';
$string['mixed'] = 'Barrejat';

$string['numbermessages'] = 'Número missatges';
$string['numberteachermessages'] = 'Número missatges professorat';
$string['numbermessagesnotanalyzed'] = 'Número missatges no analitzats';
$string['numberforums'] = 'Número fòrums';
$string['numberdiscussions'] = 'Número debats';
$string['lastupdated'] = 'Darrera actualització';
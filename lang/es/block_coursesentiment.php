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

$string['pluginname'] = 'Sentimiento del curso';
$string['privacy:metadata'] = 'El bloque de Sentimiento del curso no almacena datos personales.';
$string['coursesentiment:addinstance'] = 'Añadir un nuevo bloque de Sentimiento del curso';
$string['coursesentiment:myaddinstance'] = 'Añadir un nuevo bloque de Sentimiento del curso en la página personal';
$string['coursesentiment:viewcoursecontent'] = 'Ver el bloque de Sentimiento del curso a nivel de curso';

$string['apikey'] = 'Clave API de OpenAI';
$string['apikey_desc'] =
        'Consigue una clave desde tu <a href="https://platform.openai.com/account/api-keys" target="_blank">cuenta de OpenAI</a>.';
$string['model'] = 'Modelo';
$string['modeldesc'] = 'Modelo que generará la respuesta';
$string['temperature'] = 'Temperatura';
$string['temperaturedesc'] = 'Controla la aleatoriedad: cuanto más baja, más determinista será el modelo.';
$string['maxlength'] = 'Longitud máxima';
$string['maxlengthdesc'] = 'Número máximo de tokens a generar.';
$string['topp'] = 'Top P';
$string['toppdesc'] = 'Controla la diversidad mediante muestreo de núcleo.';
$string['frequency'] = 'Penalización por frecuencia';
$string['frequencydesc'] = 'Penaliza tokens que ya han aparecido.';
$string['presence'] = 'Penalización por presencia';
$string['presencedesc'] = 'Penaliza tokens que ya existen para fomentar nuevos temas.';

$string['enableglobalratelimit'] = 'Establecer límite global de peticiones';
$string['enableglobalratelimit_desc'] = 'Limita el número de peticiones a la API de OpenAI por hora en todo el sitio.';
$string['globalratelimit'] = 'Máximo de peticiones globales';
$string['globalratelimit_desc'] = 'Número de peticiones permitidas por hora.';
$string['orgid'] = 'ID de organización de OpenAI';
$string['orgid_desc'] =
        'Obtén tu ID de organización desde tu <a href="https://platform.openai.com/account/org-settings" target="_blank">cuenta de OpenAI</a>.';

$string['privacy:metadata:block_coursesentiment:externalpurpose'] =
        'Esta información se envía a OpenAI para generar una respuesta.';
$string['privacy:metadata:block_coursesentiment:model'] = 'Modelo usado para generar la respuesta.';
$string['privacy:metadata:block_coursesentiment:forummessages'] = 'Mensajes del foro usados para generar el análisis.';

$string['restricttocategory'] = 'Restringir a categoría';
$string['restricttocategory_help'] = 'Para restringir el uso del bloque a ciertos cursos, selecciona las categorías.';
$string['caregories'] = 'Categorías';

$string['forumanalysistask'] = 'Tarea de análisis de sentimientos del foro para cursos';

$string['provider'] = 'Proveedor de IA';
$string['providerdesc'] = 'Selecciona el proveedor de IA para el análisis de sentimientos.';
$string['usecredchain'] = 'Usar cadena por defecto de credenciales AWS';
$string['api_key'] = 'Clave de acceso';
$string['api_region'] = 'Región de la API de Amazon';
$string['api_secret'] = 'Clave secreta';
$string['aws_information'] = 'Completa los siguientes campos con los datos de AWS';
$string['debugmessage'] = 'Mensajes detallados de depuración';

$string['therearenodatatoshow'] = 'No hay foros o los datos están siendo procesados';
$string['sentiment'] = 'Sentimiento';
$string['positives'] = 'Positivos';
$string['negatives'] = 'Negativos';
$string['neutral'] = 'Neutro';
$string['mixed'] = 'Mixto';

$string['numbermessages'] = 'Número mensajes';
$string['numberteachermessages'] = 'Número mensajes profesorado';
$string['numbermessagesnotanalyzed'] = 'Número mensajes no analizados';
$string['numberforums'] = 'Número foros';
$string['numberdiscussions'] = 'Número debates';
$string['lastupdated'] = 'Última actualización';
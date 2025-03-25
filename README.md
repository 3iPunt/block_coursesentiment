# Course sentiment #

Per descomptat! Aquí tens una **descripció professional en anglès** que pots utilitzar com a **resum del projecte a GitHub** (per exemple, al `README.md` o a la descripció del repositori):

---

### `block_coursesentiment` — Moodle Block for Forum Sentiment Analysis

The `block_coursesentiment` plugin provides insight into the emotional tone of forum discussions within a Moodle course. It uses AI-based sentiment analysis (via AWS Comprehend or OpenAI) to evaluate forum messages and present aggregated metrics about student engagement and course atmosphere.

Key features:
- Automatically analyzes all forum messages within a course.
- Detects and classifies sentiment as **positive**, **negative**, **neutral**, or **mixed**.
- Provides course-level summaries, including:
   - Number of forums and messages.
   - Sentiment distribution.
   - Teacher engagement (reply count and average reply time).
- Supports pluggable AI providers (AWS Comprehend and OpenAI).
- Respects Moodle roles and permissions.
- Includes cron task to automate forum sentiment analysis.
- Offers customizable visual summaries using Moodle's Chart API.

This block is designed to help teachers and instructional designers better understand the emotional climate of their courses and respond proactively to negative patterns.

---

## Installing via uploaded ZIP file ##

1. Log in to your Moodle site as an admin and go to _Site administration >
   Plugins > Install plugins_.
2. Upload the ZIP file with the plugin code. You should only be prompted to add
   extra details if your plugin type is not automatically detected.
3. Check the plugin validation report and finish the installation.

## Installing manually ##

The plugin can be also installed by putting the contents of this directory to

    {your/moodle/dirroot}/blocks/coursesentiment

Afterwards, log in to your Moodle site as an admin and go to _Site administration >
Notifications_ to complete the installation.

Alternatively, you can run

    $ php admin/cli/upgrade.php

to complete the installation from the command line.

## License ##

2024 3ipunt <moodle@tresipunt.com>

This program is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program.  If not, see <https://www.gnu.org/licenses/>.

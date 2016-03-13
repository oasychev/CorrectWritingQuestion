<?php
// This file is part of Correct Writing question type - https://bitbucket.org/oasychev/moodle-plugins/
//
// Correct Writing is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Correct Writing is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * A validation rule, which must expose a validation handler
 *
 * @package    qtype
 * @subpackage correctwriting
 * @copyright  2011 Sychev Oleg
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();
global $CFG;
require_once($CFG->libdir . '/pear/HTML/QuickForm/Rule.php');

class qtype_correctwring_expose_validator extends HTML_QuickForm_Rule {
    /**
     * Returns a JS for exposing validator function qf_errorHandler
     * @param     mixed     Options for the rule
     * @return array
     */
    function getValidationScript($options = null)
    {
        return array('window["qf_errorHandler"] = qf_errorHandler;', 'false');
    }
}
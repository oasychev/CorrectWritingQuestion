<?php
// This file is part of CorrectWriting question type - https://bitbucket.org/oasychev/moodle-plugins/
//
// CorrectWriting question type is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// CorrectWriting is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with CorrectWriting.  If not, see <http://www.gnu.org/licenses/>.


/**
 * Defines error classes for the correct writing question.
 *
 * Errors are teacher errors: e.g. lexical and syntax error in answers,
 * provided by the teacher to the question. All supplied answers must be
 * a valid text in given language!
 *
 * @copyright &copy; 2011  Oleg Sychev
 * @author Oleg Sychev, Volgograd State Technical University
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package questions
 */

defined('MOODLE_INTERNAL') || die();

//Other necessary requires

//Base class for answer error
abstract class  qtype_correctwriting_answer_error {
    //Error position as qtype_correctwriting_node_position object
    public $position;
    //Language name
    public $languagename;
    //Error message, generated by constructor
    public $errormsg;
}
?>
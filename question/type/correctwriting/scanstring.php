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
 * Defines a scanning string  options
 *
 * Abstract analyzer class defines an interface any analyzer should implement.
 * Analyzers have state, i.e. for each analyzed pair of strings there will be differrent analyzer
 *
 * @copyright &copy; 2013  Oleg Sychev
 * @author Oleg Sychev, Volgograd State Technical University
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package questions
 */
define('AJAX_SCRIPT', true);
require_once('../../../config.php');
require_once($CFG->dirroot . '/blocks/formal_langs/block_formal_langs.php');
require_once($CFG->dirroot . '/question/editlib.php');
require_once($CFG->libdir  . '/filelib.php');
require_once($CFG->libdir  . '/formslib.php');
require_once($CFG->dirroot . '/question/type/edit_question_form.php');
require_once($CFG->dirroot . '/question/type/correctwriting/edit_correctwriting_form.php');

$PAGE->set_context(context_system::instance());
require_login();

$langid  =  required_param('lang', PARAM_INT);
$text = required_param('scannedtext', PARAM_RAW);
$shouldperformparse = optional_param('parse', 0, PARAM_INT);
$isyntaxanalyzerenabled = optional_param('issyntaxanalyzerenabled', 0, PARAM_INT);
$isenumanalyzerenabled = optional_param('isenumanalyzerenabled', 0, PARAM_INT);
$allowinvalidsyntaxanswers = optional_param('allowinvalidsyntaxanswers', 0, PARAM_INT);

$language = block_formal_langs::lang_object($langid);

if ($language == null) {
    echo '{"tokens": [], "errors": ""}';
} else {
    $string = $language->create_from_string($text);
    $stream = $string->stream;
    $tokens = $stream->tokens;
    $form = 'qtype_correctwriting_edit_form';
    if(count($tokens)) {
        $tokenvalues = array();
        $errormessages = $form::convert_tokenstream_errors_to_formatted_messages($string);
        // If we already have mistakes - do not ignore them
        if (!$isyntaxanalyzerenabled || $language->could_parse() == false || core_text::strlen($errormessages) != 0) {
            foreach($tokens as $token) {
                $tokenvalues[] = (string)($token->value());
            }
            if ($isenumanalyzerenabled && $language->could_parse() && core_text::strlen($errormessages) == 0) {
                $tree = $string->syntax_tree(false);
                if (count($tree) > 1) {
                    $errormessages = $form::make_enum_analyzer_required_valid_answer_error($string);
                }
            }
        } else {
            $tree = $string->syntax_tree(!$allowinvalidsyntaxanswers);
            $filter = function($o) { return is_a($o, 'block_formal_langs_parsing_error'); };
            $errors = array_filter($string->errors, $filter);
            $treeisinvalid = count($tree) > 1;
            if ($isenumanalyzerenabled) {
                if ($allowinvalidsyntaxanswers) {
                    if ($treeisinvalid) {
                        $errormessages = $form::make_enum_analyzer_required_valid_answer_error($string);
                    }
                } else {
                    $errormessages = $form::parsing_errors_to_formatted_messages($string);
                }
            } else {
                if (!$allowinvalidsyntaxanswers) {
                    $errormessages = $form::parsing_errors_to_formatted_messages($string);
                }
            }

            $treelist = $string->tree_to_list();
            foreach($treelist as $node) {
                /** @var block_formal_langs_ast_node_base $node */
                $string = $node->value();
                if (is_object($string)) {
                    $string = $string->string();
                }
                $tokenvalues[] = $string;
            }
        }
        $result = (object)array('tokens' => $tokenvalues, "errors" => $errormessages);
        echo json_encode($result);
    } else {
        echo '{"tokens": [], "errors": ""}';
    }
}
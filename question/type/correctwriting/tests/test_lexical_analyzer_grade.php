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

require_once(dirname(__FILE__) . '/../../../../config.php');
global $CFG;
require_once($CFG->libdir.'/moodlelib.php');
require_once($CFG->dirroot.'/question/type/correctwriting/question.php');
require_once($CFG->dirroot.'/question/type/edit_question_form.php');
require_once($CFG->dirroot.'/question/engine/tests/helpers.php');
require_once($CFG->dirroot.'/question/type/correctwriting/edit_correctwriting_form.php');
require_once($CFG->dirroot.'/question/type/correctwriting/questiontype.php');
require_once($CFG->dirroot.'/blocks/formal_langs/language_simple_english.php');
require_once($CFG->dirroot.'/blocks/formal_langs/language_cpp_parseable_language.php');

class qtype_correctwriting_lexical_analyzer_grade_test extends advanced_testcase {

    /**
     * Test bugs, which occur only when only lexical analyzer is enabled
     */
    public function test_lexical_analyzer_bug_13_06_16_1() {
        $language = new block_formal_langs_language_cpp_parseable_language();
        $question = new qtype_correctwriting_question();
        $question->usecase = false;
        $question->lexicalerrorthreshold = 0.7;
        $question->lexicalerrorweight = 0.01;
        $question->usedlanguage = $language;
        $question->movedmistakeweight = 0.1;
        $question->absentmistakeweight = 0.11;
        $question->addedmistakeweight = 0.12;
        $question->hintgradeborder = 0.75;
        $question->maxmistakepercentage = 0.95;
        $question->qtype = new qtype_correctwriting();
        $question->islexicalanalyzerenabled = 1;
        $question->isenumanalyzerenabled = 0;
        $question->issequenceanalyzerenabled = 0;
        $question->issyntaxanalyzerenabled = 0;
        $answers = array((object)array('id' => 1, 'answer' => 'int a = variable;', 'fraction' => 1.0));
        $question->answers = $answers;
        $question->grade_response(array('answer' => 'inta = variable; asd'));
        /** @var qtype_correctwriting_response_mistake $mistake */
        $mistakes = $question->matchedresults->mistakes();
        $this->assertTrue(count($mistakes) == 2);
        for($i = 0; $i < 2; $i++) {
            $mistake = $mistakes[$i];
            if (get_class($mistake) == 'qtype_correctwriting_lexeme_added_mistake') {
                $this->assertTrue($mistake->responsemistaken == array(5));
            }
        }
    }

    /**
     * Test bugs, which occur only when only lexical analyzer is enabled
     */
    public function test_lexical_analyzer_bug_13_06_16_2() {
        $language = new block_formal_langs_language_cpp_parseable_language();
        $question = new qtype_correctwriting_question();
        $question->usecase = false;
        $question->lexicalerrorthreshold = 0.7;
        $question->lexicalerrorweight = 0.01;
        $question->usedlanguage = $language;
        $question->movedmistakeweight = 0.1;
        $question->absentmistakeweight = 0.11;
        $question->addedmistakeweight = 0.12;
        $question->hintgradeborder = 0.75;
        $question->maxmistakepercentage = 0.95;
        $question->qtype = new qtype_correctwriting();
        $question->islexicalanalyzerenabled = 1;
        $question->isenumanalyzerenabled = 0;
        $question->issequenceanalyzerenabled = 0;
        $question->issyntaxanalyzerenabled = 0;
        $answers = array((object)array('id' => 1, 'answer' => 'Today is a good day to create something!', 'fraction' => 1.0));
        $question->answers = $answers;
        $question->grade_response(array('answer' => 'To day is a good day to create something! asd'));
        /** @var qtype_correctwriting_response_mistake $mistake */
        $mistakes = $question->matchedresults->mistakes();
        $this->assertTrue(count($mistakes) == 2);
        for($i = 0; $i < 2; $i++) {
            $mistake = $mistakes[$i];
            if (get_class($mistake) == 'qtype_correctwriting_lexeme_added_mistake') {
                $this->assertTrue($mistake->responsemistaken == array(9));
            }
        }
    }
}
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

global $CFG;
require_once($CFG->dirroot.'/question/type/correctwriting/enum_analyzer.php');
require_once($CFG->dirroot.'/question/type/correctwriting/enum_catcher.php');
require_once($CFG->dirroot.'/question/type/correctwriting/string_pair.php');
require_once($CFG->dirroot.'/blocks/formal_langs/language_base.php');
require_once($CFG->dirroot.'/question/type/correctwriting/processed_string.php');
require_once($CFG->dirroot.'/blocks/formal_langs/language_cpp_parseable_language.php');
require_once($CFG->dirroot.'/question/type/poasquestion/classes/utf8_string.php');

class qtype_correctwriting_enum_catcher_test extends PHPUnit\Framework\TestCase {

    /**
     *  Test for find enumerations in definition list.
     *  Enumeration only in first definition.
     */
    public function test_definition() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'int k = j / h + t + o - r ; bool g = kill = live ;';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[0][] = array(3,5);
        $expected_result[0][] = array(7,7);
        $expected_result[0][] = array(9,9);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Sequence of definitions.';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[0][2], $result[0][2], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }

    /**
     *  Test for find enumerations in sequence of mod.
     *  Enumeration elements from h to r.
     */
    public function test_sequence_of_mod() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'k = j % h % t % o % r ;';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[0][] = array(4,4);
        $expected_result[0][] = array(6,6);
        $expected_result[0][] = array(8,8);
        $expected_result[0][] = array(10,10);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Sequence of mod.';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[0][2], $result[0][2], $error_string);
        $this->assertEquals($expected_result[0][3], $result[0][3], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);

    }

    /**
     *  Test for find enumerations in sequence of plus with multiple.
     *  Two enumerations, first from sequence of plus, second multiple operation.
     */
    public function test_sequence_of_plus_with_multiple() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'k = ( j + h + t + o + r ) * f ;';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[0][] = array(3,3);
        $expected_result[0][] = array(5,5);
        $expected_result[0][] = array(7,7);
        $expected_result[0][] = array(9,9);
        $expected_result[0][] = array(11,11);
        $expected_result[1][] = array(2,12);
        $expected_result[1][] = array(14,14);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Sequence of plus with multiple.';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[0][2], $result[0][2], $error_string);
        $this->assertEquals($expected_result[0][3], $result[0][3], $error_string);
        $this->assertEquals($expected_result[0][4], $result[0][4], $error_string);
        $this->assertEquals($expected_result[1][0], $result[1][0], $error_string);
        $this->assertEquals($expected_result[1][1], $result[1][1], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }

    /**
     *  Test for find enumerations in sequence of plus add multiple.
     *  Four enumerations, first from sequence of plus, second, third, fourth from multiple operations.
     */
    public function test_sequence_of_plus_and_multiple() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'k = ( j * h + t * o + r ) * f ;';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[0][] = array(3,5);
        $expected_result[0][] = array(7,9);
        $expected_result[0][] = array(11,11);
        $expected_result[1][] = array(2,12);
        $expected_result[1][] = array(14,14);
        $expected_result[2][] = array(3,3);
        $expected_result[2][] = array(5,5);
        $expected_result[3][] = array(7,7);
        $expected_result[3][] = array(9,9);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Sequence of plus and multiple.';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[0][2], $result[0][2], $error_string);
        $this->assertEquals($expected_result[1][0], $result[1][0], $error_string);
        $this->assertEquals($expected_result[1][1], $result[1][1], $error_string);
        $this->assertEquals($expected_result[2][0], $result[2][0], $error_string);
        $this->assertEquals($expected_result[2][1], $result[2][1], $error_string);
        $this->assertEquals($expected_result[3][0], $result[3][0], $error_string);
        $this->assertEquals($expected_result[3][1], $result[3][1], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }

    /**
     *  Test for find enumerations in sequence of plus add multiple with type casting.
     *  Four enumerations, first from sequence of plus, second, third, fourth from multiple operations.
     */
    public function test_sequence_of_plus_and_multiple_with_type_casting() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'k = (int)( j * h + t * o + r ) * f ;';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[0][] = array(6,8);
        $expected_result[0][] = array(10,12);
        $expected_result[0][] = array(14,14);
        $expected_result[1][] = array(2,15);
        $expected_result[1][] = array(17,17);
        $expected_result[2][] = array(6,6);
        $expected_result[2][] = array(8,8);
        $expected_result[3][] = array(10,10);
        $expected_result[3][] = array(12,12);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Sequence of plus and multiple with type casting.';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[0][2], $result[0][2], $error_string);
        $this->assertEquals($expected_result[1][0], $result[1][0], $error_string);
        $this->assertEquals($expected_result[1][1], $result[1][1], $error_string);
        $this->assertEquals($expected_result[2][0], $result[2][0], $error_string);
        $this->assertEquals($expected_result[2][1], $result[2][1], $error_string);
        $this->assertEquals($expected_result[3][0], $result[3][0], $error_string);
        $this->assertEquals($expected_result[3][1], $result[3][1], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }

    /**
     *  Test for find enumerations in sequence of plus, multiple, division and mod.
     *  Four enumerations, first from sequence of plus, second from multiple operation,
     *  third from division sequence, fourth from mod sequence.
     */
    public function test_sequence_of_plus_and_multiple_mod_and_div() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'k = ( j * h + t / o / r ) % f % g ;';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[0][] = array(3,5);
        $expected_result[0][] = array(7,11);
        $expected_result[1][] = array(3,3);
        $expected_result[1][] = array(5,5);
        $expected_result[2][] = array(9,9);
        $expected_result[2][] = array(11,11);
        $expected_result[3][] = array(14,14);
        $expected_result[3][] = array(16,16);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Sequence of plus and multiple, mod and div.';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[1][0], $result[1][0], $error_string);
        $this->assertEquals($expected_result[1][1], $result[1][1], $error_string);
        $this->assertEquals($expected_result[2][0], $result[2][0], $error_string);
        $this->assertEquals($expected_result[2][1], $result[2][1], $error_string);
        $this->assertEquals($expected_result[3][0], $result[3][0], $error_string);
        $this->assertEquals($expected_result[3][1], $result[3][1], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }

    /**
     *  Test for find enumerations in sequence of subtraction.
     *  Enumeration from h to r.
     */
    public function test_sequence_of_sub() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'k = j - h - t - o - r ;';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[0][] = array(4,4);
        $expected_result[0][] = array(6,6);
        $expected_result[0][] = array(8,8);
        $expected_result[0][] = array(10,10);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Sequence of sub.';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[0][2], $result[0][2], $error_string);
        $this->assertEquals($expected_result[0][3], $result[0][3], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }

    /**
     *  Test for find enumerations in sequence of division.
     *  Enumeration from h to r.
     */
    public function test_sequence_of_div() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'k = j / h / t / o / r ;';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[0][] = array(4,4);
        $expected_result[0][] = array(6,6);
        $expected_result[0][] = array(8,8);
        $expected_result[0][] = array(10,10);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Sequence of div.';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[0][2], $result[0][2], $error_string);
        $this->assertEquals($expected_result[0][3], $result[0][3], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }

    /**
     *  Test for find enumerations in sequence of plus.
     *  Enumeration from j to r.
     */
    public function testsequence_of_plus() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'k = j + h + t + o + r ;';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[0][] = array(2,2);
        $expected_result[0][] = array(4,4);
        $expected_result[0][] = array(6,6);
        $expected_result[0][] = array(8,8);
        $expected_result[0][] = array(10,10);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Sequence plus.';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[0][2], $result[0][2], $error_string);
        $this->assertEquals($expected_result[0][3], $result[0][3], $error_string);
        $this->assertEquals($expected_result[0][4], $result[0][4], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }

    /**
     *  Test for find enumerations in sequence of multiple.
     *  Enumeration from j to r.
     */
    public function test_sequence_of_mul() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'k = j * h * t * o * r ;';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[0][] = array(2,2);
        $expected_result[0][] = array(4,4);
        $expected_result[0][] = array(6,6);
        $expected_result[0][] = array(8,8);
        $expected_result[0][] = array(10,10);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Sequence of mul.';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[0][2], $result[0][2], $error_string);
        $this->assertEquals($expected_result[0][3], $result[0][3], $error_string);
        $this->assertEquals($expected_result[0][4], $result[0][4], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }

    /**
     *  Test for find enumerations in sequence of assign.
     *  Enumeration from k to o.
     */
    public function test_sequence_of_assign() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'k = j = h = t = o = r ;';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[0][] = array(0,0);
        $expected_result[0][] = array(2,2);
        $expected_result[0][] = array(4,4);
        $expected_result[0][] = array(6,6);
        $expected_result[0][] = array(8,8);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Sequence of assign.';
        $this->assertEquals(5, count($result[0]));
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[0][2], $result[0][2], $error_string);
        $this->assertEquals($expected_result[0][3], $result[0][3], $error_string);
        $this->assertEquals($expected_result[0][4], $result[0][4], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }

    /**
     *  Test for find enumerations in bit operations.
     *  Four enumerations, first form bit or, second from bit and, third from bit and, fourth from bit xor.
     */
    public function test_bit_operations() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'k = j & h | t ^ o & r ;';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[0][] = array(2,4);
        $expected_result[0][] = array(6,10);
        $expected_result[1][] = array(2,2);
        $expected_result[1][] = array(4,4);
        $expected_result[2][] = array(8,8);
        $expected_result[2][] = array(10,10);
        $expected_result[3][] = array(6,6);
        $expected_result[3][] = array(8,10);
        $result = $temp->getEnums();

        $error_string = 'Error enumeration catcher found!Bit operators.';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[1][0], $result[1][0], $error_string);
        $this->assertEquals($expected_result[1][1], $result[1][1], $error_string);
        $this->assertEquals($expected_result[2][0], $result[2][0], $error_string);
        $this->assertEquals($expected_result[2][1], $result[2][1], $error_string);
        $this->assertEquals($expected_result[3][0], $result[3][0], $error_string);
        $this->assertEquals($expected_result[3][1], $result[3][1], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }

    /**
     *  Test for find enumerations in sequence of definitions variables and pointers.
     *  Enumeration, from first * to h.
     */
    public function test_definition_variables_and_pointers() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'int * k , j , *h;';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[0][] = array(1,2);
        $expected_result[0][] = array(4,4);
        $expected_result[0][] = array(6,7);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Definition of variables and pointers.';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[0][2], $result[0][2], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }
    /**
     *  Test for find enumerations in sequence of definitions variables and pointers with assign.
     *  Enumeration, from first * to e.
     */
    public function test_definition_variables_and_pointers_with_assign() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'int * k , j , *h=z=e;';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[0][] = array(1,2);
        $expected_result[0][] = array(4,4);
        $expected_result[0][] = array(6,11);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Definition of variables and pointers.';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[0][2], $result[0][2], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }

    /**
     *  Test for find enumerations in sequence of definitions variables.
     *  Enumeration, from first k to h.
     */
    public function test_definition_variables() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'int k , j , h;';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[0][] = array(1,1);
        $expected_result[0][] = array(3,3);
        $expected_result[0][] = array(5,5);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Definition variales.';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[0][2], $result[0][2], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }

    /**
     *  Test for find enumerations in sequence of definitions variables with assign.
     *  Enumeration, from first k to h.
     */
    public function test_definition_variables_with_assign() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'int k=2 , j=u , h;';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[0][] = array(1,3);
        $expected_result[0][] = array(5,7);
        $expected_result[0][] = array(9,9);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Definition variables with assign.';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[0][2], $result[0][2], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }

    /**
     *  Test for find enumerations in sequence of definitions variables with assign(use dependences).
     *  Enumeration, from first k to h.
     */
    public function test_definition_variables_with_assign_heavy() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'int k=2 , j=k , h;';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[0][] = array(1,3);
        $expected_result[0][] = array(5,7);
        $expected_result[0][] = array(9,9);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Definition variables with assign, heavy.';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[0][2], $result[0][2], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }

    /**
     *  Test for find enumerations in definition of array.
     *  No enumerations.
     */
    public function test_definition_array() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'int k [ 5 ] = { 1 , 2 , 4 , 3 , 0 } ;';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Definition variables with assign, heavy.';
        $this->assertEquals($expected_result, $result, $error_string);
    }

    /**
     *  Test for find enumerations in definition of enumeration.
     *  Enumeration from Int to Float.
     */
    public function test_definition_enum() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'enum types { Int , Char , Double , Float } ;';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[0][] = array(3,3);
        $expected_result[0][] = array(5,5);
        $expected_result[0][] = array(7,7);
        $expected_result[0][] = array(9,9);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Definition enum.';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[0][2], $result[0][2], $error_string);
        $this->assertEquals($expected_result[0][3], $result[0][3], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }

    /**
     *  Test for find enumerations in definition of enumeration with assign.
     *  Enumeration from Int to Float.
     */
    public function test_definition_enum_with_assign() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'enum suit { Int = 1 , Float } ;';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[0][] = array(3,5);
        $expected_result[0][] = array(7,7);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Definition enum with assign.';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }

    /**
     *  Test for find enumerations in definition of struct.
     *  Enumeration from int to prelast ';'.
     */
    public function test_definition_struct() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'struct suit { int a ; char * b ; float k ; } ;';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[0][] = array(3,5);
        $expected_result[0][] = array(6,9);
        $expected_result[0][] = array(10,12);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Definition struct.';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[0][2], $result[0][2], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }
    /**
     *  Test for find enumerations in logical operators.
     *  Four enumerations, first from logical or, second from logical and, third from logical equal, fourth from logical not equal.
     */
    public function test_logical_operators() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'k = j == h && t != o && r  || f ;';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[0][] = array(2,10);
        $expected_result[0][] = array(12,12);
        $expected_result[1][] = array(2,4);
        $expected_result[1][] = array(6,8);
        $expected_result[1][] = array(10,10);
        $expected_result[2][] = array(2,2);
        $expected_result[2][] = array(4,4);
        $expected_result[3][] = array(6,6);
        $expected_result[3][] = array(8,8);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Definition enum.';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[1][0], $result[1][0], $error_string);
        $this->assertEquals($expected_result[1][1], $result[1][1], $error_string);
        $this->assertEquals($expected_result[1][2], $result[1][2], $error_string);
        $this->assertEquals($expected_result[2][0], $result[2][0], $error_string);
        $this->assertEquals($expected_result[2][1], $result[2][1], $error_string);
        $this->assertEquals($expected_result[3][0], $result[3][0], $error_string);
        $this->assertEquals($expected_result[3][1], $result[3][1], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }

    /**
     *  Test for find enumerations in logical operators with brackets.
     *  Five enumerations, first from logical or, second from logical and, third from logical and, fourth from logical equal,
     *  fifth from logical not equal.
     */
    public function test_logical_operators_and_brackets() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'k = j == h && (t != o && r  || f );';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[0][] = array(7,11);
        $expected_result[0][] = array(13,13);
        $expected_result[1][] = array(2,4);
        $expected_result[1][] = array(6,14);
        $expected_result[2][] = array(7,9);
        $expected_result[2][] = array(11,11);
        $expected_result[4][] = array(7,7);
        $expected_result[4][] = array(9,9);
        $expected_result[3][] = array(2,2);
        $expected_result[3][] = array(4,4);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Logical_operations and brackets.';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[1][0], $result[1][0], $error_string);
        $this->assertEquals($expected_result[1][1], $result[1][1], $error_string);
        $this->assertEquals($expected_result[2][0], $result[2][0], $error_string);
        $this->assertEquals($expected_result[2][1], $result[2][1], $error_string);
        $this->assertEquals($expected_result[3][0], $result[3][0], $error_string);
        $this->assertEquals($expected_result[3][1], $result[3][1], $error_string);
        $this->assertEquals($expected_result[4][0], $result[4][0], $error_string);
        $this->assertEquals($expected_result[4][1], $result[4][1], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }

    /**
     *  Test for find enumerations in math operators and plus short form.
     *  Three enumerations, first from plus, second from next plus, third from multiple.
     */
    public function test_use_shortform_plus() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'k += j + h - (3 - o + r  * f );';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[0][] = array(2,2);
        $expected_result[0][] = array(4,4);
        $expected_result[1][] = array(9,9);
        $expected_result[1][] = array(11,13);
        $expected_result[2][] = array(11,11);
        $expected_result[2][] = array(13,13);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Shortform of plus.';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[1][0], $result[1][0], $error_string);
        $this->assertEquals($expected_result[1][1], $result[1][1], $error_string);
        $this->assertEquals($expected_result[2][0], $result[2][0], $error_string);
        $this->assertEquals($expected_result[2][1], $result[2][1], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }

    /**
     *  Test for find enumerations in math operators and subtraction short form.
     *  Three enumerations, first from plus, second from next plus, third from multiple.
     */
    public function test_use_shortform_sub() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'k -= j + h - (3 - o + r  * f );';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[0][] = array(2,2);
        $expected_result[0][] = array(4,4);
        $expected_result[1][] = array(9,9);
        $expected_result[1][] = array(11,13);
        $expected_result[2][] = array(11,11);
        $expected_result[2][] = array(13,13);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Shortform of sub.';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[1][0], $result[1][0], $error_string);
        $this->assertEquals($expected_result[1][1], $result[1][1], $error_string);
        $this->assertEquals($expected_result[2][0], $result[2][0], $error_string);
        $this->assertEquals($expected_result[2][1], $result[2][1], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }

    /**
     *  Test for find enumerations in math operators and multiple short form.
     *  Three enumerations, first from plus, second from next plus, third from multiple.
     */
    public function test_use_shortform_mul() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'k *= j + h - (3 - o + r  * f );';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[0][] = array(2,2);
        $expected_result[0][] = array(4,4);
        $expected_result[1][] = array(9,9);
        $expected_result[1][] = array(11,13);
        $expected_result[2][] = array(11,11);
        $expected_result[2][] = array(13,13);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Shortform of mul.';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[1][0], $result[1][0], $error_string);
        $this->assertEquals($expected_result[1][1], $result[1][1], $error_string);
        $this->assertEquals($expected_result[2][0], $result[2][0], $error_string);
        $this->assertEquals($expected_result[2][1], $result[2][1], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }

    /**
     *  Test for find enumerations in math operators and division short form.
     *  Three enumerations, first from plus, second from next plus, third from multiple.
     */
    public function test_use_shortform_div() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'k /= j + h - (3 - o + r  * f );';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[0][] = array(2,2);
        $expected_result[0][] = array(4,4);
        $expected_result[1][] = array(9,9);
        $expected_result[1][] = array(11,13);
        $expected_result[2][] = array(11,11);
        $expected_result[2][] = array(13,13);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Shortform of div.';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[1][0], $result[1][0], $error_string);
        $this->assertEquals($expected_result[1][1], $result[1][1], $error_string);
        $this->assertEquals($expected_result[2][0], $result[2][0], $error_string);
        $this->assertEquals($expected_result[2][1], $result[2][1], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }

    /**
     *  Test for find enumerations in math operators and two forms of increment.
     *  Three enumerations, first from plus, second from next plus, third from multiple.
     */
    public function test_use_shortform_inc() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'k = j + h++ - (3 - ++o + r  * f );';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[0][] = array(2,2);
        $expected_result[0][] = array(4,5);
        $expected_result[1][] = array(10,11);
        $expected_result[1][] = array(13,15);
        $expected_result[2][] = array(13,13);
        $expected_result[2][] = array(15,15);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Shortform of inc.';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[1][0], $result[1][0], $error_string);
        $this->assertEquals($expected_result[1][1], $result[1][1], $error_string);
        $this->assertEquals($expected_result[2][0], $result[2][0], $error_string);
        $this->assertEquals($expected_result[2][1], $result[2][1], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }

    /**
     *  Test for find enumerations in math operators and two forms of decrement.
     *  Three enumerations, first from plus, second from next plus, third from multiple.
     */
    public function test_use_shortform_dec() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'k = j + h-- - (3 - --o + r  * f );';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[0][] = array(2,2);
        $expected_result[0][] = array(4,5);
        $expected_result[1][] = array(10,11);
        $expected_result[1][] = array(13,15);
        $expected_result[2][] = array(13,13);
        $expected_result[2][] = array(15,15);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Shortform of dec.';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[1][0], $result[1][0], $error_string);
        $this->assertEquals($expected_result[1][1], $result[1][1], $error_string);
        $this->assertEquals($expected_result[2][0], $result[2][0], $error_string);
        $this->assertEquals($expected_result[2][1], $result[2][1], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }

    /**
     *  Test for find enumerations in sequence of assign.
     *  Enumeration from a to k.
     */
    public function test_sequence_of_assign_definition()
    {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'int t = a = b = c = k = 5 ;';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[0][] = array(3, 3);
        $expected_result[0][] = array(5, 5);
        $expected_result[0][] = array(7, 7);
        $expected_result[0][] = array(9, 9);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Sequence of assign.';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[0][2], $result[0][2], $error_string);
        $this->assertEquals($expected_result[0][3], $result[0][3], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }

    /**
     *  Test for find enumerations in definition of struct inside class.
     */
    public function test_definition_struct_in_class() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'class cl { public : struct suit { int a ; char * b ; float k ; } first; float second;} ;';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[0][] = array(5,20);
        $expected_result[0][] = array(21,23);
        $expected_result[1][] = array(8,10);
        $expected_result[1][] = array(11,14);
        $expected_result[1][] = array(15,17);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Definition struct inside class.';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[1][0], $result[1][0], $error_string);
        $this->assertEquals($expected_result[1][1], $result[1][1], $error_string);
        $this->assertEquals($expected_result[1][2], $result[1][2], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }

    /**
     *  Test for find enumerations in definition of function in class.
     */
    public function test_definition_function_in_class() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'class cl { public : int suit() { int a ; char * b ; float k ; return 0; } float second;} ;';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[0][] = array(5,23);
        $expected_result[0][] = array(24,26);
        $expected_result[1][] = array(10,12);
        $expected_result[1][] = array(13,16);
        $expected_result[1][] = array(17,19);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Definition function inside class.';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[1][0], $result[1][0], $error_string);
        $this->assertEquals($expected_result[1][1], $result[1][1], $error_string);
        $this->assertEquals($expected_result[1][2], $result[1][2], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }

    /**
     *  Test for find enumerations in definition of struct in struct.
     */
    public function test_definition_struct_in_struct() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'struct st {struct suit { int a ; char * b ; float k ; } first; float second;} ;';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[0][] = array(3,18);
        $expected_result[0][] = array(19,21);
        $expected_result[1][] = array(6,8);
        $expected_result[1][] = array(9,12);
        $expected_result[1][] = array(13,15);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Definition struct inside struct.';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[1][0], $result[1][0], $error_string);
        $this->assertEquals($expected_result[1][1], $result[1][1], $error_string);
        $this->assertEquals($expected_result[1][2], $result[1][2], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }

    /**
     *  Test for find enumerations in definition of struct in union.
     */
    public function test_definition_struct_in_union() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'union un {struct suit { int a ; char * b ; float k ; } first; float second;} ;';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[0][] = array(3,18);
        $expected_result[0][] = array(19,21);
        $expected_result[1][] = array(6,8);
        $expected_result[1][] = array(9,12);
        $expected_result[1][] = array(13,15);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Definition struct in union.';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[1][0], $result[1][0], $error_string);
        $this->assertEquals($expected_result[1][1], $result[1][1], $error_string);
        $this->assertEquals($expected_result[1][2], $result[1][2], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }

    /**
     *  Test for find enumerations in definition of union in class.
     */
    public function test_definition_union_in_class() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'class cl {union suit { int a ; char * b ; float k ; } first; float second;} ;';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[0][] = array(3,18);
        $expected_result[0][] = array(19,21);
        $expected_result[1][] = array(6,8);
        $expected_result[1][] = array(9,12);
        $expected_result[1][] = array(13,15);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Definition union in class.';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[1][0], $result[1][0], $error_string);
        $this->assertEquals($expected_result[1][1], $result[1][1], $error_string);
        $this->assertEquals($expected_result[1][2], $result[1][2], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }

    /**
     *  Test for find enumerations in definition of union in struct.
     */
    public function test_definition_union_in_struct() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'struct st {union suit { int a ; char * b ; float k ; } first; float second;} ;';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[0][] = array(3,18);
        $expected_result[0][] = array(19,21);
        $expected_result[1][] = array(6,8);
        $expected_result[1][] = array(9,12);
        $expected_result[1][] = array(13,15);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Definition union in struct.';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[1][0], $result[1][0], $error_string);
        $this->assertEquals($expected_result[1][1], $result[1][1], $error_string);
        $this->assertEquals($expected_result[1][2], $result[1][2], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }

    /**
     *  Test for find enumerations in definition of union in union.
     */
    public function test_definition_union_in_union() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'union un {union suit { int a ; char * b ; float k ; } first; float second;} ;';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[0][] = array(3,18);
        $expected_result[0][] = array(19,21);
        $expected_result[1][] = array(6,8);
        $expected_result[1][] = array(9,12);
        $expected_result[1][] = array(13,15);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Definition union in union.';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[1][0], $result[1][0], $error_string);
        $this->assertEquals($expected_result[1][1], $result[1][1], $error_string);
        $this->assertEquals($expected_result[1][2], $result[1][2], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }

    /**
     *  Test for find enumerations in definition of class in class.
     */
    public function test_definition_class_in_class() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'class cl { class suit { public: int a ; char * b ; float k ; private: int d,c; }; float second;};';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[0][] = array(3,26);
        $expected_result[0][] = array(27,29);
        $expected_result[1][] = array(8,10);
        $expected_result[1][] = array(11,14);
        $expected_result[1][] = array(15,17);
        $expected_result[2][] = array(6,17);
        $expected_result[2][] = array(18,24);
        $expected_result[3][] = array(21,21);
        $expected_result[3][] = array(23,23);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Definition class in class';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[1][0], $result[1][0], $error_string);
        $this->assertEquals($expected_result[1][1], $result[1][1], $error_string);
        $this->assertEquals($expected_result[1][2], $result[1][2], $error_string);
        $this->assertEquals($expected_result[2][0], $result[2][0], $error_string);
        $this->assertEquals($expected_result[2][1], $result[2][1], $error_string);
        $this->assertEquals($expected_result[3][0], $result[3][0], $error_string);
        $this->assertEquals($expected_result[3][1], $result[3][1], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }

    /**
     *  Test for find enumerations in definition of class in struct.
     */
    public function test_definition_class_in_struct() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'struct st {class suit { public: int a ; char * b ; float k ; private: int d,c; }; float second;} ;';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[0][] = array(3,26);
        $expected_result[0][] = array(27,29);
        $expected_result[1][] = array(8,10);
        $expected_result[1][] = array(11,14);
        $expected_result[1][] = array(15,17);
        $expected_result[2][] = array(6,17);
        $expected_result[2][] = array(18,24);
        $expected_result[3][] = array(21,21);
        $expected_result[3][] = array(23,23);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Definition class in struct.';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[1][0], $result[1][0], $error_string);
        $this->assertEquals($expected_result[1][1], $result[1][1], $error_string);
        $this->assertEquals($expected_result[1][2], $result[1][2], $error_string);
        $this->assertEquals($expected_result[2][0], $result[2][0], $error_string);
        $this->assertEquals($expected_result[2][1], $result[2][1], $error_string);
        $this->assertEquals($expected_result[3][0], $result[3][0], $error_string);
        $this->assertEquals($expected_result[3][1], $result[3][1], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }

    /**
     *  Test for find enumerations in definition of class in union.
     */
    public function test_definition_class_in_union() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'union un {class suit { public: int a ; char * b ; float k ; private: int b,c; }; float second;};';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[0][] = array(3,26);
        $expected_result[0][] = array(27,29);
        $expected_result[1][] = array(8,10);
        $expected_result[1][] = array(11,14);
        $expected_result[1][] = array(15,17);
        $expected_result[2][] = array(6,17);
        $expected_result[2][] = array(18,24);
        $expected_result[3][] = array(21,21);
        $expected_result[3][] = array(23,23);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Definition class in union';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[1][0], $result[1][0], $error_string);
        $this->assertEquals($expected_result[1][1], $result[1][1], $error_string);
        $this->assertEquals($expected_result[1][2], $result[1][2], $error_string);
        $this->assertEquals($expected_result[2][0], $result[2][0], $error_string);
        $this->assertEquals($expected_result[2][1], $result[2][1], $error_string);
        $this->assertEquals($expected_result[3][0], $result[3][0], $error_string);
        $this->assertEquals($expected_result[3][1], $result[3][1], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }

    /**
     *  Test for find enumerations in definition of union.
     *  Enumeration from int to prelast ';'.
     */
    public function test_definition_union() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'union suit { int a ; char * b ; float k ; } ;';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[0][] = array(3,5);
        $expected_result[0][] = array(6,9);
        $expected_result[0][] = array(10,12);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Definition union.';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[0][2], $result[0][2], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }

    /**
     *  Test for find enumerations in definition of class.
     *  Enumeration from int to prelast ';'.
     */
    public function test_definition_class() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'class suit { int a ; char * b ; float k ; } ;';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[0][] = array(3,5);
        $expected_result[0][] = array(6,9);
        $expected_result[0][] = array(10,12);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Definition class.';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[0][2], $result[0][2], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }

    /**
     *  Test for find enumerations in definition of class.
     *  Enumeration from int to prelast ';'.
     */
    public function test_definition_class_with_visibility_modify() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'class suit { public: int a ; char * b ; float k ; private: int d,c; } ;';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[0][] = array(5,7);
        $expected_result[0][] = array(8,11);
        $expected_result[0][] = array(12,14);
        $expected_result[1][] = array(3,14);
        $expected_result[1][] = array(15,21);
        $expected_result[2][] = array(18,18);
        $expected_result[2][] = array(20,20);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Definition class with visibitility modificators.';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[0][2], $result[0][2], $error_string);
        $this->assertEquals($expected_result[1][0], $result[1][0], $error_string);
        $this->assertEquals($expected_result[1][1], $result[1][1], $error_string);
        $this->assertEquals($expected_result[2][1], $result[2][1], $error_string);
        $this->assertEquals($expected_result[2][0], $result[2][0], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }

    /**
     *  Test for find enumerations in logical operators.
     *  Four enumerations, first from logical or, second from logical and, third from logical equal, fourth from logical not equal.
     */
    public function test_logical_operators_without_defenition() {
        $lang = new block_formal_langs_language_cpp_parseable_language();
        $string = 'j == h && t != o && r  || f ;';
        $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
        $tree = $correct->syntaxtree;
        $temp = new qtype_correctwriting_enum_catcher($tree);
        $expected_result = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[] = array();
        $expected_result[0][] = array(0,8);
        $expected_result[0][] = array(10,10);
        $expected_result[1][] = array(0,2);
        $expected_result[1][] = array(4,6);
        $expected_result[1][] = array(8,8);
        $expected_result[2][] = array(0,0);
        $expected_result[2][] = array(2,2);
        $expected_result[3][] = array(4,4);
        $expected_result[3][] = array(6,6);
        $result = $temp->getEnums();
        $error_string = 'Error enumeration catcher found!Definition enum.';
        $this->assertEquals($expected_result[0][0], $result[0][0], $error_string);
        $this->assertEquals($expected_result[0][1], $result[0][1], $error_string);
        $this->assertEquals($expected_result[1][0], $result[1][0], $error_string);
        $this->assertEquals($expected_result[1][1], $result[1][1], $error_string);
        $this->assertEquals($expected_result[1][2], $result[1][2], $error_string);
        $this->assertEquals($expected_result[2][0], $result[2][0], $error_string);
        $this->assertEquals($expected_result[2][1], $result[2][1], $error_string);
        $this->assertEquals($expected_result[3][0], $result[3][0], $error_string);
        $this->assertEquals($expected_result[3][1], $result[3][1], $error_string);
        $this->assertEquals($expected_result, $result, $error_string);
    }
}

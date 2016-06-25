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
require_once($CFG->dirroot.'/question/type/correctwriting/string_pair.php');
require_once($CFG->dirroot.'/blocks/formal_langs/language_base.php');
require_once($CFG->dirroot.'/question/type/correctwriting/processed_string.php');
require_once($CFG->dirroot.'/blocks/formal_langs/language_simple_english.php');

class qtype_correctwriting_enum_analyzer_large_test extends PHPUnit_Framework_TestCase {

      // Test for construct, same tokens in diffrent enumerations.
      public function test__construct_same_tokens() {
          $lang = new block_formal_langs_language_simple_english;
          // Input data.
          $string = 'Today I meet my friends : Sam , Dine and Sam , and my Sam , with their three friends : Sam , Carry and  Sam .';
          $corrected = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
          $string = 'Today I meet my friends : Sam , Dine and Michel , and my neighbors , with their three children : Victoria ,';
          $string = $string.' Carry and Sam .';
          $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
          $enumdescription = array();
          $enumdescription[] = array(new enum_element(6, 6), new enum_element(8, 8), new enum_element(10, 10));
          $enumdescription[] = array(new enum_element(3, 10), new enum_element(13, 25));
          $enumdescription[] = array(new enum_element(21, 21), new enum_element(23, 23), new enum_element(25, 25));
          $pair = new qtype_correctwriting_string_pair(clone $correct, clone $corrected, null);
          $pair->correctstring()->enumerations = $enumdescription;
          $temp = new qtype_correctwriting_enum_analyzer('q',$pair,$lang,true);
          $temp->fill_string_as_text_in_corrected_string($pair->correctedstring());
          // Expected result.
          $string = 'Today I meet my friends : Michel , Dine and Sam , and my neighbors , with their three children : Victoria ,';
          $string = $string.' Carry and Sam .';

          $newcorrect = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
          $enumdescription = array();
          $enumdescription[] = array(new enum_element(10, 10), new enum_element(8, 8), new enum_element(6, 6));
          $enumdescription[] = array(new enum_element(3, 10), new enum_element(13, 25));
          $enumdescription[] = array(new enum_element(21, 21), new enum_element(23, 23), new enum_element(25, 25));
          $indexesintable = array(0, 1, 2, 3, 4, 5, 10, 7, 8, 9, 6, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26);
          $newpair = clone $pair;
          $newpair->set_enum_correct_to_correct($indexesintable);
          $newpair->set_enum_correct_string($newcorrect);
          $newpair->enum_correct_string()->enumerations = $enumdescription;
          $temp->fill_string_as_text_in_corrected_string($newpair->correctedstring());
          $newpair->correctstring()->stream = null;
          $newpair->correctedstring()->stream = null;
          $newpair->enum_correct_string()->stream = null;
          $newpair->correctstring()->stream->tokens;
          $newpair->correctedstring()->stream->tokens;
          $pairs = array();
          $pairs[] = $newpair;

          $string = 'Today I meet my friends : Michel , Dine and Sam , and my neighbors , with their three children : Sam ,';
          $string = $string.' Carry and Victoria .';
          $newcorrect = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
          $enumdescription = array();
          $enumdescription[] = array(new enum_element(10, 10), new enum_element(8, 8), new enum_element(6, 6));
          $enumdescription[] = array(new enum_element(3, 10), new enum_element(13, 25));
          $enumdescription[] = array(new enum_element(25, 25), new enum_element(23, 23), new enum_element(21, 21));
          $indexesintable = array(0, 1, 2, 3, 4, 5, 10, 7, 8, 9, 6, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 25, 22, 23, 24, 21, 26);
          $newpair = clone $pair;
          $newpair->set_enum_correct_to_correct($indexesintable);
          $newpair->set_enum_correct_string($newcorrect);
          $newpair->enum_correct_string()->enumerations = $enumdescription;
          $newpair->correctstring()->stream = null;
          $newpair->correctedstring()->stream = null;
          $newpair->enum_correct_string()->stream = null;
          $newpair->correctstring()->stream->tokens;
          $newpair->correctedstring()->stream->tokens;
          $pairs[] = $newpair;

          $string = 'Today I meet my friends : Sam , Dine and Michel , and my neighbors , with their three children : Victoria ,';
          $string = $string.' Carry and Sam .';
          $newcorrect = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
          $enumdescription = array();
          $enumdescription[] = array(new enum_element(6, 6), new enum_element(8, 8), new enum_element(10, 10));
          $enumdescription[] = array(new enum_element(3, 10), new enum_element(13, 25));
          $enumdescription[] = array(new enum_element(21, 21), new enum_element(23, 23), new enum_element(25, 25));
          $indexesintable = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26);
          $newpair = clone $pair;
          $newpair->set_enum_correct_to_correct($indexesintable);
          $newpair->set_enum_correct_string($newcorrect);
          $newpair->enum_correct_string()->enumerations = $enumdescription;
          $newpair->correctstring()->stream = null;
          $newpair->correctedstring()->stream = null;
          $temp->fill_string_as_text_in_corrected_string($newpair->correctedstring());
          $newpair->enum_correct_string()->stream = null;
          $newpair->correctstring()->stream->tokens;
          $newpair->correctedstring()->stream->tokens;
          $pairs[] = $newpair;

          $string = 'Today I meet my friends : Sam , Dine and Michel , and my neighbors , with their three children : Sam ,';
          $string = $string.' Carry and Victoria .';
          $newcorrect = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
          $enumdescription = array();
          $enumdescription[] = array(new enum_element(6, 6), new enum_element(8, 8), new enum_element(10, 10));
          $enumdescription[] = array(new enum_element(3, 10), new enum_element(13, 25));
          $enumdescription[] = array(new enum_element(25, 25), new enum_element(23, 23), new enum_element(21, 21));
          $indexesintable = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 25, 22, 23, 24, 21, 26);
          $newpair = clone $pair;
          $newpair->set_enum_correct_to_correct($indexesintable);
          $newpair->set_enum_correct_string($newcorrect);
          $newpair->enum_correct_string()->enumerations = $enumdescription;
          $newpair->correctstring()->stream = null;
          $newpair->correctedstring()->stream = null;
          $temp->fill_string_as_text_in_corrected_string($newpair->correctedstring());
          $newpair->enum_correct_string()->stream = null;
          $newpair->correctstring()->stream->tokens;
          $newpair->correctedstring()->stream->tokens;
          $pairs[] = $newpair;
          // Test body.
          $temp = new qtype_correctwriting_enum_analyzer('q',$pair,$lang,false);
          $this->assertEquals($pairs, $temp->result_pairs(), 'Error in work found!Same tokens');
      }

      // Test for construct, six enumerations.
      public function test__construct_six_enums() {
          $lang = new block_formal_langs_language_simple_english;
          $string = 'int q = z * w * r + j + h , p = * f * d + b + e * r * z ;';
          $corrected = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');

          // Input data.
          $string = 'int a = b + c * d * f + k * z * e , p = j + h + t * r * w ;';
          $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
          $enumdescription = array();
          $enumdescription[] = array(new enum_element(1, 15), new enum_element(17, 27));
          $enumdescription[] = array(new enum_element(3, 3), new enum_element(5, 9), new enum_element(11, 15));
          $enumdescription[] = array(new enum_element(19, 19), new enum_element(21, 21), new enum_element(23, 27));
          $enumdescription[] = array(new enum_element(5, 5), new enum_element(7, 7), new enum_element(9, 9));
          $enumdescription[] = array(new enum_element(23, 23), new enum_element(25, 25), new enum_element(27, 27));
          $enumdescription[] = array(new enum_element(11, 11), new enum_element(13, 13), new enum_element(15, 15));
          $pair = new qtype_correctwriting_string_pair(clone $correct, clone $corrected, null);
          $pair->correctstring()->enumerations = $enumdescription;
          $temp = new qtype_correctwriting_enum_analyzer('q',$pair,$lang,true);
          $temp->fill_string_as_text_in_corrected_string($pair->correctedstring());
          // Expected result.
          $string = 'int p = t * w * r + j + h , a = c * f * d + b + e * k * z ;';
          $newcorrect = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
          $newenumdescription = array();
          $newenumdescription[] = array(new enum_element(13, 27), new enum_element(1, 11));
          $newenumdescription[] = array(new enum_element(21, 21), new enum_element(15, 19), new enum_element(23, 27));
          $newenumdescription[] = array(new enum_element(9, 9), new enum_element(11, 11), new enum_element(3, 7));
          $newenumdescription[] = array(new enum_element(15, 15), new enum_element(19, 19), new enum_element(17, 17));
          $newenumdescription[] = array(new enum_element(3, 3), new enum_element(7, 7), new enum_element(5, 5));
          $newenumdescription[] = array(new enum_element(25, 25), new enum_element(27, 27), new enum_element(23, 23));
          $newpair = new qtype_correctwriting_string_pair(clone $correct, clone $corrected, null);
          $newpair->correctstring()->enumerations = $enumdescription;

          $indexesintable = array(0, 17, 18, 23, 24, 27, 26, 25, 20, 19, 22, 21, 16, 1, 2, 5, 6, 9, 8, 7, 4, 3, 10, 15, 12, 11, 14,
              13, 28);
          $newpair->set_enum_correct_to_correct($indexesintable);
          $newpair->set_enum_correct_string($newcorrect);
          $newpair->enum_correct_string()->enumerations = $newenumdescription;
          $temp->fill_string_as_text_in_corrected_string($newpair->correctedstring());
          $newpair->correctstring()->stream = null;
          $newpair->correctedstring()->stream = null;
          $newpair->correctstring()->stream->tokens;
          $newpair->correctedstring()->stream->tokens;
          $pairs = array();
          $pairs[] = $newpair;
          // Test body.
          $temp = new qtype_correctwriting_enum_analyzer('q',$pair,$lang,false);
          $this->assertEquals($pairs, $temp->result_pairs(), 'Error in work found!Six enumerations');
      }
      // Test for construct, enumeration elements are missed.
      public function test__construct_enum_elems_are_missed() {
          $lang = new block_formal_langs_language_simple_english;
          // Input data.
          $string = 'Billy was like the other rich kids had bicycle , nurse and .';
          $corrected = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
          $string = 'Billy was like the other rich kids had a nurse , swimming pool and bicycle .';
          $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
          $enumdescription = array();
          $enumdescription[] = array(new enum_element(8, 9), new enum_element(11, 12), new enum_element(14, 14));
          $pair = new qtype_correctwriting_string_pair($correct, $corrected, null);
          $pair->correctstring()->enumerations = $enumdescription;
          $temp = new qtype_correctwriting_enum_analyzer('q',$pair,$lang,true);
          $temp->fill_string_as_text_in_corrected_string($pair->correctedstring());
          // Expected result.
          $string = 'Billy was like the other rich kids had bicycle , a nurse and swimming pool .';
          $newcorrect = $lang->create_from_string(new qtype_poasquestion\utf8_string($string), 'qtype_correctwriting_processed_string');
          $enumdescription = array();
          $enumdescription[] = array(new enum_element(10, 11), new enum_element(13, 14), new enum_element(8, 8));
          $newpair = clone $pair;
          $indexesintable = array(0, 1, 2, 3, 4, 5, 6, 7, 14, 10, 8, 9, 13, 11, 12, 15);
          $newpair->set_enum_correct_to_correct($indexesintable);
          $newpair->set_enum_correct_string($newcorrect);
          $newpair->enum_correct_string()->enumerations = $enumdescription;
          $newpair->correctstring()->stream = null;
          $newpair->correctedstring()->stream = null;
          $newpair->enum_correct_string()->stream = null;
          $temp->fill_string_as_text_in_corrected_string($newpair->correctedstring());
          $newpair->correctstring()->stream->tokens;
          $newpair->correctedstring()->stream->tokens;
          $pairs = array();
          $pairs[] = $newpair;
          // Test body.
          $temp = new qtype_correctwriting_enum_analyzer('q',$pair,$lang,false);
          $this->assertEquals($pairs, $temp->result_pairs(), 'Error in work found!Enumeration elements are missed');
      }
}

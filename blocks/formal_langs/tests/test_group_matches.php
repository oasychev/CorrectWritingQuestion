<?php
/**
 * Defines unit-tests for token_base
 *
 * For a complete info, see qtype_correctwriting_token_base
 *
 * @copyright &copy; 2012  
 * @author Oleg Sychev, Volgograd State Technical University
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package 
 */
global $CFG;
require_once($CFG->dirroot.'/blocks/formal_langs/tokens_base.php');

class blocks_formal_langs_token_base_group_matches_test extends UnitTestCase {

    function test_group_matches_1() {
    $mistakeweight=1;
    $correctcoverage=array(0,1,2);
    $comparedcoverage=array(0,1,2);
    //sasha and masha
    //sasha and dasha
    $token_stream=new block_formal_langs_token_stream(array(),array());
    $pair1=new block_formal_langs_matched_tokens_pair(array(0),array(0),0);
    $pair2=new block_formal_langs_matched_tokens_pair(array(0),array(2),1);
    $pair3=new block_formal_langs_matched_tokens_pair(array(1),array(1),0);
    $pair4=new block_formal_langs_matched_tokens_pair(array(2),array(0),1);
    $pair5=new block_formal_langs_matched_tokens_pair(array(2),array(2),1);
    $matches=array();
    array_push($matches,$pair1,$pair2,$pair3,$pair4,$pair5);
    
    $this->assertTrue(count($token_stream->group_matches($matches))==1);
    list($result)=$token_stream->group_matches($matches);
    $this->assertTrue($result->mistakeweight==1);
    $this->assertTrue($result->correctcoverage==$correctcoverage);
    $this->assertTrue($result->comparedcoverage==$comparedcoverage);
    }
    
    function test_group_matches_2() {
    $mistakeweight=1;
    $correctcoverage=array(0,1,2);
    $comparedcoverage=array(0,1);

    //and you to
    //youto and you
    $token_stream=new block_formal_langs_token_stream(array(),array());
    $pair1=new block_formal_langs_matched_tokens_pair(array(0),array(1),0);
    $pair2=new block_formal_langs_matched_tokens_pair(array(1),array(2),0);
    $pair3=new block_formal_langs_matched_tokens_pair(array(1,2),array(0),1);
    $matches=array();
    array_push($matches,$pair1,$pair2,$pair3);
    
    list($result)=$token_stream->group_matches($matches);
    $this->assertTrue(count($token_stream->group_matches($matches))==1);
    $this->assertTrue($result->mistakeweight==1);
    $this->assertTrue($result->correctcoverage==$correctcoverage);
    $this->assertTrue($result->comparedcoverage==$comparedcoverage);
    }
    
    function test_group_matches_3() {
    $mistakeweight=3;
    $correctcoverage=array(0,1);
    $comparedcoverage=array(0,1);

    //ty our
    //tyour tyr
    $token_stream=new block_formal_langs_token_stream(array(),array());
    $pair1=new block_formal_langs_matched_tokens_pair(array(0),array(1),1);
    $pair2=new block_formal_langs_matched_tokens_pair(array(0,1),array(0),1);
    $pair3=new block_formal_langs_matched_tokens_pair(array(1),array(0),2);

    $matches=array();
    array_push($matches,$pair1,$pair2,$pair3);
    
    list($result)=$token_stream->group_matches($matches);
    $this->assertTrue(count($token_stream->group_matches($matches))==1);
    $this->assertTrue($result->mistakeweight==3);
    $this->assertTrue($result->correctcoverage==$correctcoverage);
    $this->assertTrue($result->comparedcoverage==$comparedcoverage);
    }

    /*function test_group_matches_4() {
    $sets_of_pairs=array();
    $set_of_pairs1=block_formal_langs_matches_group();
    $set_of_pairs1->mistakeweight=;
    $set_of_pairs1->correctcoverage=array();
    $set_of_pairs1->comparedcoverage=array();
    $set_of_pairs2=block_formal_langs_matches_group();
    $set_of_pairs2->mistakeweight=;
    $set_of_pairs2->correctcoverage=array();
    $set_of_pairs2->comparedcoverage=array();
    array_push($sets_of_pairs,$set_of_pairs1);
    array_push($sets_of_pairs,$set_of_pairs2);
    $this->assertTrue();
    $this->assertTrue();
    $this->assertTrue();
    }*/
    
    function test_group_matches_5() {
    $mistakeweight=0;
    $correctcoverage=array();
    $comparedcoverage=array();
    //sasha and masha
    //you name mike
    $token_stream=new block_formal_langs_token_stream(array(),array());
    $matches=array();
    
    $this->assertTrue(count($token_stream->group_matches($matches))==0);
    list($result)=$token_stream->group_matches($matches);
    $this->assertTrue($result->mistakeweight==0);
    $this->assertTrue($result->correctcoverage==$correctcoverage);
    $this->assertTrue($result->comparedcoverage==$comparedcoverage);
    }

}
?>
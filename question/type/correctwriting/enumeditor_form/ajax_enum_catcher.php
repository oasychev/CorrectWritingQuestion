<?php


// get the q parameter from URL
require_once(dirname(__FILE__) . '/../../../../config.php');
require_once($CFG->dirroot.'/question/type/correctwriting/enum_analyzer.php');
require_once($CFG->dirroot.'/question/type/correctwriting/enum_catcher.php');
require_once($CFG->dirroot.'/question/type/correctwriting/string_pair.php');
require_once($CFG->dirroot.'/blocks/formal_langs/language_base.php');
require_once($CFG->dirroot.'/question/type/correctwriting/processed_string.php');
require_once($CFG->dirroot.'/blocks/formal_langs/language_cpp_parseable_language.php');
require_once($CFG->dirroot.'/question/type/poasquestion/classes/utf8_string.php');

$q = $_REQUEST;
if ($q['data'] != "") {
    $lang = new block_formal_langs_language_cpp_parseable_language();
    $correct = $lang->create_from_string(new qtype_poasquestion\utf8_string($q['data']), 'qtype_correctwriting_processed_string');
    $tree = $correct->syntaxtree;
    $temp = new qtype_correctwriting_enum_catcher($tree);
    echo json_encode($temp->getEnums());
} else {
    echo json_encode(array());
}
?>

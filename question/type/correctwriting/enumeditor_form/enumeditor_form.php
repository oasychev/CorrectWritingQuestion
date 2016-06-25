<?php
/**
 * Defines enumerations defenitions form. 
 *
 * @package    qtype_correctwriting
 * @copyright  &copy; 2012 Oleg Sychev, Volgograd State Technical University
 * @author     Klevtsov Vadim <vad23klev@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
//moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class enumeditor_form extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG;

        $mform = $this->_form;
        $mform->addElement('html', '<style>@font-face{font-family: ptmono;src: url(' . $CFG->wwwroot . '/question/type/correctwriting/enumeditor_form/PTM55FT.ttf);</style>');
        $mform->addElement('html', '<link href="' . $CFG->wwwroot . '/question/type/correctwriting/enumeditor_form/style.css" id="SL_resources" rel="stylesheet" type="text/css">');
        $mform->addElement('text', 'answer', get_string('enumeditoranswer', 'qtype_correctwriting')); // Add elements to your form
        $mform->setType('answer','PARAM_TEXT');
        $mform->addElement('html', '<a class="ajaxcatcher">'.get_string('enumeditordetermineenumerations', 'qtype_correctwriting') .'</a>');
        $mform->addElement('html', '<div id="work"><div id="lines"></div><div id="closes"></div><div id="arrows"></div>
                                    <span id="width" style = "visibility: hidden;font-family:ptmono, monospace;display:inline-block;resize:none;line-height:50px;
                                    font-size:30px;white-space:nowrap;">T</span><div id="words" style = "font-family:ptmono, monospace;display:inline-block;
                                    resize:none;line-height:50px;font-size:30px;white-space:nowrap;" class = "show" readonly="true" ></div></div>');
        $mform->addElement('static', 'enumerations', get_string('enumeditorenumerations', 'qtype_correctwriting'));
        $mform->addElement('html', '<div id="enums">');
        $mform->addElement('html', '</div>');
        $buttonarray=array();
        $buttonarray[] =& $mform->createElement('button', 'add', get_string('enumeditoraddenumeration', 'qtype_correctwriting'));
        $buttonarray[] =& $mform->createElement('button', 'remove', get_string('enumeditorremoveenumeration', 'qtype_correctwriting'));
        $mform->addGroup($buttonarray, 'enum_manage', '', array(' '), false);
       // $mform->addElement('button', 'add', "Add enumeration");
       // $mform->addElement('button', 'remove', "Remove enumeration");
        $mform->addElement('html', '<div id="elements">');
        $mform->addElement('static', 'elements', get_string('enumeditorelements', 'qtype_correctwriting'));


    }
        //<label id = "id_enumlabel">Enumerations:</label>
        //<label>Elements:</label>
    //<span>To append element to enumeration select text.</span>
    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}

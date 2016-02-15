<?php
/**
 * Defines button-with-text-input widget, parent of abstract poasquestion
 * text-and-button widget. This class extends parent class with javascript
 * callbacks for button clicks.
 *
 * @package    qtype_correctwriting
 * @copyright  &copy; 2012 Oleg Sychev, Volgograd State Technical University
 * @author     Klevtsov Vadim <vad23klev@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/question/type/poasquestion/poasquestion_text_and_button.php');

MoodleQuickForm::registerElementType('correctwriting_text_and_button',
    $CFG->dirroot.'/question/type/correctwriting/enumeditor_form/correctwriting_text_and_button.php',
    'qtype_correctwriting_text_and_button');

class qtype_correctwriting_text_and_button extends qtype_poasquestion_text_and_button {

    private static $_correctwriting_enumerations_editor_form_included = false;

    public function  __construct($textareaName = null, $textareaLabel = null, $buttonName = null) {
        global $CFG;
        global $PAGE;
        $attributes = array('rows' => 1, 'cols' => 80, 'style' => 'width: 95%');
        $elementLinks = array(
            'link_to_button_image' => $CFG->wwwroot . '/theme/image.php/clean/core/1410350174/t/edit',
            'link_to_page' => $CFG->wwwroot . '/question/type/correctwriting/enumeditor_form/enumeditor.php'
        );
        $dialogWidth = '90%';

        parent::__construct($textareaName, $textareaLabel, $attributes, $buttonName, $elementLinks, $dialogWidth);

        if (!self::$_correctwriting_enumerations_editor_form_included) {
            $jsmodule = array(
                'name' => 'correctwriting_enumeration_editor_form',
                'fullpath' => '/question/type/correctwriting/enumeditor_form/correctwriting_editor.js'
            );
            $jsargs = array(
                $CFG->wwwroot,
                'TODO - poasquestion_text_and_button_objname',  // 'M.poasquestion_text_and_button' ?
            );
            $PAGE->requires->js_init_call('M.correctwriting_enumeration_editor_form.init', $jsargs, true, $jsmodule);
            self::$_correctwriting_enumerations_editor_form_included = true;
        }
    }

    public function getDialogTitle() {
        return 'Correct Writing enumerations description editor form';
    }

    public function getTooltip() {
        return 'Enumeration description editor form';
    }
}

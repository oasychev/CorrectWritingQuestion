<?php
require_once(dirname(__FILE__) . '/../../../../config.php');
require_once(dirname(__FILE__) . '/../../../../question/type/correctwriting/enumeditor_form/enumeditor_form.php');

$PAGE->set_url('/question/type/correctwriting/enumeditor_form/enumeditor_form.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('popup');
$mform = new enumeditor_form();
$mform->display();

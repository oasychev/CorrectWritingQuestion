<?php
// This file is part of Correct Writing question type - https://bitbucket.org/oasychev/moodle-plugins/
//
// Correct Writing question type is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Correct Writing is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Correct Writing question type upgrade code.
 *
 * @package    qtype_correctwriting
 * @copyright  2013 Oleg Sychev, Volgograd State Technical University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once($CFG->dirroot . '/blocks/formal_langs/block_formal_langs.php');

function xmldb_qtype_correctwriting_upgrade($oldversion=0) {

    global $CFG, $DB;
    $dbman = $DB->get_manager();

    if ($oldversion < 2013011500) {

        // Define field whatishintpenalty to be added to qtype_correctwriting
        $table = new xmldb_table('qtype_correctwriting');
        $field = new xmldb_field('whatishintpenalty', XMLDB_TYPE_NUMBER, '4, 2', null, XMLDB_NOTNULL, null, '1.1', 'maxmistakepercentage');

        // Conditionally launch add field whatishintpenalty
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // correctwriting savepoint reached
        upgrade_plugin_savepoint(true, 2013011500, 'qtype', 'correctwriting');
    }

    if ($oldversion < 2013011800) {

        // Define field wheretxthintpenalty to be added to qtype_correctwriting
        $table = new xmldb_table('qtype_correctwriting');
        $field = new xmldb_field('wheretxthintpenalty', XMLDB_TYPE_NUMBER, '4, 2', null, XMLDB_NOTNULL, null, '1.1', 'whatishintpenalty');

        // Conditionally launch add field wheretxthintpenalty
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // correctwriting savepoint reached
        upgrade_plugin_savepoint(true, 2013011800, 'qtype', 'correctwriting');
    }

    if ($oldversion < 2013012300) {

        // Define field absenthintpenaltyfactor to be added to qtype_correctwriting
        $table = new xmldb_table('qtype_correctwriting');
        $field = new xmldb_field('absenthintpenaltyfactor', XMLDB_TYPE_NUMBER, '4, 1', null, XMLDB_NOTNULL, null, '1', 'wheretxthintpenalty');

        // Conditionally launch add field absenthintpenaltyfactor
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // correctwriting savepoint reached
        upgrade_plugin_savepoint(true, 2013012300, 'qtype', 'correctwriting');
    }
    if ($oldversion < 2013012900) {
        // Define field wherepichintpenalty to be added to qtype_correctwriting
        $table = new xmldb_table('qtype_correctwriting');
        $field = new xmldb_field('wherepichintpenalty', XMLDB_TYPE_NUMBER, '4, 2', null, XMLDB_NOTNULL, null, '1.1', 'absenthintpenaltyfactor');

        // Conditionally launch add field wherepichintpenalty
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // correctwriting savepoint reached
        upgrade_plugin_savepoint(true, 2013012900, 'qtype', 'correctwriting');
    }
    
    $updateanalyzersenables = function() {
        global $DB;
        $DB->execute("
            UPDATE {qtype_correctwriting}
            SET
            islexicalanalyzerenabled = '0',
            isenumanalyzerenabled = '0',
            issequenceanalyzerenabled = '1',
            issyntaxanalyzerenabled = '0'
        ");
    };

    if ($oldversion < 2013092400) {
        $table = new xmldb_table('qtype_correctwriting');
        $fieldnames = array(
            'islexicalanalyzerenabled' => 'wherepichintpenalty',
            'isenumanalyzerenabled' => 'islexicalanalyzerenabled',
            'issequenceanalyzerenabled' => 'isenumanalyzerenabled',
            'issyntaxanalyzerenabled' =>  'issequenceanalyzerenabled'
        );

        foreach($fieldnames as $name => $previous) {
            $defaultvalue = ($name == 'issequenceanalyzerenabled') ? '1' : '0';
            $field = new xmldb_field($name, XMLDB_TYPE_INTEGER, '4', null ,XMLDB_NOTNULL, null, $defaultvalue, $previous);
            $dbman->add_field($table, $field);
        }

        $updateanalyzersenables();

        // correctwriting savepoint reached
        upgrade_plugin_savepoint(true, 2013092400, 'qtype', 'correctwriting');
    }

    if ($oldversion < 2015033100) {        
        // Define field whatishintpenalty to be added to qtype_correctwriting
        $table = new xmldb_table('qtype_correctwriting');
        $field = new xmldb_field('howtofixpichintpenalty', XMLDB_TYPE_NUMBER, '4, 2', null, XMLDB_NOTNULL, null, '1.1', 'issyntaxanalyzerenabled');

        // Conditionally launch add field whatishintpenalty
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // correctwriting savepoint reached
        upgrade_plugin_savepoint(true, 2015033100, 'qtype', 'correctwriting');
    }

    if ($oldversion < 2015071000) {
        // Fix bugs, linked to syntax analyzer, being enabled to all question
        // Also, if enum analyzer is enabled in incorrect cases - disable it too
        $DB->execute("
            UPDATE {qtype_correctwriting}
            SET islexicalanalyzerenabled = '0',
            isenumanalyzerenabled = '0',
            issequenceanalyzerenabled = '1',
            issyntaxanalyzerenabled = '0'
            WHERE issyntaxanalyzerenabled='1'
        ");

        // correctwriting savepoint reached
        upgrade_plugin_savepoint(true, 2015071000, 'qtype', 'correctwriting');
    }

    if ($oldversion < 2015101000) {
        $table = new xmldb_table('qtype_correctwriting_enums');
        $idfield = new xmldb_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null);
        $answeridfield = new xmldb_field('answerid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, 0, $idfield);
        $enumerationsfield = new xmldb_field('enumerations', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null, $answeridfield);
	
        $table->addField($idfield);
        $table->addField($answeridfield, $idfield);
        $table->addField($enumerationsfield, $answeridfield);

        $key = new xmldb_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->addKey($key);

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // correctwriting savepoint reached
        upgrade_plugin_savepoint(true, 2015101000, 'qtype', 'correctwriting');
    }

    if ($oldversion < 2016070000) {
        $table = new xmldb_table('qtype_correctwriting');
        $fieldnames = array(
            'allowinvalidsyntaxanswers' => 'howtofixpichintpenalty'
        );
		
        foreach($fieldnames as $name => $previous) {
            $defaultvalue = '0';
            $field = new xmldb_field($name, XMLDB_TYPE_INTEGER, '4', null ,XMLDB_NOTNULL, null, $defaultvalue, $previous);
            if (!$dbman->field_exists($table, $field)) {
                $dbman->add_field($table, $field);
            }
        }

        // correctwriting savepoint reached
        upgrade_plugin_savepoint(true, 2016070000, 'qtype', 'correctwriting');
    }
    
	if ($oldversion < 2017032300) {
        $DB->execute(
            "DELETE FROM {config_log} WHERE " . $DB->sql_like('name', ':name'),
            array('name' => 'qtype_correctwriting%')
        );
        $configs = $DB->get_records_sql(
            "SELECT * from {config} WHERE " . $DB->sql_like('name', ':name'),
            array('name' => 'qtype_correctwriting%')
        );

        $newconfigs = array_map(function ($config) {
            return (object)array(
                'plugin' => 'qtype_correctwriting',
                'name' => str_replace('qtype_correctwriting_', '', $config->name),
                'value' => $config->value,
            );
        }, $configs);
        $DB->insert_records('config_plugins', $newconfigs);

        $DB->execute(
            "DELETE FROM {config} WHERE " . $DB->sql_like('name', ':name'),
            array('name' => 'qtype_correctwriting%')
        );
        upgrade_plugin_savepoint(true, 2017032300, 'qtype', 'correctwriting');
    }

    return true;
}
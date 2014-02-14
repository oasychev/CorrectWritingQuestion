<?php
/**
 * Defines class which is builder of graphical syntax tree.
 *
 * @copyright &copy; 2012 Oleg Sychev, Volgograd State Technical University
 * @author Terechov Grigory <grvlter@gmail.com>, Valeriy Streltsov <vostreltsov@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package qtype_preg
 */

require_once($CFG->dirroot . '/question/type/preg/authoring_tools/preg_authoring_tool.php');
require_once($CFG->dirroot . '/question/type/preg/authoring_tools/preg_syntax_tree_nodes.php');

class qtype_preg_syntax_tree_tool extends qtype_preg_dotbased_authoring_tool {

    public function __construct($regex = null, $options = null) {
        parent::__construct($regex, $options);
    }

    /**
     * Overloaded from qtype_preg_regex_handler.
     */
    public function name() {
        return 'syntax_tree_tool';
    }

    /**
     * Overloaded from qtype_preg_regex_handler.
     */
    protected function node_infix() {
        return 'syntax_tree';
    }

    /**
     * Overloaded from qtype_preg_regex_handler.
     */
    protected function get_engine_node_name($nodetype, $nodesubtype) {
        if ($nodetype == qtype_preg_node::TYPE_NODE_FINITE_QUANT || $nodetype == qtype_preg_node::TYPE_NODE_INFINITE_QUANT) {
            return 'qtype_preg_syntax_tree_node_quant';
        }
        return parent::get_engine_node_name($nodetype, $nodesubtype);
    }

    /**
     * Overloaded from qtype_preg_regex_handler.
     */
    protected function is_preg_node_acceptable($pregnode) {
        return true;
    }

    /**
     * Overloaded from qtype_preg_authoring_tool.
     */
    public function json_key() {
        return 'tree';
    }

    /**
     * Overloaded from qtype_preg_authoring_tool.
     */
    public function generate_json_for_accepted_regex(&$json) {
        $indfirst = $this->selectednode !== null ? $this->selectednode->position->indfirst : -2;
        $indlast = $this->selectednode !== null ? $this->selectednode->position->indlast : -2;
        $context = new qtype_preg_dot_node_context($this, true, $this->options->treeorientation == 'horizontal', new qtype_preg_position($indfirst, $indlast));
        $dotscript = $this->get_dst_root()->dot_script($context);
        $json[$this->json_key()] = array(
            'img' => 'data:image/svg+xml;base64,' . base64_encode(qtype_preg_regex_handler::execute_dot($dotscript, 'svg')),
            'map' => qtype_preg_regex_handler::execute_dot($dotscript, 'cmapx')
        );
    }
}
<?php
/**
 * Defines DFA matcher node classes with code needed to do DFA stuff
 *
 * @copyright &copy; 2010 Sychev Oleg, Kolesov Dmitriy
 * @author Sychev Oleg, Kolesov Dmitriy, Volgograd State Technical University
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package questions
 */

require_once($CFG->dirroot . '/question/type/preg/preg_nodes.php');

/**
* Abstract class for dfa nodes. 
* Declare any necessary for every node function as absract there, optional - with empty body
*/
abstract class dfa_preg_node {

    //Instance of preg_node child class
    public $pregnode;
    //Cashes for important data
    public $nullable;
    public $firstpos;
    public $lastpos;
    public $number;
    /**
    * Message with UI node name describing the reason for rejection
    */
    public $rejectmsg;

    public function __construct($node, &$matcher) {
        $this->pregnode = $node;
        //Convert operands to dfa nodes
        if (is_a($node, 'preg_operator')) {
            foreach ($node->operands as $key=>$operand) {
                if (is_a($node->operands[$key], 'preg_node')) {//Just to be sure this is not plain-data operand
                    $node->operands[$key] =& $matcher->from_preg_node($operand);
                }
            }
        }
    }

    /**
    * Returns true if engine support the node, false otherwise
    * When returnig false should also set rejectmsg field
    */
    public function accept() {
        return true; //accepting anything by default, overload function in case of partial accepting or total rejection
    }


    /**
    *Function print indent before something
    *@param indent size of indent in count of 5 dot
    */
    public function print_indent($indent) {
        for ($i=0; $i<$indent; $i++) {
            echo '.....';
        }
    }
    /**
    *Function print the subtree with root in this node with indents
    *@param indent indent for printing info about this node
    */
    public function print_tree($indent) {
        $this->print_self($indent);
    }
    /**
    *Function print info about this node
    *@param indent indent for printing info about this node
    */
    abstract public function print_self($indent);

    
    /**
    * Return false if the node is supported by engine, interface string name to report as unsupported if not
    */
    abstract public function not_supported();

    //DFA functions
    /**
    *Function numerate leafs, nodes use for find leafs. Start on root and move to leafs.
    *Put pair of number=>linktoleaf to connection.
    *@param $connection table for saving connection numbers and leafs.
    *@param $maxnum maximum number of leaf, it's number of previous leaf
    */
    abstract public function number(&$connection, &$maxnum);//replacement of old 'numeration'
    /**
    *Function determine: subtree with root in this node can give empty word or not.
    *@return true if can give empty word, else false
    */
    abstract public function nullable();
    /**
    *function determine characters which can be on first position in word, which given subtree with root in this node
    *@return numbers of characters (array)
    */
    abstract public function firstpos();
    /**
    *function determine characters which can be on last position in word, which given subtree with root in this node
    *@return numbers of characters (array)
    */
    abstract public function lastpos();
    /**
    *function determine characters which can follow characters from this node
    *@param fpmap - map of following of characters
    */
    abstract public function followpos(&$fpmap);
    /**
    *function find asserts' nodes in tree and put link to root of it to $roots[<number of assert>]
    *@param node - current nod for recursive search
    */
    abstract public function find_asserts(&$roots);
    
    //Service DFA function
    /**
    *function append array2 to array1, non unique values not add
    *@param arr1 - first array
    *@param arr2 - second array, which will appended to arr1
    */
    static public function push_unique(&$arr1, $arr2) {
        if (!is_array($arr1)) {
            $arr1 = array();
        }
        foreach ($arr2 as $value) {
            if (!in_array($value, $arr1)) {
                $arr1[] = $value;
            }
        }
    }
}

abstract class dfa_preg_leaf extends dfa_preg_node {
    public function number(&$connection, &$maxnum) {
        $this->number = ++$maxnum;
        $connection[$maxnum] = &$this;
    }
    public function nullable() {
        $this->nullable = false;
        return false;
    }
    public function firstpos() {
        $this->firstpos = array($this->number);
        return $this->firstpos;
    }
    public function lastpos() {
        $this->lastpos = array($this->number);
        return $this->lastpos;
    }
    public function followpos(&$fpmap) {
        ;//do nothing, because not need for leaf
    }
    public function find_asserts(&$roots) {
        ;//do nothing, because not need for leaf
    }
    public function print_self($indent) {
        $this->print_indent($indent);
        echo 'number: ', $this->number, '<br/>';
        $this->print_indent($indent);
        if ($this->nullable) {    
            echo 'nullable: true<br>';
        } else {
            echo 'nullable: false<br>';
        }
        $this->print_indent($indent);
        if (is_array($this->firstpos)) {
            $this->print_indent($indent);
            echo 'firstpos: ';
            foreach ($this->firstpos as $val) {
                echo $val, ' ';
            }
            echo '<br>';
        }
        if (is_array($this->lastpos)) {
            $this->print_indent($indent);
            echo 'lastpos: ';
            foreach ($this->lastpos as $val) {
                echo $val, ' ';
            }
            echo '<br>';
        }
    }
}
class dfa_preg_leaf_charset extends dfa_preg_leaf {
    public function not_supported() {
        return false;
    }
    public function print_self($indent) {
        $this->print_indent($indent);
        echo 'type: leaf charset ';
        if ($this->pregnode->negative) {
            echo 'negative';
        } else {
            echo 'positive';
        }
        echo '<br/>';
        $this->print_indent($indent);
        echo 'charset: ', $this->pregnode->charset, '<br/>';
        parent::print_self($indent);
    }
}
class dfa_preg_leaf_meta extends dfa_preg_leaf {
    const ENDREG = 186759556;
    public function number(&$connection, &$maxnum) {
        if ($this->pregnode->subtype === preg_leaf_meta::SUBTYPE_ENDREG) {
            $this->number = dfa_preg_leaf_meta::ENDREG;
            $connection[dfa_preg_leaf_meta::ENDREG] = &$this;
        } else {
            parent::number($connection, $maxnum);
        }
    }
    public function not_supported() {
        return false;
    }
    public function print_self($indent) {
        $this->print_indent($indent);
        echo 'type: leaf meta ';
        if ($this->pregnode->negative) {
            echo 'negative';
        } else {
            echo 'positive';
        }
        echo '<br/>';
        switch ($this->pregnode->subtype) {
            case preg_leaf_meta::SUBTYPE_DOT:
                $subtype = 'dot';
                break;
            case preg_leaf_meta::SUBTYPE_UNICODE_PROP:
                $subtype = 'unicode property';
                break;
            case preg_leaf_meta::SUBTYPE_WORD_CHAR:
                $subtype = 'word char';
                break;
            case preg_leaf_meta::SUBTYPE_EMPTY:
                $subtype = 'empty';
                break;
            case preg_leaf_meta::SUBTYPE_ENDREG:
                $subtype = 'endreg';
                break;
        }
        $this->print_indent($indent);
        echo 'subtype: ', $subtype, '<br/>';
        parent::print_self($indent);
    }
}
class dfa_preg_leaf_assert extends dfa_preg_leaf {
    public function not_supported() {
        if ($this->pregnode->subtype == preg_leaf_assert::SUBTYPE_ESC_G) {
            return 'escg';
        } else {
            return false;
        }
    }
    public function print_self($indent) {
        $this->print_indent($indent);
        echo 'type: node assert ';
        if ($this->pregnode->negative) {
            echo 'negative';
        } else {
            echo 'positive';
        }
        echo '<br/>';
        switch ($this->pregnode->subtype) {
            case preg_leaf_assert::SUBTYPE_CIRCUMFLEX:
                $subtype = 'circumflex';
                break;
            case preg_leaf_assert::SUBTYPE_DOLLAR:
                $subtype = 'dollar';
                break;
            case preg_leaf_assert::SUBTYPE_WORDBREAK:
                $subtype = 'word break';
                break;
            case preg_leaf_assert::SUBTYPE_ESC_A:
                $subtype = '\\A';
                break;
            case preg_leaf_assert::SUBTYPE_ESC_Z:
                $subtype = '\\Z';
                break;
        }
        $this->print_indent($indent);
        echo 'subtype: ', $subtype, '<br/>';
        parent::print_self($indent);
    }
}
class dfa_preg_leaf_combo extends dfa_preg_leaf {
    public function not_supported() {
        return false;
    }
    public function print_self($indent) {
        echo 'Error!!!<br>Combo leafs forbidden in tree, only for connection table!';
    }
}
abstract class dfa_preg_operator extends dfa_preg_node {
    public function number(&$connection, &$maxnum) {
        foreach ($this->pregnode->operands as $key => $operand) {
            $this->pregnode->operands[$key]->number($connection, $maxnum);
        }
    }
    public function followpos(&$fpmap) {
        foreach ($this->pregnode->operands as $key=>$operand) {
            $this->pregnode->operands[$key]->followpos($fpmap);
        }
    }
    public function find_asserts(&$roots) {
        foreach ($this->pregnode->operands as $key=>$operand) {
            $this->pregnode->operands[$key]->find_asserts(&$roots);
        }
    }
    public function print_tree($indent) {
        parent::print_tree($indent);
        foreach ($this->pregnode->operands as $operand) {
            echo '<br/>';
            $this->print_indent($indent+1);
            echo 'OPERAND:<br/>';
            $operand->print_tree($indent+1);
        }
    }
    public function print_self($indent) {
        $this->print_indent($indent);
        if ($this->nullable) {    
            echo 'nullable: true<br>';
        } else {
            echo 'nullable: false<br>';
        }
        if (is_array($this->firstpos)) {
            $this->print_indent($indent);
            echo 'firstpos: ';
            foreach ($this->firstpos as $val) {
                echo $val, ' ';
            }
            echo '<br>';
        }
        if (is_array($this->lastpos)) {
            $this->print_indent($indent);
            echo 'lastpos: ';
            foreach ($this->lastpos as $val) {
                echo $val, ' ';
            }
            echo '<br>';
        }
    }
}
class dfa_preg_node_concat extends dfa_preg_operator {
    public function nullable() {
        $secnull = $this->pregnode->operands[1]->nullable();
        $this->nullable = $this->pregnode->operands[0]->nullable() && $secnull;
        return $this->nullable;
    }
    public function firstpos() {
        if ($this->pregnode->operands[0]->nullable) {
            $this->firstpos = array_merge($this->pregnode->operands[0]->firstpos(), $this->pregnode->operands[1]->firstpos());
        } else {
            $this->firstpos = $this->pregnode->operands[0]->firstpos();
            $this->pregnode->operands[1]->firstpos();
        }
        return $this->firstpos;
    }
    public function lastpos() {
        if ($this->pregnode->operands[1]->nullable) {
            $this->lastpos = array_merge($this->pregnode->operands[0]->lastpos(), $this->pregnode->operands[1]->lastpos());
        } else {
            $this->lastpos = $this->pregnode->operands[1]->lastpos();
            $this->pregnode->operands[0]->lastpos();
        }
        return $this->lastpos;
    }
    public function followpos(&$fpmap) {
        parent::followpos(&$fpmap);
        foreach ($this->pregnode->operands[0]->lastpos as $key) {
            dfa_preg_node::push_unique($fpmap[$key], $this->pregnode->operands[1]->firstpos);
        }        
    }
    public function not_supported() {
        return false;
    }
    public function print_self($indent) {
        $this->print_indent($indent);
        echo 'type: node concatenation<br/>';
        parent::print_self($indent);
    }
}
class dfa_preg_node_alt extends dfa_preg_operator {
    public function nullable() {
        $firnull = $this->pregnode->operands[0]->nullable();
        $this->nullable = $firnull || $this->pregnode->operands[1]->nullable();
        return $this->nullable;
    }
    public function firstpos() {
        $this->firstpos = array_merge($this->pregnode->operands[0]->firstpos(), $this->pregnode->operands[1]->firstpos());
        return $this->firstpos;
    }
    public function lastpos() {
        $this->lastpos = array_merge($this->pregnode->operands[0]->lastpos(), $this->pregnode->operands[1]->lastpos());
        return $this->lastpos;
    }
    public function not_supported() {
        return false;
    }
    public function print_self($indent) {
        $this->print_indent($indent);
        echo 'type: node alternative<br/>';
        parent::print_self($indent);
    }
}
class dfa_preg_node_assert extends dfa_preg_operator {
    const ASSERT_MIN_NUM = 1073741824;//it's minimum number for node with assert, for different from leafs
    
    public function not_supported() {
        switch ($this->pregnode->subtype) {
            case preg_node_assert::SUBTYPE_PLA:
                $res = false;
                break;
            case preg_node_assert::SUBTYPE_NLA:
                $res = 'assertff';
                break;
            case preg_node_assert::SUBTYPE_PLB:
                $res = 'asserttb';
                break;
            case preg_node_assert::SUBTYPE_NLB:
                $res = 'assertfb';
                break;
        }
        return $res;
    }
	public function accept() {
        if ($this->pregnode->subtype!=preg_node_assert::SUBTYPE_PLA) {
			switch ($this->pregnode->subtype) {
				case preg_node_assert::SUBTYPE_NLA:
					$res = 'assertff';
					break;
				case preg_node_assert::SUBTYPE_PLB:
					$res = 'asserttb';
					break;
				case preg_node_assert::SUBTYPE_NLB:
					$res = 'assertfb';
					break;
			}
			$this->rejectmsg = get_string($res, 'qtype_preg');
		}
		return true;
    }
    public function number(&$connection, &$maxnum) {
        $this->number = ++$maxnum + dfa_preg_node_assert::ASSERT_MIN_NUM;
        $connection[$this->number] = &$this;
    }
    public function nullable() {
        $this->nullable = true;
        return true;
    }
    public function firstpos() {
        $this->firstpos = array($this->number);
        return $this->firstpos;
    }
    public function lastpos() {
        $this->lastpos = array($this->number);
        return $this->lastpos;
    }
    public function followpos(&$fpmap) {
        ;//do nothing, because not need for assert
    }
    public function find_asserts(&$roots) {
        $roots[$this->number] = &$this;
    }
    public function print_self($indent) {
        $this->print_indent($indent);
        echo 'type: node assert<br/>';
        switch ($this->pregnode->subtype) {
            case preg_node_assert::SUBTYPE_PLA:
                $subtype = 'PLA';
                break;
            case preg_node_assert::SUBTYPE_PLB:
                $subtype = 'PLB';
                break;
            case preg_node_assert::SUBTYPE_NLA:
                $subtype = 'NLA';
                break;
            case preg_node_assert::SUBTYPE_NLB:
                $subtype = 'NLB';
                break;
        }
        $this->print_indent($indent);
        echo 'subtype: ', $subtype, '<br/>';
        $this->print_indent($indent);
        echo 'number: ', $this->number, '<br/>';
        parent::print_self();
    }
}
class dfa_preg_node_infinite_quant extends dfa_preg_operator {

	public function accept() {
		if (!$this->pregnode->greed) {
			$this->rejectmsg = get_string('lazyquant', 'qtype_preg');
			return false;
		}
		return true;
	}
    public function nullable() {
        //{}quantificators will be converted to ? and * combination
        if ($this->pregnode->leftborder == 0) {//? or *
            $result = true;
            $this->pregnode->operands[0]->nullable();
        } else {//+
            $result = $this->pregnode->operands[0]->nullable();
        }
        $this->nullable = $result;
        return $result;
    }
    public function firstpos() {
        $this->firstpos = $this->pregnode->operands[0]->firstpos();
        return $this->firstpos;
    }
    public function lastpos() {
        $this->lastpos = $this->pregnode->operands[0]->lastpos();
        return $this->lastpos;
    }
    public function not_supported() {
        return $this->pregnode->greed;
    }
    public function followpos(&$fpmap) {
        parent::followpos(&$fpmap);
        foreach ($this->pregnode->operands[0]->lastpos as $lpkey) {
            dfa_preg_node::push_unique($fpmap[$lpkey], $this->pregnode->operands[0]->firstpos);
        }
    }
    public function print_self($indent) {
        $this->print_indent($indent);
        if (!is_a($this, 'dfa_preg_node_finite_quant')) {
            echo 'type: node infinite quant<br/>';
        }
        $this->print_indent($indent);
        echo 'left border: ', $this->pregnode->leftborder, '<br/>';
        if (is_a($this, 'dfa_preg_node_finite_quant')) {    
            $this->print_indent($indent);
            echo 'right border: ', $this->pregnode->rightborder, '<br/>';
        }
        parent::print_self($indent);
    }
}
class dfa_preg_node_finite_quant extends dfa_preg_node_infinite_quant {
	

    public function followpos(&$fpmap) {
        dfa_preg_operator::followpos(&$fpmap);
    }

    public function print_self($indent) {
        $this->print_indent($indent);
        echo 'type: node finite quant<br/>';
        parent::print_self($indent);
    }
}
?>
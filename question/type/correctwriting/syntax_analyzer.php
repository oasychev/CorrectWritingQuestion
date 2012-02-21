<?php
/**
 * Defines class of syntax analyzer for correct writing question.
 *
 * Syntax analyzer object is created for each possible LCS of answer and response and 
 * is responsible for grouping tokens into abstract syntax tree (AST) and using it to
 * generate more descriptive mistakes, based on tree nodes (which may represent logically
 * grouped sequence of tokens) instead of just single tokens.
 *
 * Syntax analyzers are the last line of analyzers for now.
 * They should throw an exception on creation if given language (or engine)
 * doesn't support syntax analysis.
 *
 * @copyright &copy; 2011  Oleg Sychev
 * @author Oleg Sychev, Sergey Pashaev, Volgograd State Technical University
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package questions
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/question/type/correctwriting/sequence_analyzer.php');
//Other necessary requires

class qtype_correctwriting_syntax_analyzer {//object created for each lcs
    protected $language;//language object - contains scaner, parser etc
    protected $errors;//array of objects - teacher errors when entering answer

    protected $answer;//array of tokens
    protected $correctedresponse;//array of tokens with lexical errors corrected
    protected $lcs;//longest common subsequence - array with indexes in answer as keys and indexes in response as values
    protected $ast;//abstract syntax tree of answer (with labels)
    protected $subtrees;//array - trees created by parsing parts of response don't covered by LCS
    protected $mistakes;//array of objects - student errors (merged from all stages)

    /**
     * Do all processing and fill all member variables
     * Passed response could be null, than object used just to find errors in the answers, token count etc...
     */
    public function __construct($answer, $language, $correctedresponse=null, $lcs=null) {

        //TODO:
        // 0. Throw exception if language doesn't support parsing or syntax analyzer isnt' written yet
        //1. Create Abstract Syntax Tree for answer - Pashaev
        //  - call language object to do it
        //2. Label tree using LCS - Pashaev
        //  - special function
        //3. Parse parts of corrected response don't covered by LCS - Pashaev
        //  - special function, calling language object when necessary
        //4. Find coverage(s) of AST with subtrees from previous stage - Pashaev
        //  - special function
        //5. Set array of mistakes accordingly - Pashaev
        //  - special function
        //NOTE: if response is null just check for errors - Pashaev
        //NOTE: if some stage create errors, stop processing right there
    }

    /**
    * Returns fitness as aggregate measure of how students response fits this particular answer - i.e. more fitness = less mistakes
    * Used to choose best matched answer
    * Fitness is negative or zero (no errors, full match)
    * Fitness doesn't necessary equivalent to the number of mistakes as each mistake could have different weight
    */
    public function fitness() {
    }

    public function mistakes() {
        return $this->mistakes;
    }

    public function has_errors() {
        return !empty($this->errors);
    }

    public function errors() {
        return $this->errors;
    }

    //Other necessary access methods

    /**
     * Returns array of objects describing nodes from answer for which teacher-supplied sematic names required
     *
     * Keys are unique id's, that would allow analyzer to identify node, values are substring of answer corresponding to this node
     */
    public function nodes_with_semantic_names() {
    }

    /**
     * Returns a name for a leaf node - token from answer
     */
    public function token_name($index) {
    }
}
?>
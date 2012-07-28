<?php

$string['addmoreanswerblanks'] = 'Adding a regular expression options';
$string['answersinstruct'] = '<p>Enter (at least one) regular expressions in the choosen notation as answers. If a correct answer is given, it should match at least one regular expression with 100% grade.</p><p>You can use placeholders like {$0} in the feedback to insert captured parts of a student\'s response. {$0} will be replaced by the whole match, {$1} with the first subpattern match etc. If the choosen engine doesn\'t support subpatterns capturing you should use only {$0}.</p>';
$string['answerno'] = 'Answer {$a}';
$string['charhintpenalty'] = 'Penalty for the next character hint';
$string['charhintpenalty_help'] = 'Penalty for getting the one-character hint. Typically will be greater than usual Moodle question penalty (which applies to any new attempt to answer question without hints). These penalties are mutually exclusive.';
$string['lexemhintpenalty'] = 'Penalty for the next lexem hint';
$string['lexemhintpenalty_help'] = 'Penalty for getting the next lexem hint. Typically will be greater than usual Moodle question penalty (which applies to any new attempt to answer question without hints) and next character one. These penalties are mutually exclusive.';
$string['correctanswer'] = 'Correct answer';
$string['correctanswer_help'] = 'Enter a correct answer (not a regular expression) to be shown to students. If you leave it empty the matching engine will try to generate a correct answer itself, taking heed to get the closest one to the student\'s response. For now only NFA engine can generate correct answers.';
$string['debugheading'] = 'Debug settings';
$string['defaultenginedescription'] = 'Matching engine selected by default when creating a new question';
$string['defaultenginelabel'] = 'Default matching engine';
$string['defaultlangdescription'] = 'Language selected by default when creating a new question';
$string['defaultlanglabel'] = 'Default language';
$string['defaultnotationdescription'] = 'Notation selected by default when creating a new question';
$string['defaultnotationlabel'] = 'Default notation';
$string['dfa_matcher'] = 'Deterministic finite state automata';
$string['engine'] = 'Matching engine';
$string['engine_help'] = '<p>There is no \'best\' matching enginge, so you can choose the engine that fits the particular question best.</p><p>Native <b>PHP preg matching engine</b> works using preg_match() function from PHP langugage. It\'s almost 100% bug-free and able to work with full PCRE syntax, but can\'t support advanced features (showing partial matches and hinting).</p><p>The <b>NFA matching engine</b> and the <b>DFA matching engine</b> are engines that use custom matching code. They support partial matching and hinting, but don\'t support lookaround assertions (you\'ll be notified when trying to save a question with unsupported expressions) and potentially can contain bugs (different for each engine: regular expression matching is still a very complex thing).</p><p>If the difference between engines is too hard to you, just try them all to see how their capabilities suit your needs. If one engine fails in a question then try another engines to see if they can handle it better.</p><p>The NFA engine is probably the best choise if you don\'t use lookaround assertions.</p><p>Avoid using the DFA engine for the Moodle shortanswer notation.</p>';
$string['exactmatch'] = 'Exact matching';
$string['exactmatch_help'] = '<p>By default regular expression matching returns true if there is at least one match in the given string (answer). Exact matching means that the match must be the entire string.</p><p>Set this to Yes, if you write regular expressions for full student\'s answers. Setting this to No gives you additional flexibility: you can specify an answer with low (or zero) grade to catch common errors and give comments on them. You still can specify exact matches for some of your regular expressions if you start them with ^ and end with $.</p>';
$string['hintgradeborder'] = 'Hint grade border';
$string['hintgradeborder_help'] = 'Answers with the grade less than the hint grade border won\'t be used in hinting.';
$string['hintnextchar'] = 'next correct character';
$string['langselect'] = 'Language';
$string['langselect_help'] = 'For next lexem hint you should choose a language, which is used to break answers down to lexems. Each language has it own rules for lexems. Languages are defined using \'Formal languages block\'';
$string['largefa'] = 'Too large finite automaton';
$string['lexemusername'] = 'Student-visible name for lexem';
$string['lexemusername_help'] = 'Your students probably won\'t know that an atomic part of the language they learn is called <b>lexem</b>. They may prefer to call it "word" or "number" or something. You may define a name for lexem that would be shown on the "Hint next lexem" button there.';
$string['maxerrorsshowndescription'] = 'Maximum number of errors shown for each regular expression in the question editing form';
$string['maxerrorsshownlabel'] = 'Maximum number of errors shown';
$string['nfa_matcher'] = 'Nondeterministic finite state automata';
$string['noabstractaccept'] = 'Matching by abstract matcher';
$string['nocorrectanswermatch'] = 'No maximum grade regular expression matches the correct answer';
$string['nohintgradeborderpass'] = 'No answer has a grade greater or equal the hint grade border. This disables hinting.';
$string['nohintsupport'] = '{$a} engine doesn\'t support hinting';
$string['notation'] = 'Regular expression notation';
$string['notation_help'] = '<p>You can choose the notation to enter regular expressions. If you just want to write a regular expression, please use the default, <b>Regular expression</b> notation which is very close to PCRE, but has additional error-proof capabilities.</p><p><b>Moodle shortanswer</b> notation allows you to use preg as a usual Moodle shortanswer question with the hinting capability - with no need to understand regular expressions. Just copy you answers from shortanswer question. The \'*\' wildcard is supported.</p>';
$string['notation_native'] = 'Regular expression';
$string['notation_mdlshortanswer'] = 'Moodle shortanswer';
$string['noregex'] = 'No regex supplied for matching';
$string['nosubpatterncapturing'] = '{$a} engine doesn\'t support subpattern capturing, please remove placeholders (except {$0}) from the feedback or choose another engine';
$string['pluginname'] = 'Regular expression';
$string['pluginname_help'] = '<p>Regular expressions are a form of writing patterns to match different strings. You can use it to verify answers in two ways: an expression to match with full (usually correct) answer, or an expression to match a part of the answer (which can be used, for example, to catch common errors and give appropriate comments).</p><p>This question uses the PHP perl-compatible regular expression syntax as the default notation. There are many tutorials about creating and using regular expression, here is one <a href="http://www.phpfreaks.com/content/print/126">example</a>. You can find detailed syntax of expression here: <a href="http://www.nusphere.com/kb/phpmanual/reference.pcre.pattern.syntax.htm">php manual</a>. Note that you should neither enclose regular expression in delimiters nor specify any modifiers - Moodle will do it for you.</p><p>You can also use this question as the advanced form of shortanswer with hinting, even if you don\'t know a bit about regular expressions! Just select <b>Moodle shortanswer</b> as notation for your questions.</p>';
$string['php_preg_matcher'] = 'PHP preg extension';
$string['pluginname_link'] = 'question/type/preg';
$string['pluginnameadding'] = 'Adding a regular expression question';
$string['pluginnameediting'] = 'Editing a regular expression question';
$string['pluginnamesummary'] = 'Enter a string response from student that can be matched against several regular expressions. Shows to the student the correct part of his response. Using behaviours with multiple tries can give a hint by telling a next correct character.<br/>You can use it without knowing regular expression to get hinting by using the \'Moodle shortanswer\' notation.';
$string['preg_regex_handler'] = 'Regex handler';
$string['questioneditingheading'] = 'Question editing settings';
$string['subpattern'] = 'Subpattern';
$string['tobecontinued'] = '...';
$string['toomanyerrors'] = '.......{$a} more errors';
$string['ungreedyquant'] = 'ungreedy quantifiers';
$string['unsupported'] = '{$a->nodename} in position from  {$a->indfirst} to {$a->indlast} is unsupported by the {$a->engine}';
$string['unsupportedmodifier'] = 'Error: modifier {$a->modifier} isn\'t supported by the {$a->classname}.';
$string['usecharhint'] = 'Allow next character hinting';
$string['usehint_help'] = 'In behaviours which allow multiple tries (e.g. adaptive or interactive) show students the \'Hint next character\' button that allows to get a one-character hint with applying the \'Hint next character penalty\'. Not all matching engines support hinting.';
$string['usecharhint_help'] = 'In behaviours which allow multiple tries (e.g. adaptive or interactive) show students the \'Hint next character\' button that allows to get a one-character hint with applying the \'Hint next character penalty\'. Not all matching engines support hinting.';
$string['uselexemhint'] = 'Allow next lexem (word, number, punctuation mark) hinting';
$string['uselexemhint_help'] = '<p>In behaviours which allow multiple tries (e.g. adaptive or interactive) show students the \'Hint next word\' button that allows to get a hint either completing current lexem or showing next one if lexem is complete with applying the \'Hint next lexem penalty\'. Not all matching engines support hinting.</p><p><b>Lexem</b> is an atomic part of the language: a word, number, punctuation mark, operator etc.</p>';

/******* Abstract syntax tree nodes descriptions *******/
// Types.
$string['node_abstract']               = 'abstract node';
$string['leaf_charset']                = 'character set';
$string['leaf_meta']                   = 'meta-character or escape-sequence';
$string['leaf_assert']                 = 'simple assertion';
$string['leaf_backref']                = 'backreference';
$string['leaf_recursion']              = 'recursion';
$string['leaf_control']                = 'control sequence';
$string['leaf_options']                = 'modifier';   // TODO: remove?
$string['node_finite_quant']           = 'finite quantifier';
$string['node_infinite_quant']         = 'infinite quantifier';
$string['node_concat']                 = 'concatenation';
$string['node_alt']                    = 'alternative';
$string['node_assert']                 = 'lookaround assertion';
$string['node_subpatt']                = 'subpattern';
$string['node_cond_subpatt']           = 'conditional subpattern';
$string['node_error']                  = 'syntax error';
// Subtypes.
$string['empty_leaf_meta']             = 'emptiness';
$string['circumflex_leaf_assert']      = 'start of the subject assertion';
$string['dollar_leaf_assert']          = 'end of the subject assertion';
$string['esc_b_leaf_assert']           = 'word boundary assertion';
$string['esc_a_leaf_assert']           = 'start of the subject assertion';
$string['esc_z_leaf_assert']           = 'end of the subject assertion';
$string['esc_g_leaf_assert']           = 'first matching position in the subject assertion';
$string['accept_leaf_control']         = '';   // TODO
$string['fail_leaf_control']           = '';
$string['mark_name_leaf_control']      = '';
$string['commit_leaf_control']         = '';
$string['prune_leaf_control']          = '';
$string['skip_leaf_control']           = '';
$string['skip_name_leaf_control']      = '';
$string['then_leaf_control']           = '';
$string['cr_leaf_control']             = '';
$string['lf_leaf_control']             = '';
$string['crlf_leaf_control']           = '';
$string['anycrlf_leaf_control']        = '';
$string['any_leaf_control']            = '';
$string['bsr_anycrlf_leaf_control']    = '';
$string['bsr_unicode_leaf_control']    = '';
$string['no_start_opt_leaf_control']   = '';
$string['utf8_leaf_control']           = '';
$string['utf16_leaf_control']          = '';
$string['ucp_leaf_control']            = '';
$string['pla_node_assert']             = 'positive lookahead assert';
$string['nla_node_assert']             = 'negative lookahead assert';
$string['plb_node_assert']             = 'positive lookbehind assert';
$string['nlb_node_assert']             = 'negative lookbehind assert';
$string['subpatt_node_subpatt']        = 'subpattern';
$string['onceonly_node_subpatt']       = 'once-only subpattern';
$string['subpatt_node_cond_subpatt']   = '"subpattern"-conditional subpattern';
$string['recursion_node_cond_subpatt'] = 'recursive conditional subpattern';
$string['define_node_cond_subpatt']    = '"define"-conditional subpattern';
$string['pla_node_cond_subpatt']       = 'positive lookahead conditional subpattern';
$string['nla_node_cond_subpatt']       = 'negative lookahead conditional subpattern';
$string['plb_node_cond_subpatt']       = 'positive lookbehind conditional subpattern';
$string['nlb_node_cond_subpatt']       = 'negative lookbehind conditional subpattern';
$string['unknown_error_node_error']                                = 'unknown error';
$string['consubpatt_too_much_alter_node_error']                    = 'too much top-level alternatives in a conditional subpattern';
$string['wrong_close_paren_node_error']                            = 'closing paren without opening';
$string['wrong_open_paren_node_error']                             = 'opening paren without closing';
$string['empty_parens_node_error']                                 = 'empty parens';
$string['quantifier_without_parameter_node_error']                 = 'quantifier at the start of the expression';
$string['unclosed_charset_node_error']                             = 'unclosed brackets in a character set';
$string['set_and_unset_same_modifier_at_the_same_time_node_error'] = 'set and unset same modifier at ther same time';
$string['unknown_unicode_property_node_error']                     = 'unknown unicode property';
$string['unknown_posix_class_node_error']                          = 'unknown posix class';
$string['unknown_control_sequence_node_error']                     = 'unknown control sequence (*...)';
$string['incorrect_range_node_error']                              = 'incorrect ranges in a quantifier or a character set: {5,3} or [z-a]';

/******* Error messages *******/
$string['error_PCREincorrectregex']     = 'Incorrect regular expression - syntax error! Consult <a href="http://pcre.org/pcre.txt">PCRE documentation</a> for more information.';
$string['error_threealtincondsubpatt']  = 'Regex syntax error: three or more top-level alternatives in the conditional subpattern in position from {$a->indfirst} to {$a->indlast}. Use parentheses if you want to include alternatives in yes-expr on no-expr.';
$string['error_unopenedparen']          = 'Regex syntax error: missing opening parenthesis \'(\' for the closing parenthesis in position {$a->indfirst}.';
$string['error_unclosedparen']          = 'Regex syntax error: missing a closing parenthesis \')\' for the opening parenthesis in position {$a->indfirst}.';
$string['error_emptyparens']            = 'Regex syntax error: empty parentheses in position from {$a->indfirst} to {$a->indlast}.';
$string['error_quantifieratstart']      = 'Regex syntax error: quantifier in position from {$a->indfirst} to {$a->indlast} doesn\'t have an operand - nothing to repeat.';
$string['error_unclosedsqbrackets']     = 'Regex syntax error: missing a closing bracket \']\' for the character set starting in position {$a->indfirst}.';
$string['error_setunsetmod']            = 'Setting and unsetting the {$a->addinfo} modifier at the same time in position from {$a->indfirst} to {$a->indlast}.';
$string['error_unknownunicodeproperty'] = 'Unknown unicode property: {$a->addinfo}.';
$string['error_unknownposixclass']      = 'Unknown posix class: {$a->addinfo}.';
$string['error_unknowncontrolsequence'] = 'Unknown control sequence: {$a->addinfo}.';
$string['error_incorrectcharsetrange']  = 'Incorrect character range in position from  {$a->indfirst} to {$a->indlast}: the left character is "greater" than the right one.';
$string['error_incorrectquantrange']    = 'Incorrect quantifier range in position from  {$a->indfirst} to {$a->indlast}: the left border is greater than the right one.';


/******* DFA and NFA limitations *******/
$string['engine_heading_descriptions'] = 'Matching regular expressions can be time and memory consuming. These settings allow you to control limits of time and memory usage by the matching engines. Increase them when you get messages that the regular expression is too complex, but do mind your server\'s performance (you may also want to increase PHP time and memory limits). Decrease them if you get blank page when saving or running a preg question.';
$string['too_large_fa'] = 'Regular expression is too complex to be matched by {$a->engine} due to the time and/or memory limits. Please try another matching engine, ask your administrator to <a href="'.$CFG->wwwroot.'/admin/settings.php?section=qtypesettingpreg"> increase time and memory limits</a> or simplify you regular expression.';
$string['fa_state_limit'] = 'Automata size limit: states';
$string['fa_transition_limit'] = 'Automata size limit: transitions';
$string['dfa_settings_heading'] = 'Deterministic finite state automata engine settings';
$string['nfa_settings_heading'] = 'Nondeterministic finite state automata engine settings';
$string['dfa_state_limit_description'] = 'Allows you to tune time and memory limits for the DFA engine when matching complex regexes';
$string['nfa_state_limit_description'] = 'Allows you to tune time and memory limits for the NFA engine when matching complex regexes';
$string['dfa_transition_limit_description'] = 'Maximum number of transitions in DFA';
$string['nfa_transition_limit_description'] = 'Maximum number of transitions in NFA';

/********** This is string for authors tool **********************/
$string['regexeditheader'] = 'Input regex';
$string['regexeditheader_help'] = 'Here you can input regular expression for which will be draw interactive tree, explaining graph and description. In the field "Input regex" you can input/edit regular expression. Pushing "check" button redraws new image with tree, graph and discription.';
$string['regeximgheader'] = 'Interactive tree/explaining graph';
$string['regeximgheader_help'] = 'Here you can see interactive tree and explaining graph. Pressing the node of tree marks corresponding subtree, subgraph and corresponding part of description.';
$string['regexdescriptionheader'] = 'Description';
$string['regexdescriptionheader_help'] = 'Here you can see description of regular expression.';
$string['regexmatchheader'] = 'Input string for check';
$string['regexmatchheader_help'] = 'Here you can input string for matching. In field "Input string" you can input string to varify coincidence whith regular expression (in new string coincidence substring will be marked of greed, don\'t coincidence substring will be marked of reed) or generate one character of continuation. In field "Must match" and "Must not match" you can input strings to varify coincidence/don\'t coincidence whith regular expression (coincidence string will be marked of greed, don\'t coincidence string will be marked of reed).';

// Strings for node description

//TYPE_LEAF_META
$string['description_empty'] = 'nothing';
//TYPE_LEAF_ASSERT
$string['description_circumflex'] = 'beginning of the string';
$string['description_dollar'] = 'end of the string';
$string['description_wordbreak'] = 'at a word boundary';
$string['description_wordbreak_neg'] = 'not at a word boundary';
$string['description_esc_a'] = 'at the start of the subject';
$string['description_esc_z'] = 'at the end of the subject';
$string['description_esc_g'] = 'at the first matching position in the subject';
//TYPE_LEAF_BACKREF
$string['description_backref'] = 'back reference to subpattern #%number';
$string['description_backref_name'] = 'back reference to subpattern "%name"';
//TYPE_LEAF_RECURSION
$string['description_recursion_all'] = 'recursive match with whole regular expression                                 ';
$string['description_recursion'] = 'recursive match with subpattern #%number';
$string['description_recursion_name'] = 'recursive match with subpattern  "%name"';
//TYPE_LEAF_OPTIONS
$string['description_option_i'] = 'caseless: ';
$string['description_unsetoption_i'] = 'case sensitive: ';
/*$string['description_option_s'] = 'dot metacharacter matches \n in following:[%text]';
$string['description_option_m'] = 'multiline matching:[%text]';
$string['description_option_x'] = 'ignore white space:[%text]';
$string['description_option_U'] = 'quantifiers ungreedy by default:[%text]';
$string['description_option_J'] = 'allow duplicate names:[%text]';*/
//TYPE_NODE_FINITE_QUANT
$string['description_finite_quant'] = '%1 is repeated from %leftborder to %rightborder times%greed';
$string['description_finite_quant_0'] = '%1 is repeated no more %rightborder times or missing%greed';
$string['description_finite_quant_1'] = '%1 is repeated no more %rightborder times%greed';
$string['description_finite_quant_01'] = '%1 may be missing%greed';
//TYPE_NODE_INFINITE_QUANT
$string['description_infinite_quant'] = '%1 is repeated at least %leftborder times%greed';
$string['description_infinite_quant_0'] = '%1 is repeated any number of times or missing%greed';
$string['description_infinite_quant_1'] = '%1 is repeated any number of times%greed';
//%greed
$string['description_quant_lazy'] = ' (lazy quantifier)';
$string['description_quant_greed'] = '';
$string['description_quant_possessive'] = ' (possessive quantifier)';
//TYPE_NODE_CONCAT
$string['description_concat'] = '%1 then %2';
$string['description_concat_wcomma'] = '%1 then %2';
$string['description_concat_and'] = '%1 and %2';
$string['description_concat_short'] = '%1%2';
//TYPE_NODE_ALT
$string['description_alt'] = '%1 or %2';
$string['description_alt_wcomma'] = '%1 or %2';
//TYPE_NODE_ASSERT
$string['description_pla_node_assert'] = 'further text should match: [%1]';
$string['description_nla_node_assert'] = 'further text should not match: [%1]';
$string['description_plb_node_assert'] = 'preceding text should match: [%1]';
$string['description_nlb_node_assert'] = 'preceding text should not match: [%1]';
//TYPE_NODE_SUBPATT
$string['description_subpattern'] = 'subpattern #%number: [%1]';
$string['description_subpattern_once'] = 'once checked subpattern #%number: [%1]';
$string['description_subpattern_name'] = 'subpattern "%name": [%1]';
$string['description_subpattern_once_name'] = 'once checked subpattern "%name": [%1]';
//TYPE_NODE_COND_SUBPATT
$string['description_node_cond_subpatt'] = 'if [%cond] then check: [%1]%else';
$string['description_node_cond_subpatt_else'] = ' else check: [%2]';
$string['description_backref_node_cond_subpatt'] = 'if the subpattern #%number has been successfully matched then check: [%1]%else';
$string['description_backref_node_cond_subpatt_name'] = 'if the subpattern "%name" has been successfully matched then check: [%1]%else';
$string['description_recursive_node_cond_subpatt_all'] = 'if the whole pattern has been successfully recursively matched then check: [%1]%else';
$string['description_recursive_node_cond_subpatt'] = 'if the pattern#%number has been successfully recursively matched then check: [%1]%else';
$string['description_recursive_node_cond_subpatt_name'] = 'if the pattern "%name" has been successfully recursively matched then check: [%1]%else';
$string['description_define_node_cond_subpatt'] = 'definition of %1';
//TYPE_LEAF_CONTROL
$string['description_accept_leaf_control'] = 'force successful subpattern match';
$string['description_fail_leaf_control'] = 'force fail';
$string['description_mark_name_leaf_control'] = 'set name to %name to be passed back';
$string['description_control_backtrack'] = 'if the rest of the pattern does not match %what';
$string['description_commit_leaf_control'] = 'overall failure, no advance of starting point';
$string['description_prune_leaf_control'] = 'advance to next starting character';
$string['description_skip_leaf_control'] = 'advance to current matching position';
$string['description_skip_name_leaf_control'] = 'advance to (*MARK:%name)';
$string['description_then_leaf_control'] = 'backtrack to next alternation';
$string['description_control_newline'] = 'newline matches %what';
$string['description_cr_leaf_control'] = 'carriage return only';
$string['description_lf_leaf_control'] = 'linefeed only';
$string['description_crlf_leaf_control'] = 'carriage return followed by linefeed';
$string['description_anycrlf_leaf_control'] = 'carriage return, linefeed or carriage return followed by linefeed';
$string['description_any_leaf_control'] = 'any Unicode newline sequence';
$string['description_control_r'] = '\R matches %what';
$string['description_bsr_anycrlf_leaf_control'] = 'CR, LF, or CRLF';
$string['description_bsr_unicode_leaf_control'] = 'any Unicode newline sequence';
$string['description_no_start_opt_leaf_control'] = 'no start-match optimization';
$string['description_utf8_leaf_control'] = 'UTF-8 mode';
$string['description_utf16_leaf_control'] = 'UTF-16 mode';
$string['description_ucp_leaf_control'] = 'PCRE_UCP';
//TYPE_LEAF_CHARSET
$string['description_charset'] = 'one of the following characters: %characters;';
$string['description_charset_negative'] = 'any symbol except the following: %characters;';
$string['description_charset_one_neg'] = 'not %characters';
//$string['description_charset_one'] = '%characters';
//CHARSET FLAGS
// TODO correct charset flags
$string['description_charset_range'] = 'form <span style="color:blue">%start</span> to <span style="color:blue">%end</span>';
$string['description_char'] = '<span style="color:blue">%char</span>';
$string['description_char_space'] = 'space';
$string['description_char_t'] = 'tabulation';
$string['description_char_n'] = 'newline(LF)';
$string['description_char_r'] = 'carriage return character';
$string['description_char_16value'] = 'character with hex code %code';
//$string['description_char_neg'] = 'not <span style="color:red">%char</span>';
$string['description_charflag_digit'] = 'decimal digit';
$string['description_charflag_xdigit'] = 'hexadecimal digit';
$string['description_charflag_space'] = 'white space';
$string['description_charflag_word'] = 'word character';
$string['description_charflag_alnum'] = 'letter or digit';
$string['description_charflag_alpha'] = 'letter';
$string['description_charflag_ascii'] = 'character with codes 0-127';
$string['description_charflag_cntrl'] = 'control character';
$string['description_charflag_graph'] = 'printing character (excluding space)';
$string['description_charflag_lower'] = 'lower case letter';
$string['description_charflag_upper'] = 'upper case letter';
$string['description_charflag_print'] = 'printing character (including space)';
$string['description_charflag_punct'] = 'printing character (excluding letters and digits and space)';
$string['description_charflag_hspace'] = 'horizontal whitespace character';
$string['description_charflag_vspace'] = 'vertical whitespace character';
$string['description_charflag_Cc'] = 'control';
$string['description_charflag_Cf'] = 'format';
$string['description_charflag_Cn'] = 'unassigned';
$string['description_charflag_Co'] = 'private use';
$string['description_charflag_Cs'] = 'surrogate';
$string['description_charflag_C'] = 'other unicode property';
$string['description_charflag_Ll'] = 'lower case letter';
$string['description_charflag_Lm'] = 'modifier letter';
$string['description_charflag_Lo'] = 'other letter';
$string['description_charflag_Lt'] = 'title case letter';
$string['description_charflag_Lu'] = 'upper case letter';
$string['description_charflag_L'] = 'letter';
$string['description_charflag_Mc'] = 'spacing mark';
$string['description_charflag_Me'] = 'enclosing mark';
$string['description_charflag_Mn'] = 'non-spacing mark';
$string['description_charflag_M'] = 'mark';
$string['description_charflag_Nd'] = 'decimal number';
$string['description_charflag_Nl'] = 'letter number';
$string['description_charflag_No'] = 'other number';
$string['description_charflag_N'] = 'number';
$string['description_charflag_Pc'] = 'connector punctuation';
$string['description_charflag_Pd'] = 'dash punctuation';
$string['description_charflag_Pe'] = 'close punctuation';
$string['description_charflag_Pf'] = 'final punctuation';
$string['description_charflag_Pi'] = 'initial punctuation';
$string['description_charflag_Po'] = 'other punctuation';
$string['description_charflag_Ps'] = 'open punctuation';
$string['description_charflag_P'] = 'punctuation';
$string['description_charflag_Sc'] = 'currency symbol';
$string['description_charflag_Sk'] = 'modifier symbol';
$string['description_charflag_Sm'] = 'mathematical symbol';
$string['description_charflag_So'] = 'other symbol';
$string['description_charflag_S'] = 'symbol';
$string['description_charflag_Zl'] = 'line separator';
$string['description_charflag_Zp'] = 'paragraph separator';
$string['description_charflag_Zs'] = 'space separator';
$string['description_charflag_Z'] = 'separator';
$string['description_charflag_Xan'] = 'any alphanumeric character';
$string['description_charflag_Xps'] = 'any POSIX space character';
$string['description_charflag_Xsp'] = 'any Perl space character';
$string['description_charflag_Xwd'] = 'any Perl "word" character';
$string['description_charflag_Arabic'] = 'Arabic character';
$string['description_charflag_Armenian'] = 'Armenian character';
$string['description_charflag_Avestan'] = 'Avestan character';
$string['description_charflag_Balinese'] = 'Balinese character';
$string['description_charflag_Bamum'] = 'Bamum character';
$string['description_charflag_Bengali'] = 'Bengali character';
$string['description_charflag_Bopomofo'] = 'Bopomofo character';
$string['description_charflag_Braille'] = 'Braille character';
$string['description_charflag_Buginese'] = 'Buginese character';
$string['description_charflag_Buhid'] = 'Buhid character';
$string['description_charflag_Canadian_Aboriginal'] = 'Canadian Aboriginal character';
$string['description_charflag_Carian'] = 'Carian character';
$string['description_charflag_Cham'] = 'Cham character';
$string['description_charflag_Cherokee'] = 'Cherokee character';
$string['description_charflag_Common'] = 'Common character';
$string['description_charflag_Coptic'] = 'Coptic character';
$string['description_charflag_Cuneiform'] = 'Cuneiform character';
$string['description_charflag_Cypriot'] = 'Cypriot character';
$string['description_charflag_Cyrillic'] = 'Cyrillic character';
$string['description_charflag_Deseret'] = 'Deseret character';
$string['description_charflag_Devanagari'] = 'Devanagari character';
$string['description_charflag_Egyptian_Hieroglyphs'] = 'Egyptian Hieroglyphs character';
$string['description_charflag_Ethiopic'] = 'Ethiopic character';
$string['description_charflag_Georgian'] = 'Georgian character';
$string['description_charflag_Glagolitic'] = 'Glagolitic character';
$string['description_charflag_Gothic'] = 'Gothic character';
$string['description_charflag_Greek'] = 'Greek character';
$string['description_charflag_Gujarati'] = 'Gujarati character';
$string['description_charflag_Gurmukhi'] = 'Gurmukhi character';
$string['description_charflag_Han'] = 'Han character';
$string['description_charflag_Hangul'] = 'Hangul character';
$string['description_charflag_Hanunoo'] = 'Hanunoo character';
$string['description_charflag_Hebrew'] = 'Hebrew character';
$string['description_charflag_Hiragana'] = 'Hiragana character';
$string['description_charflag_Imperial_Aramaic'] = 'Imperial Aramaic character';
$string['description_charflag_Inherited'] = 'Inherited character';
$string['description_charflag_Inscriptional_Pahlavi'] = 'Inscriptional Pahlavi character';
$string['description_charflag_Inscriptional_Parthian'] = 'Inscriptional Parthian character';
$string['description_charflag_Javanese'] = 'Javanese character';
$string['description_charflag_Kaithi'] = 'Kaithi character';
$string['description_charflag_Kannada'] = 'Kannada character';
$string['description_charflag_Katakana'] = 'Katakana character';
$string['description_charflag_Kayah_Li'] = 'Kayah Li character';
$string['description_charflag_Kharoshthi'] = 'Kharoshthi character';
$string['description_charflag_Khmer'] = 'Khmer character';
$string['description_charflag_Lao'] = 'Lao character';
$string['description_charflag_Latin'] = 'Latin character';
$string['description_charflag_Lepcha'] = 'Lepcha character';
$string['description_charflag_Limbu'] = 'Limbu character';
$string['description_charflag_Linear_B'] = 'Linear B character';
$string['description_charflag_Lisu'] = 'Lisu character';
$string['description_charflag_Lycian'] = 'Lycian character';
$string['description_charflag_Lydian'] = 'Lydian character';
$string['description_charflag_Malayalam'] = 'Malayalam character';
$string['description_charflag_Meetei_Mayek'] = 'Meetei Mayek character';
$string['description_charflag_Mongolian'] = 'Mongolian character';
$string['description_charflag_Myanmar'] = 'Myanmar character';
$string['description_charflag_New_Tai_Lue'] = 'New Tai Lue character';
$string['description_charflag_Nko'] = 'Nko character';
$string['description_charflag_Ogham'] = 'Ogham character';
$string['description_charflag_Old_Italic'] = 'Old Italic character';
$string['description_charflag_Old_Persian'] = 'Old Persian character';
$string['description_charflag_Old_South_Arabian'] = 'Old South_Arabian character';
$string['description_charflag_Old_Turkic'] = 'Old_Turkic character';
$string['description_charflag_Ol_Chiki'] = 'Ol_Chiki character';
$string['description_charflag_Oriya'] = 'Oriya character';
$string['description_charflag_Osmanya'] = 'Osmanya character';
$string['description_charflag_Phags_Pa'] = 'Phags_Pa character';
$string['description_charflag_Phoenician'] = 'Phoenician character';
$string['description_charflag_Rejang'] = 'Rejang character';
$string['description_charflag_Runic'] = 'Runic character';
$string['description_charflag_Samaritan'] = 'Samaritan character';
$string['description_charflag_Saurashtra'] = 'Saurashtra character';
$string['description_charflag_Shavian'] = 'Shavian character';
$string['description_charflag_Sinhala'] = 'Sinhala character';
$string['description_charflag_Sundanese'] = 'Sundanese character';
$string['description_charflag_Syloti_Nagri'] = 'Syloti_Nagri character';
$string['description_charflag_Syriac'] = 'Syriac character';
$string['description_charflag_Tagalog'] = 'Tagalog character';
$string['description_charflag_Tagbanwa'] = 'Tagbanwa character';
$string['description_charflag_Tai_Le'] = 'Tai_Le character';
$string['description_charflag_Tai_Tham'] = 'Tai_Tham character';
$string['description_charflag_Tai_Viet'] = 'Tai_Viet character';
$string['description_charflag_Tamil'] = 'Tamil character';
$string['description_charflag_Telugu'] = 'Telugu character';
$string['description_charflag_Thaana'] = 'Thaana character';
$string['description_charflag_Thai'] = 'Thai character';
$string['description_charflag_Tibetan'] = 'Tibetan character';
$string['description_charflag_Tifinagh'] = 'Tifinagh character';
$string['description_charflag_Ugaritic'] = 'Ugaritic character';
$string['description_charflag_Vai'] = 'Vai character';
$string['description_charflag_Yi'] = 'Yi character';

//This is string for authors tool
$string['regex_edit_header'] = 'Input regex';
$string['regex_edit_header_help'] = 'Here you can input regular expression for which will be draw interactive tree, explaining graph and description. In the field "Input regex" you can input/edit regular expression. Pushing "check" button redraws new image with tree, graph and discription.';
$string['regex_tree_header'] = 'Interactive tree';
$string['regex_tree_header_help'] = 'Here you can see interactive tree. Pressing the node of tree marks corresponding subtree, subgraph and corresponding part of description.';
$string['regex_graph_header'] = 'Explaining graph';
$string['regex_graph_header_help'] = 'Here you can see explaining graph. Pressing the node of tree marks corresponding subtree, subgraph and corresponding part of description.';
$string['regex_description_header'] = 'Description';
$string['regex_description_header_help'] = 'Here you can see description of regular expression.';
$string['regex_match_header'] = 'Input string for check';
$string['regex_match_header_help'] = 'Here you can input string for matching. In field "Input string" you can input string to varify coincidence whith regular expression (in new string coincidence substring will be marked of greed, don\'t coincidence substring will be marked of reed) or generate one character of continuation. In field "Must match" and "Must not match" you can input strings to varify coincidence/don\'t coincidence whith regular expression (coincidence string will be marked of greed, don\'t coincidence string will be marked of reed).';

// Strings for explaining graph

$string['explain_subpattern'] = 'subpattern #';
$string['explain_backref'] = 'the result of subpattern #';
$string['explain_recursion'] = 'recursion';
$string['explain_unknow_node'] = 'unknow node';
$string['explain_unknow_meta'] = 'unknow meta';
$string['explain_unknow_assert'] = 'unknow assert';
$string['explain_unknow_charset_flag'] = 'unknow charset flag';
$string['explain_tab'] = 'tabulation';
$string['explain_not'] = 'not ';
$string['explain_quote'] = 'quote';
$string['explain_slash'] = 'slash';
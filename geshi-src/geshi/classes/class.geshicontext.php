<?php
/**
 * GeSHi - Generic Syntax Highlighter
 * 
 * For information on how to use GeSHi, please consult the documentation
 * found in the docs/ directory, or online at http://geshi.org/docs/
 * 
 *  This file is part of GeSHi.
 *
 *  GeSHi is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  GeSHi is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with GeSHi; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * You can view a copy of the GNU GPL in the COPYING file that comes
 * with GeSHi, in the docs/ directory.
 *
 * @package   core
 * @author    Nigel McNie <nigel@geshi.org>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright (C) 2005 Nigel McNie
 * @version   $Id$
 * 
 */

/**
 * The GeSHiContext class
 * 
 * @package core
 * @author  Nigel McNie
 * @since   1.1.0
 * @version $Revision$
 */
class GeSHiContext
{
    // {{{ properties
    
    /**#@-
     * @access private
     */
    
    /**
     * The context name. A unique identifier that corresponds to a path under
     * the GESHI_CLASSES_ROOT folder where the configuration file for this
     * context is.
     * @var string
     */
    var $_contextName;
    
    /**
     * The file name from where to load data for this context
     * @var string
     */
    var $_fileName;
    
    /**
     * The dialect name of this context
     * @var string
     */
    var $_dialectName;
    
    /**
     * The styler helper object
     * @var GeSHiStyler
     */
    var $_styler;
    
    /**
     * The context delimiters
     * @var array
     */
    var $_contextDelimiters = array();
    
    /**
     * The child contexts
     * @var array
     */
    var $_childContexts = array();
    
    /**
     * The style type of this context, used for backward compatibility
     * with GeSHi 1.0.X
     * @var int
     */
    var $_contextStyleType = GESHI_STYLE_NONE;
    
    /**
     * Delimiter parse data. Controls which context - the parent or child -
     * should parse the delimiters for a context
     * @var int
     */
    var $_delimiterParseData = GESHI_CHILD_PARSE_BOTH;
    
    /**
     * The overriding child context, if any
     * @var GeSHiContext
     */
    var $_overridingChildContext;
    
    /**
     * The matching regex table for regex starters
     * @var array
     */
     var $_startRegexTable = array();
    
    /**
     * The "infectious context". Will be used to "infect" the context
     * tree with itself - this is how PHP inserts itself into HTML contexts
     * @var GeSHiContext
     */
    var $_infectiousContext;
    
    /**
     * Whether this context has been already loaded
     * @var boolean
     */
    var $_loaded = false;
    
    /**
     * The name for stuff detected in the start of a context
     * @var string 
     */
    var $_startName = 'start';
    
    /**
     * The name for stuff detected in the end of a context
     * @var string 
     */
    var $_endName = 'end';

    /**
     * Whether this context is an alias context
     * @var boolean
     */
    var $_isAlias = false;
    
    /**
     * The name of the context if not aliased
     * @var string
     */
    var $_aliasForContext = '';
    
    /**
     * Whether this context should never be trimmed
     * @var boolean
     */
    var $_neverTrim = false;
    
    /**
     * Whether this context should be broken up by whitespace
     * for the code parser
     * @var boolean
     */
    var $_isComplex = false;
    /**#@-*/
    
    // }}}
    // {{{ GeSHiContext()
    
    /**
     * Creates a new GeSHiContext.
     * 
     * @param string The name of the language this context represents
     * @param string The dialect of the language this context represents
     * @param string The name of the context
     * @param array  The name used for aliasing
     * @todo [blocking 1.1.9] Better comment
     */
    function GeSHiContext ($language_name, $dialect_name = '', $context_name = '', $alias_name = '')
    {
        // Set dialect
        if ('' == $dialect_name) {
            $dialect_name = $language_name;
        }
        $this->_dialectName = $dialect_name;
        
        // Set the context and file names
        if ('' == $context_name) {
            // Root of a language
            $this->_fileName = $this->_contextName = $language_name . '/' . $dialect_name;
            return;
        }
        if (0 === strpos($context_name, 'common')) {
            $this->_fileName = $context_name;
            // Strip "common/" from context name to get the actual name...
            $context_name = substr($context_name, 7);
        } else {
            $this->_fileName = $language_name . '/' . $context_name;
        }
        if ($alias_name) {
            $this->_contextName = $alias_name;
            $this->_isAlias     = true;
            $this->_aliasForContext = "$language_name/$dialect_name/$context_name";
        } else {
            $this->_contextName = "$language_name/$dialect_name/$context_name";
        }
    }
    
    // }}}
    // {{{ getName()
    
    /**
     * Returns the name of this context
     * 
     * @return string The full name of this context (language, dialect and context)
     */
    function getName ()
    {
        return $this->_contextName;
    }
    
    // }}}
    // {{{ getStartName()
    
    /**
     * Returns the name given to the part of this context that is
     * matched by the starting context delimiter. Typically this is
     * used by aliased contexts to lie about what they really start
     * with.
     * 
     * @return string
     */
    function getStartName ()
    {
        return $this->_startName;
    }
    
    // }}}
    // {{{ getEndName()
    
    /**
     * Returns the name given to the part of this context that is
     * matched by the ending context delimiter. Typically this is
     * used by aliased contexts to lie about what they really end
     * with.
     * 
     * @return string
     */
    function getEndName ()
    {
        return $this->_endName;
    }
    
    // }}}
    // {{{ isAlias()
    
    /**
     * Returns whether this context is an aliased context
     * 
     * @return boolean
     */
    function isAlias ()
    {
        return $this->_isAlias;
    }
    
    // }}}
    // {{{ load()
    
    /**
     * Loads the context data
     * 
     * @param GeSHiStyler The styler object to use
     */
    function load (&$styler)
    {
        geshi_dbg('Loading context: ' . $this->_contextName, GESHI_DBG_INFO + GESHI_DBG_LOADING);
        
        if ($this->_loaded) {
            geshi_dbg('@oAlready loaded', GESHI_DBG_INFO + GESHI_DBG_LOADING);
            return;
        }
        $this->_loaded = true;
        
        $this->_styler =& $styler;
        
        if (!geshi_can_include(GESHI_CONTEXTS_ROOT . $this->_fileName . $this->_styler->fileExtension)) {
            geshi_dbg('@e  Cannot get context information for ' . $this->getName() . ' from file '
                . GESHI_CONTEXTS_ROOT . $this->_fileName . $this->_styler->fileExtension,
                GESHI_DBG_ERR + GESHI_DBG_LOADING);
            return array('code' => GESHI_ERROR_FILE_UNAVAILABLE, 'name' => $this->_contextName);
        }geshi_dbg('@oCan load file', GESHI_DBG_NOTICE + GESHI_DBG_LOADING);
        
        // Load the data for this context
        $CONTEXT = $this->_contextName;
        $CONTEXT_START = "$this->_contextName/$this->_startName";
        $CONTEXT_END   = "$this->_contextName/$this->_endName";
        $DIALECT = $this->_dialectName;
        // @todo [blocking 1.1.5] This needs testing to see if it is faster
        if (false) {
            $language_file_name = GESHI_CONTEXTS_ROOT . $this->_contextName . $this->_styler->fileExtension;
            $cached_data = $this->_styler->getCacheData($language_file_name);
            if (null == $cached_data) {
                // Data not loaded for this context yet
                //geshi_dbg('@wLoading data for context ' . $this->_contextName, GESHI_DBG_INFO + GESHI_DBG_LOADING);
                // Get the data, stripping the start/end PHP code markers which aren't allowed in eval()
                $cached_data = substr(implode('', file($language_file_name)), 5, -3);
                $this->_styler->setCacheData($language_file_name, $cached_data);
            } else {
                //geshi_dbg('@oRetrieving data from cache for context ' . $this->_contextName,
                //    GESHI_DBG_INFO + GESHI_DBG_LOADING);
            }
            eval($cached_data);
        } else {
            require GESHI_CONTEXTS_ROOT . $this->_fileName . $this->_styler->fileExtension;
        }
        
        // Push the infectious context into the child contexts
        if (null != $this->_infectiousContext) {
            // Add the context to each of the current contexts...
            $keys = array_keys($this->_childContexts);
            foreach ($keys as $key) {
                $this->_childContexts[$key]->infectWith($this->_infectiousContext);
            }
            // And add the infectious context to this context itself
            $this->_childContexts[] =& $this->_infectiousContext;
            //geshi_dbg('  Added infectious context ' . $this->_infectiousContext->getName()
            //    . ' to ' . $this->getName(), GESHI_DBG_INFO + GESHI_DBG_LOADING);
        }

        // Recursively load the child contexts
        $keys = array_keys($this->_childContexts);
        foreach ($keys as $key) {
            $this->_childContexts[$key]->load($styler);
        }
        
        // Load the overriding child context, if any
        if ($this->_overridingChildContext) {
            if (null != $this->_infectiousContext) {
                $this->_overridingChildContext->infectWith($this->_infectiousContext);
            }
            $this->_overridingChildContext->load($styler);
        }
        //geshi_dbg('@o  Finished loading context ' . $this->_styleName . ' successfully',
        //    GESHI_DBG_NOTICE + GESHI_DBG_LOADING);
    }

    // }}}
    // {{{ infectWith()
    
    /**
     * Adds an "infectious child" to this context.
     * 
     * Relies on child being a subclass of or actually being a GeSHiContext
     */
    function infectWith (&$context)
    {
        $this->_infectiousContext =& $context;
        //geshi_dbg('  Added infectious context ' . $context->getName()
        //    . ' to ' . $this->getName(), GESHI_DBG_NOTICE + GESHI_DBG_LOADING);
    }
    
    // }}}
    // {{{ loadStyleData()
    
    /**
     * Loads style data for the given context. Not implemented here, but can be overridden
     * by a child class to get style data from its parent
     * 
     * Note to self: This is needed by GeSHiCodeContext, so don't touch it!
     */
    function loadStyleData ()
    {
        //geshi_dbg('Loading style data for context ' . $this->getName(), GESHI_DBG_INFO + GESHI_DBG_LOADING);
        // Recursively load the child contexts
        $keys = array_keys($this->_childContexts);
        foreach ($keys as $key) {
            $this->_childContexts[$key]->loadStyleData();
        }
        
        // Load the style data for the overriding child context, if any
        if ($this->_overridingChildContext) {
            $this->_overridingChildContext->loadStyleData();
        }        
    }
    
    // }}}
    // {{{ trimUselessChildren()
    
    /**
     * Checks each child to see if it's useful. If not, then remove it
     * 
     * @param string The code that can be used to check if a context
     *               is needed.
     */
    function trimUselessChildren ($code)
    {
        //geshi_dbg('GeSHiContext::trimUselessChildren()', GESHI_DBG_INFO + GESHI_DBG_LOADING);
        $new_children = array();
        $keys = array_keys($this->_childContexts);
        
        foreach ($keys as $key) {
            //geshi_dbg('  Checking child: ' . $this->_childContexts[$key]->getName() . ': ',
            //    GESHI_DBG_DEBUG + GESHI_DBG_LOADING, false);
            if (!$this->_childContexts[$key]->contextCanStart($code)) {
                // This context will _never_ be useful - and nor will its children
                //geshi_dbg('@buseless, removed', GESHI_DBG_DEBUG + GESHI_DBG_LOADING);
                // RAM saving technique
                // But we shouldn't remove highlight data if the child is an
                // "alias" context, since the real context might need the data
                if (!$this->_childContexts[$key]->isAlias()) {
                    $this->_styler->removeStyleData($this->_childContexts[$key]->getName(),
                        $this->_childContexts[$key]->getStartName(),
                        $this->_childContexts[$key]->getEndName());
                }
                unset($this->_childContexts[$key]);
            }
        }
        
        // Recurse into the remaining children, checking them
        $keys = array_keys($this->_childContexts);
        foreach ($keys as $key) {
            $this->_childContexts[$key]->trimUselessChildren($code);
        }
    }
    
    // }}}
    // {{{ parseCode()        
    
    /**
     * Parses the given code
     */
    function parseCode (&$code, $context_start_key = -1, $context_start_delimiter = '', $ignore_context = '',
        $first_char_of_next_context = '')
    {
        geshi_dbg('*** GeSHiContext::parseCode(' . $this->_contextName . ') ***', GESHI_DBG_NOTICE + GESHI_DBG_PARSING);
        geshi_dbg('CODE: ' . str_replace("\n", "\r", substr($code, 0, 100)) . "<<<<<\n",
            GESHI_DBG_INFO + GESHI_DBG_PARSING);
        if ($context_start_delimiter) geshi_dbg('Delimiter: ' . $context_start_delimiter,
            GESHI_DBG_INFO + GESHI_DBG_PARSING);
        // Skip empty/almost empty contexts
        if (!$code || ' ' == $code) {
            $this->_addParseData($code);
            return;
        }
        
        // FIRST:
        //   If there is an "overriding child context", it should immediately take control
        //   of the entire parsing.
        //   An "overriding child context" has the following properties:
        //     * No starter or ender delimiter
        //
        //   The overridden context has the following properties:
        //     * Explicit starter/ender
        //     * No children (they're not relevant after all)
        //   
        //   An example: HTML embeds CSS highlighting by using the html/css context. This context
        //   has one overriding child context: css. After all, once in the CSS context, HTML don't care
        //   anymore.
        //   Likewise, javascript embedded in HTML is an overriding child - HTML does the work of deciding
        //   exactly where javascript gets called, and javascript does the rest.
        //
        
        // If there is an overriding context...
        if ($this->_overridingChildContext) {
            // Find the end of this thing
            $finish_data = $this->_getContextEndData($code, $context_start_key, $context_start_delimiter, true); // true?
            // If this context should not parse the ender, add it on to the stuff to parse
            if ($this->shouldParseEnder()) {
                $finish_data['pos'] += $finish_data['len'];
            }
            // Make a temp copy of the stuff the occ will parse
            $tmp = substr($code, 0, $finish_data['pos']);
            // Tell the occ to parse the copy
            $this->_overridingChildContext->parseCode($tmp); // start with no starter at all
            // trim the code
            $code = substr($code, $finish_data['pos']);
            return;
        }
        
        // Add the start of this context to the parse data if it is already known
        if ($context_start_delimiter) {
            $this->_addParseDataStart($context_start_delimiter);
            $code = substr($code, strlen($context_start_delimiter));
        }
        
        $original_length = strlen($code);
        
        while ('' != $code) {
            if (strlen($code) != $original_length) {
                geshi_dbg('CODE: ' . str_replace("\n", "\r", substr($code, 0, 100)) . "<<<<<\n",
                    GESHI_DBG_NOTICE + GESHI_DBG_PARSING);
            }
            // Second parameter: if we are at the start of the context or not
            // Pass the ignored context so it can be properly ignored
            $earliest_context_data = $this->_getEarliestContextData($code, strlen($code) == $original_length,
                $ignore_context);
            $finish_data = $this->_getContextEndData($code, $context_start_key, $context_start_delimiter,
                strlen($code) == $original_length);
            geshi_dbg('@bEarliest context data: pos=' . $earliest_context_data['pos'] . ', len=' .
                $earliest_context_data['len'], GESHI_DBG_INFO + GESHI_DBG_PARSING);
            geshi_dbg('@bFinish data: pos=' . $finish_data['pos'] . ', len=' . $finish_data['len'],
                GESHI_DBG_INFO + GESHI_DBG_PARSING);
            
            // If there is earliest context data we parse up to it then hand control to that context
            if ($earliest_context_data) {
                if ($finish_data) {
                    // Merge to work out who wins
                    if ($finish_data['pos'] <= $earliest_context_data['pos']) {
                        geshi_dbg('Earliest context and Finish data: finish is closer',
                            GESHI_DBG_INFO + GESHI_DBG_PARSING);
                        
                        // Add the parse data
                        $this->_addParseData(substr($code, 0, $finish_data['pos']), substr($code, $finish_data['pos'], 1));
                        
                        // If we should pass the ender, add the parse data
                        if ($this->shouldParseEnder()) {
                        	$this->_addParseDataEnd(substr($code, $finish_data['pos'], $finish_data['len']));
                        	$finish_data['pos'] += $finish_data['len'];
                        }
                        // Trim the code and return the unparsed delimiter
                        $code = substr($code, $finish_data['pos']);
                        return $finish_data['dlm'];
                    } else {
                        geshi_dbg('Earliest and finish data, but earliest gets priority',
                            GESHI_DBG_INFO + GESHI_DBG_PARSING);
                        $foo = true;
                    }
                } else { $foo = true; /** no finish data */}

                if (isset($foo)) geshi_dbg('Earliest data but not finish data', GESHI_DBG_INFO + GESHI_DBG_PARSING);
                // Highlight up to delimiter
                ///The "+ len" can be manipulated to do starter and ender data
                if (!$earliest_context_data['con']->shouldParseStarter()) {
                     $earliest_context_data['pos'] += $earliest_context_data['len'];
                     //BUGFIX: null out dlm so it doesn't squash the actual rest of context
                     $earliest_context_data['dlm'] = '';
                }
                                
                // We should parseCode() the substring.
                // BUT we have to remember that we should ignore the child context we've matched,
                // else we'll have a wee recursion problem on our hands...
                $tmp = substr($code, 0, $earliest_context_data['pos']);
                $this->parseCode($tmp, -1, '', $earliest_context_data['con']->getName(),
                    substr($code, $earliest_context_data['pos'], 1)); // parse with no starter
                $code = substr($code, $earliest_context_data['pos']);
                $ender = $earliest_context_data['con']->parseCode($code, $earliest_context_data['key'], $earliest_context_data['dlm']);
                // check that the earliest context actually wants the ender
                if (!$earliest_context_data['con']->shouldParseEnder() && $earliest_context_data['dlm'] == $ender) {
                	geshi_dbg('earliest_context_data[dlm]=' . $earliest_context_data['dlm'] . ', ender=' . $ender,
                        GESHI_DBG_INFO + GESHI_DBG_PARSING);
                    // second param = first char of next context
                    $this->_addParseData(substr($code, 0, strlen($ender)), substr($code, strlen($ender), 1));
                    $code = substr($code, strlen($ender));
                }
            } else {
                if ($finish_data) {
                    // finish early...
                    geshi_dbg('No earliest data but finish data', GESHI_DBG_INFO + GESHI_DBG_PARSING);

                    // second param = first char of next context
                    $this->_addParseData(substr($code, 0, $finish_data['pos']), substr($code, $finish_data['pos'], 1));
                    
                    if ($this->shouldParseEnder()) {
                       	$this->_addParseDataEnd(substr($code, $finish_data['pos'], $finish_data['len']));
                       	$finish_data['pos'] += $finish_data['len'];
                    }
                    $code = substr($code, $finish_data['pos']);
                    // return the length for use above
                    return $finish_data['dlm'];
                } else {
                    geshi_dbg('No earliest or finish data', GESHI_DBG_INFO + GESHI_DBG_PARSING);
                    // All remaining code is in this context
                    $this->_addParseData($code, $first_char_of_next_context);
                    $code = '';
                    return; // not really needed (?)
                }
            }
        }
    }
    
    // }}}
    // {{{ shouldParseStarter()

    /**
     * @return true if this context wants to parse its start delimiters
     */    
    function shouldParseStarter()
    {
        return $this->_delimiterParseData & GESHI_CHILD_PARSE_LEFT;
    }
    
    // }}}
    // {{{ shouldParseEnder()
    
    /**
     * @return true if this context wants to parse its end delimiters
     */
    function shouldParseEnder ()
    {
        return $this->_delimiterParseData & GESHI_CHILD_PARSE_RIGHT;
    }
    
    // }}}
    // {{{ contextCanStart()

    /**
     * Return true if it is possible for this context to parse this code at all
     */    
    function contextCanStart ($code)
    {
        if ($this->_neverTrim) {
            return true;
        }
        foreach ($this->_contextDelimiters as $key => $delim_array) {
            foreach ($delim_array[0] as $delimiter) {
                //geshi_dbg('    Checking delimiter ' . $delimiter . '... ', GESHI_DBG_INFO + GESHI_DBG_LOADING, false);
                $data     = geshi_get_position($code, $delimiter, 0, $delim_array[2]);
                
                if (false !== $data['pos']) {
                    return true;
                }
            }
        }
        return false;
    }
    
    // }}}
    // {{{ _getEarliestContextData()

    /**
     * Works out the closest child context
     * 
     * @param $ignore_context The context to ignore (if there is one)
     */
    function _getEarliestContextData ($code, $start_of_context, $ignore_context)
    {
        geshi_dbg('  GeSHiContext::_getEarliestContextData(' . $this->_contextName . ', '. $start_of_context . ')',
            GESHI_DBG_INFO + GESHI_DBG_PARSING);
        $earliest_pos = false;
        $earliest_len = false;
        $earliest_con = null;
        $earliest_key = -1;
        $earliest_dlm = '';
        
        foreach ($this->_childContexts as $context) {
            if ($ignore_context == $context->getName()) {
                // whups, ignore you...
                continue;
            }
            $data = $context->getContextStartData($code, $start_of_context);
            geshi_dbg('  ' . $context->_contextName . ' says it can start from ' . $data['pos'],
                GESHI_DBG_INFO + GESHI_DBG_PARSING, false);
            
            if (-1 != $data['pos']) {
                if ((false === $earliest_pos) || $earliest_pos > $data['pos'] ||
                   ($earliest_pos == $data['pos'] && $earliest_len < $data['len'])) {
                    geshi_dbg(' which is the earliest position', GESHI_DBG_INFO + GESHI_DBG_PARSING);
                    $earliest_pos = $data['pos'];
                    $earliest_len = $data['len'];
                    $earliest_con = $context;
                    $earliest_key = $data['key'];
                    $earliest_dlm = $data['dlm'];
                } else {
                    geshi_dbg('', GESHI_DBG_INFO + GESHI_DBG_PARSING);
                }
            } else {
                geshi_dbg('', GESHI_DBG_INFO + GESHI_DBG_PARSING);
            }
        }
        // What do we need to know?
        //   Well, assume that one of the child contexts can parse
        //   Then, parseCode() is going to call parseCode() recursively on that object
        //   
        if (false !== $earliest_pos) {
            return array('pos' => $earliest_pos, 'len' => $earliest_len, 'con' => $earliest_con, 'key' => $earliest_key, 'dlm' => $earliest_dlm);
        } else {
            return false;
        }
    }
    
    // }}}
    // {{{ getContextStartData()
    
    /**
     * Checks the context delimiters for this context against the passed
     * code to see if this context can help parse the code
     */
    function getContextStartData ($code, $start_of_context)
    {
        //geshi_dbg('    GeSHi::getContextStartInformation(' . $this->_contextName . ')',
        //    GESHI_DBG_INFO + GESHI_DBG_PARSING);
        geshi_dbg('  ' . $this->_contextName, GESHI_DBG_INFO + GESHI_DBG_PARSING);

        $first_position = -1;
        $first_length   = -1;
        $first_key      = -1;
        $first_dlm      = '';
        
        foreach ($this->_contextDelimiters as $key => $delim_array) {
            foreach ($delim_array[0] as $delimiter) {
                geshi_dbg('    Checking delimiter ' . $delimiter . '... ', GESHI_DBG_INFO + GESHI_DBG_PARSING, false);
                $data     = geshi_get_position($code, $delimiter, 0, $delim_array[2], true);
                geshi_dbg(print_r($data, true), GESHI_DBG_INFO + GESHI_DBG_PARSING, false);
                $position = $data['pos'];
                $length   = $data['len'];
                if (isset($data['tab'])) {
                    geshi_dbg('Table: ' . print_r($data['tab'], true), GESHI_DBG_INFO + GESHI_DBG_PARSING);
                    $this->_startRegexTable = $data['tab'];
                    $delimiter = $data['tab'][0];
                }
                
                if (false !== $position) {
                    geshi_dbg('found at position ' . $position . ', checking... ',
                        GESHI_DBG_INFO + GESHI_DBG_PARSING, false);
                    if ((-1 == $first_position) || ($first_position > $position) ||
                       (($first_position == $position) && ($first_length < $length))) {
                        geshi_dbg('@bearliest! (length ' . $length . ')',
                            GESHI_DBG_INFO + GESHI_DBG_PARSING);
                        $first_position = $position;
                        $first_length   = $length;
                        $first_key      = $key;
                        $first_dlm      = $delimiter;
                    }
                } else {
                    geshi_dbg('', GESHI_DBG_INFO + GESHI_DBG_PARSING);
                }
            }
        }
        
        return array('pos' => $first_position, 'len' => $first_length,
                     'key' => $first_key, 'dlm' => $first_dlm);
    }
    
    // }}}
    // {{{ _getContextEndData()
    
    /**
     * GetContextEndData
     */
    function _getContextEndData ($code, $context_open_key, $context_opener, $beginning_of_context)
    {
        geshi_dbg('GeSHiContext::_getContextEndData(' . $this->_contextName . ', ' . $context_open_key . ', '
        	. $context_opener . ', ' . $beginning_of_context . ')', GESHI_DBG_INFO + GESHI_DBG_PARSING);
        $context_end_pos = false;
        $context_end_len = -1;
        $context_end_dlm = '';
        $offset = 0;
        
        // Bail out if context open key tells us that there is no ender for this context
        if (-1 == $context_open_key) {
        	geshi_dbg('  no opener so no ender', GESHI_DBG_INFO + GESHI_DBG_PARSING);
        	return false;
        }

        // Balanced endings is handled here
        if (isset($this->_contextDelimiters[$context_open_key][3])) {
            $balance_opener = $this->_contextDelimiters[$context_open_key][3][0];
            $balance_closer = $this->_contextDelimiters[$context_open_key][3][1];

            // We get the first push for free
            // @todo [blocking 1.1.1] if what we are balancing against is not related
            // to the starter of the context then we have a problem... check $context_opener
            // for starter stuff instead of assuming
            $balance_count = 1;
            geshi_dbg('@w  Begun balancing', GESHI_DBG_INFO + GESHI_DBG_PARSING);
            
            while ($balance_count > 0) {
                // Look for opener/closers.
                $opener_pos = geshi_get_position($code, $balance_opener, $offset);
                $closer_pos = geshi_get_position($code, $balance_closer, $offset);
                geshi_dbg('  opener pos = ' . print_r($opener_pos,true) . ', closer pos = ' . print_r($closer_pos,true),
                    GESHI_DBG_INFO + GESHI_DBG_PARSING);
                
                // Check what we found
                if (false !== $opener_pos['pos']) {
                    if (false !== $closer_pos['pos']) {
                        // Opener and closer available
                        if ($opener_pos['pos'] < $closer_pos['pos']) {
                            // Opener is closer so inc. counter
                            ++$balance_count;
                            geshi_dbg('  opener is closer so inc. to ' . $balance_count,
                                GESHI_DBG_INFO + GESHI_DBG_PARSING);
                            // Start searching from new pos just past where we found the opener
                            $offset = $opener_pos['pos'] + 1;
                            // @todo [blocking 1.1.1] could cache closer pos at this point?
                        } else {
                            // closer is closer (bad english heh)
                            --$balance_count;
                            $offset = $closer_pos['pos'] + 1;
                            geshi_dbg('  closer is closer so dec. to ' . $balance_count,
                                GESHI_DBG_INFO + GESHI_DBG_PARSING);
                        }
                    } else {
                        // No closer will ever be available yet we are still in this context...
                        // use end of code as end pos
                        // I've yet to test this case
                        geshi_dbg('@w  No closer but still in this context!', GESHI_DBG_INFO + GESHI_DBG_PARSING);
                        return array('pos' => strlen($code), 'len' => 0, 'dlm' => '');
                    }
                } elseif (false !== $closer_pos['pos']) {
                    // No opener but closer. Nothing wrong with this
                    --$balance_count;
                    $offset = $closer_pos['pos'] + 1;
                    geshi_dbg('  only closer left, dec. to ' . $balance_count, GESHI_DBG_INFO + GESHI_DBG_PARSING);
                } else {
                    // No opener or closer
                    // Assume that we end this context at the end of the code, with
                    // no delimiter
                    geshi_dbg('@w  No opener or closer but still in this context!', GESHI_DBG_INFO + GESHI_DBG_PARSING);
                    return array('pos' => strlen($code), 'len' => 0, 'dlm' => '');
                }
            }
            // start looking for real end from the position where balancing ends
            // because we've found where balancing ends, but the end of the balancing
            // is likely to be the same as the end of the context
            --$offset;
        }        
        
        foreach ($this->_contextDelimiters[$context_open_key][1] as $ender) {
            geshi_dbg('  Checking ender: ' . str_replace("\n", '\n', $ender), GESHI_DBG_INFO + GESHI_DBG_PARSING, false);
            $ender = $this->_substitutePlaceholders($ender);
            geshi_dbg(' converted to ' . $ender, GESHI_DBG_INFO + GESHI_DBG_PARSING);
            
            // Use the offset we may have found when handling balancing of contexts (will
            // be zero if balancing not done).
            $position = geshi_get_position($code, $ender, $offset);
            geshi_dbg('    Ender ' . $ender . ': ' . print_r($position, true), GESHI_DBG_INFO + GESHI_DBG_PARSING);
            $length   = $position['len'];
            $position = $position['pos'];
            
            // BUGFIX:skip around crap starters
            if (false === $position) {
                continue;
            }
            
            if ((false === $context_end_pos) || ($position < $context_end_pos)
                || ($position == $context_end_pos && strlen($ender) > $context_end_len)) {
                $context_end_pos = $position;
                $context_end_len = $length;
                $context_end_dlm = $ender;
            }
        }
        geshi_dbg('Context ' . $this->_contextName . ' can finish at position ' . $context_end_pos,
            GESHI_DBG_INFO + GESHI_DBG_PARSING);
        
        if (false !== $context_end_pos) {
            return array('pos' => $context_end_pos, 'len' => $context_end_len, 'dlm' => $context_end_dlm);
        } else {
            return false;
        }
    }
    
    // }}}
    // {{{ _addParseData()
    
    /**
     * Adds parse data to the overall result
     * 
     * This method is mainly designed so that subclasses of GeSHiContext can
     * override it to break the context up further - for example, GeSHiStringContext
     * uses it to add escape characters
     * 
     * @param string The code to add
     * @param string The first character of the next context (used by GeSHiCodeContext)
     */
    function _addParseData ($code, $first_char_of_next_context = '')
    {
       $this->_styler->addParseData($code, $this->_contextName, $this->_getExtraParseData(), $this->_isComplex);
    }
    
    // }}}
    // {{{ _addParseDataStart()
    
    /**
     * Adds parse data for the start of a context to the overallresult
     */
    function _addParseDataStart ($code)
    {
        $this->_styler->addParseDataStart($code, $this->_contextName, $this->_startName, $this->_isComplex);
    }
    
    // }}}
    // {{{ _addParseDataEnd()

    /**
     * Adds parse data for the end of a context to the overallresult
     */
    function _addParseDataEnd ($code)
    {
        $this->_styler->addParseDataEnd($code, $this->_contextName, $this->_endName, $this->_isComplex);
    }
    
    // }}}
    // {{{ _getExtraParseData()
    
    function _getExtraParseData ($data = array())
    {
        $return = ($this->_isAlias ? array('alias_name' => $this->_aliasForContext) : array());
        return array_merge($return, $data);
    }
    
    // {{{ _substitutePlaceholders()
    
    /**
     * Substitutes placeholders for values matched in opening regular expressions
     * for contexts with their actual values
     * 
     * 
     */
    function _substitutePlaceholders ($ender)
    {
        if ($this->_startRegexTable) {
            foreach ($this->_startRegexTable as $key => $match) {
                $ender = str_replace('!!!' . $key, quotemeta($match), $ender);
            }
        }
        return $ender;
    }
    
    // }}}
}

?>

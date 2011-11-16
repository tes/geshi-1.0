<?php
/*************************************************************************************
 * <bb4w.php>
 * ---------------------------------
 * Author: Jon Ripley (geshi at jonripley dot com)
 * Copyright: (c) 2011 Jon Ripley (http://jonripley.com)
 * Release Version: 1.0.8.11
 * Date Started: 05/07/2011
 *
 * BBC BASIC for Windows (BB4W) language file for GeSHi.
 *
 * CHANGES
 * -------
 * 12/11/2011 (1.0.8.11)
 *  -  First Release
 *
 * TODO (updated 12/11/2011)
 * -------------------------
 * Line numbers after GOTO, GOSUB and RESTORE
 * Adjacent keywords with no whitespace
 * Line continuation comments
 *
 *************************************************************************************
 *
 *     This file is part of GeSHi.
 *
 *   GeSHi is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   GeSHi is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with GeSHi; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 * ************************************************************************************/

$language_data = array (
    'LANG_NAME' => 'BBC BASIC for Windows',
    'COMMENT_SINGLE' => array(1 => 'REM', 2 => '*|'),
    'COMMENT_MULTI' => array(),
    'CASE_KEYWORDS' => GESHI_CAPS_NO_CHANGE,
    'QUOTEMARKS' => array('"'),
    'ESCAPE_CHAR' => '',
    'KEYWORDS' => array(
        // Keywords that can start an identifier
        1 => array(
            'BGET', 'BPUT', 'BY', 'CLEAR', 'CLG', 'CLOSE', 'CLS', 'COUNT',
            'END', 'ENDCASE', 'ENDIF', 'ENDPROC', 'ENDWHILE', 'EOF', 'ERL',
            'ERR', 'EXIT', 'EXT', 'FALSE', 'HIMEM', 'LOMEM', 'OF', 'OFF',
            'PAGE', 'PI', 'POS', 'PTR', 'QUIT', 'REPORT', 'RETURN', 'RND',
            'RUN', 'STOP', 'TIME', 'TRUE', 'VPOS', 'WAIT'
        ),
        // Keywords that cannot start an identifier
        2 => array(
            'ABS', 'ACS', 'ADVAL', 'AND', 'ASC', 'ASN', 'ATN', 'CALL',
            'CASE', 'CHAIN', 'CIRCLE', 'COLOR', 'COLOUR', 'COS',
            'DATA', 'DEG', 'DIM', 'DIV', 'DRAW', 'ELLIPSE',    'ELSE',
            'ENVELOPE', 'EOR', 'ERROR', 'EVAL', 'EXP', 'FILL', 'FOR',
            'GCOL', 'GET', 'GOSUB', 'GOTO', 'IF', 'INKEY',
            'INPUT', 'INSTALL', 'INT', 'LEN',
            'LET', 'LINE', 'LN', 'LOCAL', 'LOG', 'MOD', 'MODE',
            'MOUSE', 'MOVE', 'NEXT', 'NOT', 'ON', 'OPENIN', 'OPENOUT',
            'OPENUP', 'OR', 'ORIGIN', 'OSCLI', 'OTHERWISE', 'PLOT',
            'PRINT', 'PRIVATE', 'RAD', 'READ', 'RECTANGLE',
            'REM', 'REPEAT', 'RESTORE', 'SGN', 'SIN',
            'SOUND', 'SPC', 'SQR', 'STEP', 'SUM',
            'SWAP', 'SYS', 'TAN', 'THEN', 'TINT', 'TO', 'TRACE',
            'UNTIL', 'USR', 'VAL', 'VDU', 'WHEN', 'WHILE', 'WIDTH'
        ),
        // Subroutine declaration
        3 => array(
            'DEF'
        ),
    ), # end of keywords
    'SYMBOLS' => array(
        //'(', ')', '[', ']', '{', '}', // These are already defined by GeSHi as BRACKETS.
        '!', '@', '|', '&', '?', '$', '#', '^', ':', ';', '.', '/', '\\',
        '+', '-', '*', '/', '%', '=', '<', '>', '>=', '>=', '<>', '>>', '<<',
        '&gt;', '&lt;'
    ),
    'REGEXPS' => array(
        // Line numbers
        1 => array(
            GESHI_SEARCH => '((\\n|^)\\s*)([0-9]+)',
            GESHI_REPLACE => '\\3',
            GESHI_MODIFIERS => '',
            GESHI_BEFORE => '\\1',
            GESHI_AFTER => ''
        ),
        // PROC/FN
        2 => array(
            GESHI_SEARCH => '(DEF|\W)(PROC|FN)([a-zA-Z0-9`_@]+)',
            GESHI_REPLACE => '\\2',
            GESHI_MODIFIERS => '',
            GESHI_BEFORE => '\\1',
            GESHI_AFTER => '\\3'
        ),
        // Keywords ending with (
        3 => array(
            GESHI_SEARCH => '(\W)((INSTR|POINT|TAB))(\\()',
            GESHI_REPLACE => '\\2',
            GESHI_MODIFIERS => '',
            GESHI_BEFORE => '\\1',
            GESHI_AFTER => '\\4'
        ),
        // Keywords ending with $(
        4 => array(
            GESHI_SEARCH => '(\W)((LEFT|RIGHT|MID|STRING)\\$)(\\()',
            GESHI_REPLACE => '\\2',
            GESHI_MODIFIERS => '',
            GESHI_BEFORE => '\\1',
            GESHI_AFTER => '\\4'
        ),
        // Keywords ending with $
        5 => array(
            GESHI_SEARCH => '(\W)((CHR|GET|INKEY|STR)\\$)',
            GESHI_REPLACE => '\\2',
            GESHI_MODIFIERS => '',
            GESHI_BEFORE => '\\1',
            GESHI_AFTER => ''
        ),
        // Keywords followed by an identifier
        6 => array(
            GESHI_SEARCH =>
                '(\W)(ABS|ACS|ADVAL|AND|ASC|ASN|ATN|CALL|CASE|CHAIN|'.
                'CIRCLE|COLOR|COLOUR|COS|DATA|DEG|DIM|DIV|DRAW|ELLIPSE|'.
                'ELSE|ENVELOPE|EOR|ERROR|EVAL|EXP|FILL|FOR|GCOL|GET|'.
                'GOSUB|GOTO|IF|INKEY|INPUT|INSTALL'.
                'INT|LEN|LET|LINE|LN|LOCAL|LOG|MOD|MODE|'.
                'MOUSE|MOVE|NEXT|NOT|ON|OPENIN|OPENOUT|OPENUP|OR|ORIGIN|'.
                'OSCLI|OTHERWISE|PLOT|PRINT|PRIVATE|RAD|READ|RECTANGLE|'.
                'REM|REPEAT|RESTORE|SGN|SIN|SOUND|SPC|SQR|STEP|'.
                'SUM|SWAP|SYS|TAN|THEN|TINT|TO|'.
                'TRACE|UNTIL|USR|VAL|VDU|WHEN|WHILE|WIDTH)(\w)',
            GESHI_REPLACE => '\\2',
            GESHI_MODIFIERS => '',
            GESHI_BEFORE => '\\1',
            GESHI_AFTER => '\\3'
        ),
    ),
    // Keywords are case sensitive
    'CASE_SENSITIVE' => array(
        GESHI_COMMENTS => false,
        1 => true,
        2 => true,
        3 => true
        ),
    'STYLES' => array(
        'KEYWORDS' => array(
            1 => 'color: #ff6600;',
            2 => 'color: #ff6600;',
            3 => 'color: #ff0000;'
            ),
        'SYMBOLS' => array(
            0 => 'color: #000000;'
            ),
        'COMMENTS' => array(
            1 => 'color: #009900;',
            2 => 'color: #009900;'
            ),
        'STRINGS' => array(
            'color: #ff00ff;'
            ),
        'REGEXPS' => array(
            1 => 'color: #0000ff;',
            2 => 'color: #ff6600;',
            3 => 'color: #ff6600;',
            4 => 'color: #ff6600;',
            5 => 'color: #ff6600;',
            6 => 'color: #ff6600;'
            ),
        'ESCAPE_CHAR' => array(),
        'BRACKETS' => array(),
        'NUMBERS' => array(),
        'METHODS' => array(),
        'SCRIPT' => array(),
    ),
    'STRICT_MODE_APPLIES' => GESHI_NEVER,
    'OOLANG' => false,
    'OBJECT_SPLITTERS' => array(),
    'URLS' => array(
        1 => '',
        2 => '',
        3 => ''
        ),
    'SCRIPT_DELIMITERS' => array(),
    'HIGHLIGHT_STRICT_BLOCK' => array(),
    'PARSER_CONTROL' => array(
        'COMMENTS' => array(
            'DISALLOWED_BEFORE' => '$'
            ),
        'KEYWORDS' => array(
            1 => array(
                'DISALLOWED_BEFORE' => "(?<![\.\-a-zA-Z0-9_\$\#])",
                'DISALLOWED_AFTER' => "(?![\.\-a-zA-Z0-9_%\\/])"
                ),
            2 => array(
                'DISALLOWED_BEFORE' => "(?<![\.\-a-zA-Z0-9_\$\#])",
                'DISALLOWED_AFTER' => "(?![\.\-a-zA-Z0-9_%\\/])"
                )
            )
        )
    );

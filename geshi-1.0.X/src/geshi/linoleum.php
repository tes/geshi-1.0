<?php
/*************************************************************************************
 * <linoleum.php>
 * --------------
 * Author: Herbert Glarner and Dominick Bellizzi (herbert.glarner@bluewin.ch)
 * Copyright: (c) 2004 Herbert Glarner (http://herbert.wikispaces.com)
 * Release Version: 1.0.0
 * CVS Revision Version: $Revision: 1.16 $
 * Date Started: 2006/06/11
 * Last Modified: $Date: 2006/05/21 00:01:20 $
 *
 * Linoleum language file for GeSHi.
 *
 * CHANGES
 * -------
 * 2006/06/11 (1.0.0)
 *  -  First Release
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
 *
 ************************************************************************************/

$language_data = array (
	'LANG_NAME' => 'Linoleum',
	'COMMENT_MULTI' => array('(' => ')'),
	'COMMENT_SINGLE' => array(),
	'CASE_KEYWORDS' => GESHI_CAPS_NO_CHANGE,
	'QUOTEMARKS' => array('"'),
	'ESCAPE_CHAR' => '',
	'KEYWORDS' => array(
		1 => array(
			'show', 
			'registers', 
			'plus', 'pragma', 
			'ok', 
			'minus', 'multiplied', 'mtp', 
			'leave', 
			'isocall', 
			'failed', 'fail', 
			'end',
			'div', 'divided'
			),
		2 => array(
			"'*", "'/%", "'/", "'%", 
			"!=", "!", 
			"?",
			"+-", "++--", "++", "+", 
			"**", "*", 
			"=&gt;", "=$:", "=,", "=", 
			",=", 
			"&lt;&lt;", "&lt;=", "&lt;----", "&lt;--", "&lt;-", "&lt;@", "&lt;&gt;", "&lt;", 
			"----&gt;", "--&gt;", "-&gt;", "--", "-", 
			"&gt;&gt;", "&gt;", 
			"$+", "$-", "$:=", 
			"~~", "~", 
			"//", "/~", "/%", "/-/", "/", 
			"&#124;&#124;", "&#124;", 
			"&amp;", 
			"&#35;", 
			"@&gt;=", "@&gt;", 
			"%%", "%", 
			"^/", "^"
			),
		3 => array(
			'A', 'B', 'C', 'D', 'E'
			)
		),
	'SYMBOLS' => array(
		'[', ']', '{', '}'
		),
	'CASE_SENSITIVE' => array(
		GESHI_COMMENTS => false,
		1 => false,
		2 => false,
		3 => false,
		),
	'STYLES' => array(
		'KEYWORDS' => array(
			1 => 'color: #0000ff;',
			2 => 'color: #ff0000;',
			3 => 'color: #800080;'
			),
		'COMMENTS' => array(
			1 => '', 2 => '', 
			'MULTI' => 'color: #008000;'
			),
		'ESCAPE_CHAR' => array(
			0 => ''
			),
		'BRACKETS' => array(
			0 => ''
			),
		'STRINGS' => array(
			0 => 'color: #ff0000;'
			),
		'NUMBERS' => array(
			0 => 'color: #800080;'
			),
		'METHODS' => array(
			0 => ''
			),
		'SYMBOLS' => array(
			0 => 'color: #ff0000;'
			),
		'REGEXPS' => array(
			0 => ''
			),
		'SCRIPT' => array(
			0 => '',
			1 => '',
			2 => '',
			3 => ''
			)
		),
	'URLS' => array(
		1 => '',
		2 => '',
		3 => ''
		),
	'OOLANG' => false,
	'OBJECT_SPLITTER' => '',
	'REGEXPS' => array(
		),
	'STRICT_MODE_APPLIES' => GESHI_MAYBE,
	'SCRIPT_DELIMITERS' => array(
		0 => array(
			),
		1 => array(
			),
		2 => array(
			),
		3 => array(
			)
		),
	'HIGHLIGHT_STRICT_BLOCK' => array(
		0 => true,
		1 => true,
		2 => true,
		3 => true
		)
);

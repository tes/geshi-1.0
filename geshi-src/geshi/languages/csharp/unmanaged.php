<?php
/**
 * GeSHi - Generic Syntax Highlighter
 * <pre>
 *   File:   geshi/languages/csharp/unmanaged.php
 *   Author: Knut A. Wikstr�m
 *   E-mail: knut@wikstrom.dk
 * </pre>
 * 
 * For information on how to use GeSHi, please consult the documentation
 * found in the docs/ directory, or online at http://geshi.org/docs/
 * 
 * This program is part of GeSHi.
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 * 
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301 USA
 *
 * @package    geshi
 * @subpackage lang
 * @author     Knut A. Wikstr�m <knut@wikstrom.dk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 2006 Knut A. Wikstr�m
 * @version    $Id$
 *
 */

/**#@+
 * @access private
 */

function geshi_csharp_unmanaged(&$context)
{
	/** Include the common C# stuff, that is here even though it is unmanaged. */
	require_once GESHI_LANGUAGES_ROOT . 'csharp' . GESHI_DIR_SEP . 'common.php';
	geshi_csharp_common($context);
	
	// Object separators
	$context->addObjectSplitter('->', 'oopointer', 'symbol'); // Dynamic (pointer) separator
	$context->addObjectSplitter('::', 'oostatic', 'symbol'); // Static separator
}
?>
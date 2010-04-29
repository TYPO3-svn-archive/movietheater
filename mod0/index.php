<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Markus Martens <m.martens@digitage.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */

$LANG->includeLLFile('EXT:movietheater/mod0/locallang.xml');
require_once(PATH_t3lib . 'class.t3lib_scbase.php');
$BE_USER->modAccess($MCONF,1);	// This checks permissions and exits if the users has no permission for entry.
	// DEFAULT initialization of a module [END]

/**
 * Module 'Seminars' for the 'movietheater' extension.
 *
 * @author	Markus Martens <m.martens@digitage.de>
 * @package	TYPO3
 * @subpackage	tx_movietheater
 */
class  tx_movietheater_module0 extends t3lib_SCbase {
	
	/**
	 * Prints out the module HTML
	 *
	 * @return	void
	 */
	function printContent()	{
		print("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 3.2 Final//EN\">\n");
		print("<html>\n");
		print("<head>\n");
		print("	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n");
		print("	<meta name=\"generator\" content=\"TYPO3 4.2, http://typo3.com, &#169; Kasper Sk&#229;rh&#248;j 1998-2008, extensions are copyright of their respective owners.\" />\n");
		print("	<title>tx_movietheater_module0</title>\n");
		print("	<link rel=\"stylesheet\" type=\"text/css\" href=\"stylesheet.css\" />\n");
		print("	<link rel=\"stylesheet\" type=\"text/css\" href=\"sysext/t3skin/stylesheets/stylesheet_post.css\" />\n");
		print("	<script src=\"tab.js\" type=\"text/javascript\"></script>\n");
		print("</head>\n");
		print("<body onclick=\"if (top.menuReset) top.menuReset();\" id=\"typo3-mod-php\" style='overflow:auto;'>\n");
		print($this->display());
		print("</body>\n");
		print("</html>\n");
	}

	/**
	 * Generates the module content
	 *
	 * @return	(string) content
	 */
	function display()	{
		global $BE_USER,$LANG,$BACK_PATH,$TCA_DESCR,$TCA,$CLIENT,$TYPO3_CONF_VARS;
		//SNIP: $LANG->getLL('title')
		//SNIP: t3lib_div::view_array($_POST)
    return "Hello World?";
	}
	
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/movietheater/mod0/index.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/movietheater/mod0/index.php']);
}

// Make instance:
$SOBE = t3lib_div::makeInstance('tx_movietheater_module0');
$SOBE->init();

// Include files?
foreach($SOBE->include_once as $INC_FILE)	include_once($INC_FILE);

$SOBE->printContent();

?>
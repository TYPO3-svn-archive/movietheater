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
$LANG->includeLLFile('EXT:movietheater/mod2/locallang.xml');
require_once(PATH_t3lib . 'class.t3lib_scbase.php');
require_once('class.view_list.php');
$BE_USER->modAccess($MCONF,1);	// This checks permissions and exits if the users has no permission for entry.

/**
 * Module 'Seminars' for the 'movietheater' extension.
 *
 * @author	Markus Martens <m.martens@digitage.de>
 * @package	TYPO3
 * @subpackage	tx_movietheater
 */
class  tx_movietheater_module2 extends t3lib_SCbase {

	/**
	 * Prints out the module HTML
	 *
	 * @return	void
	 */
	function printContent()	{
    $this->doc = t3lib_div::makeInstance('noDoc');
    $this->doc->backPath = $BACK_PATH;
    $this->doc->form='<form action="" method="post" enctype="multipart/form-data">';
    $this->doc->styleSheetFile='../typo3conf/ext/movietheater/mod2/styles.css';
    $this->doc->JScode = '<script language="javascript" type="text/javascript">script_ended = 0;function jumpToUrl(URL)	{document.location = URL;}</script>';
    $this->doc->JScode = '<script language="javascript">
        script_ended = 0;
        function jumpToUrl(URL){document.location = URL;}
        var T3_RETURN_URL = "'.t3lib_div::getIndpEnv('REQUEST_URI').'";
        var T3_THIS_LOCATION = "'.t3lib_div::getIndpEnv('REQUEST_URI').'";
    </script>';
    $this->doc->postCode='<script language="javascript" type="text/javascript">
      script_ended = 1;
      if (top.fsMod) top.fsMod.recentIds["web"] = 0;
    </script>';
    $view = t3lib_div::makeInstance('view_list');
    $view->doc = $this->doc;
    //print(t3lib_div::view_array($_POST));/*DEBUG*/
    print($this->doc->startPage(get_class(this)));
    print($view->display());
    print($this->doc->endPage());
	}
  
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/movietheater/mod2/index.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/movietheater/mod2/index.php']);
}

// Make instance:
$SOBE = t3lib_div::makeInstance('tx_movietheater_module2');
$SOBE->init();

// Include files?
foreach($SOBE->include_once as $INC_FILE)	include_once($INC_FILE);

$SOBE->printContent();

?>
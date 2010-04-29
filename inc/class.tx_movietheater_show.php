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
require_once(t3lib_extMgm::extPath("movietheater")."inc/class.tx_movietheater_film.php");
require_once(t3lib_extMgm::extPath("movietheater")."inc/class.tx_movietheater_hall.php");

/**
 * Class 'Film' for the 'movietheater' extension.
 *
 * @author	Markus Martens <m.martens@digitage.de>
 * @package	TYPO3
 * @subpackage	tx_movietheater
 */
class tx_movietheater_show{
  private $data = null;
	
	function tx_movietheater_show($uid)	{
		$this->data = array_shift($GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*','tx_movietheater_shows',sprintf('uid = %d',$uid)));// query db
		if(empty($this->data))throw new Exception('couldn\'t find show');// check result
		$this->data['film'] = new tx_movietheater_film(intval($this->film));// get film
		$this->data['hall'] = new tx_movietheater_hall(intval($this->hall));// get hall
		//print('<pre>');var_dump($this);die('</pre>');/*DEBUG*/
	}
  
	function __get($name){return $this->data[$name];}
	
	function getBELabel(){
		return sprintf('%s "%s"',date('d.m.y H:i',$this->date),$this->film->title);
	}
	
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/movietheater/inc/class.tx_movietheater_show.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/movietheater/inc/class.tx_movietheater_show.php']);
}

?>
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
 * Class 'Film' for the 'movietheater' extension.
 *
 * @author	Markus Martens <m.martens@digitage.de>
 * @package	TYPO3
 * @subpackage	tx_movietheater
 */
class tx_movietheater_version{
  private $data = null;
  
	function tx_movietheater_version($uid)	{
		$this->data = array_shift($GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*','tx_movietheater_versions',sprintf('uid = %d',$uid)));// query db
		if(empty($this->data))throw new Exception('couldn\'t find version');// check result
		//print('<pre>');print_r($this);die('</pre>');/*DEBUG*/
	}
  
	function __get($name){if( $name == 'marker' )return $this->marker(); else return $this->data[$name];}
	
	public function marker($prefix=''){
		$result = array();
		foreach( $this->data as $key => $val )$result['###'.$prefix.strtoupper($key).'###'] = $val;
		return $result;
	}
	
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/movietheater/inc/class.tx_movietheater_version.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/movietheater/inc/class.tx_movietheater_version.php']);
}

?>
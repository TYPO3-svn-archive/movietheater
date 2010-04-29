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
require_once(t3lib_extMgm::extPath("movietheater")."inc/class.tx_movietheater_special.php");
require_once(t3lib_extMgm::extPath("movietheater")."inc/class.tx_movietheater_version.php");

/**
 * Class 'Film' for the 'movietheater' extension.
 *
 * @author	Markus Martens <m.martens@digitage.de>
 * @package	TYPO3
 * @subpackage	tx_movietheater
 */
class tx_movietheater_film{
  private $data = null;
  
	/**
	 * Constructor
	 */
	function tx_movietheater_film($uid,$pid=0){
		$where = sprintf('uid = %d',$uid);
		if($pid)$where .= sprintf(' AND pid = %d',$pid);
		$this->data = array_shift($GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*','tx_movietheater_films',$where));// query db
		if(empty($this->data))throw new Exception('couldn\'t find film');// check result
		if($this->special) $this->data['special'] = new tx_movietheater_special(intval($this->special));// get special
		if($this->version) $this->data['version'] = new tx_movietheater_version(intval($this->version));// get version
		//print('<pre>');print_r($this);die('</pre>');/*DEBUG*/
	}
  
	/**
	 * General data access
	 */
	function __get($name){switch($name){case 'marker': return $this->marker(); default: return $this->data[$name];}}

	/**
	 * Return markers and subparts for this film
	 */
	public function marker($prefix=''){
		$result = array();
		foreach( $this->data as $key => $val )if(!is_object($val))$result['###'.$prefix.strtoupper($key).'###'] = $val;// get normal markers
		$result = array_merge($result,$this->data['special']->marker('SPECIAL.'));// get markers for special
		$result['###IMAGES###'] = array();
		foreach( explode(',',$this->data['images']) as $num => $image ){
			$result['###IMAGES###'][$num] = array( '###IMAGE###' => 'uploads/tx_movietheater/'.$image );
		}
		return $result;
	}
	
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/movietheater/inc/class.tx_movietheater_film.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/movietheater/inc/class.tx_movietheater_film.php']);
}

?>
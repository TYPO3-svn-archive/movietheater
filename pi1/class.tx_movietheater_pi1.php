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
require_once(t3lib_extMgm::extPath('movietheater').'mmlib/class.mmlib_pibase.php');
require_once(t3lib_extMgm::extPath("movietheater")."inc/class.tx_movietheater_film.php");


/**
 * Plugin 'Movies' for the 'movietheater' extension.
 *
 * @author	Markus Martens <m.martens@digitage.de>
 * @package	TYPO3
 * @subpackage	tx_movietheater
 */
class tx_movietheater_pi1 extends mmlib_pibase {
	var $prefixId      = 'tx_movietheater_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_movietheater_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'movietheater';	// The extension key.
	var $pi_checkCHash = true;
	
	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content,$conf){try{
	
		$extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey]);
		
		parent::main($content,$conf);
		
		$this->cObj->start(array_merge(
			array_flaten($this->flexform,'ff'),
			array_flaten($this->piVars,'pivar'),
			array_flaten($extConf,'ext'),
			array(
				'now'						=> time(),
				'midnight'			=> strtotime('today 00:00:00'),
				'thursday'			=> strtotime((date('N')==4?'today':'last thursday').' 00:00:00'),
				'monday'				=> strtotime((date('N')==1?'today':'last monday').' 00:00:00'),
			)
		));
		
		//print('<pre>');var_dump($this->cObj->data);die('</pre>');/*DEBUG*/

    return $this->pi_wrapInBaseClass($this->cObj->COBJ_ARRAY($conf));
		
	}catch (Exception $e){
		return $e->getMessage();
	}}
	
	/**
	 * Create a pagebrowser.
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function browser($content,$conf){try{
		$from = strtotime((date('N')==4?'today':'last thursday').' 00:00:00');
		$to = $from + ( 7 * 24 * 60 * 60 );
		for( $i = $from ; $i <= $to ; $i += ( 24 * 60 * 60 ) )$days[] = $i;
		$cObj = new tslib_cObj();
		$cObj->start(array(
			'days' => implode(',',$days)
		));
		return $cObj->cObjGetSingle($conf['renderObj'],$conf['renderObj.']);
	}catch (Exception $e){
		return $e->getMessage();
	}}

}

function array_flaten($data,$prefix=''){
	foreach($data as $k => $v){
		$k = strtr($k,array('.'=>''));// strip dots
		if(is_array($v)){
			$tmp = array_merge($tmp?$tmp:array(),array_flaten($v,$prefix.'.'.$k));
		}else{
			$tmp[$prefix.'.'.$k] = $v;
		}
	}
	return is_array($tmp)?$tmp:array();
}

function print_ts($ts,$prefix=''){
	foreach($ts as $key => $value)if(is_array($value)){
		print_ts($value,$prefix.$key);
	}else{
		print($prefix.$key." = ".$value."\n");
	}
}
function print_marks($data){
	foreach(array_keys($data) as $num => $key)print(sprintf("%s = TEXT\n%s.field = %s\n",strtoupper($key),strtoupper($key),$key));
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/movietheater/pi1/class.tx_movietheater_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/movietheater/pi1/class.tx_movietheater_pi1.php']);
}

?>
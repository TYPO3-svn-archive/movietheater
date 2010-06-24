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
require_once(t3lib_extMgm::extPath("movietheater")."inc/class.tx_movietheater_day.php");
require_once(t3lib_extMgm::extPath("movietheater")."inc/class.tx_movietheater_week.php");
require_once(t3lib_extMgm::extPath("movietheater")."inc/class.tx_movietheater_month.php");

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
		
		parent::main($content,$conf,1);
		
		$this->setRegisters(unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey]));
		
		switch($this->mode){
			case 'singleview':	return $this->singleview();
			case 'dayview': 		return $this->dayview();
			case 'weekview': 		return $this->weekview();
			case 'monthview':		return $this->monthview();
			default: throw new Exception(sprintf('invalid mode "%s"',$this->mode));
		}
		
	}catch (Exception $e){
		return sprintf("<pre>%s</pre>%s",$e,$GLOBALS['TYPO3_DB']->debug_lastBuiltQuery);
	}}
	
	private function singleview(){
		$uid = intval($this->piVars['film']);
		if($this->film) $uid = $this->film;// prefer typoscript & flexform
		if(!$uid) return "";
		$film = new tx_movietheater_film(tx_movietheater_film::query($uid));
		$this->cObj->start($film->data);
		$content  = $this->pi_wrapInBaseClass($this->cObj->cObjGetSingle($this->conf['singleview'],$this->conf['singleview.']));
		//$content .= "<hr />\n".t3lib_div::view_array($this->cObj->data);//DEBUG
		//$content .= "<hr />\n".t3lib_div::view_array($this->conf);//DEBUG
		//$content .= "<hr />\n".t3lib_div::view_array($this->piVars);//DEBUG
		return $content;
	}
	
	private function dayview(){
		$timestamp = intval($this->piVars['day']);
		if($this->day) $timestamp = $this->day;// prefer typoscript & flexform
		if(!$timestamp) $timestamp = time();// default to now
		$day = new tx_movietheater_day($timestamp);
		$this->cObj->start($day->fields);
		$content  = $this->pi_wrapInBaseClass($this->cObj->cObjGetSingle($this->conf['dayview'],$this->conf['dayview.']));
		//$content .= "<hr />\nDATA:".t3lib_div::view_array($this->cObj->data);//DEBUG
		//$content .= "<hr />\n".$this->conf['dayview'].":".t3lib_div::view_array($this->conf['dayview.']);//DEBUG
		//$content .= "<hr />\nPIVARS:".t3lib_div::view_array($this->piVars);//DEBUG
		return $content;
	}
	
	private function weekview(){
		$timestamp = intval($this->piVars['week']);
		if($this->week) $timestamp = $this->week;// prefer typoscript & flexform
		if(!$timestamp) $timestamp = time();// default to now
		$week = new tx_movietheater_week($timestamp);
		$this->cObj->start($week->fields);
		$content  = $this->pi_wrapInBaseClass($this->cObj->cObjGetSingle($this->conf['weekview'],$this->conf['weekview.']));
		return $content;
	}
	
	private function monthview(){
		$timestamp = intval($this->piVars['month']);
		if($this->month) $timestamp = $this->month;// prefer typoscript & flexform
		if(!$timestamp) $timestamp = time();// default to now
		$month = new tx_movietheater_month($timestamp);
		$this->cObj->start($month->fields);
		$content  = $this->pi_wrapInBaseClass($this->cObj->cObjGetSingle($this->conf['monthview'],$this->conf['monthview.']));
		return $content;
	}
	
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/movietheater/pi1/class.tx_movietheater_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/movietheater/pi1/class.tx_movietheater_pi1.php']);
}

?>
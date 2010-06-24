<?php //require_once(t3lib_extMgm::extPath("movietheater")."inc/class.tx_movietheater_week.php");
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
class tx_movietheater_week{

  public $timestamp = null;// stores shows for this day
  
	function __construct($timestamp)	{
		$this->timestamp = $timestamp;
	}
  
	function __get($name){switch($name){
    case 'fields': return self::fields($this->timestamp);
		default: return null;
	}}
  
  /* return fields for use in typoscript */
  public static function fields($timestamp){
    return array(
      'tstamp'=> $timestamp,
      'begin' => self::beginning($timestamp),
      'end'   => self::ending($timestamp),
      'prev'  => self::prev($timestamp),
      'next'  => self::next($timestamp),
      'week'  => $timestamp+(60*60*24*7)
    );
  }
  
  /* gibt für einen zeitstempel alle vorstellungen der woche zurück */
	public static function query($timestamp){
    $where = sprintf('date BETWEEN %d AND %d',self::beginning($timestamp),self::next($timestamp));
		return $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*','tx_movietheater_shows',$where);
	}
  
  /* gibt für einen zeitstempel den ersten tag der kinowoche zurück */
  public static function beginning($timestamp,$day=THU){
    switch($day){
      case MON: return strtotime('LAST SUNDAY    +1 DAY',$timestamp);
      case TUE: return strtotime('LAST MONDAY    +1 DAY',$timestamp);
      case WED: return strtotime('LAST TUESDAY   +1 DAY',$timestamp);
      case THU: return strtotime('LAST WEDNESDAY +1 DAY',$timestamp);
      case FRI: return strtotime('LAST THURSDAY  +1 DAY',$timestamp);
      case SAT: return strtotime('LAST FRIDAY    +1 DAY',$timestamp);
      case SUN: return strtotime('LAST SATURDAY  +1 DAY',$timestamp);
      default: return null;
    }
  }
  
  public static function ending($timestamp,$day=THU){
    return self::next($timestamp,$day)-1;
  }
  
  public static function prev($timestamp,$day=THU){
    return self::beginning($timestamp,$day)-(60*60*24*7);
  }
  
  public static function next($timestamp,$day=THU){
    return self::beginning($timestamp,$day)+(60*60*24*7);
  }
  
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/movietheater/inc/class.tx_movietheater_week.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/movietheater/inc/class.tx_movietheater_week.php']);
}

?>
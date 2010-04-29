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
 * @author	Markus Martens <m.martens@digitage.de>
 * @package	TYPO3
 * @subpackage	tx_movietheater
 */
class  view_list{

  public $doc = null;
  private $halls = null;
  private $store = 0;
  private $row = 0;
  
	/**
	 * Generates the module content
	 *
	 * @return	(string) content
	 */
	function display()	{
		global $BE_USER,$LANG,$BACK_PATH,$TCA_DESCR,$TCA,$CLIENT,$TYPO3_CONF_VARS;
		//SNIP: $LANG->getLL('title')
		//SNIP: t3lib_div::view_array($_POST)
		$extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['movietheater']);
    $this->store = intval($extConf['store']);// set storage folder
		$this->halls = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*','tx_movietheater_halls','deleted = 0 AND hidden = 0 AND pid = '.$this->store,'','','','uid');
		$shows = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*','tx_movietheater_shows','deleted = 0 AND pid = '.$this->store,'','date ASC','','uid');
    $start = reset($shows);
    $start = $start['date']?$start['date']:time();
    $days = ceil((time()-$start)/60/60/24);
    
    // create base show-array from first show until now + 14 days
    for( $i = $start ; $i < strtotime("+2 week") ; $i += (60*60*24) ) $tmp[date('y:W',$i)][strtotime('00:00:00',$i)] = array('');
    // fill in actual shows
		foreach($shows as $num => $item){
			$tmp[date('y:W',$item['date'])][strtotime('00:00:00',$item['date'])][$item['hall']][date('H:i',$item['date'])] = $item;//PHP 5.1.0
		}
    
    //print(t3lib_div::view_array($tmp));die();
		return $this->viewKW($tmp);
	}
	
  private function viewKW($data){
    foreach($data as $key => $val){
      list($year,$kw) = explode(':',$key);
      $sub = $this->viewDAY($val);
      $tmp .= sprintf("<tr><th class='col1'>KW%02d 20%02d</th></tr>\n<tr><td>%s</td></tr>",$kw,$year,$sub);
    }
    return sprintf("<table class='kw' cellpadding='0' cellspacing='0' border='0'>%s</table>",$tmp);
  }
  
  private function viewDAY($data){
    $days = array('XX','MO','DI','MI','DO','FR','SA','SO');
    foreach($data as $key => $val){
      $sub = $this->viewHALL($val,$key);
      if( $key < strtotime('00:00:00') ) $css = ' old';
      elseif( $key == strtotime('00:00:00') ) $css = ' today';
      else $css = ' future';
      $tmp .= sprintf("<tr><td class='col1".$css."'>%s</td><td class='col2".$css."'>%s</td></tr>",date('D d.m.',$key),$sub);
    }
    return sprintf("<table class='day' cellpadding='0' cellspacing='0' border='0'>%s</table>",$tmp);
  }
  
  private function viewHALL($data,$date){
    global $LANG;
    foreach($this->halls as $key => $val){
      $sub = $data[$key]?$this->viewTIME($data[$key]):'-';
      //$ctrl = "<input type='image' name='action' value='N:".$date."-".$key."' src='sysext/t3skin/icons/gfx/new_page.gif'/>";
      $params = "&edit[tx_movietheater_shows][".$this->store."]=new&preset[date]=".strtotime('12:00:00',$date)."&preset[hall]=".$key;
      $ctrl = $this->button($this->alt_doc($params),'new_page',$LANG->getLL('CTRL.NEW'));
      $tmp .= sprintf("<tr class='%s'><td class='col1'>%s%s</td><td>%s</td></tr>",(($this->row%2)?'odd':'even'),$val['name'],$ctrl,$sub);
      $this->row++;
    }
    return sprintf("<table class='hall' cellpadding='0' cellspacing='0' border='0'>%s</table>",$tmp);
  }
  
  private function viewTIME($data){
    foreach($data as $key => $val){
      $ctrl = $this->getCTRL($val);
      $sub = $this->viewFILM($val);
      $tmp .= sprintf("<tr><td class='col1'>%s</td><td class='col2'>%s</td><td class='film'>%s</td></tr>",$key,$ctrl,$sub);
    }
    return sprintf("<table class='time' cellpadding='0' cellspacing='0' border='0'>%s</table>",$tmp);
  }
  
  private function viewFILM($data){
		$film = array_shift($GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*','tx_movietheater_films','uid='.$data['film']));
    //return sprintf("[%05d]%s&nbsp;[%05d]&nbsp;%s",$data['uid'],$ctrl,$film['uid'],$film['title']);/*DEBUG*/
    return sprintf("%s&nbsp;&nbsp;%s",$ctrl,$film['title']);
  }

  private function getCTRL($data){
    $ctrl = $this->getLinks('tx_movietheater_shows',$data);
    $ctrl = array_intersect_key($ctrl,array_flip(array('edit','hidden','delete')));
    return "<div class=\"typo3-DBctrl\">\n".implode("\n",$ctrl)."</div>\n";
  }
  
  private function getLinks($table,$row){
		global $LANG;
    $cells['info']='<a href="#" onclick="top.launchView(\''.$table.'\',\''.$row['uid'].'\',\''.$GLOBALS['BACK_PATH'].'\'); return false;">'.$this->icon('zoom2','Informationen anzeigen').'</a>';
    // Edit:
    $params='&edit['.$table.']['.$row['uid'].']=edit';
    $cells['edit']='<a href="#" onclick="'.htmlspecialchars(t3lib_BEfunc::editOnClick($params,$GLOBALS['BACK_PATH'],t3lib_div::getIndpEnv('REQUEST_URI'))).'">'.$this->icon('edit2',$LANG->getLL('CTRL.EDIT')).'</a>';
    // Hide:
    if ($row['hidden'])	{
      $params='&amp;data['.$table.']['.$row['uid'].'][hidden]=0';
      $cells['hidden']=$this->button($this->jumpToUrl($params),'button_unhide',$LANG->getLL('CTRL.SHOW'));
    } else {
      $params='&amp;data['.$table.']['.$row['uid'].'][hidden]=1';
      $cells['hidden']=$this->button($this->jumpToUrl($params),'button_hide',$LANG->getLL('CTRL.HIDE'));
    }
    // Delete
    $params='&amp;cmd['.$table.']['.$row['uid'].'][delete]=1';
    $cells['delete']=$this->button($this->jumpToUrl($params,$LANG->getLL('CTRL.CHECK')),'garbage',$LANG->getLL('CTRL.DELETE'));
    // return  
    return $cells;
  }
  
  private function button($onclick,$icon,$title){
    return "<a href=\"#\" onclick=\"".$onclick."\">".$this->icon($icon,$title)."</a>";
  }
  
  private function jumpToUrl($params,$msg=''){
    if(empty($msg))
      return "return jumpToUrl('".$this->doc->issueCommand($params)."');";
    else
      return "if(confirm(unescape('".rawurlencode($msg)."'))){".$this->jumpToUrl($params)."}else{return false;}";
  }
  
  private function alt_doc($params){
    //$params = "&edit[tx_movietheater_shows][23]=new"
    return "window.location.href='alt_doc.php?returnUrl='+T3_THIS_LOCATION+'".$params."';return false;";
  }

  private function editOnClick($params){
    return htmlspecialchars(t3lib_BEfunc::editOnClick($params,$GLOBALS['BACK_PATH'],t3lib_div::getIndpEnv('REQUEST_URI')));
  }
  
  private function icon($name,$title){
    return '<img'.t3lib_iconWorks::skinImg($GLOBALS['BACK_PATH'],'gfx/'.$name.'.gif','width="11" height="12"').' border="0" align="top" alt="X" title="'.$title.'" />';
  }
  
}
?>
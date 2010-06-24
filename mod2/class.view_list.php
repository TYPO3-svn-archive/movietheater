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
require_once(t3lib_extMgm::extPath("movietheater")."inc/class.tx_movietheater_day.php");
require_once(t3lib_extMgm::extPath("movietheater")."inc/class.tx_movietheater_week.php");

/* ISO-8601 */
define('MON',1);
define('TUE',2);
define('WED',3);
define('THU',4);
define('FRI',5);
define('SAT',6);
define('SUN',7);

/**
 * @author	Markus Martens <m.martens@digitage.de>
 * @package	TYPO3
 * @subpackage	tx_movietheater
 */
class  view_list{

  public $doc = null;
  public $clipboard = null;
  private $halls = null;
  private $extConf = null;
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
		$this->extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['movietheater']);
    print("\n<!--\nextConf = ");var_dump($this->extConf);print("-->\n");//DEBUG
    
    if(!empty($_POST['CLEAR'])){// remove old shows
      $where = sprintf('( date BETWEEN %d AND %d ) AND ( pid = %d )',$_POST['CLEAR'],$_POST['CLEAR']+(60*60*24*7),$this->extConf['storage']);
      $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_movietheater_shows',$where,array( 'deleted' => 1 ));
    }
    
		$this->halls = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*','tx_movietheater_halls','deleted = 0 AND hidden = 0 AND pid = '.$this->extConf['storage'],'','','','uid');
		$shows = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*','tx_movietheater_shows','deleted = 0 AND pid = '.$this->extConf['storage'],'','date ASC','','uid');
    
    $start = reset($shows);
    $start = $start['date'];
    if(!$start)$start = time();// if no shows start today
    $start = tx_movietheater_week::beginning(min(strtotime('00:00:00',$start),time()));
    $stop  = tx_movietheater_week::beginning(time()) + (($this->extConf['preview']+1)*60*60*24*7);
    //$days = ceil((time()-$start)/60/60/24);
    
    // create base show-array from first show until now + 14 days
    for( $i = $start ; $i < $stop ; $i += (60*60*24) ){
      $tmp[view_list::kinowoche($i)][strtotime('00:00:00',$i)] = array();
    }
    
    // fill in actual shows
		foreach($shows as $num => $item){
      $kw = view_list::kinowoche($item['date']);
      $tag = tx_movietheater_day::beginning($item['date']);
			$tmp[$kw][$tag][$item['hall']][date('H:i',$item['date'])] = $item;//PHP 5.1.0
		}/**/
    
    //print(t3lib_div::view_array($tmp));die();
		return $toolbar.$this->viewKW($tmp);
	}
	
  /* gibt für einen zeitstempel die kinowoche im format [JJJJ:WW] zurück */
  public static function kinowoche($timestamp,$day=THU){
    return date('Y:W',tx_movietheater_week::beginning($timestamp,$day));
  }
  
  private function viewKW($data){
    foreach($data as $key => $val){
      list($year,$kw) = explode(':',$key);
      $sub = $this->viewDAY($val);
      $tmp .= "<tr><th class='col1'>";
      $tmp .= "<a onclick=\"previewWin=window.open(top.WorkspaceFrontendPreviewEnabled?'../index.php?id=".$this->extConf['weekview']."&tx_movietheater_pi1[week]=".$key."&no_cache=1':'../typo3/mod/user/ws/wsol_preview.php?id=22','newTYPO3frontendWindow');previewWin.focus();\" href=\"#\">Kino Woche ".$kw." - ".$year."</a>";
      $tmp .= "<input type='image' src='sysext/t3skin/icons/gfx/garbage.gif' name='CLEAR' value='".tx_movietheater_week::beginning(key($val))."' title='KW ".$kw." löschen' />";
      $tmp .= "</th></tr>\n";
      $tmp .= "<tr><td>".$sub."</td></tr>\n";
    }
    return "<table class='kw' cellpadding='0' cellspacing='0' border='0'>".$tmp."</table>\n";
  }
  
  private function viewDAY($data){
    global $LANG;
    $days = array('XX','MO','DI','MI','DO','FR','SA','SO');
    foreach($data as $key => $val){
      $sub = $this->viewHALL($val,$key);
      if( $key < strtotime('00:00:00') ) $css = ' old';
      elseif( $key == strtotime('00:00:00') ) $css = ' today';
      else $css = ' future';
      $label = date('D d.m.',$key);
      $params = '&tx_movietheater_pi1[day]='.$key.'&no_cache=1';
      $label = '<a href="#" title='.$LANG->getLL('CTRL.VIEW').' onclick="'.htmlspecialchars(t3lib_BEfunc::viewOnClick($this->extConf['dayview'],$GLOBALS['BACK_PATH'],'','','',$params)).'">'.$label.'</a>';
      $tmp .= sprintf("<tr><td class='col1".$css."'>%s</td><td class='col2'>%s</td></tr>",$label,$sub);
    }
    return sprintf("<table class='day' cellpadding='0' cellspacing='0' border='0'>%s</table>",$tmp);
  }
  
  private function viewHALL($data,$date){
    global $LANG;
    foreach($this->halls as $key => $val){
      $sub = $data[$key]?$this->viewTIME($data[$key]):'-';
      $params = "&edit[tx_movietheater_shows][".$this->extConf['storage']."]=new&preset[date]=".strtotime('12:00:00',$date)."&preset[hall]=".$key;
      $ctrl  = '';
      //$ctrl .= '<a href="#" onclick="return false;">'.$this->icon('clip_pasteinto','Einfügen am '.date('d.m.Y',$date).' in '.$val['name']).'</a>';
      $ctrl .= $this->button($this->alt_doc($params),'new_page',$LANG->getLL('CTRL.NEW'));
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
    global $LANG;
		$film = array_shift($GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*','tx_movietheater_films','uid='.$data['film']));
    $label = $film['title'];
    $params = '&tx_movietheater_pi1[film]='.$film['uid'].'&no_cache=1';
    $label = '<a href="#" title='.$LANG->getLL('CTRL.VIEW').' onclick="'.htmlspecialchars(t3lib_BEfunc::viewOnClick($this->extConf['singleview'],$GLOBALS['BACK_PATH'],'','','',$params)).'">'.$label.'</a>';
    return $label;
  }

  private function getCTRL($data){
    $ctrl = $this->getLinks('tx_movietheater_shows',$data);
    return "<div class=\"typo3-DBctrl\">\n".implode("\n",$ctrl)."</div>\n";
  }
  
  private function getLinks($table,$row){
		global $LANG;
    
    // INFO
    //$cells['info']='<a href="#" onclick="top.launchView(\''.$table.'\',\''.$row['uid'].'\',\''.$GLOBALS['BACK_PATH'].'\'); return false;">'.$this->icon('zoom2','Informationen anzeigen').'</a>';
    
    // EDIT:
    $params='&edit['.$table.']['.$row['uid'].']=edit';
    $cells['edit']='<a href="#" onclick="'.htmlspecialchars(t3lib_BEfunc::editOnClick($params,$GLOBALS['BACK_PATH'],t3lib_div::getIndpEnv('REQUEST_URI'))).'">'.$this->icon('edit2',$LANG->getLL('CTRL.EDIT')).'</a>';
    
    // HIDE:
    if ($row['hidden'])	{
      $params='&amp;data['.$table.']['.$row['uid'].'][hidden]=0';
      $cells['hidden']=$this->button($this->jumpToUrl($params),'button_unhide',$LANG->getLL('CTRL.SHOW'));
    } else {
      $params='&amp;data['.$table.']['.$row['uid'].'][hidden]=1';
      $cells['hidden']=$this->button($this->jumpToUrl($params),'button_hide',$LANG->getLL('CTRL.HIDE'));
    }
    
    // CUT
    $params='&amp;CB[el]['.$table.'%7C'.$row['uid'].']=1';
    $cells['cut']=$this->button($this->jumpToUrl($params),'clip_cut',$LANG->getLL('CTRL.CUT'));
    
    // COPY
    $params='&amp;cmd['.$table.']['.$row['uid'].'][setCB]=1&amp;CB[el]['.$table.'%7C'.$row['uid'].']=1&amp;CB[setCopyMode]=1';
    $cells['copy']=$this->button($this->jumpToUrl($params),'clip_copy',$LANG->getLL('CTRL.COPY'));
    
    // DELETE
    $params='&amp;cmd['.$table.']['.$row['uid'].'][delete]=1';
    $cells['delete']=$this->button($this->jumpToUrl($params,$LANG->getLL('CTRL.CHECK')),'garbage',$LANG->getLL('CTRL.DELETE'));
    
    // VIEW
    //$params = '&tx_movietheater_pi1[film]='.$row['film'].'&no_cache=1';
    //$cells['view']='<a href="#" onclick="'.htmlspecialchars(t3lib_BEfunc::viewOnClick($this->extConf['singleview'],$GLOBALS['BACK_PATH'],'','','',$params)).'">'.$this->icon('zoom',$LANG->getLL('CTRL.VIEW')).'</a>';
    
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
<?php
require_once(t3lib_extMgm::extPath("movietheater")."inc/class.tx_movietheater_show.php");

/* append this to your tca.php *****************************************************************************************

if( (TYPO3_MODE=="BE") && (t3lib_div::int_from_ver(TYPO3_version) >= 4001000) ){
  require_once(t3lib_extMgm::extPath('movietheater').'inc/class.tx_movietheater_labels.php');
  $TCA['tx_movietheater_shows']['ctrl']['label_userFunc'] = "tx_movietheater_labels->getLabelShow";
}

***********************************************************************************************************************/

class tx_movietheater_labels{

  function tx_movietheater_labels(){
    return null;
  }
  
  /* LISTVIEW */
  
  function getLabelShow(&$params, &$pObj){try{
    if(intval($params['row']['uid'])){
      $show = new tx_movietheater_show(tx_movietheater_show::query(intval($params['row']['uid'])));
      $params['title'] = $show->getBELabel();
    }
  }catch (Exception $e){
		$params['title'] = $e->getMessage();
	}}  
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/movietheater/inc/class.tx_movietheater_labels.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/movietheater/inc/class.tx_movietheater_labels.php']);
}
?>
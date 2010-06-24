<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');
$TCA["tx_movietheater_films"] = array (
	"ctrl" => array (
		'title'     => 'LLL:EXT:movietheater/locallang.xml:tx_movietheater_films',		
		'label'     => 'title',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => "ORDER BY title",	
		'delete' => 'deleted',	
		'enablecolumns' => array (		
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'res/gfx/icon_tx_movietheater_films.gif',
	),
	"feInterface" => array (
		"fe_admin_fieldList" => "hidden, title, originaltitle, special, credits, teaser, description, images, imdb, year, version, country",
	)
);

$TCA["tx_movietheater_specials"] = array (
	"ctrl" => array (
		'title'     => 'LLL:EXT:movietheater/locallang.xml:tx_movietheater_specials',		
		'label'     => 'title',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => "ORDER BY crdate",	
		'delete' => 'deleted',	
		'enablecolumns' => array (		
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'res/gfx/icon_tx_movietheater_specials.gif',
	),
	"feInterface" => array (
		"fe_admin_fieldList" => "hidden, title, image",
	)
);

$TCA["tx_movietheater_versions"] = array (
	"ctrl" => array (
		'title'     => 'LLL:EXT:movietheater/locallang.xml:tx_movietheater_versions',		
		'label'     => 'name',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => "ORDER BY crdate",	
		'delete' => 'deleted',	
		'enablecolumns' => array (		
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'res/gfx/icon_tx_movietheater_versions.gif',
	),
	"feInterface" => array (
		"fe_admin_fieldList" => "hidden, name, shortform",
	)
);

$TCA["tx_movietheater_shows"] = array (
	"ctrl" => array (
		'title'     => 'LLL:EXT:movietheater/locallang.xml:tx_movietheater_shows',		
		'label'     => 'date',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => "ORDER BY crdate",	
		'delete' => 'deleted',	
		'enablecolumns' => array (		
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'res/gfx/icon_tx_movietheater_shows.gif',
	),
	"feInterface" => array (
		"fe_admin_fieldList" => "hidden, hall, film, date",
	)
);

$TCA["tx_movietheater_halls"] = array (
	"ctrl" => array (
		'title'     => 'LLL:EXT:movietheater/locallang.xml:tx_movietheater_halls',		
		'label'     => 'name',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => "ORDER BY crdate",	
		'delete' => 'deleted',	
		'enablecolumns' => array (		
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'res/gfx/icon_tx_movietheater_halls.gif',
	),
	"feInterface" => array (
		"fe_admin_fieldList" => "hidden, name, seats",
	)
);

t3lib_div::loadTCA('tt_content');

/* PI1 */
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1']='layout,select_key,pages';// hide ?,?,startingpoint
$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi1']='pi_flexform';// add flexform to be renderd when your plugin is shown
t3lib_extMgm::addPlugin(array('LLL:EXT:movietheater/locallang.xml:tt_content.list_type_pi1', $_EXTKEY.'_pi1'),'list_type');
t3lib_extMgm::addStaticFile($_EXTKEY,'pi1/static/','Movies');
t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_pi1','FILE:EXT:movietheater/pi1/flexform.xml');// add flexform description file
if (TYPO3_MODE=="BE")	$TBE_MODULES_EXT["xMOD_db_new_content_el"]["addElClasses"]["tx_movietheater_pi1_wizicon"] = t3lib_extMgm::extPath($_EXTKEY).'pi1/class.tx_movietheater_pi1_wizicon.php';

/* MOD1 */
if (TYPO3_MODE == 'BE'){
	$extPath = t3lib_extMgm::extPath($_EXTKEY);
	t3lib_extMgm::addModule('txmovietheaterM0','','',$extPath.'mod0/');
	//t3lib_extMgm::addModule('txmovietheaterM0','txmovietheaterM1','',$extPath.'mod1/');
	t3lib_extMgm::addModule('txmovietheaterM0','txmovietheaterM2','',$extPath.'mod2/');
}

?>
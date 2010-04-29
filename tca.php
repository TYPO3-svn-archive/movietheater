<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA["tx_movietheater_films"] = array (
	"ctrl" => $TCA["tx_movietheater_films"]["ctrl"],
	"interface" => array (
		"showRecordFieldList" => "hidden,title,originaltitle,special,credits,teaser,description,images,imdb,year,version,country"
	),
	"feInterface" => $TCA["tx_movietheater_films"]["feInterface"],
	"columns" => array (
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		"title" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:movietheater/locallang.xml:tx_movietheater_films.title",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"eval" => "required,trim",
			)
		),
		"originaltitle" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:movietheater/locallang.xml:tx_movietheater_films.originaltitle",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"eval" => "trim",
			)
		),
		"special" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:movietheater/locallang.xml:tx_movietheater_films.special",		
			"config" => Array (
				"type" => "select",	
        "items" => Array(
          Array("",0),
        ),
				"foreign_table" => "tx_movietheater_specials",	
				"foreign_table_where" => "AND tx_movietheater_specials.pid=###CURRENT_PID### ORDER BY tx_movietheater_specials.uid",	
				"size" => 1,	
				"minitems" => 0,
				"maxitems" => 1,	
				"wizards" => Array(
					"_PADDING" => 2,
					"_VERTICAL" => 1,
					"add" => Array(
						"type" => "script",
						"title" => "Create new record",
						"icon" => "add.gif",
						"params" => Array(
							"table"=>"tx_movietheater_specials",
							"pid" => "###CURRENT_PID###",
							"setValue" => "prepend"
						),
						"script" => "wizard_add.php",
					),
				),
			)
		),
		"credits" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:movietheater/locallang.xml:tx_movietheater_films.credits",		
			"config" => Array (
				"type" => "text",
				"cols" => "30",	
				"rows" => "5",
			)
		),
		"teaser" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:movietheater/locallang.xml:tx_movietheater_films.teaser",		
			"config" => Array (
				"type" => "text",
				"cols" => "30",
				"rows" => "5",
				"wizards" => Array(
					"_PADDING" => 2,
					"RTE" => array(
						"notNewRecords" => 1,
						"RTEonly" => 1,
						"type" => "script",
						"title" => "Full screen Rich Text Editing|Formatteret redigering i hele vinduet",
						"icon" => "wizard_rte2.gif",
						"script" => "wizard_rte.php",
					),
				),
			)
		),
		"description" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:movietheater/locallang.xml:tx_movietheater_films.description",		
			"config" => Array (
				"type" => "text",
				"cols" => "30",
				"rows" => "5",
				"wizards" => Array(
					"_PADDING" => 2,
					"RTE" => array(
						"notNewRecords" => 1,
						"RTEonly" => 1,
						"type" => "script",
						"title" => "Full screen Rich Text Editing|Formatteret redigering i hele vinduet",
						"icon" => "wizard_rte2.gif",
						"script" => "wizard_rte.php",
					),
				),
			)
		),
		"images" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:movietheater/locallang.xml:tx_movietheater_films.images",		
			"config" => Array (
				"type" => "group",
				"internal_type" => "file",
				"allowed" => "gif,png,jpeg,jpg",	
				"max_size" => 1000,	
				"uploadfolder" => "uploads/tx_movietheater",
				"show_thumbs" => 1,	
				"size" => 5,	
				"minitems" => 0,
				"maxitems" => 5,
			)
		),
		"imdb" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:movietheater/locallang.xml:tx_movietheater_films.imdb",		
			"config" => Array (
				"type"     => "input",
				"size"     => "15",
				"max"      => "255",
				"checkbox" => "",
				"eval"     => "trim",
				"wizards"  => array(
					"_PADDING" => 2,
					"link"     => array(
						"type"         => "popup",
						"title"        => "Link",
						"icon"         => "link_popup.gif",
						"script"       => "browse_links.php?mode=wizard",
						"JSopenParams" => "height=300,width=500,status=0,menubar=0,scrollbars=1"
					)
				)
			)
		),
		"year" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:movietheater/locallang.xml:tx_movietheater_films.year",		
			"config" => Array (
				"type" => "input",	
				"size" => "5",	
				"max" => "4",	
				"eval" => "year,nospace",
			)
		),
		"version" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:movietheater/locallang.xml:tx_movietheater_films.version",		
			"config" => Array (
				"type" => "select",	
        "items" => Array(
          Array("",0),
        ),
				"foreign_table" => "tx_movietheater_versions",	
				"foreign_table_where" => "AND tx_movietheater_versions.pid=###CURRENT_PID### ORDER BY tx_movietheater_versions.uid",	
				"size" => 1,	
				"minitems" => 0,
				"maxitems" => 1,	
				"wizards" => Array(
					"_PADDING" => 2,
					"_VERTICAL" => 1,
					"add" => Array(
						"type" => "script",
						"title" => "Create new record",
						"icon" => "add.gif",
						"params" => Array(
							"table"=>"tx_movietheater_versions",
							"pid" => "###CURRENT_PID###",
							"setValue" => "prepend"
						),
						"script" => "wizard_add.php",
					),
				),
			)
		),
		"country" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:movietheater/locallang.xml:tx_movietheater_films.country",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
	),
	"types" => array (
		"0" => array("showitem" => "hidden;;1;;1-1-1, title;;;;2-2-2, originaltitle;;;;3-3-3, special, credits, teaser;;;richtext[cut|copy|paste|formatblock|textcolor|bold|italic|underline|left|center|right|orderedlist|unorderedlist|outdent|indent|link|table|image|line|chMode]:rte_transform[mode=ts_css|imgpath=uploads/tx_movietheater/rte/], description;;;richtext[cut|copy|paste|formatblock|textcolor|bold|italic|underline|left|center|right|orderedlist|unorderedlist|outdent|indent|link|table|image|line|chMode]:rte_transform[mode=ts_css|imgpath=uploads/tx_movietheater/rte/], images, imdb, year, version, country")
	),
	"palettes" => array (
		"1" => array("showitem" => "")
	)
);



$TCA["tx_movietheater_specials"] = array (
	"ctrl" => $TCA["tx_movietheater_specials"]["ctrl"],
	"interface" => array (
		"showRecordFieldList" => "hidden,title,image"
	),
	"feInterface" => $TCA["tx_movietheater_specials"]["feInterface"],
	"columns" => array (
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		"title" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:movietheater/locallang.xml:tx_movietheater_specials.title",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"eval" => "required",
			)
		),
		"image" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:movietheater/locallang.xml:tx_movietheater_specials.image",		
			"config" => Array (
				"type" => "group",
				"internal_type" => "file",
				"allowed" => $GLOBALS["TYPO3_CONF_VARS"]["GFX"]["imagefile_ext"],	
				"max_size" => 500,	
				"uploadfolder" => "uploads/tx_movietheater",
				"show_thumbs" => 1,	
				"size" => 1,	
				"minitems" => 0,
				"maxitems" => 1,
			)
		),
	),
	"types" => array (
		"0" => array("showitem" => "hidden;;1;;1-1-1, title, image")
	),
	"palettes" => array (
		"1" => array("showitem" => "")
	)
);



$TCA["tx_movietheater_versions"] = array (
	"ctrl" => $TCA["tx_movietheater_versions"]["ctrl"],
	"interface" => array (
		"showRecordFieldList" => "hidden,name,shortform"
	),
	"feInterface" => $TCA["tx_movietheater_versions"]["feInterface"],
	"columns" => array (
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		"name" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:movietheater/locallang.xml:tx_movietheater_versions.name",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"eval" => "required",
			)
		),
		"shortform" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:movietheater/locallang.xml:tx_movietheater_versions.shortform",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
	),
	"types" => array (
		"0" => array("showitem" => "hidden;;1;;1-1-1, name, shortform")
	),
	"palettes" => array (
		"1" => array("showitem" => "")
	)
);



$TCA["tx_movietheater_shows"] = array (
	"ctrl" => $TCA["tx_movietheater_shows"]["ctrl"],
	"interface" => array (
		"showRecordFieldList" => "hidden,hall,film,date"
	),
	"feInterface" => $TCA["tx_movietheater_shows"]["feInterface"],
	"columns" => array (
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		"hall" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:movietheater/locallang.xml:tx_movietheater_shows.hall",		
			"config" => Array (
				"type" => "select",	
				"foreign_table" => "tx_movietheater_halls",	
				"foreign_table_where" => "AND tx_movietheater_halls.pid=###CURRENT_PID### ORDER BY tx_movietheater_halls.uid",	
				"size" => 1,	
				"minitems" => 0,
				"maxitems" => 1,	
				"default"  => $_GET['preset']['hall']?$_GET['preset']['hall']:'',
				"wizards" => Array(
					"_PADDING" => 2,
					"_VERTICAL" => 1,
					"add" => Array(
						"type" => "script",
						"title" => "Create new record",
						"icon" => "add.gif",
						"params" => Array(
							"table"=>"tx_movietheater_halls",
							"pid" => "###CURRENT_PID###",
							"setValue" => "prepend"
						),
						"script" => "wizard_add.php",
					),
				),
			)
		),
		"film" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:movietheater/locallang.xml:tx_movietheater_shows.film",		
			"config" => Array (
        /*
				"type" => "group",	
				"internal_type" => "db",	
				"allowed" => "tx_movietheater_films",
        */
        "type" => "select",
        "foreign_table" => "tx_movietheater_films",
        "foreign_table_where" => "ORDER BY tx_movietheater_films.title",
        
				"size" => 1,	
				"minitems" => 1,
				"maxitems" => 1,
			)
		),
		"date" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:movietheater/locallang.xml:tx_movietheater_shows.date",		
			"config" => Array (
				"type"     => "input",
				"size"     => "12",
				"max"      => "20",
				"eval"     => "datetime,required",
				"checkbox" => "0",
				"default"  => $_GET['preset']['date']?$_GET['preset']['date']:time(),
			)
		),
	),
	"types" => array (
		"0" => array("showitem" => "hidden;;1;;1-1-1, hall, film, date")
	),
	"palettes" => array (
		"1" => array("showitem" => "")
	)
);



$TCA["tx_movietheater_halls"] = array (
	"ctrl" => $TCA["tx_movietheater_halls"]["ctrl"],
	"interface" => array (
		"showRecordFieldList" => "hidden,name,seats"
	),
	"feInterface" => $TCA["tx_movietheater_halls"]["feInterface"],
	"columns" => array (
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		"name" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:movietheater/locallang.xml:tx_movietheater_halls.name",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"eval" => "required",
			)
		),
		"seats" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:movietheater/locallang.xml:tx_movietheater_halls.seats",		
			"config" => Array (
				"type"     => "input",
				"size"     => "4",
				"max"      => "4",
				"eval"     => "int",
				"checkbox" => "1",
				"range"    => Array (
					"upper" => "1000",
					"lower" => "1"
				),
				"default" => 1
			)
		),
	),
	"types" => array (
		"0" => array("showitem" => "hidden;;1;;1-1-1, name, seats")
	),
	"palettes" => array (
		"1" => array("showitem" => "")
	)
);

if( (TYPO3_MODE=="BE") && (t3lib_div::int_from_ver(TYPO3_version) >= 4001000) ){
  require_once(t3lib_extMgm::extPath('movietheater').'inc/class.tx_movietheater_labels.php');
  $TCA['tx_movietheater_shows']['ctrl']['label_userFunc'] = "tx_movietheater_labels->getLabelShow";
}
?>
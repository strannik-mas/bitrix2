<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Демонстрационная версия продукта «1С-Битрикс: Управление сайтом»");
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
$APPLICATION->SetTitle("Статьи");
?>

<!-- -->
				<?$APPLICATION->IncludeComponent("bitrix:news.list", "articles", Array(
					"IBLOCK_TYPE"	=>	"articles",
					"IBLOCK_ID"	=>	"2",
					"NEWS_COUNT"	=>	"5",
					"SORT_BY1"	=>	"ACTIVE_FROM",
					"SORT_ORDER1"	=>	"DESC",
					"SORT_BY2"	=>	"SORT",
					"SORT_ORDER2"	=>	"ASC",
					"FILTER_NAME"	=>	"",
					"FIELD_CODE"	=>	array(
					),
					"PROPERTY_CODE"	=>	array(
						0	=>	"FORUM_MESSAGE_CNT",
						1	=>	"rating",
					),
					"DETAIL_URL"	=>	"/content/articles/#ELEMENT_ID#/",
					"CACHE_TYPE"	=>	"A",
					"CACHE_TIME"	=>	"3600",
					"CACHE_FILTER"	=>	"N",
					"PREVIEW_TRUNCATE_LEN"	=>	"0",
					"ACTIVE_DATE_FORMAT"	=>	"M j, Y, H:m",
					"DISPLAY_PANEL"	=>	"N",
					"SET_TITLE"	=>	"N",
					"INCLUDE_IBLOCK_INTO_CHAIN"	=>	"Y",
					"ADD_SECTIONS_CHAIN"	=>	"Y",
					"HIDE_LINK_WHEN_NO_DETAIL"	=>	"N",
					"PARENT_SECTION"	=>	"",
					"DISPLAY_TOP_PAGER"	=>	"N",
					"DISPLAY_BOTTOM_PAGER"	=>	"N",
					"PAGER_TITLE"	=>	"Статьи",
					"PAGER_SHOW_ALWAYS"	=>	"N",
					"PAGER_TEMPLATE"	=>	"",
					"PAGER_DESC_NUMBERING"	=>	"N",
					"PAGER_DESC_NUMBERING_CACHE_TIME"	=>	"36000",
					"PAGER_SHOW_ALL" => "N",
					"DISPLAY_DATE"	=>	"Y",
					"DISPLAY_NAME"	=>	"Y",
					"DISPLAY_PICTURE"	=>	"N",
					"DISPLAY_PREVIEW_TEXT"	=>	"Y"
					)
					);?>
				<!-- -->
<!-- --><h1>Новости</h1>
					<?$APPLICATION->IncludeComponent(
						"bitrix:news.list",
						"",
						Array(
							"DISPLAY_DATE" => "Y",
							"DISPLAY_NAME" => "Y",
							"DISPLAY_PICTURE" => "N",
							"DISPLAY_PREVIEW_TEXT" => "Y",
							"IBLOCK_TYPE" => "news",
							"IBLOCK_ID" => "3",
							"NEWS_COUNT" => "5",
							"SORT_BY1" => "ACTIVE_FROM",
							"SORT_ORDER1" => "DESC",
							"SORT_BY2" => "SORT",
							"SORT_ORDER2" => "ASC",
							"FILTER_NAME" => "",
							"FIELD_CODE" => Array("",""),
							"PROPERTY_CODE" => Array("",""),
							"DETAIL_URL" => "/content/news/#SECTION_ID#/#ELEMENT_ID#/",
							"PREVIEW_TRUNCATE_LEN" => "0",
							"ACTIVE_DATE_FORMAT" => "d.m.Y",
							"DISPLAY_PANEL" => "N",
							"SET_TITLE" => "N",
							"INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
							"CACHE_TIME" => "3600",
							"CACHE_FILTER" => "N",
							"DISPLAY_TOP_PAGER" => "N",
							"DISPLAY_BOTTOM_PAGER" => "N",
							"PAGER_TITLE" => "Новости",
							"PAGER_SHOW_ALWAYS" => "N",
							"PAGER_TEMPLATE" => "",
							"PAGER_DESC_NUMBERING" => "N",
							"PAGER_SHOW_ALL" => "N",
						)
					);?>
				<!-- -->

<h2>Видео-новости</h2>

<?$APPLICATION->IncludeComponent(
	"bitrix:player",
	"",
	Array(
		"PLAYER_TYPE" => "auto", 
		"USE_PLAYLIST" => "N", 
		"PATH" => "/upload/intro.flv", 
		"WIDTH" => "400", 
		"HEIGHT" => "324", 
		"FULLSCREEN" => "Y", 
		"SKIN_PATH" => "/bitrix/components/bitrix/player/mediaplayer/skins", 
		"SKIN" => "bitrix.swf", 
		"CONTROLBAR" => "bottom", 
		"WMODE" => "transparent", 
		"HIDE_MENU" => "N", 
		"SHOW_CONTROLS" => "Y", 
		"SHOW_STOP" => "N", 
		"SHOW_DIGITS" => "Y", 
		"CONTROLS_BGCOLOR" => "FFFFFF", 
		"CONTROLS_COLOR" => "000000", 
		"CONTROLS_OVER_COLOR" => "000000", 
		"SCREEN_COLOR" => "000000", 
		"WMODE_WMV" => "window", 
		"AUTOSTART" => "N", 
		"REPEAT" => "N", 
		"VOLUME" => "90", 
		"DISPLAY_CLICK" => "play", 
		"MUTE" => "N", 
		"HIGH_QUALITY" => "Y", 
		"ADVANCED_MODE_SETTINGS" => "N", 
		"BUFFER_LENGTH" => "10", 
		"DOWNLOAD_LINK_TARGET" => "_self" 
	),
false
);?>

<!-- --><h2>Новые фотографии</h2>
<?$APPLICATION->IncludeComponent(
	"bitrix:photogallery.detail.list",
	".default",
	Array(
		"IBLOCK_TYPE" => "photos",
		"IBLOCK_ID" => "11",
		"BEHAVIOUR" => "USER",
		"USER_ALIAS" => $_REQUEST["USER_ALIAS"],
		"SECTION_ID" => $_REQUEST["SECTION_ID"],
		"ELEMENT_LAST_TYPE" => "none",
		"USE_DESC_PAGE" => "N",
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_ORDER" => "asc",
		"PAGE_ELEMENTS" => "6",
		"DETAIL_URL" => "/content/gallery/#USER_ALIAS#/#SECTION_ID#/#ELEMENT_ID#/",
		"DETAIL_SLIDE_SHOW_URL" => "/content/gallery/#USER_ALIAS#/#SECTION_ID#/#ELEMENT_ID#/slide_show/",
		"SEARCH_URL" => "/content/gallery/search/",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"PAGE_NAVIGATION_TEMPLATE" => "",
		"USE_PERMISSIONS" => "N",
		"GROUP_PERMISSIONS" => array(0=>"1",1=>"",),
		"COMMENTS_TYPE" => "none",
		"SET_TITLE" => "N",
		"DATE_TIME_FORMAT" => "d.m.Y",
		"ADDITIONAL_SIGHTS" => array(),
		"PICTURES_SIGHT" => "",
		"THUMBNAIL_SIZE" => "90",
		"SHOW_PAGE_NAVIGATION" => "none",
		"SHOW_CONTROLS" => "N",
		"SHOW_RATING" => "N",
		"SHOW_SHOWS" => "N",
		"SHOW_COMMENTS" => "N",
		"SHOW_TAGS" => "N",
		"MAX_VOTE" => "5",
		"VOTE_NAMES" => array(0=>"1",1=>"2",2=>"3",3=>"4",4=>"5",5=>"",)
	)
);?><!-- -->

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
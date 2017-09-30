<?
IncludeModuleLangFile(__FILE__);

class CAllStatEventType
{
	function Delete($ID, $DELETE_EVENT="Y")
	{
		$err_mess = "File: ".__FILE__."<br>Line: ";
		$DB = CDatabase::GetModuleConnection('statistic');
		$ID = intval($ID);
		$strSql = "SELECT ID FROM b_stat_event_list WHERE EVENT_ID='$ID'";
		$a = $DB->Query($strSql, false, $err_mess.__LINE__);
		while ($ar = $a->Fetch()) CStatEvent::Delete($ar["ID"]);
		$DB->Query("DELETE FROM b_stat_event_day WHERE EVENT_ID='$ID'", false, $err_mess.__LINE__);
		if ($DELETE_EVENT=="Y")
		{
			$DB->Query("DELETE FROM b_stat_event WHERE ID='$ID'", false, $err_mess.__LINE__);
			return true;
		}
		else
		{
			$DB->Query("UPDATE b_stat_event SET DATE_ENTER=null WHERE ID='$ID'", false, $err_mess.__LINE__);
			return true;
		}
		return false;
	}

	// returns arrays which is nedded for plot drawing
	function GetGraphArray($arFilter, &$arrLegend)
	{
		$err_mess = "File: ".__FILE__."<br>Line: ";
		$DB = CDatabase::GetModuleConnection('statistic');
		$arSqlSearch = Array();
		$strSqlSearch = "";
		if (is_array($arFilter))
		{
			foreach ($arFilter as $key => $val)
			{
				if(is_array($val))
				{
					if(count($val) <= 0)
						continue;
				}
				else
				{
					if( (strlen($val) <= 0) || ($val === "NOT_REF") )
						continue;
				}
				$match_value_set = array_key_exists($key."_EXACT_MATCH", $arFilter);
				$key = strtoupper($key);
				switch($key)
				{
					case "EVENT_ID":
						$match = ($arFilter[$key."_EXACT_MATCH"]=="N" && $match_value_set) ? "Y" : "N";
						$arSqlSearch[] = GetFilterQuery("D.EVENT_ID",$val,$match);
						break;
					case "DATE1":
						if (CheckDateTime($val))
							$arSqlSearch[] = "D.DATE_STAT>=".$DB->CharToDateFunction($val, "SHORT");
						break;
					case "DATE2":
						if (CheckDateTime($val))
							$arSqlSearch[] = "D.DATE_STAT<=".$DB->CharToDateFunction($val." 23:59:59", "FULL");
						break;
				}
			}
		}
		$arrDays = array();
		$arrLegend = array();
		$strSqlSearch = GetFilterSqlSearch($arSqlSearch);
		$summa = $arFilter["SUMMA"]=="Y" ? "Y" : "N";
		$strSql = CStatEventType::GetGraphArray_SQL($strSqlSearch);
		$rsD = $DB->Query($strSql, false, $err_mess.__LINE__);
		while ($arD = $rsD->Fetch())
		{
			$arrDays[$arD["DATE_STAT"]]["D"] = $arD["DAY"];
			$arrDays[$arD["DATE_STAT"]]["M"] = $arD["MONTH"];
			$arrDays[$arD["DATE_STAT"]]["Y"] = $arD["YEAR"];
			if ($summa=="N")
			{
				$arrDays[$arD["DATE_STAT"]][$arD["EVENT_ID"]]["COUNTER"] = $arD["COUNTER"];
				$arrDays[$arD["DATE_STAT"]][$arD["EVENT_ID"]]["MONEY"] = $arD["MONEY"];
				$arrLegend[$arD["EVENT_ID"]]["COUNTER_TYPE"] = "DETAIL";
				$arrLegend[$arD["EVENT_ID"]]["NAME"] = (strlen($arD["NAME"])>0) ? $arD["NAME"] : $arD["EVENT1"]." / ".$arD["EVENT2"];
			}
			elseif ($summa=="Y")
			{
				$arrDays[$arD["DATE_STAT"]]["COUNTER"] += $arD["COUNTER"];
				$arrDays[$arD["DATE_STAT"]]["MONEY"] += $arD["MONEY"];
				$arrLegend[0]["COUNTER_TYPE"] = "TOTAL";
			}
		}
		reset($arrLegend);
		$total = sizeof($arrLegend);
		while (list($key, $arr) = each($arrLegend))
		{
			$color = GetNextRGB($color, $total);
			$arr["COLOR"] = $color;
			$arrLegend[$key] = $arr;
		}

		reset($arrDays);
		reset($arrLegend);
		return $arrDays;
	}

	function ConditionSet($event1, $event2, &$arEventType)
	{
		$err_mess = "File: ".__FILE__."<br>Line: ";
		$DB = CDatabase::GetModuleConnection('statistic');
		$w = CStatEventType::GetByEvents($event1, $event2);
		$arEventType = $w->Fetch();
		$EVENT_ID = intval($arEventType["EVENT_ID"]);

		if ($EVENT_ID<=0)
		{
			if (strlen($event1)>0 || strlen($event2)>0)
			{
				// save to database
				$arFields = Array(
					"EVENT1"		=> (strlen($event1)>0) ? "'".$DB->ForSql($event1,200)."'" : "null",
					"EVENT2"		=> (strlen($event2)>0) ? "'".$DB->ForSql($event2,200)."'" : "null",
					"DATE_ENTER"	=> "null"
					);
				$EVENT_ID = $DB->Insert("b_stat_event", $arFields, $err_mess.__LINE__);
			}
		}
		return intval($EVENT_ID);
	}

	function GetByEvents($event1, $event2)
	{
		$err_mess = "File: ".__FILE__."<br>Line: ";
		$DB = CDatabase::GetModuleConnection('statistic');
		$event1 = $DB->ForSql(trim($event1),200);
		$event2 = $DB->ForSql(trim($event2),200);
		$where1 = (strlen($event1)<=0) ? "(EVENT1='' or EVENT1 is null)" : "(EVENT1 = '$event1')";
		$where2 = (strlen($event2)<=0) ? "(EVENT2='' or EVENT2 is null)" : "(EVENT2 = '$event2')";
		$strSql = "
			SELECT
				ID as EVENT_ID,
				ID as TYPE_ID,
				DYNAMIC_KEEP_DAYS,
				KEEP_DAYS,
				DATE_ENTER,
				".$DB->DateToCharFunction("DATE_ENTER")."	DATE_ENTER_STR
			FROM
				b_stat_event
			WHERE $where1 and $where2
			";
		$w = $DB->Query($strSql, false, $err_mess.__LINE__);
		return $w;
	}

	function DynamicDays($EVENT_ID, $date1="", $date2="")
	{
		$arFilter = array("DATE1"=>$date1, "DATE2"=>$date2);
		$z = CStatEventType::GetDynamicList($EVENT_ID, $by, $order, $arMaxMin, $arFilter);
		$d = 0;
		while($zr = $z->Fetch())
			if(intval($zr["COUNTER"]) > 0)
				$d++;
		return $d;
	}
	//check fields before writing
	function CheckFields($arFields, $ID)
	{
		$aMsg = array();

		if(is_set($arFields, "EVENT1") && strlen($arFields["EVENT1"])<=0)
			$aMsg[] = array("id"=>"EVENT1", "text"=>GetMessage("STAT_FORGOT_EVENT1"));
		if(is_set($arFields, "EVENT2") && strlen($arFields["EVENT2"])<=0)
			$aMsg[] = array("id"=>"EVENT2", "text"=>GetMessage("STAT_FORGOT_EVENT2"));
		if(intval($ID)==0)
		{
			$rs = $this->GetByEvents($arFields["EVENT1"], $arFields["EVENT2"]);
			if($rs->Fetch())
				$aMsg[] = array("id"=>"EVENT1", "text"=>GetMessage("STAT_WRONG_EVENT"));
		}

		if(!empty($aMsg))
		{
			$e = new CAdminException($aMsg);
			$GLOBALS["APPLICATION"]->ThrowException($e);
			return false;
		}

		return true;
	}
}
?>

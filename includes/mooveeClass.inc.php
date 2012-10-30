<?php
// please prepare data using prepare.php

class moovee
{
	private $errMsg;
	private $movies, $collision, $group;

	function checkErr()
	{
		if(count($this->errMsg) == 0) return false;
		return true;
	}

	function __construct()
	{
		// set timezone
		date_default_timezone_set('Asia/Taipei');

		$this->errMsg = array();
		$this->group = array();

		$path = "data";
		if(!file_exists("{$path}/movies.json")) $path = "../data";
		$this->movies = json_decode(file_get_contents("{$path}/movies.json"), true);
		$this->collision = json_decode(file_get_contents("{$path}/collision.json"), true);

		// CACHE fields
		// PLACE, DATE, CATEGORY, CTITLE, GRADE, REMARK
		$fields = array("PLACE", "DATE", "CATEGORY", "CTITLE", "GRADE", "REMARK");

		foreach($fields as $field) $this->group[$field] = json_decode(file_get_contents("{$path}/group_{$field}.json"), true);
	}

	function getKeyListByField($field)
	{
		if(isset($this->group[$field])) return $this->group[$field];
		return null;
	}

	function getMovieByKey($key, $field = "*")
	{
		if(isset($this->movies[$key]))
		{
			if($field == "*") return $this->movies[$key];
			else if(is_array($field))
			{
				$output = array();
				foreach($field as $f) $output[$f] = $this->movies[$key][$f];
				return $output;
			}
			else return $this->movies[$key][$field];
		}
		return null;
	}

	function checkCollision($movId, $movIdList)
	{
		if($movId == null) return 0;
		if(!is_array($movIdList)) $movIdList = explode(", ", $movIdList);
		if(isset($this->collision[$movId])) $collTime = $this->collision[$movId];
		$collSeled = $this->group['CTITLE'][$this->movies[$movId]['CTITLE']];
		$collSeled[] = $movId;

		if(!isset($collTime) || array_intersect($collTime, $movIdList) == false)
		{
			if(array_intersect($collSeled, $movIdList) == false) return 0;
			else return -1;
		}
		return 1;
	}

	function getGroupByFieldName($field)
	{
		return $this->group[$field];
	}

	function getCalendar($movIdList)
	{
		if($movIdList == "") return;
		$movIdList = explode(",", trim($movIdList, ','));
		$movList = array();

		foreach($movIdList as $movId) $movList[] = $this->getMovieByKey($movId);

		usort($movList, "compareTime");

		$startDay = date("N", $movList[0]['START_T']) % 7;
		$endDay = 6 - date("N", $movList[count($movList)-1]['END_T']) % 7;
		$calDateT = $movList[0]['START_T'] - 86400 * $startDay;
		$calDate = date("m-d", $calDateT);
		$period = date("d", $movList[count($movList)-1]['END_T'] - $movList[0]['START_T']) + $startDay + $endDay;

		$dayName = array("日", "一", "二", "三", "四", "五", "六");
		echo "<div id=\"calendar\">";
		for($day = 1; $day <= 7; $day++)
		{
			echo "<div class=\"calDayName\">{$dayName[$day-1]}</div>";
		}
		echo "<hr class=\"clear\" />";
		for($day = 1; $day <= $period; $day++)
		{
			echo "<div id=\"d_{$calDate}\" class=\"calDateBlock\"><span class=\"calDate\">{$calDate}</span></div>";
			if($day %7 == 0 && $day != $period) echo "<hr class=\"clear\" />";
			$calDateT += 86400;
			$calDate = date("m-d", $calDateT);
		}
		echo "<hr class=\"clear\" /></div>";

		$datePrev = "";
		foreach($movList as $mov)
		{
			$divId = preg_replace("/....-(..)-(..).*/", "m_$1-$2", $mov['DATE']);
			if($datePrev == "") echo "<ul id=\"{$divId}\" class=\"calMov hidden\">";
			else if($mov['DATE'] != $datePrev) echo "</ul><ul id=\"{$divId}\" class=\"calMov hidden\">";
			$movStartH = date("H", $mov['START_T']);
			$movStartM = date("i", $mov['START_T']);
			$movEndH = date("H", $mov['END_T']);
			$movEndM = date("i", $mov['END_T']);
			if($movEndH < $movStartH) $movEndH += 24;
			echo "<li><span class=\"movTime\">{$movStartH}:{$movStartM} -- {$movEndH}:{$movEndM}</span>"
			. "<span class=\"movPlace\">{$mov['PLACE']}</span>"
			. "<span class=\"movTitle\">{$mov['CTITLE']}</span><span class=\"movRemark\">{$mov['REMARK']}</span></li>";
			$datePrev = $mov['DATE'];
		}
		if($datePrev != "") echo "</ul>";
	}
};


function compareTime($mov1, $mov2)
{
	return ($mov1['START_T'] > $mov2['START_T'])? 1:-1;
}

function compareTitle($mov1, $mov2)
{
	return strcmp($mov1['CTITLE'], $mov2['CTITLE']);
}

?>

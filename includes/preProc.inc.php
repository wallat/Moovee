<?php

function load_file($filename)
{
	$lines = file($filename, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);

	$fields = array();
	$data = array();
	$weekday = array("Mon" => "一", "Tue" => "二", "Wed" => "三",
		"Thu" => "四", "Fri" => "五", "Sat" => "六", "Sun" => "日");

echo "Loading... ";
	foreach($lines as $lineN => $line)
	{
	// 0 PLACE, 1 DATE, 2 TIME, 3 DURATION, 4 CATEGORY, 5 CTITLE, 6 ETITLE, 7 GRADE, 8 REMARK, 9 PAGE
		if($lineN == 0) continue;
		else
		{
echo "$lineN ";
			$line = explode("\t", $line);
			$start_t = strtotime("{$line[1]} {$line[2]}");
			$end_t = strtotime("{$line[1]} {$line[2]} + {$line[3]} minutes");
			$start_d = $weekday[date("D", $start_t)];
			$end_d = $weekday[date("D", $end_t)];
			$tmp = array(
				'KEY' => $lineN-1,
				'PLACE' => $line[0],
				'START_T' => $start_t,
				'END_T' => $end_t,
				'DATE' => date("Y-m-d [{$start_d}]", $start_t),
				'START' => date("Y-m-d [{$start_d}] H:i", $start_t),
				'END' => date("Y-m-d [{$end_d}] H:i", $end_t),
				'DURATION' => $line[3],
				'CATEGORY' => $line[4],
				'CTITLE' => $line[5],
				'ETITLE' => $line[6],
				'GRADE' => $line[7],
				'REMARK' => $line[8],
				'PAGE' => $line[9]
			);
			$data[] = $tmp;
		}
	}
echo "done.\n";
	return $data;
}

function arr_search ( $array, $expression ) { 
// echo "expression: $expression\n";
	$result = array(); 
	$expression = preg_replace ( "/([^\s]+?) ?(=|<|>|!)/", "\$a['$1']$2", $expression ); 
	foreach ( $array as $a ) if ( eval ( "return $expression;" ) ) $result[] = $a; 
	return $result; 
}

function check_collision($mov1, $mov2)
{
	if($mov1['END_T'] < $mov2['START_T'] || $mov2['END_T'] < $mov1['START_T']) return false;
	return true;
}

function pre_collision($data)
{
	$output = array();
	foreach($data as $k1)
	{
		foreach($data as $k2)
		{
			if($k1['KEY'] == $k2['KEY']) continue;
			if(check_collision($k1, $k2) == true) $output[$k1['KEY']][] = $k2['KEY'];
		}
	}
	return $output;
}

function pre_groupBy($data, $field = "CTITLE")
{
	$output = array();
	foreach($data as $k1)
	{
		$movGroup = arr_search($data, $field . '=="' . $k1[$field] . '"');
		foreach($movGroup as $k2)
		{
			$output[$k1[$field]][] = $k2['KEY'];
		}
	}
	foreach($output as $k=>$v)
	{
		$output[$k] = array_unique($v);
		sort($output[$k], SORT_NUMERIC);
	}
	ksort($output, SORT_STRING);
	return $output;
}
?>

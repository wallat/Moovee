<?php
include("../../includes/preProc.inc.php");
if(!isset($argv[1])) die("usage: prepare.php [filename]\n");
$data = load_file($argv[1]);
file_put_contents("movies.json", json_encode($data));
file_put_contents("collision.json", json_encode(pre_collision($data)));

// CACHE fields
// PLACE, DATE, CATEGORY, CTITLE, GRADE, REMARK, LINK
// $fields = array("PLACE", "DATE", "CATEGORY", "CTITLE", "GRADE", "REMARK", "LINK");
$fields = array("PLACE", "DATE", "CATEGORY", "CTITLE", "GRADE", "REMARK");

foreach($fields as $field)
{
	echo "cache {$field}\n";
	file_put_contents("group_{$field}.json", json_encode(pre_groupBy($data, $field)));
}

?>

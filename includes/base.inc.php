<?php
header('Cache-Control: max-age=120, must-revalidate;');
header('Content-type: text/html; charset= utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';
$movs = "";
if(isset($_GET['movs'])) $movs = preg_replace('/[^0-9,]/', '', $_GET['movs']);
?>


<?php
$counter = intval(file_get_contents("counter.dat"));
if(!isset($_COOKIE['visitor'])){
    $counter++;
    $fp = fopen("counter.dat", "w");
    flock($fp, LOCK_EX);   // do an exclusive lock
    fwrite($fp, $counter);
    flock($fp, LOCK_UN);   // release the lock
    fclose($fp);
//    setcookie("visitor", 1, time()+3600);
    setcookie("visitor", 1, time()+30); // set to 30 sec.
}

header('Cache-Control: max-age=120, must-revalidate;');
header('Content-type: text/html; charset= utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';
$movs = "";
if(isset($_GET['movs'])) $movs = preg_replace('/[^0-9,]/', '', $_GET['movs']);
?>


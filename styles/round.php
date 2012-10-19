<?php
header('Content-Type: image/svg+xml');
$bd = "FF0000";
$bg = "FFCCCC";
if(isset($_GET['bd'])) $bd = $_GET['bd'];
if(isset($_GET['bg'])) $bg = $_GET['bg'];
?>
<svg xmlns="http://www.w3.org/2000/svg">
<mask id="m1">
<rect width="100%" height="100%" rx="10" ry="10" fill="white" stroke="black" stroke-width="2" />
</mask>

<rect stroke="#<?php echo $bd;?>" fill="#<?php echo $bg;?>" stroke-width="4" mask="url(#m1)" width="100%" height="100%" rx="10" ry="10" />

</svg>

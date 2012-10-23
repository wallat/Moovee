<?php
if(isset($_GET['movs']))
{
	header("HTTP/1.1 301 Moved Permanently");
	header("location: ./gh2009/?movs=" . $_GET['movs']);
	return;
}
else
{
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Moovee -- A simple movie scheduler</title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<meta name="description" content="很陽春的排片單系統" />
<meta name="keywords" content="排片單, 金馬, 金馬影展, 台北電影節, Taipei Golden Horse Film Festival, Taipei Film Festival, Movie Time Schduler" />
<!-- iPhone test -->
<link rel="apple-touch-icon" href="icon/moovee.png" />
<meta name="viewport" content = "width = device-width, initial-scale = 1, user-scalable = no" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
<!-- Oh, yes, dear IE, please render it using chrome frame, or your almost-standard engine -->
<meta http-equiv="X-UA-Compatible" content="chrome=1" />
<meta http-equiv="X-UA-Compatible" content="IE=8" />
<link rel="stylesheet" href="styles/reset.css" type="text/css" />
<link rel="stylesheet" href="styles/style.css" type="text/css" media="screen" />
<link rel="stylesheet" href="styles/print.css" type="text/CSS" media="print" />
<link rel="stylesheet" href="styles/movprops.css" type="text/css" />
<script type="text/javascript">
// <!--
// <![CDATA[
// handle iPhone WebApp
// dirty code
	function render()
	{
		var navAgent = navigator.userAgent;
		if(navAgent.match(/iPad/i) || navAgent.match(/iPhone/i) || navAgent.match(/iPod/i))
		{
			document.title = "Moovee";
			if(window.navigator.standalone != true)
			{
				document.getElementsByTagName("body")[0].innerHTML = '<h1 style="font-size:200%;">沒錯，這是排片單網頁。請點選 + 將此頁安裝至您的 Home Screen :)</h1>';
			}
		}

		if(navAgent.match(/iPad/i) || navAgent.match(/iPhone/i) || navAgent.match(/iPod/i) || navAgent.match(/Android/i))
		{
			document.title = "Moovee";
			document.getElementById("festList").style.fontSize = "120%";
			document.getElementsByTagName("body")[0].style.paddingTop = "10px";

			var a=document.getElementsByTagName("a");
			for(var i=0;i<a.length;i++)
			{
				a[i].onclick = function() {
					window.location=this.getAttribute("href");
					return false;
				}
			}
		}

		if(navAgent.match(/MSIE/i))
			document.getElementsByTagName("span")[0].innerHTML = "<iframe src=\"http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fcornguo.atcity.org%2Ftest%2Fmoovee%2F&amp;layout=button_count&amp;show_faces=true&amp;width=100&amp;action=like&amp;colorscheme=light&amp;height=21\" style=\"border:none; overflow:hidden; width:100px; height:21px;\" frameborder=\"0\" scrolling=\"no\" allowTransparency=\"true\" />";
	}
// ]]>
// -->
</script>
</head>
<body onload="render();">
<div id="festList" style="border:1px solid #FF6666; padding:5px; margin:10px 0; font-size:200%;">
<h1>Which film festival?</h1>
<ul>
<li><a href="http://moovee.wallagroup.com">Moovee @ Walla Group</a> (New)</li>
<li><a href="tiff2011">2011 台北電影節 / Taipei Film Festival 2011</a></li>
<li><a href="gh2011f">2011 金馬奇幻影展 / Golden Horse 2011 (Fantastic)</a></li>
<li><a href="gh2010">2010 金馬影展 / Golden Horse 2010</a></li>
<li><a href="tiff2010">2010 台北電影節 / Taipei Film Festival 2010</a></li>
<li><a href="gh2010f">2010 金馬奇幻影展 / Golden Horse 2010 (Fantastic)</a></li>
<li><a href="gh2009">2009 金馬影展 / Golden Horse 2009</a></li>
</ul>
</div>
<div>
<p>Short link of this page: <a href="http://bit.ly/moovee">http://bit.ly/moovee</a></p>
<p>Hint: iPad / iPhone / iPod user, try to add this page to your Home Screen :)</p>
<p>20121020: Here's <a href="https://github.com/cornguo/moovee">the source code</a> of this tool. Have fun :D</p>
<div class="fb_like"><object data="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fcornguo.atcity.org%2Ftest%2Fmoovee%2F&amp;layout=button_count&amp;show_faces=true&amp;width=100&amp;action=like&amp;colorscheme=light&amp;height=21" style="border:none; overflow:hidden; width:100px; height:21px;" type="text/html"></object></div>
</div>
</body>
</html>
<?php
}
?>

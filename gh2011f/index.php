<?php include("../includes/base.inc.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Moovee -- 2011 Golden Horse Fantastic Film Festival</title>
<meta name="description" content="很陽春的排片單系統" />
<meta name="keywords" content="排片單, 2011, 金馬, 金馬影展, 奇幻影展, Golden Horse 2011" />
<?php include("../includes/header.inc.php"); ?>
</head>
<body onload="render();">
<div class="fb_like"><object data="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fcornguo.atcity.org%2Ftest%2Fmoovee%2F&amp;layout=button_count&amp;show_faces=true&amp;width=100&amp;action=like&amp;colorscheme=light&amp;height=21" style="border:none; overflow:hidden; width:100px; height:21px;" type="text/html"></object></div>
<div id="list">
<div id="tabs">
<span id="by-CATEGORY" class="tab" onclick="getGroup(this);">影展分類</span>
<span id="by-DATE" class="tab" onclick="getGroup(this);">播映日期</span>
<span id="by-PLACE" class="tab" onclick="getGroup(this);">播映影廳</span>
<span id="by-GRADE" class="tab" onclick="getGroup(this);">電影分級</span>
<span id="by-REMARK" class="tab" onclick="getGroup(this);">場次備註</span>
<span class="description">&lt;- 點此開始 [<a href="http://playpcesor.blogspot.com/2009/10/moovee-2009.html" title="使用說明 by 電腦玩物">?</a>] (<a href="http://www.ghfff.org.tw/home.aspx">2011 台北金馬奇幻影展官方網站</a> / <a href="2011_GHFFF_Programme.pdf">影展手冊 PDF</a>)</span>
</div>
<div id="groupBox"></div>
<div id="titleBox"></div>
</div>
<hr class="clear" />
<div id="dropBox"></div>
<div id="lPanel">
<div id="dragBox"></div>
<div id="remarkDesc">
★ 影人出席或導讀；詳情請見網站及現場<br />
▲ 影片拷貝非英語發音且無英文字幕 (註：有中文發音或中文字幕)<br />
◎ 非售票之活動場次；入場方式請見活動頁<br />
(跨日場次將以大於 24 小時方式表示)
</div>
</div>
<?php include("footer.inc.php");?>
<div id="varStor" class="hidden"><?php if(strlen($movs) > 0) echo $movs;?></div>
<div id="filter" class="hidden"></div>
</body>
</html>

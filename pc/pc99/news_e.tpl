
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="keywords" content="<sl-tag>新闻分类keywords</sl-tag>">
<meta name="description" content="<sl-tag>新闻分类description</sl-tag>">
<meta name="author" content="S-CMS">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<meta content="telephone=no" name="format-detection">
<link rel="stylesheet" type="text/css" href="<sl-tag>安装目录</sl-tag>pc/pc99/skin/style/lib.css">
<link rel="stylesheet" type="text/css" href="<sl-tag>安装目录</sl-tag>pc/pc99/skin/style/style.css">
<link rel="stylesheet" type="text/css" href="<sl-tag>安装目录</sl-tag>pc/pc99/skin/style/999.css">
<script type="text/javascript" src="<sl-tag>安装目录</sl-tag>pc/pc99/skin/js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="<sl-tag>安装目录</sl-tag>pc/pc99/skin/js/org1470120033.js" data-main="baseMain"></script>
<title><sl-tag>新闻分类标题</sl-tag>-<sl-tag>网站标题</sl-tag></title>
<link href="<sl-tag>安装目录</sl-tag><sl-tag>网站ico</sl-tag>" rel="shortcut icon">
</head>
<body>
<sl-tag>网站顶部</sl-tag>
<div id="sitecontent">
  <div id="newsPage" class="npagePage Pagenews">
    <div id="banner">
      <div style="background-image:url(<sl-tag>安装目录</sl-tag><sl-tag>新闻列表页</sl-tag>);"></div>
    </div>
    <div class="content">
      <div class="header">
        <p class="title"><sl-tag>新闻分类标题</sl-tag></p>
        <p class="subtitle"><sl-tag>新闻分类英文标题</sl-tag></p>
      </div>
      <ul id="category">
<sl-function f="left_list">
<parameter><![CDATA[<li><a href="%左侧链接%" title="%左侧标题%" id="%左侧type%-%左侧typeID%">%左侧标题%</a></li>]]></parameter>
<parameter><![CDATA[<sl-tag>菜单ID</sl-tag>]]></parameter>
</sl-function>
</ul>
      <div id="newslist">
        <div class="wrapper">
<sl-function f="news_list2">
<parameter><![CDATA[]]></parameter>
<parameter><![CDATA[<div id="newsitem_%i%" class="wow newstitem left"> <a class="newscontent" target="_blank" href="%新闻链接%" title="%新闻标题%">            <div class="news_wrapper">              <div class="newsbody">                <p class="date"><span class="md">%发表年%<span>-</span></span><span class="year">%发表月%-%发表日%</span></p>                <p class="title">%新闻标题%</p>                <div class="separator"></div>                <p class="description">%新闻简述%</p>              </div>            </div>            <div class="newsimg" style="background-image:url(<sl-tag>安装目录</sl-tag>%新闻图片%)"></div>            </a> <a href="%新闻链接%" target="_blank" class="details" title="%新闻标题%">more<i class="fa fa-angle-right"></i></a> </div>]]></parameter>
<parameter><![CDATA[10|<sl-tag>新闻分类page</sl-tag>]]></parameter>
<parameter><![CDATA[<sl-tag>新闻分类ID</sl-tag>]]></parameter>
<parameter><![CDATA[normal]]></parameter>
</sl-function>
</div>
      </div>
      <div class="clear"></div>
      <div id="pages"> <sl-function f="getpage2">
<parameter><![CDATA[news]]></parameter>
<parameter><![CDATA[<sl-tag>新闻分类ID</sl-tag>]]></parameter>
<parameter><![CDATA[10|<sl-tag>新闻分类page</sl-tag>]]></parameter>
</sl-function>
 </div>
    </div>
  </div>
</div>
<sl-tag>网站底部</sl-tag>
<script>$('#news-<sl-tag>新闻分类ID</sl-tag>').attr('class','active');</script></body>
</html> 

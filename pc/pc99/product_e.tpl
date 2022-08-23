
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="keywords" content="<sl-tag>产品分类keywords</sl-tag>">
<meta name="description" content="<sl-tag>产品分类description</sl-tag>">
<meta name="author" content="S-CMS">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<meta content="telephone=no" name="format-detection">
<link rel="stylesheet" type="text/css" href="<sl-tag>安装目录</sl-tag>pc/pc99/skin/style/lib.css">
<link rel="stylesheet" type="text/css" href="<sl-tag>安装目录</sl-tag>pc/pc99/skin/style/style.css">
<link rel="stylesheet" type="text/css" href="<sl-tag>安装目录</sl-tag>pc/pc99/skin/style/999.css">
<script type="text/javascript" src="<sl-tag>安装目录</sl-tag>pc/pc99/skin/js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="<sl-tag>安装目录</sl-tag>pc/pc99/skin/js/org1470120033.js" data-main="baseMain"></script>
<title><sl-tag>产品分类标题</sl-tag>-<sl-tag>网站标题</sl-tag></title>
<link href="<sl-tag>安装目录</sl-tag><sl-tag>网站ico</sl-tag>" rel="shortcut icon">
</head>
<body>
<sl-tag>网站顶部</sl-tag>
<div id="sitecontent">
  <div class="npagePage Pageanli" id="mproject">
    <div id="banner">
      <div style="background-image:url(<sl-tag>安装目录</sl-tag><sl-tag>产品列表页</sl-tag>);"></div>
    </div>
    <div class="content">
      <div class="header" id="plheader">
        <p class="title"><sl-tag>产品分类标题</sl-tag></p>
        <p class="subtitle"><sl-tag>产品分类英文标题</sl-tag></p>
      </div>
      <ul id="category">
<sl-function f="left_list">
<parameter><![CDATA[<li><a href="%左侧链接%" title="%左侧标题%" id="%左侧type%-%左侧typeID%">%左侧标题%</a></li>]]></parameter>
<parameter><![CDATA[<sl-tag>菜单ID</sl-tag>]]></parameter>
</sl-function>
</ul>
      <div id="projectlist" class="module-content">
        <div class="wrapper">
          <ul class="content_list">
<sl-function f="product_list2">
<parameter><![CDATA[]]></parameter>
<parameter><![CDATA[<li class="projectitem"><a href="%产品链接%" target="_blank" title="%产品标题%">              <div class="project_img"> <img src="<sl-tag>安装目录</sl-tag>%产品小图%" width="500" height="320" alt="%产品标题%" title="%产品标题%"> </div>              <div class="project_info">                <div>                  <p class="title">%产品标题%</p>                  <p class="subtitle">%产品分类标题%</p>                  <p class="description hide">%产品简述%</p>                </div>              </div>              </a> <a href="%产品链接%" target="_blank" class="details" title="%产品标题%">more<i class="fa fa-angle-right"></i></a> </li>]]></parameter>
<parameter><![CDATA[10|<sl-tag>产品分类page</sl-tag>]]></parameter>
<parameter><![CDATA[<sl-tag>产品分类ID</sl-tag>|<sl-tag>产品分类type</sl-tag>]]></parameter>
</sl-function>
</ul>
        </div>
      </div>
      <div class="clear"></div>
      <div id="pages">
<sl-function f="getpage2">
<parameter><![CDATA[product]]></parameter>
<parameter><![CDATA[<sl-tag>产品分类ID</sl-tag>|<sl-tag>产品分类type</sl-tag>]]></parameter>
<parameter><![CDATA[10|<sl-tag>产品分类page</sl-tag>]]></parameter>
</sl-function>
</div>
    </div>
  </div>
</div>
<sl-tag>网站底部</sl-tag>
<script>$('#product-<sl-tag>产品分类ID</sl-tag>').attr('class','active');</script></body>
</html> 

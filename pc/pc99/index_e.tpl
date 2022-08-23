<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="keywords" content="<sl-tag>网站关键字</sl-tag>">
<meta name="description" content="<sl-tag>网站描述</sl-tag>">
<meta name="author" content="S-CMS">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<meta content="telephone=no" name="format-detection">
<link rel="stylesheet" type="text/css" href="<sl-tag>安装目录</sl-tag>pc/pc99/skin/style/lib.css">
<link rel="stylesheet" type="text/css" href="<sl-tag>安装目录</sl-tag>pc/pc99/skin/style/style.css">
<link rel="stylesheet" type="text/css" href="<sl-tag>安装目录</sl-tag>pc/pc99/skin/style/999.css">
<script type="text/javascript" src="<sl-tag>安装目录</sl-tag>pc/pc99/skin/js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="<sl-tag>安装目录</sl-tag>pc/pc99/skin/js/org1470120033.js" data-main="indexMain"></script>
<title><sl-tag>网站标题</sl-tag></title>
<link href="<sl-tag>安装目录</sl-tag><sl-tag>网站ico</sl-tag>" rel="shortcut icon">
</head>
<body>
<sl-tag>网站顶部</sl-tag>
<div id="sitecontent">
  <div id="indexPage">
    <div id="mslider" class="module"> 
      <script type="text/javascript">$(function(){$("#mslider li video").each(function(index, element) {element.play();});})</script>
      <ul class="slider" data-options-height="660" data-options-auto="1" data-options-mode="0" data-options-pause="6" data-options-ease="ease-out">
<sl-function f="getslide">
<parameter><![CDATA[<li style="background-image:url(<sl-tag>安装目录</sl-tag>%图片路径%)" class="active">  <a target="_blank" href="%幻灯链接%" title="%幻灯标题%">          <div id="tempImage_0"></div>          <div class="mask"></div>        </a>          </li>]]></parameter>
</sl-function>
</ul>
    </div>
    <div id="mindex" data-options-ease="Expo.easeInOut" data-options-speed="1" data-options-sscreen="0"></div>
    <div id="mservice" class="module">
      <div class="bgmask"></div>
      <div class="content layoutnone">
        <div class="header wow fw" data-wow-delay=".1s">
          <p class="title">医疗特色</p>
          <p class="subtitle"></p>
        </div>
        <div class="module-content fw" id="servicelist">
          <div class="wrapper">
            <ul class="content_list" data-options-sliders="3" data-options-margin="10" data-options-ease="1" data-options-speed="1">
              <li id="serviceitem_0" class="serviceitem wow"><a href="<sl-tag>图片1url</sl-tag>" target="_blank"><img src="<sl-tag>安装目录</sl-tag><sl-tag>图片1</sl-tag>" height="120">
                <div>
                  <p class="title"><sl-tag>文字1</sl-tag></p>
                  <p class="description"><sl-tag>文字1en</sl-tag></p>
                </div>
                </a> <a href="<sl-tag>图片1url</sl-tag>" target="_blank" class="details">more<i class="fa fa-angle-right"></i></a> </li>
              <li id="serviceitem_1" class="serviceitem wow"><a href="<sl-tag>图片2url</sl-tag>" target="_blank"><img src="<sl-tag>安装目录</sl-tag><sl-tag>图片2</sl-tag>" height="120">
                <div>
                  <p class="title"><sl-tag>文字2</sl-tag></p>
                  <p class="description"><sl-tag>文字2en</sl-tag></p>
                </div>
                </a> <a href="<sl-tag>图片2url</sl-tag>" target="_blank" class="details">more<i class="fa fa-angle-right"></i></a> </li>
              <li id="serviceitem_2" class="serviceitem wow"><a href="<sl-tag>图片3url</sl-tag>" target="_blank"><img src="<sl-tag>安装目录</sl-tag><sl-tag>图片3</sl-tag>" height="120">
                <div>
                  <p class="title"><sl-tag>文字3</sl-tag></p>
                  <p class="description"><sl-tag>文字3en</sl-tag></p>
                </div>
                </a> <a href="<sl-tag>图片3url</sl-tag>" target="_blank" class="details">more<i class="fa fa-angle-right"></i></a> </li>
            </ul>
          </div>
        </div>
        <div class="clear"></div>
        <a href="index.html" class="more wow">MORE<i class="fa fa-angle-right"></i></a> </div>
    </div>
    <div id="mproject" class="module">
      <div class="bgmask"></div>
      <div class="content layoutnone">
        <div class="header wow">
          <p class="title">Department navigation</p>
          <p class="subtitle"></p>
        </div>
        <div id="category" class="hide wow">
<sl-function f="product_sort_list">
<parameter><![CDATA[<a href="%产品分类链接%" title="%产品分类标题%">%产品分类标题%</a>]]></parameter>
<parameter><![CDATA[0]]></parameter>
</sl-function>
</div>
        
        <div class="module-content" id="projectlist">
          <div class="projectSubList" id="projectSubList_">
            <div class="projectSubHeader">
              <p class="title"></p>
              <p class="subtitle"></p>
            </div>
            <div class="wrapper">
              <ul class="content_list" data-options-sliders="8" data-options-margin="15" data-options-ease="1" data-options-speed="1">
<sl-function f="product_list2">
<parameter><![CDATA[]]></parameter>
<parameter><![CDATA[<li id="projectitem_%i%" class="projectitem wow"> <a href="%产品链接%" class="projectitem_content" target="_blank" title="%产品标题%">                  <div class="projectitem_wrapper">                    <div class="project_img"> <img src="<sl-tag>安装目录</sl-tag>%产品小图%" alt="%产品标题%" title="%产品标题%"> </div>                    <div class="project_info">                      <div>                        <p class="title">%产品标题%</p>                        <p class="subtitle">%产品简述%</p>                      </div>                    </div>                  </div>                  </a> </li>]]></parameter>
<parameter><![CDATA[8]]></parameter>
<parameter><![CDATA[0]]></parameter>
</sl-function>
</ul>
            </div>
             
          </div>
           
          <a href="<sl-function f="product_list2">
<parameter><![CDATA[%产品分类链接%]]></parameter>
<parameter><![CDATA[]]></parameter>
<parameter><![CDATA[1]]></parameter>
<parameter><![CDATA[0]]></parameter>
</sl-function>" class="more wow">MORE<i class="fa fa-angle-right"></i></a> </div>
        
        <div class="clear"></div>
      </div>
    </div>
    
    <div id="mteam" class="module">
      <div class="bgmask"></div>
      <div class="content layoutslider">
        <div class="header wow">
          <p class="title">Physician introduction</p>
          <p class="subtitle"></p>
        </div>
        <div class="module-content fw">
          <div class="wrapper">
            <ul class="content_list" data-options-sliders="1" data-options-margin="40" data-options-ease="1" data-options-speed="0.5">
<sl-function f="product_list2">
<parameter><![CDATA[]]></parameter>
<parameter><![CDATA[<li id="teamitem_%i%" class="wow">                <div class="header wow" data-wow-delay=".3s"> <a href="%产品链接%" target="_blank" title="%产品标题%"><img src="<sl-tag>安装目录</sl-tag>%产品小图%" width="180" height="180" alt="%产品标题%" title="%产品标题%"></a> </div>                <div class="summary wow">                  <p class="title"><a href="%产品链接%" title="%产品标题%">%产品标题%</a></p>                  <p class="subtitle">%产品分类标题%</p>                  <p class="description wow">%产品简述%</p>                </div>                <a href="%产品链接%" target="_blank" class="details" title="%产品标题%">more<i class="fa fa-angle-right"></i></a> </li>]]></parameter>
<parameter><![CDATA[10]]></parameter>
<parameter><![CDATA[0|1]]></parameter>
</sl-function>
</ul>
          </div>
        </div>
        <div class="clear"></div>
 </div>
    </div>
    <div id="mnews" class="module">
      <div class="bgmask"></div>
      <div class="content layoutnone">
        <div class="header wow">
          <p class="title">News</p>
          <p class="subtitle"></p>
        </div>
        <div class="module-content" id="newslist">
          <div class="wrapper">
            <ul id="newsx" class="content_list" data-options-sliders="3" data-options-margin="40" data-options-ease="1" data-options-speed="0.5" data-options-mode="horizontal" data-options-wheel="0">
<sl-function f="news_list2">
<parameter><![CDATA[]]></parameter>
<parameter><![CDATA[<li id="newsitem_%i%" class="wow newstitem left"><a class="newscontent" target="_blank" href="%新闻链接%" title="%新闻标题%">                <div class="news_wrapper">                  <div class="newsbody">                    <p class="date"><span class="md">%发表年%<span>-</span></span><span class="year">%发表月%-%发表日%</span></p>                    <p class="title">%新闻标题%</p>                    <div class="separator"></div>                    <p class="description">%新闻简述%</p>                  </div>                </div>                <div class="newsimg" style="background-image:url(<sl-tag>安装目录</sl-tag>%新闻图片%)"></div>                </a> <a href="%新闻链接%" target="_blank" class="details" title="%新闻标题%">more<i class="fa fa-angle-right"></i></a> </li>]]></parameter>
<parameter><![CDATA[5]]></parameter>
<parameter><![CDATA[0]]></parameter>
<parameter><![CDATA[normal]]></parameter>
</sl-function>
</ul>
          </div>
        </div>
        <div class="clear"></div>
        <a href="<sl-function f="news_list2">
<parameter><![CDATA[%新闻分类链接%]]></parameter>
<parameter><![CDATA[]]></parameter>
<parameter><![CDATA[1]]></parameter>
<parameter><![CDATA[0]]></parameter>
</sl-function>" class="more wow">MORE<i class="fa fa-angle-right"></i></a>
        <div style="height:0"> &nbsp; </div>
      </div>
    </div>
    <script>
    $("#newsx li:even").attr("class","wow newstitem right")
    
    </script>
    <div id="mpage" class="module">
      <div class="bgmask"></div>
      <div class="content">
        <div class="module-content">
          <div class="wrapper">
            <ul class="slider one">
              <sl-function f="text_intro">
<parameter><![CDATA[<li>                <div class="header wow" data-wow-delay=".2s">                  <p class="title">%简介标题%</p>                  <p class="subtitle">%简介英文标题%</p>                </div>                <div class="des-wrap">                  <p class="description wow" data-wow-delay=".3s">%简介内容%</p>                </div>                <a href="%简介链接%" class="more wow" data-wow-delay=".5s" title="%简介标题%">MORE<i class="fa fa-angle-right"></i></a>                <div class="fimg wow" style="background-image:url(<sl-tag>安装目录</sl-tag>%简介图片%)"></div>              </li>]]></parameter>
<parameter><![CDATA[a]]></parameter>
<parameter><![CDATA[200]]></parameter>
</sl-function>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div id="mpartner" class="module">
      <div class="bgmask"></div>
      <div class="content layoutslider">
        <div class="header wow fw" data-wow-delay=".1s">
          <p class="title">Cooperative partner</p>
          <p class="subtitle"></p>
        </div>
        <div class="module-content fw">
          <div class="wrapper">
            <ul class="content_list" data-options-ease="1" data-options-speed="1" id="xxx">
<sl-function f="link_list">
<parameter><![CDATA[<a href="%友链网址%" title="%友链网站%" target="_blank"><img src="<sl-tag>安装目录</sl-tag>%友链图片%" width="160" height="80" alt="%友链网站%" title="%友链网站%"></a>]]></parameter>
<parameter><![CDATA[0]]></parameter>
</sl-function>
            </ul>
          </div>
        </div>
      </div>
    </div>
<script type="text/javascript">
all=$('#xxx').children().length;
num=Math.ceil(all/8);
for ( var k = 0; k <= num-1; k++){
$ ("<li id='item_"+k+"' class='wow'>").appendTo("#xxx");
}
  $("#xxx a").each(function(i){
   var id = "item_"+parseInt(i/8);
   $(this).appendTo("#"+id);
  });
</script>
    <div id="mcontact" class="module">
      <div class="bgmask"></div>
      <div class="content">
        <div class="header wow fadeInUp fw" data-wow-delay=".1s">
          <p class="title">Contact Us</p>
          <p class="subtitle">Contact us</p>
        </div>
        <div id="contactlist" class="fw">
          <div id="contactinfo" class="fl wow" data-wow-delay=".2s">
            <h3 class="ellipsis name"><sl-tag>网站标题</sl-tag></h3>
            <sl-tag>联系方式</sl-tag>
            <div> <a class="fl" target="_blank" href="<sl-tag>微博主页</sl-tag>" id="sweibo"><i class="fa fa-weibo"></i></a>  <a id="mpbtn" class="fl" href="<sl-tag>安装目录</sl-tag><sl-tag>微信二维码</sl-tag>"><i class="fa fa-weixin"></i></a> </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<sl-tag>网站底部</sl-tag>
</body>
</html>

<div class="mip-mnav">
        <mip-sidebar id="left-sidebar" layout="nodisplay" side="left" class="mip-hidden">
            <ul class="nav navbar-nav navbar-left">
<sl-function f="getmenu">
<parameter><![CDATA[<li><a href="%主菜单链接%" title="%主菜单标题%" data-type="mip">%主菜单标题%</a></li>]]></parameter>
<parameter><![CDATA[]]></parameter>
<parameter><![CDATA[]]></parameter>
</sl-function>
</ul>
        </mip-sidebar>
    </div>
    <mip-fixed type="top" class="mip-top-head">
        <div class="mip-pcheader">
            <div class="mip-center">
                <div class="mip-text clearfix">
                    <div class="mip-logo">
                        <a href="<sl-tag>首页链接</sl-tag>" data-type="mip"><mip-img src="<sl-tag>安装目录</sl-tag><sl-tag>网站logo</sl-tag>"></mip-img></a>
                    </div>
                    <div class="mip-mbtn">
                        <span on="tap:left-sidebar.open"></span>
                    </div>
                    <div class="mip-nav">
                        <mip-nav-slidedown data-id="bs-navbar" class="mip-element-sidebar container">
                            <nav id="bs-navbar" class="navbar-collapse collapse navbar navbar-static-top">
                                <ul class="nav navbar-nav navbar-right">
<sl-function f="getmenu">
<parameter><![CDATA[<li>
                                            <a href="%主菜单链接%" title="%主菜单标题%" data-type="mip"><span class="navbar-more">%主菜单标题%</span></a>
                                            %子菜单%
                                        </li>]]></parameter>
<parameter><![CDATA[<li><a href="%子菜单链接%" title="%子菜单标题%" data-type="mip">%子菜单标题%</a></li>]]></parameter>
<parameter><![CDATA[<ul>
%子菜单样式%
</ul>]]></parameter>
</sl-function>
</ul>
                            </nav>
                        </mip-nav-slidedown>
                    </div>
                </div>
            </div>
        </div>
    </mip-fixed>
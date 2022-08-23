<header>
		<button on="tap:sidebar.toggle" class="ampstart-btn caps m2 header-icon-1"><i class="fa fa-navicon"></i></button>
		<a href="<sl-tag>首页链接</sl-tag>" class="header-logo"></a>
		<a href="<sl-tag>联系链接</sl-tag>" class="header-icon-2"><i class="fa fa-envelope-o"></i></a>
	</header>
		
	<amp-sidebar id="sidebar" layout="nodisplay" side="left">
<sl-function f="getmenu">
<parameter><![CDATA[<amp-accordion class="submenu"><section><h4><i class="fa fa-%主菜单图标%"></i>%主菜单标题%</h4><div><a href="%主菜单链接%" title="%主菜单标题%"><i class="fa fa-%主菜单图标%"></i>%主菜单标题%</a>%子菜单%</div></section></amp-accordion>]]></parameter>
<parameter><![CDATA[<a href="%子菜单链接%" title="%子菜单标题%"><i class="fa fa-%子菜单图标%"></i>%子菜单标题%</a>]]></parameter>
<parameter><![CDATA[%子菜单样式%]]></parameter>
</sl-function>
</amp-sidebar>
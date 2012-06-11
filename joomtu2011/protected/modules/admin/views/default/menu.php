	<script language=javascript>
	function expand(el)
	{
		childObj = document.getElementById("child" + el);

		if (childObj.style.display == 'none')
		{
			childObj.style.display = 'block';
		}
		else
		{
			childObj.style.display = 'none';
		}
		return;
	}
	</script>
<?php $this->widget('ext.tree.widgets.TreeWidget',array('modelName'=>'Menu'));?>

	<table height="100%" cellSpacing=0 cellPadding=0 width=170
		background=/images/menu_bg.jpg border=0>
		<tr>
			<td vAlign=top align=middle>
				<table cellSpacing=0 cellPadding=0 width="100%" border=0>

					<tr>
						<td height=10></td>
					</tr>
				</table>
				<table cellSpacing=0 cellPadding=0 width=150 border=0>

					<tr height=22>
						<td style="padding-left: 30px" background=/images/menu_bt.jpg><a
							class=menuParent onclick=expand(1) href="javascript:void(0);">关于我们</a>
						</td>
					</tr>
					<tr height=4>
						<td></td>
					</tr>
				</table>
				<table id=child1 style="DISPLAY: none" cellSpacing=0 cellPadding=0
					width=150 border=0>
					<tr height=20>
						<td align=middle width=30><IMG height=9 src="/images/menu_icon.gif"
							width=9></td>
						<td><a class=menuChild href="#" target="main">公司简介</a></td>
					</tr>
					<tr height=20>
						<td align=middle width=30><IMG height=9 src="/images/menu_icon.gif"
							width=9></td>
						<td><a class=menuChild href="#" target=main>荣誉资质</a></td>
					</tr>
					<tr height=20>
						<td align=middle width=30><IMG height=9 src="/images/menu_icon.gif"
							width=9></td>
						<td><a class=menuChild href="#" target=main>分类管理</a></td>
					</tr>
					<tr height=20>
						<td align=middle width=30><IMG height=9 src="/images/menu_icon.gif"
							width=9></td>
						<td><a class=menuChild href="#" target=main>子类管理</a></td>
					</tr>
					<tr height=4>
						<td colSpan=2></td>
					</tr>
				</table>
				<table cellSpacing=0 cellPadding=0 width=150 border=0>
					<tr height=22>
						<td style="padding-left: 30px" background=/images/menu_bt.jpg><a
							class=menuParent onclick=expand(2) href="javascript:void(0);">新闻中心</a>
						</td>
					</tr>
					<tr height=4>
						<td></td>
					</tr>
				</table>
				<table id=child2 style="DISPLAY: none" cellSpacing=0 cellPadding=0
					width=150 border=0>
					<tr height=20>
						<td align=middle width=30><IMG height=9 src="/images/menu_icon.gif"
							width=9></td>
						<td><a class=menuChild href="#" target=main>公司新闻</a></td>
					</tr>
					<tr height=20>
						<td align=middle width=30><IMG height=9 src="/images/menu_icon.gif"
							width=9></td>
						<td><a class=menuChild href="#" target=main>分类管理</a></td>
					</tr>
					<tr height=20>
						<td align=middle width=30><IMG height=9 src="/images/menu_icon.gif"
							width=9></td>
						<td><a class=menuChild href="#" target=main>子类管理</a></td>
					</tr>
					<tr height=4>
						<td colSpan=2></td>
					</tr>
				</table>
				<table cellSpacing=0 cellPadding=0 width=150 border=0>
					<tr height=22>
						<td style="padding-left: 30px" background=/images/menu_bt.jpg><a
							class=menuParent onclick=expand(3) href="javascript:void(0);">产品中心</a>
						</td>
					</tr>
					<tr height=4>
						<td></td>
					</tr>
				</table>
				<table id=child3 style="DISPLAY: none" cellSpacing=0 cellPadding=0
					width=150 border=0>
					<tr height=20>
						<td align=middle width=30><IMG height=9 src="/images/menu_icon.gif"
							width=9></td>
						<td><a class=menuChild href="#" target=main>产品展示</a></td>
					</tr>
					<tr height=20>
						<td align=middle width=30><IMG height=9 src="/images/menu_icon.gif"
							width=9></td>
						<td><a class=menuChild href="#" target=main>最新产品</a></td>
					</tr>
					<tr height=20>
						<td align=middle width=30><IMG height=9 src="/images/menu_icon.gif"
							width=9></td>
						<td><a class=menuChild href="#" target=main>分类管理</a></td>
					</tr>
					<tr height=20>
						<td align=middle width=30><IMG height=9 src="/images/menu_icon.gif"
							width=9></td>
						<td><a class=menuChild href="#" target=main>子类管理</a></td>
					</tr>
					<tr height=4>
						<td colSpan=2></td>
					</tr>
				</table>
				<table cellSpacing=0 cellPadding=0 width=150 border=0>
					<tr height=22>
						<td style="padding-left: 30px" background=/images/menu_bt.jpg><a
							class=menuParent onclick=expand(4) href="javascript:void(0);">客户服务</a>
						</td>
					</tr>
					<tr height=4>
						<td></td>
					</tr>
				</table>
				<table id=child4 style="DISPLAY: none" cellSpacing=0 cellPadding=0
					width=150 border=0>
					<tr height=20>
						<td align=middle width=30><IMG height=9 src="/images/menu_icon.gif"
							width=9></td>
						<td><a class=menuChild href="#" target=main>客户服务</a></td>
					</tr>
					<tr height=20>
						<td align=middle width=30><IMG height=9 src="/images/menu_icon.gif"
							width=9></td>
						<td><a class=menuChild href="#" target=main>分类管理</a></td>
					</tr>
					<tr height=20>
						<td align=middle width=30><IMG height=9 src="/images/menu_icon.gif"
							width=9></td>
						<td><a class=menuChild href="#" target=main>子类管理</a></td>
					</tr>
					<tr height=4>
						<td colSpan=2></td>
					</tr>
				</table>
				<table cellSpacing=0 cellPadding=0 width=150 border=0>
					<tr height=22>
						<td style="padding-left: 30px" background=/images/menu_bt.jpg><a
							class=menuParent onclick=expand(5) href="javascript:void(0);">经典案例</a>
						</td>
					</tr>
					<tr height=4>
						<td></td>
					</tr>
				</table>
				<table id=child5 style="DISPLAY: none" cellSpacing=0 cellPadding=0
					width=150 border=0>

					<tr height=20>
						<td align=middle width=30><IMG height=9 src="/images/menu_icon.gif"
							width=9></td>
						<td><a class=menuChild href="#" target=main>分类管理</a></td>
					</tr>
					<tr height=20>
						<td align=middle width=30><IMG height=9 src="/images/menu_icon.gif"
							width=9></td>
						<td><a class=menuChild href="#" target=main>子类管理</a></td>
					</tr>
					<tr height=4>
						<td colSpan=2></td>
					</tr>
				</table>
				<table cellSpacing=0 cellPadding=0 width=150 border=0>

					<tr height=22>
						<td style="padding-left: 30px" background=/images/menu_bt.jpg><a
							class=menuParent onclick=expand(6) href="javascript:void(0);">高级管理</a>
						</td>
					</tr>
					<tr height=4>
						<td></td>
					</tr>
				</table>
				<table id=child6 style="DISPLAY: none" cellSpacing=0 cellPadding=0
					width=150 border=0>

					<tr height=20>
						<td align=middle width=30><IMG height=9 src="/images/menu_icon.gif"
							width=9></td>
						<td><a class=menuChild href="#" target=main>广告管理</a></td>
					</tr>
					<tr height=20>
						<td align=middle width=30><IMG height=9 src="/images/menu_icon.gif"
							width=9></td>
						<td><a class=menuChild href="#" target=main>访问统计</a></td>
					</tr>
					<tr height=20>
						<td align=middle width=30><IMG height=9 src="/images/menu_icon.gif"
							width=9></td>
						<td><a class=menuChild href="#" target=main>邮件发送设置</a></td>
					</tr>
					<tr height=20>
						<td align=middle width=30><IMG height=9 src="/images/menu_icon.gif"
							width=9></td>
						<td><a class=menuChild href="#" target=main>联系部门</a></td>
					</tr>
					<tr height=20>
						<td align=middle width=30><IMG height=9 src="/images/menu_icon.gif"
							width=9></td>
						<td><a class=menuChild href="#" target=main>用户留言</a></td>
					</tr>
					<tr height=20>
						<td align=middle width=30><IMG height=9 src="/images/menu_icon.gif"
							width=9></td>
						<td><a class=menuChild href="#" target=main>招聘职位</a></td>
					</tr>
					<tr height=20>
						<td align=middle width=30><IMG height=9 src="/images/menu_icon.gif"
							width=9></td>
						<td><a class=menuChild href="#" target=main>应聘人员</a></td>
					</tr>
					<tr height=20>
						<td align=middle width=30><IMG height=9 src="/images/menu_icon.gif"
							width=9></td>
						<td><a class=menuChild href="#" target=main>留言簿</a></td>
					</tr>
					<tr height=20>
						<td align=middle width=30><IMG height=9 src="/images/menu_icon.gif"
							width=9></td>
						<td><a class=menuChild href="#" target=main>产品订购</a></td>
					</tr>
					<tr height=20>
						<td align=middle width=30><IMG height=9 src="/images/menu_icon.gif"
							width=9></td>
						<td><a class=menuChild href="#" target=main>链接管理</a></td>
					</tr>
					<tr height=20>
						<td align=middle width=30><IMG height=9 src="/images/menu_icon.gif"
							width=9></td>
						<td><a class=menuChild href="#" target=main>文件管理</a></td>
					</tr>
					<tr height=20>
						<td align=middle width=30><IMG height=9 src="/images/menu_icon.gif"
							width=9></td>
						<td><a class=menuChild href="#" target=main>信息转移</a></td>
					</tr>
					<tr height=4>
						<td colSpan=2></td>
					</tr>
				</table>
				<table cellSpacing=0 cellPadding=0 width=150 border=0>

					<tr height=22>
						<td style="padding-left: 30px" background=/images/menu_bt.jpg><a
							class=menuParent onclick=expand(7) href="javascript:void(0);">系统管理</a>
						</td>
					</tr>
					<tr height=4>
						<td></td>
					</tr>
				</table>
				<table id=child7 style="DISPLAY: none" cellSpacing=0 cellPadding=0
					width=150 border=0>

					<tr height=20>
						<td align=middle width=30><IMG height=9 src="/images/menu_icon.gif"
							width=9></td>
						<td><a class=menuChild href="#" target=main>基本设置</a></td>
					</tr>
					<tr height=20>
						<td align=middle width=30><IMG height=9 src="/images/menu_icon.gif"
							width=9></td>
						<td><a class=menuChild href="#" target=main>样式管理</a></td>
					</tr>
					<tr height=20>
						<td align=middle width=30><IMG height=9 src="/images/menu_icon.gif"
							width=9></td>
						<td><a class=menuChild href="#" target=main>栏目管理</a></td>
					</tr>
					<tr height=20>
						<td align=middle width=30><IMG height=9 src="/images/menu_icon.gif"
							width=9></td>
						<td><a class=menuChild href="#" target=main>功能管理</a></td>
					</tr>
					<tr height=20>
						<td align=middle width=30><IMG height=9 src="/images/menu_icon.gif"
							width=9></td>
						<td><a class=menuChild href="#" target=main>菜单管理</a></td>
					</tr>
					<tr height=20>
						<td align=middle width=30><IMG height=9 src="/images/menu_icon.gif"
							width=9></td>
						<td><a class=menuChild href="#" target=main>首页设置</a></td>
					</tr>
					<tr height=20>
						<td align=middle width=30><IMG height=9 src="/images/menu_icon.gif"
							width=9></td>
						<td><a class=menuChild href="#" target=main>管理员列表</a></td>
					</tr>
					<tr height=4>
						<td colSpan=2></td>
					</tr>
				</table>
				<table cellSpacing=0 cellPadding=0 width=150 border=0>

					<tr height=22>
						<td style="padding-left: 30px" background=/images/menu_bt.jpg><a
							class=menuParent onclick=expand(0) href="javascript:void(0);">个人管理</a>
						</td>
					</tr>
					<tr height=4>
						<td></td>
					</tr>
				</table>
				<table id=child0 style="DISPLAY: none" cellSpacing=0 cellPadding=0
					width=150 border=0>

					<tr height=20>
						<td align=middle width=30><IMG height=9 src="/images/menu_icon.gif"
							width=9></td>
						<td><a class=menuChild href="#" target=main>修改口令</a></td>
					</tr>
					<tr height=20>
						<td align=middle width=30><IMG height=9 src="/images/menu_icon.gif"
							width=9></td>
						<td><a class=menuChild
							onclick="if (confirm('确定要退出吗？')) return true; else return false;"
							href="http://www.865171.cn" target=_top>退出系统</a></td>
					</tr>
				</table></td>
			<td width=1 bgColor=#d1e6f7></td>
		</tr>
	</table>
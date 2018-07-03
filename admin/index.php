<?php
/**
 * 이 파일은 iModule Todolist 모듈의 일부입니다. (https://www.imodule.kr)
 *
 * Todolist 모듈 관리자패널을 구성한다.
 * 
 * @file /modules/todolist/admin/index.php
 * @author Eunseop Lim (dmstjq12@naver.com)
 * @license MIT License
 * @version 0.1.0
 * @modified 2018. 7. 3.
 */
if (defined('__IM__') == false) exit;
?>
<script>
Ext.onReady(function() { Ext.getCmp("iModuleAdminPanel").add(
	new Ext.TabPanel({
		id: "ModuleTodolist",
		border: false,
		tabPosition: "bottom",
		items: [
			new Ext.grid.Panel({
				id: "ModuleTodolistList",
				title: Todolist.getText("admin/list/title"),
				border: false,
				tbar: [
					new Ext.Button({
						text: Todolist.getText("admin/list/addTodo"),
						iconCls: "fa fa-plus",
						handler: function() {
							Todolist.list.add();
						}
					}),
					new Ext.Button({
						text: Todolist.getText("admin/list/editTodo"),
						iconCls: "fa fa-pencil",
						handler: function() {
							Todolist.list.edit();
						}
					}),
					new Ext.Button({
						text: Todolist.getText("admin/list/removeTodo"),
						iconCls: "mi mi-trash",
						handler: function() {
							Todolist.list.remove();
						}
					})
				],
				store: new Ext.data.JsonStore(),
				columns: [],
				selModel: new Ext.selection.CheckboxModel(),
				bbar: new Ext.PagingToolbar(),
				listeners: {}
			})
		]
	})
)});
</script>
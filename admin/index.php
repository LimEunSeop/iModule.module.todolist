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
				id: "ModuleAdminTodolist",
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
				store: new Ext.data.JsonStore({
					proxy: {
						type: "ajax",
						simpleSortMode: true,
						url: ENV.getProcessUrl("todolist", "@getAdminTodolists"),
						reader: {type:"json"}
					},
					remoteSort: true,
					sorters: [{property:"idx",direction:"ASC"}],
					autoLoad: true,
					pageSize: 50,
					fields: ["id", "taskname", "mem_cnt", "mem_comp_cnt"],
					listeners: {
						load: function(store, records, success, e) {
							if (success == false) {
								if (e.getError()) {
									Ext.Msg.show({title:Admin.getText("alert/error"),msg:e.getError(),buttons:Ext.Msg.OK,icon:Ext.Msg.ERROR});
								} else {
									Ext.Msg.show({title:Admin.getText("alert/error"),msg:Admin.getText("error/load"),buttons:Ext.Msg.OK,icon:Ext.Msg.ERROR});
								}
							}
						}
					}
				}),
				columns: [{
					text: Todolist.getText("admin/list/columns/idx"),
					width: 80,
					dataIndex: "id",
					sortable: true
				}, {
					text: Todolist.getText("admin/list/columns/taskname"),
					width: 350,
					dataIndex: "taskname",
					sortable: true,
				}, {
					text: Todolist.getText("admin/list/columns/mem_cnt"),
					width: 120,
					dataIndex: "mem_cnt",
					sortable: true
				}, {
					text: Todolist.getText("admin/list/columns/mem_comp_cnt"),
					width: 120,
					dataIndex: "mem_comp_cnt",
					sortable: true,
					renderer: function(value, p) {
						if (value == 0) {
							p.style = "text-align:center;";
							return "-";
						}
						return Ext.util.Format.number(value, "0,000");
					}
				}, {
					text: Todolist.getText("admin/list/columns/percentage"),
					width: 80,
					sortable: true,
					renderer: function(value, p) {
						var mem_cnt = parseInt(this.getDataIndex("mem_cnt"));
						var mem_comp_cnt = parseInt(this.getDataIndex("mem_comp_cnt"));
						var res = mem_comp_cnt / mem_cnt;

						if (res <= 0) {
							p.style = "text-align:center;";
							return "-";
						}
						return Ext.util.Format.percent(res);
					}
				}],
				selModel: new Ext.selection.CheckboxModel(),
				bbar: new Ext.PagingToolbar({
					store: null,
					displayInfo: false,
					items: [
						"->",
						{xtype:"tbtext",text:"항목 더블클릭 : 상세보기"}
					],
					listeners: {
						beforerender: function(tool) {
							tool.bindStore(Ext.getCmp("ModuleAdminTodolist").getStore());
						}
					}
				}),
				listeners: {
					itemdblclick: function(grid, record) {
						Todolist.list.view(record.data.idx, record.data.taskname);
					}
				}
			})
		]
	})
)});
</script>
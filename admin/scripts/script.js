/**
 * 이 파일은 iModule Todolist 모듈의 일부입니다. (https://www.imodule.kr)
 *
 * Todolist 관리자 UI를 처리한다.
 * 
 * @file /modules/todolist/admin/scripts/script.js
 * @author Eunseop Lim (dmstjq12@naver.com)
 * @license MIT License
 * @version 0.1.0
 * @modified 2018. 7. 3.
 */
var Todolist = {
	/**
	 * Todolist 목록관리
	 */
	list:{
		/**
		 * Todolist 추가/수정/삭제
		 *
		 * @param string id Todolist 관리 아이디 (없을 경우 추가)
		 */
		add: function(admin_idx) {
			new Ext.Window({
				id: "ModuleTodolistAddTodoWindow",
				title: (admin_idx ? Todolist.getText("admin/list/window/modify") : Todolist.getText("admin/list/window/add")),
				modal: true,
				width: 750,
				border: false,
				scrollable: true,
				items: [
					new Ext.form.Panel({
						id: "ModuleTodolistAddTodoForm",
						border: false,
						bodyPadding: "10 10 0 10",
						fieldDefaults: {labelAlign:"right",labelWidth:100,anchor:"100%",allowBlank:false},
						items: [
							new Ext.form.Hidden({
								name: "admin_idx",
								value: (admin_idx ? admin_idx : null)
							}),
							new Ext.form.Hidden({
								name: "mode",
								value: (admin_idx ? "modify" : "add")
							}),
							new Ext.form.FieldSet({
								collapsible: true,
								collapsed: false,
								title: Todolist.getText("admin/list/form/default_setting"),
								items: [
									new Ext.form.TextField({
										fieldLabel: Todolist.getText("admin/list/form/taskname"),
										name: "taskname",
										maxLength: 50
									})
								]
							}),
							new Ext.form.Hidden({
								name: "mem_indices"
							}),
							new Ext.form.FieldSet({
								collapsible: true,
								collapsed: false,
								title: Todolist.getText("admin/list/form/member_selection"),
								items: [
									new Ext.grid.Panel({
										id: "ModuleTodolistMemberSelectionList",
										border: true,
										tbar: [],
										store: new Ext.data.JsonStore({
											proxy: {
												type: "ajax",
												simpleSortMode: true,
												url: ENV.getProcessUrl("member", "@getMembers"),
												reader: {type:"json"}
											},
											remoteSort: true,
											sorters: [{property:"idx",direction:"DESC"}],
											autoLoad: true,
											// pageSize: 5,
											fields: ["idx", "name", "nickname", "email", "reg_date"],
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
											text: "idx",
											width: 80,
											dataIndex: "idx",
											sortable: true
										}, {
											text: Member.getText("admin/list/columns/name"),
											width: 125,
											dataIndex: "name",
											sortable: true,
											align: "center"
										}, {
											text: Member.getText("admin/list/columns/nickname"),
											width: 125,
											dataIndex: "nickname",
											sortable: true,
											align: "center"
										}, {
											text: Member.getText("admin/list/columns/email"),
											width: 200,
											dataIndex: "email",
											align: "right"
										}, {
											text: Member.getText("admin/list/columns/reg_date"),
											width: 130,
											align: "center",
											dataIndex: "reg_date",
											sortable: true,
											renderer: function(value) {
												return value > 0 ? moment(value * 1000).format("YYYY-MM-DD HH:mm") : "-";
											}
										}],
										selModel: new Ext.selection.CheckboxModel()
									})
								]
							})
						]
					})
				],
				buttons: [
					new Ext.Button({
						text: Todolist.getText("button/confirm"),
						handler: function() {
							var selectedMembers = Ext.getCmp("ModuleTodolistMemberSelectionList").getSelectionModel().getSelection();
							var selectedIdx = [];
							for (var i = 0; i < selectedMembers.length; i++) {
								selectedIdx.push(selectedMembers[i].data.idx);
							}
							
							Ext.getCmp("ModuleTodolistAddTodoForm").getForm().findField("mem_indices").setValue(JSON.stringify(selectedIdx));

							// admin_idx, mode, taskname, mem_idx 를 파라미터로 던진다.
							Ext.getCmp("ModuleTodolistAddTodoForm").getForm().submit({
								url: ENV.getProcessUrl("todolist", "@saveAdminTodo"),
								submitEmptyText: false,
								waitTitle: Admin.getText("action/wait"),
								waitMsg: Admin.getText("action/saving"),
								success: function(form, action) {
									Ext.Msg.show({title:Admin.getText("alert/info"),msg:Admin.getText("action/saved"),buttons:Ext.Msg.OK,icon:Ext.Msg.INFO,fn:function(button) {
										Ext.getCmp("ModuleTodolistAddTodoWindow").close();
										Ext.getCmp("ModuleAdminTodolist").getStore().reload();
									}});
								},
								failure: function(form, action) {
									if (action.result) {
										if (action.result.message) {
											Ext.Msg.show({title:Admin.getText("alert/error"),msg:action.result.message,buttons:Ext.Msg.OK,icon:Ext.Msg.ERROR});
										} else {
											Ext.Msg.show({title:Admin.getText("alert/error"),msg:Admin.getText("error/save"),buttons:Ext.Msg.OK,icon:Ext.Msg.ERROR});
										}
									} else {
										Ext.Msg.show({title:Admin.getText("alert/error"),msg:Admin.getText("error/form"),buttons:Ext.Msg.OK,icon:Ext.Msg.ERROR});
									}
								}
							})
						}
					}),
					new Ext.Button({
						text: Todolist.getText("button/cancel"),
						handler: function() {
							Ext.getCmp("ModuleTodolistAddTodoWindow").close();
						}
					})
				],
				listeners: {
					show: function() {
						if (admin_idx !== undefined) {
							Ext.getCmp("ModuleTodolistAddTodoForm").getForm().load({
								url: ENV.getProcessUrl("todolist", "@getAdminTodo"),
								params: {idx:admin_idx},
								waitTitle: Admin.getText("action/wait"),
								waitMsg: Admin.getText("action/loading"),
								success: function(form, action) {
									
								},
								failure:function(form,action) {
									if (action.result && action.result.message) {
										Ext.Msg.show({title:Admin.getText("alert/error"),msg:action.result.message,buttons:Ext.Msg.OK,icon:Ext.Msg.ERROR});
									} else {
										Ext.Msg.show({title:Admin.getText("alert/error"),msg:Admin.getText("error/load"),buttons:Ext.Msg.OK,icon:Ext.Msg.ERROR});
									}
									Ext.getCmp("ModuleTodolistAddTodoWindow").close();
								}
							})
						}
					}
				}
			}).show();
		},
		edit: function(idx) {
			Todolist.list.add(idx);
		},
		view: function(idx, title) {
			new Ext.Window({
				id: "ModuleTodolistViewMembersWindow",
				title: title + Todolist.getText("admin/list/view/title"),
				modal: true,
				width: 950,
				height: 600,
				border: false,
				layout: "fit",
				maximizable: true,
				items: [
					new Ext.grid.Panel({
						id: "ModuleTodolistViewMembersGrid",
						border: true,
						tbar: [],
						store: new Ext.data.JsonStore({
							proxy: {
								type: "ajax",
								simpleSortMode: true,
								url: ENV.getProcessUrl("todolist", "@getTodoMembers"),
								extraParams: {
									idx: idx
								},
								reader: {type:"json"}
							},
							remoteSort: true,
							sorters: [{property:"idx",direction:"DESC"}],
							autoLoad: true,
							pageSize: 50,
							fields: ["idx", "name", "nickname", "email", "reg_date", "complete"],
							listeners: {
								load: function(store, records, success, e) {
									if (success == false) {
										if (e.getError()) {
											Ext.Msg.show({title:Admin.getText("alert/error"),msg:e.getError(),buttons:Ext.Msg.OK,icon:Ext.Msg.Error});
										} else {
											Ext.Msg.show({title:Admin.getText("alert/error"),msg:Admin.getText("error/load"),buttons:Ext.Msg.OK,icon:Ext.Msg.ERROR});
										}
									}
								}
							}
						}),
						columns: [{
							text: "idx",
							width: 80,
							dataIndex: "idx",
							sortable: true
						}, {
							text: Member.getText("admin/list/columns/name"),
							width: 125,
							dataIndex: "name",
							sortable: true,
							align: "center"
						}, {
							text: Member.getText("admin/list/columns/nickname"),
							width: 125,
							dataIndex: "nickname",
							sortable: true,
							align: "center"
						}, {
							text: Member.getText("admin/list/columns/email"),
							width: 200,
							dataIndex: "email",
							align: "right"
						}, {
							text: Member.getText("admin/list/columns/reg_date"),
							width: 130,
							align: "center",
							dataIndex: "reg_date",
							sortable: true,
							renderer: function(value) {
								return value > 0 ? moment(value * 1000).format("YYYY-MM-DD HH:mm") : "-";
							}
						}, {
							text: Todolist.getText("admin/list/columns/complete"),
							width: 70,
							dataIndex: "complete",
							sortable: true,
							align: "center"
						}, {
							text: Todolist.getText("admin/list/columns/comp_date"),
							width: 130,
							align: "center",
							dataIndex: "comp_date",
							sortable: true,
							renderer: function(value, metaData, record, rowIndex, colIndex, store) {
								return (value > 0 && record.data.complete == "YES") ? moment(value * 1000).format("YYYY-MM-DD HH:mm") : "-";
							}
						}],
						bbar: new Ext.PagingToolbar({
							store: null,
							displayInfo: false,
							listeners: {
								beforerender: function(tool) {
									tool.bindStore(Ext.getCmp("ModuleTodolistViewMembersGrid").getStore());
								}
							}
						})
					})
				]

			}).show();
		},
		remove: function(idx) {
			new Ext.Window().show();
		}
	}
};
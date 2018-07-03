<?php
/**
 * 이 파일은 iModule todolist 모듈의 일부입니다. (https://www.imodule.kr)
 *
 * todolist 모듈 설정패널
 * 
 * @file /modules/todolist/admin/configs.php
 * @author Eunseop Lim (dmstjq12@naver.com)
 * @license GPLv3
 * @version 0.1.0
 * @modified 2018. 4. 29.
 */
if (defined('__IM__') == false) exit;
?>
<script>
var config = new Ext.form.Panel({
	id:"ModuleConfigForm",
	border:false,
	bodyPadding:"10 10 5 10",
	width:500,
	fieldDefaults:{labelAlign:"right",labelWidth:80,anchor:"100%",allowBlank:true},
	items:[
		new Ext.form.FieldSet({
			title:Todolist.getText("admin/configs/form/default_setting"),
			items:[
				Admin.templetField(Todolist.getText("admin/configs/form/templet"),"templet","todolist",false)
			]
		})
	]
});
</script>
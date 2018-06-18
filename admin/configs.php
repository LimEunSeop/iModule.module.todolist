<?php
/**
 * 이 파일은 iModule 모듈예제 #1 의 일부입니다. (https://www.imodule.kr)
 *
 * 캘린더모듈 설정패널
 * 
 * @file /modules/example1/admin/configs.php
 * @author Arzz (arzz@arzz.com)
 * @license GPLv3
 * @version 3.0.0
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
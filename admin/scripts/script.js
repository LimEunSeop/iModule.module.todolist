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
		add: function(id) {
			new Ext.Window().show();
		},
		edit: function(id) {
			new Ext.Window().show();
		},
		remove: function(id) {
			new Ext.Window().show();
		}
	}
};
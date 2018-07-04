<?php
/**
 * 이 파일은 iModule Todolist 모듈의 일부입니다. (https://www.imodule.kr)
 * 
 * 관리패널의 할일 추가 Window에서 Admin이 만든 Todolist를 추가하거나 변경한다.
 *
 * @file /modules/todolist/process/@saveAdminTodo.php
 * @author Eunseop Lim (dmstjq12@naver.com)
 * @license GPLv3
 * @version 0.1.0
 * @modified 2018. 7. 4.
 */
if (defined('__IM__') == false) exit;

// admin_idx, mode, taskname, mem_idx 를 파라미터로 받는다.
$admin_idx = Request('admin_idx');
$mode = Request('mode');
$taskname = Request('taskname');
$mem_idx = json_decode(Request('mem_idx'));

if ($mode == 'add') {
    $this->db()->insert($this->table->admin_todolist, array('taskname'=>$taskname))->execute();
} else if ($mode == 'modify') {

}
?>
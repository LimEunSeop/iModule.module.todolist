<?php
/**
 * 이 파일은 iModule Todolist 모듈의 일부입니다. (https://www.imodule.kr)
 * 
 * Admin 패널의 일 하나를 삭제한다.
 *
 * @file /modules/todolist/process/@deleteAdminTodo.php
 * @author Eunseop Lim (dmstjq12@naver.com)
 * @license GPLv3
 * @version 0.1.0
 * @modified 2018. 7. 6.
 */
if (defined('__IM__') == false) exit;

$idx = Request('idx');

$this->db()->startTransaction();
$this->db()->delete($this->table->admin_todolist)->where('idx', $idx)->execute();
$this->db()->delete($this->table->todolist)->where('admin_idx', $idx)->execute();
$this->db()->commit();

$results->success = true;
?>
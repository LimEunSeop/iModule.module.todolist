<?php
/**
 * 이 파일은 iModule todolist 모듈의 일부입니다. (https://www.imodule.kr)
 * 
 * 할 일을 추가한다.
 *
 * @file /modules/todolist/process/add.php
 * @author Eunseop Lim (dmstjq12@naver.com)
 * @license MIT License
 * @version 0.0.5
 * @modified 2018. 6. 22.
 */

if (defined('__IM__') == false) exit;

$taskname = Request('item');
if ($taskname == "") {
    return;
}

$insert['mem_idx'] = $this->IM->getModule('member')->getMember()->idx;
$insert['taskname'] = $taskname;
$insert['reg_date'] = time();

$idx = $this->db()->insert($this->table->todolist, $insert)->execute();

$results->success = true;
?>
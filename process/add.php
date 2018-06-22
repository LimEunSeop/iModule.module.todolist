<?php
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
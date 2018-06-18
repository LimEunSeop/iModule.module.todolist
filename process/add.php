<?php
if (defined('__IM__') == false) exit;

$taskname = Request('item');
if ($taskname == "") {
    return;
}

$insert['taskname'] = $taskname;
$insert['reg_date'] = time();

$idx = $this->db()->insert($this->table->todolist, $insert)->execute();
if ($idx === false) {
    $results->success = false;
    $results->message = $this->getErrorText('DATABASE_INSERT_ERROR');
    return;
}
?>
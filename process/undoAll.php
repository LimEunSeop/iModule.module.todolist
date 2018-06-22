<?php

if (defined('__IM__') == false) exit;

$this->db()->update($this->table->todolist, array('complete'=>'NO'))->execute();
$results->success = true;

?>
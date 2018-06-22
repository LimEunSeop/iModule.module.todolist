<?php

if (defined('__IM__') == false) exit;

$this->db()->delete($this->table->todolist)->where('complete','YES')->execute();

$results->success = true;

?>
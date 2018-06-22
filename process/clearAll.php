<?php

if (defined('__IM__') == false) exit;

$success = $this->db()->delete($this->table->todolist)->execute();

$results->success = true;

?>
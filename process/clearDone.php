<?php

if (defined('__IM__') == false) exit;

$success = $this->db()->delete($this->table->todolist)->where('complete','YES')->execute();

if ($success == true) {
    $results->success = true;
} else {
    $results->success = false;
    return;
}

?>
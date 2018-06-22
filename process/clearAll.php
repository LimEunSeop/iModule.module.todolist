<?php

if (defined('__IM__') == false) exit;

$success = $this->db()->delete($this->table->todolist)->execute();

if ($success == true) {
    $results->success = true;
} else {
    $results->success = false;
    return;
}

?>
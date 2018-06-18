<?php

if (defined('__IM__') == false) exit;

// 성공실패여부 판별하기 애매하다. 쿼리가 잘못되면 내부에서 프로그램이 종료돼버린다.
$success = $this->db()->update($this->table->todolist, array('complete'=>'YES', 'comp_date'=>time()))->execute();

if ($success == true) {
    $results->success = true;
} else {
    $results->success = false;
    $results->message = $this->getErrorText('doAll : DATABASE_SET_ERROR');
    return;
}

?>
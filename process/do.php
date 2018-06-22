<?php

if (defined('__IM__') == false) exit;

$idx = Request('idx');
$comp_date = time();

// 현재는 rawQuery 함수에 리턴값이 없어서 성공실패여부 판별할 수가 없다. 쿼리가 잘못되면 내부에서 프로그램이 종료돼버린다.
$this->db()->rawQuery("UPDATE ".__IM_DB_PREFIX__.$this->table->todolist." SET complete = IF(complete='YES', 'NO', 'YES'), comp_date = ".$comp_date." WHERE idx = ".$idx);
$results->success = true;
$results->comp_date = GetTime("Y-m-d H:i:s", $comp_date); // PHP 상에서 포메팅 후 반환하기.
?>
<?php

if (defined('__IM__') == false) exit;

// 성공실패여부 판별하기 애매하다. 쿼리가 잘못되면 내부에서 프로그램이 종료돼버린다.
$comp_date = time();
$success = $this->db()->update($this->table->todolist, array('complete'=>'YES', 'comp_date'=>$comp_date))->execute();

$results->success = true;
$results->comp_date = GetTime("Y-m-d H:i:s", $comp_date); // 포메팅을 PHP 차원에서 해주고 반환한다.

?>
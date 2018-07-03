<?php
/**
 * 이 파일은 iModule todolist 모듈의 일부입니다. (https://www.imodule.kr)
 * 
 * 모든 작업에 완료표시를 한다.
 *
 * @file /modules/todolist/process/doAll.php
 * @author Eunseop Lim (dmstjq12@naver.com)
 * @license MIT License
 * @version 0.1.0
 * @modified 2018. 6. 22.
 */

if (defined('__IM__') == false) exit;

$memberModule = $this->IM->getModule('member');
$logged = $memberModule->isLogged();

if (!$logged) {
    $results->success = false;
    $results->message = 'NOT LOGGED';
    return;
}

$comp_date = time();
$this->db()->update($this->table->todolist, array('complete'=>'YES', 'comp_date'=>$comp_date))->execute();

$results->success = true;
$results->comp_date = GetTime("Y-m-d H:i:s", $comp_date); // 포메팅을 PHP 차원에서 해주고 반환한다.

?>
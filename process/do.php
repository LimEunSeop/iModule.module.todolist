<?php
/**
 * 이 파일은 iModule todolist 모듈의 일부입니다. (https://www.imodule.kr)
 * 
 * 작업에 완료표시를 한다.
 *
 * @file /modules/todolist/process/do.php
 * @author Eunseop Lim (dmstjq12@naver.com)
 * @license MIT License
 * @version 0.0.5
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

$idx = Request('idx');
$prevState = Request('isCompleted');

if (in_array($prevState, array('YES', 'NO') == false)) {
    $results->success = false;
    $results->message = $this->getErrorText('INVALID_VALUE');
    return;
}

$nextState = $prevState == 'YES' ? 'NO' : 'YES';
$comp_date = time();

// 현재는 rawQuery 함수에 리턴값이 없어서 성공실패여부 판별할 수가 없다. 쿼리가 잘못되면 내부에서 프로그램이 종료돼버린다.
$this->db()->update($this->table->todolist, array('complete'=>$nextState, 'comp_date'=>$comp_date))->where('idx', $idx)->execute();

$results->success = true;
$results->comp_date = GetTime("Y-m-d H:i:s", $comp_date); // PHP 상에서 포메팅 후 반환하기.
?>
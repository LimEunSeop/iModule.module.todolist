<?php
/**
 * 이 파일은 iModule todolist 모듈의 일부입니다. (https://www.imodule.kr)
 * 
 * 등록된 모든 작업을 삭제한다.
 *
 * @file /modules/todolist/process/clearAlls.php
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

$success = $this->db()->delete($this->table->todolist)->execute();

$results->success = true;

?>
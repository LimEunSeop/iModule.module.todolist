<?php
/**
 * 이 파일은 iModule todolist 모듈의 일부입니다. (https://www.imodule.kr)
 * 
 * 완료된 작업을 삭제한다.
 *
 * @file /modules/todolist/process/clearDone.php
 * @author Eunseop Lim (dmstjq12@naver.com)
 * @license MIT License
 * @version 0.0.5
 * @modified 2018. 6. 22.
 */

if (defined('__IM__') == false) exit;

$this->db()->delete($this->table->todolist)->where('complete','YES')->execute();

$results->success = true;

?>
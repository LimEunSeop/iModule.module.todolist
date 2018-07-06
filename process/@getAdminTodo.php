<?php
/**
 * 이 파일은 iModule Todolist 모듈의 일부입니다. (https://www.imodule.kr)
 * 
 * AdminTodo 정보 하나를 불러온다 (할일 이름, 선택된 멤버 인덱스)
 *
 * @file /modules/board/process/@getBoard.php
 * @author Eunseop Lim (dmstjq12@naver.com)
 * @license GPLv3
 * @version 3.0.0
 * @modified 2018. 7. 6.
 */
if (defined('__IM__') == false) exit;

$idx = Request('idx');
$data = new stdClass();
$data->taskname = $this->db()->select($this->table->admin_todolist)->where('idx',$idx)->getOne()->taskname;

if ($data == null) {
	$results->success = false;
	$results->message = $this->getErrorText('NOT_FOUND');
} else {
    $registeredMembers = $this->db()->select($this->table->todolist)->where('admin_idx', $idx)->get();
    
    $data->registeredMemberIndices = array();
    foreach ($registeredMembers as $member) {
        array_push($data->registeredMemberIndices, $member->mem_idx);
    }
    
    $data->registeredMemberIndices = json_encode($data->registeredMemberIndices);
    
	$results->success = true;
	$results->data = $data;
}
?>
<?php
/**
 * 이 파일은 iModule Todolist 모듈의 일부입니다. (https://www.imodule.kr)
 * 
 * 관리패널의 할일 추가 Window에서 Admin이 만든 Todolist를 추가하거나 변경한다.
 *
 * @file /modules/todolist/process/@saveAdminTodo.php
 * @author Eunseop Lim (dmstjq12@naver.com)
 * @license GPLv3
 * @version 0.1.0
 * @modified 2018. 7. 4.
 */
if (defined('__IM__') == false) exit;

// admin_idx, mode, taskname, mem_idx 를 파라미터로 받는다.
$admin_idx = Request('admin_idx'); // mode가 add 일 경우에는 세팅되어있지 않는다.
$mode = Request('mode');
$taskname = Request('taskname');
$mem_indices = json_decode(Request('mem_indices'));

// 여기서 값 검증해서 $results->success = false; 후 리턴 해야할듯 싶은데, 아직까지는 별다른 특이점은 없다.

$currentTime = time();

if ($mode == 'add') {

    // todolist_admin_table 삽입 - 리턴값인 새로운 admin idx 를 $idx에 넣어준다.
    $idx = $this->db()->insert($this->table->admin_todolist, array('taskname'=>$taskname, 'reg_date'=>$currentTime))->execute();

    // todolist 삽입
    $this->db()->startTransaction();
    foreach ($mem_indices as $mem_idx) {
        $insert['mem_idx'] = $mem_idx;
        $insert['taskname'] = $taskname;
        $insert['reg_date'] = $currentTime;
        $insert['admin_idx'] = $idx;
        $this->db()->insert($this->table->todolist, $insert)->execute();
    }
    $this->db()->commit();

    $results->success = true;
} else if ($mode == 'modify') {
    $old_mem_indices = json_decode(Request('registeredMemberIndices'));
    $new_mem_indices = $mem_indices;

    // 차집합 구하기 : 예전것에서 없어진것은 DB에서 제거, 예전것에서 새로 추가된 것은 DB에 추가
    $remove_mem_indices = array_values(array_diff($old_mem_indices, $new_mem_indices));
    $add_mem_indices = array_values(array_diff($new_mem_indices, $old_mem_indices));

    $this->db()->startTransaction();
    // 레코드 제가 추가 반영
    foreach ($remove_mem_indices as $remove_mem_idx) {
        $this->db()->delete($this->table->todolist)->where('mem_idx', $remove_mem_idx)->where('admin_idx', $admin_idx)->execute();
    }
    foreach ($add_mem_indices as $add_mem_idx) {
        $insert['mem_idx'] = $add_mem_idx;
        $insert['taskname'] = $taskname;
        $insert['reg_date'] = $currentTime;
        $insert['admin_idx'] = $admin_idx;
        $this->db()->insert($this->table->todolist, $insert)->execute();
    }
    // 할일 이름 수정 반영
    $this->db()->update($this->table->admin_todolist, array('taskname'=>$taskname))->where('idx', $admin_idx)->execute();
    $this->db()->update($this->table->todolist, array('taskname'=>$taskname))->where('admin_idx', $admin_idx)->execute();
    $this->db()->commit();

    $results->success = true;
}
?>
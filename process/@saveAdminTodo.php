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
$admin_idx = Request('admin_idx');
$mode = Request('mode');
$taskname = Request('taskname');
$mem_indices = json_decode(Request('mem_indices'));

// 여기서 값 검증해서 $results->success = false; 후 리턴 해야할듯 싶은데, 아직까지는 별다른 특이점은 없다.

if ($mode == 'add') {
    // todolist_admin_table 삽입 - 리턴값인 새로운 admin idx 를 $idx에 넣어준다.
    $idx = $this->db()->insert($this->table->admin_todolist, array('taskname'=>$taskname))->execute();

    // todolist 삽입
    $this->db()->startTransaction();
    foreach ($mem_indices as $mem_idx) {
        $insert['mem_idx'] = $mem_idx;
        $insert['taskname'] = $taskname;
        $insert['reg_date'] = time();
        $insert['admin_idx'] = $idx;
        $this->db()->insert($this->table->todolist, $insert)->execute();
    }
    $this->db()->commit();

    $results->success = true;
} else if ($mode == 'modify') {

}
?>
<?php
/**
 * 이 파일은 iModule Todolist 모듈의 일부입니다. (https://www.imodule.kr)
 * 
 * 관리패널 Admin이 만든 Todolist 목록을 불러온다.
 *
 * @file /modules/todolist/process/@getAdminTodolists.php
 * @author Eunseop Lim (dmstjq12@naver.com)
 * @license GPLv3
 * @version 0.1.0
 * @modified 2018. 7. 4.
 */
if (defined('__IM__') == false) exit;

/**
 * listWithMemCnt 에는 전체 회원수를 계산, listWithMemCompCnt에는 일을 완료한 회원수를 계산.
 * 이 모든 값을 listWithMemCnt에 할당했다. 새로운 변수 lists 를 만들어 모았을 수도 있겠지만.. 코드가 지저분해질까봐 그냥 이렇게 했다
 */
$start = Request('start');
$limit = Request('limit');
$sort = Request('sort') ? Request('sort') : 'idx';
$dir = Request('dir') ? Request('dir') : 'asc';

// 할일 할당돼있는 전체 회원 수 구하기
$listWithMemCnt = $this->db()->select($this->table->admin_todolist.' a','a.idx, a.taskname, count(b.mem_idx) as mem_cnt, a.reg_date')->join($this->table->todolist.' b','a.idx=b.admin_idx AND b.mem_idx is not NULL','LEFT');
$listWithMemCnt->groupBy('a.idx, a.taskname, a.reg_date')->orderBy($sort,$dir);
$total = $listWithMemCnt->copy()->count(); // admin_todolist 테이블 레코드 갯수
if ($limit > 0) $listWithMemCnt->limit($start,$limit);
$listWithMemCnt = $listWithMemCnt->get();

// 할일을 완성한 회원수 구하기
$listWithMemCompCnt = $this->db()->select($this->table->admin_todolist.' a','a.idx, a.taskname, count(b.complete) as mem_comp_cnt, a.reg_date')->join($this->table->todolist.' b','a.idx=b.admin_idx AND b.complete="YES"','LEFT');
$listWithMemCompCnt->groupBy('a.idx, a.taskname, a.reg_date')->orderBy($sort,$dir);
if ($limit > 0) $listWithMemCompCnt->limit($start,$limit);
$listWithMemCompCnt = $listWithMemCompCnt->get();

// listWithMemCnt 에 모든 값(mem_comp_cnt)을 양도함.
for ($i = 0, $length = count($listWithMemCnt); $i < $length; $i++) {
    $listWithMemCnt[$i]->mem_comp_cnt = $listWithMemCompCnt[$i]->mem_comp_cnt;
}

$results->success = true;
$results->lists = $listWithMemCnt;
$results->total = $total;
?>
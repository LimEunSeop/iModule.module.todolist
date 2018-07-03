<?php
/**
 * 이 파일은 iModule Todolist 모듈의 일부입니다. (https://www.imodule.kr)
 * 
 * 관리패널 Admin이 만든 Todolist 목록을 불러온다.
 *
 * @file /modules/board/process/@getBoards.php
 * @author Arzz (arzz@arzz.com)
 * @license GPLv3
 * @version 3.0.0
 * @modified 2018. 2. 18.
 */
if (defined('__IM__') == false) exit;

/**
 * listWithMemCnt 에는 전체 회원수를 계산, listWithMemCompCnt에는 일을 완료한 회원수를 계산.
 * 이 모든 값을 listWithMemCnt에 할당했다. 새로운 변수 lists 를 만들어 모았을 수도 있겠지만.. 코드가 지저분해질까봐 그냥 이렇게 했다
 */
$start = Request('start');
$limit = Request('limit');
$listWithMemCnt = $this->db()->select($this->table->admin_todolist.' a','a.idx, a.taskname, count(*) as mem_cnt')->join($this->table->todolist.' b','a.idx=b.admin_idx','INNER');
$listWithMemCompCnt = $listWithMemCnt->copy();

$sort = Request('sort') ? Request('sort') : 'idx';
$dir = Request('dir') ? Request('dir') : 'asc';
$listWithMemCnt->groupBy("a.idx, a.taskname")->orderBy($sort,$dir);
$listWithMemCompCnt->groupBy("idx, taskname")->where("complete", "YES")->orderBy($sort,$dir);
$total = $listWithMemCnt->copy()->count();

if ($limit > 0) {
    $listWithMemCnt->limit($start,$limit);
    $listWithMemCompCnt->limit($start,$limit);
}

$listWithMemCnt = $listWithMemCnt->get();
$listWithMemCompCnt = $listWithMemCompCnt->get();

// listWithMemCnt 에 모든 값(mem_cnt)를 양도함.
for ($i = 0, $length = count($listWithMemCnt); $i < $length; $i++) {
    $listWithMemCnt[$i]->mem_comp_cnt = $listWithMemCompCnt[$i]->mem_cnt;
}

$results->success = true;
$results->lists = $listWithMemCnt;
$results->total = $total;
?>
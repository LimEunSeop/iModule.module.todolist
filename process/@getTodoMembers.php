<?php
/**
 * 이 파일은 iModule Todolist 모듈의 일부입니다. (https://www.imodule.kr)
 * 
 * 관리패널 Todolist 등록멤버의 상세정보를 불러온다.
 *
 * @file /modules/todolist/process/@getTodoMembers.php
 * @author Eunseop Lim (dmstjq12@naver.com)
 * @license GPLv3
 * @version 0.1.0
 * @modified 2018. 7. 5.
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

$idx = Request('idx');

$lists = $this->db()->select($this->table->todolist.' t', 'm.idx, m.name, m.nickname, m.email, m.reg_date, t.complete')->join($this->IM->getModule('member')->getTable('member').' m', 't.mem_idx=m.idx')->where('t.admin_idx', $idx);
$total = $lists->copy()->count(); // admin_todolist 테이블 레코드 갯수
$lists->orderBy($sort, $dir);
if ($limit > 0) $lists->limit($start, $limit);
$lists = $lists->get();

$results->success = true;
$results->lists = $lists;
$results->total = $total;
?>
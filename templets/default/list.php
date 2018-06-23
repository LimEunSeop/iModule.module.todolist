<?php
/**
 * 이 파일은 iModule todolist 모듈의 일부입니다. (https://www.imodule.kr)
 *
 * todolist 기본템플릿 - list 템플릿
 * 
 * @file /modules/todolist/templets/default/list.php
 * @author Eunseop Lim (dmstjq12@naver.com)
 * @license MIT License
 * @version 0.0.2
 * @modified 2018. 6. 22.
 */
if (defined('__IM__') == false) exit;
?>

<ul id="myTodoList" class="tl-list">
    <?php foreach ($tasks as $data) { ?>
    <li class="tl-item <?php echo $data->complete === 'YES' ? 'complete' : '';?>" data-index="<?php echo $data->idx; ?>">
        <span class="tl-text"> <?php echo $data->taskname ?> </span>
        <span class="tl-regdate"> <?php echo GetTime("Y-m-d H:i:s", $data->reg_date); ?> </span>
        <span class="tl-compdate"> &nbsp;&nbsp;
            <span class="image"> <img src="<?php echo $Templet->getDir().'/images/checkmark.png'; ?>" /> </span>
            <span class="value"> <?php echo GetTime("Y-m-d H:i:s", $data->comp_date); ?> </span>
        </span>
    </li>
    <?php } ?>
</ul>

<div class="tl-control">
    <input type="text" name="item" placeholder="Enter Item!">
    <button type="submit" name="add"> Add </button>
    <button type="button" name="doAll"> Do All </button>
    <button type="button" name="undoAll"> Undo All </button>
    <button type="button" name="clearDone"> Clear Done </button>
    <button type="button" name="clearAll"> Clear All </burron>
</div>
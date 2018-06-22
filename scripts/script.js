/**
 * 이 파일은 iModule 모듈예제 #1 의 일부입니다. (https://www.imodule.kr)
 *
 * 모듈예제 #1 의 컨텍스트에서 사용할 자바스크립트
 * 이 자바스크립트 파일은 ModuleExample1.class.php 의 getContext() 함수내에 호출하도록 정의되어 있다.
 * 다른 모듈에서 호출되는 자바스크립트와 충돌을 피하기 위해 모듈명으로 선언된 자바스크립트 클래스를 사용한다.
 * @see /modules/examples1/ModuleExample1.class.php
 * 
 * @file /modules/examples1/scripts/script.js
 * @author Arzz (arzz@arzz.com)
 * @license MIT License
 * @version 3.0.0
 * @modified 2018. 4. 30.
 */
var Todolist = {
	list: {
		init: function() {
			$("input[name=item]").focus();

			var $form = $("#ModuleTodolistForm");

			// 아이템 클릭시 완료처리
			$("li", $form).on("click", function(e) {
				// console.log(e.target.closest(".tl-item"));
				var clickedItem = e.target.closest(".tl-item");
				
				var clickedIdx = $(clickedItem).data("index");
				var isCompleted = $(clickedItem).hasClass("complete") ? "YES" : "NO";
				$.send(ENV.getProcessUrl("todolist", "do"), {idx:clickedIdx, isCompleted:isCompleted}, function(result) {
					if (result.success == true) {
						$("span.value", $(clickedItem)).html(result.comp_date);
						$(clickedItem).toggleClass("complete");
					} else {
						iModule.modal.alert(iModule.getText("text/confirm"),result.message);
					}
				});
			});

			// add버튼 클릭 or input 에서 엔터 누를때 아이템 추가
			$form.on("submit", function() {
				var $input = $("input[name=item]");
				if ($input.val().trim() !== "") {
					$form.send(ENV.getProcessUrl("todolist", "add"));
				} else {
					return false;
				}
			});

			$("button", $form).on("click", function(e) {
				var buttonName = e.target.name;

				switch(buttonName) {

					case "doAll":
						$.send(ENV.getProcessUrl("todolist", "doAll"), function(result) {
							if (result.success == true) {
								$("li", $form).each(function(index, item) {
									$("span.value", item).html(result.comp_date);
									$(item).addClass("complete");
								});
							}
						});
						break;

					case "undoAll":
						$.send(ENV.getProcessUrl("todolist", "undoAll"), function(result) {
							if (result.success == true) {
								$("li", $form).each(function(index, item) {
									$(item).removeClass("complete");
								});
							}
						});
						break;

					case "clearDone":
						$.send(ENV.getProcessUrl("todolist", "clearDone"), function(result) {
							if (result.success == true) {
								$("li.complete", $form).remove();
							}
						});
						break;

					case "clearAll":
						$.send(ENV.getProcessUrl("todolist", "clearAll"), function(result) {
							if (result.success == true) {
								$("li", $form).remove();
							}
						});
						break;
				}
			});
		}
	}
};
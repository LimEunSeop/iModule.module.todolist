/**
 * 이 파일은 iModule todolist 모듈의 일부입니다. (https://www.imodule.kr)
 *
 * todolist 모듈의 컨텍스트에서 사용할 자바스크립트
 * 이 자바스크립트 파일은 ModuleTodolist.class.php 의 getContext() 함수내에 호출하도록 정의되어 있다.
 * 다른 모듈에서 호출되는 자바스크립트와 충돌을 피하기 위해 모듈명으로 선언된 자바스크립트 클래스를 사용한다.
 * @see /modules/todolist/ModuleTodolist.class.php
 * 
 * @file /modules/todolist/scripts/script.js
 * @author Eunseop Lim (dmstjq12@naver.com)
 * @license MIT License
 * @version 0.1.0
 * @modified 2018. 7. 3.
 */
var Todolist = {
	getUrl:function(view) {
		var url = $("div[data-module=todolist]").attr("data-base-url") ? $("div[data-module=todolist]").attr("data-base-url") : ENV.getUrl(null,null,false);
		if (!view || view == false) return url;
		url+= "/"+view;
		return url;
	},

	list: {
		init: function() {
			$("input[name=item]").focus();

			var $form = $("#ModuleTodolistListForm");

			/**
			 * 핸들러 등록 부분
			 */

			// 아이템 클릭시 완료처리
			$("li", $form).on("click", Todolist.list.itemClickHandler);

			// add버튼 클릭 or input 에서 엔터 누를때 아이템 추가
			$form.on("submit", Todolist.list.formSubmitHandler);

			// Control 버튼 (Add, Do All, Undo All, Clear Done, Clear All) 처리
			$("button", $form).on("click", Todolist.list.buttonClickHandler);
		},

		itemClickHandler: function(e) {

			var clickedItem = e.target.closest(".item");
			
			var clickedIdx = $(clickedItem).data("index");
			var isCompleted = $(clickedItem).hasClass("complete") ? "YES" : "NO";
			$.send(ENV.getProcessUrl("todolist", "do"), {idx:clickedIdx, isCompleted:isCompleted}, function(result) {
				if (result.success == true) {
					$("span.value", $(clickedItem)).html(result.comp_date);
					$(clickedItem).toggleClass("complete");
				} else {
					if (result.message === "NOT LOGGED") {
						Member.loginModal();
					}
				}
			});
		},

		formSubmitHandler: function() {

			var $form = $("#ModuleTodolistListForm");

			var $input = $("input[name=item]");
			if ($input.val().trim() !== "") {

				$form.send(ENV.getProcessUrl("todolist", "add"), function(result) {
					if (result.success == true) {
						$form.attr("action",Todolist.getUrl(false)); // 이부분 다시공부
						$form.attr("method","post");
						$form.off("submit");
						$form.submit();
					} else {
						if (result.message === "NOT LOGGED") {
							Member.loginModal();
						}
					}
				});
			}

			return false;
		},

		buttonClickHandler: function(e) {

			var $form = $("#ModuleTodolistListForm");
			var buttonName = e.target.name;

			switch(buttonName) {

				case "doAll":
					$.send(ENV.getProcessUrl("todolist", "doAll"), function(result) {
						if (result.success == true) {
							$("li", $form).each(function(index, item) {
								$("span.value", item).html(result.comp_date);
								$(item).addClass("complete");
							});
						} else {
							if (result.message === "NOT LOGGED") {
								Member.loginModal();
							}
						}
					});
					break;

				case "undoAll":
					$.send(ENV.getProcessUrl("todolist", "undoAll"), function(result) {
						if (result.success == true) {
							$("li", $form).each(function(index, item) {
								$(item).removeClass("complete");
							});
						} else {
							if (result.message === "NOT LOGGED") {
								Member.loginModal();
							}
						}
					});
					break;

				case "clearDone":
					$.send(ENV.getProcessUrl("todolist", "clearDone"), function(result) {
						if (result.success == true) {
							$("li.complete", $form).not($("li.admin")).remove();
						} else {
							if (result.message === "NOT LOGGED") {
								Member.loginModal();
							}
						}
					});
					break;

				case "clearAll":
					$.send(ENV.getProcessUrl("todolist", "clearAll"), function(result) {
						if (result.success == true) {
							// var $except = $("li.admin");
							$("li", $form).not($("li.admin")).remove();
						} else {
							if (result.message === "NOT LOGGED") {
								Member.loginModal();
							}
						}
					});
					break;
			}
		}
	}
};
# iModule 의 Module 제작 가이드 - Todolist 모듈

## iModule 의 아키텍쳐
![imodulearchitecture](https://user-images.githubusercontent.com/34618693/41954142-ad43d99e-7a14-11e8-86f8-31485e4028c2.png)
- DB를 제외한 전체 클래스가 iModule 코어기능을 사용하기 위해 iModule 클래스와 상호 연계되어 있다.
- DB 는 iModule이 의존하고 있는 클래스일 뿐이다.
- iModule 코어는 이벤트를 발생시키고 모듈을 로드하고 DB처리 하는 핵심적인 기능을 가지고 있다.
- 따라서 코어가 아닌 다른 클래스에서 이러한 기능을 사용하려면 $this->IM 을 이용하여 그 기능을 사용해야할 것이다.
- iModule과 직접 연결돼있는 Module 클래스를 빼고 생각해보면, iModule->Module->ModuleXXX 순으로 상속이 진행된다고 생각할 수 있다.
- 그럼에도 불구하고 iModule 밑에 바로 Module 을 둔 이유는, 굳이 ModuleXXX 클래스에서 부르지 않아도 될 static 함수 같은 것들을 편리하게 호출하기 위해서이다.
- ModuleXXX와 연결돼있는 Module 클래스는 Module의 static기능 + ModuleXXX 의 정보를 조회하는 기능을 가지고 있다.


## 모듈 뼈대 생성
### 1. example1 모듈 clone 하기
```git clone https://github.com/moimz/iModule.module.example1.git <만들고자 하는 모듈명>```

터미널에서 위와같은 명령으로 모듈의 뼈대를 가져온다.

### 2. 파일의 이름 및 내용 변경
- 모든 파일을 찾아가며 파일명이나 내용에 example1 이라고 있으면 <만들고자 하는 모듈명> 으로 모두 바꾼다.
- ModuleExample1.class.php 에서 example1 테이블은 바꾸지 않는것이 맞다.
- ModuleExample1.class.php 의 getContextConfigs 메서드에서 $templet->target을 <만들고자 하는 모듈명> 으로 바꾼다.
- 템플릿 폴더의 package.json 과 모듈 최상위 폴더의 package.json 파일을 알맞게 수정한다.

> Todolist 모듈을 만들 것이므로, 해당되는 것들을 모두 Todolist 라고 변경하면 된다. (상황별 대소문자 유의))

## package.json 설정
다음과 같이 Todolist 모듈의 메타정보를 수정하여, 모듈의 기본 정보 및 데이터베이스를 구성한다.
```json
{
	"id":"com.moimz.imodule.module.todolist",
	"icon":"mi mi-imodule",
	"title":{
		"ko":"To do 리스트"
	},
	"version":"0.1.0",
	"description":{
		"ko":"할일을 관리할 수 있습니다."
	},
	"author":{
		"name":"Eunseop",
		"email":"dmstjq12@naver.com"
	},
	"homepage":"http://github.com/limeunseop",
	"dependencies":{
		"core":"3.0.0",
		"member":"3.0.0"
	},
	"language":"ko",
	"admin":false,
	"context":true,
	"global":false,
	"article":false,
	"configs":{
		"templet":{
			"type":"templet",
			"default":"default"
		}
	},
	"databases":{
		"todolist":{
			"columns":{
				"idx":{
					"type":"int",
					"length":11,
					"comment":"자동증가고유값"
				},
				"mem_idx":{
					"type":"int",
					"length":11,
					"comment":"멤버idx"
				},
				"taskname":{
					"type":"varchar",
					"length":100,
					"comment":"할 일",
					"is_null":false
				},
				"complete":{
					"type":"enum",
					"length":"'YES', 'NO'",
					"default":"NO",
					"comment":"완료 여부"
				},
				"reg_date":{
					"type":"int",
					"length":11,
					"comment":"작성일"
				},
				"comp_date":{
					"type":"int",
					"length":11,
					"comment":"완료일"
				}
			},
			"indexes":{
				"idx":"primary_key",
				"mem_idx":"index"
			},
			"auto_increment":"idx"
		}
	}
}
```

## 컨텍스트 호출 설정하기
ModuleTodolist.class.php 파일에서 getListContext 함수를 만들어 데이터 구성 후 list 템플릿을 반환하는 로직을 구성하고, getContext 함수에서 그 함수를 호출하도록 한다.

### Context 호출 제어문 작성
ModuleTodolist.class.php 파일의 getContext 메소드를 다음과 같이 수정한다.
```php
function getContext($context,$configs=null) {
    /**
     * 모듈 기본 스타일 및 자바스크립트
     */
    $this->IM->addHeadResource('style',$this->getModule()->getDir().'/styles/style.css');
    $this->IM->addHeadResource('script',$this->getModule()->getDir().'/scripts/script.js');

    $view = $this->getView() == null ? 'list' : $this->getView();
    
    $html = PHP_EOL.'<!-- TODOLIST MODULE -->'.PHP_EOL.'<div data-role="context" data-type="module" data-module="todolist" data-context="'.$context.'">'.PHP_EOL;
    $html.= $this->getHeader($configs);
    
    switch ($context) {

        case 'list' :
            $html.= $this->getListContext($configs);
            break;
        
    }
    
    $html.= $this->getFooter($configs);
    
    /**
     * 컨텍스트 컨테이너를 설정한다.
     */
    $html.= PHP_EOL.'</div>'.PHP_EOL.'<!--// TODOLIST MODULE -->'.PHP_EOL;
    
    return $html;
}
```

### default view 설정
```php
$view = $this->getView() == null ? 'list' : $this->getView();
```
모듈을 가져왔을 시 기본적으로 보여질 뷰를 설정한다. 여기서는 list 라는 이름의 뷰를 기본뷰로 설정했다.

### 머릿말 주석 변경
```php
$html = PHP_EOL.'<!-- TODOLIST MODULE -->'.PHP_EOL.'<div data-role="context" data-type="module" data-module="todolist" data-context="'.$context.'">'.PHP_EOL;
```
맨 앞 주석의 모듈명을 바꿔주고 data-module 의 모듈명도 바꿔준다.

### context별 호출함수 정의
```php
switch ($context) {

    case 'list' :
        $html.= $this->getListContext($configs);
        break;
    
}
```
가져올 뷰 이름별로 컨텍스트를 가져오는 함수를 호출한다. 여기서는 list 뷰를 가져올 때 getListContext를 호출하여 그 결과값을 $html 에 덧붙이겠다고 설정했다.

### 꼬릿말 주석 변경
```php
$html.= PHP_EOL.'</div>'.PHP_EOL.'<!--// TODOLIST MODULE -->'.PHP_EOL;
```
다음과 같이 꼬릿말 주석을 변경해준다.

## 컨텍스트 로딩함수 정의
아까 호출하기로 한 getListContext 함수를 다음과 같이 정의한다.
```php
/**
 * list 컨텍스트를 가져온다
 * 
 * @param object $configs 사이트맵 관리를 통해 설정된 페이지 컨텍스트 설정
 * @return string $html 컨텍스트 HTML
 */
function getListContext($configs=null) {
    $memberModule = $this->IM->getModule('member');
    $logged = $memberModule->isLogged();

    if (!$logged) {
        return $this->getError('REQUIRED_LOGIN');
        // return "<script>Member.loginModal();</script>"; 이것은 스크립트에서 필요할때 부르는 걸로!!!
    }

    $mem_idx = $this->IM->getModule('member')->getMember()->idx; // 멤버 idx 값 불러오기
    $tasks = $this->db()->select($this->table->todolist)->where('mem_idx', $mem_idx)->get();

    $header = PHP_EOL.'<form id="ModuleTodolistForm">'.PHP_EOL;
    $footer = PHP_EOL.'</form>'.PHP_EOL.'<script>Todolist.list.init();</script>'.PHP_EOL;

    return $this->getTemplet($configs)->getContext('list', get_defined_vars(), $header, $footer);
}
```
로그인여부 체크하고, list 템플릿에서 사용할 tasks(작업 리스트)를 데이터베이스에서 가져온다. 그 후 list 템플릿을 호출하면 tasks 데이터가 구성된 템플릿의 html이 반환된다. ```<script>Todolist.list.init();</script>``` 라는 구문이, 브라우저에 컨텍스트가 출력되는 순간 자바스크립트에 정의되어 있던 Todolist.list.init() 함수를 트리거한다. 저 함수는 곧, list 템플릿을 초기화한다는 의미를 지닌다.

## /languages/ko.json 수정
모듈 설정패널에서 내가만든 list 템플릿을 보이게 하려면 다음과 같이 context 하위에 "템플릿명":"보여질 이름" 과 같이 맵핑시켜야 한다.
```json
{
	"context":{
		"list":"todolist 컨텍스트"
	},
	"admin":{
		"configs":{
			"form":{
				"default_setting":"모듈기본설정",
				"templet":"템플릿"
			}
		}
	}
}
```

## /templets/default/list.php 작성
getListContext 에서 정의한 tasks 변수를 그대로 불러와 html 화면을 구성하면 된다.
```php
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
```

## /templets/default/styles/style.css 작성
https://github.com/LimEunSeop/iModule.module.todolist/blob/master/templets/default/styles/style.css 에서 소스를 확인해보자.

> ### 템플릿 css 작성 규칙
> 맨 앞에 div[data-module=todolist] 와 같이 네임스페이스를 통해 모듈별로 구분될 수 있는 선택자를 써줘야 한다. 그렇지 않으면 모듈별 경계가 명확해지지 않아 타 모듈에 스타일이 적용되는 불상사가 발생한다.


## /scripts/script.js 작성
어느 템플릿이건 기능은 공통적으로 동작하기 때문에, 최상위 script로 작성하였다. 템플릿 별로 차별화된 스크립트가 있다면, 템플릿 폴더안의 scripts 폴더를 이용하도록 하자.
```javascript
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
 * @modified 2018. 6. 22.
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

			var $form = $("#ModuleTodolistForm");

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
						 .
						 .
						 .
```
getListContext 함수에서 footer 변수에 Todolist.list.init 함수를 호출하는 구문을 세팅하여 init 함수가 저절로 실행되고, 필요한 부분에 이밴트핸들러를 할당해놓는 작업을 한다.

> ### 템플릿 script 작성 규칙
> 1. 최상위 객체 이름은 모듈간 충돌을 방지하기 위해 모듈명으로 하되, 맨 앞글자를 대문자로 한다.
> 2. getUrl과 같은 전역함수개념을 제외하고, 속성명을 <템플릿명> 으로 한다.
> 3. 각 템플릿에는 반드시 이벤트 핸들러 등록 등의 초기화 작업을 위한 init 함수가 존재한다.
> 4. 나머지 함수는 필요하다면 자율적으로 정의한다.

## Process 정의
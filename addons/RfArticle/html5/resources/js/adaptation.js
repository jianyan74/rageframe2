var initScreen=function(callback){//初始化html  font-size
    //$("html").css("font-size",document.documentElement.clientHeight/document.documentElement.clientWidth<1.5 ? (document.documentElement.clientHeight/603*312.5+"%") : (document.documentElement.clientWidth/375*312.5+"%")); //单屏全屏布局时使用,短屏下自动缩放
    $("html").css("font-size",document.documentElement.clientWidth/375*312.5+"%");//长页面时使用,不缩放
    if(callback)callback();
}

$(function(){
	initScreen();
	
	window.addEventListener("resize",function(e){
    	initScreen()
    }, false);
})

//提示弹窗
var remind = function(content,time){
	var str= '<div class="modal"><div id="remind"><div class="content">'+content+'</div></div></div>';
	$('body').append(str);
	var time = time?time:1500;
	setTimeout(function(){
		$(".modal").remove();
	},time);
};


$(".cancel").click(function(){
	$(this).parents(".pop").hide();
})

document.onreadystatechange = function(e){
	document.body.style.display = "block"
}
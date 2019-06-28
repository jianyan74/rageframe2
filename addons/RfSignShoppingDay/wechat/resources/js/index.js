var initScreen=function(callback){//初始化html  font-size
    //$("html").css("font-size",document.documentElement.clientHeight/document.documentElement.clientWidth<1.5 ? (document.documentElement.clientHeight/603*312.5+"%") : (document.documentElement.clientWidth/375*312.5+"%")); //单屏全屏布局时使用,短屏下自动缩放
    $("html").css("font-size",document.documentElement.clientWidth/375*312.5+"%");//长页面时使用,不缩放
    if(callback)callback();
}

document.onreadystatechange = function(e){
	document.body.style.display = "block"
	$("#wrap").addClass("show");
}

function Toast(msg,duration){  
    duration=isNaN(duration)?3000:duration;  
    var m = document.createElement('div');  
    m.innerHTML = msg;  
    m.style.cssText="width:60%; min-width:3rem; background:rgba(0,0,0,0.5);height:0.8rem; color:#fff; line-height:0.8rem; font-size:0.24rem;text-align:center; border-radius:0.2rem; position:fixed; top:40%; left:20%; z-index:999999;";  
    document.body.appendChild(m);  
    setTimeout(function() {  
        var d = 0.5;  
        m.style.webkitTransition = '-webkit-transform ' + d + 's ease-in, opacity ' + d + 's ease-in';  
        m.style.opacity = '0';  
        setTimeout(function() { document.body.removeChild(m) }, d * 1000);  
    }, duration);  
}

template.config('openTag','<%');
template.config('closeTag','%>');

$(function(){
	initScreen();
	var music = [path + 'audio/bgm.mp3'];
	
    if(/micromessenger/i.test(navigator.userAgent.toLowerCase())){
        document.addEventListener("WeixinJSBridgeReady", function () {
            WeixinJSBridge.invoke('getNetworkType', {}, function (e) {
                voice = audio.create({
                    audio: music,
                    autoPlay: true,
                    loop: true,
                    end: function(cur, length) {
                    }
                });
            });
        }, false);

    }else{
        voice = audio.create({
            audio: music,
            autoPlay: true,
            loop: true,
            end: function(cur, length) {
            }
        });
    }
	
	window.addEventListener("resize",function(e){
    	initScreen()
    }, false);
    
    var position = [
    	{x:0.1,y:-0.7},
    	{x:1.5,y:-0.7},
    	{x:2.9,y:-0.7},
    	{x:4.3,y:-0.7},
    	{x:5.7,y:-0.7},
    	{x:5.7,y:0.9},
    	{x:4.3,y:0.9},
    	{x:2.9,y:0.9},
    	{x:1.5,y:0.9},
    	{x:0.1,y:0.9},
    	{x:0.1,y:2.5},
    	{x:1.5,y:2.5},
    	{x:2.9,y:2.5},
    	{x:4.3,y:2.5},
    	{x:5.7,y:2.5},
    	{x:5.7,y:4.1},
    	{x:4.3,y:4.1},
    	{x:2.9,y:4.1}
    ]
    
    $(".person").css({
		left: position[signInDays].x+'rem',
		top: position[signInDays].y+'rem'
	})
    
    if(signed){
    	$(".signIn").hide();
    	$(".signed").show();
    }else{
    	$(".signed").hide();
    	$(".signIn").show();
    }
    
    var showModal = function(el){
    	$(".modal").show();
    	setTimeout(function(){
    		$(".modal").addClass("show");
    	},30)
    	$(el).addClass("show");
    }
    
    var closeModal = function(el){
    	
    }
    
    $(".modal,.btn-sure").click(function(){
    	$(".modal").removeClass("show");
    	setTimeout(function(){
    		$(".modal").hide();
    	},300)
    	$(".layer").removeClass("show");
    })
    
    $(".play").click(function(){
		$(this).toggleClass("pause");
		voice.playPause();
	})
    
    $(".myPrize").click(function(){
    	getRecord();
    	showModal(".layer-7");
    })
    
    var isLock = false;
    
    $(".signIn").click(function(){
    	if(signInDays==17){
    		return;
    	}
    	if(isLock){
    		return
    	}
    	isLock = true;
    	if(isMember){
    		if(isStart==false){
    			showModal(".layer-5");
    			isLock = false;
    			return;
    		}
    		if(isEnd==true){
    			showModal(".layer-6");
    			isLock = false;
    			return;
    		}
    		signInDays++;
    		$(".person").css({
    			left: position[signInDays].x+'rem',
    			top: position[signInDays].y+'rem'
    		})
    		lottery();
    	}else{
    		showModal(".layer-1");
    	}
    });
    
    //签到抽奖
    function lottery(){
    	/*
    	 * 参数说明
    	 * {
				"status": true,      //是否中奖   true=中奖，false=未中奖
				"type": 1,           //奖品类型   1=积分，2=卡券
				"prizeName": "200",  //奖品名称
				"msg": "错误提示信息"
			}
    	 */
    	$.ajax({
    		type:"get",
    		url:signUrl,
    		dataType: "json",
    		data: {},
    		success: function(res){
    			if(res.code==200){
    				if(res.data.award_cate_id == 1){
    					//积分
    					$(".layer-2 .score").text(res.data.award_title);
    					showModal(".layer-2");
    				}else{
    					//卡券
    					$(".layer-3 p").text(res.data.award_title);
    					showModal(".layer-3");
    				}
    			}else{
    				//未中奖
    				showModal(".layer-4");
    			}
    			isLock = false;
    			$(".footer span").text(signInDays);
    			signed = true;
    			$(".signIn").hide();
    			$(".signed").show();
    			
    		},
    		error: function(xhr, errorType, error){
				Toast(error,2000);
				isLock = false;
			}
    	});
    }
    
    //中奖记录
    function getRecord(){
    	$.ajax({
    		type:"get",
    		url:recordUrl,
    		dataType: "json",
    		data: {},
    		success: function(data){
    			var html = template('listTpl',data.data);
    			$(".layer-7 ul").html(html);
    		}
    	});
    }
})




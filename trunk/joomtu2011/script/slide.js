// JavaScript Document
$(function($){
	var index = 0;
	//滑动导航改变内容	
	$("#slideNav li").hover(function(){
		if(MyTime){
			clearInterval(MyTime);
		}
		index  =  $("#slideNav li").index(this);
		MyTime = setTimeout(function(){
		ShowjQueryFlash(index);
		$('#slideContent').stop();
		} , 400);

	}, function(){
		clearInterval(MyTime);
		MyTime = setInterval(function(){
		ShowjQueryFlash(index);
		index++;
		if(index==4){index=0;}
		} , 3000);
	});
	//滑入 停止动画，滑出开始动画.
	 $('#slideContent').hover(function(){
			  if(MyTime){
				 clearInterval(MyTime);
			  }
	 },function(){
				MyTime = setInterval(function(){
				ShowjQueryFlash(index);
				index++;
				if(index==4){index=0;}
			  } , 3000);
	 });
	//自动播放
	var MyTime = setInterval(function(){
		ShowjQueryFlash(index);
		index++;
		if(index==4){index=0;}
	} , 3000);
});
function ShowjQueryFlash(i) {
$("#slideContent div").eq(i).animate({opacity: 1},1000).css({"z-index": "1"}).siblings().animate({opacity: 0},1000).css({"z-index": "0"});
$("#slideNav li").eq(i).addClass("current").siblings().removeClass("current");
}
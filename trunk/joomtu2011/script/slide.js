// JavaScript Document
$(function($){
	var index = 0;
	//���������ı�����	
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
	//���� ֹͣ������������ʼ����.
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
	//�Զ�����
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
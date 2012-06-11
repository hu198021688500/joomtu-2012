function shop_list_area_pager(areaId, page){
    $(".ajax_shop_list").removeClass("on");
    if ($("#ajax_shop_list_area_" + areaId + "_" + page).size() > 0) {
        $("#ajax_shop_list_area_" + areaId + "_" + page).addClass("on");
        $("#ajax_shop_list_area_" + areaId + "_" + page +" h6:first").trigger("click");
    } else {
        $.get("/goods/ajaxshops",{id:<?php echo $goods['gid']; ?>,aid:areaId, page:page},function(html){
            $("#shop_list_container").append(html);
            $("#ajax_shop_list_area_" + areaId + "_" + page).show();
            $("#ajax_shop_list_area_" + areaId + "_" + page +" h6:first").trigger("click");
        });
    }
}
/***** 门店详细信息展开与收缩以及改变地图标注位置 *****/
function openShopDetail(aid, page, obj) {
    var h6Obj = $(obj);
    $("#ajax_shop_list_area_" + aid + "_" + page +" h6").removeClass("Hh6");
    $("#ajax_shop_list_area_" + aid + "_" + page +" .shop_list_detail").removeClass("tsi-show");
    h6Obj.addClass("Hh6");
    h6Obj.next("div").addClass("tsi-show");
    var ln = h6Obj.attr("ln");
    var lt = h6Obj.attr("lt");
    addMarkerToMap(ln, lt, "#big_map", "/merchant/bigshoplocation/ln/" + ln + "/lt/" + lt);
}
function resetFullbg(){  
    var fullbg=$(".fullbg").css("display");  
    if(fullbg=="block"){  
        var sH2=$("body").height();  
        var sW2=$("body").width();  
        if(sW2<1000){sW2=1000}
        $(".fullbg").css({width:sW2,height:sH2});   
    }  
} 
/***** 绑定手机 *****/
function getMobileVerCode() {
    $.ajax({
        type: "GET",
        url: "/goods/ajaxgetcode",
        dataType:"json",
        data:{
            "mobile" : $("#current_mobile").val(),
            "code" : $("#current_check_number").val()
        },
        success: function(data){
            if("send_success" == data.error_code) {
                $("#bing_mobile_box_2").show();
                $("#bind_do_mobile").show();
                $("#bing_mobile_box_message").html('');
                $("#bing_mobile_box_message").hide();
                shopBindMobileCountdown();
            } else {
                $(".click_refresh_img").trigger("click");
                $("#bing_mobile_box_message").html(data.msg);
                $("#bing_mobile_box_message").show();
                $("#bind_mobile_button").val("重新获取");
            }
        }
    });
}
var countdownSencond = 60;
/******* 验证绑定手机，倒计时 *****/
function shopBindMobileCountdown() {
    if(countdownSencond > 0) {
        $("#bind_mobile_button").attr('disabled','true');
        $("#bind_mobile_button").val('('+countdownSencond+')秒重新获取');
        countdownSencond--;
        setTimeout('shopBindMobileCountdown()', 1000);
    } else {
        $("#bind_mobile_button").removeAttr('disabled');
        $("#bind_mobile_button").val('获取手机验证码');
        countdownSencond = 60;
    }
}
/****** 隐藏手机短信绑定框 *****/
function hiddenMobileBindDialog() {
    $(".fullbg").hide();
    $("#dialog_box_1").hide();
    $("#current_mobile").val('');
    $("#current_check_number").val('');
    $("#bing_mobile_box_2").hide();
    $("#bing_mobile_box_message").html('');
    $("#bing_mobile_box_message").hide();
    $("#bind_do_mobile").hide();
}
$(document).ready(function() {
    /************ 受理门店页面加载完后加载 ************/
    $.get("/goods/ajaxshops", {id:<?php echo $goods['gid']; ?>, aid:<?php echo $area_id; ?>}, function(html) {
        $("#shop_list_container").append(html);
        $("#ajax_shop_list_area_<?php echo $area_id; ?>_1 h6:first").trigger("click");
    });
    /************* 单击区域tab ******************/
    $(".shop_list_tab").click(function() {
        var tabLiObj = $(this);
        // 如果单击的tab表示当前显示的tab页
        if (!tabLiObj.hasClass("on")) {
            // 给tab标签加样式
            $(".shop_list_tab").removeClass("on");
            tabLiObj.addClass("on");
            var areaId = tabLiObj.attr("id").substr(5);
            $(".ajax_shop_list").removeClass("on");
            if ($("#ajax_shop_list_area_" + areaId + "_1").size() > 0) {
                $("#ajax_shop_list_area_" + areaId + "_1").addClass("on").show();
                $("#ajax_shop_list_area_" + areaId + "_1 h6:first").trigger("click");
            } else {
                $.get("/goods/ajaxshops",{id : <?php echo $goods['gid']; ?>, aid : areaId}, function(html) {
                    $("#shop_list_container").append(html);
                    $("#ajax_shop_list_area_" + areaId + "_1 h6:first").trigger("click");
                });
            }
        }
    });
});
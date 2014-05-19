/**
 * Created by zzzou on 14-5-15.
 */

$(function(){
    var questionBasketCount =0;

    $('.tiku_content').click(function () {
        var _this = $(this);
        var tiku = _this.parents('.tiku');
        var answer = $('.answer', tiku);
        if (!answer.hasClass('hasc')) {
            $('.answer', tiku).addClass('hasc');
        }
        answer.toggleClass('hide');
        return false;
    });

    /*向前一页*/
    $('.pg .prev').click(function () {
        var _this = $(this);
        var form = _this.parents('form:first');
        $("input[name='pageIndex']").val(parseInt($("input[name='pageIndex']").val())-1);
        form.submit();   //在这里做一些hidden字段。。。
        return false;
    });

    /*向后一页*/
    $('.pg .next').click(function () {
        var _this = $(this);
        var form = _this.parents('form:first');
        $("input[name='pageIndex']").val(parseInt($("input[name='pageIndex']").val()) +1);
        form.submit();  //在这里做一些hidden字段。。。
        return false;
    });

    /*已经引入我的题库*/
    $('.tiku .collection_yin').click(function () {
        var _this =$(this);

        if(_this.parent().hasClass('add_add_c'))
        {
            return;
        }else{
            $.ajax({
                type: "POST",
                url: "index.php?c=question&a=addQuote",
                data: {SourceId:_this.attr('data'), userId:'7oogamcif4rb5xjyuleafg',schoolId :'4wg3ajgiyrnjfhbx2jcqrq'},
                dataType:"text",
                success: function(jsonData){
                    //console.debug(_this.parent);
                    _this.parent().addClass('add_add_c');
                    _this.parent().attr('title', "已引用到我的题库");
                    _this.unbind("click");
                    alert("引用到我的题库成功！");
                }
            });
        }


    });

    /*加入试题篮子*/
    $('.tiku .quesbar').click(function () {
        /* var _this = $(this);
         var tiku = _this.parents('.tiku');
         var ids = [];
         ids.push(tiku.data('quesid'));
         var path = _this.parent().hasClass("remove") ? "exam-removecart" : "exam-addcart";
         ques.cart_add_remove(ids, vp + path, _this);
         return false;
         */
        //alert("等待后来做，粘贴html即可。。");
        // console.debug("test");
        var _this = $(this);
        if(_this.parent('span').hasClass('remove')){
            //alert("这里去掉移除Controller");
            $.ajax({
                type: "POST",
                url: "index.php?c=QuestionBar&a=delQuesBar",
                data:{id:_this.attr('data')},
                dataType:"text",
                success: function(jsonData){
                    _this.parent('span').removeClass('remove');
                   // questionBasketCount--;
                   // $('.shitijianzi').html("高中语文 (<b>"+questionBasketCount+"</b>题)");
                	   $(".zujuan_bottom").hide();
                    $('.tabA').hide();
                }
            });
        }else{
            //alert("这里去掉添加Controller");
            $.ajax({
                type: "POST",
                url: "index.php?c=QuestionBar&a=joinQuesBar",
                data:{id:_this.attr('data')},
                dataType:"text",
                success: function(jsonData){
                    //console.debug(" join  seccess");
                    _this.parent('span').addClass('remove');
                   // questionBasketCount++;
                   // $('.shitijianzi').html("高中语文 (<b>"+questionBasketCount+"</b>题)");
                	   $(".zujuan_bottom").hide();
                    $('.tabA').hide();
                }
            });
        }
    });

    /*全部加入试题篮*/
    $(".select_all").click(function(){
        var allId ="";
        $(".in").each(function(i,n){
            //console.debug($(n).attr('data'));
            allId += $(n).attr('data')+",";
            $(n).parent('span').addClass('remove');

        });
        $.ajax({
            type: "POST",
            url: "index.php?c=QuestionBar&a=joinQuesBar",
            data:{id:allId},
            dataType:"text",
            success: function(jsonData){
                //questionBasketCount =0;
                //questionBasketCount = $(".in").length
                //$('.shitijianzi').html("高中语文 (<b>"+questionBasketCount+"</b>题)");
            	   $(".zujuan_bottom").hide();
                $('.tabA').hide();
            }
        });
    });
});

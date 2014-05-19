$(function () {
    ques.index();

    var exam = $('#exam_tabs');
    var pc = Number("1");

    $('.paging [name=go]', exam).keydown(function (e) {
        var _this = $(this);
        if (_this.parents('.paging:first').find(".ajax").length > 0) {
            if (e.keyCode == 13) {
                var form = _this.parents('form:first');
                var p = page(form);
                if (Number(_this.val()) >= pc) _this.val(pc);
                p.val(_this.val());
            }
        }
    });

    $('.search input', exam).keydown(function (e) {
        var form = $(this).parents('form:first');
        if (e.keyCode == 13) {
            $('.search button', form).click();
        }
    });

    $('.search button', exam).click(function () {
        var _this = $(this);
        var form = _this.parents('form:first');
        var p = page(form);
        p.val(1);
        form.submit();
        return false;
    });

    /*跳转*/
    $('.paging .go').click(function () {
        var _this = $(this);
        var go=$("[name='go']").val();
        $("#Pagination a").each(function(i,val){

            if(go == val.innerHTML){
                $("#Pagination a").eq(i).click();
            }
        });

    });
    /**分页开始   edit by qifang*/
    function pageselectCallback(page_id, panel){
        $("#page_current").val(page_id+1);
    }

    var count=$("#count").html();
    $("#Pagination").pagination(count,{
        callback: pageselectCallback
    });
    /**分页结束**/

    /*向前一页*/
    $('.pg .prev').click(function () {
        $("#Pagination .prev").click();
    });

    /*向后一页*/
    $('.pg .next').click(function () {
        $("#Pagination .next").click();
    });

    /*批量加入试题篮*/
    $(".select_all").click(function(){
        var ids="";
        $(".common_title input:checked").each(function(){
            ids=ids+$(this).val()+",";
        })
        $(".ids").val(ids);
        $.ajax({
            type: "POST",
            url: "index.php?c=questionBar&a=joinQuesBar",
            data:{id:ids},
            dataType:"text",
            success: function(jsonData){
                /* questionBasketCount =0;
                 questionBasketCount = $(".in").length
                 $('.shitijianzi').html("高中语文 (<b>"+questionBasketCount+"</b>题)");
                 $('.tabA').hide(); */
                alert("添加成功");
            }
        });
    })



    $('body').unbind("keydown").keydown(function (e) {
        var _this = $(this);
        var form = $('body :not(:hidden) form:not(:hidden)');
        if ($('.paging .ajax', form).length > 0) {
            var cur = Number(jQuery.trim($('.pagination .current', form).text()));
            var p = page(form);
            if (e.keyCode == 37) {
                p.val(cur - 1);
                form.submit();
            }

            if (e.keyCode == 39) {
                cur++;
                if (cur >= pc) cur = pc;
                p.val(cur);
                form.submit();
            }
        }
    });
})
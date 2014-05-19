/**
 * Created by zzzou on 14-5-15.
 */
$(function () {
    var exam = $('#exam_tabs');
    /*form表单提交*/
    //信息栏的展开与收缩 按钮。。
    $(".close a").click(function () {
        $(".slider").slideToggle();
        var dlmm = document.getElementById("zhankai");
        if (dlmm.innerHTML == "展开") {
            dlmm.innerHTML = "收起";
        }
        else { dlmm.innerHTML = "展开"; }
    });

    $(".sign_ok").click(function(){
        exam.submit();
    });

    /*信息栏中的选项按钮。。高中，年份，等等*/
    $('.fore a', exam).click(function () {
        var _this = $(this);
        _this.parents("dd").find('a').removeClass('color_title');
        _this.addClass("color_title");
        $('[name=page]', exam).val(1);
        $('input', _this.parents('dd:first')).val(_this.data('name'));
        _this.parents('form:first').submit();
        return false;
    });

    /*点击语文综合库，语文鲁教版本*/
    $('[name="calibration[knowledgeId]"]', exam).val("1316").chosen().change(function () {
        var _this = $(this);
        $('[name=knowledgeId],[name=nodepath]', exam).val('');
        $('[name=page]', exam).val(1);
        var setting = {
            async: {
                enable: true,
                type:"get",
                url:'index.php?c=Knowledge&a=getTreeNode&categoryId='+ $('[name="calibration[knowledgeId]"]', exam).val()+'&parentId=0',
                autoParam: ["id=parentId"]
            },
            check: {
                enable: true,
                chkStyle: "checkbox",
                autoCheckTrigger: true
            },
            callback: {

                /*   onCheck: function(event,treeId, treeNode){
                 alert(treeNode.id);
                 }*/
            }
        };
        jQuery.fn.zTree.init($(".ztree", exam), setting);
    }).change();

});
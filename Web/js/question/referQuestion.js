/**
 * Created by zzzou on 14-5-15.
 */

$(function () {

    var exam = $('#exam_tabs');
    /*form表单提交*/
    exam.gform({
        onSuccess: function (r) {
            $('.know_con', exam).html(r);
        }
    });

    //信息栏的展开与收缩 按钮。。
    $(".close a").click(function () {
        $(".slider").slideToggle();
        var dlmm = document.getElementById("zhankai");
        if (dlmm.innerHTML == "展开") {
            dlmm.innerHTML = "收起";
        }
        else { dlmm.innerHTML = "展开"; }
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
    $('[name=bankId]', exam).val("1316").chosen().change(function () {
        var _this = $(this);
        $('[name=knowledgeId],[name=nodepath]', exam).val('');
        $('[name=page]', exam).val(1);
        var setting = {
            async: {
                enable: true,
                type:"get",
                url:'index.php?c=Knowledge&a=getTreeNode&categoryId='+ $('[name=bankId]', exam).val()+'&parentId=0',
                autoParam: ["id=parentId"]
            },
            callback: {
                beforeClick: function (treeId, treeNode) {
                    $('[name=page]', exam).val(1);
                    $('[name=knowledgeId]', exam).val(treeNode.id);
                    $('[name=nodepath]', exam).val(treeNode.nodepath);
                    exam.submit();
                }
            }
        };
        jQuery.fn.zTree.init($(".ztree", exam), setting);
        jQuery.fn.gform.working = false;
        exam.submit();

    }).change();

});
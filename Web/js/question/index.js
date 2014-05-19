function _format() {
    if (arguments.length == 0)
        return null;
    var str = arguments[0];
    for (var i = 1; i < arguments.length; i++) {
        var re = new RegExp('\\{' + (i - 1) + '\\}', 'gm');
        str = str.replace(re, arguments[i]);
    }
    return str;
};
jQuery(document).ready(function () {


    var menu = $('.left_wrapper'), cur_subject = "", m = 0;
    var cateid = $('.proCon .pro dd a input:checkbox[checked=checked]:first', menu).parent().data('cate');
    if (cateid > 0) {
        $('.proCon .pro dd a input:checkbox[checked=checked]:first', $('.left_wrapper')).parent().siblings('[data-cate=' + cateid + ']').children('input').attr('disabled', false);
    }

    $('.fn-left_title_zn_sx').hover(function () {
        var _this = $(this);
        _this.addClass('h_r');
        $('.productBox').show();
        return false;
    }, function () {
        var _this = $(this);
        _this.removeClass('h_r');
        $('.productBox').hide();
        return false;
    });

    $('.fn-left_title_zn_sx .clear', menu).click(function () {
        $('.proCon .pro dd a input:checked', menu).each(function (i, v) {
            $(v).attr('checked', false);
        });

        $('.fn-left_title_zn_sx .pro .kj span', menu).click();
        return false;
    });

    $('.proCon .pro dd a span', menu).click(function () {
        var _this = $(this).parents('a:first'), pro = _this.parents('.pro');
        var bankId = _this.data('id');
        var oldId = _this.parents('.proCon:first').find('.kj').data('id');
        var bankIds = "";
        if (_this.data('cate') != 0) {
            $('a[data-cate!=0] input:checked:not(:disabled)', pro).each(function (i, v) {
                bankIds += "," + $(v).parents('a:first').data('id');
            });

            if (bankIds != "") {
                bankIds = bankIds.substring(1);
                jQuery.each(bankIds, function (i, v) {
                    if (v != _this.data('id') && _this.children('input:not(:disabled,:checked)').length > 0)
                        bankIds = "";
                });
            }
        }

        jQuery.post("/exam/setBank", { bankId: bankId, oldId: oldId, bankIds: bankIds }, function () {
            location.reload();
        }, "json");
        return false;
    });

    $('.proCon .pro dd a input', menu).click(function () {
        var _this = $(this), a = _this.parents('a:first'), pro = _this.parents('.pro:first'), con = _this.parents('.proCon:first');

        $('.pro', con).removeClass('c');

        pro.addClass('c');

        var cur = $('.fn-left_title_zn_sx a.cur', menu);

        var pl = $('.fn-left_title_zn_sx span:first', menu);

        $('.clear', pl).hide();

        var str = "";
        if (a.data('cate') == 1) {
            str = "理综：";
        } else if (a.data('cate') == 2) {
            str = "文综：";
        }
        if (str != "") {
            $('.pro:not(.c) input', con).attr('disabled', true);
            $('a[data-cate!=' + a.data('cate') + '] input', con).attr('disabled', true);

            var len = -1;
            $('a[data-cate=' + a.data('cate') + '] input:checked', pro).each(function (i, v) {
                if (i == 0) {
                    str += $(v).parents('a:first').attr('title').substring(2);
                } else {
                    str += "," + $(v).parents('a:first').attr('title').substring(2);
                }
                len = i;
            });
            if (m == 0) {
                cur_subject = cur.html();
                pl.addClass('pl');
            }
            if (len == -1) {
                pl.addClass('pl');
                cur.html(cur_subject);
                $('a[data-cate!=0] input', con).attr('disabled', false);
            }
            else {
                pl.removeClass('pl');
                $('.clear', pl).show();
                cur.html(cur_subject.substr(0, 2) + str);
                if ($('a[data-cate] input:checked', con).length == 3) {
                    $('a[data-cate] input:checked:first').next().click();
                }
            }
        }
        m++;
        event.stopPropagation();
    });
});
var storage = window.localStorage;
var key = $("#userid").val();


$(function () {

    $("#exam_form").gform({
        onSuccess: function (r) {
            $("#exam_tables").html(r);
        }
    });
    $('.fore a').click(function () {

        var _this = $(this);
        _this.parents("dd").find('a').removeClass('color_title');
        _this.addClass("color_title");
        $('input', _this.parents('dd:first')).val(_this.data('name'));
        _this.parents('form:first').submit();
        return false;
    });
})
$(function () {
    $('.question-table .ljx').click(function () {
        jQuery.post("/exam-removecart", { quesId: [], cateName: $(this).parents('tr:first').data('categoryid') }, function (r) {
            if (r.code == 1) location.reload();
        }, "json");
        return false;
    });
    $(".data-table").find("tr").find("td:first").css("border-left","none");
    $('.question-table .clean').click(function () {
        if (confirm("确定清空吗？")) {
            jQuery.post("/exam-removecart", { quesId: [], cateName: -1 }, function (r) {
                if (r.code == 1) location.reload();
            }, "json");
            return false;
        }
    });
});

var tiku = $(".tiku");
$(function () {
    $(".caozuo_waiwei").hover(function () {
        $(this).find(".new_caozuo").fadeToggle();
    })


    $(".add_school", tiku).click(function () {
        if (confirm("确定将题目加入学校题库吗？")) {
            var _this = $(this);
            jQuery.post("/MyBank/add_school", { id: _this.data("id") }, function (r) {
                alert(r.msg);
                var _audit = _this.parents(".yichu").prev(".common_title");
                if (_audit.find(".audit").length < 1) {
                    _audit.append("<span class='common_title_n audit'>（审核状态：审核中）</span>");
                }
                else {
                    _audit.find(".audit").html("（审核状态：审核中）");
                }
                _this.parents(".yichu").find(".reason,.add_school").remove();

            }, "json");
        }
    });


    $("a[enabled=1]", tiku).click(function () {
        jQuery.post("/MyBank/quesSign", { id: $(this).data("id"), page: "1" }, function (r) {
            $("#exam_tabs").html(r);
            window.onload();
        }, "json");
    });

    $(".signAll").click(function () {
        var ids = "";
        var _ques = $("input[type=checkbox]:checked");
        if (_ques.length == 0) {
            alert("请至少选择一个题目，否则无法批量标定。");
            return false;
        }
        $("input:checkbox:checked").each(function (i,v) {
                ids += $(this).attr("value") + ",";
        });
        ids = ids.indexOf(",") > 0 ? ids.substr(0, ids.length - 1) : ids;

        window.location.href = "./index.php?c=question&a=calibration&id="+ids;
    });

    $(".reason", tiku).click(function () {
        var _this = $(this);
        var dlg = $("#ReasonDialog");
        dlg.dialog({
            title: "驳回原因",
            width: 400,
            height: 220,
            modal: true,
            buttons: {
                "关闭": function () {
                    dlg.dialog("close");
                }
            }
        }).html("<label>" + _this.attr("reason") + "<label>");
    });
});

$(document).ready(function(){
    var wrapper = $('#a_wrapper');
    $('.skin a', wrapper).click(function () {
        var _this = $(this);
        jQuery.post('/teacher/saveTheme', { theme: _this.attr('title') }, function (r) {
            if (r.code == 1) {
                $('#azb_theme').attr('href', './default/skin_' + _this.attr('title') + '.css');
            }
        }, "json");
    });

    $('#role_span').hover(function () {
        $('#person_news').fadeIn();
    });

    $('.btn').button();

    $(document).click(function () {
        $("#person_news").hide();
    });

    $('#news_span').hover(function () {
        $('#news_content').show();
    });
})

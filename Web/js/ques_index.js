if (typeof ques == 'undefined')
    ques = {};
var vp;
ques.index = function () {
    var exam = $('#exam_tabs');
    vp = exam.data('vp');
    ques.setting = {
        exam: exam,
        vp: vp
    };
    $('.tiku .quesbar', exam).click(function () {
        var _this = $(this);
        var tiku = _this.parents('.tiku');
        var ids = [];
        ids.push(tiku.data('quesid'));
        var path = _this.parent().hasClass("remove") ? "exam-removecart" : "exam-addcart";
        $.post(vp + "exam-isRegenerateAnswerCard", {}, function (r) {
            if (r.code == 1) {
                alert("新添加的试题导致已生成的答题卡将会重新生成，恢复初始值");
            }
        }, "json");
        ques.cart_add_remove(ids, vp + path, _this);
        return false;
    });
    $('.select_all', exam).click(function () {
        $.post(vp + "exam-isRegenerateAnswerCard", {}, function (r) {
            if (r.code == 1) {
                alert("新添加的试题导致已生成的答题卡将会重新生成，恢复初始值");
            }
        }, "json");
        var ids = [];
        $('.tiku', exam).each(function (i, v) {
            if ($('.remove', v).length == 0) {
                ids.push($(v).data('quesid'));
            }
        });
        ques.cart_add_remove(ids, vp + 'exam-addcart', $('.tiku span:not(.remove) .in', exam));
        return false;
    });

    $('.tiku .collection', exam).click(function () {
        var _this = $(this);
        var tiku = _this.parents('.tiku');
        _this.parent().toggleClass('add');
        if (_this.parent().hasClass("add")) {
            $.post(vp + "exam-removeCollection", { examId: tiku.data('paperid'), quesId: tiku.data('quesid') }, function (r) {
                _this.parent().attr('title', "点击加入收藏");
            }, "json");
        } else {
            $.post(vp + "exam-addCollection", { examId: tiku.data('paperid'), quesId: tiku.data('quesid') }, function (r) {
                _this.parent().attr('title', "点击取消收藏");
            }, "json");

        }
    });

    $('.tiku .play', exam).click(function () {
        var _this = $(this);
        $.post(vp + "exam-play", { quesId: _this.parents('.tiku').data('quesid') }, function (r) {

        }, "json");
    });

    $('.tiku .collection_yin', exam).mouseover(function () {
        var _this = $(this);
        var tiku = _this.parents('.tiku');
        if (_this.hasClass('ishove')) return false;
        jQuery.post(vp + "exam-isQuote", { sourceId: tiku.data('sourceid') }, function (r) {
            _this.addClass('ishove');
            if (r) {
                _this.parent().attr('title', "已引用到我的题库").data('ishave', 0);
            } else {
                _this.parent().attr('title', "点击引用到我的题库").data('ishave', 1);
            }
        }, "json");
        return false;
    }).click(function () {
        var _this = $(this);
        var tiku = _this.parents('.tiku');
        var ishave = _this.parent().data('ishave');
        if (ishave == 1) {
            $.post(vp + "exam-addQueslibrary", { id: tiku.data('quesid') }, function (r) {
                _this.parent().data('ishave', 0);
                _this.parent().attr('title', "已引用到我的题库");
                _this.parent().addClass('add_add_c');
                alert("引用到我的题库成功！");
            }, "json");
        }
        return false;
    });

    $('.tiku_content').click(function () {
        var _this = $(this);
        var tiku = _this.parents('.tiku');
        var answer = $('.answer', tiku);
        answer.addClass('hasc');
        answer.toggleClass('hide');
    });
};

ques.dialog = function (params, html, ok, cancel, close) {
    var dlg = $('#dlg_paper');
    if (dlg.length == 0) {
        dlg = $('<div id="dlg_question"/>');
        $('body').append(dlg);
    }

    params = $.extend(true, {}, {
        title: '试卷设置', center: true, modal: true, height: 'auto', width: 600,
        buttons: {
            '确定': function () {
                if ($.isFunction(ok)) {
                    if (ok.call()) {
                        $(this).dialog("close");
                    }
                }

            },
            '取消': function () {
                if ($.isFunction(cancel)) cancel.call();
                $(this).dialog("close");
            }
        }, close: function (event, ui) {
            if ($.isFunction(close)) close.call();
            $(this).dialog("close");
            dlg.remove();
        }
    }, params);

    return dlg.html(html).dialog(params);
};

ques.cart_add_remove = function (ids, url, el) {
    var exam = ques.setting.exam;
    $.post(url, { quesId: ids }, function (r) {
        if (r.code == 1) {
            el.each(function (i, v) {
                $(v).parent().toggleClass('remove');
            });
            $(".bfb").html(r.bfb);
            $('.xzbox', exam).html(r.html);
        }
    }, "json");
};


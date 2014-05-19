//Design by leixu@txtek.cn
//2013-02-28 20:00:00
//TXTEK 

//text 要截取的字符串
//cut_length 截取的长度 中文=cut_length/2
//截取后末尾添加的字符串 比如:...
(function ($) {
    $.extend({
        autoInterception: function (text, cut_length, end) {
            if (text.length == 0 || cut_length > $.getLength(text)) return $.htmlencode(text);

            var result = '';
            var j = 0;

            var str_array = text.split('');

            for (var i = 0; i < str_array.length; i++) {
                var c = str_array[i];

                if (text.charCodeAt(i) >= 255) {
                    j = j + 2;
                } else {
                    j = j + 1;
                }

                result = result + c;

                if (j >= cut_length) {
                    if (end) return $.htmlencode(result + end); else return $.htmlencode(result + '...');
                }
            }
        },
        getLength: function (text) {
            var length = 0, c = '';

            for (var i = 0; i < text.length; i++) {
                c = text.charCodeAt(i);
                if (c >= 255) {
                    length = length + 2;
                } else {
                    length = length + 1;
                }
            }
            return length;
        },
        htmlencode: function (text) {
            return $('<div />').text(text).html();
        },
        htmldecode: function (text) {
            return $('<div />').html(text).text();
        }
    });
})(jQuery);

$(function () {

    $.pnotify.defaults.styling = "jqueryui";
    $.pnotify.defaults.history = false;
    $.pnotify.defaults.animation = 'none';
    $.pnotify.defaults.sticker = false;

    window.alert = function (message, type) {
        $.pnotify({
            title: message,
            addclass: 'stack-bar-top',
            width: '30%',
            delay: 5000,
            sticker: false,
            stack: { "dir1": "left", "dir2": "up", "push": "top", "spacing1": 45, "spacing2": 45 },
            type: type || 'info'
        });
    };

    window.confirm2 = window.confirm;

    window.confirm = function (msg, x, y, cb, cb1, options) {
        options = $.extend(true, {}, { width: '160px' }, options);

        if (!x) {
            return window.confirm2(msg);
        }
        else {
            var dlg = $.pnotify({
                title: "<div style='padding-left:30px;'>" + msg + "</div>",
                text: '<button type="submit" style="margin-left:20px;" class="button1">确定</button> <button type="button" style="margin-left:10px;" class="button">取消</button>',
                width: options.width,
                hide: false,
                stack: false
            });

            dlg.css({
                'top': x,
                'left': y
            });

            dlg.find('button:first').click(function () {
                if (cb && jQuery.isFunction(cb)) {
                    cb.apply(dlg, []);

                    return false;
                }
            });

            dlg.find('button:last').click(function () {
                if (cb1) {
                    cb1.apply(dlg, []);
                    return false;
                }

                dlg.pnotify_remove();
            }).focus();
        }
    };

    if ((jQuery.browser.msie) && (parseInt(jQuery.browser.version) < 8)) {
        var style = document.createElement("style");
        style.type = "text/css";
        style.media = "screen";

        (document.getElementsByTagName("head")[0] || document.body).appendChild(style);

        var css = "#ie6-warning {position:fixed;width: 100%;background: #ffffe1;padding: 10px 0;font-size: 12px;color: #000;z-index: 9999;} #ie6-warning a {color: blue;} #ie6-warning a:hover {text-decoration: underline;} #ie6-warning p {width: 960px;margin: 0 auto;} #header{top:38px;}";

        if (style.styleSheet) { //for ie
            style.styleSheet.cssText = css;
        } else {//for w3c
            style.appendChild(document.createTextNode(css));
        }

        $('body').prepend('<div id="ie6-warning"><p>您正在使用的浏览器版本过低，我们希望您的体验更顺畅、安全，建议免费升级<a href="http://www.microsoft.com/china/windows/internet-explorer/" target="_blank">最新版 Internet Explorer</a> 或以下浏览器：<a href="http://www.mozillaonline.com/" target="_blank">Firefox</a> / <a href="http://www.google.com/chrome/?hl=zh-CN" target="_blank">Chrome</a> / <a href="http://www.apple.com.cn/safari/" target="_blank">Safari</a> / <a href="http://www.operachina.com/" target="_blank">Opera</a></p></div>');
    };

    //定时获取各种消息提醒
    var interval_getData = function () {
        $.post($('head').data('vp') + 'home/connect/unread_count', {}, function (r) {
            r = handleException(r);

            if (r) {
                $('#page').trigger('pullmsg', [r]);
            }
        }, 'json');
    };

    if ($('.headNews').length == 1) {
        window.setInterval(interval_getData, 50000);
    }

    $('#top .has_sub').click(function () {
        $('.topitem').removeClass('selected').find('.top_classlist').hide();

        var ts = $(this);
        var parent = ts.parent();
        var isCurrent = parent.hasClass('select');

        if (parent.find('.top_classlist:first').length == 0) {
            $.post($('head').data('vp') + ts.data('url'), {}, function (r) {
                r = handleException(r);

                if (r && r.length > 0) {
                    parent.append(r);
                    parent.find('.top_classlist:first').show();

                    parent.removeClass('select');
                    parent.addClass('selected');
                }
            }, 'json');
        } else {
            parent.find('.top_classlist:first').show();

            parent.removeClass('select');
            parent.addClass('selected');
        }

        $('body').one('click', function () {
            parent.removeClass('selected');

            if (isCurrent) parent.addClass('select');
            parent.find('.top_classlist:first').hide();
        });
        return false;
    });

    $('#header_nav .gbma1').parent().click(function () {
        var ele = $(this).parent();
        if (ele.hasClass('sele'))
            return false;

        $('#header_nav .sele').removeClass('sele');

        $(this).parents('.header_navInfo').addClass('sele');

        $('body').one('click', function () {
            $('#header_nav .sele').removeClass('sele');
        });
        return false;
    });

    if ($('#header_nav .sbnum').length > 0) {
        $('#header_nav .sbnum').parents('li:first').addClass('sele');
    }

    var search_form = $('#form_top_search');
    search_form.gform({ bindOnly: true, focus_first_input: false });

    $('[name=q]', search_form).maxlength({
        onEdit: function () {
            var v = jQuery.trim($(this).val());
            if (v.length > 0) {
                $('[type=button]', search_form).removeClass('sub').addClass('sub1');
                var div = $('.search_base', search_form);

                if ($('li', div).length > 1) {
                    div.slideDown('fast').find('span').text($.autoInterception(v, 10, '...'));
                }

                if ($('.hover', div).length == 0) {
                    if ($('li.def', div).length > 0)
                        $('li.def', div).addClass('hover');
                    else
                        $('li:first', div).addClass('hover');
                }
            } else {
                $('.search_base', search_form).slideUp('fast');
                $('[type=button]', search_form).addClass('sub').removeClass('sub1');
            }
        }
    }).focus(function () {
        $(this).addClass('focus');
    }).blur(function () {
        $(this).removeClass('focus');
        $('[type=button]', search_form).addClass('sub').removeClass('sub1');
        $('.search_base', search_form).slideUp('fast');
    }).keydown(function (event) {
        if (event.keyCode == 40) {
            var cur = $('.hover', search_form);
            var next = cur.removeClass('hover').next();
            if (next.length == 0)
                next = cur.parent().children(':first');

            next.addClass('hover');

            event.preventDefault();
        } else if (event.keyCode == 38) {
            var cur = $('.hover', search_form);
            var prev = cur.removeClass('hover').prev();
            if (prev.length == 0)
                prev = cur.parent().children(':last');

            prev.addClass('hover');

            event.preventDefault();
        } else if (event.keyCode == 13) {
            $('[type=button]', search_form).trigger('click');
        }
    });

    $('.search_base li', search_form)
        .hover(function () {
            $(this).addClass('hover').siblings('.hover').removeClass('hover');
        }, function () { })
        .mousedown(function () {
            // 此处未调用$('[type=button]', search_form).trigger('click')是为了防止触发文本框的blur事件
            search_form.attr('action', $('li.hover', search_form).data('action'));

            search_form[0].submit();

            return false;
        }).click(function () {
            return false;
        });

    $('[type=button]', search_form).click(function () {

        search_form.attr('action', $('li.hover', search_form).data('action'));

        search_form[0].submit();
    });

    $('a').focus(function () {
        $(this).blur();
    });

    var div_apps = $('#lines');
    if (div_apps.length > 0) {
        $(".myyy").click(function () {
            div_apps.slideDown("fast");
            $('body').one('click', function () {
                div_apps.slideUp("fast");
            });
            return false;
        })
    }

    $(window).scroll(function () {
        if ($(this).scrollTop() != 0) {
            $('#toTop').fadeIn();
        }
        else {
            $('#toTop').fadeOut();
        }
    });

    $('#toTop .gotop').mouseover(function () {
        $(this).find('span').show();
    }).mouseleave(function () {
        $(this).find('span').hide();
    }).click(function () {  //back to top
        $('body,html').animate({ scrollTop: 0 }, 600);
        return false;
    });

    $('#toTop .feedback').mouseover(function () {
        $(this).find('span').show();
    }).mouseleave(function () {
        $(this).find('span').hide();
    });

    poshytip('init', $('[data-uid]'));

    //bind pullmsg 更新各个消息,包括群组和班级空间,common上显示的
    $('#page').bind('pullmsg', function (e, r) {
        if (r.aboutme) {
            for (var prop in r.aboutme) {
                if (parseInt(r.aboutme[prop]) > 0 && prop != 'unread_count') {
                    var el = $('#header_nav .set1 a');
                    el.find('span#' + prop).html('<span class="sbnum">' + r.aboutme[prop] + '</span>');
                    el.parents('.header_navInfo:first').removeClass('sele').addClass('sele');
                }
            }

            if ($('#header_nav .set1').parents('.header_navInfo:first').hasClass('sele')) {
                document.title = '【 新消息 】';
            }
        }

        if (r.groups) {
            for (var i = 0; i < r.groups.length; i++) {
                if (r.groups[i].c6 == '1') {
                    if (parseInt(r.groups[i].unread_count) > 0) {
                        var czone = $('.topitem[data-key="class"]').find('.top_classlist ul li a[data-gid="' + r.groups[i].id + '"]');

                        if (czone.length > 0) {
                            czone.find('i').remove();

                            czone.append('<i class="grIcon grop1"></i>');
                        }

                        $('.topitem[data-key="class"]').find('i:first').show();
                    }
                } else {
                    if (parseInt(r.groups[i].unread_count) > 0) {
                        var gzone = $('.topitem[data-key="group"]').find('.top_classlist ul li a[data-gid="' + r.groups[i].id + '"]');

                        if (gzone.length > 0) {
                            gzone.find('i').remove();

                            gzone.append('<i class="grIcon grop1"></i>');
                        }

                        $('.topitem[data-key="group"]').find('i:first').show();
                    }
                }
            }
        }
    });
});

var poshytip = function (cate, r) {
    try {
        var poshy = $('[data-uid]').poshytip({
            showTimeout: 200,
            className: 'tip-darkgray',
            alignTo: 'target',
            slide: false,
            alignX: 'center',
            alignY: 'top',
            offsetX: 0,
            showAniDuration: 0,
            offsetY: 7,
            allowTipHover: true,
            hideTimeout: 500,
            fade: true,
            liveEvents: true,
            content: function (updateCallback) {
                var userid = $(this).attr('data-uid');
                $(this).click(function () {
                    setTimeout(function () {
                        $('.tip-darkgray').css('visibility', 'hidden');
                    }, 100);

                });
                $.get($('head').data('vp') + 'home/action-home-getInfo', { userid: userid }, function (data) {
                    if (data) {
                        data = handleException(data);
                        return updateCallback($(data));
                    }
                }, 'json');

                return '';
            }
        });

        if (cate == "update")
            $('[data-uid]').poshytip('update', function (updateCallback) {
                var userid = $(this).attr('data-uid');
                $.get($('head').data('vp') + 'home/action-home-getInfo', { userid: userid }, function (data) {
                    if (data) {
                        data = handleException(data);
                        return updateCallback($(data));
                    }
                }, 'json');

                return '';
            })
    } catch (e) {

    }
};
/**
 * Created by xlzhao3 on 14-3-20.
 * @description 登录页页面逻辑
 */

$(function() {
    var forget_pwd_timer = null;
    // 为文本框添加hover效果
    $(".login_form label.inlined + input.input-text").each(function () {
        var $this = $(this);
        if(!!$this.val()) {
            if($this.attr('id') === 'tb_validcode') {
                // 清空
                $this.val('');
            } else {
                $this.prev("label.inlined").css({
                    'opacity':0.00,
                    'display': 'none'
                });
            }
        }

        $this.focus(function () {
            if($(this).val() == "") {
                $(this).prev("label.inlined").stop().animate({
                    'opacity': 0.35
                },60,'linear');
            }
        });

        $this.keydown(function (e) {
            switch(e.keyCode) {
                // enter
                case 13:
                    $('#btn_submit').click();
                    break;
                // escape
                case 27:
                    $(this).val('');
                default:
                    $(this).prev("label.inlined").stop().css({
                        'opacity':0.00,
                        'display': 'none'
                    });
                    break;
            }
        });

        $this.blur(function () {
            if (!$(this).val()) {
                $(this).prev("label.inlined").css('display', '').stop().animate({
                    'opacity':0.75
                },60,'linear');
            }
        });

        $this.bind('paste', function (e) {
            e.preventDefault();
        });
    });

    $(".login_form label.inlined").click(function(e) {
        $(this).next('input.input-text').focus();
        e.preventDefault();
    });

    $('#forget_pwd').hover(function(e) {
        e.preventDefault();
        $('#forget_pwd_info').stop().animate({'opacity': 1}, 200);
        if(forget_pwd_timer) clearTimeout(forget_pwd_timer);
    }, function(e) {
        forget_pwd_timer = setTimeout(function() {
            $('#forget_pwd_info').animate({'opacity': 0},4000);
        }, 2000);
    });

    // 登录验证
    $('#btn_submit').click(function(e) {
        var validResult = 0x000,
            username = $.trim($('#tb_username').val()),
            pwd = $.trim($('#tb_pwd').val()),
            code = $.trim($('#tb_validcode').val());
        // 在这里只验证是否为空
        !username && (validResult = validResult|0x100);
        !pwd && (validResult = validResult|0x010);
        !code && (validResult = validResult|0x001);

        if(validResult !== 0x000) {
            switch(true) {
                case validResult >= 0x100:
                    printerr('用户名不能为空');
                    break;
                case validResult >= 0x010:
                    printerr('密码不能为空');
                    break;
                case validResult >= 0x001:
                    printerr('验证码不能为空');
                    break;
            }
        } 
        // 无措即提交
        else {
            $.post('index.php?c=user_controller&a=loginAuth',{
                'username':username,'password':pwd,'code':code
            },function(data) {
                var info = eval('('+data+')');
                // 显示错误信息
                if(info.state !== 1) {
                    printerr(info.message);
                    // 更新验证码
                    changecode();
                    // 清空验证码框
                    $('#tb_validcode').val('');

                    switch(info.state) {
                        // 用户名与密码不匹配
                        case -1 :
                            // 清空密码框
                            $('#tb_pwd').val('');
                            // 触发blur事件，来实现placehover效果
                            $(".login_form label.inlined + input.input-text").blur();
                            // 焦点移动到密码文本框
                            $('#tb_pwd').focus();
                            break;
                        // 该用户不存在
                        case -2:
                            // 清空密码框
                            $('#tb_pwd').val('');
                            // 触发blur事件，来实现placehover效果
                            $(".login_form label.inlined + input.input-text").blur();
                            // 焦点移动到用户名文本框
                            $('#tb_username').focus();
                            break;
                        // 验证码输入不正确
                        case -3:
                            // 触发blur事件，来实现placehover效果
                            $(".login_form label.inlined + input.input-text").blur();
                            // 焦点移动到验证码文本框
                            $('#tb_validcode').focus();
                            break;
                    }
                } else {
                    window.location.href = info.data;
                }
            });
        }
        e.preventDefault();
    });

    $('#lbtn_changecode').click(function(e) {
        changecode();
        e.preventDefault();
    });

    // 更新验证码
    function changecode() {
        var timestamp = (new Date()).valueOf();
        $('#img_code').attr('src','./index.php?c=user_controller&a=getCode&_='+timestamp);
    }

    // 显示错误信息
    function printerr(msg) {
        $('#err_msg').text(msg);
    }
});

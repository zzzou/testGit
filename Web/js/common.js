/**
 * 左侧导航页面逻辑
 * @date 2014-3-25 19:47
 * @author xlzhao3
 */

!function(window, $) {
    $(function() {
        // 自定义折叠相关事件
        $('#left_nav').find("dl.collapse > dt").on('click', function () {
            var $this = $(this),
                $ele = $(this).parent('dl'),
                isOn = $ele.hasClass('on'),
                height = isOn ? $this.outerHeight() : $ele[0].scrollHeight,
                action = isOn ? 'remove':'add';
            if(!isOn) {
                $('#left_nav').find('dl.collapse.on').each(function () {
                    $(this).removeClass('on').height($(this).children('dt').outerHeight());
                });
            }
            $ele.height(height)[action+'Class']('on');
        });

        $('#left_nav').find("dd.active").eq(0).parent('dl').each(function () {
            var $this = $(this);
            $this.addClass('on').height($this[0].scrollHeight);
        });
    });
}(window, jQuery);
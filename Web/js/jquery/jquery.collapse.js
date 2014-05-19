/**
 * 折叠选项卡控件
 * @date 2014-3-24 16:24
 * @author xlzhao3
 * @based on bootstrap-collpase.js
 */

!function(factory) {
    if (typeof require === 'function' && typeof exports === 'object' && typeof module === 'object') {
        var target = module['exports'] || exports;
        factory(target);
    } else if (typeof define === 'function' && define['amd']) {
        define(['exports'], factory);
    } else {
        factory();
    }
}(function(exports) {
    var et = exports === undefined ? {} : exports;

    var Collapse = function ( element, options ) {
    this.$element = $(element)
    this.options = $.extend({}, $.fn.collapse.defaults, options)
    if (this.options["parent"]) {
        this.$parent = $(this.options["parent"])
    }

    this.options.toggle && this.toggle();
  }

  Collapse.prototype = {
    // 构造函数
    constructor: Collapse,
    // 打开
    show : function() {
        var self = this,
            actives = this.$parent && this.$parent.find('.in'),
            hasData;

        if (actives && actives.length) {
            hasData = actives.data('collapse');
            actives.collapse('hide');
            // 保证前后data的状态一致, 防止被动触发导致的parent失效
            hasData || actives.data('collapse', null);
        }

        this.$element.removeClass('collapse').addClass('in');
        // 触发用户自定义事件
        this.$element.trigger('show');
        this.$element.stop().animate({'height':this.$element[0].scrollHeight}, 
            this.options.speed, function() {
                $(this).trigger('shown');
            });
    },
    // 隐藏
    hide : function() {
        this.$element.removeClass('in').addClass('collapse');
        // 触发自定义事件
        this.$element.trigger('hide');
        this.$element.stop().animate({'height':0}, 
            this.options.speed, function() {
                $(this).trigger('hidden');
            });
    },
    // 自动
    toggle : function() {
        this[this.$element.hasClass('in') ? 'hide' : 'show']();
    },
    // 重新根据内容大小进行调整
    adjust : function() {
        if(this.$element.hasClass('in')) {
            this.$element.stop().animate({'height':this.$element[0].scrollHeight},
                this.options.speed);
        }
    }
  }

  /* COLLAPSIBLE PLUGIN DEFINITION
  * ============================== */

  $.fn.collapse = function ( option ) {
    return this.each(function () {
        var $this = $(this),
            data = $this.data('collapse'),
            options = typeof option == 'object' && option;
        if (!data) $this.data('collapse', (data = new Collapse(this, options)))
        if (typeof option == 'string') data[option]()
    })
  }

  $.fn.collapse.defaults = {
    toggle: true,
    // 动画速度
    speed: 200
  }

  $.fn.collapse.Constructor = Collapse

  // 添加监听事件
  $(function () {
    $('body').on('click', '.collapse-trig', function (e) {
        var $this = $(this), href,
            target = $this.attr('data-target')
            || e.preventDefault()
            || (href = $this.attr('href')) && href.replace(/.*(?=#[^\s]+$)/, '')
            , option = $(target).data('collapse') ? 'toggle' : $this.data()
        $(target).collapse(option);
    })
  });

});

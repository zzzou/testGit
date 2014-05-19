/**
 * Created with JetBrains PhpStorm.
 * User: xhcao@iflytek.com
 * Date: 14-4-30
 * Time: 下午3:58
 * To change this template use File | Settings | File Templates.
 */

//  初始化uploadify的上传控件
//  _callback  定义回调函数
!function(){
    var _callback = function(data){
        urls = $("#url").val();
        $.ajax({
            url: './index.php?c=propositionItemTopic&a=deleteFile&url='+urls+'&newUrl='+data+'&topicId=<{$topicId}>'
        });
        $("#url").val(data);
    };
    $(function() {
        $(".upload_button").click(
            function () {
                showVideo("upload_video.php",_callback);
            }
        );
    });
}();
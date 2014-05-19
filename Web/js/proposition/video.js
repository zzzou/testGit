/**
 * Created with JetBrains PhpStorm.
 * User: xhcao
 * Date: 14-4-22
 * Time: 上午11:20
 * To change this template use File | Settings | File Templates.
 */
/*---------------------------------------------------
 * 弹出层页面事件定义 */
function playVideo(id){
             var a = $(id).attr('data-video');
             if(a){
                 $('#player').attr("href",a);
                 flowplayer("player", "js/flowplayer/flowplayer-3.2.15.swf");
                 $('#pop_layer').show();
             }else{
                 alert("您还未上传视频")
             }
    }
!function(){
    $(function() {
        $('#lbtn_pop_layer_close').click(function(e) {
            $('#pop_layer').hide();
            $('body').removeClass('pop_layer_on');
            e.preventDefault();
        });
    });
}();
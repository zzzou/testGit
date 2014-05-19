/**
 * Created with JetBrains PhpStorm.
 * User: xhcao@iflytek.com
 * Date: 14-4-29
 * Time: 上午9:12
 * To change this template use File | Settings | File Templates.
 */

    //  上传视频的uploadify  的 配置
/*
    @ url  要返回url路径的元素id
    @ php_url    异步ajax处理请求的php文件名
    @ _callback    上传完成后执行的回调函数的一些操作
 */


    function showVideo(php_url,_callback){
        // 显示 弹框
        $('#pop_layer').show();
        //初始化 上传按钮的配置
        $('#upload_file').uploadify({
            'swf'      		: 	"./js/uploadify/uploadify.swf",
            'uploader' 		: 	"./js/uploadify/"+php_url,
            'cancelImg'		:	"./js/uploadify/uploadify-cancel.png",
            'method'		:	'post',
            'buttonClass'	:  'upload_file',
            'fileTypeDesc'	:	'文件',
            'buttonText' :      '上传文件',
            'multi'				:	false,
            /**
             * 上传成功后触发事件

                @ data :  'uploader' 异步的php文件返回的参数
             */
            'onUploadSuccess' : function(file,data,response) {
                if(data!='false'){
                    _callback(data);
                    setTimeout("$('#pop_layer').hide();$('body').removeClass('pop_layer_on');",3500);
                }else{
                    alert("您好，您上传的视频类型不允许，请选择视频格式的文件！");
                }
            }
        });
    }

//关闭弹框
!function(){
    $(function() {
        $('#lbtn_pop_layer_close2').click(function(e) {
            $('#pop_layer').hide();
            $('body').removeClass('pop_layer_on');
            e.preventDefault();
        });
    });
}();

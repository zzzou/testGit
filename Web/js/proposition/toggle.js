/**
 * Created with JetBrains PhpStorm.
 * User: xhcao
 * Date: 14-4-18
 * Time: 上午11:22
 * To change this template use File | Settings | File Templates.
 */
    //  收起和展开功能
var cnt = 1;
function changeHeight(id){
    if(cnt==1){
        $(id).parents('.ifo_con').find('.ifo_con_text').css({height:"100%"});
        $(id).html('收起');
        cnt++;
    } else{
        $(id).parents('.ifo_con').find('.ifo_con_text').css({height:"38px"});
        $(id).html('展开');
        cnt--;
    }
}
/**
 * Created by zzzou on 14-5-12.
 */
//预览office文件
function view_office(scaleUrl,id,name,num,scaleAnwUrl){
    //把本地的绝对地址换成相对地址
    var scaleArr = scaleUrl.split("/");
    var scaleName = scaleArr[scaleArr.length-1].split(".");
    var scaleFullName = scaleName[scaleName.length-2]+".swf";
    var url = "./resource/swf/"+scaleFullName;
    //实现切换效果
    if(id==0){
        $("#tab03").html("");
        $("#tab01").html("");
        var myc=new Array();
        var muy=new Array();
        muy.push("<h1 > "+name+"("+num+")</h1>")
        myc.push("<h3 id='h1' style='background:white' onclick=\"view_office('"+scaleUrl+"','1','"+name+"','"+num+"');\">测验试卷</h3>");
        myc.push("<h3 id='h2' onclick=\"view_office('"+scaleAnwUrl+"','2','"+name+"','"+num+"');\">答题卡</h3> ");
        $("#tab03").append(myc.join(''));
        $("#tab01").append(muy.join(''));
        $("#h1").css("background","white");
        $("#h2").css("background","#E0E0E0");

    }
    if(id==1){
        $("#h1").css("background","white");
        $("#h2").css("background","#E0E0E0");
    }
    if(id==2){
        $("#h2").css("background","white");
        $("#h1").css("background","#E0E0E0");
    }
    //预览控件的设置
    $('#preview').FlexPaperViewer(
        { config: {
            SwfFile: url,
            Scale: 1.0,
            ZoomTransition: 'easeOut',
            ZoomTime: 0.5,
            ZoomInterval: 0.2,
            FitPageOnLoad: false,
            FitWidthOnLoad: false,
            FullScreenAsMaxWindow: false,
            ProgressiveLoading: false,
            MinZoomSize: 0.2,
            MaxZoomSize: 5,
            SearchMatchAll: false,
            InitViewMode: 'Portrait',
            PrintPaperAsBitmap: false,
            ViewModeToolsVisible: true,
            ZoomToolsVisible: true,
            NavToolsVisible: true,
            CursorToolsVisible: true,
            SearchToolsVisible: false,
            PrintToolsVisible : false,
            localeChain: 'zh_CN'
        }
        });
    jQuery("#preview").children().each(function(){
        jQuery(this).css("height","600px");
    });
}

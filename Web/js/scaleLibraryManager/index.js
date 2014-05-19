
    var ren = /^[0-9]+[0-9]*]*$/;
	//评价维度点击时触发
	function display(num,name){
        var phase = $("#phaseId").val();
        var type =  $("#typeId").val();
        var dimensions =  $("#dimId").val();
        var target =  $("#targetId").val()
		dimensions = num;
		target = ""


        window.location.href='./index.php?c=scaleManager&a=getScaleByCondition&phase_id='+phase+'&dimensions_id='+dimensions+'&target_id='+target+'&type_id='+type;
    }
	//学段点击时触发
	function show(phaseId,phasename){
        var phase = $("#phaseId").val();
        var type =  $("#typeId").val();
        var dimensions =  $("#dimId").val();
        var target =  $("#targetId").val()
		phase=phaseId;
        window.location.href='./index.php?c=scaleManager&a=getScaleByCondition&phase_id='+phase+'&dimensions_id='+dimensions+'&target_id='+target+'&type_id='+type;
	}
	
	//评价指标点击时触发
	function targe(num2,targetname){
        var phase = $("#phaseId").val();
        var type =  $("#typeId").val();
        var dimensions =  $("#dimId").val();
        var target =  $("#targetId").val()
		target=num2;
        window.location.href='./index.php?c=scaleManager&a=getScaleByCondition&phase_id='+phase+'&dimensions_id='+dimensions+'&target_id='+target+'&type_id='+type;
	}
	
	//测评方式点击事件
	function typeDis(typeNum,typename){
        var phase = $("#phaseId").val();
        var type =  $("#typeId").val();
        var dimensions =  $("#dimId").val();
        var target =  $("#targetId").val()
		
		type = typeNum;
        window.location.href='./index.php?c=scaleManager&a=getScaleByCondition&phase_id='+phase+'&dimensions_id='+dimensions+'&target_id='+target+'&type_id='+type;
	}

    //下载
	function downfile(str)
	{
	  window.location.href = str;
	}
	
	//切换到编辑页面
	function goEdit(id){
		 
		window.location.href = "./index.php?c=scaleManager&a=scaleEditIndex&scale_id="+id;
	}

    $(document).ready(function(){
        truncateText()
    })
	//实现文字省略效果
    function truncateText(len) {
        len = len || 60;
        $('p.truncate').each(function(i, item) {
            var originText = $(this).text(),
                inner = originText.substring(0, len);
            if(originText === inner) return 'true';
            inner.replace(/\w+$/, '');

            inner += '<a href="javascript:;">...</a>'
            $(this).html(inner);
        });
    }

//评价维度、评价指标联动效果
function showTar(dimId){
	$.ajax({
		  url: './index.php?c=scaleManager&a=showTargetByDim&dim_id='+dimId,
		  success:function(data){
			  
			  $("#tarse").html("");
			  var re = eval(data);
			  var len = re.length;
			  var tar = new Array();
			  for(var i =0;i<len;i++){
				  tar.push("<option value='"+re[i].target_id+"'>"+re[i].target_name+"</option>");
			  }
			  
			  $("#tarse").append(tar.join(''));
			  
		  } 
		});
}

//切换选中效果
function isSelec(id){
	for(var i=1;i<3;i++){
		if(id===i){
			$("#in"+i).addClass("selected");
            $("#inp4").val(id);
		}else{
			$("#in"+i).removeClass("selected");
		}
	}
}

//添加量表库方法
function sub(){

	var valArr = [];
	$('input:checkbox[name="cb[]"]:checked').each(function () { valArr.push($(this).val()) })
	
	$("#inp3").val(valArr.join(','));

	$("#inp5").val($("#te1").val());
	$("#inp6").val($("#te2").val())

    var ver = 0;
    //提交验证
    if($("#inp9").val()==""){
        $(".caution1").show();
        ver =1;
    }else{
        $(".caution1").hide();
    }
    if($("#inp10").val()==""){
        $(".caution2").show();
        ver =1;
    }else{
        $(".caution2").hide();
    }
    if($("#inp1").val()==""){
        $(".caution3").show();
        ver =1;
    }else{
        $(".caution3").hide();
    }
    if($("#inp3").val()==""){
        $(".caution4").show();
        ver =1;
    }else{
        $(".caution4").hide();
    }
    if($("#te1").val()==""){
        $(".caution5").show();
        ver =1;
    }else{
        $(".caution5").hide();
    }
    if($("#te2").val()==""){
        $(".caution6").show();
        ver =1;
    }else{
        $(".caution6").hide();
        if(!ren.test($("#te2").val())){
            $(".caution7").show();
            ver =1;
        }else{
            $(".caution7").hide();
        }

    }
    if(ver == 1){
        return;
    }

    $("#scale_form").ajaxSubmit({success:function(data){
            if(data == 'false'){
                alert("已存在相同名称的量表，请修改量表名称");
            }else{
                window.location.href="./index.php?c=scaleManager&a=addSuccess&scale_id="+data;
            }
        }
    })
}

//转换到添加页面
function goAdd(){
	window.location.href="./index.php?c=scaleManager&a=addScaleIndex";
	
} 

!function(){
    $(function() {
        $('#lbtn_pop_layer_close2').click(function(e) {
            $('#pop_layer').hide();
            $('body').removeClass('pop_layer_on');
            e.preventDefault();
        });
    });
}();

//切换到主页
function showScale(){
	window.location.href = "./index.php?c=scaleManager&a=getScaleByCondition&phase_id=0&dimensions_id=0&target_id=&type_id=0";
}

//删除量表库方法
function deleScale(scaleId){
	window.location.href = "./index.php?c=scaleManager&a=deleteScale&scale_id="+scaleId;
}

//编辑提交事件
function edit(){
    $(".pop_box_prt").hide();
	var valArr = [];
	$('input:checkbox[name="cb[]"]:checked').each(function () { valArr.push($(this).val()) })

	$("#inp3").val(valArr.join(','));

	$("#inp5").val($("#te1").val());
	$("#inp6").val($("#te2").val())


    //提交验证
    var vers = 0;
    if($("#inp1").val()==""){
        $(".caution3").show();
        vers = 1;
    }else{
        $(".caution3").hide();
    }
    if($("#inp3").val()==""){
        $(".caution4").show();
        vers = 1;
    }else{
        $(".caution4").hide();
    }
    if($("#te1").val()==""){
        $(".caution5").show();
        vers = 1;
    }else{
        $(".caution5").hide();
    }
    if($("#te2").val()==""){
        $(".caution6").show();
        vers = 1;
    }else{
        $(".caution6").hide();
        if(!ren.test($("#te2").val())){
            $(".caution7").show();
            vers = 1;
        }else{
            $(".caution7").hide();
        }
    }

    if(vers == 1){
        return;
    }

    $("#edit_scale_form").ajaxSubmit({success:function(data){
        if(data == 'false'){
            alert("已存在相同名称的量表，请修改量表名称");
        }else{
           showScale();
        }
    }
    })

}

!function(){
	//编辑量表
    var _callback1 = function(data){
    	if(data!='false'){
    		var strArr = data.split("/");
    		
        	$("#inp11").val(strArr[strArr.length-1]);
        	$("#inpu3").val(data);
        }else{
            alert("您好，您上传的视频类型不允许，请选择swf视频格式的文件！");
        }
    };

    //编辑答题卡
    var _callback2 = function(data){
    	if(data!='false'){
    		var strArr = data.split("/");
    		
        	$("#inp12").val(strArr[strArr.length-1]);
        	$("#inpu4").val(data);
        }else{
            alert("您好，您上传的视频类型不允许，请选择swf视频格式的文件！");
        }
    };

    //新增量表
    var _callback3 = function(data){
    	if(data!='false'){
    		var strArr = data.split("/");
        	$("#inp9").val(strArr[strArr.length-1]);
        	$("#inpu1").val(data);
        }else{
            alert("您好，您上传的视频类型不允许，请选择swf视频格式的文件！");
        }
    };
    
    //新增答题卡
    var _callback4 = function(data){
    	if(data!='false'){
    		var strArr = data.split("/");
        	$("#inp10").val(strArr[strArr.length-1]);
        	$("#inpu2").val(data);
        }else{
            alert("您好，您上传的视频类型不允许，请选择swf视频格式的文件！");
        }
    };
    $(function() {
        $("#upload1").click(
            function () {
                showVideo("uploadify.php",_callback1);
            }
        );
        $("#upload2").click(
            function () {
                showVideo("uploadify.php",_callback2);
            }
        );
        $("#upload3").click(
            function () {
                showVideo("uploadify.php",_callback3);
            }
        );
        $("#upload4").click(
            function () {
                showVideo("uploadify.php",_callback4);
            }
        );

        $("#pageUp").click(
            function(){
                nowNum --;
                insert($("#data").val());
            }
        );

        $("#pageDown").click(
            function(){
                nowNum ++;
                insert($("#data").val());
            }
        );

        $("#pageIndex").click(
            function(){
                nowNum =0;
                insert($("#data").val());
            }
        );

        $("#pageMin").click(
            function(){
                nowNum=pagerNum;
                insert($("#data").val());
            }
        )
    });
    /**
     * fancybox控件设置
     */
    $(".btn_fb").fancybox({
        padding: 0,
        openEffect : 'elastic',
        openSpeed  : 150,
        closeEffect : 'elastic',
        closeSpeed  : 150,

        overlayShow:true,
        helpers : {

        }
    });
}();
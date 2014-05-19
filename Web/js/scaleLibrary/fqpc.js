
/**
 * 选择框和全选框切换
 * @param grp_id 全选框id
 */
function uncheckGrp(grp_id){
	document.getElementById(grp_id).checked=document.getElementById(grp_id).checked&0;
}

/**
 * 点击全选选中div中所有选择框
 * @param div_id 需要被选择的div id
 * @param grp_id 全选框的id
 */
function selectGroup(div_id,grp_id){
	var g_div=document.getElementById(div_id);
	var grp=document.getElementById(grp_id);
	var eles=g_div.getElementsByTagName("input");
	for(var i =0;i<eles.length;i++){
		if(eles[i].disabled==false){
			eles[i].checked=grp.checked;
		}
	}
}


$(function(){
    $( "#begin_date" ).datepicker({
		defaultDate: "+1w",
		minDate: new Date(),
		changeMonth: true,
		numberOfMonths: 3,
		dateFormat: 'yy-mm-dd',
		onClose: function( selectedDate ) {
			$( "#end_date" ).datepicker( "option", "minDate", selectedDate );
		}
    });
    $( "#end_date" ).datepicker({
		defaultDate: "+1w",
		changeMonth: true,
		numberOfMonths: 3,
		dateFormat: 'yy-mm-dd',
		onClose: function( selectedDate ) {
			$( "#begin_date" ).datepicker( "option", "maxDate", selectedDate );
		}
    });
	
    //预览弹出div
    $("#yl").fancybox({
    	padding: 0,
		openEffect : 'elastic',
		openSpeed  : 150,
		closeEffect : 'elastic',
		closeSpeed  : 150,
		closeClick : false,
		overlayShow:true,
		helpers : {
			
		}
	});
    
    
	//发起测评
	$("#fqcp").click(function(){
		
		var check_value="";
		var cpmc =$("#pcmc").val();
		var start_time=$("[name='start_time']").val();
		var end_time=$("[name='end_time']").val();
		var cpwd =$(".fqcp_cpwd").html();
		var cpzb =$(".fqcp_cpzb").html();
		
		$("#allcheckbox input:checkbox[name='check']:checked").each(function(){
			check_value+=$(this).val()+" ";
		})	
		var sylb =$("#testNum").html()+$("#testName").html();
		
		$(".onError").css("display","none");
		if(cpmc==""){
			$("#cpmc_error").css("display","inline");
			$("#cpmc_error").html("请输入测评名称 !");
		}else if(start_time==""||end_time==""){
			$("#time_error").css("display","inline");
			$("#time_error").html("请选择任务提交期限");
		}else if(check_value==""){
			$("#cpdx_error").css("display","inline");
			$("#cpdx_error").html("请选择测评对象!");
		}else{
			
			$("#gb1 .fl").html(cpmc);
			$("#gb1 .gb1_start_time").html(start_time);
			$("#gb1 .gb1_end_time").html(end_time);
			$("#gb1 .gb1_cpdx").html("测评对象："+check_value);
			$("#gb1 .gb1_cpwd").html("测评维度： "+cpwd);
			$("#gb1 .gb1_cpzb").html("测评指标："+cpzb);
			$("#gb1 .gb1_sylb").html("使用量表："+sylb);
			$("#fqcp").fancybox({
				padding: 0,
				openEffect : 'elastic',
				openSpeed  : 150,
				closeEffect : 'elastic',
				closeSpeed  : 150,
				closeClick : true,
				helpers : {
					overlay : null
				}
			});
			
		}
		
		
	});
			
		
	$("#cpqx").click(function(){
		window.location.href="./index.php?c=scale&a=getScaleLibrary";
	});
	
	
	//提交表单(发布信息)
	$("#save").click(function(){
		var check_value="";
		$("#allcheckbox input:checkbox[name='check']:checked").each(function(){
			check_value+=$(this).val()+" ";
		})
		//测评名称
		var cpmc=$("#gb1 .fl").html();
		//测评维度
		var fqcp_cpwd=$(".fqcp_cpwd").html();
		//测评指标
		var fqcp_cpzb=$(".fqcp_cpzb").html();
		//测评对象
		var fqcp_cpdx=check_value;
		//测评ID
		var scaleId=$(".scaleId").html();
		$("#proposition_form").append('<input type="hidden" name="state" value="1" />');
		//添加展示的文本
		$("#proposition_form").append('<input type="hidden" name="pcmc" value="'+cpmc+'" />');
		$("#proposition_form").append('<input type="hidden" name="fqcp_cpwd" value="'+fqcp_cpwd+'" />');
		$("#proposition_form").append('<input type="hidden" name="fqcp_cpzb" value="'+fqcp_cpzb+'" />');
		$("#proposition_form").append('<input type="hidden" name="gb1_cpdx" value="'+fqcp_cpdx+'" />');
		$("#proposition_form").append('<input type="hidden" name="fqcp_lbnum" value="'+scaleId+'" />');
		$("#proposition_form").submit();

	});
})


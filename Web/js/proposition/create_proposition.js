/*!
 * lhgcore Dialog Plugin 
 * Copyright (c) 2014 By qifang
 */

//全局变量
var classId=9;
var array = [
{"id":1,"school":"学校1","grade":10.00,"name":"name1","sex":"男","position":"1","subject":"EST-1","professional":"professional","cityId":1,"areaId":1,"schoolId":1,"classId":1},
{"id":2,"school":"学校2","grade":12.00,"name":"name2","sex":"男","position":"2","subject":"EST-10","professional":"professional","cityId":1,"areaId":1,"schoolId":1,"classId":1},
{"id":3,"school":"学校3","grade":12.00,"name":"name3","sex":"女","position":"6","subject":"EST-11","professional":"professional","cityId":1,"areaId":1,"schoolId":1,"classId":2},
{"id":4,"school":"学校4","grade":12.00,"name":"name4","sex":"男","position":"8","subject":"EST-12","professional":"professional","cityId":1,"areaId":1,"schoolId":1,"classId":2},
{"id":5,"school":"学校5","grade":12.00,"name":"name5","sex":"男","position":"Adult","subject":"EST-13","professional":"professional","cityId":1,"areaId":1,"schoolId":1,"classId":3},
{"id":6,"school":"学校6","grade":12.00,"name":"name6","sex":"女","position":"Tailless","subject":"EST-14","professional":"professional","cityId":1,"areaId":1,"schoolId":1,"classId":3},
{"id":7,"school":"学校7","grade":12.00,"name":"name7","sex":"女","position":"With tail","subject":"EST-15","professional":"professional","cityId":1,"areaId":1,"schoolId":1,"classId":3},
{"id":8,"school":"学校8","grade":12.00,"name":"name8","sex":"男","position":"Adult ","subject":"EST-16","professional":"professional","cityId":1,"areaId":1,"schoolId":1,"classId":4},
{"id":9,"school":"学校9","grade":12.00,"name":"name9","sex":"男","position":"Adult ","subject":"EST-17","professional":"professional","cityId":1,"areaId":1,"schoolId":1,"classId":4}
];

var arrayNew =new Array();
var flag =0;

$(function(){
    $( "#start_time" ).datepicker({
		defaultDate: "+1w",
		minDate: new Date(),
		changeMonth: true,
		dateFormat: 'yy-mm-dd',
		onClose: function( selectedDate ) {
			$( "#end_time" ).datepicker( "option", "minDate", selectedDate );
		}
    });
    $( "#end_time" ).datepicker({
		defaultDate: "+1w",
		changeMonth: true,
		dateFormat: 'yy-mm-dd',
		onClose: function( selectedDate ) {
			$( "#start_time" ).datepicker( "option", "maxDate", selectedDate );
		}
    });

	//发起命题但弹出层时触发验证
	$(".btn_fb").click(function(){
		var start_time=$("[name='start_time']").val();
    	var end_time=$("[name='end_time']").val();
    	var organzise=$("#nameSpan1").html();
    	var checkState=$("#checkState").val();
    	var title=$(".edit_task").val();
        var saveState=$(".saveState").val();
		$(".onError").css("display","none");
		if(title=="")
		{
			$("#time_error").css("display","inline");
			$("#time_error").html("请输入任务名称 !");
		}
		else if(start_time==""||end_time=="")
		{
			$("#time_error").css("display","inline");
			$("#time_error").html("请输入任务提交期限 !");
			
		}else if(organzise=="0人")
		{
			$("#organzise_error").css("display","inline");
		}
		else{
			//fancybox
			$(".btn_fb").fancybox({
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
	})
	
	//提交表单(发布信息)
	$("#save").click(function(){
		var start_time=$("[name='start_time']").val();
    	var end_time=$("[name='end_time']").val();
    	var organzise=$("#nameSpan1").html();
    	var checkState=$("#checkState").val();
    	var title=$(".edit_task").val();
        var saveState=$(".saveState").val();
		$(".onError").css("display","none");
		if(title=="")
		{
			$("#time_error").css("display","inline");
			$("#time_error").html("请输入任务名称 !");
		}
		else if(start_time==""||end_time=="")
		{
			$("#time_error").css("display","inline");
			$("#time_error").html("请输入任务提交期限 !");
			
		}else if(organzise=="0人")
		{
			$("#organzise_error").css("display","inline");
		}
		else{
			if(saveState=="ok")
			{
				$(".saveState").val("");
			}
			$("#state").val('1');
			$("#proposition_form").submit();
		}
		
		
	});
	
	
	//提交表单(保存信息)
	$("#task_save").click(function(e){
		function show(){
			$(".mtbc").css("display","none");
		}
		var start_time=$("[name='start_time']").val();
    	var end_time=$("[name='end_time']").val();
    	var organzise=$("#nameSpan1").html();
    	var title=$(".edit_task").val();
		e.preventDefault();
		$(".onError").css("display","none");
		if(title=="")
		{
			$("#time_error").css("display","inline");
			$("#time_error").html("请输入任务名称 !");
		}
		else if(start_time==""||end_time=="")
		{
			$("#time_error").css("display","inline");
			$("#time_error").html("请输入任务提交期限 !");
			
		}else if(organzise=="0人")
		{
			$("#organzise_error").css("display","inline");
		}
		else{
			$("#state").val('0');
			$("#proposition_form").append('<input type="hidden" class="saveState" name="saveState" value="ok" />');	
			$("#proposition_form").ajaxSubmit({success:function(data){
				var id=eval('('+data+')').id;
				$("#proposition_form").attr('action', 'index.php?c=proposition&a=save&id='+id);
			}});
			$(".mtbc").css("display","inline");
			setTimeout(show,4000);
			
		}
		
	});
	

	//点击发布任务jquery获取表单内容
	$("#task_button").click(function(){
    	var name=$(".renw_cont .edit_task").val();
    	
    	var study_section=$("[name='study_section']").val();
    	var subject=$("[name='subject']").val();
    	var object=$("[name='object']").val();
    	var type=$("[name='type']").val();
    	var Vote1=$(".renw_cont input[type='radio']:checked").val();
    	var exam_time=$("[name='exam_time']").val();
    	var start_time=$("[name='start_time']").val();
    	var end_time=$("[name='end_time']").val();
    	var organzise=$("#nameSpan1").html();
    	var check=$("#nameSpan2").html();
    	var teacher=$("#nameSpan3").html();
    	
    	$("#demo .fl").html(name);
    	$("#demo .study_section").html("学段："+study_section);
    	$("#demo .type").html("考试类型："+type);
    	$("#demo .subject").html("学科："+subject);
    	$("#demo .object").html("考试对象："+object);
    	$("#demo .Vote1").html("试卷难度："+Vote1);
    	$("#demo .start_time1").html(start_time);
    	$("#demo .end_time1").html(end_time);
    	$("#demo .organzise").html(organzise);
    	$("#demo .check").html(check);
    	$("#demo .teacher").html(teacher);
    });
	
	
	
	/*!
	 * lhgcore Dialog Plugin 
	 * Copyright (c) 2014 By wqyan
	 */
	
    

       $("#p").panel({
			         closed:true,
						 closable:true,
						 onBeforeClose:function(){
					      $("#kwzzr").show();
					 }
					// collapsible:true
			 });

			  $("#p2").panel({
			         closed:true,
						 closable:true,
						 onBeforeClose:function(){
					      $("#kwzzr2").show();
					 }
					// collapsible:true
			 });

			   $("#p3").panel({
			         closed:true,
						 closable:true,
						 onBeforeClose:function(){
					      $("#kwzzr3").show();
					 }
					// collapsible:true
			 });



         $('#cc').combobox({
			url:'./js/json/combobox_data.json',
			width:80,
			method:'get',
			valueField:'id',
			textField:'text'
		 });

		   $('#cc2').combobox({
				url:'./js/json/combobox_data2.json',
			width:80,
			method:'get',
			valueField:'id',
			textField:'text'
		 });

		   $('#cc3').combobox({
				url:'./js/json/combobox_data3.json',
			width:90,
			method:'get',
			valueField:'id',
			textField:'text'
		  });

		 $('#cc4').combobox({
			url:'./js/json/combobox_data4.json',
			width:100,
			method:'get',
			valueField:'id',
			onSelect:function(record){
			  // alert(record.id);
			   classId = record.id;
			},
			textField:'text'
		 });

         $('#cc5').combobox({
			url:'./js/json/combobox_data.json',
			width:80,
			method:'get',
			valueField:'id',
			textField:'text'
		 });

		   $('#cc6').combobox({
				url:'./js/json/combobox_data2.json',
			width:80,
			method:'get',
			valueField:'id',
			textField:'text'
		 });

		   $('#cc7').combobox({
				url:'./js/json/combobox_data3.json',
			width:90,
			method:'get',
			valueField:'id',
			textField:'text'
		  });

		 $('#cc8').combobox({
			url:'./js/json/combobox_data4.json',
			width:100,
			method:'get',
			valueField:'id',
			onSelect:function(record){
			  // alert(record.id);
			   classId = record.id;
			},
			textField:'text'
		 });
      
        $('#cc9').combobox({
			url:'./js/json/combobox_data.json',
			width:80,
			method:'get',
			valueField:'id',
			textField:'text'
		 });

		   $('#cc10').combobox({
				url:'./js/json/combobox_data2.json',
			width:80,
			method:'get',
			valueField:'id',
			textField:'text'
		 });

		   $('#cc11').combobox({
				url:'./js/json/combobox_data3.json',
			width:90,
			method:'get',
			valueField:'id',
			textField:'text'
		  });

		 $('#cc12').combobox({
			url:'./js/json/combobox_data4.json',
			width:100,
			method:'get',
			valueField:'id',
			onSelect:function(record){
			  // alert(record.id);
			   classId = record.id;
			},
			textField:'text'
		 });

			 $("#btn").bind("click",function(){
			 
                arrayNew.length =0;   
                for(var i=0;i<array.length;i++)
					    {
					       if(""==$("#searchCondition").val())
					       {			     
					        if(classId ==array[i].classId )
					         {
					            //加入一个数组
					            arrayNew.push(array[i]);
					         }
					         
					      }else{
					      
							      if(classId ==array[i].classId && array[i].name.match($("#searchCondition").val()))
							      {
							          //加入一个数组
							            arrayNew.push(array[i]);
							      }
					      
					      }
					    }
					    
					    if(9==classId)
					    {
					      if(""==$("#searchCondition").val())
					      {
					       for(var i=0;i<array.length;i++)
					       {
					            arrayNew.push(array[i]);
					       }				      
					      }else{				      
					        for(var i=0;i<array.length;i++)
					       {
					         
					           if(array[i].name.match($("#searchCondition").val()))
							      {
							          //加入一个数组
							            arrayNew.push(array[i]);
							      }					      
							}
					      }
					      
					    }                      
                  $("#dg").datagrid("loadData",arrayNew);                               
			 });

			  $("#btnSave").bind("click",function(){
			    var selectRow =  $("#dg").datagrid("getSelected");
			   
			   if(selectRow==null)
			   {
			     $.messager.alert('warning','请选择一个考务组织人','info');		     
			   }else{
				   var ids= selectRow.id;
				   $("input[name='organzise']").remove();
				   $("input[name='organziseName']").remove();
				   $("#proposition_form").append('<input type="hidden" name="organzise" value="'+ids+'" />');
				   $("#proposition_form").append('<input type="hidden" name="organziseName" value="'+selectRow.name+'"/>');
				   $("#demo .organzise").html('1人'+'('+selectRow.name+')');
			       $("#kwzzPerson").html(selectRow.name); 
				   $("#nameSpan1").html('1人'+'('+selectRow.name+')'); 
				   $("#p").panel("close");
			   }			   
			 });

			   $("#btnSave2").bind("click",function(){
			    var selectRows =  $("#dg2").datagrid("getSelections");
			   if(selectRows==null||selectRows.length ==0)
			   {
			     $.messager.alert('warning','请至少选择一个试题审核人再保存','info');		     
			   }else{

				     var names ="(";
				     var userNames="";
				     var ids;
				     $("input[name='checktype[id][]']").remove();
	                 $("input[name='checktype[name][]']").remove();
                    for(var i=0;i<selectRows.length;i++)
				    {
	                    ids= selectRows[i].id;
	                   
	                    $("#proposition_form").append('<input type="hidden" name="checktype[id][]" value="'+ids+'"/>');
	                    $("#proposition_form").append('<input type="hidden" name="checktype[name][]" value="'+selectRows[i].name+'"/>');
					  if(i==(selectRows.length-1))
						{   
						    userNames=userNames+selectRows[i].name;
					        names = names+ selectRows[i].name;
					        
					        
					   }else{
						  userNames=userNames+selectRows[i].name+",";
					      names = names+ selectRows[i].name+",";
					   }
					}

					names =names +")";
				   $("#demo .check").html(selectRows.length+'人'+names);
				   $("#stshrPersonNumber").html(selectRows.length+'人');
			       $("#stshrPerson").html(names); 
				   $("#nameSpan2").html(selectRows.length+'人'+names); 
				   $("input[name='auditor']").remove();
				   $("#proposition_form").append('<input type="hidden" name="auditor" value="'+userNames+'"/>');
				   $("#p2").panel("close");
			   }			   
			 });

			   $("#btnSave3").bind("click",function(){
				var  ids;
			    var selectRows =  $("#dg3").datagrid("getSelections");
			    if(selectRows==null||selectRows.length ==0)
			   {
			     $.messager.alert('warning','请至少选择一个命题教师再保存','info');		     
			   }else{
				   var names ="(";
				   var userNames="";
				   $("input[name='teacher[id][]']").remove();
	               $("input[name='teacher[name][]']").remove();
                    for(var i=0;i<selectRows.length;i++)
				    {
                     ids=selectRows[i].id;
                     $("#proposition_form").append('<input type="hidden" name="teacher[id][]" value="'+ids+ '"/>');
  				     $("#proposition_form").append('<input type="hidden" name="teacher[name][]" value="'+selectRows[i].name+'"/>');
					  if(i==selectRows.length-1)
						{   
						    userNames=userNames+selectRows[i].name;
					        names = names+ selectRows[i].name;
					  }else{
						  userNames=userNames+selectRows[i].name+",";
					      names = names+ selectRows[i].name+",";
					   }
					}

					names =names +")";

                   $("#demo .teacher").html(selectRows.length+'人'+names);
                   $("#mtlsPersonNumber").html(selectRows.length+'人');
			       $("#mtlsPerson").html(names); 
				   $("#nameSpan3").html(selectRows.length+'人'+names); 
				   $("input[name='assign_teacher']").remove();
				   $("#proposition_form").append('<input type="hidden" name="assign_teacher" value="'+userNames+'"/>');
				   $("#p3").panel("close");
			   }			   
			 });


            
            
            $("#editKwzzr").bind("click",function(){
            	$("#organzise_error").css("display","none");
			    $("#kwzzr").hide();
			    $("#p").panel("open");
			});

			  $("#editStshr").bind("click",function(){
			    $("#kwzzr2").hide();
			    $("#p2").panel("open");
			});

			 $("#editMtls").bind("click",function(){
			    $("#kwzzr3").hide();
			    $("#p3").panel("open");
			});
            
          
});

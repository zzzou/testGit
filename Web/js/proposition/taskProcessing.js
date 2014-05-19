Array.prototype.remove=function(dx)
{
	if(isNaN(dx)||dx>this.length){
		return false;
	}
	for(var i=0,n=0;i<this.length;i++){
		if(this[i]!=this[dx]){
　　　　　　this[n++]=this[i]
		}
	}
	this.length-=1 
}

$(function(){
	
   var problemType = [{ "id": "1", "text": "选择题","selected":true }, { "id": "2", "text": "现代文阅读" },
	                   { "id": "3", "text": "文言文阅读" }, { "id": "4", "text": "默写" }, { "id": "5", "text": "诗歌鉴赏" }
	                   , { "id": "6", "text": "语言表达" }, { "id": "7", "text": "名著导读" }, { "id": "8", "text": "作文" }];
	               
   var  problemNumber =[{ "id": "1", "text": "10","selected":true },
                        { "id": "2", "text": "20" },
                        { "id": "3", "text": "30" },
                        { "id": "4", "text": "40" }];
   var count =0;
   var twoProblemType =[];  

   $("input[name='proposition_type[name][]']").each(
	  function(){
		  twoProblemType.push($(this).val());
	  }
    );
	
   var  delTwoProblemType =  function(pro)
   {
      for(var i=0;i<twoProblemType.length;i++)
   	   {
   	      if(twoProblemType[i]==pro)
   	    	  {
   	    	    twoProblemType.remove(i);
   	    	  }	   
   	   }   
   };
   
   var isCon =  function(val){
		for(var i=0; i<twoProblemType.length; i++){
			if(twoProblemType[i] == val)
				return true;
		}
		return false;
	};
	
	var commonCombobox = function(id,url,width)
	{
		 $('#'+id).combobox({
		    	method:'get',
				url: url,
				width:width,
				valueField:'id',
				textField:'text'
			});
	};
	
   removeDiv = function (id){
		delTwoProblemType($(id).parent().find("span").find("input").val());
		$(id).parent().remove();
	};
   
    $("#p").panel({
        closed:true,
			 closable:true,
			 onBeforeClose:function(){
		      $("#shrxx").show();
		 }
    });
    
    $("#p2").panel({
        closed:true,
			 closable:true,
			 onBeforeClose:function(){
		      $("#mtrxx").show();
		 }
		// collapsible:true
     });
    
    $("#shrxxAdd").bind("click",function(){
    	$("#shrxx").hide();
		$("#p").panel("open");
    	
    });
    
	$("#mtrxxAdd").bind("click",function(){
	    $("#mtrxx").hide();
	    $("#p2").panel("open");
	});
    
	  
	$('#jiaocaiInformation').combobox({
		url: 'js/json/chinese.json',
		width:130,
		valueField:'Id',
		onSelect:function(record){
            var url = 'js/json/chinese_'+record.Id+'.json';
            $.getJSON(url, function(data){
            	$('#tt2').tree('loadData', data);
            });
		},
		textField:'Title'
	});
	
	$('#tt').tree({
		url: 'js/json/chinese_1316.json',
		lines:true,
		checkbox:true,
		onCheck:function(){
			$("#selectItemsKnowledge").html("");
			$("#knowledge_points_div").html("");
			var nodes = $('#tt').tree('getChecked');
			for(var i=0; i<nodes.length; i++){
				$("#selectItemsKnowledge").append('<option value="' + nodes[i].id + '">' + nodes[i].text + '</option>');
				$("#knowledge_points_div").append('<input name="knowledge_points[' + nodes[i].id + ']" value="' + nodes[i].text + '"></option>');
			}
		}
	});
	  
	 $('#tt2').tree({
		url: 'js/json/chinese_12151.json',
		method:'get',
		lines:true,
		onCheck:function(node, checked){
			$("#selectItems").html("");
			$("#textbooks_div").html("");
			var nodes = $('#tt2').tree('getChecked');
			for(var i=0; i<nodes.length; i++){
				$("#selectItems").append('<option value="' + nodes[i].id + '">' + nodes[i].text + '</option>');
				$("#textbooks_div").append('<input name="textbooks[' + nodes[i].id + ']" value="' + nodes[i].text + '"></option>');
			}
		},
		checkbox:true
	});

	  
    $('#cc').combobox({
    	method:'get',
		url:'js/json/combobox_data.json',
		width:100,
		valueField:'id',
		onSelect:function(record){
		   classId = record.id;
		},
		textField:'text'
	 });
    
    commonCombobox('cc2','js/json/combobox_data2.json',80);
    commonCombobox('cc3','js/json/combobox_data3.json',80);
    commonCombobox('cc4','js/json/combobox_data4.json',90);

    commonCombobox('cc5','js/json/combobox_data.json',100);
    commonCombobox('cc6','js/json/combobox_data2.json',80);
    commonCombobox('cc7','js/json/combobox_data3.json',80);
    commonCombobox('cc8','js/json/combobox_data4.json',90);
  
	$("#btnSave2").bind("click",function(){
		var selectRows =  $("#dg2").datagrid("getSelections");
		if(selectRows==null||selectRows.length==0){
		 	$.messager.alert('warning','请至少选择一个命题人信息再保存','info');		     
		}else{
			$("#assign_teachers").html('');
		    var names ="(";
		    var ids;
			for(var i=0;i<selectRows.length;i++) {
		        ids= selectRows[i].id;
		        $("#assign_teachers").append('<input type="hidden" name="teacher[id][]" value="'+ids+'"/>');
		        $("#assign_teachers").append('<input type="hidden" name="teacher[name][]" value="'+selectRows[i].name+'"/>');
			  	if(i==(selectRows.length-1)){
			        names = names+ selectRows[i].name;        
				}else{
			    	names = names+ selectRows[i].name+",";
				}
			}

			names =names +")";
			$("#stshrPerson").html(names); 
			$("#stshrPerson2").html(names); 
			$("#stshrPersonNumber").html(selectRows.length); 
			$("#stshrPerson2Number").html(selectRows.length);  
			$("#p2").panel("close");
		}			   
	});
		   
	$("#btnSave").bind("click",function(){
		var selectRows =  $("#dg").datagrid("getSelections");
		if(selectRows==null||selectRows.length==0){
			$.messager.alert('warning','请至少选择一个审核人信息再保存','info');		     
		}else{
			$("#auditors").html('');
		     var names ="(";
		     var ids;
		  	for(var i=0;i<selectRows.length;i++){
		        ids= selectRows[i].id;
		        $("#auditors").append('<input type="hidden" name="checktype[id][]" value="'+ids+ '"/>');
				$("#auditors").append('<input type="hidden" name="checktype[name][]" value="'+selectRows[i].name+'"/>');
			  	if(i==(selectRows.length-1)){
			        names = names+ selectRows[i].name;        
				}else{
			      names = names+ selectRows[i].name+",";
				}
			}

			names =names +")";
		   $("#kwzzPerson").html(names); 
		   $("#kwzzPerson2").html(names); 
		   $("#kwzzPersonNumber").html(selectRows.length); 
		   $("#kwzzPerson2Number").html(selectRows.length); 
		   $("#p").panel("close");
		}			   
	});
		   
	var startTime=$(".start_time").val();
	var endTime=$(".end_time").val();
	$('#topic_time').datepicker({minDate:startTime, maxDate:endTime});

	$('#problemType').combobox({
		valueField:'id',
		textField:'text',
		onSelect: function(rec){
		}
	});
	
	$("#problemType").combobox("loadData", problemType);
	$('#problemNumber').combobox({
		valueField:'id',
		textField:'text'
	});
	
	$('#problemNumber').combobox("loadData", problemNumber);
	
	$("#button").click(function(){
		count++;
		var pro = $("#problemType").combobox("getText");
	    var ct = $("#problemNumber").combobox("getText");
	    
	    if(pro==""||ct==""){
    	   $.messager.alert('Warning','请选择题型或者题数！！');
    	   return;
    	}
	   	if(isCon(pro)) {
		    $.messager.alert('Warning','这种题型已经选择过了！！');
		    return;
	   	}
	    twoProblemType.push(pro);
	    $("#twoLevel").before( "<div style='display: block;'  class='querenmt_cont' id='wqyan"+count+ "' >"+pro+":"+ct+"道<span>"+
	    		"<input type='hidden' name='proposition_type[name][]'  value='"+pro+ "'/></span>"+ 
	    		"<input type='hidden' name='proposition_type[count][]'  value='"+ct+ "'/>"+

	    		"<img onclick='removeDiv(this)' src='images/3.png' " +
	    		" style='float: right;margin-top: 10px' id='img"+count+"' /> </div>");	

	});
	
	$('#task_save').click(function(e){
		e.preventDefault();
		$("#textbooks_error").css("display:none");
		var textbooks=$("#selectItems").html();
		var Knowledge=$("#selectItemsKnowledge").html();
		var type=$(".querenmt_cont").html();
		var  auditors=$("#kwzzPerson2Number").html();
		var  teacher=$("#stshrPerson2Number").html();
		
		
		var  time=$("#demo_inp1").val();
		if(type==""||typeof(type)=="undefined")
		{
			$("#textbooks_error").css("display","inline");
			$("#textbooks_error").html("请选择确认命题！");
		}
		else if(textbooks=="")
		{
			$("#textbooks_error").css("display","inline");
			$("#textbooks_error").html("请选择教材信息！");
		}
		else if(Knowledge=="")
		{
			$("#textbooks_error").css("display","inline");
			$("#textbooks_error").html("请选择知识点信息");
		}
		else if(time=="")
		{
			$("#textbooks_error").css("display","inline");
			$("#textbooks_error").html("命题截止时间");
		}
		else if(auditors=="0人")
		{
			$("#textbooks_error").css("display","inline");
			$("#textbooks_error").html("请选择审核人信息");
		}
		else if(teacher=="0人")
		{
			$("#textbooks_error").css("display","inline");
			$("#textbooks_error").html("请选择命题人信息");
		}
		else{
			$("#task_form").submit();	
		}
		
	});
});


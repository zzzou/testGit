$.browser = {};
$.browser.mozilla = /firefox/.test(navigator.userAgent.toLowerCase());
$.browser.webkit = /webkit/.test(navigator.userAgent.toLowerCase());
$.browser.opera = /opera/.test(navigator.userAgent.toLowerCase());
$.browser.msie = /msie/.test(navigator.userAgent.toLowerCase());
/*上面的$.browse定义为了防止版本冲突问题。。*/
var $_GET = (function(){
    var url = window.document.location.href.toString();
    var u = url.split("?");
    if(typeof(u[1]) == "string"){
        u = u[1].split("&");
        var get = {};
        for(var i in u){
            var j = u[i].split("=");
            get[j[0]] = j[1];
        }
        return get;
    } else {
        return {};
    }
})();


$(function(){	

    var Address =[];              
	var teacher = [];	
	var problemNumber =[];	
	//完整保存之后，我做一个标志
	var saveFlag = true;	
	//点击编辑，我做一个标志
	var editFlag = false;
	var editRowNo =0;
	var gloabalEditRow =null;
	var  rowsLength =0;
	var arrayNew =new Array();	
	
	var addKnowledge = function(){
		var s ='';
		var nodes = $('#tt').tree('getChecked');
		for(var i=0; i<nodes.length; i++){			
			if(i==nodes.length-1)
				{
				   s += nodes[i].text;
				}else{
					s += nodes[i].text+',';
				}
		}
		var target2 = $('#dg2').datagrid('getEditor', {'index':rowsLength-1,'field':'knowledge'}).target;
		target2.val(s);
		target2.attr("title",s);
		$('#win').window('close');  
	};

   var knowledgeDetail = function (){
		$('#win').window('open');   
		$.ajax({
			dataType:"json",
			url:'index.php?c=proposition&a=getPropositionKnowledgePoints&id='+$("#sendId").html(),
			success: function(jsonData){		  
				var obj = [];
				$.each(jsonData,function(i,n){
					var o= new Object();
					o.id = i;
					o.text =n;
					obj.push(o);	
				});
				$('#tt').tree({
					lines:true,
					checkbox:true,
					onCheck:function(){					
					}
				});
				$('#tt').tree("loadData",obj);
			}	  
	   	});
	};
	
	$("#addBtn").bind("click",addKnowledge);
	
	$(".task_click").click(function(){		
		var rows = $("#dg").datagrid("getRows");
		if(rows==undefined)
			{
			     alert("命题分配未完成！！");
			     return;
			}else{
				if(rows[rows.length-2].count.indexOf("未") >= 0)
					{
					     alert("命题分配未完成！！");
					     return;
					}
			}
		
		$("#save").ajaxSubmit({success:function(data){
		}});
		var json=$("#jsonData").val();
		var obj=jQuery.parseJSON(json);
		$(".second_tr").remove();
		if(obj!=null){
			for(var i=0;i<obj.rows.length;i++){				  
	              $(".frist_tr").after("<tr class='second_tr'><td>"+obj.rows[i].teacherName+"</td>" +
	              		"<td>"+obj.rows[i].problemType+"</td>" +
	              		"<td>"+obj.rows[i].already+"</td>" +
	              		"<td>"+(obj.rows[i].already)/(obj.rows[i].count)*100+'%'+"</td></tr>")
			}	
		}
		$('.pop_layer').show();
	});
	$(".ajax_save").click(function(){
		function show(){
			$(".mtbc").css("display","none");
		}
		
		var rows = $("#dg").datagrid("getRows");
		if(rows==undefined)
			{
			     alert("命题分配未完成！！");
			     return;
			}else{
				if(rows[rows.length-2].count.indexOf("未") >= 0)
					{
					     alert("命题分配未完成！！");
					     return;
					}
			}
		
		$("#save").ajaxSubmit({success:function(data){
			$(".mtbc").css("display","inline");
			setTimeout(show,4000);
		}});
	})
	$('#win').window({
	    title:'知识点详情',
	    width:400,
	    modal:true,
	    minimizable:false,
	    maximizable:false,
	    collapsible:false,
	    closed:true
	});
	
	$.ajax({
	    dataType:"json",
	    url:'index.php?c=proposition&a=ajaxTaskAssignment&id='+$("#sendId").html(),
	    success: function(array){
	    	
	    	/*保存题型*/
	    	for(var i=0;i<array.length;i++)
	    	{
	    		problemNumber.push(array[i].count);
	    		if(i<array.length-2)
	    			{
	    			   var o =new Object();
	    			   o.id =array[i].problemType;
	    			   o.text = array[i].problemType;
	    			   Address.push(o);   			   
	    			}
	    	}
	    	/*保存老师*/
	    	for(var i=0;i<array[array.length-1].count.length;i++)
    		{    		
	    		 var o = new Object();
		    	 o.id= array[array.length-1].count[i].user_id;
		    	 o.text = array[array.length-1].count[i].username;
		    	 teacher.push(o);    		    	            		   
    		}
    		
	    	$('#dg').datagrid({
	    	    //width:800,
	    	  // height:200,
	    		    fit:true,
	       singleSelect:true,
	    	    columns:[[
	    		       {field:'problemType',title:'题型',width:137,align:'center'},
	    		       {field:'count',title:'题数',width:137,align:'center',align:'center',formatter:function(value, rowData, rowIndex) {
	    		    	   if(typeof(value)=='object')
	    		    		   {
	    		    		     var s="";
	    		    		     $.each(value,function(index,item){   
	    		    		    	if(index == value.length-1)
	    		    		    		{
	    		    		    		  s += item.username;
	    		    		    		}else{
	    		    		    			s += item.username+",";
	    		    		    		}
	    		    		     });
	    		    		      return s;
	    		    		   }else{
	    		    			   return value;
	    		    		   }
	    		       }},
	    		       {field:'already',title:'已分配题数',width:140,align:'center'},
	    		       {field:'percent',title:'占比',width:137,align:'center'},
	    		       {field:'person',title:'命题人数',width:140,align:'center'}
	    		    ]],
	    		    onLoadSuccess:function()
	    		    {
	    		    	$('#dg2').datagrid({
	    		    		width:700,
	    		         //height:200,
	    		     singleSelect:true,
	    		    	  toolbar: [
	    		    	    	{
	    		    			    text: '添加',  
	    		    			    handler: function () {
	    		    			    	if(!saveFlag){
	    		    			    		alert("把正在编辑的编辑完成后再添加！！");
	    		    			    		return;
	    		    			    	}
	    		    			    	var rows = $("#dg").datagrid("getRows");
	    		    			    	if(rows[rows.length-2].count.indexOf("black") >= 0)
	    		    			    		{
	    		    			    		  alert("分配已经完成，不能再分配！！");
	    		    			    		  return;
	    		    			    		}
	    		    			    	
	    		    			    	$("#dg2").datagrid('unselectAll');
	    		    			        $("#dg2").datagrid('appendRow', {
	    		    			            index: rowsLength,
	    		    			            row: {}
	    		    			        });
	    		    			                          
	    		    			        $("#dg2").datagrid('beginEdit',rowsLength);

	    		    				
	    		    					var target = $('#dg2').datagrid('getEditor', {'index':rowsLength,'field':'count'}).target;
	    		    					target.attr("readonly","readonly")//将input元素设置为readonly

	    		    					var target2 = $('#dg2').datagrid('getEditor', {'index':rowsLength,'field':'knowledge'}).target;
	    		    					target2.attr("readonly","readonly")//将input元素设置为readonly
	    		    					target2.val('点击选择知识点详情');
	    		    					target2.bind("click",function(){
	    		    						//alert("yes!!");
	    		    					  knowledgeDetail();
	    		    					});  
	    		    					saveFlag =false;
	    		    					rowsLength++;
	    		    			    }
	    		    	        }, 
	    		    	        '-', {
	    		    			    text: '编辑',  
	    		    			    handler: function () {
	    		    			    	if(!saveFlag){
	    		    			    		alert("已经存在正在编辑的记录！");
	    		    			    		return;
	    		    			    	}
	    		    			    	
	    		    			    	 var rowEdit = $("#dg2").datagrid('getSelected');
	    		    			    	

		    		    	             if(rowEdit==undefined)
		    		    	                	{
		    		    	                	   alert("请选择一个编辑行！");
		    		    	                	   return;
		    		    	                	}
		    		    	             gloabalEditRow = rowEdit.already;
		    		    	            var index =  $("#dg2").datagrid('getRowIndex',rowEdit); 
		    		    	            editRowNo =index;   //editRowNo 全局变量
	    		    			        $("#dg2").datagrid('beginEdit',index);
	    		    			        editFlag = true;
		    				
	    		    					var target = $('#dg2').datagrid('getEditor', {'index':index,'field':'count'}).target;
	    		    					target.attr("readonly","readonly")//将input元素设置为readonly

	    		    					var target2 = $('#dg2').datagrid('getEditor', {'index':index,'field':'knowledge'}).target;
	    		    					target2.attr("readonly","readonly")//将input元素设置为readonly
	    		    					target2.val('点击选择知识点详情');
	    		    					target2.bind("click",function(){
	    		    					 //alert("yes!!");
	    		    					  knowledgeDetail();
	    		    					});  
	    		    					saveFlag =false;
	    		    			    }
	    		    	        }, '-',
	    		    	        {
	    		    	            text: '保存', 
	    		    	            handler: function () {
	    		    	            	 var rows ,row;
	    		    	              if(editFlag){                 
		    		    			         $("#dg2").datagrid('endEdit',editRowNo);
		    		    			         rows =  $("#dg2").datagrid("getRows"); 
 		    		    	                 row = rows[editRowNo];
	    		    	            		}else{
	    		    	            			    $("#dg2").datagrid('endEdit', rowsLength-1);
	    		    	            			    rows =  $("#dg2").datagrid("getRows"); 
	    		    		    	                row = rows[rowsLength-1];
	    		    	            		}

	    		    	               if(row.already==0||row.already=="")
	    		    	            	   {
	    		    	            	      alert("请填写分配的题数");
	    		    	            	      $("#dg2").datagrid('beginEdit', rowsLength-1);
	    		    	            	      var target2 = $('#dg2').datagrid('getEditor', {'index':rowsLength-1,'field':'knowledge'}).target;
	  	    		    					  target2.attr("readonly","readonly")//将input元素设置为readonly
	  	    		    					  target2.val('点击选择知识点详情');
	  	    		    					  target2.bind("click",function(){
			  	    		    					  knowledgeDetail();
			  	    		    					}); 
	    		    	            	      return;
	    		    	            	   }
	    		    	               
	    		    	               if(row.teacher=="")
    		    	            	   {
    		    	            	      alert("请选择老师");
    		    	            	      $("#dg2").datagrid('beginEdit', rowsLength-1);
    		    	            	      var target2 = $('#dg2').datagrid('getEditor', {'index':rowsLength-1,'field':'knowledge'}).target;
  	    		    					  target2.attr("readonly","readonly")//将input元素设置为readonly
  	    		    					  target2.val('点击选择知识点详情');
  	    		    					  target2.bind("click",function(){
  	    		    						//alert("yes!!");
  	    		    					    knowledgeDetail();
  	    		    					     }); 
    		    	            	      return;
    		    	            	   }
	    		    	              
	    		    	               var dgRows =  $("#dg").datagrid("getRows"); 
	    		    	              		    	          
	    		    	               if(editFlag){
	    		    	            	   /*处理老师*/
	    		    	            	   var rowsNew  =[];
	    		    	            	   for(var i=0;i<rows.length;i++)
	    		    	            	   {
	    		    	            		   if(i==editRowNo)
	    		    	            		   {	    		    	            			   
	    		    	            		   }else{
	    		    	            			   rowsNew.push(rows[i]); 
	    		    	            		        }    	            		    
	    		    	            	   }
	    		    	            	  
		    		    	               for(var i =0;i<rowsNew.length;i++)
	    		    	            	   {
	    		    	            	      if((row.teacher == rowsNew[i].teacher) && (rowsNew[i].problemType== row.problemType)){
	    		    	            	    	  alert("这类题型，这个老师已经分配过了！");
		    		    	            		  $("#dg2").datagrid('beginEdit', editRowNo);
		    		    	            		  var target2 = $('#dg2').datagrid('getEditor', {'index':editRowNo,'field':'knowledge'}).target;
		  	    		    					  target2.attr("readonly","readonly")//将input元素设置为readonly
		  	    		    					  target2.val('点击选择知识点详情');
		  	    		    					  target2.bind("click",function(){
				  	    		    					  knowledgeDetail();
				  	    		    					}); 
		    		    	            		  return ;
	    		    	            	      }
	    		    	            	   }
	    		    	            	   
	    		    	               }else{
	    		    	            	   /*处理老师*/
		    		    	               for(var i =0;i<rows.length-1;i++)
	    		    	            	   {
	    		    	            	      if((row.teacher == rows[i].teacher) && (rows[i].problemType== row.problemType)){
	    		    	            	    	  alert("这类题型，这个老师已经分配过了！");
		    		    	            		  $("#dg2").datagrid('beginEdit', rowsLength-1);
		    		    	            		  var target2 = $('#dg2').datagrid('getEditor', {'index':rowsLength-1,'field':'knowledge'}).target;
		  	    		    					  target2.attr("readonly","readonly")//将input元素设置为readonly
		  	    		    					  target2.val('点击选择知识点详情');
		  	    		    					  target2.bind("click",function(){
			  	    		    					  knowledgeDetail();
			  	    		    					}); 
		    		    	            		  return ;
	    		    	            	      }
	    		    	            	   }
	    		    	               }
	                           
	    		    	           	$.ajax({
		    	                	    dataType:"json",
		    	                	    url:'index.php?c=proposition&a=ajaxTaskAssignment&id='+$("#sendId").html(),
		    	                	    success: function(array){                	    	
		    	                	    	for(var i =0;i<array.length-2;i++)
		    	                	    		{
		    	                	    		   var tempCount =0;
		    	                	    		   var personNumber =0;
		    	                	    		   for(var j=0;j<rows.length;j++)
		    	                	    		   {
		    	                	    			   if(array[i].problemType == rows[j].problemType)
		    	                	    				   {
		    	                	    				   tempCount +=parseInt(rows[j].already); 
		    	                	    				   personNumber++;
		    	                	    				   }
		    	                	    			  	    		    	                	    			   
		    	                	    			}	
		    	                	    		   /*编辑状态下，改变题目类型的话，增加的code*/
		    	                	    		   if(tempCount>parseInt(array[i].count))
		    	                	    			   {
		    	                	    			      alert("不能分配这么多题目");
		    	                	    			      if(editFlag)
				    		    	            			 {
				    		    	            			  $("#dg2").datagrid('beginEdit', editRowNo);
				    		    	            			 }else{
				    		    	            				  $("#dg2").datagrid('beginEdit', rowsLength-1);
				    		    	            			 }
				    		    	            		
				    		    	            		  var target2 = $('#dg2').datagrid('getEditor', {'index':rowsLength-1,'field':'knowledge'}).target;
				  	    		    					  target2.attr("readonly","readonly")//将input元素设置为readonly
				  	    		    					  target2.val('点击选择知识点详情');
				  	    		    					  target2.bind("click",function(){
						  	    		    					  knowledgeDetail();
						  	    		    					}); 
				    		    	            		  return ;
		    	                	    			   }
		    	                	    		     array[i].already =  tempCount;
	    	                	    			     array[i].percent=(tempCount/array[i].count )*100+"%";
	    	                	    			     array[i].person = personNumber;	
		    	                	    		}   
		    	                	    	
		    	                	        /*判断完成情况*/
   		    		    	            	for(var i=0;i<array.length-2;i++)
   		    		    	    	    	{    	    	    	    	
   		    		    	    	    	   if(i==array.length-3)
	    		    	    	    	    	{
	    		    	    	    	    		 array[array.length-2].count ='<font color="black">完成</font>';
	    		    	    	    	    	}else{
	    		    	    	    	    		 array[array.length-2].count ='<font color="red">未完成</font>';
	    		    	    	    	    	}
   		    		    	    	    	  if(array[i].percent!='100%')
	    		    	    	    	    	{
	    		    	    	    	    	    array[array.length-2].count ='<font color="red">未完成</font>';
	    		    	    	    	    		break;
	    		    	    	    	    	}
   		    		    	    	    	}
   		    		    	            	
   		    		    	            	/*对未分配老师的处理*/ 		    		    	            	
   		    		    	            	array[array.length-1].count.length =0;
   		    		    	            	for(var i=0;i<teacher.length;i++)
   		    		    	            	{
   		    		    	            		var o =new Object();
   		    		    	            		o.user_id = teacher[i].id;
   		    		    	            		o.username =teacher[i].text;
   		    		    	            		array[array.length-1].count.push(o);
   		    		    	            	}
   		    		    	            	
   		    		    	            	for(var j =0;j<rows.length;j++)
   		    		    	            		{
   		    		    	            		    for(var i=0;i<array[array.length-1].count.length;i++)
   		    		    	            		    	{
   		    		    	            		    	   if(rows[j].teacher ==  array[array.length-1].count[i].user_id)
   		    		    	            		    		   {
   		    		    	            		    		        array[array.length-1].count.splice(i,1);
   		    		    	            		    		   }
   		    		    	            		    	
   		    		    	            		    	}
   		    		    	            		}    		    	            	
   		    		    	            	
   		    		    	                //完整保存之后，我做一个标志
   		    		    					saveFlag =true;
   		    		    					editFlag =false;
   		    		    	                $("#dg").datagrid("loadData",array); 
   		    		    	             var merges = [{
		    		    						index: array.length-2,
		    		    						colspan: 4
		    		    					},{
		    		    						index: array.length-1,
		    		    						colspan: 4
		    		    					}];
		    		    			
		    		    					$('#dg').datagrid('mergeCells',{
		    		    						index: merges[0].index,
		    		    						field: 'count',
		    		    						rowspan : 1,
		    		    						colspan: merges[0].colspan
		    		    					});
		    		    					
		    		    					$('#dg').datagrid('mergeCells',{
		    		    						index: merges[1].index,
		    		    						field: 'count',
		    		    						rowspan : 1,
		    		    						colspan: merges[1].colspan
		    		    					});   	
		    	                	    }
	    	                		}); 

	    		    					//放到display ：none  input中
	    		    					var allData = $("#dg2").datagrid("getData");
	    		    					
	    		    					for(var i=0;i<allData.rows.length;i++)
	    		    						{
	    		    						     for(var j=0;j<teacher.length;j++)
	    		    						    	 {
	    		    						    	    if(allData.rows[i].teacher == teacher[j].id)
	    		    						    	    	{
	    		    						    	    	allData.rows[i].teacherName = teacher[j].text; 
	    		    						    	    	}
	    		    						    	 }
	    		    						}
	    		    					
	    		    					//console.debug(JSON.stringify(allData));
	    		    					$("#jsonData").val(JSON.stringify(allData));
	    		    					
	    		    				    //完整保存之后，我做一个标志
	    		    					saveFlag =true;
	    		    					
	    		    	             }
	    		    	        }, 
	    		    	        '-', 
	    		    	        {
	    		    	            text: '删除', 
	    		    	            handler: function () {
	    		    	                var rowDel = $("#dg2").datagrid('getSelected');
	    		    	                
	    		    	                /*在编辑状态下删除*/
	    		    	                if(editFlag)
	    		    	                {
	    		    	                	alert("正在编辑模式下，不能删除！！");
	    		    	                    return;          	
	    		    	                }
	    		    	                
	    		    	                if(!saveFlag)
	    		    	                	{
	    		    	                    $("#dg2").datagrid('deleteRow',rowsLength-1);
	    		    	                    rowsLength--;
	    		    	                    //完整保存之后，我做一个标志
		    		    					saveFlag =true;
	    		    	                    return;
	    		    	                	}
	    		    	                
	    		    	              
	    		    	                
	    		    	                if(rowDel==undefined)
	    		    	                	{
	    		    	                	   alert("请选择一个删除行！");
	    		    	                	   return;
	    		    	                	}
	    		    	                var index =  $("#dg2").datagrid('getRowIndex',rowDel);
	    		    	                $("#dg2").datagrid('deleteRow',index);
	    		    	                rowsLength--;
	    		    	                
	    		    	                var rows =  $("#dg2").datagrid("getRows"); 
	    		    	                if(rows.length ==0)
	    		    	                	{
	    		    	                	$.ajax({
	    		    	                	    dataType:"json",
	    		    	                	    url:'index.php?c=proposition&a=ajaxTaskAssignment&id='+$("#sendId").html(),
	    		    	                	    success: function(array){
	    		    	                	    	Address.length =0;
	    		    	                	    	for(var i=0;i<array.length;i++)
	    		    	                	    	{
	    		    	                	    		problemNumber.push(array[i].count);
	    		    	                	    		if(i<array.length-2)
	    		    	                	    			{
	    		    	                	    			   var o =new Object();
	    		    	                	    			   o.id =array[i].problemType;
	    		    	                	    			   o.text = array[i].problemType;
	    		    	                	    			   Address.push(o);   			   
	    		    	                	    			}
	    		    	                	    	}
	    		    	                	    	/*保存老师*/
	    		    	                	    	teacher.length =0;
	    		    	                	    	for(var i=0;i<array[array.length-1].count.length;i++)
	    		    	                    		{    		
	    		    	                	    		 var o = new Object();
	    		    	                		    	 o.id= array[array.length-1].count[i].user_id;
	    		    	                		    	 o.text = array[array.length-1].count[i].username;
	    		    	                		    	 teacher.push(o);    		    	            		   
	    		    	                    		}
	    		    	                	    	
	    		    	                	    	$("#dg").datagrid("loadData",array);
	    		    	                	    	 var merges = [{
	    			    		    						index: array.length-2,
	    			    		    						colspan: 4
	    			    		    					},{
	    			    		    						index: array.length-1,
	    			    		    						colspan: 4
	    			    		    					}];
	    			    		    			
	    			    		    					$('#dg').datagrid('mergeCells',{
	    			    		    						index: merges[0].index,
	    			    		    						field: 'count',
	    			    		    						rowspan : 1,
	    			    		    						colspan: merges[0].colspan
	    			    		    					});
	    			    		    					
	    			    		    					$('#dg').datagrid('mergeCells',{
	    			    		    						index: merges[1].index,
	    			    		    						field: 'count',
	    			    		    						rowspan : 1,
	    			    		    						colspan: merges[1].colspan
	    			    		    					});
	    		    	                	    }
	    		    	                	  });       	
	    		    	                	
	    		    	                	}else{
	    		    	                		
	    		    	                		$.ajax({
		    		    	                	    dataType:"json",
		    		    	                	    url:'index.php?c=proposition&a=ajaxTaskAssignment&id='+$("#sendId").html(),
		    		    	                	    success: function(array){
		    		    	                	    	
		    		    	                	    	for(var i =0;i<array.length-2;i++)
		    		    	                	    	{
		    		    	                	    	   var tempCount =0;
		    		    	                	    	   var personNumber =0;
		    		    	                	    	   for(var j=0;j<rows.length;j++)
		    		    	                	    		{
	    		    	                	    			   if(array[i].problemType == rows[j].problemType)
	    		    	                	    				   {
	    		    	                	    				   tempCount +=parseInt(rows[j].already); 
	    		    	                	    				   personNumber++;
	    		    	                	    				   }    			  	    		    	                	    			   
		    		    	                	    		}	
   		   
	    		    	                	    			array[i].percent=(tempCount/array[i].count )*100+"%";
	    		    	                	    			array[i].person = personNumber;	
		    		    	                	    	}   
		    		    	                	    	
		    		    	                	        /*判断完成情况*/
			   		    		    	            	for(var i=0;i<array.length-2;i++)
			   		    		    	    	    	{    	    	    	    			   		    		    	    	    	    	
		   		    		    	    	    	    	if(i==array.length-3)
		   		    		    	    	    	    	{
		   		    		    	    	    	    		 array[array.length-2].count ='<font color="black">完成</font>';
		   		    		    	    	    	    	}else{
		   		    		    	    	    	    		 array[array.length-2].count ='<font color="red">未完成</font>';
		   		    		    	    	    	    	}
		   		    		    	    	    	    	if(array[i].percent!='100%')
	   		    		    	    	    	    		{
		   		    		    	    	    	    	    array[array.length-2].count ='<font color="red">未完成</font>';
		   		    		    	    	    	    		break;
		   		    		    	    	    	    	}
			   		    		    	    	    	}
			   		    		    	            	
			   		    		    	            	/*对未分配老师的处理*/	   		    		    	            	
			   		    		    	            	array[array.length-1].count.length =0;
			   		    		    	            	for(var i=0;i<teacher.length;i++)
			   		    		    	            	{
			   		    		    	            		var o =new Object();
			   		    		    	            		o.user_id = teacher[i].id;
			   		    		    	            		o.username =teacher[i].text;
			   		    		    	            		array[array.length-1].count.push(o);
			   		    		    	            	}
			   		    		    	            	
			   		    		    	            	for(var j =0;j<rows.length;j++)
			   		    		    	            	{
	   		    		    	            		      for(var i=0;i<array[array.length-1].count.length;i++)
	   		    		    	            		    	{
	   		    		    	            		    	   if(rows[j].teacher ==  array[array.length-1].count[i].user_id)
	   		    		    	            		    		   {
	   		    		    	            		    		        array[array.length-1].count.splice(i,1);
	   		    		    	            		    		   }
	   		    		    	            		    	}
			   		    		    	            	}	   		    		    	            	
			   		    		    	            	
			   		    		    	                //完整保存之后，我做一个标志
			   		    		    					saveFlag =true;
			   		    		    	                $("#dg").datagrid("loadData",array); 
			   		    		    	                var merges = [{
					    		    						index: array.length-2,
					    		    						colspan: 4
					    		    					},{
					    		    						index: array.length-1,
					    		    						colspan: 4
					    		    					}];
					    		    			
					    		    					$('#dg').datagrid('mergeCells',{
					    		    						index: merges[0].index,
					    		    						field: 'count',
					    		    						rowspan : 1,
					    		    						colspan: merges[0].colspan
					    		    					});
					    		    					
					    		    					$('#dg').datagrid('mergeCells',{
					    		    						index: merges[1].index,
					    		    						field: 'count',
					    		    						rowspan : 1,
					    		    						colspan: merges[1].colspan
					    		    					});
		    		    	                	    	
		    		    	                	    }
	    		    	                		});
	    		    	                	}       
	    		    	            }
	    		    	        } ],
	    		    	    columns:[[
	    		    			{
	    		    				field:'problemType',
	    		    				title:'题型',
	    		    				width:120,
	    		    				align:'center' ,
	    		    				formatter:function(value, rowData, rowIndex) {
	    		    		    	    if (value == 0||value=='undefined') {
	    		    		    	        return;
	    		    		    	    }
	    		    		    	    for (var i = 0; i < Address.length; i++) {
	    		    		    	        if (Address[i].id == value) {
	    		    		    	            return Address[i].text;
	    		    		    	        }
	    		    		    	    }
	    		    		    	},
	    		    	            editor: { 
	    		    	            	type: 'combobox', 
	    		    	            	options: { 
	    		    	                	data: Address, 
	    		    				  valueField: "id", 
	    		    				   textField: "text" ,
	    		    				    onSelect: function(record){
	    		    							var target = $('#dg2').datagrid('getEditor', {'index':rowsLength-1,'field':'count'}).target;
	    		    							for(var i=0;i<array.length;i++)
	    		    								{
	    		    								   if(array[i].problemType ==record.text)
    		    									   {
    		    									   target.val(array[i].count); break;
    		    									   }
	    		    								}	    	                                      
	    		    	                        target.attr("readonly","readonly");               
	    		    	                	} 	
	    		    	                }
	    		    	            }
	    		    	        },
	    		    			{field:'count',title:'总题数',width:120,align:'center',editor: { type: 'text', options: {} }  },
	    		    			{field:'already',title:'分配题数',width:120,align:'center', editor: { type: 'text', options: { required: true } } },
	    		    			{field:'knowledge',title:'知识点',width:180,align:'center',editor: { type: 'text', options: {} },
	    		    				formatter: function(value,row,index){
	    		    					if(value != 'undefined')
	    		    					{
	    		    						return '<a href="#" title= "'+value+'" onclick="knowledgeDetail('+index+')">'+value+'</a>';  
	    		    					}else{
	    		    						return '<a href="#" onclick="knowledgeDetail('+index+')">知识点详情</a>';  
	    		    					}	    		    				
	    		    				}
	    		    	       },
	    		    	       {field:'teacher',title:'命题老师',width:120,align:'center', formatter:function(value, rowData, rowIndex) {
	    		    	    	    if (value == 0) {
	    		    	    	        return;
	    		    	    	    }
	    		    	    	    for (var i = 0; i < teacher.length; i++) {
	    		    	    	        if (teacher[i].id == value) {
	    		    	    	            return teacher[i].text;
	    		    	    	        }
	    		    	    	    }
	    		    	    	},editor: { type: 'combobox', 
	    		    	       		options: { data: teacher, valueField: "id", textField: "text" }}}
	    		    	    	]
	    		    	    ],
	    		    	    onClickRow : function(rowIndex, rowData)
	    		    	    {
	    		    			grobalrowIndex = rowIndex;
	    		    	    }
	    		    	});
	    		    }
	    		});

	    		$('#dg').datagrid('loadData', array);
	    		
	    		var merges = [{
	    				index: array.length-2,
	    				colspan: 4
	    			},{
	    				index: array.length-1,
	    				colspan: 4
	    			}];
	    			
	    		$('#dg').datagrid('mergeCells',{
	    			index: merges[0].index,
	    			field: 'count',
	    			rowspan : 1,
	    			colspan: merges[0].colspan
	    		});
	    		
	    		$('#dg').datagrid('mergeCells',{
	    			index: merges[1].index,
	    			field: 'count',
	    			rowspan : 1,
	    			colspan: merges[1].colspan
	    		});
	    }
	});
});


var arrayStore =[];

var practiseType = [];
//练习父题型

var practiseFatherNumber = [];

//练习子题型
var practiseChildrenNumber = [[[]],
							  [[],[],[],[],[],[],[],[],[],[],[],[]],
							  [[],[],[],[]],
							  [[],[],[{ "id": "1", "text": "自然类文本阅读","selected":true },{ "id": "2", "text": "社科类文本阅读" }],[]],
							  [[],[],[],[]],
							  [[],[],[],[]],
							  []];


//一级题型ID
var firstLevelID;
//二级题型ID
var secondLevelID;
//三级题型ID
var thirdLevelID;
							  
$(function(){

	$.ajax({
		url: './js/json/chinese_1316.json',
		dataType:"json",
		success: function(array){
			
			//arrayStore = array;			  
			for(var i=0;i<array.length;i++)
			{
				arrayStore.push(array[i]);
			}
			
			
			for(var i=0;i<arrayStore.length;i++)
			{
			   var o= new Object();
			   o.id =arrayStore[i].id;
			   o.text =arrayStore[i].text;
			   practiseType.push(o);
			}
	
			var temp =[];
			for(var i=0;i<arrayStore.length;i++)
				{
				  temp.length =0;
				   var childrens =  arrayStore[i].children;
				   
				   if( childrens==undefined)
					   {
					     practiseFatherNumber.push([]);
					   }else{
						   for(var j=0;j<childrens.length;j++)
							{  
							   var o= new Object();
							   o.id =childrens[j].id;
							   o.text =childrens[j].text;
							   temp.push(o);
							}					  
						  practiseFatherNumber.push(temp);					   
					   }
				}
			
			
			$('#practiseType').combobox({
				valueField:'id',
				textField:'text',
				onSelect: function(rec){
					
					$('#threeDiv').hide();
					$('#twoDiv').show();
					 $('#practiseFatherNumber').combobox("setValue","");
					for(var i=0;i<arrayStore.length;i++)
					{
					     if(rec.id ==arrayStore[i].id)
					    	 {
					    	  var childrens =  arrayStore[i].children;
					    	  if( childrens==undefined)
					    		  {
					    		  $('#practiseFatherNumber').combobox("loadData",[]);
					    		  }else{
					    			  $('#practiseFatherNumber').combobox("loadData",childrens);
					    		  }
					    	   
					    	 }
					   
					}
					
					if(rec.text =="全部")
					{
						$('#threeDiv').hide();
						$('#twoDiv').hide();
					}
					
					//knowledge_point =rec.id;
					//load_data();
					//alert(rec.id);
					$("#hideSpan").val(rec.text);
									
				}
			});
			$("#practiseType").combobox("loadData",practiseType);
			
			$('#practiseFatherNumber').combobox({
				valueField:'id',
				textField:'text',
				onSelect: function(rec){
					for(var i=0;i<arrayStore.length;i++)
					{
						$('#practiseChildrenNumber').combobox("setValue","");
							if($("#practiseType").combobox("getValue") ==arrayStore[i].id)
							   {
							     var childrens =  arrayStore[i].children;
							     if( childrens==undefined)
					    		  {
							    	 $('#practiseChildrenNumber').combobox("loadData",[]);
					    		  }else{
					    			  for(var k=0;k<childrens.length;k++)
					    				{
				    				        if((rec.id+"") == (childrens[k].id+""))
				    				        	{
				    				        	    if(childrens[k].children==undefined)
				    				        	    	{
				    				        	    	$("#threeDiv").hide();
					    				        	    $('#practiseChildrenNumber').combobox("loadData",[]); 
				    				        	    	}else{
				    				        	    	    $('#threeDiv').show();
						    				        	    $('#practiseChildrenNumber').combobox("loadData",childrens[k].children);
				    				        	    	}
				    				        	
				    				        	}
					    		}
					    			  
					    	}
							   
						}
					}
					
					//knowledge_point =rec.id;
					//load_data();
					$("#hideSpan").val(rec.text);
				}
			});
			$('#practiseFatherNumber').combobox("loadData",practiseFatherNumber[0]);
			
			$('#practiseChildrenNumber').combobox({
				valueField:'id',
				textField:'text',
				onSelect: function(rec){
					//knowledge_point =rec.id;
					//load_data();
					$("#hideSpan").val(rec.text);
				}
			});	
			$('#practiseChildrenNumber').combobox("loadData",practiseChildrenNumber);
			
		}
	});	
	
});

/**
 * Created by fxmei on 14-4-24.
 */

var i=1;   
var pa=0;
var npa=0;
var sum;  //题目数目

//审核通过
function progress(){
	if(i>sum){
		return false;
	}
	else{
    var topicId=$("#u"+i).val();
    var topicNextId;
    if(i==sum){
    	topicNextId=$("#u"+i).val();
    }
    else{
    	topicNextId=$("#u"+(i+1)).val();
    }
	$(".l_btg_reason").hide();
	var temp=$("#b33").val();
	$("#a"+i).removeClass('l_flag');
	$("#a"+i).removeClass('l_orange');
	$("#a"+i).addClass('l_green');
	 $("#a"+(i+1)).addClass('l_flag ');
	 
	 var palen=$(".l_green").length;
	 var npalen=$(".l_orange").length;
	 $.ajax({
			url: './index.php?c=propositionItemTopic&a=updatePropositionItemTopicById&topicId='+topicId+'&state=1&teaId='+temp+'&audCount='+(palen+npalen),
			  success: function(data){
			     
			  }
				  
			});
	
	 
	 $.getJSON('./index.php?c=propositionItemTopic&a=getPropositionitemTopicById&topicId='+topicNextId, function(data) {
		 var re = eval(data);
		 if($.trim(re.video).length<=0){
			 $("#img1").hide();
		 }
		 else{
			 $("#img1").show();
		 $("#img1").attr("data-video",re.video);
		 }
		  $(".l_blcok2_content").html("");
			  var okArray=new Array();
			  okArray.push("<div class='l_blcok2_content'>");
			  okArray.push("<ul><li>  题型："+re.topic_type+"</li>");
			  okArray.push(" <li>难易度：<img src='./images/yellow_circle.png'/><img src='./images/yellow_circle.png'/><img src='./images/gray_circle.png'/><img src='./images/gray_circle.png'/>("+re.topic_difficulty+")</li>");
			  if(re.topic_knowledge_points==null){
				  okArray.push("<li>  知识点：</li>");
			  }
			  else{
				  okArray.push("<li>  知识点："+re.topic_knowledge_points+"</li>");
			  }
			  okArray.push("<li>  [题文]："+re.topic_content+"</li>");
			  okArray.push("<li>  [答案]："+re.topic_answer+"</li>");
			  okArray.push("</ul><div>");
			  okArray.push("</br></br></br></br></br></br></br></br>");
			  $(".l_blcok2_content").append(okArray.join('')); 
	 });
	 
	
	 
	 $(".l_no_reason_block_middle").html("");
	  var mys=new Array();
	  mys.push("<div class='l_no_reason_block_middle'>");
	  mys.push("已审核："+(palen+npalen)+"/"+sum+"道<br/>");
	  mys.push("<span class='l_yellow'>"+npalen+"</span> 题不通过，"+palen+"题通过</div>");
	  $(".l_no_reason_block_middle").append(mys.join('')); 
	 
	  

	 i++;
	}
	
	}
//审核不通过
function noprogress(){
	if(i>sum){
		$(".l_btg_reason").hide();
		return false;
	}
	else{
	 $(".l_btg_reason").show();
	 $("textarea").val('');
	 $("#tt").focus();
	}

}
//审核不通过，填写理由后确定
function okprogress(){
	
	
	var temp=$("#b33").val();
	
	var textval=$("textarea").val();
	var flag=true;
	
	if(textval==''){
	  return;
	}
	else{
		 var topicId=$("#u"+i).val();
		 var topicNextId;
		    if(i==sum){
		    	topicNextId=$("#u"+i).val();
		    }
		    else{
		    	topicNextId=$("#u"+(i+1)).val();
		    }
		$(".l_btg_reason").hide();
	$("#a"+i).removeClass('l_flag');
	$("#a"+i).removeClass('l_green');
	$("#a"+i).addClass('l_orange');
	 $("#a"+(i+1)).addClass('l_flag ');
	 var reason=$("#tt").val();
	 var pagh=$(".l_green").length;
	 var npagh=$(".l_orange").length;
	 $.ajax({
		  url: './index.php?c=propositionItemTopic&a=updateProTopicStateReasonById&topicId='+topicId+'&state=-1&reason='+reason+'&teaId='+temp+'&audCount='+(pagh+npagh),
		  success: function(data){
		     
		  }
			  
		});

	 $.getJSON('./index.php?c=propositionItemTopic&a=getPropositionitemTopicById&topicId='+topicNextId, function(data) {
		 var re = eval(data);
		 if($.trim(re.video).length<=0){
			 $("#img1").hide();
		 }
		 else{
			 $("#img1").show();
		 $("#img1").attr("data-video",re.video);
		 }
		  $(".l_blcok2_content").html("");
			  var nokArray=new Array();
			  nokArray.push("<div class='l_blcok2_content'>");
			  nokArray.push("<ul><li>  题型："+re.topic_type+"</li>");
			  nokArray.push(" <li>难易度：<img src='./images/yellow_circle.png'/><img src='./images/yellow_circle.png'/><img src='./images/gray_circle.png'/><img src='./images/gray_circle.png'/>("+re.topic_difficulty+")</li>");
			  if(re.topic_knowledge_points==null){
				  nokArray.push("<li>  知识点：</li>");
			  }
			  else{
				  nokArray.push("<li>  知识点："+re.topic_knowledge_points+"</li>");
			  }
			  nokArray.push("<li>  [题文]："+re.topic_content+"</li>");
			  nokArray.push("<li>  [答案]："+re.topic_answer+"</li>");
			  nokArray.push("</ul><div>");
			  nokArray.push("</br></br></br></br></br></br></br></br>");
			 
			
			  $(".l_blcok2_content").append(nokArray.join('')); 
	 });
	 
	
	 
	 $(".l_no_reason_block_middle").html("");
	  var mys=new Array();

	  mys.push("<div class='l_no_reason_block_middle'>");
	  mys.push("已审核："+(pagh+npagh)+"/"+sum+"道<br/>");
	  mys.push("<span class='l_yellow'>"+npagh+"</span> 题不通过，"+pagh+"题通过</div>");
	  $(".l_no_reason_block_middle").append(mys.join('')); 

	 i++;
	}
	
	
	
	
	
}
//点击任意一题
function backWork(index){
	var temp=$("#b33").val();
	$("#a"+i).removeClass('l_flag');
	$("#a"+index).addClass('l_flag ');
	i=index;
	 $.getJSON('./index.php?c=propositionItemTopic&a=getProposition&temp='+temp, function(data) {
		 var re = eval(data);
		 if($.trim(re[index-1].video).length<=0){
			
			 $("#img1").hide();
		 }
		 else{
		 $("#img1").attr("data-video",re[index-1].video);
		 $("#img1").show();
		 }
		  $(".l_blcok2_content").html("");
			  var mycs=new Array();
			  mycs.push("<div class='l_blcok2_content'>");
			  mycs.push("<ul><li>  题型："+re[index-1].topic_type+"</li>");
			 
			  mycs.push(" <li>难易度：<img src='./images/yellow_circle.png'/><img src='./images/yellow_circle.png'/><img src='./images/gray_circle.png'/><img src='./images/gray_circle.png'/>("+re[i-1].topic_difficulty+")</li>");
			  if(re[index-1].topic_knowledge_points==null){
				  mycs.push("<li>  知识点：</li>");
			  }
			  else{
				  mycs.push("<li>  知识点："+re[index-1].topic_knowledge_points+"</li>");
			  }
			  mycs.push("<li>  [题文]："+re[index-1].topic_content+"</li>");
			  mycs.push("<li>  [答案]："+re[index-1].topic_answer+"</li>");
			  mycs.push("</ul><div>");
			  mycs.push("</br></br></br></br></br></br></br></br>");
			
			  $(".l_blcok2_content").append(mycs.join('')); 
	 });
	 return false;
	
}
//保存任务
function  saveTask(){
	 var pagh=$(".l_green").length;
	 var npagh=$(".l_orange").length;
	$("#pcn").text(pagh);
	$("#npcn").text(npagh);
	
	if((pagh+npagh)<sum){
		 $.messager.alert('Warning','未审核完！！');
		 
	}
	else{
		$('#btn_check').attr('href','#checkOK'); 
	var pasum=$('.l_green');
	var npasum=$('.l_orange');
	$("#t1").html("");
	$("#t2").html("");
    for(var d=0;d<pasum.length;d++){
    	$("#t1").parent().show();
    	if(d<pasum.length-1){
           $("#t1").append($(pasum[d]).text()+",");
    	}
    	else{
    		$("#t1").append($(pasum[d]).text());
    	}
    }
    for(var d=0;d<npasum.length;d++){
    	$("#t2").parent().show();
    	if(d<npasum.length-1){
           $("#t2").append($(npasum[d]).text()+",");
    	}
    	else{
    		$("#t2").append($(npasum[d]).text());
    	}
    }
    if(pasum.length<=0){
    	$("#t1").parent().hide();
    }
    if(npasum.length<=0){
    	$("#t2").parent().hide();
    }

	
	if((pagh+npagh)==sum){
		$(".fonts").text("审核完毕");
		}
}
}
//发送任务
function  sendTask(){

	 var pagh=$(".l_green").length;
	 var npagh=$(".l_orange").length;
	var temp=$("#b33").val();
	if((pagh+npagh)<sum){
		 $.messager.alert('Warning','未审核完！！');
	}
	else{
		 location.href="index.php?c=propositionTeacherItem&a=updateTask&id="+temp;
	}
}

//加载
$(function(){
	var temp=$("#b33").val();
	 $.getJSON('./index.php?c=propositionItemTopic&a=getProposition&temp='+temp, function(data) {
		 var re = eval(data);
		  $(".l_no_reason_block_left").html("");
		  if(re.length>0){
		  var fs=new Array();
		  sum=re.length;
		  fs.push("<ul class='l_no_reason_block_left'>");
		  for(var j=0;j<re.length;j++){
			  if(re[j].state==0){
				  fs.push("<li id='a"+(j+1)+"'><span>"+"<a href='' onclick='return backWork("+(j+1)+")'>"+(j+1)+"</a>"+"</span><label></label></li>");
				  
			  }
				  if(re[j].state==1){
					  fs.push("<li class='l_green' id='a"+(j+1)+"'><span>"+"<a href='' onclick='return backWork("+(j+1)+")'>"+(j+1)+"</a>"+"</span><label></label></li>");
					  }
				  if(re[j].state==-1){
					  fs.push("<li class='l_orange' id='a"+(j+1)+"'><span>"+"<a href='' onclick='return backWork("+(j+1)+")'>"+(j+1)+"</a>"+"</span><label></label></li>");
					  }
				  
				  fs.push("<input type='hidden' id='u"+(j+1)+"' value='"+re[j].id+"'/>");
				  
				  
		  }
		  
		  fs.push("<div class='clear'></div></ul> ");
		  $(".l_no_reason_block_left").append(fs.join('')); 
		  $("#a1").addClass('l_flag');
		 
		  
		  
		  var tp=$(".l_green").length;
			 var ntp=$(".l_orange").length;
		  
		  $(".l_no_reason_block_middle").html("");
		  var mys=new Array();
		  mys.push("<div class='l_no_reason_block_middle'>");
		  mys.push("已审核："+tp+"/"+sum+"道<br/>");
		  mys.push("<span class='l_yellow'>"+ntp+"</span> 题不通过，"+tp+"题通过</div>");
		  $(".l_no_reason_block_middle").append(mys.join('')); 
		

		  if($.trim(re[0].video).length<=0){
		  
				 $("#img1").hide();
				 
			 }
			 else{
				 $("#img1").show();
			 $("#img1").attr("data-video",re[0].video);
			
			 }
		  $(".l_blcok2_content").html("");
		  var ms=new Array();
		  ms.push("<div class='l_blcok2_content'>");
		  ms.push("<ul><li>  题型："+re[0].topic_type+"</li>");
		  ms.push(" <li>难易度：<img src='./images/yellow_circle.png'/><img src='./images/yellow_circle.png'/><img src='./images/gray_circle.png'/><img src='./images/gray_circle.png'/>("+re[0].topic_difficulty+")</li>");
		  if(re[0].topic_knowledge_points==null){
			  ms.push("<li>  知识点：</li>");
		  }
		  else{
			  ms.push("<li>  知识点："+re[0].topic_knowledge_points+"</li>");
		  }
		  ms.push("<li>  [题文]："+re[0].topic_content+"</li>");
		  ms.push("<li>  [答案]："+re[0].topic_answer+"</li>");
		  ms.push("</ul><div>");
		  ms.push("</br></br></br></br></br></br></br></br>");
		  $(".l_blcok2_content").append(ms.join('')); 
		  }
	 });
	
	
	
})
 

 
 
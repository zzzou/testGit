
    //评价维度点击方法
	function display(num){
        var phase = $("#phaseId").val();
        var type =  $("#typeId").val();
        var dimensions =  $("#dimId").val();
        var target =  $("#targetId").val()
		dimensions = num;
		target = ""
        window.location.href = './index.php?c=scale_controller&a=getExam&phase_id='+phase+'&dimensions_id='+dimensions+'&target_id='+target+'&type_id='+type
    }
	//学段点击方法
	function show(phaseId){
        var phase = $("#phaseId").val();
        var type =  $("#typeId").val();
        var dimensions =  $("#dimId").val();
        var target =  $("#targetId").val()
		phase=phaseId;
        window.location.href = './index.php?c=scale_controller&a=getExam&phase_id='+phase+'&dimensions_id='+dimensions+'&target_id='+target+'&type_id='+type
	}
	//评价指标点击方法
	function targe(num2){
        var phase = $("#phaseId").val();
        var type =  $("#typeId").val();
        var dimensions =  $("#dimId").val();
        var target =  $("#targetId").val()

		target=num2;
        window.location.href = './index.php?c=scale_controller&a=getExam&phase_id='+phase+'&dimensions_id='+dimensions+'&target_id='+target+'&type_id='+type
	}
	//测评方式点击方法
	function typeDis(typeNum){
        var phase = $("#phaseId").val();
        var type =  $("#typeId").val();
        var dimensions =  $("#dimId").val();
        var target =  $("#targetId").val()

		type = typeNum;
        window.location.href = './index.php?c=scale_controller&a=getExam&phase_id='+phase+'&dimensions_id='+dimensions+'&target_id='+target+'&type_id='+type
	}
	//ajax的统一返回方法
	function insert(data){
        var re = eval("("+data+")");
        var scale = new Array();
        $("#sent_mail1").html("");
        var len = re.data.length;

        if(nowNum==0){
            $("#td1").css("display","none");
            $("#td2").css("display","none");
        }else{
            $("#td1").removeAttr("style");
            $("#td2").removeAttr("style");
        }


        for(var i=0;i<len;i++){
			  var time=re.data[i].testTime.split(" ");
			  scale.push("<div class='cep_cont'>");
			  scale.push("<h5><em class='fr'>录入时间："+time[0]+"</em><b>"+re.data[i].testNum+"</b>"+re.data[i].testName+" <font>使用次数："+re.data[i].useNum+"次</font></h5>");
			  scale.push("<div class='entrust_bluebg'>");
			  scale.push("<p><img src='./images/pic_new.png' width='120' height='130' /></p>");
			  scale.push("<dl class='fl'>");
			  scale.push("<dt><b>评价维度：</b>"+re.data[i].dimensionsName+"</dt>");
			  scale.push("<dd><b>评价指标：</b>"+re.data[i].targetName+"</dd>");
			  scale.push("<dd id='dd1' title='"+re.data[i].phaseName+"'><b>适用学段：</b>"+re.data[i].phaseName+"</dd>");
			  scale.push("<dd><b>测评方式：</b>"+re.data[i].typeName+"</dd>");
			  scale.push("<dd><b>题目数量：</b>"+re.data[i].topicNum+"</dd>");
			  scale.push("</dl>");
			  scale.push("<div class='bright_cont_right fl'>");
			  scale.push("<div class='bright_cont_p'>");
			  scale.push("<b>简介：</b>");
			  scale.push("<p style=\"width:auto;\" class=\"truncate\" title=\""+re.data[i].testIntro+"\">"+re.data[i].testIntro+"</p></div>");
			  scale.push("<div class='btn_cp'><input  name='' type='button'  id='task_button' class='btn_fb' onclick=\"view_office('"+re.data[i].scale_url+"','0','"+re.data[i].testName+"','"+re.data[i].testNum+"','"+re.data[i].scale_anw_url+"');\" value='预览' href='#demo'/><input  name='' type='button' onclick=\"downfile('./resource/doc/"+re.data[i].download_name+"');\"  value='下载'/><input class='bty_btn' type='button' onclick=\"fqpc('"+re.data[i].id+"');\" value='发起测评'/></div>");
			  scale.push("</div>");
			  scale.push("<div class='clear'></div>");
			  scale.push("</div>");
			  scale.push("</div>");
		  }
		$("#sent_mail1").append(scale.join(''));
		truncateText();
		
	}
	//下载文件
	function downfile(str)
	{
	  window.location.href = str;
	}
	//发起评测方法
	function fqpc(id){
		location.href="./index.php?c=scale_controller&a=getNewAssessment&exam_id="+id;
	}

    $(document).ready(function(){
        truncateText()
    })
    //文字过多时显示...
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

    $(function() {
        //上一页
        $("#pageUp").click(
            function(){
                nowNum --;
                insert($("#data").val());
            }
        )
        //下一页
        $("#pageDown").click(
            function(){
                nowNum ++;
                insert($("#data").val());
            }
        )
        //首页
        $("#pageIndex").click(
            function(){
                nowNum =0;
                insert($("#data").val());
            }
        )

        //尾页
        $("#pageMin").click(
            function(){
                nowNum=pagerNum;
                insert($("#data").val());
            }
        )
        //fancybox弹出框
        $(".btn_fb").fancybox({
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
    })
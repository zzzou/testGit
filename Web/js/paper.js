/*点击左边的left  标题，右边content对应 */
function gotoContentTitle(id) {
    var _this = $("[data-id=" + id + "]").last();
    var right = $('.fn-right_content');   
    right.animate({
        scrollTop: _this.offset().top - right.offset().top - $('.shitidatilan').outerHeight(true) + right.scrollTop()
    });
    _this.addClass("shine");
    setTimeout(function () { _this.removeClass("shine"); }, 800);
}

$(function(){
	   var exam = $('#exam_tabs');
	   if (typeof paper == 'undefined')  paper = {};
	   paper.setting = {};

	   paper.index = function () {
		    var  vp = exam.data('vp');
		    paper.setting = { vp: vp, nums: exam.data('nums') };

		    /*struct select  默认结构， 标准结构 */
		    $('[name=structure]', exam).change(function () {
		        $('.fn-left_content .check', exam).trigger('click');
		        var _this = $(this);
		        setTimeout(function () {
		            if (_this.val() == "") {
		                $('.fn-left_content .uncheck', exam).trigger('click');
		            } else {
		                $('.fn-left_content [data-' + _this.val() + '] .uncheck', exam).trigger('click');
		            }
		        }, 200);
		        return false;
		    });

		    //传入可以修改项的categoryid  
		    /*左边的编辑 类似。。checkbox  点击 右边的 主题也会出现编辑dialog */
		    var editDialog = function (categoryid) {		    	
		        if ($('[name=examstatu]', exam).val() != 2) {
		           /* paper.post("index.php?c=paper&a=getDialog", { categoryId: categoryid, examId: exam.data('examid') }, function (r) {
		                paper.dialog({}, r.html, function () { $('#editDialogForm').submit(); });
		                */
		        	 $.ajax({
		    	    	  type: "POST",
		    	          url: "index.php?c=paper&a=getDialog",
		    	          data:{'examId':$("#examId").html()},
		    	          dataType:"text",
		    	          success: function(r){	
		    	        	  $("#myDialog").html(r);
		    	        	  $("#myDialog").dialog({
		    	        		  title:"试卷设置",
		    	        		  width:650
		    	        	  });
		    	          }    	
		    	    });		        	 
		        }		    	
		      };

		    /*点击右边的标题 编辑。。*/
		    $('.paper-title,.paper-prititle,.paper-info,.student-info,.count-box,.unit-box,.type-handle-box,.question-handle-box,#pui_seal,.type-handle-box .control-box .set-icon', exam).click(function () {
		        editDialog($(this).attr("data-id").substr(2));
		        return false;
		    });
		    
		    /*左边的 checkbox 点击*/
		    $('.fn-left_content .icon', exam).click(function () {
		        var _this = $(this);
		        if (_this.hasClass('set')) {
		            editDialog(_this.parent().data('id'));
		        }
		        var id = _this.parents('[data-id]:first').data('id');
		        if (_this.hasClass('check')) {
		          console.debug("hasClass(check)");
		          $('.fn-right_content [data-id="' + id + '"]', exam).hide();
		          _this.removeClass('check').addClass('uncheck');
		        } else if (_this.hasClass('uncheck')) {
		        	console.debug("hasClass(uncheck)");
		        	  _this.removeClass('uncheck').addClass('check');
		        	  $('.fn-right_content [data-id="' + id + '"]', exam).show();
		        } else if (_this.hasClass('set')) {

		        }
		        return false;
		    });
		};

		paper.index();

		/*定义paper.grey*/
	    paper.grey = function (grey) {
	        $('.del-icon', grey).click(function () {
	            var quesbox = $(this).parents().next(".quesbox");
	            var questionid = quesbox.attr('data-questionid'); 
	            console.debug("delete questionId");
	            $.ajax({
		   	    	  type: "POST",
		   	          url: "index.php?c=paper&a=deleteQuestion",
		   	          data:{'questionId':questionid},
		   	          dataType:"text",
		   	          success: function(r){	
		   	        	  quesbox.parent(".question-item-box").remove();
		   	          }    	
		   	       });	         
	            return false;
	        });

	        $('.up-icon', grey).click(function () {
	        	console.debug("grey..up ...");
	            var _this = $(this);
	            var cur = $(this).parents('.question-item-box:first');
	            var categoryId = cur.prevAll('.question-handle-box').data('categoryid');

	            if (cur.prev('.question-item-box').length == 0) return false;

	            var pre = cur.prev('.question-item-box');
	            var questionid =  cur.find(".quesbox").attr('data-questionid');	   
	            $.ajax({
		   	    	  type: "POST",
		   	          url: "index.php?c=paper&a=order",
		   	          data:{'questionId':questionid,'flag':0},
		   	          dataType:"text",
		   	          success: function(r){	
		   	              pre.before(cur);
		   	          }    	
		   	       });	          
               /*
	                //修改左边的大纲
	                var li_pre = $('.question-box li[data-id=q_' + pre.find('.quesbox').data('id') + ']');
	                var li_now = $('.question-box li[data-id=q_' + cur.find('.quesbox').data('id') + ']');
	                li_pre.before(li_now);
	                ReArrangPage();
	            });
	            */	            
	            return false;
	        });

	        $('.down-icon', grey).click(function () {
	        	console.debug("grey..down ...");
	            var CategoryId = $(this).parent().parent().parent().prevAll('.question-handle-box').data('categoryid');
	            if ($(this).parent().parent().parent().next('.question-item-box')[0] == undefined)
	                return false;
	            var next = $(this).parent().parent().parent().next('.question-item-box');
	            var now = $(this).parent().parent().parent('.question-item-box');
	            
	            var questionid =  now.find(".quesbox").attr('data-questionid');	         
	            $.ajax({
		   	    	  type: "POST",
		   	          url: "index.php?c=paper&a=order",
		   	          data:{'questionId':questionid,'flag':1},
		   	          dataType:"text",
		   	          success: function(r){	
		  	            now.before(next);
		                var li_next = $('.question-box li[data-id=q_' + next.find('.quesbox').attr('data-id') + ']');
		                var li_now = $('.question-box li[data-id=q_' + now.find('.quesbox').attr('data-id') + ']');
		                li_now.before(li_next);
		   	          }    	
		   	       });	                 
	             /*  
	                var li_next = $('.question-box li[data-id=q_' + next.find('.quesbox').attr('data-id') + ']');
	                var li_now = $('.question-box li[data-id=q_' + now.find('.quesbox').attr('data-id') + ']');
	                li_now.before(li_next);
	                ReArrangPage();
	             */
	            return false;
	        });

	        //查看详情
	        $(".detail", grey).click(function () {
	            var _this = $(this);
	            var qu = _this.parents('.question-item-box:first');
		       	 $.ajax({
	   	    	  type: "POST",
	   	          url: "index.php?c=paper&a=getAnswer",
	   	          data:{'questionId':qu.attr('data-id')},
	   	          dataType:"text",
	   	          success: function(r){	
	   	        	  $("#questionDetail").html(r);
	   	        	  $("#questionDetail").dialog({
	   	        		  title:'详细信息',
	   	        		   width: 700,
	   	        		   height: 500
	   	        	  });
	   	          }    	
	   	       });	            
	        });
	    };
	    /*结束定义paper.grey*/
	    
	    /*定义paper.green*/
	    paper.green = function (green) {
	        //标定选择题的总分
	        $('.score', green).click(function () {
	            var qb = $(this).parents('.question-box:first'), box = $('.question-handle-box', qb);
                console.debug(box.attr('data-id'));
                console.debug(box.attr('data-categoryid'));
	            var _this = qb.find('.quesbox');
	            if (_this.length <= 0) 
	            	{
	            	alert("该题型下没有题目可以标定！");
	            	return;
	            	}

	            var ques = $(_this.get(0));
	            var totalScore = 0;
	            if (ques.data('status') == 1) {
	                _this.each(function (i, v) {
	                    totalScore += parseFloat($(v).data('score'));
	                });
	            } 
	            $.ajax({
		   	    	  type: "POST",
		   	          url: "index.php?c=paper&a=getCategoryMsg",
		   	          data:{'examId':$("#examId").html(),'categoryId':box.attr('data-categoryid'),'id':box.attr('data-id')},
		   	          dataType:"text",
		   	          success: function(r){	
		   	        	  $("#biaoDingScore").html(r);
		   	        	  $("#biaoDingScore").dialog({
		   	        		   title:'详细信息',
		   	        		   width: 400,
		   	        		   height: 300
		   	        	  });
		   	          }    	
		   	       });	    
	            return false;
	        });

	        //清空
	        $('.clear', green).click(function () {
	            if (confirm("确认清空当前题型？")) {
	                var _this = $(this).parents('.question-box').find('.quesbox');
	                var ques_div = $(this).parents('.question-handle-box');
	                var ids = [];
	                jQuery.each(_this, function (i, v) {
	                    ids.push($(v).data('id'));
	                });
	              /*  paper.post("GreenClean", { QuestionIds: ids }, function (r) {
	                    $(".bfb").html(r.bfb);
	                    ques_div.nextAll('.question-item-box').remove();
	                    $('.list-title[data-id=' + ques_div.data('id').substr(2) + ']').next('ul').remove();
	                    ReArrangPage();
	                    alert("清空成功");
	                });*/
	                $.ajax({
			   	    	  type: "POST",
			   	          url: "index.php?c=paper&a=clearQuestion",
			   	          data:{'examId':3868,'categoryId':ques_div.attr('data-categoryid')},
			   	          dataType:"text",
			   	          success: function(r){	
			   	           ques_div.nextAll('.question-item-box').remove();
			   	           alert("清空成功");
			   	          }    	
			   	       });	    
	            }
	            return false;
	        });

	        $('.del-icon', green).click(function () {
	            if (confirm("确认清空并删除当前题型？")) {
	                var _this = $(this).parents('.question-box');
	                var ids = [];
	                jQuery.each($(this).parents('.question-box').find('.quesbox'), function (i, v) {
	                    ids.push($(v).data('id'));
	                });
	                console.debug(_this.find('.question-handle-box').attr('data-categoryid'));
	                $.ajax({
			   	    	  type: "POST",
			   	          url: "index.php?c=paper&a=deleteCategoryQuestion",
			   	          data:{'examId':3868,'categoryId':_this.find('.question-handle-box').attr('data-categoryid')},
			   	          dataType:"text",
			   	          success: function(r){	
			   	        	_this.remove();
			   	           alert("清空成功");
			   	          }    	
			   	       });	                
	                /*
	                paper.post("GreenDelete", { categoryid: $(this).parents('.question-handle-box').data('id').substr(2), QuestionIds: ids }, function (r) {
	                    $(".bfb").html(r.bfb);
	                    _this.remove();
	                    $('.list-title[data-id=' + _this.find('.question-handle-box').data('id').substr(2) + ']').next('ul').remove();
	                    $('.list-title[data-id=' + _this.find('.question-handle-box').data('id').substr(2) + ']').remove();
	                    ReArrangPage();
	                    alert("清空成功");
	                });
	                */
	            }
	            return false;
	        });

	        $('.set-icon', green).click(function () {
	            paper.index.editDialog($(this).parents('.question-handle-box').attr("data-id").substr(2));
	            return false;
	        });	     
	    };
	    /*结束定义paper.green*/
		/*浮动到content标题上面，字体变色  color f60*/
		 $('.paper-title,.paper-prititle,.paper-info,.student-info,.count-box,.unit-box', exam).hover(function () {
		        $(this).addClass("f60");
		        return false;
		    }, function () {
		        $(this).removeClass("f60");
		        return false;
		    });
		 
		 /*浮动出现  标定分数 清空 删除 */
		 $('.question-item-box,.question-handle-box,.type-handle-box', exam).hover(function () {
		        var _this = $(this), color = _this.data('color'), box = _this;
		        if (color == undefined) {
		            box = _this.parents('[data-color]:first');
		            color = box.data('color');
		        }
		        box.toggleClass('question-item-box-' + color);   //  如果存在（不存在）就删除（添加）一个类。
		        var func = eval('paper.' + color);
		        if (func && $.isFunction(func))
		            func.apply(box, [box]);
		        return false;
		    }, function () {
		        var _this = $(this), color = _this.data('color'), box = _this;
		        if (color == undefined) {
		            box = _this.parents('[data-color]:first');
		            color = box.data('color');
		        }
		        box.toggleClass('question-item-box-' + color);
		        $('.control-box a span', box).unbind('click');
		        return false;
		    });		 
});

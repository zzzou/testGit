/**
 * Created by zzzou on 14-5-15.
 */
$(function () {
    var noRealGlobalList =[];
    var exam = $('#exam_tabs');
    var cart = $('.xzbox', exam);
    var tabA = $('.tabA', cart);
    var tab_con = $('.tab_con', cart);

    $(".hishiti").click(function () {
        //console.log("11111111");
        //$('.shitijianzi', cart).html("高中语文 (<b>2</b>题)");
        if (tabA.is(":visible") == true) {
            tabA.hide();
            $(".zujuan_bottom").slideUp(1);
        }else {
            $(".zujuan_bottom").toggle();
        }
        $.ajax({
            type: "POST",
            url: "index.php?c=QuestionBar&a=getQuesBar",
            dataType:"json",
            success: function(jsonData){
                $('.shitijianzi').html("高中语文 (<b>"+jsonData.sum+"</b>题)");
            }
        });         
    });

    $('.shitijianzi', cart).click(function () {
        console.log("222");
        $.ajax({
            type: "POST",
            url: "index.php?c=QuestionBar&a=getQuesBar",
            dataType:"json",
            success: function(jsonData){
                // console.debug(jsonData);
                noRealGlobalList = jsonData.list;
                //  console.debug(noRealGlobalList);
                var list =jsonData.list;
                var optionHtml = " <option value='' selected='selected'></option>";
                for(var i=0;i<list.length;i++)
                {
                    optionHtml +=" <option value='"+list[i].Name+"'>"+list[i].Title+"</option>";
                }
                //console.debug(optionHtml);
                $("#selectType").html(optionHtml);
                if ($(".tabA").is(":visible") == false) {
                    $(".tabA").show();
                }
                else {
                    $(".tabA").slideUp(500);
                }
            }
        });
    });

    $('[name=c_categoryId]', cart).change(function () {
        //console.debug($(this).val());
        var cate = $(this).val();
        // console.debug(noRealGlobalList);
        for(var i=0;i<noRealGlobalList.length;i++)
        {
            if(cate == noRealGlobalList[i].Name)
            {
                //	console.debug(noRealGlobalList[i].listTitle);
                var tableHtml ="";
                var testList =noRealGlobalList[i].listTitle;
                //console.debug( typeof testList);
                tableHtml = " <tbody>"+
                    "<tr>"+
                    " <td class='forth-col' style='border-left-style: none;'><a class='clean' href='#' style='color: #03A295;'>题号</a></td>"+
                    " <td class='forth-col' style='width: 200px;'><a class='clean' href='#' style='color: #03A295;'>已选试题</a></td>"+
                    "</tr>";
                for(var j=0;j<testList.length;j++)
                {
                    tableHtml  +=
                        "<tr  style='display: table-row;'>"+
                            " <td class='blod blod_width' style='border-left-style: none;'> "+testList[j].sourceId+"</td>"+
                            " <td class='third-col' data-img='' style='width: 185px;' title=''><div id='yes' style='height:30px;width:182px;'><img src='"+testList[j].content+"' border='1' /></div></td>"+
                            "</tr>";
                }

                tableHtml  += "</tbody>";

                $('.data-table').html(tableHtml);

                $('.data-table td.third-col').poshytip({
                    content: function (updateCallback) {
                        //	console.debug("third-col......");
                        var s =   $(this).find("div").find("img").attr("src");
                        updateCallback($('<div style="background-color: white;z-index:99999;border: 1px solid #000;width:580px;">'
                            +'<img src="' + s + '" border="1" /></div>'));
                    },
                    className: 'tip-darkgray',
                    bgImageFrameSize: 11,
                    alignY:'top',
                    alignX:'right',
                    offsetX: -500
                });
            }
        }
    });
});
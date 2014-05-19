<?php
/**
 * Created by JetBrains PhpStorm.
 * User: xhcao
 * Date: 14-5-7
 * Time: 下午2:59
 * To change this template use File | Settings | File Templates.
 */

class PageLib extends BaseLib {
    protected $modelName = 'LogModel';
    /**
     * 数据数组
     */
    private  $datas = array();

    /**
     * 构造函数
     */
    public function __construct() {
        $this->model = spClass ( "ModelModel" );
        $this->datas = $this->getUserModelList($_SESSION['role']);
    }

    /**
     * 题库和卷库的分页页面
     *
     * @param string $type
     * 传递的参数说明:
     *              $count      要跳转到哪一页的页数
     *              $current    当前在哪一页
     *              $total  题目总数
     *              $page   每页显示题目数
     *              $max    显示的总页数
     *              $plus   显示页面的正负值
     *              $num    底部翻页菜单显示翻页数量
     *
     * 返回的参数说明：
     * @return
     *              0               已经是最大页或者最小页，无法翻页
     * 或者：
     *      array($prev_next,$bottom_page)
     *              $prev_next      顶部的上一页下一页和总计数量的跳转栏
     *              $bottom_page    底部的翻页跳转栏
     *
     */
    public function getPage($count,$current,$total,$page='9',$num='5') {
        $plus = floor($num/2);
        $max = ceil($total/$page);
        if(($count == '-1' && $current==1) || ($count == '-2' && $current==$max) || (empty($count)) || $count>$max){
            return '0';
            exit;
        }
        if($count == '-1'){
            $current--;
        }else if($count == '-2'){
            $current++;
        }else{
            $current=$count;
        }
        //实现
        $prev_next = '';
        $prev_next.="<a href='javascript:page(-1);' class='prev'>&nbsp;</a> 第<b>".$current."</b>/".$max."页 <a href='javascript:page(-2);' class='next'>&nbsp;</a>
                &nbsp;&nbsp;共<b style='color: red;'>".$total."</b>卷";
        $bottom_page = '';
        $bottom_page.= "<div class='paging'>
            <span style='display: inline-block;'><a href='javascript:page(+0);' class='go' >跳转到</a><input name='go' value='' class='ui-widget-content ui-corner-all' style='width: 40px;'>页</span>";
        switch($current){
            case 1:
                $bottom_page.="<div class='pagination ajax'><span class='current '>1</span>";
                $max>$num ? $j=$num : $j=$max;
                for($i=2;$i<=$j;$i++){
                    $bottom_page.="<a data-p='".$i."' href='javascript:page(".$i.");'>".$i."</a>";
                }
                $bottom_page.="<a data-p='2' href='javascript:page(-2);' class='next'>下一页</a></div></div>";
                break;
            case $max:
                $bottom_page.= "<div class='pagination ajax'><a data-p='2' href='javascript:page(-1);' class='prev'>上一页</a>";
                if($max<=$num){
                    for($i=1;$i<$max;$i++){
                        $bottom_page.="<a data-p='".$i."' href='javascript:page(".$i.");'>".$i."</a>";
                    }
                }else{
                    for($i=$num-1;$i>0;$i--){
                        $bottom_page.="<a data-p='".($max-$i)."' href='javascript:page(".($max-$i).");'>".($max-$i)."</a>";
                    }
                }
                $bottom_page.="<span class='current '>".$max."</span></div></div>";
                break;
            default:
                $bottom_page.= "<div class='pagination ajax'><a data-p='2' href='javascript:page(-1);' class='prev'>上一页</a>";
                if($max<=$num){
                    for($i=1;$i<=$max;$i++){
                        if($current==$i){
                            $bottom_page.="<span class='current '>".$current."</span>";
                        }else{
                            $bottom_page.="<a data-p='".$i."' href='javascript:page(".$i.");'>".$i."</a>";
                        }
                    }
                }else{
                    switch($current){
                        case $current<=$plus:
                            for($i=1;$i<$current;$i++){
                                $bottom_page.= "<a data-p='".($i)."' href='javascript:page(".($i).");'>".($i)."</a>";
                            }
                            $bottom_page.="<span class='current '>".$current."</span>";
                            for($i=1;$i<=$num-$current;$i++){
                                $bottom_page.= "<a data-p='".($current+$i)."' href='javascript:page(".($current+$i).");'>".($current+$i)."</a>";
                            }
                            break;
                        case $current>=($max-$plus):
                            for($i=$plus;$i>=1;$i--){
                                $bottom_page.= "<a data-p='".($current-$i)."' href='javascript:page(".($current-$i).");'>".($current-$i)."</a>";
                            }
                            $bottom_page.="<span class='current '>".$current."</span>";
                            for($i=$current+1;$i<=$max;$i++){
                                $bottom_page.= "<a data-p='".($i)."' href='javascript:page(".($i).");'>".($i)."</a>";
                            }
                            break;
                        default:
                            for($i=$plus;$i>0;$i--){
                                $bottom_page.= "<a data-p='".($current-$i)."' href='javascript:page(".($current-$i).");'>".($current-$i)."</a>";
                            }
                            $bottom_page.="<span class='current '>".$current."</span>";
                            for($i=1;$i<=$plus;$i++){
                                $bottom_page.= "<a data-p='".($current+$i)."' href='javascript:page(".($current+$i).");'>".($current+$i)."</a>";
                            }
                            break;
                    }
                }
                $bottom_page.="<a data-p='2' href='javascript:page(-2);' class='next'>下一页</a></div></div>";
        }
        return array($prev_next,$bottom_page);
    }
}
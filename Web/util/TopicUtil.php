<?php
/**
 * 题库相关方法
 *
 * @package util
 * @author 宋利鹏 lpsong@iflytek.com
 * @date 2014-3-24
 */

/**
  * 获取难度列表
  *
  */
function topicGetDiffculties(){
	return array(
		1=>'容易',
		2=>'较易',
		3=>'一般',
		4=>'较难',
		5=>'困难'
	);
}

/**
  * 获取年级列表
  *
  */
function topicGetGrades(){
	return array(
		1=>'初一',
		2=>'初二',
		3=>'初三',
		4=>'高一',
		5=>'高二',
		6=>'高三'
	);
}

/**
  * 获取考试类型列表
  *
  */
function topicGetExamTypes(){
	return array(
		1=>'单元试卷',
		2=>'专题试卷',
		3=>'月考试卷',
		4=>'开学考试',
		5=>'期中考试',
		6=>'期末考试',
		7=>'中考模拟',
		8=>'中考真卷',
		9=>'高考模拟',
		10=>'高考真卷',
		11=>'水平会考',
		12=>'竞赛测试',
		14=>'同步测试'
	);
}

/**
  * 获取试题类型列表
  *
  */
function topicGetTopicTypes(){
	return array(
		1=>'其他',
		2=>'选择题',
		3=>'单选题',
		4=>'双选题',
		5=>'多选题',
		6=>'单项选择',
		7=>'不定项选择题',
		8=>'现代文阅读',
		9=>'文言文阅读',
		10=>'诗歌鉴赏',
		11=>'语言表达',
		12=>'名著导读',
		13=>'默写',
		14=>'书写',
		16=>'作文',
		17=>'实验题',
		18=>'填空题',
		19=>'计算题',
		20=>'解答题',
		21=>'简答题',
		22=>'论述题',
		23=>'综合题',
		24=>'完型填空',
		25=>'阅读理解',
		26=>'单词拼写',
		27=>'短文改错',
		28=>'阅读填空',
		29=>'信息匹配',
		30=>'书面表达',
		31=>'句型转换',
		32=>'单词拼写',
		33=>'补充句子',
		34=>'翻译',
		35=>'改错',
		36=>'单词造句',
		37=>'选词填空',
		38=>'作图题',
		39=>'判断题',
		40=>'探究题',
		41=>'辨析题',
		42=>'连线题',
		43=>'推断题',
		44=>'信息分析题',
		45=>'填表题',
		46=>'列举题',
		47=>'问答题'
	);
}

/**
 * 获取题型名称获取ID
 */
function GetTypeIdByName($name){
	$typeArray=topicGetTopicTypes();
	foreach ($typeArray as $key=>$value) {
		if($value===$name){
			return $key;
		}
	}
}
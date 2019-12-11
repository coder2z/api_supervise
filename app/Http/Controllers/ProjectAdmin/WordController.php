<?php

namespace App\Http\Controllers\ProjectAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PhpOffice\PhpWord\PhpWord;

class WordController extends Controller
{
    public function getWord()
    {
        $phpword = new PhpWord();
        $section = $phpword->addSection();
        $phpword->addParagraphStyle('pageStyle',array('spaceAfter' => 240));
        $paragraphStyleName = 'pStyle';
        $phpword->addParagraphStyle('pStyle',array('spacing' => 100));
        $fontStyle = [
            'name' => '宋体',
            'size' => 12,
            'color' => '#000000',
            'bold' => false,
        ];
        $fontStylebold = [
            'name' => '宋体',
            'size' => 12,
            'color' => '#000000',
            'bold' => true,
        ];
        $oneTitileFontStyle = [
            'name' => '等线',
            'size' => 22,
            'color' => '#000000',
            'bold' => true,
        ];
        $twoTitileFontStyle = [
            'name' => '等线',
            'size' => 16,
            'color' => '#000000',
            'bold' => true,
            'align' => 'left',
        ];
        //标题
        $titile = "api接口管理接口文档";
        //应用层标题
        $applicationTitle = "应用返回数据层级";
        //数据层级信息
        $applicationMess = array(
                'data'=>'应用数据',
                'msg'=>'应用信息',
                'code'=>'状态码',
                '200'=>'成功'
            );
        //模块标题
        $moduleTitle = [
            "管理员模块",
        ];
        //模块具体信息
        $className = 'WordController';
        $fullClassName = 'App\Http\Controllers\ProjectAdmin';
        $classFun = '导出word';
        $classMet = 'public function getWord()';
        $tableMetName = 'getWord()';
        $phpword->addTitleStyle(1,$oneTitileFontStyle,array('align'=>'center','spaceAfter' => 240));
        $section->addTitle($titile,1);
        $phpword->addTitleStyle(2,$twoTitileFontStyle,array('align'=>'left','spaceAfter' => 240));
        $section->addTitle($applicationTitle,2);
        $textrun = $section->addTextRun($paragraphStyleName);
        foreach ($applicationMess as $key => $vale){
            $textrun->addText("  ".$key.':'.$vale,$fontStyle);
            $textrun->addTextBreak();
        }
        //模块部分
        $section->addTitle($moduleTitle[0],2,array('align'=>'left','spaceAfter' => 240));
        $textrun = $section->addTextRun($paragraphStyleName);
        $textrun->addText("♦ 类名：".$className,$fontStyle);
        $textrun->addTextBreak();
        $textrun->addText("♦ 全类名：".$fullClassName,$fontStyle);
        $textrun->addTextBreak();
        $textrun->addText("♦ 作用：".$classFun,$fontStyle);
        $textrun->addTextBreak();
        $textrun->addText("♦ 类方法：",$fontStyle);
        $textrun->addTextBreak();
        $textrun->addText('    '.$classMet,$fontStyle);
        $textrun->addTextBreak();
        $textrun->addText("♦ ".$tableMetName,$fontStyle,array('spaceAfter' => 240));
        //表格部分
        $retErrorMsg = array('code' => 100, 'msg' =>'失败', 'data' => '失败信息！');
        $retSuccesMsg =array('code' => 100, 'msg' =>'失败', 'data' => array('信息1'=>'信息1显示','信息2'=>'信息2显示'));
        $arr = array('status' => true,'errMsg' => '错误了','member' =>array(array('name' => '李逍遥','gender' => '男'),array('name' => '赵灵儿','gender' => '女')));
        $switchJson = json_encode($arr, JSON_UNESCAPED_UNICODE);
        $testMsg ="{\"msg\": \"成功\",\"code\": 200,\"data\":{\"current_page\": 1,\"user\":[{\"id\": \"用户Id\",\"name\": \"姓名\",\"phone_number\": \"手机号\",\"email\": \"邮箱\",},{\"id\": \"用户Id\",\"name\": \"姓名\",\"phone_number\": \"手机号\",\"email\": \"邮箱\",},{\"id\": \"用户Id\",\"name\": \"姓名\",\"phone_number\": \"手机号\",\"email\": \"邮箱\",},],\"first_page_url\": \"http://www.api.com/UserItem/0?page=1\",\"from\": 1,\"last_page\": 3,\"last_page_url\": \"http://www.api.com/UserItem/0?page=3\",\"next_page_url\": \"http://www.api.com/UserItem/0?page=2\",\"path\": \"http://www.api.com/UserItem/0\",\"per_page\": 8,\"prev_page_url\": null,\"to\": 8,\"total\": 18,\"item\":[{\"id\": \"项目Id\",\"name\": \"项目名\",},{\"id\": \"项目Id\",\"name\": \"项目名\",},{\"id\": \"项目Id\",\"name\": \"项目名\",},]}}";
        $metFun = "导出项目接口文档";
        substr($metFun,0,1);
        $requestMet = "get";
        $metRoute = "/ProjectAdmin/getWord";
        $inputName = "project_id";
        $inputeType = "int";
        $inputExplaim = "项目id";
        $returnType = "Json（data）";
        $styleTable = array('borderSize' => 6, 'borderColor' => 'black','cellMargin' => 80);//表格整体样式
        $phpword->addTableStyle('talentSystem', $styleTable);
        $table = $section->addTable('talentSystem');
        //第一行
        $table->addRow();
        $table->addCell(2000)->addText('作用',$fontStylebold);
        $table->addCell(6000,array('gridSpan' => 3))->addText($metFun,$fontStyle);
        //第二行
        $table->addRow();
        $table->addCell(2000)->addText("请求方式",$fontStylebold);
        $table->addCell(1000)->addText($requestMet,$fontStyle);
        $table->addCell(1000)->addText("路由",$fontStylebold);
        $table->addCell(4000)->addText($metRoute,$fontStyle);
        //第三行
        $table->addRow();
        $table->addCell(2000)->addText("入参参数名",$fontStylebold);
        $table->addCell(2000,array('gridSpan' => 2))->addText("类型",$fontStylebold);
        $table->addCell(4000)  ->addText("说明",$fontStylebold);
        //第四行
        $table->addRow();
        $table->addCell(2000)->addText($inputName,$fontStyle);
        $table->addCell(2000,array('gridSpan' => 2))->addText($inputeType,$fontStyle);
        $table->addCell(4000)->addText($inputExplaim,$fontStyle);
        //第五行
        $table->addRow();
        $table->addCell(2000)->addText("返回值类型",$fontStylebold);
        $table->addCell(6000,array('gridSpan' => 3))->addText($returnType,$fontStyle);
        //第六行
        $table->addRow(4000);
        $table->addCell(2000)->addText("成功返回示例",$fontStylebold);
        $table->addCell(6000,array('gridSpan' => 3))->addText($metFun,$fontStyle);
        //第七行
        $table->addRow(4000);
        $table->addCell(2000)->addText("失败返回示例",$fontStylebold);
        $cell1 = $table->addCell(6000,array('gridSpan' => 3));
        $celltext = $cell1->addTextRun($paragraphStyleName);
        foreach ($this->mb_str_split($testMsg) as $key){
            if ($key == '['){
                do{
                    $celltext->addText($key, $fontStyle);
                    $celltext->addTextBreak();
                    $celltext->addText("        ",$fontStyle);
                }while($key == ']');
            }
            elseif ($key == ','){
                $celltext->addText($key, $fontStyle);
                $celltext->addTextBreak();
                $celltext->addText("    ",$fontStyle);
            }
            elseif($key ==  '{'){
                $celltext->addText($key, $fontStyle);
                $celltext->addTextBreak();
                $celltext->addText("    ",$fontStyle);
            }
            elseif($key ==  '}'){
                $celltext->addTextBreak();
                $celltext->addText("    ",$fontStyle);
                $celltext->addText($key, $fontStyle);
            }
            else{
                $celltext->addText($key,$fontStyle);
            }
        }
//        $celltext->addText("{",$fontStyle);
//        $celltext->addTextBreak();
//        $celltext->addText("    ",$fontStyle);
//        foreach ($retSuccesMsg as $key1 => $vaule1) {
//            if(is_array($vaule1)){
//                $celltext->addText("{",$fontStyle);
//                $celltext->addText("[",$fontStyle);
//                $celltext->addTextBreak();
//                $celltext->addText("    ",$fontStyle);
//                foreach ($vaule1 as $key2 => $vaule2){
//                    $celltext->addText("{",$fontStyle);
//                    $celltext->addTextBreak();
//                    $celltext->addText("\"", $fontStyle);
//                    $celltext->addText($key2, $fontStyle);
//                    $celltext->addText("\"", $fontStyle);
//                    $celltext->addText(":", $fontStyle);
//                    $celltext->addText("\"", $fontStyle);
//                    $celltext->addText($vaule2, $fontStyle);
//                    $celltext->addText("\"", $fontStyle);
//                    $celltext->addText(",", $fontStyle);
//                    $celltext->addTextBreak();
//                    $celltext->addText("}",$fontStyle);
//                    $celltext->addTextBreak();
//                }
//                $celltext->addText("]",$fontStyle);
//                $celltext->addText("}",$fontStyle);
//            }
//                $celltext->addText("\"", $fontStyle);
//                $celltext->addText($key1, $fontStyle);
//                $celltext->addText("\"", $fontStyle);
//                $celltext->addText(":", $fontStyle);
//                $celltext->addText("\"", $fontStyle);
//                $celltext->addText($vaule1, $fontStyle);
//                $celltext->addText("\"", $fontStyle);
//                $celltext->addText(",", $fontStyle);
//                $celltext->addTextBreak();
//
//        }
//        $celltext->addText("}",$fontStyle);
        $filename = $titile.".docx";
        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpword, 'Word2007');
        $xmlWriter->save("php://output");
    }

    function mb_str_split( $string ) {
        // /u表示把字符串当作utf-8处理，并把字符串开始和结束之前所有的字符串分割成数组
        return preg_split('/(?<!^)(?!$)/u', $string );
    }
}

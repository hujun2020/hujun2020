<?php
require '../vendor/autoload.php';
use common\choujiangModel;
/**
 * 0  没有资格
 * 1  提交成功
 * 2  一天只能一次
 * 3  提交失败
 */

$return=['code'=>0];
//验证资格
session_start();
if(!empty($_SESSION['phone'])){
    $choujiang=new choujiangModel();
    //今天是否参加过
    if($choujiang->hasOperate($_SESSION['phone'])){
        $return['code']=2;
    }else{
        //处理抽奖逻辑
        $return=$choujiang->operate($_SESSION['phone']);
        if($return['index']>0)$return['code']=1;
        else $return['code']=3;
    }
}
echo json_encode($return);
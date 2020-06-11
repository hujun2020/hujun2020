<?php
require '../vendor/autoload.php';
use common\choujiangModel;
use common\downloadTools;

$fileName='获奖记录.csv';
$columns=['ID','手机号','奖品','获得时间'];
$awardModel=new choujiangModel();
$total=$awardModel->getAwardCount()['c'];
$pageSize=500;

$tool=new downloadTools($fileName,$columns,$awardModel,$total,$pageSize);
$tool->download();
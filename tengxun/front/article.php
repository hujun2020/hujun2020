<?php
include_once '../common/validate.php';
include_once '../common/articleModel.php';
/**
 * 0  验证失败
 * 1  提交成功
 * 2  超过次数
 * 3  提交失败
 */

$return=['code'=>0];
$args=$_POST;
$validate=new Validate();
if($validate->validCode($args['code']) && $validate->validPhone($args['phone']) && $content=$validate->vaildContent($args['content'])){
    $article=new articleModel();
    unset($args['code']);
    //是否参加过
    if($article->hasPhone($args['phone'])){
        $return['code']=2;
    }elseif($article->saveArticle($args)){
        $return['code']=1;
    }else{
        $return['code']=3;
    }
}

echo json_encode($return);

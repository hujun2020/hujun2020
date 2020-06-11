<?php
namespace common;
class Validate {

    //验证码
    function validCode($code){
        return true;
    }
    //验证手机号
    function validPhone($phone){
        return preg_match('/^1[34578]\d{9}$/',$phone,$res);
    }
    //验证文章
    function vaildContent($content){
        $len = mb_strlen($content,"utf-8");

        if($len>2 && $len<500){
                return $this->filterXss($content);
        }else return false;
    }
    //xss过滤
    function filterXss($content){
        $str = strip_tags($content);   //过滤html标签

        $str = htmlspecialchars($str);   //将字符内容转化为html实体

        $str = addslashes($str);  //防止SQL注入

        return $str;
    }
}

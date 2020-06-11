<?php
namespace common;
use common\db;
use common\redisUntil;
class articleModel{
    protected $db;
    protected $redis;
    public function __construct()
    {
        $this->db=new db;
        $this->redis=new redisUntil;
    }
    //插入文章
    function saveArticle($arr){
        $time=date('Y-m-d H:i:s');
        $sql="insert into `article`(`phone`,`content`,`create_time`) values(?,?,'$time')";
        if($this->db->save($sql,array_values($arr))) {
            session_start();
            $_SESSION['phone']=$arr['phone'];
            return $this->redis->hSet('phones',$arr['phone'],1); //设定为参加过
        }
    }
    //得到文章信息
    function getArticleById($arr){
        $sql="select `content` from `article` where `id`=?";
        return $this->db->queryColumn($sql,$arr);
    }
    //得到文章列表
    function getArticles($offset){
        $sql="select * from `article` limit $offset";
        return $this->db->query($sql);
    }
    //文章总数
    function getArticleCount(){
        $sql="select count(*) as c from `article`";
        return $this->db->query($sql);
    }
    //是否参加过
    function hasPhone($phone){
        return $this->redis->hExists('phones',$phone);
    }
}
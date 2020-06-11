<?php
namespace common;
use common\db;
use common\redisUntil;
class choujiangModel{
    protected $db;
    protected $redis;
    public function __construct()
    {
        $this->db=new db;
        $this->redis=new redisUntil;
    }
    //今日是否参加过
    function hasOperate($phone){
        return $this->redis->hExists(date('Ymd'),$phone);
    }
    //概率算法
    private function getRand($proArr) {
        $result = '';
        //概率数组的总概率精度
        $proSum = array_sum($proArr);
        //概率数组循环
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset ($proArr);
        return $result;
    }

    /**
     * 手机 5部 每天限制产出1部，概率1% 电话卡 100张 每个用户活动期间内最多获得2张，概率5% 贴纸 不限量 概率94%
     * phone_award_date   集合  存获取手机的当天日期
     */
    function operate($phone){
        //index 和九宫格中的位置保持一致
        $prize_arr['3']=array('index'=>3,'prize'=>'贴纸','rate'=>94);

        $phone_award_date=$this->redis->sMembers('phone_award_date');
        if(!in_array(date('Ymd'),$phone_award_date) && count($phone_award_date)<5){
            $prize_arr['0']=array('index'=>0,'prize'=>'手机','rate'=>1);
        }
        if($this->redis->hGet('phone_card',$phone)<2 && $this->redis->get('phone_card_num')<100){
            $prize_arr['6']=array('index'=>6,'prize'=>'电话卡','rate'=>5);
        }

        foreach ($prize_arr as $key => $val) {
            $arr[$val['index']] = $val['rate'];
        }
        $res['index'] = $this->getRand($arr); //根据概率获取奖项id

        $res['prize'] = $prize_arr[$res['index']]['prize']; //中奖项
        if(!$this->saveAward(['phone'=>$phone,'award'=>$res['index']]))$res['index']=-1;

        return $res;

    }
    //插入获奖信息    award  0手机  3贴纸  6电话卡
    function saveAward($arr){
        $time=date('Y-m-d H:i:s');
        $sql="insert into `award`(`phone`,`award`,`create_time`) values(?,?,'$time')";
        if($this->db->save($sql,array_values($arr))) {
            $res=true;
            if($arr['award']==0){
                $res=$this->redis->sAdd('phone_award_date',date('Ymd'));
            }else if($arr['award']==6){
                $res=$this->redis->hIncrBy('phone_card',$arr['phone'],1) && $this->redis->incr('phone_card_num');
            }
            $totay=date('Ymd');
            $this->redis->hSet($totay,$arr['phone'],1);
            return $res  && $this->redis->expire($totay,86400); //设定为今日抽奖过
        }
    }
    //抽奖数据分页信息
    public function getPageList($offset,$pageSize){
        $sql="select `id`,`phone`,case `award` when '0' then '手机' when '6' then '电话卡' else '贴纸' end as `awardInfo` ,`create_time` from `award` limit $offset,$pageSize";
        return $this->db->query($sql);
    }
    //抽奖总数
    function getAwardCount(){
        $sql="select count(*) as c from `award`";
        return $this->db->query($sql);
    }
}
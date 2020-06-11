<?php
namespace common;
/**
 * 数据库DAO -->>> 对数据库进行操作的类
 */
class db
{
    /**
     * 连接数据的地址
     * @var string
     */
    CONST DRIVER_CLASS = 'mysql:port=3308;host=localhost;dbname=test';

    /**
     * 数据库的用户名
     * @var string
     */
    CONST USERNAME = 'root';

    /**
     * 数据库的密码
     * @var string
     */
    CONST PASSWORD = '';

    /**
     * 数据库连接出错
     * @var string|array
     */
    private $error = '没有异常';

    /**
     * 连接数据库驱动
     * @var PDO
     */
    private $pdo;

    public function __construct()
    {
        try {
            // 初始化执行数据库
            $this->pdo = new \PDO(self::DRIVER_CLASS, self::USERNAME, self::PASSWORD);
            $this->pdo->query('SET NAMES UTF8');
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (PDOException  $e) {
            throw new \Exception($e->getMessage(), 500);
        }
    }

    //查询多个
    public function query($sql)
    {
        try {
            $result = $this->pdo->query($sql,2);
            $data = [];
            foreach($result as $key => $value){
                $data[] = $value;
            }
            return (count($data) <= 1) ? $data[0] : $data ;

        } catch (PDOException  $e) {
             throw new \Exception($e->getMessage(), 500);
        }
    }
   //查询单列值
    public function queryColumn($sql, $select_param)
    {
        try{
            $stmt = $this->pdo->prepare($sql);
            if ($stmt->execute($select_param)) {
                return $stmt->fetchColumn();
            } else {
                return false;
            }
        }catch (PDOException  $e){
            throw new \Exception($e->getMessage(), 500);
        }
    }
    /**
     * [call description]
     * @param  string $sql 执行的语句
     * @param  string $select_param 参数
     * @return [type]
     */
    public function save($sql, $param)
    {
        try{
            $stmt = $this->pdo->prepare($sql);
            if ($stmt->execute($param)) {
                return $stmt->rowCount();
            } else {
                return false;
            }
        }catch (PDOException  $e){
            throw new \Exception($e->getMessage(), 500);
        }

    }


}

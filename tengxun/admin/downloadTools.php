<?php

class downloadTools {
    protected $fileName;
    protected $columns;
    protected $dataObject;
    protected $total;
    protected $pageSize;

    public function __construct($fileName,$columns,$dataObject,$total,$pageSize)
    {
        $this->fileName=$fileName;
        $this->columns=$columns;
        $this->dataObject=$dataObject;
        $this->total=$total;
        $this->pageSize=$pageSize;
    }

    /**
     * 下载的日志文件通常很大, 所以先设置csv相关的Header头, 然后打开
     * PHP output流, 渐进式的往output流中写入数据, 写到一定量后将系统缓冲冲刷到响应中
     * 避免缓冲溢出
     */
    public function download()
    {
            set_time_limit(0);
            //设置好告诉浏览器要下载excel文件的headers
            header('Content-Description: File Transfer');
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="'. $this->fileName .'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            $fp = fopen('php://output', 'a');//打开output流
            fputcsv($fp, $this->columns);//将数据格式化为CSV格式并写入到output流中
            $pages = ceil($this->total / $this->pageSize);
            $lastId = 0;
            for($i = 1; $i <= $pages; $i++) {
                $data = $this->dataObject->getPageList($lastId,$this->pageSize);
                foreach($data as $row) {
                    $rowData = array_values($row);
                    fputcsv($fp, $rowData);
                    $lastId = $row['id'];
                }
                unset($data);//释放变量的内存
                //刷新输出缓冲到浏览器
                ob_flush();
                flush();//必须同时使用 ob_flush() 和flush() 函数来刷新输出缓冲。
            }
            fclose($fp);
    }
}

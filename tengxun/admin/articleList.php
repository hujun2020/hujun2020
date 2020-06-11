<?php
require '../vendor/autoload.php';
use common\ajaxPage;
use common\articleModel;

$article=new articleModel();
$pageSize=20;
$page = new ajaxPage($article->getArticleCount()['c'],$pageSize);
$result = $article->getArticles($page->limit); // 取出数据
echo '<table align="center" border="1" width="1100" style="border-collapse:collapse;font-size:14px;" bordercolor="#666">';
echo '<caption><h1>征文列表</h1></caption>';
echo '<tr height="25"><th>ID</th><th>手机号</th><th>内容</th><th>发表时间</th></tr>';
foreach ($result as $v) {
    echo "<tr height='21'><td align='center'>{$v['id']}</td><td align='center'>{$v['phone']}</td><td align='center'><a href=\"./articleDetail.php?id={$v['id']}\">查看</a></td><td align='center'>{$v['create_time']}</td></tr>";
}
echo '<tr><td align="right" colspan="4">'.$page->fpage().'</td></tr>';
echo '<tr><td align="center" colspan="4"><a href="awardDownload.php">导出所有获奖记录</a></td></tr>';
echo '</table>';




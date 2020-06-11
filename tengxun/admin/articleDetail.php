<?php
require '../vendor/autoload.php';
use common\articleModel;
$id=$_GET['id'];

$article=new articleModel();
$content=$article->getArticleById([$id]);

echo htmlspecialchars_decode($content);

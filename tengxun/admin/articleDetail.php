<?php
include_once '../common/articleModel.php';
$id=$_GET['id'];

$article=new articleModel();
$content=$article->getArticleById([$id]);

echo htmlspecialchars_decode($content);

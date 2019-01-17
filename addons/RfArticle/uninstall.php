<?php

// 表前缀
$table_prefixion = "rf_addon_";
// 列表
$table_name = ['article', 'article_cate', 'article_single', 'article_tag', 'article_tag_map', 'article_adv'];

$sql = "";
foreach ($table_name as $value)
{
    $table = $table_prefixion . $value;
    $sql .= "DROP TABLE IF EXISTS `{$table}`;";
}

// 执行sql
Yii::$app->getDb()->createCommand($sql)->execute();
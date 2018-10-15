<?php

// 表前缀
$table_prefixion = "rf_addon_sign_shopping_street_";
// 列表
$table_name = ['award', 'record', 'stat', 'user'];

$sql = "";
foreach ($table_name as $value)
{
    $table = $table_prefixion . $value;
    $sql .= "DROP TABLE IF EXISTS `{$table}`;";
}

// 执行sql
 Yii::$app->getDb()->createCommand($sql)->execute();
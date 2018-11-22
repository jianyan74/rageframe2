<?php

// 获取模块信息
if (!($addon = \common\models\sys\Addons::findByName('RfArticle')))
{
    return false;
}

// 版本记录 每次修改可在这里添加版本信息后去 updateDatabaseField 函数添加具体的执行内容
$versionsHistory = [
    '1.0.0', // 默认版本
    '1.0.1',
    '1.0.2',
];

$isUpdate = false;
for ($i = 0; $i < count($versionsHistory); $i++)
{
    $isUpdate == true && updateDatabaseField($versionsHistory[$i]);
    $addon->version == $versionsHistory[$i] && $isUpdate = true;
}

// 更新版本号
$addon->version = end($versionsHistory);
// $addon->save();

/**
 * 版本更新函数
 *
 * @param $version
 * @throws \yii\db\Exception
 */
function updateDatabaseField($version)
{
    switch ($version)
    {
        case '1.0.1' :
            // 增加测试 - 冗余的字段
            $sql = "ALTER TABLE rf_addon_example_curd ADD COLUMN redundancy_field varchar(48) CHARACTER SET utf8mb4 COLLATE utf8_general_ci COMMENT '冗余字段';";
            // Yii::$app->getDb()->createCommand($sql)->execute();
            break;

        case '1.0.2' :
            // 删除测试 - 冗余的字段
            $sql = "ALTER TABLE `rf_addon_example_curd` DROP `redundancy_field`;";
            // Yii::$app->getDb()->createCommand($sql)->execute();
            break;
    }

    return true;
}



<?php

namespace console\controllers;

use Yii;
use yii\helpers\Console;
use yii\helpers\ArrayHelper;
use console\components\MigrateCreate;

/**
 * Class ConsoleController
 * @package console\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ConsoleController extends \e282486518\migration\ConsoleController
{
    /**
     * Creates a new migration. php yii migrate/backup all
     *
     * @param string $name
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function actionBackup($name){
        /* 所有数据表 */
        $alltables  = Yii::$app->db->createCommand('SHOW TABLE STATUS')->queryAll();
        $alltables  = array_map('array_change_key_case', $alltables);
        $alltables  = ArrayHelper::getColumn($alltables, 'name');

        $name = trim($name,',');
        if ($name == 'all') {
            /* 备份所有数据 */
            $tables  = $alltables;
        } else if(strpos($name, ',')){
            /* 备份部分数据表 */
            $tables = explode(',', $name);
        } else {
            /* 备份一个数据表 */
            $tables = [$name];
        }
        /* 检查表是否存在 */
        foreach ($tables as $table) {
            if (!in_array($table,$alltables)) {
                $this->stdout($table." table no find ...\n", Console::FG_RED);
                die();
            }
        }
        /* 创建migration */
        foreach ($tables as $table) {
            //$migrate = new MigrateCreate();
            $migrate = Yii::createObject([
                'class' => MigrateCreate::class,
                'migrationPath' => '@app/migrations'
            ]);
            $migrate->create($table);
            unset($migrate);
        }

        $this->stdout("backup success.\n", Console::FG_GREEN);
    }
}
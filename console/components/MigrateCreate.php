<?php

namespace console\components;

use Yii;

/**
 * Class MigrateCreate
 * @package console\components
 * @author jianyan74 <751393839@qq.com>
 */
class MigrateCreate extends \e282486518\migration\components\MigrateCreate
{
    /**
     * @param string $table
     * @return array|void
     * @throws \yii\db\Exception
     */
    public function generateTableData($table){
        $tableSchema = \Yii::$app->db->getTableSchema($table);
        $data = Yii::$app->db->createCommand('SELECT * FROM `' . $table . '`')->queryAll();
        //$array = [];
        if (is_array($data)) {
            $this->upStr->addStr('/* 表数据 */');
            foreach ($data as $row) {
                $out = '$this->insert(\'{{%' . $this->getTableName($table) . '}}\',[';
                foreach ($tableSchema->columns as $column) {
                    /* 注意：addslashes会将null转化为'' */
                    if (is_null($row[ $column->name ])) {
                        $out .= "'" . $column->name . "'=>NULL,";
                    } elseif ($this->is_serialized($row[ $column->name ]) || is_array(json_decode($row[ $column->name ], true))) {
                        /* 序列化的内容被addslashes就不能反序列化了 */
                        $out .= "'" . $column->name . "'=>'" . $row[ $column->name ] . "',";
                    } else {
                        $out .= "'" . $column->name . "'=>'" . addslashes($row[ $column->name ]) . "',";
                    }
                }
                $out = rtrim($out, ',') . ']);';
                //$array[] = $out;
                $this->upStr->addStr($out);
            }
        }
        //return $array;
    }
}
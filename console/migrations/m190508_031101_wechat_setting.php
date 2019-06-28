<?php

use yii\db\Migration;

class m190508_031101_wechat_setting extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%wechat_setting}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'merchant_id' => "int(10) unsigned NULL COMMENT '商户id'",
            'history' => "varchar(200) NULL DEFAULT '' COMMENT '历史消息参数设置'",
            'special' => "text NULL COMMENT '特殊消息回复参数'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='微信_参数设置'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        $this->insert('{{%wechat_setting}}',['id'=>'5','merchant_id'=>'1','history'=>'{\"history_status\":\"0\",\"msg_history_date\":\"1\",\"utilization_status\":\"1\"}','special'=>'{\"image\":{\"type\":\"1\",\"content\":\"123\",\"selected\":\"RfExample\"},\"voice\":{\"type\":\"1\",\"content\":\"\",\"selected\":\"\"},\"video\":{\"type\":\"1\",\"content\":\"\",\"selected\":\"\"},\"shortvideo\":{\"type\":\"1\",\"content\":\"\",\"selected\":\"\"},\"location\":{\"type\":\"1\",\"content\":\"\",\"selected\":\"\"},\"trace\":{\"type\":\"1\",\"content\":\"\",\"selected\":\"\"},\"link\":{\"type\":\"1\",\"content\":\"\",\"selected\":\"\"},\"merchant_order\":{\"type\":\"1\",\"content\":\"\",\"selected\":\"\"},\"ShakearoundUserShake\":{\"type\":\"1\",\"content\":\"\",\"selected\":\"\"},\"ShakearoundLotteryBind\":{\"type\":\"1\",\"content\":\"\",\"selected\":\"\"},\"WifiConnected\":{\"type\":\"1\",\"content\":\"\",\"selected\":\"\"}}','status'=>'1','created_at'=>'1555566470','updated_at'=>'1557281781']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%wechat_setting}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}


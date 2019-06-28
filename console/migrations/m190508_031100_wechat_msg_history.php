<?php

use yii\db\Migration;

class m190508_031100_wechat_msg_history extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%wechat_msg_history}}', [
            'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT",
            'merchant_id' => "int(10) unsigned NULL COMMENT '商户id'",
            'rule_id' => "int(10) unsigned NULL COMMENT '规则id'",
            'keyword_id' => "int(10) NULL DEFAULT '0' COMMENT '关键字id'",
            'openid' => "varchar(50) NULL DEFAULT ''",
            'module' => "varchar(50) NULL DEFAULT '' COMMENT '触发模块'",
            'addons_name' => "varchar(100) NOT NULL DEFAULT '' COMMENT '插件名称'",
            'message' => "varchar(1000) NULL DEFAULT '' COMMENT '微信消息'",
            'type' => "varchar(20) NULL DEFAULT ''",
            'event' => "varchar(20) NULL DEFAULT '' COMMENT '详细事件'",
            'status' => "tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL COMMENT '创建时间'",
            'updated_at' => "int(10) NOT NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='微信_历史记录表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%wechat_msg_history}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}


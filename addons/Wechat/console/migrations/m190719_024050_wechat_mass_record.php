<?php

use yii\db\Migration;

class m190719_024050_wechat_mass_record extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%addon_wechat_mass_record}}', [
            'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'msg_id' => "bigint(20) NULL DEFAULT '0' COMMENT '微信消息id'",
            'msg_data_id' => "varchar(10) NULL DEFAULT '0' COMMENT '图文消息数据id'",
            'tag_id' => "int(10) NULL DEFAULT '0' COMMENT '标签id'",
            'tag_name' => "varchar(50) NULL DEFAULT '' COMMENT '标签名称'",
            'fans_num' => "int(10) unsigned NULL DEFAULT '0' COMMENT '粉丝数量'",
            'module' => "varchar(50) NULL DEFAULT '' COMMENT '模块类型'",
            'data' => "text NULL",
            'send_type' => "tinyint(4) NULL DEFAULT '1' COMMENT '发送类别 1立即发送2定时发送'",
            'send_time' => "int(10) unsigned NULL DEFAULT '0' COMMENT '发送时间'",
            'send_status' => "tinyint(4) NULL DEFAULT '0' COMMENT '0未发送 1已发送'",
            'final_send_time' => "int(10) unsigned NULL DEFAULT '0' COMMENT '最终发送时间'",
            'error_content' => "text NULL COMMENT '报错原因'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0'",
            'updated_at' => "int(10) NOT NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='微信_群发记录'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%addon_wechat_mass_record}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}


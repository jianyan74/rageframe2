<?php

use yii\db\Migration;

class m190719_024050_wechat_menu extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%addon_wechat_menu}}', [
            'id' => "int(10) NOT NULL AUTO_INCREMENT COMMENT '公众号id'",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'menu_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '微信菜单id'",
            'type' => "tinyint(3) unsigned NULL DEFAULT '1' COMMENT '1:默认菜单；2个性化菜单'",
            'title' => "varchar(30) NULL DEFAULT '' COMMENT '标题'",
            'sex' => "tinyint(3) unsigned NULL COMMENT '性别'",
            'tag_id' => "int(10) NULL DEFAULT '0' COMMENT '标签id'",
            'client_platform_type' => "tinyint(3) unsigned NULL COMMENT '手机系统'",
            'country' => "varchar(100) NULL DEFAULT '中国' COMMENT '国家'",
            'province' => "varchar(100) NULL COMMENT '省'",
            'city' => "varchar(50) NULL COMMENT '市'",
            'language' => "varchar(50) NULL DEFAULT '' COMMENT '语言'",
            'menu_data' => "json NULL COMMENT '微信菜单'",
            'status' => "tinyint(3) NULL DEFAULT '0' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='微信_自定义菜单'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%addon_wechat_menu}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}


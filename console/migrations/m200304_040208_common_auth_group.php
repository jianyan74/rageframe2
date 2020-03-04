<?php

use yii\db\Migration;

class m200304_040208_common_auth_group extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%common_auth_group}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT COMMENT '主键'",
            'title' => "varchar(100) NOT NULL DEFAULT '' COMMENT '分组名称'",
            'app_id' => "varchar(30) NOT NULL DEFAULT '' COMMENT '应用ID'",
            'is_free' => "tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否免费，0：否，1：是'",
            'price' => "decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT '价格'",
            'count_unit' => "tinyint(1) unsigned NULL COMMENT '计算单位，0：天，1：周，2：月，3，季，4，年'",
            'status' => "tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态，0：禁用，1：启用，-1：删除'",
            'sort' => "int(4) unsigned NOT NULL DEFAULT '0' COMMENT '排序'",
            'created_at' => "int(11) unsigned NULL COMMENT '创建时间'",
            'updated_at' => "int(11) unsigned NULL COMMENT '最后更新时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='公用-权限角色分组表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */

        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%common_auth_group}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}


<?php

use yii\db\Migration;

class m200304_040208_common_auth_allocation extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%common_auth_allocation}}', [
            'member_id' => "bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID'",
            'group_id' => "bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '权限组ID'",
            'app_id' => "varchar(30) NOT NULL DEFAULT '' COMMENT '应用ID'",
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='公用-用户分组关联表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */

        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%common_auth_allocation}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}


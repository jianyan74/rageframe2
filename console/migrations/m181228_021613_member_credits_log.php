<?php

use yii\db\Migration;

class m181228_021613_member_credits_log extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%member_credits_log}}', [
            'id' => 'bigint(20) NOT NULL AUTO_INCREMENT',
            'member_id' => 'int(11) NULL DEFAULT \'0\' COMMENT \'会员id\'',
            'credit_type' => 'varchar(30) NOT NULL DEFAULT \'\' COMMENT \'变动类型[integral:积分;money:余额]\'',
            'credit_group' => 'varchar(30) NULL DEFAULT \'\' COMMENT \'变动的详细组别\'',
            'old_num' => 'double(10,2) NULL DEFAULT \'0\' COMMENT \'之前的数据\'',
            'new_num' => 'double(10,2) NULL DEFAULT \'0\' COMMENT \'变动后的数据\'',
            'num' => 'double(10,2) NULL DEFAULT \'0\' COMMENT \'变动的数据\'',
            'remark' => 'varchar(200) NULL DEFAULT \'\' COMMENT \'备注\'',
            'status' => 'tinyint(4) NULL DEFAULT \'1\' COMMENT \'状态[-1:删除;0:禁用;1启用]\'',
            'created_at' => 'int(10) NULL DEFAULT \'0\' COMMENT \'创建时间\'',
            'updated_at' => 'int(10) NULL DEFAULT \'0\' COMMENT \'修改时间\'',
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='会员_积分等变动表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%member_credits_log}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}


<?php

use yii\db\Migration;

class m181228_023003_addon_sign_shopping_street_record extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%addon_sign_shopping_street_record}}', [
            'id' => 'int(11) NOT NULL AUTO_INCREMENT',
            'openid' => 'varchar(50) NOT NULL',
            'is_win' => 'int(2) NOT NULL DEFAULT \'2\'',
            'award_id' => 'varchar(50) NULL',
            'award_title' => 'varchar(255) NULL',
            'award_cate_id' => 'int(11) NULL DEFAULT \'0\'',
            'record_date' => 'date NULL COMMENT \'日期\'',
            'status' => 'tinyint(4) NULL DEFAULT \'1\' COMMENT \'状态(-1:已删除,0:禁用,1:正常)\'',
            'created_at' => 'int(10) NULL DEFAULT \'0\' COMMENT \'创建时间\'',
            'updated_at' => 'int(10) unsigned NULL DEFAULT \'0\' COMMENT \'修改时间\'',
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='扩展_购物节_中奖记录表'");
        
        /* 索引设置 */
        $this->createIndex('openid','{{%addon_sign_shopping_street_record}}','openid',0);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%addon_sign_shopping_street_record}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}


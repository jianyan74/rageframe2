<?php

use yii\db\Migration;

class m181228_023003_addon_sign_shopping_street_award extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%addon_sign_shopping_street_award}}', [
            'id' => 'int(11) NOT NULL AUTO_INCREMENT',
            'title' => 'varchar(50) NOT NULL COMMENT \'奖品名称\'',
            'cate_id' => 'tinyint(4) NULL DEFAULT \'1\' COMMENT \'分类\'',
            'sort' => 'int(10) NULL DEFAULT \'0\' COMMENT \'排序\'',
            'prob' => 'mediumint(7) NOT NULL DEFAULT \'0\' COMMENT \'中奖概率\'',
            'all_num' => 'int(10) NOT NULL DEFAULT \'0\' COMMENT \'奖品总数量\'',
            'surplus_num' => 'int(10) NOT NULL DEFAULT \'0\' COMMENT \'奖品剩余数量\'',
            'max_day_num' => 'int(10) NOT NULL DEFAULT \'0\' COMMENT \'每日限制中奖数\'',
            'max_user_num' => 'int(10) NOT NULL DEFAULT \'0\' COMMENT \'每人最多中奖数\'',
            'start_time' => 'int(10) NULL DEFAULT \'0\' COMMENT \'奖品有效开始时间\'',
            'end_time' => 'int(10) NULL DEFAULT \'0\' COMMENT \'奖品有效结束时间\'',
            'draw_start_time' => 'int(10) NULL DEFAULT \'0\' COMMENT \'奖品可中开始时间\'',
            'draw_end_time' => 'int(10) NULL DEFAULT \'0\' COMMENT \'奖品可中结束时间\'',
            'status' => 'tinyint(4) NOT NULL DEFAULT \'1\' COMMENT \'状态(-1:已删除,0:禁用,1:正常)\'',
            'created_at' => 'int(10) NULL DEFAULT \'0\' COMMENT \'创建时间\'',
            'updated_at' => 'int(10) unsigned NULL DEFAULT \'0\' COMMENT \'修改时间\'',
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='扩展_购物节_奖品表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%addon_sign_shopping_street_award}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}


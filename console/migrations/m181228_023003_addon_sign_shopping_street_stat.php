<?php

use yii\db\Migration;

class m181228_023003_addon_sign_shopping_street_stat extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%addon_sign_shopping_street_stat}}', [
            'id' => 'int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT \'自动编号\'',
            'openid' => 'varchar(50) NOT NULL DEFAULT \'\' COMMENT \'用户openid\'',
            'source_page' => 'varchar(200) NOT NULL DEFAULT \'\' COMMENT \'来源 url\'',
            'page' => 'varchar(200) NOT NULL DEFAULT \'\' COMMENT \'当前页面 url\'',
            'device' => 'varchar(20) NOT NULL DEFAULT \'\' COMMENT \'设备\'',
            'ip' => 'varchar(20) NOT NULL DEFAULT \'\' COMMENT \'ip地址\'',
            'status' => 'tinyint(4) NOT NULL DEFAULT \'1\' COMMENT \'状态(-1:已删除,0:禁用,1:正常)\'',
            'created_at' => 'int(10) NULL DEFAULT \'0\' COMMENT \'创建时间\'',
            'updated_at' => 'int(10) unsigned NULL DEFAULT \'0\' COMMENT \'修改时间\'',
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='扩展_购物节_访问记录表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%addon_sign_shopping_street_stat}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}


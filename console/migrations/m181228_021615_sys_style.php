<?php

use yii\db\Migration;

class m181228_021615_sys_style extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%sys_style}}', [
            'id' => 'int(11) NOT NULL AUTO_INCREMENT',
            'manager_id' => 'int(11) unsigned NULL DEFAULT \'0\' COMMENT \'管理员id\'',
            'skin_id' => 'tinyint(4) unsigned NULL DEFAULT \'0\' COMMENT \'皮肤id[0:默认;1;蓝色3:黄色]\'',
            'created_at' => 'int(10) unsigned NULL DEFAULT \'0\' COMMENT \'创建时间\'',
            'updated_at' => 'int(10) unsigned NULL DEFAULT \'0\' COMMENT \'修改时间\'',
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='系统_风格'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%sys_style}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}


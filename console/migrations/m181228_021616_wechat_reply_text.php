<?php

use yii\db\Migration;

class m181228_021616_wechat_reply_text extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%wechat_reply_text}}', [
            'id' => 'int(10) NOT NULL AUTO_INCREMENT',
            'rule_id' => 'int(10) NULL DEFAULT \'0\' COMMENT \'规则id\'',
            'content' => 'varchar(1000) NULL COMMENT \'内容\'',
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='微信_文字回复'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%wechat_reply_text}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}


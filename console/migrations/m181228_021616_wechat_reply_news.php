<?php

use yii\db\Migration;

class m181228_021616_wechat_reply_news extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%wechat_reply_news}}', [
            'id' => 'int(10) NOT NULL AUTO_INCREMENT',
            'rule_id' => 'int(10) NULL',
            'attachment_id' => 'int(10) NULL DEFAULT \'0\'',
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='微信_图文回复'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%wechat_reply_news}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}


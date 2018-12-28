<?php

use yii\db\Migration;

class m181228_021616_wechat_reply_user_api extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%wechat_reply_user_api}}', [
            'id' => 'int(10) NOT NULL AUTO_INCREMENT',
            'rule_id' => 'int(10) NULL DEFAULT \'0\' COMMENT \'规则id\'',
            'api_url' => 'varchar(255) NOT NULL DEFAULT \'\' COMMENT \'接口地址\'',
            'description' => 'varchar(255) NULL DEFAULT \'\' COMMENT \'说明\'',
            'default' => 'varchar(50) NULL DEFAULT \'\' COMMENT \'默认回复\'',
            'cache_time' => 'int(10) NULL DEFAULT \'0\' COMMENT \'缓存时间 0默认为不缓存\'',
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='微信_自定义接口回复'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%wechat_reply_user_api}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}


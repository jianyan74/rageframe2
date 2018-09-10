<?php

use yii\db\Migration;

class m180910_013518_wechat_fans_tags extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%wechat_fans_tags}}', [
            'id' => 'int(10) NOT NULL AUTO_INCREMENT',
            'tags' => 'longtext NULL COMMENT \'标签\'',
            'status' => 'tinyint(4) NULL DEFAULT \'1\' COMMENT \'状态[-1:删除;0:禁用;1启用]\'',
            'created_at' => 'int(10) unsigned NULL DEFAULT \'0\' COMMENT \'创建时间\'',
            'updated_at' => 'int(10) NOT NULL DEFAULT \'0\' COMMENT \'修改时间\'',
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='微信_粉丝标签表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        $this->insert('{{%wechat_fans_tags}}',['id'=>'7','tags'=>'a:2:{i:0;a:3:{s:2:"id";i:2;s:4:"name";s:9:"星标组";s:5:"count";i:1;}i:1;a:3:{s:2:"id";i:113;s:4:"name";s:12:"星星点灯";s:5:"count";i:0;}}','status'=>'1','created_at'=>'1536541370','updated_at'=>'1536541370']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%wechat_fans_tags}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}


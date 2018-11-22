<?php

use yii\db\Migration;

class m181122_084409_wechat_setting extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%wechat_setting}}', [
            'id' => 'int(11) NOT NULL AUTO_INCREMENT',
            'history' => 'varchar(200) NULL DEFAULT \'\' COMMENT \'历史消息参数设置\'',
            'special' => 'text NULL COMMENT \'特殊消息回复参数\'',
            'status' => 'tinyint(4) NULL DEFAULT \'1\' COMMENT \'状态[-1:删除;0:禁用;1启用]\'',
            'created_at' => 'int(10) NOT NULL DEFAULT \'0\' COMMENT \'创建时间\'',
            'updated_at' => 'int(10) NOT NULL DEFAULT \'0\' COMMENT \'修改时间\'',
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COMMENT='微信_参数设置'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        $this->insert('{{%wechat_setting}}',['id'=>'4','history'=>'{\"history_status\":\"1\",\"msg_history_date\":\"0\",\"utilization_status\":\"1\"}','special'=>NULL,'status'=>'1','created_at'=>'1541035698','updated_at'=>'1541035698']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%wechat_setting}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}


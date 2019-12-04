<?php

use yii\db\Migration;

class m191107_050146_common_config_value extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%common_config_value}}', [
            'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键'",
            'config_id' => "int(10) NOT NULL DEFAULT '0' COMMENT '配置id'",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'data' => "text NULL COMMENT '配置内'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='公用_配置值表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        $this->insert('{{%common_config_value}}',['id'=>'1','config_id'=>'6','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'2','config_id'=>'1','merchant_id'=>'1','data'=>'© 2016 - 2019 RageFrame All Rights Reserved.']);
        $this->insert('{{%common_config_value}}',['id'=>'3','config_id'=>'60','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'4','config_id'=>'59','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'5','config_id'=>'4','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'6','config_id'=>'2','merchant_id'=>'1','data'=>'RageFrame']);
        $this->insert('{{%common_config_value}}',['id'=>'7','config_id'=>'5','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'8','config_id'=>'7','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'9','config_id'=>'52','merchant_id'=>'1','data'=>'1']);
        $this->insert('{{%common_config_value}}',['id'=>'10','config_id'=>'55','merchant_id'=>'1','data'=>'1']);
        $this->insert('{{%common_config_value}}',['id'=>'11','config_id'=>'53','merchant_id'=>'1','data'=>'0']);
        $this->insert('{{%common_config_value}}',['id'=>'12','config_id'=>'90','merchant_id'=>'1','data'=>'0']);
        $this->insert('{{%common_config_value}}',['id'=>'13','config_id'=>'64','merchant_id'=>'1','data'=>'1']);
        $this->insert('{{%common_config_value}}',['id'=>'14','config_id'=>'61','merchant_id'=>'1','data'=>'1']);

        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%common_config_value}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}


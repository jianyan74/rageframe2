<?php

use yii\db\Migration;

class m200102_075647_common_config_value extends Migration
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
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='公用_配置值表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        $this->insert('{{%common_config_value}}',['id'=>'63','config_id'=>'6','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'64','config_id'=>'1','merchant_id'=>'1','data'=>'© 2016 - 2020 RageFrame All Rights Reserved.']);
        $this->insert('{{%common_config_value}}',['id'=>'65','config_id'=>'60','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'66','config_id'=>'59','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'67','config_id'=>'4','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'68','config_id'=>'2','merchant_id'=>'1','data'=>'RageFrame']);
        $this->insert('{{%common_config_value}}',['id'=>'69','config_id'=>'5','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'70','config_id'=>'7','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'71','config_id'=>'52','merchant_id'=>'1','data'=>'1']);
        $this->insert('{{%common_config_value}}',['id'=>'72','config_id'=>'55','merchant_id'=>'1','data'=>'1']);
        $this->insert('{{%common_config_value}}',['id'=>'73','config_id'=>'53','merchant_id'=>'1','data'=>'0']);
        $this->insert('{{%common_config_value}}',['id'=>'74','config_id'=>'90','merchant_id'=>'1','data'=>'0']);
        $this->insert('{{%common_config_value}}',['id'=>'75','config_id'=>'64','merchant_id'=>'1','data'=>'1']);
        $this->insert('{{%common_config_value}}',['id'=>'76','config_id'=>'61','merchant_id'=>'1','data'=>'1']);
        $this->insert('{{%common_config_value}}',['id'=>'77','config_id'=>'40','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'78','config_id'=>'41','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'79','config_id'=>'42','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'80','config_id'=>'43','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'81','config_id'=>'44','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'82','config_id'=>'45','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'83','config_id'=>'46','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'84','config_id'=>'47','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'85','config_id'=>'65','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'86','config_id'=>'66','merchant_id'=>'1','data'=>'0']);
        $this->insert('{{%common_config_value}}',['id'=>'87','config_id'=>'75','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'88','config_id'=>'76','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'89','config_id'=>'77','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'90','config_id'=>'78','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'91','config_id'=>'79','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'92','config_id'=>'80','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'93','config_id'=>'81','merchant_id'=>'1','data'=>'0']);
        $this->insert('{{%common_config_value}}',['id'=>'94','config_id'=>'87','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'97','config_id'=>'8','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'99','config_id'=>'10','merchant_id'=>'1','data'=>'4']);
        $this->insert('{{%common_config_value}}',['id'=>'104','config_id'=>'16','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'105','config_id'=>'17','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'106','config_id'=>'19','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'110','config_id'=>'36','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'111','config_id'=>'37','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'112','config_id'=>'48','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'113','config_id'=>'49','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'114','config_id'=>'50','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'115','config_id'=>'51','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'116','config_id'=>'56','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'117','config_id'=>'57','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'118','config_id'=>'58','merchant_id'=>'1','data'=>'']);
        $this->insert('{{%common_config_value}}',['id'=>'119','config_id'=>'67','merchant_id'=>'1','data'=>'']);
        
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


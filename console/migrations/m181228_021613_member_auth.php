<?php

use yii\db\Migration;

class m181228_021613_member_auth extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%member_auth}}', [
            'id' => 'int(10) NOT NULL AUTO_INCREMENT COMMENT \'主键\'',
            'member_id' => 'int(10) NULL DEFAULT \'0\' COMMENT \'用户id\'',
            'unionid' => 'varchar(64) NULL DEFAULT \'\' COMMENT \'唯一ID\'',
            'oauth_client' => 'varchar(20) NULL DEFAULT \'\' COMMENT \'授权组别\'',
            'oauth_client_user_id' => 'varchar(100) NULL DEFAULT \'\' COMMENT \'授权id\'',
            'sex' => 'tinyint(4) NULL DEFAULT \'1\' COMMENT \'性别\'',
            'nickname' => 'varchar(200) NULL DEFAULT \'\' COMMENT \'昵称\'',
            'head_portrait' => 'varchar(200) NULL DEFAULT \'\' COMMENT \'头像\'',
            'birthday' => 'date NULL COMMENT \'生日\'',
            'country' => 'varchar(100) NULL DEFAULT \'\' COMMENT \'国家\'',
            'province' => 'varchar(100) NULL DEFAULT \'\' COMMENT \'省\'',
            'city' => 'varchar(100) NULL DEFAULT \'\' COMMENT \'市\'',
            'status' => 'tinyint(4) NOT NULL DEFAULT \'1\' COMMENT \'状态(-1:已删除,0:禁用,1:正常)\'',
            'created_at' => 'int(10) NULL DEFAULT \'0\' COMMENT \'创建时间\'',
            'updated_at' => 'int(10) NULL DEFAULT \'0\' COMMENT \'修改时间\'',
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='用户_第三方登录'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%member_auth}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}


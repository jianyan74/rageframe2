<?php

use yii\db\Migration;

class m190719_024050_wechat_fans extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%addon_wechat_fans}}', [
            'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'member_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '用户id'",
            'unionid' => "varchar(64) NULL DEFAULT '' COMMENT '唯一公众号ID'",
            'openid' => "varchar(50) NOT NULL DEFAULT '' COMMENT 'openid'",
            'nickname' => "varchar(50) NULL DEFAULT '' COMMENT '昵称'",
            'head_portrait' => "varchar(255) NULL DEFAULT '' COMMENT '头像'",
            'sex' => "tinyint(2) NULL DEFAULT '0' COMMENT '性别'",
            'follow' => "tinyint(1) NULL DEFAULT '1' COMMENT '是否关注[1:关注;0:取消关注]'",
            'followtime' => "int(10) unsigned NULL DEFAULT '0' COMMENT '关注时间'",
            'unfollowtime' => "int(10) unsigned NULL DEFAULT '0' COMMENT '取消关注时间'",
            'group_id' => "int(10) NULL DEFAULT '0' COMMENT '分组id'",
            'tag' => "varchar(1000) NULL DEFAULT '' COMMENT '标签'",
            'last_longitude' => "varchar(10) NULL DEFAULT '' COMMENT '最后一次经纬度上报'",
            'last_latitude' => "varchar(10) NULL DEFAULT '' COMMENT '最后一次经纬度上报'",
            'last_address' => "varchar(100) NULL DEFAULT '' COMMENT '最后一次经纬度上报地址'",
            'last_updated' => "int(10) NULL DEFAULT '0' COMMENT '最后更新时间'",
            'country' => "varchar(100) NULL DEFAULT '' COMMENT '国家'",
            'province' => "varchar(100) NULL DEFAULT '' COMMENT '省'",
            'city' => "varchar(100) NULL DEFAULT '' COMMENT '市'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '添加时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='微信_粉丝表'");
        
        /* 索引设置 */
        $this->createIndex('openid','{{%addon_wechat_fans}}','openid',0);
        $this->createIndex('nickname','{{%addon_wechat_fans}}','nickname',0);
        $this->createIndex('member_id','{{%addon_wechat_fans}}','member_id',0);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%addon_wechat_fans}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}


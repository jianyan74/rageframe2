<?php

use yii\db\Migration;

class m181228_021614_sys_config_cate extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%sys_config_cate}}', [
            'id' => 'int(10) NOT NULL AUTO_INCREMENT COMMENT \'主键\'',
            'title' => 'varchar(50) NOT NULL DEFAULT \'\' COMMENT \'标题\'',
            'pid' => 'int(10) unsigned NULL DEFAULT \'0\' COMMENT \'上级id\'',
            'level' => 'tinyint(1) unsigned NULL DEFAULT \'1\' COMMENT \'级别\'',
            'sort' => 'int(5) NULL DEFAULT \'0\' COMMENT \'排序\'',
            'status' => 'tinyint(4) NULL DEFAULT \'1\' COMMENT \'状态[-1:删除;0:禁用;1启用]\'',
            'created_at' => 'int(10) unsigned NULL DEFAULT \'0\' COMMENT \'添加时间\'',
            'updated_at' => 'int(10) unsigned NULL DEFAULT \'0\' COMMENT \'修改时间\'',
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COMMENT='系统_配置分类表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        $this->insert('{{%sys_config_cate}}',['id'=>'1','title'=>'网站配置','pid'=>'0','level'=>'1','sort'=>'0','status'=>'1','created_at'=>'1497177084','updated_at'=>'1507794040']);
        $this->insert('{{%sys_config_cate}}',['id'=>'2','title'=>'系统配置','pid'=>'0','level'=>'1','sort'=>'1','status'=>'1','created_at'=>'1497177084','updated_at'=>'1526377118']);
        $this->insert('{{%sys_config_cate}}',['id'=>'3','title'=>'微信/小程序配置','pid'=>'0','level'=>'1','sort'=>'3','status'=>'1','created_at'=>'1497177084','updated_at'=>'1526711653']);
        $this->insert('{{%sys_config_cate}}',['id'=>'4','title'=>'第三方支付','pid'=>'0','level'=>'1','sort'=>'4','status'=>'1','created_at'=>'1497177095','updated_at'=>'1526711369']);
        $this->insert('{{%sys_config_cate}}',['id'=>'5','title'=>'第三方登录','pid'=>'0','level'=>'1','sort'=>'5','status'=>'1','created_at'=>'1497177103','updated_at'=>'1526711371']);
        $this->insert('{{%sys_config_cate}}',['id'=>'6','title'=>'邮件配置','pid'=>'0','level'=>'1','sort'=>'6','status'=>'1','created_at'=>'1497177113','updated_at'=>'1526711373']);
        $this->insert('{{%sys_config_cate}}',['id'=>'7','title'=>'云存储','pid'=>'0','level'=>'1','sort'=>'7','status'=>'1','created_at'=>'1497177124','updated_at'=>'1526711376']);
        $this->insert('{{%sys_config_cate}}',['id'=>'8','title'=>'支付宝','pid'=>'4','level'=>'2','sort'=>'2','status'=>'1','created_at'=>'1497177218','updated_at'=>'1521773795']);
        $this->insert('{{%sys_config_cate}}',['id'=>'9','title'=>'微信','pid'=>'4','level'=>'2','sort'=>'1','status'=>'1','created_at'=>'1497177226','updated_at'=>'1526520128']);
        $this->insert('{{%sys_config_cate}}',['id'=>'10','title'=>'银联','pid'=>'4','level'=>'2','sort'=>'3','status'=>'1','created_at'=>'1497177235','updated_at'=>'1515130737']);
        $this->insert('{{%sys_config_cate}}',['id'=>'11','title'=>'QQ登录','pid'=>'5','level'=>'2','sort'=>'0','status'=>'1','created_at'=>'1497177283','updated_at'=>'1497177283']);
        $this->insert('{{%sys_config_cate}}',['id'=>'12','title'=>'微博登录','pid'=>'5','level'=>'2','sort'=>'1','status'=>'1','created_at'=>'1497177295','updated_at'=>'1497177401']);
        $this->insert('{{%sys_config_cate}}',['id'=>'13','title'=>'微信登录','pid'=>'5','level'=>'2','sort'=>'2','status'=>'1','created_at'=>'1497177320','updated_at'=>'1497177402']);
        $this->insert('{{%sys_config_cate}}',['id'=>'14','title'=>'GitHub登录','pid'=>'5','level'=>'2','sort'=>'3','status'=>'1','created_at'=>'1497177361','updated_at'=>'1497177403']);
        $this->insert('{{%sys_config_cate}}',['id'=>'15','title'=>'七牛云','pid'=>'7','level'=>'2','sort'=>'0','status'=>'1','created_at'=>'1497177378','updated_at'=>'1497177378']);
        $this->insert('{{%sys_config_cate}}',['id'=>'16','title'=>'邮件','pid'=>'6','level'=>'2','sort'=>'0','status'=>'1','created_at'=>'1497177394','updated_at'=>'1497181686']);
        $this->insert('{{%sys_config_cate}}',['id'=>'17','title'=>'网站配置','pid'=>'1','level'=>'2','sort'=>'0','status'=>'1','created_at'=>'1497177421','updated_at'=>'1545644441']);
        $this->insert('{{%sys_config_cate}}',['id'=>'18','title'=>'系统配置','pid'=>'2','level'=>'2','sort'=>'0','status'=>'1','created_at'=>'1497177428','updated_at'=>'1497182113']);
        $this->insert('{{%sys_config_cate}}',['id'=>'19','title'=>'公众号','pid'=>'3','level'=>'2','sort'=>'0','status'=>'1','created_at'=>'1497177441','updated_at'=>'1497181644']);
        $this->insert('{{%sys_config_cate}}',['id'=>'21','title'=>'阿里云OSS','pid'=>'7','level'=>'2','sort'=>'1','status'=>'1','created_at'=>'1506747965','updated_at'=>'1506747965']);
        $this->insert('{{%sys_config_cate}}',['id'=>'22','title'=>'分享配置','pid'=>'3','level'=>'2','sort'=>'2','status'=>'1','created_at'=>'1506755826','updated_at'=>'1506755826']);
        $this->insert('{{%sys_config_cate}}',['id'=>'25','title'=>'小程序','pid'=>'3','level'=>'2','sort'=>'1','status'=>'1','created_at'=>'1526711398','updated_at'=>'1526711398']);
        $this->insert('{{%sys_config_cate}}',['id'=>'26','title'=>'视频直播','pid'=>'0','level'=>'1','sort'=>'8','status'=>'1','created_at'=>'1526869221','updated_at'=>'1526869255']);
        $this->insert('{{%sys_config_cate}}',['id'=>'27','title'=>'阿里云','pid'=>'26','level'=>'2','sort'=>'0','status'=>'1','created_at'=>'1526869577','updated_at'=>'1526869591']);
        $this->insert('{{%sys_config_cate}}',['id'=>'30','title'=>'图片处理','pid'=>'2','level'=>'2','sort'=>'1','status'=>'1','created_at'=>'1545272329','updated_at'=>'1545282625']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%sys_config_cate}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}


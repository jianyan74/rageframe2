<?php

use yii\db\Migration;

class m181228_023631_sys_addons extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%sys_addons}}', [
            'id' => 'int(11) NOT NULL AUTO_INCREMENT COMMENT \'主键\'',
            'title' => 'varchar(20) NOT NULL DEFAULT \'\' COMMENT \'中文名\'',
            'name' => 'varchar(40) NOT NULL DEFAULT \'\' COMMENT \'插件名或标识\'',
            'title_initial' => 'varchar(1) NOT NULL DEFAULT \'\' COMMENT \'首字母拼音\'',
            'cover' => 'varchar(200) NULL DEFAULT \'\' COMMENT \'封面\'',
            'group' => 'varchar(20) NULL DEFAULT \'\' COMMENT \'组别\'',
            'brief_introduction' => 'varchar(140) NULL DEFAULT \'\' COMMENT \'简单介绍\'',
            'description' => 'varchar(1000) NULL DEFAULT \'\' COMMENT \'插件描述\'',
            'config' => 'text NULL COMMENT \'配置\'',
            'author' => 'varchar(40) NULL DEFAULT \'\' COMMENT \'作者\'',
            'version' => 'varchar(20) NULL DEFAULT \'\' COMMENT \'版本号\'',
            'wechat_message' => 'varchar(1000) NULL DEFAULT \'\' COMMENT \'接收微信回复类别\'',
            'is_setting' => 'tinyint(1) NULL DEFAULT \'-1\' COMMENT \'设置\'',
            'is_hook' => 'tinyint(1) NULL DEFAULT \'0\' COMMENT \'钩子[0:不支持;1:支持]\'',
            'is_rule' => 'tinyint(4) NULL DEFAULT \'0\' COMMENT \'是否要嵌入规则\'',
            'is_mini_program' => 'tinyint(4) NULL DEFAULT \'0\' COMMENT \'小程序[0:不支持;1:支持]\'',
            'status' => 'tinyint(4) NOT NULL DEFAULT \'1\' COMMENT \'状态[-1:删除;0:禁用;1启用]\'',
            'created_at' => 'int(10) NOT NULL DEFAULT \'0\' COMMENT \'创建时间\'',
            'updated_at' => 'int(10) NOT NULL DEFAULT \'0\' COMMENT \'修改时间\'',
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='系统_插件表'");
        
        /* 索引设置 */
        $this->createIndex('name','{{%sys_addons}}','name',0);
        $this->createIndex('status','{{%sys_addons}}','status',0);
        
        
        /* 表数据 */
        $this->insert('{{%sys_addons}}',['id'=>'1','title'=>'内容管理','name'=>'RfArticle','title_initial'=>'N','cover'=>'','group'=>'plug','brief_introduction'=>'内置基础的单页，文章管理','description'=>'文章管理','config'=>NULL,'author'=>'简言','version'=>'1.0.0','wechat_message'=>'a:0:{}','is_setting'=>'0','is_hook'=>'1','is_rule'=>'0','is_mini_program'=>'0','status'=>'1','created_at'=>'1545964141','updated_at'=>'1545964141']);
        $this->insert('{{%sys_addons}}',['id'=>'2','title'=>'示例管理','name'=>'RfExample','title_initial'=>'S','cover'=>'','group'=>'plug','brief_introduction'=>'系统的功能示例','description'=>'系统自带的功能使用示例及其说明，包含一些简单的交互','config'=>NULL,'author'=>'简言','version'=>'1.0.0','wechat_message'=>'a:4:{i:0;s:5:"image";i:1;s:5:"voice";i:2;s:5:"video";i:3;s:10:"shortvideo";}','is_setting'=>'1','is_hook'=>'1','is_rule'=>'1','is_mini_program'=>'1','status'=>'1','created_at'=>'1545964146','updated_at'=>'1545964146']);
        $this->insert('{{%sys_addons}}',['id'=>'3','title'=>'购物节','name'=>'RfSignShoppingDay','title_initial'=>'G','cover'=>'','group'=>'activity','brief_introduction'=>'购物节签到抽奖','description'=>'购物节签到活动，每日可签到抽奖一次, 随机获取奖品','config'=>NULL,'author'=>'简言','version'=>'1.0.0','wechat_message'=>'a:0:{}','is_setting'=>'1','is_hook'=>'0','is_rule'=>'0','is_mini_program'=>'0','status'=>'1','created_at'=>'1545964153','updated_at'=>'1545964153']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%sys_addons}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}


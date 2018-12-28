<?php

use yii\db\Migration;

class m181228_021615_sys_menu_cate extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%sys_menu_cate}}', [
            'id' => 'int(10) NOT NULL AUTO_INCREMENT COMMENT \'主键\'',
            'title' => 'varchar(50) NOT NULL DEFAULT \'\' COMMENT \'标题\'',
            'icon' => 'varchar(20) NULL DEFAULT \'\' COMMENT \'icon\'',
            'is_default_show' => 'tinyint(4) unsigned NULL DEFAULT \'0\' COMMENT \'默认显示\'',
            'sort' => 'int(5) NULL DEFAULT \'0\' COMMENT \'排序\'',
            'status' => 'tinyint(4) NULL DEFAULT \'1\' COMMENT \'状态[-1:删除;0:禁用;1启用]\'',
            'created_at' => 'int(10) unsigned NULL DEFAULT \'0\' COMMENT \'添加时间\'',
            'updated_at' => 'int(10) unsigned NULL DEFAULT \'0\' COMMENT \'修改时间\'',
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COMMENT='系统_菜单分类表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        $this->insert('{{%sys_menu_cate}}',['id'=>'1','title'=>'平台首页','icon'=>'fa-bookmark','is_default_show'=>'1','sort'=>'0','status'=>'1','created_at'=>'1528042032','updated_at'=>'1542332455']);
        $this->insert('{{%sys_menu_cate}}',['id'=>'2','title'=>'微信公众号','icon'=>'fa-wechat','is_default_show'=>'0','sort'=>'1','status'=>'1','created_at'=>'1528042033','updated_at'=>'1545289508']);
        $this->insert('{{%sys_menu_cate}}',['id'=>'3','title'=>'系统管理','icon'=>'fa-cogs','is_default_show'=>'0','sort'=>'2','status'=>'1','created_at'=>'1528042096','updated_at'=>'1542332432']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%sys_menu_cate}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}


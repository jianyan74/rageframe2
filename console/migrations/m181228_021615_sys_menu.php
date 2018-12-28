<?php

use yii\db\Migration;

class m181228_021615_sys_menu extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%sys_menu}}', [
            'id' => 'int(11) NOT NULL AUTO_INCREMENT',
            'title' => 'varchar(50) NOT NULL DEFAULT \'\' COMMENT \'标题\'',
            'pid' => 'int(50) NULL DEFAULT \'0\' COMMENT \'上级id\'',
            'url' => 'varchar(50) NULL DEFAULT \'\' COMMENT \'链接地址\'',
            'menu_css' => 'varchar(50) NULL DEFAULT \'\' COMMENT \'样式\'',
            'sort' => 'int(5) NULL DEFAULT \'0\' COMMENT \'排序\'',
            'level' => 'tinyint(1) NULL DEFAULT \'1\' COMMENT \'级别\'',
            'cate_id' => 'tinyint(5) NULL DEFAULT \'0\'',
            'dev' => 'tinyint(4) NULL DEFAULT \'0\' COMMENT \'开发者[0:都可见;开发模式可见]\'',
            'params' => 'varchar(1000) NULL DEFAULT \'\' COMMENT \'参数\'',
            'status' => 'tinyint(1) NULL DEFAULT \'1\' COMMENT \'状态[-1:删除;0:禁用;1启用]\'',
            'created_at' => 'int(10) NULL DEFAULT \'0\' COMMENT \'添加时间\'',
            'updated_at' => 'int(10) NULL DEFAULT \'0\' COMMENT \'修改时间\'',
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COMMENT='系统_菜单导航表'");
        
        /* 索引设置 */
        $this->createIndex('url','{{%sys_menu}}','url',0);
        
        
        /* 表数据 */
        $this->insert('{{%sys_menu}}',['id'=>'1','title'=>'会员管理','pid'=>'0','url'=>'index-member','menu_css'=>'fa-user','sort'=>'0','level'=>'1','cate_id'=>'1','dev'=>'0','params'=>'a:1:{i:0;a:2:{s:3:"key";s:0:"";s:5:"value";s:0:"";}}','status'=>'1','created_at'=>'1526456122','updated_at'=>'1545962174']);
        $this->insert('{{%sys_menu}}',['id'=>'2','title'=>'网站设置','pid'=>'0','url'=>'/sys/config/edit-all','menu_css'=>'fa-cog','sort'=>'0','level'=>'1','cate_id'=>'3','dev'=>'0','params'=>'','status'=>'1','created_at'=>'1526456141','updated_at'=>'1545289337']);
        $this->insert('{{%sys_menu}}',['id'=>'3','title'=>'系统功能','pid'=>'0','url'=>'sys-function','menu_css'=>'fa-suitcase','sort'=>'1','level'=>'1','cate_id'=>'3','dev'=>'0','params'=>'','status'=>'1','created_at'=>'1526456224','updated_at'=>'1545642977']);
        $this->insert('{{%sys_menu}}',['id'=>'4','title'=>'应用管理','pid'=>'3','url'=>'/sys/addons-plug/uninstall','menu_css'=>'','sort'=>'0','level'=>'2','cate_id'=>'3','dev'=>'0','params'=>'','status'=>'1','created_at'=>'1526456248','updated_at'=>'1528278861']);
        $this->insert('{{%sys_menu}}',['id'=>'5','title'=>'配置管理','pid'=>'3','url'=>'/sys/config/index','menu_css'=>'','sort'=>'1','level'=>'2','cate_id'=>'3','dev'=>'1','params'=>'','status'=>'1','created_at'=>'1526456309','updated_at'=>'1529117846']);
        $this->insert('{{%sys_menu}}',['id'=>'6','title'=>'后台菜单','pid'=>'3','url'=>'/sys/menu/index','menu_css'=>'','sort'=>'2','level'=>'2','cate_id'=>'3','dev'=>'1','params'=>'','status'=>'1','created_at'=>'1526456437','updated_at'=>'1529117741']);
        $this->insert('{{%sys_menu}}',['id'=>'7','title'=>'用户权限','pid'=>'0','url'=>'sys-manager-auth','menu_css'=>'fa-user-secret','sort'=>'2','level'=>'1','cate_id'=>'3','dev'=>'0','params'=>'','status'=>'1','created_at'=>'1526456471','updated_at'=>'1545643008']);
        $this->insert('{{%sys_menu}}',['id'=>'8','title'=>'后台用户','pid'=>'7','url'=>'/sys/manager/index','menu_css'=>'','sort'=>'0','level'=>'2','cate_id'=>'3','dev'=>'0','params'=>'','status'=>'1','created_at'=>'1526456499','updated_at'=>'1528044761']);
        $this->insert('{{%sys_menu}}',['id'=>'9','title'=>'角色管理','pid'=>'7','url'=>'/sys/auth-role/index','menu_css'=>'','sort'=>'1','level'=>'2','cate_id'=>'3','dev'=>'0','params'=>'','status'=>'1','created_at'=>'1526456556','updated_at'=>'1528419635']);
        $this->insert('{{%sys_menu}}',['id'=>'10','title'=>'权限管理','pid'=>'7','url'=>'/sys/auth-accredit/index','menu_css'=>'','sort'=>'2','level'=>'2','cate_id'=>'3','dev'=>'1','params'=>'','status'=>'1','created_at'=>'1526456596','updated_at'=>'1530515235']);
        $this->insert('{{%sys_menu}}',['id'=>'11','title'=>'规则管理','pid'=>'7','url'=>'/sys/auth-rule/index','menu_css'=>'','sort'=>'3','level'=>'2','cate_id'=>'3','dev'=>'1','params'=>'','status'=>'1','created_at'=>'1526456639','updated_at'=>'1535508573']);
        $this->insert('{{%sys_menu}}',['id'=>'35','title'=>'历史消息','pid'=>'0','url'=>'/wechat/msg-history/index','menu_css'=>'fa-comments','sort'=>'3','level'=>'1','cate_id'=>'2','dev'=>'0','params'=>'','status'=>'1','created_at'=>'1528279611','updated_at'=>'1528279611']);
        $this->insert('{{%sys_menu}}',['id'=>'13','title'=>'系统工具','pid'=>'0','url'=>'sys-tool','menu_css'=>'fa-microchip','sort'=>'3','level'=>'1','cate_id'=>'3','dev'=>'0','params'=>'','status'=>'1','created_at'=>'1526456681','updated_at'=>'1545643018']);
        $this->insert('{{%sys_menu}}',['id'=>'14','title'=>'备份/还原','pid'=>'13','url'=>'/sys/data-base/backups','menu_css'=>'','sort'=>'2','level'=>'2','cate_id'=>'3','dev'=>'0','params'=>'','status'=>'1','created_at'=>'1526456715','updated_at'=>'1529133449']);
        $this->insert('{{%sys_menu}}',['id'=>'40','title'=>'查看日志','pid'=>'13','url'=>'/sys/log/action','menu_css'=>'','sort'=>'1','level'=>'2','cate_id'=>'3','dev'=>'0','params'=>'','status'=>'1','created_at'=>'1529133353','updated_at'=>'1529326260']);
        $this->insert('{{%sys_menu}}',['id'=>'19','title'=>'增强功能','pid'=>'0','url'=>'wechat-to-enhance-function','menu_css'=>'fa-superpowers','sort'=>'0','level'=>'1','cate_id'=>'2','dev'=>'0','params'=>'a:1:{i:0;a:2:{s:3:"key";s:0:"";s:5:"value";s:0:"";}}','status'=>'1','created_at'=>'1526456959','updated_at'=>'1545709658']);
        $this->insert('{{%sys_menu}}',['id'=>'20','title'=>'粉丝管理','pid'=>'0','url'=>'wechat-fans','menu_css'=>'fa-heart','sort'=>'1','level'=>'1','cate_id'=>'2','dev'=>'0','params'=>'a:1:{i:0;a:2:{s:3:"key";s:0:"";s:5:"value";s:0:"";}}','status'=>'1','created_at'=>'1526456983','updated_at'=>'1545709673']);
        $this->insert('{{%sys_menu}}',['id'=>'21','title'=>'素材库','pid'=>'0','url'=>'/wechat/attachment/index','menu_css'=>'fa-file-image-o','sort'=>'2','level'=>'1','cate_id'=>'2','dev'=>'0','params'=>'','status'=>'1','created_at'=>'1526457142','updated_at'=>'1530871672']);
        $this->insert('{{%sys_menu}}',['id'=>'22','title'=>'参数','pid'=>'0','url'=>'/wechat/setting/history-stat','menu_css'=>'fa-cog','sort'=>'6','level'=>'1','cate_id'=>'2','dev'=>'0','params'=>'','status'=>'1','created_at'=>'1526457177','updated_at'=>'1528245747']);
        $this->insert('{{%sys_menu}}',['id'=>'23','title'=>'数据统计','pid'=>'0','url'=>'wechat-data-statistics','menu_css'=>'fa-pie-chart','sort'=>'5','level'=>'1','cate_id'=>'2','dev'=>'0','params'=>'a:1:{i:0;a:2:{s:3:"key";s:0:"";s:5:"value";s:0:"";}}','status'=>'1','created_at'=>'1526457197','updated_at'=>'1545709693']);
        $this->insert('{{%sys_menu}}',['id'=>'24','title'=>'自动回复','pid'=>'19','url'=>'/wechat/rule/index','menu_css'=>'','sort'=>'0','level'=>'2','cate_id'=>'2','dev'=>'0','params'=>'','status'=>'1','created_at'=>'1526708408','updated_at'=>'1545290016']);
        $this->insert('{{%sys_menu}}',['id'=>'25','title'=>'自定义菜单','pid'=>'19','url'=>'/wechat/menu/index','menu_css'=>'','sort'=>'1','level'=>'2','cate_id'=>'2','dev'=>'0','params'=>'','status'=>'1','created_at'=>'1526708445','updated_at'=>'1545289477']);
        $this->insert('{{%sys_menu}}',['id'=>'26','title'=>'二维码/转化链接','pid'=>'19','url'=>'/wechat/qrcode/index','menu_css'=>'','sort'=>'2','level'=>'2','cate_id'=>'2','dev'=>'0','params'=>'','status'=>'1','created_at'=>'1526708511','updated_at'=>'1526708511']);
        $this->insert('{{%sys_menu}}',['id'=>'27','title'=>'粉丝管理','pid'=>'20','url'=>'/wechat/fans/index','menu_css'=>'','sort'=>'0','level'=>'2','cate_id'=>'2','dev'=>'0','params'=>'','status'=>'1','created_at'=>'1526708540','updated_at'=>'1526708540']);
        $this->insert('{{%sys_menu}}',['id'=>'28','title'=>'粉丝标签','pid'=>'20','url'=>'/wechat/fans-tags/index','menu_css'=>'','sort'=>'1','level'=>'2','cate_id'=>'2','dev'=>'0','params'=>'','status'=>'1','created_at'=>'1526708560','updated_at'=>'1526708560']);
        $this->insert('{{%sys_menu}}',['id'=>'29','title'=>'历史消息','pid'=>'18','url'=>'/wechat/msg-history/index','menu_css'=>'','sort'=>'3','level'=>'1','cate_id'=>'2','dev'=>'0','params'=>'','status'=>'1','created_at'=>'1526708598','updated_at'=>'1526708598']);
        $this->insert('{{%sys_menu}}',['id'=>'30','title'=>'粉丝关注统计','pid'=>'23','url'=>'/wechat/stat/fans-follow','menu_css'=>'','sort'=>'0','level'=>'2','cate_id'=>'2','dev'=>'0','params'=>'','status'=>'1','created_at'=>'1526708655','updated_at'=>'1526708655']);
        $this->insert('{{%sys_menu}}',['id'=>'31','title'=>'回复规则使用量','pid'=>'23','url'=>'/wechat/stat/rule','menu_css'=>'','sort'=>'1','level'=>'2','cate_id'=>'2','dev'=>'0','params'=>'','status'=>'1','created_at'=>'1526708681','updated_at'=>'1526708681']);
        $this->insert('{{%sys_menu}}',['id'=>'32','title'=>'关键字命中规则','pid'=>'23','url'=>'/wechat/stat/rule-keyword','menu_css'=>'','sort'=>'2','level'=>'2','cate_id'=>'2','dev'=>'0','params'=>'','status'=>'1','created_at'=>'1526708703','updated_at'=>'1526708703']);
        $this->insert('{{%sys_menu}}',['id'=>'33','title'=>'定时群发','pid'=>'18','url'=>'/wechat/timing-mass/index','menu_css'=>'','sort'=>'4','level'=>'1','cate_id'=>'2','dev'=>'0','params'=>'','status'=>'1','created_at'=>'1526708823','updated_at'=>'1526708823']);
        $this->insert('{{%sys_menu}}',['id'=>'34','title'=>'系统信息','pid'=>'13','url'=>'/sys/system/info','menu_css'=>'','sort'=>'0','level'=>'2','cate_id'=>'3','dev'=>'0','params'=>'','status'=>'1','created_at'=>'1528279347','updated_at'=>'1528279347']);
        $this->insert('{{%sys_menu}}',['id'=>'36','title'=>'定时群发','pid'=>'0','url'=>'/wechat/mass-record/index','menu_css'=>'fa-send','sort'=>'4','level'=>'1','cate_id'=>'2','dev'=>'0','params'=>'','status'=>'1','created_at'=>'1528372622','updated_at'=>'1528372723']);
        $this->insert('{{%sys_menu}}',['id'=>'41','title'=>'服务器信息','pid'=>'13','url'=>'/sys/system/server','menu_css'=>'','sort'=>'3','level'=>'2','cate_id'=>'3','dev'=>'0','params'=>'','status'=>'1','created_at'=>'1529133395','updated_at'=>'1529284227']);
        $this->insert('{{%sys_menu}}',['id'=>'42','title'=>'会员信息','pid'=>'1','url'=>'/member/member/index','menu_css'=>'','sort'=>'0','level'=>'2','cate_id'=>'1','dev'=>'0','params'=>'a:1:{i:0;a:2:{s:3:"key";s:0:"";s:5:"value";s:0:"";}}','status'=>'1','created_at'=>'1531791306','updated_at'=>'1545701948']);
        $this->insert('{{%sys_menu}}',['id'=>'44','title'=>'第三方用户','pid'=>'1','url'=>'/member/auth/index','menu_css'=>'','sort'=>'1','level'=>'2','cate_id'=>'1','dev'=>'0','params'=>'','status'=>'1','created_at'=>'1543646302','updated_at'=>'1543646318']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%sys_menu}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}


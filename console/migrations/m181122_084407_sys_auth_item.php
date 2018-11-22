<?php

use yii\db\Migration;

class m181122_084407_sys_auth_item extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%sys_auth_item}}', [
            'name' => 'varchar(64) NOT NULL',
            'type' => 'int(11) NOT NULL',
            'key' => 'int(10) NOT NULL DEFAULT \'0\' COMMENT \'唯一key\'',
            'description' => 'text NULL',
            'rule_name' => 'varchar(64) NULL DEFAULT \'\' COMMENT \'规则名称\'',
            'data' => 'text NULL',
            'parent_key' => 'int(10) NULL DEFAULT \'0\' COMMENT \'父级key\'',
            'level' => 'int(5) NULL DEFAULT \'1\' COMMENT \'级别\'',
            'sort' => 'int(10) NULL DEFAULT \'0\' COMMENT \'排序\'',
            'created_at' => 'int(11) NULL DEFAULT \'0\'',
            'updated_at' => 'int(11) NULL DEFAULT \'0\'',
            'PRIMARY KEY (`name`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='系统_角色路由表'");
        
        /* 索引设置 */
        $this->createIndex('rule_name','{{%sys_auth_item}}','rule_name',0);
        $this->createIndex('type','{{%sys_auth_item}}','type',0);
        
        /* 外键约束设置 */
        $this->addForeignKey('fk_sys_auth_rule_9964_00','{{%sys_auth_item}}', 'rule_name', '{{%sys_auth_rule}}', 'name', 'CASCADE', 'CASCADE' );
        
        /* 表数据 */
        $this->insert('{{%sys_auth_item}}',['name'=>'/main/clear-cache','type'=>'2','key'=>'7','description'=>'清除缓存','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'1','level'=>'2','sort'=>'2','created_at'=>'1536813537','updated_at'=>'1538203113']);
        $this->insert('{{%sys_auth_item}}',['name'=>'/member/address/delete','type'=>'2','key'=>'17','description'=>'地址删除','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'14','level'=>'4','sort'=>'3','created_at'=>'1536813937','updated_at'=>'1536813937']);
        $this->insert('{{%sys_auth_item}}',['name'=>'/member/address/edit','type'=>'2','key'=>'16','description'=>'地址编辑','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'14','level'=>'4','sort'=>'1','created_at'=>'1536813917','updated_at'=>'1536813917']);
        $this->insert('{{%sys_auth_item}}',['name'=>'/member/address/index','type'=>'2','key'=>'15','description'=>'地址首页','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'14','level'=>'4','sort'=>'0','created_at'=>'1536813882','updated_at'=>'1536813898']);
        $this->insert('{{%sys_auth_item}}',['name'=>'/member/member/ajax-edit','type'=>'2','key'=>'12','description'=>'账号密码','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'8','level'=>'3','sort'=>'3','created_at'=>'1536813729','updated_at'=>'1536813729']);
        $this->insert('{{%sys_auth_item}}',['name'=>'/member/member/ajax-update','type'=>'2','key'=>'13','description'=>'状态修改','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'8','level'=>'3','sort'=>'4','created_at'=>'1536813800','updated_at'=>'1536813800']);
        $this->insert('{{%sys_auth_item}}',['name'=>'/member/member/delete','type'=>'2','key'=>'11','description'=>'会员删除','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'8','level'=>'3','sort'=>'2','created_at'=>'1536813696','updated_at'=>'1536813696']);
        $this->insert('{{%sys_auth_item}}',['name'=>'/member/member/edit','type'=>'2','key'=>'10','description'=>'会员编辑/创建','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'8','level'=>'3','sort'=>'0','created_at'=>'1536813659','updated_at'=>'1536813659']);
        $this->insert('{{%sys_auth_item}}',['name'=>'/member/member/index','type'=>'2','key'=>'9','description'=>'会员首页','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'8','level'=>'3','sort'=>'1','created_at'=>'1536813630','updated_at'=>'1536813630']);
        $this->insert('{{%sys_auth_item}}',['name'=>'/sys/addons-plug/create','type'=>'2','key'=>'24','description'=>'设计新插件','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'21','level'=>'4','sort'=>'2','created_at'=>'1536814574','updated_at'=>'1536814574']);
        $this->insert('{{%sys_auth_item}}',['name'=>'/sys/addons-plug/install','type'=>'2','key'=>'23','description'=>'安装插件','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'21','level'=>'4','sort'=>'1','created_at'=>'1536814551','updated_at'=>'1536814551']);
        $this->insert('{{%sys_auth_item}}',['name'=>'/sys/addons-plug/uninstall','type'=>'2','key'=>'22','description'=>'已安装的插件','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'21','level'=>'4','sort'=>'0','created_at'=>'1536814522','updated_at'=>'1536814522']);
        $this->insert('{{%sys_auth_item}}',['name'=>'/sys/config-cate/ajax-update','type'=>'2','key'=>'34','description'=>'状态修改','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'29','level'=>'5','sort'=>'3','created_at'=>'1536814984','updated_at'=>'1536814984']);
        $this->insert('{{%sys_auth_item}}',['name'=>'/sys/config-cate/delete','type'=>'2','key'=>'33','description'=>'分类删除','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'29','level'=>'5','sort'=>'0','created_at'=>'1536814958','updated_at'=>'1536814958']);
        $this->insert('{{%sys_auth_item}}',['name'=>'/sys/config-cate/edit','type'=>'2','key'=>'32','description'=>'分类编辑','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'29','level'=>'5','sort'=>'0','created_at'=>'1536814939','updated_at'=>'1536814939']);
        $this->insert('{{%sys_auth_item}}',['name'=>'/sys/config-cate/index','type'=>'2','key'=>'31','description'=>'分类首页','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'29','level'=>'5','sort'=>'0','created_at'=>'1536814898','updated_at'=>'1536814898']);
        $this->insert('{{%sys_auth_item}}',['name'=>'/sys/config/ajax-update','type'=>'2','key'=>'30','description'=>'状态修改','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'25','level'=>'4','sort'=>'3','created_at'=>'1536814818','updated_at'=>'1536814818']);
        $this->insert('{{%sys_auth_item}}',['name'=>'/sys/config/delete','type'=>'2','key'=>'28','description'=>'配置删除','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'25','level'=>'4','sort'=>'2','created_at'=>'1536814744','updated_at'=>'1536814744']);
        $this->insert('{{%sys_auth_item}}',['name'=>'/sys/config/edit','type'=>'2','key'=>'27','description'=>'配置编辑/创建','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'25','level'=>'4','sort'=>'1','created_at'=>'1536814716','updated_at'=>'1536814716']);
        $this->insert('{{%sys_auth_item}}',['name'=>'/sys/config/edit-all','type'=>'2','key'=>'19','description'=>'网站设置','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'4','level'=>'2','sort'=>'0','created_at'=>'1536814332','updated_at'=>'1536814383']);
        $this->insert('{{%sys_auth_item}}',['name'=>'/sys/config/index','type'=>'2','key'=>'26','description'=>'配置首页','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'25','level'=>'4','sort'=>'0','created_at'=>'1536814687','updated_at'=>'1536814687']);
        $this->insert('{{%sys_auth_item}}',['name'=>'/sys/manager/personal','type'=>'2','key'=>'5','description'=>'个人中心','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'1','level'=>'2','sort'=>'0','created_at'=>'1536813501','updated_at'=>'1536813501']);
        $this->insert('{{%sys_auth_item}}',['name'=>'/sys/manager/up-password','type'=>'2','key'=>'6','description'=>'修改密码','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'1','level'=>'2','sort'=>'0','created_at'=>'1536813520','updated_at'=>'1538203081']);
        $this->insert('{{%sys_auth_item}}',['name'=>'/sys/menu-cate/ajax-update','type'=>'2','key'=>'49','description'=>'状态修改','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'45','level'=>'5','sort'=>'3','created_at'=>'1537098975','updated_at'=>'1537098975']);
        $this->insert('{{%sys_auth_item}}',['name'=>'/sys/menu-cate/delete','type'=>'2','key'=>'48','description'=>'分类删除','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'45','level'=>'5','sort'=>'2','created_at'=>'1537098927','updated_at'=>'1537098927']);
        $this->insert('{{%sys_auth_item}}',['name'=>'/sys/menu-cate/edit','type'=>'2','key'=>'47','description'=>'分类编辑','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'45','level'=>'5','sort'=>'1','created_at'=>'1537098911','updated_at'=>'1537098911']);
        $this->insert('{{%sys_auth_item}}',['name'=>'/sys/menu-cate/index','type'=>'2','key'=>'46','description'=>'分类首页','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'45','level'=>'5','sort'=>'0','created_at'=>'1537098892','updated_at'=>'1537098892']);
        $this->insert('{{%sys_auth_item}}',['name'=>'/sys/menu/ajax-update','type'=>'2','key'=>'50','description'=>'状态修改','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'41','level'=>'4','sort'=>'3','created_at'=>'1537099000','updated_at'=>'1537099000']);
        $this->insert('{{%sys_auth_item}}',['name'=>'/sys/menu/delete','type'=>'2','key'=>'44','description'=>'菜单删除','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'41','level'=>'4','sort'=>'2','created_at'=>'1537098840','updated_at'=>'1537098840']);
        $this->insert('{{%sys_auth_item}}',['name'=>'/sys/menu/edit','type'=>'2','key'=>'43','description'=>'菜单编辑','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'41','level'=>'4','sort'=>'1','created_at'=>'1537098815','updated_at'=>'1537098815']);
        $this->insert('{{%sys_auth_item}}',['name'=>'/sys/menu/index','type'=>'2','key'=>'42','description'=>'菜单首页','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'41','level'=>'4','sort'=>'0','created_at'=>'1537098768','updated_at'=>'1537098768']);
        $this->insert('{{%sys_auth_item}}',['name'=>'/sys/system/info','type'=>'2','key'=>'57','description'=>'系统信息','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'56','level'=>'3','sort'=>'0','created_at'=>'1537099995','updated_at'=>'1537099995']);
        $this->insert('{{%sys_auth_item}}',['name'=>'/sys/system/server','type'=>'2','key'=>'60','description'=>'服务器信息','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'56','level'=>'3','sort'=>'3','created_at'=>'1537100191','updated_at'=>'1537100191']);
        $this->insert('{{%sys_auth_item}}',['name'=>'base','type'=>'2','key'=>'1','description'=>'系统基础','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'0','level'=>'1','sort'=>'0','created_at'=>'1536813374','updated_at'=>'1536813430']);
        $this->insert('{{%sys_auth_item}}',['name'=>'index','type'=>'2','key'=>'2','description'=>'平台首页','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'0','level'=>'1','sort'=>'1','created_at'=>'1536813419','updated_at'=>'1536813419']);
        $this->insert('{{%sys_auth_item}}',['name'=>'index-member','type'=>'2','key'=>'8','description'=>'会员管理','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'2','level'=>'2','sort'=>'0','created_at'=>'1536813598','updated_at'=>'1536814405']);
        $this->insert('{{%sys_auth_item}}',['name'=>'member-address','type'=>'2','key'=>'14','description'=>'收货地址','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'8','level'=>'3','sort'=>'0','created_at'=>'1536813849','updated_at'=>'1536813849']);
        $this->insert('{{%sys_auth_item}}',['name'=>'sys','type'=>'2','key'=>'4','description'=>'系统管理','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'0','level'=>'1','sort'=>'3','created_at'=>'1536813476','updated_at'=>'1536813476']);
        $this->insert('{{%sys_auth_item}}',['name'=>'sys-addons-plug','type'=>'2','key'=>'21','description'=>'应用管理','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'20','level'=>'3','sort'=>'0','created_at'=>'1536814468','updated_at'=>'1536814468']);
        $this->insert('{{%sys_auth_item}}',['name'=>'sys-auth','type'=>'2','key'=>'51','description'=>'用户权限','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'4','level'=>'2','sort'=>'2','created_at'=>'1537099581','updated_at'=>'1537099581']);
        $this->insert('{{%sys_auth_item}}',['name'=>'sys-auth-accredit','type'=>'2','key'=>'54','description'=>'权限管理','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'51','level'=>'3','sort'=>'2','created_at'=>'1537099796','updated_at'=>'1537099796']);
        $this->insert('{{%sys_auth_item}}',['name'=>'sys-auth-role','type'=>'2','key'=>'53','description'=>'角色管理','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'51','level'=>'3','sort'=>'1','created_at'=>'1537099766','updated_at'=>'1537099858']);
        $this->insert('{{%sys_auth_item}}',['name'=>'sys-auth-rule','type'=>'2','key'=>'55','description'=>'规则管理','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'51','level'=>'3','sort'=>'3','created_at'=>'1537099831','updated_at'=>'1537099831']);
        $this->insert('{{%sys_auth_item}}',['name'=>'sys-backups','type'=>'2','key'=>'59','description'=>'备份/还原','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'56','level'=>'3','sort'=>'2','created_at'=>'1537100154','updated_at'=>'1537100154']);
        $this->insert('{{%sys_auth_item}}',['name'=>'sys-config-cate','type'=>'2','key'=>'29','description'=>'配置分类','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'25','level'=>'4','sort'=>'4','created_at'=>'1536814780','updated_at'=>'1536814780']);
        $this->insert('{{%sys_auth_item}}',['name'=>'sys-function','type'=>'2','key'=>'20','description'=>'系统功能','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'4','level'=>'2','sort'=>'1','created_at'=>'1536814435','updated_at'=>'1536814435']);
        $this->insert('{{%sys_auth_item}}',['name'=>'sys-log','type'=>'2','key'=>'58','description'=>'系统日志','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'56','level'=>'3','sort'=>'1','created_at'=>'1537100042','updated_at'=>'1537100042']);
        $this->insert('{{%sys_auth_item}}',['name'=>'sys-manager','type'=>'2','key'=>'52','description'=>'后台用户','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'51','level'=>'3','sort'=>'0','created_at'=>'1537099723','updated_at'=>'1537099723']);
        $this->insert('{{%sys_auth_item}}',['name'=>'sys-menu','type'=>'2','key'=>'41','description'=>'后台菜单','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'20','level'=>'3','sort'=>'2','created_at'=>'1537098706','updated_at'=>'1537098706']);
        $this->insert('{{%sys_auth_item}}',['name'=>'sys-menu-cate','type'=>'2','key'=>'45','description'=>'菜单分类','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'41','level'=>'4','sort'=>'4','created_at'=>'1537098861','updated_at'=>'1537099399']);
        $this->insert('{{%sys_auth_item}}',['name'=>'sys-tool','type'=>'2','key'=>'56','description'=>'系统工具','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'4','level'=>'2','sort'=>'3','created_at'=>'1537099952','updated_at'=>'1537099952']);
        $this->insert('{{%sys_auth_item}}',['name'=>'sys/config','type'=>'2','key'=>'25','description'=>'配置管理','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'20','level'=>'3','sort'=>'1','created_at'=>'1536814654','updated_at'=>'1536814667']);
        $this->insert('{{%sys_auth_item}}',['name'=>'wecaht','type'=>'2','key'=>'3','description'=>'微信公众号','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'0','level'=>'1','sort'=>'2','created_at'=>'1536813457','updated_at'=>'1536813457']);
        $this->insert('{{%sys_auth_item}}',['name'=>'测试','type'=>'1','key'=>'61','description'=>'admin|添加了|测试|角色','rule_name'=>NULL,'data'=>NULL,'parent_key'=>'0','level'=>'1','sort'=>'0','created_at'=>'1538202746','updated_at'=>'1538202746']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%sys_auth_item}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}


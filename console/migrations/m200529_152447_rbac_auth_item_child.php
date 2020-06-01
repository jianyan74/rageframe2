<?php

use yii\db\Migration;

class m200529_152447_rbac_auth_item_child extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%rbac_auth_item_child}}', [
            'role_id' => "int(11) unsigned NOT NULL DEFAULT '0' COMMENT '角色id'",
            'item_id' => "int(10) unsigned NOT NULL DEFAULT '0' COMMENT '权限id'",
            'name' => "varchar(64) NOT NULL DEFAULT '' COMMENT '别名'",
            'app_id' => "varchar(20) NOT NULL DEFAULT '' COMMENT '类别'",
            'is_addon' => "tinyint(1) unsigned NULL DEFAULT '0' COMMENT '是否插件'",
            'addons_name' => "varchar(100) NULL DEFAULT '' COMMENT '插件名称'",
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='公用_授权角色权限表'");
        
        /* 索引设置 */
        $this->createIndex('role_id','{{%rbac_auth_item_child}}','role_id',0);
        $this->createIndex('item_id','{{%rbac_auth_item_child}}','item_id',0);
        
        
        /* 表数据 */
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'1','name'=>'base','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'2','name'=>'/base/member/personal','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'6','name'=>'/notify/announce','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'11','name'=>'indexMember','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'12','name'=>'indexMemberInfo','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'13','name'=>'/member/member/recharge','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'20','name'=>'/member/address/index','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'25','name'=>'/member/auth/index','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'30','name'=>'/member/credits-log/index','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'34','name'=>'/member/recharge-config/index','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'42','name'=>'commonAddons','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'43','name'=>'/common/addons/index','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'52','name'=>'/common/config/index','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'57','name'=>'/common/config-cate/index','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'62','name'=>'/common/menu/index','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'67','name'=>'/common/menu-cate/index','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'72','name'=>'/base/notify-announce/index','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'76','name'=>'/base/notify-message/index','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'80','name'=>'backendMember','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'81','name'=>'/base/member/index','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'87','name'=>'/base/auth-item/index','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'92','name'=>'/base/auth-role/index','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'97','name'=>'/oauth2/client/index','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'102','name'=>'/common/system/info','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'104','name'=>'/common/attachment/index','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'108','name'=>'/common/action-log/index','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'116','name'=>'/common/ip-blacklist/index','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'121','name'=>'/common/action-behavior/index','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'3','name'=>'/base/member/up-password','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'7','name'=>'/notify/announce-view','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'10','name'=>'cate:1','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'14','name'=>'/member/member/index','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'21','name'=>'/member/address/ajax-edit','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'24','name'=>'indexMemberAuth','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'26','name'=>'/member/auth/edit','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'29','name'=>'memberCreditsLog','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'31','name'=>'/member/credits-log/integral','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'35','name'=>'/member/recharge-config/edit','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'41','name'=>'commonFunction','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'44','name'=>'/common/addons/local','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'51','name'=>'commonConfig','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'53','name'=>'/common/config/ajax-edit','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'58','name'=>'/common/config-cate/ajax-edit','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'63','name'=>'/common/menu/ajax-edit','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'68','name'=>'/common/menu-cate/ajax-edit','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'73','name'=>'/base/notify-announce/edit','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'77','name'=>'/base/notify-message/ajax-edit','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'82','name'=>'/base/member/ajax-edit','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'86','name'=>'baseAuthItem','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'88','name'=>'/base/auth-item/ajax-edit','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'93','name'=>'/base/auth-role/edit','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'98','name'=>'/oauth2/client/destroy','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'103','name'=>'commonAttachment','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'105','name'=>'/common/attachment/destroy','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'109','name'=>'/common/action-log/view','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'117','name'=>'/common/ip-blacklist/destroy','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'122','name'=>'/common/action-behavior/ajax-edit','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'4','name'=>'/main/clear-cache','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'8','name'=>'/notify/message','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'15','name'=>'/member/member/edit','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'22','name'=>'/member/address/destroy','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'27','name'=>'/member/auth/destroy','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'32','name'=>'/member/credits-log/money','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'33','name'=>'memberRechargeConfig','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'36','name'=>'/member/recharge-config/delete','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'38','name'=>'cate:2','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'45','name'=>'/common/addons/install','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'54','name'=>'/common/config/delete','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'59','name'=>'/common/config-cate/delete','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'61','name'=>'commonMenu','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'64','name'=>'/common/menu/delete','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'69','name'=>'/common/menu-cate/delete','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'74','name'=>'/base/notify-announce/destroy','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'78','name'=>'/base/notify-message/destroy','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'79','name'=>'backendMemberAuth','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'83','name'=>'/base/member/destroy','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'89','name'=>'/base/auth-item/delete','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'91','name'=>'basenAuthRole','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'94','name'=>'/base/auth-role/delete','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'99','name'=>'/oauth2/client/ajax-update','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'106','name'=>'/common/attachment/ajax-update','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'107','name'=>'commonLog','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'110','name'=>'/common/log/index','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'118','name'=>'/common/ip-blacklist/ajax-update','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'123','name'=>'/common/action-behavior/delete','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'5','name'=>'baseAnnounce','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'9','name'=>'/notify/remind','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'16','name'=>'/member/member/ajax-edit','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'23','name'=>'/member/address/ajax-update','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'28','name'=>'/member/auth/ajax-update','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'37','name'=>'/member/recharge-config/ajax-update','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'46','name'=>'/common/addons/un-install','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'55','name'=>'/common/config/ajax-update','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'60','name'=>'/common/config-cate/ajax-update','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'65','name'=>'/common/menu/ajax-update','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'70','name'=>'/common/menu-cate/ajax-update','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'71','name'=>'commonNotifyAnnounce','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'84','name'=>'/base/member/ajax-update','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'90','name'=>'/base/auth-item/ajax-update','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'95','name'=>'/base/auth-role/ajax-update','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'96','name'=>'oauth2Client','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'100','name'=>'/oauth2/client/ajax-edit','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'111','name'=>'/common/log/view','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'115','name'=>'commonIpBlacklist','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'119','name'=>'/common/ip-blacklist/ajax-edit','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'124','name'=>'/common/action-behavior/ajax-update','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'125','name'=>'cate:3','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'17','name'=>'/member/member/destroy','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'47','name'=>'/common/addons/create','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'56','name'=>'commonConfigCate','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'66','name'=>'commonMenuCate','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'75','name'=>'commonNotifyMessage','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'85','name'=>'/base/member/edit','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'101','name'=>'commonTool','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'112','name'=>'/common/pay-log/index','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'120','name'=>'commonActionBehavior','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'18','name'=>'/member/member/ajax-update','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'48','name'=>'/common/addons/ajax-update','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'113','name'=>'/common/pay-log/view','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'19','name'=>'indexMemberAddress','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'49','name'=>'/common/addons/ajax-edit','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'114','name'=>'/common/sms-log/index','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        $this->insert('{{%rbac_auth_item_child}}',['role_id'=>'1','item_id'=>'50','name'=>'/common/addons/upgrade','app_id'=>'backend','is_addon'=>'0','addons_name'=>'']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%rbac_auth_item_child}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}


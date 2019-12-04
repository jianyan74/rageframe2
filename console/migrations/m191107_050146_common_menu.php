<?php

use yii\db\Migration;

class m191107_050146_common_menu extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%common_menu}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'title' => "varchar(50) NOT NULL DEFAULT '' COMMENT '标题'",
            'app_id' => "varchar(20) NOT NULL DEFAULT '' COMMENT '应用'",
            'type' => "varchar(20) NOT NULL DEFAULT '' COMMENT '类别'",
            'addons_name' => "varchar(100) NOT NULL DEFAULT '' COMMENT '插件名称'",
            'cate_id' => "tinyint(5) unsigned NULL DEFAULT '0' COMMENT '分类id'",
            'pid' => "int(50) unsigned NULL DEFAULT '0' COMMENT '上级id'",
            'url' => "varchar(50) NULL DEFAULT '' COMMENT '路由'",
            'icon' => "varchar(50) NULL DEFAULT '' COMMENT '样式'",
            'level' => "tinyint(1) unsigned NULL DEFAULT '1' COMMENT '级别'",
            'dev' => "tinyint(4) unsigned NULL DEFAULT '0' COMMENT '开发者[0:都可见;开发模式可见]'",
            'sort' => "int(5) NULL DEFAULT '999' COMMENT '排序'",
            'params' => "json NULL COMMENT '参数'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'tree' => "varchar(300) NOT NULL DEFAULT '' COMMENT '树'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '添加时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='系统_菜单导航表'");
        
        /* 索引设置 */
        $this->createIndex('url','{{%common_menu}}','url',0);
        
        
        /* 表数据 */
        $this->insert('{{%common_menu}}',['id'=>'1','title'=>'网站设置','app_id'=>'backend','type'=>'default','addons_name'=>'','cate_id'=>'2','pid'=>'0','url'=>'/common/config/edit-all','icon'=>'fa-cog','level'=>'1','dev'=>'0','sort'=>'0','params'=>'[{"key": "", "value": ""}]','status'=>'1','tree'=>'tr_0 ','created_at'=>'1572328434','updated_at'=>'1572417529']);
        $this->insert('{{%common_menu}}',['id'=>'2','title'=>'用户权限','app_id'=>'backend','type'=>'default','addons_name'=>'','cate_id'=>'2','pid'=>'0','url'=>'backendMemberAuth','icon'=>'fa-user-secret','level'=>'1','dev'=>'0','sort'=>'2','params'=>'[{"key": "", "value": ""}]','status'=>'1','tree'=>'tr_0 ','created_at'=>'1572328496','updated_at'=>'1572843384']);
        $this->insert('{{%common_menu}}',['id'=>'3','title'=>'后台用户','app_id'=>'backend','type'=>'default','addons_name'=>'','cate_id'=>'2','pid'=>'2','url'=>'/base/member/index','icon'=>'','level'=>'2','dev'=>'0','sort'=>'999','params'=>'[{"key": "", "value": ""}]','status'=>'1','tree'=>'tr_0 tr_2 ','created_at'=>'1572328535','updated_at'=>'1572560511']);
        $this->insert('{{%common_menu}}',['id'=>'4','title'=>'角色管理','app_id'=>'backend','type'=>'default','addons_name'=>'','cate_id'=>'2','pid'=>'2','url'=>'/common/auth-role/index','icon'=>'','level'=>'2','dev'=>'0','sort'=>'999','params'=>'[{"key": "", "value": ""}]','status'=>'1','tree'=>'tr_0 tr_2 ','created_at'=>'1572329079','updated_at'=>'1572329079']);
        $this->insert('{{%common_menu}}',['id'=>'5','title'=>'权限管理','app_id'=>'backend','type'=>'default','addons_name'=>'','cate_id'=>'2','pid'=>'2','url'=>'/common/auth-item/index','icon'=>'','level'=>'2','dev'=>'1','sort'=>'999','params'=>'[{"key": "", "value": ""}]','status'=>'1','tree'=>'tr_0 tr_2 ','created_at'=>'1572329162','updated_at'=>'1572560438']);
        $this->insert('{{%common_menu}}',['id'=>'6','title'=>'系统功能','app_id'=>'backend','type'=>'default','addons_name'=>'','cate_id'=>'2','pid'=>'0','url'=>'commonFunction','icon'=>'fa-list-ul','level'=>'1','dev'=>'0','sort'=>'1','params'=>'[{"key": "", "value": ""}]','status'=>'1','tree'=>'tr_0 ','created_at'=>'1572329735','updated_at'=>'1572843337']);
        $this->insert('{{%common_menu}}',['id'=>'7','title'=>'系统基础','app_id'=>'backend','type'=>'default','addons_name'=>'','cate_id'=>'2','pid'=>'0','url'=>'commonTool','icon'=>'fa-microchip','level'=>'1','dev'=>'0','sort'=>'4','params'=>'[{"key": "", "value": ""}]','status'=>'1','tree'=>'tr_0 ','created_at'=>'1572329902','updated_at'=>'1572330197']);
        $this->insert('{{%common_menu}}',['id'=>'8','title'=>'应用管理','app_id'=>'backend','type'=>'default','addons_name'=>'','cate_id'=>'2','pid'=>'6','url'=>'/common/addons/index','icon'=>'','level'=>'2','dev'=>'0','sort'=>'0','params'=>'[{"key": "", "value": ""}]','status'=>'1','tree'=>'tr_0 tr_6 ','created_at'=>'1572330081','updated_at'=>'1572473852']);
        $this->insert('{{%common_menu}}',['id'=>'9','title'=>'配置管理','app_id'=>'backend','type'=>'default','addons_name'=>'','cate_id'=>'2','pid'=>'6','url'=>'/common/config/index','icon'=>'','level'=>'2','dev'=>'1','sort'=>'2','params'=>'[{"key": "", "value": ""}]','status'=>'1','tree'=>'tr_0 tr_6 ','created_at'=>'1572330103','updated_at'=>'1572560457']);
        $this->insert('{{%common_menu}}',['id'=>'10','title'=>'开放授权','app_id'=>'backend','type'=>'default','addons_name'=>'','cate_id'=>'2','pid'=>'0','url'=>'/oauth2/client/index','icon'=>'fa-square','level'=>'1','dev'=>'0','sort'=>'3','params'=>'[{"key": "", "value": ""}]','status'=>'1','tree'=>'tr_0 ','created_at'=>'1572330249','updated_at'=>'1573021721']);
        $this->insert('{{%common_menu}}',['id'=>'11','title'=>'资源文件','app_id'=>'backend','type'=>'default','addons_name'=>'','cate_id'=>'2','pid'=>'7','url'=>'/common/attachment/index','icon'=>'','level'=>'2','dev'=>'0','sort'=>'2','params'=>'[{"key": "", "value": ""}]','status'=>'1','tree'=>'tr_0 tr_7 ','created_at'=>'1572330586','updated_at'=>'1572330797']);
        $this->insert('{{%common_menu}}',['id'=>'12','title'=>'日志记录','app_id'=>'backend','type'=>'default','addons_name'=>'','cate_id'=>'2','pid'=>'7','url'=>'commonLog','icon'=>'','level'=>'2','dev'=>'0','sort'=>'3','params'=>'[{"key": "", "value": ""}]','status'=>'1','tree'=>'tr_0 tr_7 ','created_at'=>'1572330619','updated_at'=>'1572330799']);
        $this->insert('{{%common_menu}}',['id'=>'13','title'=>'行为日志','app_id'=>'backend','type'=>'default','addons_name'=>'','cate_id'=>'2','pid'=>'12','url'=>'/common/action-log/index','icon'=>'','level'=>'3','dev'=>'0','sort'=>'0','params'=>'[{"key": "", "value": ""}]','status'=>'1','tree'=>'tr_0 tr_7 tr_12 ','created_at'=>'1572330641','updated_at'=>'1572330724']);
        $this->insert('{{%common_menu}}',['id'=>'14','title'=>'短信日志','app_id'=>'backend','type'=>'default','addons_name'=>'','cate_id'=>'2','pid'=>'12','url'=>'/common/sms-log/index','icon'=>'','level'=>'3','dev'=>'0','sort'=>'1','params'=>'[{"key": "", "value": ""}]','status'=>'1','tree'=>'tr_0 tr_7 tr_12 ','created_at'=>'1572330658','updated_at'=>'1572330725']);
        $this->insert('{{%common_menu}}',['id'=>'15','title'=>'支付日志','app_id'=>'backend','type'=>'default','addons_name'=>'','cate_id'=>'2','pid'=>'12','url'=>'/common/pay-log/index','icon'=>'','level'=>'3','dev'=>'0','sort'=>'2','params'=>'[{"key": "", "value": ""}]','status'=>'1','tree'=>'tr_0 tr_7 tr_12 ','created_at'=>'1572330673','updated_at'=>'1572330726']);
        $this->insert('{{%common_menu}}',['id'=>'16','title'=>'全局日志','app_id'=>'backend','type'=>'default','addons_name'=>'','cate_id'=>'2','pid'=>'12','url'=>'/common/log/index','icon'=>'','level'=>'3','dev'=>'0','sort'=>'3','params'=>'[{"key": "", "value": ""}]','status'=>'1','tree'=>'tr_0 tr_7 tr_12 ','created_at'=>'1572330707','updated_at'=>'1572330727']);
        $this->insert('{{%common_menu}}',['id'=>'17','title'=>'IP黑名单','app_id'=>'backend','type'=>'default','addons_name'=>'','cate_id'=>'2','pid'=>'7','url'=>'/common/ip-blacklist/index','icon'=>'','level'=>'2','dev'=>'0','sort'=>'4','params'=>'[{"key": "", "value": ""}]','status'=>'1','tree'=>'tr_0 tr_7 ','created_at'=>'1572330752','updated_at'=>'1572330800']);
        $this->insert('{{%common_menu}}',['id'=>'18','title'=>'行为监控','app_id'=>'backend','type'=>'default','addons_name'=>'','cate_id'=>'2','pid'=>'7','url'=>'/common/action-behavior/index','icon'=>'','level'=>'2','dev'=>'1','sort'=>'5','params'=>'[{"key": "", "value": ""}]','status'=>'1','tree'=>'tr_0 tr_7 ','created_at'=>'1572330768','updated_at'=>'1572560378']);
        $this->insert('{{%common_menu}}',['id'=>'19','title'=>'系统信息','app_id'=>'backend','type'=>'default','addons_name'=>'','cate_id'=>'2','pid'=>'7','url'=>'/common/system/info','icon'=>'','level'=>'2','dev'=>'0','sort'=>'6','params'=>'[{"key": "", "value": ""}]','status'=>'1','tree'=>'tr_0 tr_7 ','created_at'=>'1572330788','updated_at'=>'1572560299']);
        $this->insert('{{%common_menu}}',['id'=>'20','title'=>'会员管理','app_id'=>'backend','type'=>'default','addons_name'=>'','cate_id'=>'1','pid'=>'0','url'=>'indexMember','icon'=>'fa-user','level'=>'1','dev'=>'0','sort'=>'999','params'=>'[{"key": "", "value": ""}]','status'=>'1','tree'=>'tr_0 ','created_at'=>'1572331063','updated_at'=>'1572331063']);
        $this->insert('{{%common_menu}}',['id'=>'21','title'=>'会员信息','app_id'=>'backend','type'=>'default','addons_name'=>'','cate_id'=>'1','pid'=>'20','url'=>'/member/member/index','icon'=>'','level'=>'2','dev'=>'0','sort'=>'999','params'=>'[{"key": "", "value": ""}]','status'=>'1','tree'=>'tr_0 tr_20 ','created_at'=>'1572331081','updated_at'=>'1572331081']);
        $this->insert('{{%common_menu}}',['id'=>'22','title'=>'第三方授权','app_id'=>'backend','type'=>'default','addons_name'=>'','cate_id'=>'1','pid'=>'20','url'=>'/member/auth/index','icon'=>'','level'=>'2','dev'=>'0','sort'=>'999','params'=>'[{"key": "", "value": ""}]','status'=>'1','tree'=>'tr_0 tr_20 ','created_at'=>'1572331105','updated_at'=>'1572331105']);
        $this->insert('{{%common_menu}}',['id'=>'23','title'=>'充值日志','app_id'=>'backend','type'=>'default','addons_name'=>'','cate_id'=>'1','pid'=>'20','url'=>'/member/credits-log/index','icon'=>'','level'=>'2','dev'=>'0','sort'=>'999','params'=>'[{"key": "", "value": ""}]','status'=>'1','tree'=>'tr_0 tr_20 ','created_at'=>'1572331117','updated_at'=>'1572331117']);
        $this->insert('{{%common_menu}}',['id'=>'24','title'=>'菜单管理','app_id'=>'backend','type'=>'default','addons_name'=>'','cate_id'=>'2','pid'=>'6','url'=>'/common/menu/index','icon'=>'','level'=>'2','dev'=>'1','sort'=>'1','params'=>'[{"key": "", "value": ""}]','status'=>'1','tree'=>'tr_0 tr_6 ','created_at'=>'1572408688','updated_at'=>'1572560447']);
        $this->insert('{{%common_menu}}',['id'=>'25','title'=>'用户权限','app_id'=>'backend','type'=>'default','addons_name'=>'','cate_id'=>'4','pid'=>'0','url'=>'baseAuth','icon'=>'fa-user-secret','level'=>'1','dev'=>'0','sort'=>'2','params'=>'[{"key": "", "value": ""}]','status'=>'1','tree'=>'tr_0 ','created_at'=>'1572328496','updated_at'=>'1572329743']);
        $this->insert('{{%common_menu}}',['id'=>'26','title'=>'后台用户','app_id'=>'backend','type'=>'default','addons_name'=>'','cate_id'=>'4','pid'=>'25','url'=>'/base/manager/index','icon'=>'','level'=>'2','dev'=>'0','sort'=>'999','params'=>'[{"key": "", "value": ""}]','status'=>'1','tree'=>'tr_0 tr_25 ','created_at'=>'1572328535','updated_at'=>'1572333002']);
        $this->insert('{{%common_menu}}',['id'=>'27','title'=>'角色管理','app_id'=>'backend','type'=>'default','addons_name'=>'','cate_id'=>'4','pid'=>'25','url'=>'/base/auth-role/index','icon'=>'','level'=>'2','dev'=>'0','sort'=>'999','params'=>'[{"key": "", "value": ""}]','status'=>'1','tree'=>'tr_0 tr_25 ','created_at'=>'1572329079','updated_at'=>'1572329079']);
        $this->insert('{{%common_menu}}',['id'=>'28','title'=>'权限管理','app_id'=>'backend','type'=>'default','addons_name'=>'','cate_id'=>'4','pid'=>'25','url'=>'/base/auth-item/index','icon'=>'','level'=>'2','dev'=>'0','sort'=>'999','params'=>'[{"key": "", "value": ""}]','status'=>'1','tree'=>'tr_0 tr_25 ','created_at'=>'1572329162','updated_at'=>'1572329162']);
        $this->insert('{{%common_menu}}',['id'=>'29','title'=>'公告管理','app_id'=>'backend','type'=>'default','addons_name'=>'','cate_id'=>'2','pid'=>'6','url'=>'/base/notify-announce/index','icon'=>'','level'=>'2','dev'=>'0','sort'=>'3','params'=>'[{"key": "", "value": ""}]','status'=>'1','tree'=>'tr_0 tr_6 ','created_at'=>'1572473709','updated_at'=>'1572473862']);
        $this->insert('{{%common_menu}}',['id'=>'30','title'=>'私信管理','app_id'=>'backend','type'=>'default','addons_name'=>'','cate_id'=>'2','pid'=>'6','url'=>'/base/notify-message/index','icon'=>'','level'=>'2','dev'=>'0','sort'=>'4','params'=>'[{"key": "", "value": ""}]','status'=>'1','tree'=>'tr_0 tr_6 ','created_at'=>'1572473732','updated_at'=>'1572473863']);
        $this->insert('{{%common_menu}}',['id'=>'31','title'=>'提醒设置','app_id'=>'backend','type'=>'default','addons_name'=>'','cate_id'=>'2','pid'=>'6','url'=>'/base/notify-subscription-config/index','icon'=>'','level'=>'2','dev'=>'0','sort'=>'5','params'=>'[{"key": "", "value": ""}]','status'=>'1','tree'=>'tr_0 tr_6 ','created_at'=>'1572473760','updated_at'=>'1572473864']);
        $this->insert('{{%common_menu}}',['id'=>'168','title'=>'网站设置','app_id'=>'merchant','type'=>'default','addons_name'=>'','cate_id'=>'4','pid'=>'0','url'=>'/common/config/edit-all','icon'=>'fa-cog','level'=>'1','dev'=>'0','sort'=>'0','params'=>'[{"key": "", "value": ""}]','status'=>'1','tree'=>'tr_0 ','created_at'=>'1572587305','updated_at'=>'1572587308']);
        $this->insert('{{%common_menu}}',['id'=>'169','title'=>'用户权限','app_id'=>'merchant','type'=>'default','addons_name'=>'','cate_id'=>'4','pid'=>'0','url'=>'merchantMemberAuth','icon'=>'fa-user-secret','level'=>'1','dev'=>'0','sort'=>'999','params'=>'[{"key": "", "value": ""}]','status'=>'1','tree'=>'tr_0 ','created_at'=>'1572587403','updated_at'=>'1572849864']);
        $this->insert('{{%common_menu}}',['id'=>'170','title'=>'后台用户','app_id'=>'merchant','type'=>'default','addons_name'=>'','cate_id'=>'4','pid'=>'169','url'=>'/base/member/index','icon'=>'','level'=>'2','dev'=>'0','sort'=>'999','params'=>'[{"key": "", "value": ""}]','status'=>'1','tree'=>'tr_0 tr_169 ','created_at'=>'1572587426','updated_at'=>'1572587426']);
        $this->insert('{{%common_menu}}',['id'=>'171','title'=>'角色管理','app_id'=>'merchant','type'=>'default','addons_name'=>'','cate_id'=>'4','pid'=>'169','url'=>'/common/auth-role/index','icon'=>'','level'=>'2','dev'=>'0','sort'=>'999','params'=>'[{"key": "", "value": ""}]','status'=>'1','tree'=>'tr_0 tr_169 ','created_at'=>'1572587445','updated_at'=>'1572587445']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%common_menu}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}


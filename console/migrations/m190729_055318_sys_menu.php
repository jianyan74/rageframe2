<?php

use yii\db\Migration;

class m190729_055318_sys_menu extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%sys_menu}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'title' => "varchar(50) NOT NULL DEFAULT '' COMMENT '标题'",
            'cate_id' => "tinyint(5) unsigned NULL DEFAULT '0' COMMENT '分类id'",
            'pid' => "int(50) unsigned NULL DEFAULT '0' COMMENT '上级id'",
            'url' => "varchar(50) NULL DEFAULT '' COMMENT '路由'",
            'icon' => "varchar(20) NULL DEFAULT '' COMMENT '样式'",
            'level' => "tinyint(1) unsigned NULL DEFAULT '1' COMMENT '级别'",
            'dev' => "tinyint(4) unsigned NULL DEFAULT '0' COMMENT '开发者[0:都可见;开发模式可见]'",
            'sort' => "int(5) NULL DEFAULT '0' COMMENT '排序'",
            'params' => "json NULL COMMENT '参数'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'tree' => "varchar(300) NOT NULL DEFAULT '' COMMENT '树'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '添加时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='系统_菜单导航表'");
        
        /* 索引设置 */
        $this->createIndex('url','{{%sys_menu}}','url',0);
        
        
        /* 表数据 */
        $this->insert('{{%sys_menu}}',['id'=>'1','title'=>'会员管理','cate_id'=>'1','pid'=>'0','url'=>'indexMember','icon'=>'fa-user','level'=>'1','dev'=>'0','sort'=>'0','params'=>'[{"key": "", "value": ""}]','status'=>'1','tree'=>'tr_0','created_at'=>'1553833327','updated_at'=>'1563167436']);
        $this->insert('{{%sys_menu}}',['id'=>'2','title'=>'会员信息','cate_id'=>'1','pid'=>'1','url'=>'/member/member/index','icon'=>'','level'=>'2','dev'=>'0','sort'=>'0','params'=>'[]','status'=>'1','tree'=>'tr_0 tr_1','created_at'=>'1553833356','updated_at'=>'1563167442']);
        $this->insert('{{%sys_menu}}',['id'=>'3','title'=>'第三方用户','cate_id'=>'1','pid'=>'1','url'=>'/member/auth/index','icon'=>'','level'=>'2','dev'=>'0','sort'=>'1','params'=>'[]','status'=>'1','tree'=>'tr_0 tr_1','created_at'=>'1553833380','updated_at'=>'1553833380']);
        $this->insert('{{%sys_menu}}',['id'=>'4','title'=>'网站设置','cate_id'=>'3','pid'=>'0','url'=>'/common/config/edit-all','icon'=>'fa-cog','level'=>'1','dev'=>'0','sort'=>'0','params'=>'[]','status'=>'1','tree'=>'tr_0','created_at'=>'1553833920','updated_at'=>'1553833920']);
        $this->insert('{{%sys_menu}}',['id'=>'5','title'=>'系统工具','cate_id'=>'3','pid'=>'0','url'=>'sysTool','icon'=>'fa-microchip','level'=>'1','dev'=>'0','sort'=>'5','params'=>'[]','status'=>'1','tree'=>'tr_0','created_at'=>'1553834048','updated_at'=>'1553834146']);
        $this->insert('{{%sys_menu}}',['id'=>'6','title'=>'系统信息','cate_id'=>'3','pid'=>'5','url'=>'/sys/system/info','icon'=>'','level'=>'2','dev'=>'0','sort'=>'0','params'=>'[]','status'=>'1','tree'=>'tr_0 tr_5','created_at'=>'1553834086','updated_at'=>'1553834086']);
        $this->insert('{{%sys_menu}}',['id'=>'7','title'=>'资源文件','cate_id'=>'3','pid'=>'5','url'=>'/common/attachment/index','icon'=>'','level'=>'2','dev'=>'0','sort'=>'1','params'=>'[]','status'=>'1','tree'=>'tr_0 tr_5','created_at'=>'1553834136','updated_at'=>'1553834136']);
        $this->insert('{{%sys_menu}}',['id'=>'8','title'=>'查看日志','cate_id'=>'3','pid'=>'5','url'=>'commonLog','icon'=>'','level'=>'2','dev'=>'0','sort'=>'2','params'=>'[]','status'=>'1','tree'=>'tr_0 tr_5','created_at'=>'1553834262','updated_at'=>'1553834262']);
        $this->insert('{{%sys_menu}}',['id'=>'9','title'=>'行为日志','cate_id'=>'3','pid'=>'8','url'=>'/common/action-log/index','icon'=>'','level'=>'3','dev'=>'0','sort'=>'0','params'=>'[{"key": "", "value": ""}]','status'=>'1','tree'=>'tr_0 tr_5 tr_8','created_at'=>'1553834321','updated_at'=>'1564213776']);
        $this->insert('{{%sys_menu}}',['id'=>'10','title'=>'全局日志','cate_id'=>'3','pid'=>'8','url'=>'/common/log/index','icon'=>'','level'=>'3','dev'=>'0','sort'=>'1','params'=>'[{"key": "", "value": ""}]','status'=>'1','tree'=>'tr_0 tr_5 tr_8','created_at'=>'1553834345','updated_at'=>'1563517051']);
        $this->insert('{{%sys_menu}}',['id'=>'11','title'=>'支付日志','cate_id'=>'3','pid'=>'8','url'=>'/common/pay-log/index','icon'=>'','level'=>'3','dev'=>'0','sort'=>'2','params'=>'[]','status'=>'1','tree'=>'tr_0 tr_5 tr_8','created_at'=>'1553834364','updated_at'=>'1553834364']);
        $this->insert('{{%sys_menu}}',['id'=>'12','title'=>'系统探针','cate_id'=>'3','pid'=>'5','url'=>'/sys/system/probe','icon'=>'','level'=>'2','dev'=>'0','sort'=>'99','params'=>'[]','status'=>'1','tree'=>'tr_0 tr_5','created_at'=>'1553834442','updated_at'=>'1553834456']);
        $this->insert('{{%sys_menu}}',['id'=>'13','title'=>'系统功能','cate_id'=>'3','pid'=>'0','url'=>'sysFunction','icon'=>'fa-suitcase','level'=>'1','dev'=>'0','sort'=>'1','params'=>'[]','status'=>'1','tree'=>'tr_0','created_at'=>'1553840681','updated_at'=>'1553840681']);
        $this->insert('{{%sys_menu}}',['id'=>'14','title'=>'应用管理','cate_id'=>'3','pid'=>'13','url'=>'/common/addons/index','icon'=>'','level'=>'2','dev'=>'0','sort'=>'0','params'=>'[]','status'=>'1','tree'=>'tr_0 tr_13','created_at'=>'1553840708','updated_at'=>'1554095648']);
        $this->insert('{{%sys_menu}}',['id'=>'15','title'=>'配置管理','cate_id'=>'3','pid'=>'13','url'=>'/common/config/index','icon'=>'','level'=>'2','dev'=>'1','sort'=>'1','params'=>'[]','status'=>'1','tree'=>'tr_0 tr_13','created_at'=>'1553840731','updated_at'=>'1553911799']);
        $this->insert('{{%sys_menu}}',['id'=>'16','title'=>'后台菜单','cate_id'=>'3','pid'=>'13','url'=>'/sys/menu/index','icon'=>'','level'=>'2','dev'=>'1','sort'=>'0','params'=>'[]','status'=>'1','tree'=>'tr_0 tr_13','created_at'=>'1553840749','updated_at'=>'1553842761']);
        $this->insert('{{%sys_menu}}',['id'=>'17','title'=>'备份还原','cate_id'=>'3','pid'=>'5','url'=>'/sys/data-base/backups','icon'=>'','level'=>'2','dev'=>'0','sort'=>'3','params'=>'[]','status'=>'1','tree'=>'tr_0 tr_5','created_at'=>'1553840806','updated_at'=>'1553913042']);
        $this->insert('{{%sys_menu}}',['id'=>'18','title'=>'公告管理','cate_id'=>'3','pid'=>'13','url'=>'/sys/notify-announce/index','icon'=>'','level'=>'2','dev'=>'0','sort'=>'3','params'=>'[]','status'=>'1','tree'=>'tr_0 tr_13','created_at'=>'1553840852','updated_at'=>'1553842606']);
        $this->insert('{{%sys_menu}}',['id'=>'19','title'=>'私信管理','cate_id'=>'3','pid'=>'13','url'=>'/sys/notify-message/index','icon'=>'','level'=>'2','dev'=>'0','sort'=>'4','params'=>'[]','status'=>'1','tree'=>'tr_0 tr_13','created_at'=>'1553840879','updated_at'=>'1553842621']);
        $this->insert('{{%sys_menu}}',['id'=>'20','title'=>'用户权限','cate_id'=>'3','pid'=>'0','url'=>'sysManagerAuth','icon'=>'fa-user-secret','level'=>'1','dev'=>'0','sort'=>'2','params'=>'[]','status'=>'1','tree'=>'tr_0','created_at'=>'1553841004','updated_at'=>'1555117486']);
        $this->insert('{{%sys_menu}}',['id'=>'21','title'=>'后台用户','cate_id'=>'3','pid'=>'20','url'=>'/sys/manager/index','icon'=>'','level'=>'2','dev'=>'0','sort'=>'0','params'=>'[]','status'=>'1','tree'=>'tr_0 tr_20','created_at'=>'1553841026','updated_at'=>'1553841026']);
        $this->insert('{{%sys_menu}}',['id'=>'22','title'=>'角色管理','cate_id'=>'3','pid'=>'20','url'=>'/common/auth-role/index','icon'=>'','level'=>'2','dev'=>'0','sort'=>'1','params'=>'[]','status'=>'1','tree'=>'tr_0 tr_20','created_at'=>'1553841038','updated_at'=>'1554875554']);
        $this->insert('{{%sys_menu}}',['id'=>'23','title'=>'权限管理','cate_id'=>'3','pid'=>'20','url'=>'/common/auth-item/index','icon'=>'','level'=>'2','dev'=>'1','sort'=>'2','params'=>'[]','status'=>'1','tree'=>'tr_0 tr_20','created_at'=>'1553841055','updated_at'=>'1554875540']);
        $this->insert('{{%sys_menu}}',['id'=>'24','title'=>'短信日志','cate_id'=>'3','pid'=>'8','url'=>'/common/sms-log/index','icon'=>'','level'=>'3','dev'=>'0','sort'=>'3','params'=>'[]','status'=>'1','tree'=>'tr_0 tr_5 tr_8','created_at'=>'1553841163','updated_at'=>'1553841163']);
        $this->insert('{{%sys_menu}}',['id'=>'25','title'=>'充值日志','cate_id'=>'1','pid'=>'1','url'=>'/member/credits-log/index','icon'=>'','level'=>'2','dev'=>'0','sort'=>'2','params'=>'[]','status'=>'1','tree'=>'tr_0 tr_1','created_at'=>'1553923288','updated_at'=>'1553923288']);
        $this->insert('{{%sys_menu}}',['id'=>'27','title'=>'参数配置','cate_id'=>'2','pid'=>'0','url'=>'/wechat/setting/history-stat','icon'=>'fa-cog','level'=>'1','dev'=>'0','sort'=>'9','params'=>'[]','status'=>'1','tree'=>'tr_0','created_at'=>'1555564951','updated_at'=>'1555896025']);
        $this->insert('{{%sys_menu}}',['id'=>'29','title'=>'粉丝管理','cate_id'=>'2','pid'=>'0','url'=>'wechatFans','icon'=>'fa-heart','level'=>'1','dev'=>'0','sort'=>'1','params'=>'[]','status'=>'1','tree'=>'tr_0','created_at'=>'1555895960','updated_at'=>'1555896013']);
        $this->insert('{{%sys_menu}}',['id'=>'30','title'=>'增强功能','cate_id'=>'2','pid'=>'0','url'=>'wechatFunction','icon'=>'fa-superpowers','level'=>'1','dev'=>'0','sort'=>'0','params'=>'[]','status'=>'1','tree'=>'tr_0','created_at'=>'1555896000','updated_at'=>'1555896000']);
        $this->insert('{{%sys_menu}}',['id'=>'31','title'=>'粉丝列表','cate_id'=>'2','pid'=>'29','url'=>'/wechat/fans/index','icon'=>'','level'=>'2','dev'=>'0','sort'=>'0','params'=>'[]','status'=>'1','tree'=>'tr_0 tr_29','created_at'=>'1555896042','updated_at'=>'1557278023']);
        $this->insert('{{%sys_menu}}',['id'=>'32','title'=>'粉丝标签','cate_id'=>'2','pid'=>'29','url'=>'/wechat/fans-tags/index','icon'=>'','level'=>'2','dev'=>'0','sort'=>'1','params'=>'[]','status'=>'1','tree'=>'tr_0 tr_29','created_at'=>'1555896057','updated_at'=>'1555896062']);
        $this->insert('{{%sys_menu}}',['id'=>'33','title'=>'二维码/转化链接','cate_id'=>'2','pid'=>'30','url'=>'/wechat/qrcode/index','icon'=>'','level'=>'2','dev'=>'0','sort'=>'2','params'=>'[]','status'=>'1','tree'=>'tr_0 tr_30','created_at'=>'1555896257','updated_at'=>'1555896257']);
        $this->insert('{{%sys_menu}}',['id'=>'34','title'=>'自定义菜单','cate_id'=>'2','pid'=>'30','url'=>'/wechat/menu/index','icon'=>'','level'=>'2','dev'=>'0','sort'=>'1','params'=>'[]','status'=>'1','tree'=>'tr_0 tr_30','created_at'=>'1555896275','updated_at'=>'1555896283']);
        $this->insert('{{%sys_menu}}',['id'=>'35','title'=>'素材库','cate_id'=>'2','pid'=>'0','url'=>'/wechat/attachment/index','icon'=>'fa-file','level'=>'1','dev'=>'0','sort'=>'2','params'=>'[]','status'=>'1','tree'=>'tr_0','created_at'=>'1555896321','updated_at'=>'1555896349']);
        $this->insert('{{%sys_menu}}',['id'=>'36','title'=>'自动回复','cate_id'=>'2','pid'=>'30','url'=>'/wechat/rule/index','icon'=>'','level'=>'2','dev'=>'0','sort'=>'0','params'=>'[]','status'=>'1','tree'=>'tr_0 tr_30','created_at'=>'1555901442','updated_at'=>'1555901442']);
        $this->insert('{{%sys_menu}}',['id'=>'37','title'=>'历史消息','cate_id'=>'2','pid'=>'0','url'=>'/wechat/msg-history/index','icon'=>'fa-comments','level'=>'1','dev'=>'0','sort'=>'3','params'=>'[]','status'=>'1','tree'=>'tr_0','created_at'=>'1555901507','updated_at'=>'1555901927']);
        $this->insert('{{%sys_menu}}',['id'=>'38','title'=>'定时群发','cate_id'=>'2','pid'=>'0','url'=>'/wechat/mass-record/index','icon'=>'fa-send','level'=>'1','dev'=>'0','sort'=>'4','params'=>'[]','status'=>'1','tree'=>'tr_0','created_at'=>'1555901532','updated_at'=>'1555901589']);
        $this->insert('{{%sys_menu}}',['id'=>'39','title'=>'数据统计','cate_id'=>'2','pid'=>'0','url'=>'wechatDataStatistics','icon'=>'fa-pie-chart','level'=>'1','dev'=>'0','sort'=>'5','params'=>'[]','status'=>'1','tree'=>'tr_0','created_at'=>'1555901570','updated_at'=>'1555901570']);
        $this->insert('{{%sys_menu}}',['id'=>'40','title'=>'粉丝关注统计','cate_id'=>'2','pid'=>'39','url'=>'/wechat/stat/fans-follow','icon'=>'','level'=>'2','dev'=>'0','sort'=>'0','params'=>'[]','status'=>'1','tree'=>'tr_0 tr_39','created_at'=>'1555901618','updated_at'=>'1555901618']);
        $this->insert('{{%sys_menu}}',['id'=>'41','title'=>'回复规则使用量','cate_id'=>'2','pid'=>'39','url'=>'/wechat/stat/rule','icon'=>'','level'=>'2','dev'=>'0','sort'=>'0','params'=>'[]','status'=>'1','tree'=>'tr_0 tr_39','created_at'=>'1555901631','updated_at'=>'1555901631']);
        $this->insert('{{%sys_menu}}',['id'=>'42','title'=>'关键字命中规则','cate_id'=>'2','pid'=>'39','url'=>'/wechat/stat/rule-keyword','icon'=>'','level'=>'2','dev'=>'0','sort'=>'0','params'=>'[]','status'=>'1','tree'=>'tr_0 tr_39','created_at'=>'1555901648','updated_at'=>'1555901648']);
        $this->insert('{{%sys_menu}}',['id'=>'43','title'=>'客户授权','cate_id'=>'3','pid'=>'0','url'=>'/oauth2/client/index','icon'=>'fa-square','level'=>'1','dev'=>'0','sort'=>'3','params'=>'[]','status'=>'1','tree'=>'tr_0','created_at'=>'1559641132','updated_at'=>'1563419198']);
        $this->insert('{{%sys_menu}}',['id'=>'45','title'=>'IP黑名单','cate_id'=>'3','pid'=>'5','url'=>'/common/ip-blacklist/index','icon'=>'','level'=>'2','dev'=>'0','sort'=>'4','params'=>'[]','status'=>'1','tree'=>'tr_0 tr_5','created_at'=>'1562111649','updated_at'=>'1562111657']);
        $this->insert('{{%sys_menu}}',['id'=>'46','title'=>'行为监控','cate_id'=>'3','pid'=>'5','url'=>'/common/action-behavior/index','icon'=>'','level'=>'2','dev'=>'1','sort'=>'5','params'=>'[{"key": "", "value": ""}]','status'=>'1','tree'=>'tr_0 tr_5','created_at'=>'1564213750','updated_at'=>'1564214147']);
        
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


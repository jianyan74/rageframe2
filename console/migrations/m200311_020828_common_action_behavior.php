<?php

use yii\db\Migration;

class m200311_020828_common_action_behavior extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%common_action_behavior}}', [
            'id' => "int(10) NOT NULL AUTO_INCREMENT COMMENT '主键'",
            'app_id' => "varchar(50) NULL DEFAULT '' COMMENT '应用id'",
            'url' => "varchar(200) NULL DEFAULT '' COMMENT '提交url'",
            'method' => "varchar(20) NULL DEFAULT '' COMMENT '提交类型 *为不限'",
            'behavior' => "varchar(50) NULL DEFAULT '' COMMENT '行为类别'",
            'action' => "tinyint(4) unsigned NULL DEFAULT '1' COMMENT '前置/后置'",
            'level' => "varchar(20) NULL DEFAULT '' COMMENT '级别'",
            'is_record_post' => "tinyint(4) unsigned NULL DEFAULT '1' COMMENT '是否记录post[0;否;1是]'",
            'is_ajax' => "tinyint(4) unsigned NULL DEFAULT '2' COMMENT '是否ajax请求[1;否;2是;3不限]'",
            'remark' => "varchar(100) NULL DEFAULT '' COMMENT '备注'",
            'addons_name' => "varchar(100) NOT NULL DEFAULT '' COMMENT '插件名称'",
            'is_addon' => "tinyint(1) unsigned NULL DEFAULT '0' COMMENT '是否插件'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='系统_行为表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        $this->insert('{{%common_action_behavior}}',['id'=>'1','app_id'=>'backend','url'=>'site/logout','method'=>'*','behavior'=>'logout','action'=>'1','level'=>'info','is_record_post'=>'1','is_ajax'=>'2','remark'=>'退出登录','addons_name'=>'','is_addon'=>'0','status'=>'1','created_at'=>'1564215882','updated_at'=>'1565316801']);
        $this->insert('{{%common_action_behavior}}',['id'=>'2','app_id'=>'backend','url'=>'base/manager/ajax-edit','method'=>'POST','behavior'=>'updateAccountPassword','action'=>'1','level'=>'info','is_record_post'=>'0','is_ajax'=>'0','remark'=>'创建/修改管理员账号密码','addons_name'=>'','is_addon'=>'0','status'=>'1','created_at'=>'1564221741','updated_at'=>'1579245124']);
        $this->insert('{{%common_action_behavior}}',['id'=>'3','app_id'=>'backend','url'=>'member/member/ajax-edit','method'=>'POST','behavior'=>'updateMemberAccountPassword','action'=>'1','level'=>'info','is_record_post'=>'0','is_ajax'=>'0','remark'=>'创建/修改用户账号密码','addons_name'=>'','is_addon'=>'0','status'=>'1','created_at'=>'1564221792','updated_at'=>'1565316793']);
        $this->insert('{{%common_action_behavior}}',['id'=>'4','app_id'=>'backend','url'=>'base/manager/destroy','method'=>'*','behavior'=>'deleteManager','action'=>'1','level'=>'warning','is_record_post'=>'1','is_ajax'=>'2','remark'=>'删除管理员','addons_name'=>'','is_addon'=>'0','status'=>'1','created_at'=>'1564223374','updated_at'=>'1579245113']);
        $this->insert('{{%common_action_behavior}}',['id'=>'5','app_id'=>'backend','url'=>'base/manager/up-password','method'=>'POST','behavior'=>'updatePassword','action'=>'1','level'=>'info','is_record_post'=>'0','is_ajax'=>'0','remark'=>'修改管理员密码','addons_name'=>'','is_addon'=>'0','status'=>'1','created_at'=>'1564223463','updated_at'=>'1579245105']);
        $this->insert('{{%common_action_behavior}}',['id'=>'6','app_id'=>'backend','url'=>'member/member/recharge','method'=>'POST','behavior'=>'rechargeMemberMoney','action'=>'1','level'=>'info','is_record_post'=>'1','is_ajax'=>'0','remark'=>'充值/减少会员积分余额','addons_name'=>'','is_addon'=>'0','status'=>'1','created_at'=>'1564224361','updated_at'=>'1565316771']);
        $this->insert('{{%common_action_behavior}}',['id'=>'7','app_id'=>'backend','url'=>'member/member/destroy','method'=>'*','behavior'=>'memberDelete','action'=>'1','level'=>'warning','is_record_post'=>'1','is_ajax'=>'2','remark'=>'删除会员信息','addons_name'=>'','is_addon'=>'0','status'=>'1','created_at'=>'1564224439','updated_at'=>'1565317674']);
        $this->insert('{{%common_action_behavior}}',['id'=>'8','app_id'=>'backend','url'=>'common/config/update-info','method'=>'POST','behavior'=>'configUpdateInfo','action'=>'1','level'=>'info','is_record_post'=>'1','is_ajax'=>'1','remark'=>'修改配置信息','addons_name'=>'','is_addon'=>'0','status'=>'1','created_at'=>'1564224508','updated_at'=>'1565269552']);
        $this->insert('{{%common_action_behavior}}',['id'=>'9','app_id'=>'frontend','url'=>'storage/oss','method'=>'*','behavior'=>'Oss','action'=>'1','level'=>'error','is_record_post'=>'1','is_ajax'=>'2','remark'=>'oss直传','addons_name'=>'','is_addon'=>'0','status'=>'1','created_at'=>'1573110502','updated_at'=>'1574810506']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%common_action_behavior}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}


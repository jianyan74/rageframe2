<?php

use yii\db\Migration;

class m190729_021305_common_action_behavior extends Migration
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
            'is_record_post' => "tinyint(4) unsigned NULL DEFAULT '1' COMMENT '是否记录post[0;否;1是]'",
            'is_ajax' => "tinyint(4) unsigned NULL DEFAULT '2' COMMENT '是否ajax请求[1;否;2是;3不限]'",
            'remark' => "varchar(100) NULL DEFAULT '' COMMENT '备注'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='系统_行为表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        $this->insert('{{%common_action_behavior}}',['id'=>'14','app_id'=>'backend','url'=>'site/logout','method'=>'*','behavior'=>'logout','action'=>'1','is_record_post'=>'1','is_ajax'=>'2','remark'=>'退出登录','status'=>'1','created_at'=>'1564215882','updated_at'=>'1564223144']);
        $this->insert('{{%common_action_behavior}}',['id'=>'15','app_id'=>'backend','url'=>'sys/manager/ajax-edit','method'=>'POST','behavior'=>'updateAccountPassword','action'=>'1','is_record_post'=>'0','is_ajax'=>'0','remark'=>'创建/修改管理员账号密码','status'=>'1','created_at'=>'1564221741','updated_at'=>'1564223392']);
        $this->insert('{{%common_action_behavior}}',['id'=>'16','app_id'=>'backend','url'=>'member/member/ajax-edit','method'=>'POST','behavior'=>'updateMemberAccountPassword','action'=>'1','is_record_post'=>'0','is_ajax'=>'0','remark'=>'创建/修改用户账号密码','status'=>'1','created_at'=>'1564221792','updated_at'=>'1564222276']);
        $this->insert('{{%common_action_behavior}}',['id'=>'17','app_id'=>'backend','url'=>'sys/manager/destory','method'=>'*','behavior'=>'deleteManager','action'=>'1','is_record_post'=>'1','is_ajax'=>'2','remark'=>'删除管理员','status'=>'1','created_at'=>'1564223374','updated_at'=>'1564223374']);
        $this->insert('{{%common_action_behavior}}',['id'=>'18','app_id'=>'backend','url'=>'sys/manager/up-password','method'=>'POST','behavior'=>'updatePassword','action'=>'1','is_record_post'=>'0','is_ajax'=>'0','remark'=>'修改管理员密码','status'=>'1','created_at'=>'1564223463','updated_at'=>'1564223463']);
        $this->insert('{{%common_action_behavior}}',['id'=>'19','app_id'=>'backend','url'=>'member/member/recharge','method'=>'POST','behavior'=>'rechargeMemberMoney','action'=>'1','is_record_post'=>'1','is_ajax'=>'0','remark'=>'充值/减少会员积分余额','status'=>'1','created_at'=>'1564224361','updated_at'=>'1564224361']);
        $this->insert('{{%common_action_behavior}}',['id'=>'20','app_id'=>'backend','url'=>'member/member/destory','method'=>'*','behavior'=>'memberDelete','action'=>'1','is_record_post'=>'1','is_ajax'=>'2','remark'=>'删除会员信息','status'=>'1','created_at'=>'1564224439','updated_at'=>'1564224439']);
        $this->insert('{{%common_action_behavior}}',['id'=>'21','app_id'=>'backend','url'=>'common/config/update-info','method'=>'POST','behavior'=>'configUpdateInfo','action'=>'1','is_record_post'=>'1','is_ajax'=>'1','remark'=>'修改配置信息','status'=>'1','created_at'=>'1564224508','updated_at'=>'1564224518']);
        
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


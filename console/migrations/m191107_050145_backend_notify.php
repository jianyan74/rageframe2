<?php

use yii\db\Migration;

class m191107_050145_backend_notify extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%backend_notify}}', [
            'id' => "bigint(20) NOT NULL AUTO_INCREMENT COMMENT '主键'",
            'title' => "varchar(150) NULL DEFAULT '' COMMENT '标题'",
            'content' => "text NULL COMMENT '消息内容'",
            'type' => "tinyint(1) NULL DEFAULT '0' COMMENT '消息类型[1:公告;2:提醒;3:信息(私信)'",
            'target_id' => "int(10) NULL DEFAULT '0' COMMENT '目标id'",
            'target_type' => "varchar(100) NULL DEFAULT '' COMMENT '目标类型'",
            'target_display' => "int(10) NULL DEFAULT '1' COMMENT '接受者是否删除'",
            'action' => "varchar(100) NULL DEFAULT '' COMMENT '动作'",
            'view' => "int(10) NULL DEFAULT '0' COMMENT '浏览量'",
            'sender_id' => "int(10) NULL DEFAULT '0' COMMENT '发送者id'",
            'sender_display' => "tinyint(1) NULL DEFAULT '1' COMMENT '发送者是否删除'",
            'sender_withdraw' => "tinyint(1) NULL DEFAULT '1' COMMENT '是否撤回 0是撤回'",
            'status' => "tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) NOT NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='系统_消息公告表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        $this->insert('{{%backend_notify}}',['id'=>'989','title'=>'','content'=>'错误请求：Undefined offset: 1','type'=>'2','target_id'=>'1633','target_type'=>'log_create','target_display'=>'1','action'=>'log_error','view'=>'0','sender_id'=>'1','sender_display'=>'1','sender_withdraw'=>'1','status'=>'1','created_at'=>'1573102517','updated_at'=>'1573102517']);
        $this->insert('{{%backend_notify}}',['id'=>'990','title'=>'','content'=>'错误请求：Undefined offset: 1','type'=>'2','target_id'=>'1634','target_type'=>'log_create','target_display'=>'1','action'=>'log_error','view'=>'0','sender_id'=>'1','sender_display'=>'1','sender_withdraw'=>'1','status'=>'1','created_at'=>'1573102528','updated_at'=>'1573102528']);
        $this->insert('{{%backend_notify}}',['id'=>'991','title'=>'','content'=>'信息行为：common/config/update-info','type'=>'2','target_id'=>'162','target_type'=>'behavior_create','target_display'=>'1','action'=>'behavior_info','view'=>'0','sender_id'=>'1','sender_display'=>'1','sender_withdraw'=>'1','status'=>'1','created_at'=>'1573102652','updated_at'=>'1573102652']);
        $this->insert('{{%backend_notify}}',['id'=>'992','title'=>'','content'=>'信息行为：common/config/update-info','type'=>'2','target_id'=>'163','target_type'=>'behavior_create','target_display'=>'1','action'=>'behavior_info','view'=>'0','sender_id'=>'1','sender_display'=>'1','sender_withdraw'=>'1','status'=>'1','created_at'=>'1573102657','updated_at'=>'1573102657']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%backend_notify}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}


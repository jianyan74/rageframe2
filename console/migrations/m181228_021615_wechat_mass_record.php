<?php

use yii\db\Migration;

class m181228_021615_wechat_mass_record extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%wechat_mass_record}}', [
            'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
            'tag_name' => 'varchar(50) NULL DEFAULT \'\' COMMENT \'标签名称\'',
            'fans_num' => 'int(10) unsigned NULL DEFAULT \'0\' COMMENT \'粉丝数量\'',
            'msg_id' => 'bigint(20) NULL DEFAULT \'0\' COMMENT \'微信消息id\'',
            'msg_type' => 'varchar(10) NULL DEFAULT \'\' COMMENT \'回复类别\'',
            'content' => 'varchar(10000) NULL DEFAULT \'\' COMMENT \'内容\'',
            'tag_id' => 'int(10) NULL DEFAULT \'0\' COMMENT \'标签id\'',
            'attachment_id' => 'int(10) unsigned NULL DEFAULT \'0\' COMMENT \'资源id\'',
            'media_id' => 'varchar(100) NULL DEFAULT \'\' COMMENT \'媒体id\'',
            'media_type' => 'varchar(10) NULL DEFAULT \'\' COMMENT \'资源类别\'',
            'send_time' => 'int(10) unsigned NULL DEFAULT \'0\' COMMENT \'发送时间\'',
            'send_status' => 'tinyint(4) NULL DEFAULT \'0\' COMMENT \'0未发送 1已发送\'',
            'final_send_time' => 'int(10) unsigned NULL DEFAULT \'0\' COMMENT \'最终发送时间\'',
            'error_content' => 'varchar(255) NULL DEFAULT \'\' COMMENT \'报错原因\'',
            'status' => 'tinyint(4) NULL DEFAULT \'1\' COMMENT \'状态[-1:删除;0:禁用;1启用]\'',
            'created_at' => 'int(10) unsigned NULL DEFAULT \'0\'',
            'updated_at' => 'int(10) NOT NULL DEFAULT \'0\' COMMENT \'修改时间\'',
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='微信_群发记录'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%wechat_mass_record}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}


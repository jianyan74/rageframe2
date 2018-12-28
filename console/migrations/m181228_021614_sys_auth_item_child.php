<?php

use yii\db\Migration;

class m181228_021614_sys_auth_item_child extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%sys_auth_item_child}}', [
            'parent' => 'varchar(64) NOT NULL',
            'child' => 'varchar(64) NOT NULL',
            'PRIMARY KEY (`parent`,`child`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='系统_角色权限表'");
        
        /* 索引设置 */
        $this->createIndex('child','{{%sys_auth_item_child}}','child',0);
        
        /* 外键约束设置 */
        $this->addForeignKey('fk_sys_auth_item_6136_00','{{%sys_auth_item_child}}', 'parent', '{{%sys_auth_item}}', 'name', 'CASCADE', 'CASCADE' );
        $this->addForeignKey('fk_sys_auth_item_6136_01','{{%sys_auth_item_child}}', 'child', '{{%sys_auth_item}}', 'name', 'CASCADE', 'CASCADE' );
        
        /* 表数据 */
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/main/clear-cache']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/member/address/ajax-edit']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/member/address/ajax-update']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/member/address/destroy']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/member/address/index']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/member/auth/ajax-update']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/member/auth/destroy']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/member/auth/edit']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/member/auth/index']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/member/member/ajax-edit']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/member/member/ajax-update']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/member/member/destroy']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/member/member/edit']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/member/member/index']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/addons-plug/ajax-edit']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/addons-plug/ajax-update']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/addons-plug/create']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/addons-plug/install']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/addons-plug/uninstall']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/addons-plug/upgrade']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/addons-plug/upgrade-config']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/auth-accredit/ajax-edit']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/auth-accredit/ajax-update']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/auth-accredit/delete']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/auth-accredit/index']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/auth-role/ajax-update']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/auth-role/delete']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/auth-role/edit']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/auth-role/index']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/auth-rule/ajax-edit']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/auth-rule/ajax-update']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/auth-rule/delete']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/auth-rule/index']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/config-cate/ajax-edit']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/config-cate/ajax-update']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/config-cate/delete']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/config-cate/index']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/config/ajax-edit']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/config/ajax-update']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/config/delete']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/config/index']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/data-base/backups']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/data-base/data-dictionary']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/data-base/delete']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/data-base/export']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/data-base/export-start']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/data-base/optimize']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/data-base/repair']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/data-base/restore']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/data-base/restore-init']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/data-base/restore-start']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/log/action']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/log/action-view']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/log/error']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/log/error-view']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/log/pay']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/log/pay-view']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/manager/ajax-edit']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/manager/ajax-update']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/manager/delete']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/manager/edit']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/manager/index']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/manager/personal']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/manager/up-password']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/menu-cate/ajax-edit']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/menu-cate/ajax-update']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/menu-cate/delete']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/menu-cate/index']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/menu/ajax-edit']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/menu/ajax-update']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/menu/delete']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/menu/index']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/style/update']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/sys/system/info']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/attachment/delete']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/attachment/get-all-attachment']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/attachment/image-create']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/attachment/index']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/attachment/news-edit']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/attachment/preview']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/attachment/send']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/attachment/video-create']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/attachment/voice-create']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/fans-tags/delete']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/fans-tags/index']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/fans/get-all-fans']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/fans/index']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/fans/move-tag']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/fans/send-message']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/fans/sync']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/fans/view']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/mass-record/create']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/mass-record/delete']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/mass-record/index']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/menu/ajax-update']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/menu/delete']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/menu/edit']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/menu/index']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/menu/save']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/menu/sync']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/msg-history/delete']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/msg-history/index']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/qrcode-stat/delete']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/qrcode-stat/index']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/qrcode/add']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/qrcode/delete-all']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/qrcode/down']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/qrcode/edit']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/qrcode/index']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/qrcode/long-url']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/qrcode/qr']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/reply-default/index']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/rule/ajax-update']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/rule/delete']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/rule/edit']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/rule/index']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/setting/history-stat']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/setting/special-message']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/stat/fans-follow']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/stat/rule']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'/wechat/stat/rule-keyword']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'base']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'index']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'index-member']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'index-member-address']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'index-member-auth']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'index-member-info']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'sys']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'sys-addons']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'sys-auth-accredit']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'sys-auth-role']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'sys-auth-rule']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'sys-config']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'sys-config-cate']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'sys-data']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'sys-data-backups']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'sys-data-restore']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'sys-function']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'sys-manager']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'sys-manager-auth']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'sys-menu']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'sys-menu-cate']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'sys-tool']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'sys-view-log']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'wechat']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'wechat-attachment']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'wechat-auto-reply']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'wechat-data-statistics']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'wechat-fans']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'wechat-fans-manager']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'wechat-fans-tags']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'wechat-mass-record']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'wechat-menu']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'wechat-msg-history']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'wechat-qr-code']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'wechat-qr-code-long-url']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'wechat-qr-code-manager']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'wechat-qr-code-statistical']);
        $this->insert('{{%sys_auth_item_child}}',['parent'=>'测试','child'=>'wechat-to-enhance-function']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%sys_auth_item_child}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}


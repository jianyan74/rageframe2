<?php

use yii\db\Migration;

class m200311_020627_common_report_log extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%common_report_log}}', [
            'id' => "bigint(20) unsigned NOT NULL AUTO_INCREMENT",
            'app_id' => "varchar(50) NULL DEFAULT '' COMMENT '应用id'",
            'log_id' => "int(11) unsigned NULL DEFAULT '0' COMMENT '公用日志id'",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'user_id' => "int(11) unsigned NULL DEFAULT '0' COMMENT '用户ID'",
            'device_id' => "varchar(64) NULL DEFAULT '' COMMENT '设备ID'",
            'device_name' => "varchar(64) NULL DEFAULT '' COMMENT '设备名称'",
            'width' => "smallint(6) NULL DEFAULT '0' COMMENT '屏幕宽度'",
            'height' => "smallint(6) NULL DEFAULT '0' COMMENT '屏幕高度'",
            'os' => "varchar(64) NULL DEFAULT '' COMMENT '操作系统'",
            'os_version' => "varchar(64) NULL DEFAULT '' COMMENT '操作系统版本'",
            'is_root' => "tinyint(4) NULL DEFAULT '0' COMMENT '是否越狱， 0:未越狱， 1:已越狱'",
            'network' => "varchar(64) NULL DEFAULT '' COMMENT '网络类型'",
            'wifi_ssid' => "varchar(128) NULL DEFAULT '' COMMENT 'wifi的编号'",
            'wifi_mac' => "varchar(64) NULL DEFAULT '' COMMENT 'WIFI的mac'",
            'xyz' => "varchar(64) NULL DEFAULT '' COMMENT '三轴加速度'",
            'version_name' => "varchar(16) NULL DEFAULT '' COMMENT 'APP版本名'",
            'api_version' => "varchar(255) NULL DEFAULT '' COMMENT 'API的版本号'",
            'channel' => "varchar(64) NULL DEFAULT '' COMMENT '渠道名'",
            'app_name' => "tinyint(4) NULL DEFAULT '0' COMMENT 'APP编号， 1:android， 3:iphone'",
            'dpi' => "int(11) NULL DEFAULT '0' COMMENT '屏幕密度'",
            'api_level' => "int(11) NULL DEFAULT '0' COMMENT 'android的API的版本号'",
            'operator' => "varchar(64) NULL DEFAULT '' COMMENT '运营商'",
            'idfa' => "varchar(64) NULL DEFAULT '' COMMENT 'iphone的IDFA'",
            'idfv' => "varchar(255) NULL DEFAULT '' COMMENT 'iphone的IDFV'",
            'open_udid' => "varchar(255) NULL DEFAULT '' COMMENT 'iphone的OpenUdid'",
            'ip' => "varchar(32) NULL DEFAULT '' COMMENT 'IP地址'",
            'wlan_ip' => "varchar(64) NULL DEFAULT '' COMMENT '局网ip地址'",
            'user_agent' => "varchar(255) NULL DEFAULT '' COMMENT '浏览器的UA'",
            'time' => "datetime NULL COMMENT '客户端时间'",
            'created_at' => "int(10) NULL DEFAULT '0' COMMENT '创建时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='公用_风控日志'");
        
        /* 索引设置 */
        $this->createIndex('log_id','{{%common_report_log}}','log_id',0);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%common_report_log}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}


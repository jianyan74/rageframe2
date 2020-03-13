<?php

use yii\db\Migration;

class m200311_020626_common_action_log extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%common_action_log}}', [
            'id' => "int(10) NOT NULL AUTO_INCREMENT COMMENT '主键'",
            'merchant_id' => "int(10) unsigned NULL DEFAULT '0' COMMENT '商户id'",
            'app_id' => "varchar(50) NULL DEFAULT '' COMMENT '应用id'",
            'user_id' => "int(10) NOT NULL DEFAULT '0' COMMENT '用户id'",
            'behavior' => "varchar(50) NULL DEFAULT '' COMMENT '行为类别'",
            'method' => "varchar(20) NULL DEFAULT '' COMMENT '提交类型'",
            'module' => "varchar(50) NULL DEFAULT '' COMMENT '模块'",
            'controller' => "varchar(50) NULL DEFAULT '' COMMENT '控制器'",
            'action' => "varchar(50) NULL DEFAULT '' COMMENT '控制器方法'",
            'url' => "varchar(200) NULL DEFAULT '' COMMENT '提交url'",
            'get_data' => "json NULL COMMENT 'get数据'",
            'post_data' => "json NULL COMMENT 'post数据'",
            'header_data' => "json NULL COMMENT 'header数据'",
            'ip' => "varchar(16) NULL DEFAULT '' COMMENT 'ip地址'",
            'addons_name' => "varchar(100) NOT NULL DEFAULT '' COMMENT '插件名称'",
            'remark' => "varchar(1000) NULL DEFAULT '' COMMENT '日志备注'",
            'country' => "varchar(50) NULL DEFAULT '' COMMENT '国家'",
            'provinces' => "varchar(50) NULL DEFAULT '' COMMENT '省'",
            'city' => "varchar(50) NULL DEFAULT '' COMMENT '城市'",
            'device' => "varchar(200) NULL DEFAULT '' COMMENT '设备信息'",
            'status' => "tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) NOT NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='系统_行为表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        $this->insert('{{%common_action_log}}',['id'=>'256','merchant_id'=>'0','app_id'=>'backend','user_id'=>'1','behavior'=>'login','method'=>'GET','module'=>'backend','controller'=>'site','action'=>'login','url'=>'site/login','get_data'=>'[]','post_data'=>'[]','header_data'=>'{"host": ["merchants.local"], "accept": ["text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8"], "cookie": ["_identity-merchant=efc2ee89b186d667c493d97dfdef05217e54d41824ba2302bfc15f1f91fd45f4a%3A2%3A%7Bi%3A0%3Bs%3A18%3A%22_identity-merchant%22%3Bi%3A1%3Bs%3A46%3A%22%5B5%2C%22w8cIYvcYNHPa4iDWux2PwqmuQMKEYbS6%22%2C2592000%5D%22%3B%7D; advanced-backend=1r5epr6himgrcem00gvlos21jc; _csrf-backend=47133155747b2ca48726b0cff9d22a113d7d6b9eb42b8a3968ecf8bca0951099a%3A2%3A%7Bi%3A0%3Bs%3A13%3A%22_csrf-backend%22%3Bi%3A1%3Bs%3A32%3A%22dzItjeg6MDCen08Wp_sEUMEqnP2mGSlK%22%3B%7D; advanced-frontend=3dsf1mufgbgh2empsh8r1o8p1n; _csrf-frontend=48ec066f90a7240164e9e54811ee37306952489326ad584d75d641894bf87a3aa%3A2%3A%7Bi%3A0%3Bs%3A14%3A%22_csrf-frontend%22%3Bi%3A1%3Bs%3A32%3A%22YjH9ULr7wUrqkTjwk7YoRpEQjEiyKFWu%22%3B%7D"], "connection": ["close"], "user-agent": ["Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:73.0) Gecko/20100101 Firefox/73.0"], "authorization": [""], "cache-control": ["max-age=0"], "accept-encoding": ["gzip, deflate"], "accept-language": ["zh-CN,zh;q=0.8,zh-TW;q=0.7,zh-HK;q=0.5,en-US;q=0.3,en;q=0.2"], "upgrade-insecure-requests": ["1"]}','ip'=>'2130706433','addons_name'=>'','remark'=>'自动登录','country'=>'本机地址','provinces'=>'本机地址','city'=>'','device'=>'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:73.0) Gecko/20100101 Firefox/73.0','status'=>'1','created_at'=>'1583892010','updated_at'=>'1583892010']);
        $this->insert('{{%common_action_log}}',['id'=>'257','merchant_id'=>'0','app_id'=>'backend','user_id'=>'1','behavior'=>'configUpdateInfo','method'=>'POST','module'=>'common','controller'=>'config','action'=>'update-info','url'=>'common/config/update-info','get_data'=>'[]','post_data'=>'{"config": {"web_site_icp": "浙ICP备17025911号-1", "web_copyright": "© 2016 - 2020 RageFrame All Rights Reserved.", "web_baidu_push": "", "web_site_title": "RageFrame", "web_visit_code": "", "web_seo_keywords": "", "web_seo_description": ""}, "_csrf-backend": "OncVDNOTZ-is2-4COWfGquYWXkVBrohi9ihFN4FpcwNeDVx4ufYA3uGfrWdXV_79lkktABTjzROYeHdaxjofSA=="}','header_data'=>'{"host": ["merchants.local"], "accept": ["application/json, text/javascript, */*; q=0.01"], "cookie": ["_identity-merchant=efc2ee89b186d667c493d97dfdef05217e54d41824ba2302bfc15f1f91fd45f4a%3A2%3A%7Bi%3A0%3Bs%3A18%3A%22_identity-merchant%22%3Bi%3A1%3Bs%3A46%3A%22%5B5%2C%22w8cIYvcYNHPa4iDWux2PwqmuQMKEYbS6%22%2C2592000%5D%22%3B%7D; advanced-backend=1r5epr6himgrcem00gvlos21jc; _csrf-backend=47133155747b2ca48726b0cff9d22a113d7d6b9eb42b8a3968ecf8bca0951099a%3A2%3A%7Bi%3A0%3Bs%3A13%3A%22_csrf-backend%22%3Bi%3A1%3Bs%3A32%3A%22dzItjeg6MDCen08Wp_sEUMEqnP2mGSlK%22%3B%7D; advanced-frontend=3dsf1mufgbgh2empsh8r1o8p1n; _csrf-frontend=48ec066f90a7240164e9e54811ee37306952489326ad584d75d641894bf87a3aa%3A2%3A%7Bi%3A0%3Bs%3A14%3A%22_csrf-frontend%22%3Bi%3A1%3Bs%3A32%3A%22YjH9ULr7wUrqkTjwk7YoRpEQjEiyKFWu%22%3B%7D"], "origin": ["http://merchants.local"], "referer": ["http://merchants.local/backend/common/config/edit-all"], "connection": ["close"], "user-agent": ["Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:73.0) Gecko/20100101 Firefox/73.0"], "content-type": ["application/x-www-form-urlencoded; charset=UTF-8"], "x-csrf-token": ["OncVDNOTZ-is2-4COWfGquYWXkVBrohi9ihFN4FpcwNeDVx4ufYA3uGfrWdXV_79lkktABTjzROYeHdaxjofSA=="], "authorization": [""], "content-length": ["404"], "accept-encoding": ["gzip, deflate"], "accept-language": ["zh-CN,zh;q=0.8,zh-TW;q=0.7,zh-HK;q=0.5,en-US;q=0.3,en;q=0.2"], "x-requested-with": ["XMLHttpRequest"]}','ip'=>'2130706433','addons_name'=>'','remark'=>'修改配置信息','country'=>'本机地址','provinces'=>'本机地址','city'=>'','device'=>'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:73.0) Gecko/20100101 Firefox/73.0','status'=>'1','created_at'=>'1583892084','updated_at'=>'1583892084']);
        $this->insert('{{%common_action_log}}',['id'=>'258','merchant_id'=>'0','app_id'=>'backend','user_id'=>'1','behavior'=>'configUpdateInfo','method'=>'POST','module'=>'common','controller'=>'config','action'=>'update-info','url'=>'common/config/update-info','get_data'=>'[]','post_data'=>'{"config": {"sys_dev": "1", "sys_tags": "1", "sys_allow_ip": "", "sys_related_links": "1", "sys_ip_blacklist_open": "0", "sys_image_watermark_status": "0", "sys_image_watermark_location": "1"}, "_csrf-backend": "OncVDNOTZ-is2-4COWfGquYWXkVBrohi9ihFN4FpcwNeDVx4ufYA3uGfrWdXV_79lkktABTjzROYeHdaxjofSA=="}','header_data'=>'{"host": ["merchants.local"], "accept": ["application/json, text/javascript, */*; q=0.01"], "cookie": ["_identity-merchant=efc2ee89b186d667c493d97dfdef05217e54d41824ba2302bfc15f1f91fd45f4a%3A2%3A%7Bi%3A0%3Bs%3A18%3A%22_identity-merchant%22%3Bi%3A1%3Bs%3A46%3A%22%5B5%2C%22w8cIYvcYNHPa4iDWux2PwqmuQMKEYbS6%22%2C2592000%5D%22%3B%7D; advanced-backend=1r5epr6himgrcem00gvlos21jc; _csrf-backend=47133155747b2ca48726b0cff9d22a113d7d6b9eb42b8a3968ecf8bca0951099a%3A2%3A%7Bi%3A0%3Bs%3A13%3A%22_csrf-backend%22%3Bi%3A1%3Bs%3A32%3A%22dzItjeg6MDCen08Wp_sEUMEqnP2mGSlK%22%3B%7D; advanced-frontend=3dsf1mufgbgh2empsh8r1o8p1n; _csrf-frontend=48ec066f90a7240164e9e54811ee37306952489326ad584d75d641894bf87a3aa%3A2%3A%7Bi%3A0%3Bs%3A14%3A%22_csrf-frontend%22%3Bi%3A1%3Bs%3A32%3A%22YjH9ULr7wUrqkTjwk7YoRpEQjEiyKFWu%22%3B%7D"], "origin": ["http://merchants.local"], "referer": ["http://merchants.local/backend/common/config/edit-all"], "connection": ["close"], "user-agent": ["Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:73.0) Gecko/20100101 Firefox/73.0"], "content-type": ["application/x-www-form-urlencoded; charset=UTF-8"], "x-csrf-token": ["OncVDNOTZ-is2-4COWfGquYWXkVBrohi9ihFN4FpcwNeDVx4ufYA3uGfrWdXV_79lkktABTjzROYeHdaxjofSA=="], "authorization": [""], "content-length": ["329"], "accept-encoding": ["gzip, deflate"], "accept-language": ["zh-CN,zh;q=0.8,zh-TW;q=0.7,zh-HK;q=0.5,en-US;q=0.3,en;q=0.2"], "x-requested-with": ["XMLHttpRequest"]}','ip'=>'2130706433','addons_name'=>'','remark'=>'修改配置信息','country'=>'本机地址','provinces'=>'本机地址','city'=>'','device'=>'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:73.0) Gecko/20100101 Firefox/73.0','status'=>'1','created_at'=>'1583892091','updated_at'=>'1583892091']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%common_action_log}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}


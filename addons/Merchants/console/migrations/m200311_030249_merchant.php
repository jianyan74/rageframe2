<?php

use yii\db\Migration;

class m200311_030249_merchant extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%merchant}}', [
            'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT",
            'title' => "varchar(200) NULL DEFAULT '' COMMENT '商户名称'",
            'tax_rate' => "decimal(10,2) unsigned NULL DEFAULT '0.00' COMMENT '税率'",
            'cover' => "char(150) NULL DEFAULT '' COMMENT '头像'",
            'term_of_validity_type' => "int(1) NULL DEFAULT '0' COMMENT '有效期类型 0固定时间 1不限'",
            'cate_id' => "int(11) NULL DEFAULT '0' COMMENT '分类'",
            'start_time' => "int(10) NULL DEFAULT '0' COMMENT '开始时间'",
            'end_time' => "int(10) NULL DEFAULT '0' COMMENT '结束时间'",
            'email' => "varchar(60) NULL DEFAULT '' COMMENT '邮箱'",
            'province_id' => "int(10) NULL DEFAULT '0' COMMENT '省'",
            'city_id' => "int(10) NULL DEFAULT '0' COMMENT '城市'",
            'area_id' => "int(10) NULL DEFAULT '0' COMMENT '地区'",
            'address_name' => "varchar(200) NULL DEFAULT '' COMMENT '地址'",
            'address_details' => "varchar(100) NULL DEFAULT '' COMMENT '详细地址'",
            'longitude' => "varchar(100) NULL DEFAULT '' COMMENT '经度'",
            'latitude' => "varchar(100) NULL DEFAULT '' COMMENT '纬度'",
            'mobile' => "varchar(100) NULL DEFAULT '' COMMENT '手机号码'",
            'level_id' => "int(11) NULL COMMENT '店铺等级'",
            'company_name' => "varchar(50) NULL DEFAULT '' COMMENT '店铺公司名称'",
            'close_info' => "varchar(255) NULL DEFAULT '' COMMENT '店铺关闭原因'",
            'sort' => "int(11) NOT NULL DEFAULT '0' COMMENT '店铺排序'",
            'logo' => "varchar(100) NULL DEFAULT '' COMMENT '店铺logo'",
            'banner' => "varchar(100) NULL DEFAULT '' COMMENT '店铺横幅'",
            'keywords' => "varchar(255) NULL DEFAULT '' COMMENT '店铺seo关键字'",
            'description' => "varchar(255) NULL DEFAULT '' COMMENT '店铺seo描述'",
            'qq' => "varchar(50) NULL DEFAULT '' COMMENT 'QQ'",
            'ww' => "varchar(50) NULL DEFAULT '' COMMENT '阿里旺旺'",
            'is_recommend' => "tinyint(1) NULL DEFAULT '0' COMMENT '推荐，0为否，1为是，默认为0'",
            'credit' => "int(10) NULL DEFAULT '100' COMMENT '店铺信用'",
            'desc_credit' => "float NULL DEFAULT '5' COMMENT '描述相符度分数'",
            'service_credit' => "float NULL DEFAULT '5' COMMENT '服务态度分数'",
            'delivery_credit' => "float NULL DEFAULT '5' COMMENT '发货速度分数'",
            'collect' => "int(10) unsigned NULL DEFAULT '0' COMMENT '店铺收藏数量'",
            'stamp' => "varchar(200) NULL DEFAULT '' COMMENT '店铺印章'",
            'print_desc' => "varchar(500) NULL DEFAULT '' COMMENT '打印订单页面下方说明文字'",
            'sales' => "decimal(10,2) NULL DEFAULT '0.00' COMMENT '店铺销售额（不计算退款）'",
            'free_time' => "varchar(200) NULL COMMENT '商家配送时间'",
            'business_time' => "varchar(200) NULL COMMENT '商家配送时间'",
            'region' => "varchar(50) NULL DEFAULT '' COMMENT '店铺默认配送区域'",
            'qrcode' => "varchar(100) NULL DEFAULT '' COMMENT '店铺公众号'",
            'state' => "tinyint(1) NOT NULL DEFAULT '1' COMMENT '店铺状态，0关闭，1开启，2审核中'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%merchant}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}


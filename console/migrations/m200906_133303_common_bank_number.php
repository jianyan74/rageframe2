<?php

use yii\db\Migration;

class m200906_133303_common_bank_number extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%common_bank_number}}', [
            'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT",
            'bank_name' => "varchar(255) NULL DEFAULT '' COMMENT '银行名称'",
            'bank_number' => "varchar(255) NULL DEFAULT '' COMMENT '银行编号'",
            'type' => "tinyint(4) NULL DEFAULT '1' COMMENT '银行卡类型：1:微信；2:支付宝'",
            'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态'",
            'created_at' => "int(10) NULL DEFAULT '0' COMMENT '创建时间'",
            'updated_at' => "int(10) NULL DEFAULT '0' COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='公用_银行卡编号'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        $this->insert('{{%common_bank_number}}',['id'=>'1','bank_name'=>'工商银行','bank_number'=>'1002','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'2','bank_name'=>'农业银行','bank_number'=>'1005','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'3','bank_name'=>'建设银行','bank_number'=>'1003','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'4','bank_name'=>'中国银行','bank_number'=>'1026','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'5','bank_name'=>'交通银行','bank_number'=>'1020','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'6','bank_name'=>'招商银行','bank_number'=>'1001','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'7','bank_name'=>'邮储银行','bank_number'=>'1066','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'8','bank_name'=>'民生银行','bank_number'=>'1006','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'9','bank_name'=>'平安银行','bank_number'=>'1010','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'10','bank_name'=>'中信银行','bank_number'=>'1021','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'11','bank_name'=>'浦发银行','bank_number'=>'1004','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'12','bank_name'=>'兴业银行','bank_number'=>'1009','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'13','bank_name'=>'光大银行','bank_number'=>'1022','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'14','bank_name'=>'广发银行','bank_number'=>'1027','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'15','bank_name'=>'华夏银行','bank_number'=>'1025','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'16','bank_name'=>'宁波银行','bank_number'=>'1056','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'17','bank_name'=>'北京银行','bank_number'=>'4836','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'18','bank_name'=>'上海银行','bank_number'=>'1024','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'19','bank_name'=>'南京银行','bank_number'=>'1054','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'20','bank_name'=>'长子县融汇村镇银行','bank_number'=>'4755','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'21','bank_name'=>'长沙银行','bank_number'=>'4216','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'22','bank_name'=>'浙江泰隆商业银行','bank_number'=>'4051','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'23','bank_name'=>'中原银行','bank_number'=>'4753','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'24','bank_name'=>'企业银行（中国）','bank_number'=>'4761','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'25','bank_name'=>'顺德农商银行','bank_number'=>'4036','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'26','bank_name'=>'衡水银行','bank_number'=>'4752','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'27','bank_name'=>'长治银行','bank_number'=>'4756','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'28','bank_name'=>'大同银行','bank_number'=>'4767','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'29','bank_name'=>'河南省农村信用社','bank_number'=>'4115','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'30','bank_name'=>'宁夏黄河农村商业银行','bank_number'=>'4150','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'31','bank_name'=>'山西省农村信用社','bank_number'=>'4156','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'32','bank_name'=>'安徽省农村信用社','bank_number'=>'4166','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'33','bank_name'=>'甘肃省农村信用社','bank_number'=>'4157','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'34','bank_name'=>'天津农村商业银行','bank_number'=>'4153','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'35','bank_name'=>'广西壮族自治区农村信用社','bank_number'=>'4113','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'36','bank_name'=>'陕西省农村信用社','bank_number'=>'4108','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'37','bank_name'=>'深圳农村商业银行','bank_number'=>'4076','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'38','bank_name'=>'宁波鄞州农村商业银行','bank_number'=>'4052','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'39','bank_name'=>'浙江省农村信用社联合社','bank_number'=>'4764','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'40','bank_name'=>'江苏省农村信用社联合社','bank_number'=>'4217','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'41','bank_name'=>'江苏紫金农村商业银行股份有限公司','bank_number'=>'4072','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'42','bank_name'=>'北京中关村银行股份有限公司','bank_number'=>'4769','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'43','bank_name'=>'星展银行（中国）有限公司','bank_number'=>'4778','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'44','bank_name'=>'枣庄银行股份有限公司','bank_number'=>'4766','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'45','bank_name'=>'海口联合农村商业银行股份有限公司','bank_number'=>'4758','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        $this->insert('{{%common_bank_number}}',['id'=>'46','bank_name'=>'南洋商业银行（中国）有限公司','bank_number'=>'4763','type'=>'1','status'=>'1','created_at'=>'1576129966','updated_at'=>'1576129966']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%common_bank_number}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}


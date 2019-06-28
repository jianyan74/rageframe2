<?php

use yii\db\Migration;

class m190628_021601_common_config extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%common_config}}', [
            'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键'",
            'title' => "varchar(50) NOT NULL DEFAULT '' COMMENT '配置标题'",
            'name' => "varchar(50) NOT NULL DEFAULT '' COMMENT '配置标识'",
            'type' => "varchar(30) NOT NULL DEFAULT '' COMMENT '配置类型'",
            'cate_id' => "int(10) unsigned NOT NULL COMMENT '配置分类'",
            'extra' => "varchar(1000) NOT NULL DEFAULT '' COMMENT '配置值'",
            'remark' => "varchar(1000) NOT NULL DEFAULT '' COMMENT '配置说明'",
            'is_hide_remark' => "tinyint(4) NULL DEFAULT '1' COMMENT '是否隐藏说明'",
            'default_value' => "varchar(500) NULL DEFAULT '' COMMENT '默认配置'",
            'sort' => "int(10) unsigned NULL COMMENT '排序'",
            'status' => "tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
            'created_at' => "int(10) unsigned NOT NULL COMMENT '创建时间'",
            'updated_at' => "int(10) unsigned NOT NULL COMMENT '修改时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='公用_配置表'");
        
        /* 索引设置 */
        $this->createIndex('uk_name','{{%common_config}}','name',1);
        $this->createIndex('type','{{%common_config}}','type',0);
        $this->createIndex('group','{{%common_config}}','cate_id',0);
        
        
        /* 表数据 */
        $this->insert('{{%common_config}}',['id'=>'1','title'=>'版权所有','name'=>'web_copyright','type'=>'text','cate_id'=>'17','extra'=>'','remark'=>'','is_hide_remark'=>'1','default_value'=>'','sort'=>'9','status'=>'1','created_at'=>'1526199058','updated_at'=>'1549174954']);
        $this->insert('{{%common_config}}',['id'=>'2','title'=>'网站标题','name'=>'web_site_title','type'=>'text','cate_id'=>'17','extra'=>'','remark'=>'前台显示站点名称','is_hide_remark'=>'0','default_value'=>'','sort'=>'0','status'=>'1','created_at'=>'1526372845','updated_at'=>'1533200734']);
        $this->insert('{{%common_config}}',['id'=>'3','title'=>'网站logo','name'=>'web_logo','type'=>'image','cate_id'=>'17','extra'=>'','remark'=>'','is_hide_remark'=>'1','default_value'=>'','sort'=>'1','status'=>'1','created_at'=>'1526372885','updated_at'=>'1554367633']);
        $this->insert('{{%common_config}}',['id'=>'4','title'=>'备案号','name'=>'web_site_icp','type'=>'text','cate_id'=>'17','extra'=>'','remark'=>'','is_hide_remark'=>'1','default_value'=>'','sort'=>'10','status'=>'1','created_at'=>'1526372926','updated_at'=>'1553912639']);
        $this->insert('{{%common_config}}',['id'=>'5','title'=>'网站访问量统计代码','name'=>'web_visit_code','type'=>'textarea','cate_id'=>'17','extra'=>'','remark'=>'','is_hide_remark'=>'1','default_value'=>'','sort'=>'12','status'=>'1','created_at'=>'1526373044','updated_at'=>'1553912644']);
        $this->insert('{{%common_config}}',['id'=>'6','title'=>'百度自动推送代码','name'=>'web_baidu_push','type'=>'textarea','cate_id'=>'17','extra'=>'','remark'=>'','is_hide_remark'=>'1','default_value'=>'','sort'=>'11','status'=>'1','created_at'=>'1526373086','updated_at'=>'1553912642']);
        $this->insert('{{%common_config}}',['id'=>'7','title'=>'后台允许访问IP','name'=>'sys_allow_ip','type'=>'textarea','cate_id'=>'18','extra'=>'','remark'=>'多个用换行/逗号/分号分隔，如果不配置表示不限制IP访问','is_hide_remark'=>'0','default_value'=>'','sort'=>'3','status'=>'1','created_at'=>'1526374098','updated_at'=>'1559444377']);
        $this->insert('{{%common_config}}',['id'=>'8','title'=>'公众号帐号','name'=>'wechat_account','type'=>'text','cate_id'=>'19','extra'=>'','remark'=>'填写公众号的帐号，一般为英文帐号','is_hide_remark'=>'0','default_value'=>'','sort'=>'0','status'=>'1','created_at'=>'1526374732','updated_at'=>'1526376340']);
        $this->insert('{{%common_config}}',['id'=>'9','title'=>'原始ID','name'=>'wechat_id','type'=>'text','cate_id'=>'19','extra'=>'','remark'=>'在给粉丝发送客服消息时,原始ID不能为空。建议您完善该选项','is_hide_remark'=>'0','default_value'=>'','sort'=>'1','status'=>'1','created_at'=>'1526374769','updated_at'=>'1536050694']);
        $this->insert('{{%common_config}}',['id'=>'10','title'=>'级别','name'=>'wechat_rank','type'=>'radioList','cate_id'=>'19','extra'=>'1:普通订阅号,
2:普通服务号,
3:认证订阅号,
4:认证服务号/认证媒体/政府订阅号','remark'=>'注意：即使公众平台显示为“未认证”, 但只要【公众号设置】/【账号详情】下【认证情况】显示资质审核通过, 即可认定为认证号.','is_hide_remark'=>'0','default_value'=>'1','sort'=>'2','status'=>'1','created_at'=>'1526374841','updated_at'=>'1553850592']);
        $this->insert('{{%common_config}}',['id'=>'11','title'=>'App ID','name'=>'wechat_appid','type'=>'text','cate_id'=>'19','extra'=>'','remark'=>'请填写微信公众平台后台的AppId','is_hide_remark'=>'0','default_value'=>'','sort'=>'3','status'=>'1','created_at'=>'1526376099','updated_at'=>'1539928226']);
        $this->insert('{{%common_config}}',['id'=>'12','title'=>'App Secret','name'=>'wechat_appsecret','type'=>'text','cate_id'=>'19','extra'=>'','remark'=>'请填写微信公众平台后台的AppSecret, 只有填写这两项才能管理自定义菜单','is_hide_remark'=>'0','default_value'=>'','sort'=>'4','status'=>'1','created_at'=>'1526376188','updated_at'=>'1539928226']);
        $this->insert('{{%common_config}}',['id'=>'13','title'=>'Token','name'=>'wechat_token','type'=>'secretKeyText','cate_id'=>'19','extra'=>'32','remark'=>'与公众平台接入设置值一致，必须为英文或者数字，长度为3到32个字符. 请妥善保管, Token 泄露将可能被窃取或篡改平台的操作数据','is_hide_remark'=>'0','default_value'=>'','sort'=>'5','status'=>'1','created_at'=>'1526376249','updated_at'=>'1539134202']);
        $this->insert('{{%common_config}}',['id'=>'14','title'=>'EncodingAESKey','name'=>'wechat_encodingaeskey','type'=>'text','cate_id'=>'19','extra'=>'','remark'=>'与公众平台接入设置值一致，必须为英文或者数字，长度为43个字符. 请妥善保管, EncodingAESKey 泄露将可能被窃取或篡改平台的操作数据','is_hide_remark'=>'0','default_value'=>'','sort'=>'6','status'=>'1','created_at'=>'1526376295','updated_at'=>'1526376340']);
        $this->insert('{{%common_config}}',['id'=>'15','title'=>'微信关注二维码','name'=>'wechat_qrcode','type'=>'image','cate_id'=>'19','extra'=>'','remark'=>'','is_hide_remark'=>'1','default_value'=>'','sort'=>'7','status'=>'1','created_at'=>'1526376328','updated_at'=>'1526376393']);
        $this->insert('{{%common_config}}',['id'=>'16','title'=>'分享标题','name'=>'wechat_share_title','type'=>'text','cate_id'=>'26','extra'=>'','remark'=>'','is_hide_remark'=>'1','default_value'=>'','sort'=>'0','status'=>'1','created_at'=>'1526376418','updated_at'=>'1553909298']);
        $this->insert('{{%common_config}}',['id'=>'17','title'=>'分享详情','name'=>'wechat_share_details','type'=>'textarea','cate_id'=>'26','extra'=>'','remark'=>'','is_hide_remark'=>'1','default_value'=>'','sort'=>'1','status'=>'1','created_at'=>'1526376464','updated_at'=>'1553909304']);
        $this->insert('{{%common_config}}',['id'=>'18','title'=>'分享图片','name'=>'wechat_share_pic','type'=>'image','cate_id'=>'26','extra'=>'','remark'=>'','is_hide_remark'=>'1','default_value'=>'','sort'=>'2','status'=>'1','created_at'=>'1526376489','updated_at'=>'1553909311']);
        $this->insert('{{%common_config}}',['id'=>'19','title'=>'分享链接','name'=>'wechat_share_url','type'=>'text','cate_id'=>'26','extra'=>'','remark'=>'','is_hide_remark'=>'1','default_value'=>'','sort'=>'3','status'=>'1','created_at'=>'1526376520','updated_at'=>'1553909319']);
        $this->insert('{{%common_config}}',['id'=>'20','title'=>'App ID','name'=>'login_qq_appid','type'=>'text','cate_id'=>'11','extra'=>'','remark'=>'','is_hide_remark'=>'1','default_value'=>'','sort'=>'0','status'=>'1','created_at'=>'1526438194','updated_at'=>'1526438194']);
        $this->insert('{{%common_config}}',['id'=>'21','title'=>'App Secret','name'=>'login_qq_app_secret','type'=>'text','cate_id'=>'11','extra'=>'','remark'=>'','is_hide_remark'=>'1','default_value'=>'','sort'=>'1','status'=>'1','created_at'=>'1526438237','updated_at'=>'1526438237']);
        $this->insert('{{%common_config}}',['id'=>'22','title'=>'App ID','name'=>'login_sina_appid','type'=>'text','cate_id'=>'12','extra'=>'','remark'=>'','is_hide_remark'=>'1','default_value'=>'','sort'=>'0','status'=>'1','created_at'=>'1526438194','updated_at'=>'1526438194']);
        $this->insert('{{%common_config}}',['id'=>'23','title'=>'App Secret','name'=>'login_sing_app_secret','type'=>'text','cate_id'=>'12','extra'=>'','remark'=>'','is_hide_remark'=>'1','default_value'=>'','sort'=>'1','status'=>'1','created_at'=>'1526438237','updated_at'=>'1526438237']);
        $this->insert('{{%common_config}}',['id'=>'24','title'=>'App Secret','name'=>'login_wechat_app_secret','type'=>'text','cate_id'=>'13','extra'=>'','remark'=>'','is_hide_remark'=>'1','default_value'=>'','sort'=>'1','status'=>'1','created_at'=>'1526438237','updated_at'=>'1526438237']);
        $this->insert('{{%common_config}}',['id'=>'25','title'=>'App ID','name'=>'login_wechat_appid','type'=>'text','cate_id'=>'13','extra'=>'','remark'=>'','is_hide_remark'=>'1','default_value'=>'','sort'=>'0','status'=>'1','created_at'=>'1526438194','updated_at'=>'1526438194']);
        $this->insert('{{%common_config}}',['id'=>'26','title'=>'App ID','name'=>'login_github_appid','type'=>'text','cate_id'=>'14','extra'=>'','remark'=>'','is_hide_remark'=>'1','default_value'=>'','sort'=>'0','status'=>'1','created_at'=>'1526438194','updated_at'=>'1526438194']);
        $this->insert('{{%common_config}}',['id'=>'27','title'=>'App Secret','name'=>'login_github_app_secret','type'=>'text','cate_id'=>'14','extra'=>'','remark'=>'','is_hide_remark'=>'1','default_value'=>'','sort'=>'1','status'=>'1','created_at'=>'1526438237','updated_at'=>'1526438237']);
        $this->insert('{{%common_config}}',['id'=>'28','title'=>'SMTP服务器','name'=>'smtp_host','type'=>'text','cate_id'=>'16','extra'=>'','remark'=>'例如:smtp.163.com','is_hide_remark'=>'0','default_value'=>'','sort'=>'0','status'=>'1','created_at'=>'1526438237','updated_at'=>'1544060631']);
        $this->insert('{{%common_config}}',['id'=>'29','title'=>'SMTP帐号','name'=>'smtp_username','type'=>'text','cate_id'=>'16','extra'=>'','remark'=>'例如:mobile@163.com','is_hide_remark'=>'0','default_value'=>'','sort'=>'1','status'=>'1','created_at'=>'1526438237','updated_at'=>'1544060631']);
        $this->insert('{{%common_config}}',['id'=>'30','title'=>'SMTP客户端授权码','name'=>'smtp_password','type'=>'password','cate_id'=>'16','extra'=>'','remark'=>'如果是163邮箱，此处要填授权码','is_hide_remark'=>'0','default_value'=>'','sort'=>'2','status'=>'1','created_at'=>'1526438237','updated_at'=>'1544060734']);
        $this->insert('{{%common_config}}',['id'=>'31','title'=>'SMTP端口','name'=>'smtp_port','type'=>'text','cate_id'=>'16','extra'=>'','remark'=>'25、109、110、143、465、995、993','is_hide_remark'=>'0','default_value'=>'','sort'=>'4','status'=>'1','created_at'=>'1526438237','updated_at'=>'1544060631']);
        $this->insert('{{%common_config}}',['id'=>'32','title'=>'发件人名称','name'=>'smtp_name','type'=>'text','cate_id'=>'16','extra'=>'','remark'=>'例如:RageFrame','is_hide_remark'=>'0','default_value'=>'','sort'=>'5','status'=>'1','created_at'=>'1526438237','updated_at'=>'1544060631']);
        $this->insert('{{%common_config}}',['id'=>'33','title'=>'使用SSL加密','name'=>'smtp_encryption','type'=>'radioList','cate_id'=>'16','extra'=>'0:关闭;
1:开启;','remark'=>'开启此项后，连接将用SSL的形式，此项需要SMTP服务器支持','is_hide_remark'=>'0','default_value'=>'','sort'=>'3','status'=>'1','created_at'=>'1526438237','updated_at'=>'1526439059']);
        $this->insert('{{%common_config}}',['id'=>'34','title'=>'商户号','name'=>'wechat_mchid','type'=>'text','cate_id'=>'9','extra'=>'','remark'=>'公众号支付的商户账号','is_hide_remark'=>'0','default_value'=>'','sort'=>'0','status'=>'1','created_at'=>'1526710975','updated_at'=>'1545285079']);
        $this->insert('{{%common_config}}',['id'=>'35','title'=>'支付密钥','name'=>'wechat_api_key','type'=>'secretKeyText','cate_id'=>'9','extra'=>'32','remark'=>'','is_hide_remark'=>'1','default_value'=>'','sort'=>'1','status'=>'1','created_at'=>'1526711047','updated_at'=>'1545285079']);
        $this->insert('{{%common_config}}',['id'=>'36','title'=>'证书公钥','name'=>'wechat_cert_path','type'=>'text','cate_id'=>'9','extra'=>'','remark'=>'如需使用敏感接口(如退款、发送红包等)需要配置 API 证书路径(登录商户平台下载 API 证书),注意路径为绝对路径','is_hide_remark'=>'0','default_value'=>'','sort'=>'2','status'=>'1','created_at'=>'1526711138','updated_at'=>'1545285079']);
        $this->insert('{{%common_config}}',['id'=>'37','title'=>'证书私钥','name'=>'wechat_key_path','type'=>'text','cate_id'=>'9','extra'=>'','remark'=>'如需使用敏感接口(如退款、发送红包等)需要配置 API 证书路径(登录商户平台下载 API 证书),注意路径为绝对路径','is_hide_remark'=>'0','default_value'=>'','sort'=>'3','status'=>'1','created_at'=>'1526711237','updated_at'=>'1545285077']);
        $this->insert('{{%common_config}}',['id'=>'38','title'=>'App ID','name'=>'miniprogram_appid','type'=>'text','cate_id'=>'22','extra'=>'','remark'=>'','is_hide_remark'=>'1','default_value'=>'','sort'=>'0','status'=>'1','created_at'=>'1526711433','updated_at'=>'1553909256']);
        $this->insert('{{%common_config}}',['id'=>'39','title'=>'App Secret','name'=>'miniprogram_secret','type'=>'text','cate_id'=>'22','extra'=>'','remark'=>'','is_hide_remark'=>'1','default_value'=>'','sort'=>'1','status'=>'1','created_at'=>'1526711464','updated_at'=>'1553909246']);
        $this->insert('{{%common_config}}',['id'=>'40','title'=>'用户凭证','name'=>'storage_qiniu_accesskey','type'=>'text','cate_id'=>'15','extra'=>'','remark'=>'ak','is_hide_remark'=>'0','default_value'=>'','sort'=>'0','status'=>'1','created_at'=>'1527032360','updated_at'=>'1546657094']);
        $this->insert('{{%common_config}}',['id'=>'41','title'=>'签名密钥','name'=>'storage_qiniu_secrectkey','type'=>'text','cate_id'=>'15','extra'=>'','remark'=>'sk','is_hide_remark'=>'0','default_value'=>'','sort'=>'1','status'=>'1','created_at'=>'1527032390','updated_at'=>'1546657094']);
        $this->insert('{{%common_config}}',['id'=>'42','title'=>'域名','name'=>'storage_qiniu_domain','type'=>'text','cate_id'=>'15','extra'=>'','remark'=>'domain','is_hide_remark'=>'0','default_value'=>'','sort'=>'2','status'=>'1','created_at'=>'1527032506','updated_at'=>'1546657382']);
        $this->insert('{{%common_config}}',['id'=>'43','title'=>'空间名','name'=>'storage_qiniu_bucket','type'=>'text','cate_id'=>'15','extra'=>'','remark'=>'七牛的后台管理页面自己创建的空间名','is_hide_remark'=>'0','default_value'=>'','sort'=>'3','status'=>'1','created_at'=>'1527032578','updated_at'=>'1546657382']);
        $this->insert('{{%common_config}}',['id'=>'44','title'=>'accessKeyId','name'=>'storage_aliyun_accesskeyid','type'=>'text','cate_id'=>'20','extra'=>'','remark'=>'','is_hide_remark'=>'1','default_value'=>'','sort'=>'0','status'=>'1','created_at'=>'1527032713','updated_at'=>'1553909089']);
        $this->insert('{{%common_config}}',['id'=>'45','title'=>'accessSecret','name'=>'storage_aliyun_accesskeysecret','type'=>'text','cate_id'=>'20','extra'=>'','remark'=>'','is_hide_remark'=>'1','default_value'=>'','sort'=>'1','status'=>'1','created_at'=>'1527032738','updated_at'=>'1555887597']);
        $this->insert('{{%common_config}}',['id'=>'46','title'=>'端口','name'=>'storage_aliyun_endpoint','type'=>'text','cate_id'=>'20','extra'=>'','remark'=>'EndPoint 接收消息的终端地址 例如：oss-cn-shanghai.aliyuncs.com','is_hide_remark'=>'0','default_value'=>'','sort'=>'2','status'=>'1','created_at'=>'1527032773','updated_at'=>'1554804117']);
        $this->insert('{{%common_config}}',['id'=>'47','title'=>'空间名','name'=>'storage_aliyun_bucket','type'=>'text','cate_id'=>'20','extra'=>'','remark'=>'bucket','is_hide_remark'=>'0','default_value'=>'','sort'=>'5','status'=>'1','created_at'=>'1527032796','updated_at'=>'1560779477']);
        $this->insert('{{%common_config}}',['id'=>'48','title'=>'合作者身份','name'=>'alipay_appid','type'=>'text','cate_id'=>'8','extra'=>'','remark'=>'支付宝签约用户请在此处填写支付宝分配给您的合作者身份，签约用户的手续费按照您与支付宝官方的签约协议为准。如果您还未签约，<a href=\"https://memberprod.alipay.com/account/reg/enterpriseIndex.htm\" target=\"_blank\">请点击这里签约</a>；如果已签约,<a href=\"https://b.alipay.com/order/pidKey.htm?pid=2088501719138773&product=fastpay\" target=\"_blank\">请点击这里获取PID、Key</a>;如果在签约时出现合同模板冲突，请咨询0571-88158090','is_hide_remark'=>'0','default_value'=>'','sort'=>'0','status'=>'1','created_at'=>'1527668387','updated_at'=>'1545643402']);
        $this->insert('{{%common_config}}',['id'=>'49','title'=>'收款账号','name'=>'alipay_account','type'=>'text','cate_id'=>'8','extra'=>'','remark'=>'如果开启兑换或交易功能，请填写真实有效的支付宝账号。如账号无效或安全码有误，将导致用户支付后无法正确进行正常的交易。 如您没有支付宝帐号，<a href=\"https://memberprod.alipay.com/account/reg/enterpriseIndex.htm\" target=\"_blank\">请点击这里注册</a>','is_hide_remark'=>'0','default_value'=>'','sort'=>'0','status'=>'1','created_at'=>'1527668428','updated_at'=>'1560332578']);
        $this->insert('{{%common_config}}',['id'=>'50','title'=>'证书公钥','name'=>'alipay_cert_path','type'=>'text','cate_id'=>'8','extra'=>'','remark'=>'如需使用敏感接口(如退款等)需要配置 API 证书路径(请用支付宝生成工具生成公钥并上传),注意路径为绝对路径','is_hide_remark'=>'0','default_value'=>'','sort'=>'2','status'=>'1','created_at'=>'1526711138','updated_at'=>'1557817245']);
        $this->insert('{{%common_config}}',['id'=>'51','title'=>'证书私钥','name'=>'alipay_key_path','type'=>'text','cate_id'=>'8','extra'=>'','remark'=>'如需使用敏感接口(如退款等)需要配置 API 证书路径(请用支付宝生成工具生成私钥并上传),注意路径为绝对路径','is_hide_remark'=>'0','default_value'=>'','sort'=>'3','status'=>'1','created_at'=>'1526711237','updated_at'=>'1557817236']);
        $this->insert('{{%common_config}}',['id'=>'52','title'=>'开发模式','name'=>'sys_dev','type'=>'radioList','cate_id'=>'18','extra'=>'1:开启,
0:关闭,','remark'=>'开启后某些菜单功能可见，修改后请刷新页面','is_hide_remark'=>'0','default_value'=>'1','sort'=>'0','status'=>'1','created_at'=>'1529117534','updated_at'=>'1554041531']);
        $this->insert('{{%common_config}}',['id'=>'53','title'=>'水印状态','name'=>'sys_image_watermark_status','type'=>'radioList','cate_id'=>'23','extra'=>'1:开启,
0:关闭,','remark'=>'','is_hide_remark'=>'1','default_value'=>'0','sort'=>'1','status'=>'1','created_at'=>'1537949984','updated_at'=>'1553908904']);
        $this->insert('{{%common_config}}',['id'=>'54','title'=>'图片水印','name'=>'sys_image_watermark_img','type'=>'image','cate_id'=>'23','extra'=>'','remark'=>'如果图片尺寸小于水印尺寸则水印不生效','is_hide_remark'=>'0','default_value'=>'','sort'=>'2','status'=>'1','created_at'=>'1537950064','updated_at'=>'1553908911']);
        $this->insert('{{%common_config}}',['id'=>'55','title'=>'水印位置','name'=>'sys_image_watermark_location','type'=>'radioList','cate_id'=>'23','extra'=>'1:左上,
2:上中,
3:右上,
4:左中,
5:正中,
6:右中,
7:左下,
8:中下,
9:右下,','remark'=>'','is_hide_remark'=>'1','default_value'=>'1','sort'=>'2','status'=>'1','created_at'=>'1537951491','updated_at'=>'1553908889']);
        $this->insert('{{%common_config}}',['id'=>'56','title'=>'商户号','name'=>'union_mchid','type'=>'text','cate_id'=>'10','extra'=>'','remark'=>'','is_hide_remark'=>'1','default_value'=>'','sort'=>'0','status'=>'1','created_at'=>'1540003843','updated_at'=>'1545285072']);
        $this->insert('{{%common_config}}',['id'=>'57','title'=>'证书公钥','name'=>'union_cert_id','type'=>'text','cate_id'=>'10','extra'=>'','remark'=>'','is_hide_remark'=>'1','default_value'=>'','sort'=>'1','status'=>'1','created_at'=>'1540004005','updated_at'=>'1545285072']);
        $this->insert('{{%common_config}}',['id'=>'58','title'=>'证书秘钥','name'=>'union_private_key','type'=>'text','cate_id'=>'10','extra'=>'','remark'=>'','is_hide_remark'=>'1','default_value'=>'','sort'=>'2','status'=>'1','created_at'=>'1540004031','updated_at'=>'1540004031']);
        $this->insert('{{%common_config}}',['id'=>'59','title'=>'SEO关键字','name'=>'web_seo_keywords','type'=>'text','cate_id'=>'17','extra'=>'','remark'=>'','is_hide_remark'=>'1','default_value'=>'','sort'=>'2','status'=>'1','created_at'=>'1547087332','updated_at'=>'1553912628']);
        $this->insert('{{%common_config}}',['id'=>'60','title'=>'SEO内容','name'=>'web_seo_description','type'=>'textarea','cate_id'=>'17','extra'=>'','remark'=>'','is_hide_remark'=>'1','default_value'=>'','sort'=>'3','status'=>'1','created_at'=>'1547087379','updated_at'=>'1553912630']);
        $this->insert('{{%common_config}}',['id'=>'61','title'=>'后台 Tab 页签','name'=>'sys_tags','type'=>'radioList','cate_id'=>'18','extra'=>'1:开启,
0:关闭,','remark'=>'修改后请刷新页面','is_hide_remark'=>'0','default_value'=>'1','sort'=>'1','status'=>'1','created_at'=>'1547778279','updated_at'=>'1554041532']);
        $this->insert('{{%common_config}}',['id'=>'62','title'=>'App ID','name'=>'push_jpush_appid','type'=>'text','cate_id'=>'25','extra'=>'','remark'=>'','is_hide_remark'=>'1','default_value'=>'','sort'=>'0','status'=>'1','created_at'=>'1548405209','updated_at'=>'1553908864']);
        $this->insert('{{%common_config}}',['id'=>'63','title'=>'App Secret','name'=>'push_jpush_app_secret','type'=>'text','cate_id'=>'25','extra'=>'','remark'=>'','is_hide_remark'=>'1','default_value'=>'','sort'=>'1','status'=>'1','created_at'=>'1548405244','updated_at'=>'1553908854']);
        $this->insert('{{%common_config}}',['id'=>'64','title'=>'后台相关链接','name'=>'sys_related_links','type'=>'radioList','cate_id'=>'18','extra'=>'1:显示,
0:隐藏,','remark'=>'','is_hide_remark'=>'1','default_value'=>'0','sort'=>'2','status'=>'1','created_at'=>'1554041616','updated_at'=>'1557213534']);
        $this->insert('{{%common_config}}',['id'=>'65','title'=>'内网端口','name'=>'storage_aliyun_endpoint_internal','type'=>'text','cate_id'=>'20','extra'=>'','remark'=>'','is_hide_remark'=>'1','default_value'=>'','sort'=>'3','status'=>'1','created_at'=>'1554800824','updated_at'=>'1554870891']);
        $this->insert('{{%common_config}}',['id'=>'66','title'=>'内网上传','name'=>'storage_aliyun_is_internal','type'=>'radioList','cate_id'=>'20','extra'=>'1:开启,
0:关闭,','remark'=>'','is_hide_remark'=>'1','default_value'=>'0','sort'=>'6','status'=>'1','created_at'=>'1554870877','updated_at'=>'1560779479']);
        $this->insert('{{%common_config}}',['id'=>'67','title'=>'支付宝公钥','name'=>'alipay_notification_cert_path','type'=>'text','cate_id'=>'8','extra'=>'','remark'=>'回调验证签名必须','is_hide_remark'=>'0','default_value'=>'','sort'=>'4','status'=>'1','created_at'=>'1557815671','updated_at'=>'1557815671']);
        $this->insert('{{%common_config}}',['id'=>'68','title'=>'accessKeyId','name'=>'sms_aliyun_accesskeyid','type'=>'text','cate_id'=>'28','extra'=>'','remark'=>'','is_hide_remark'=>'1','default_value'=>'','sort'=>'0','status'=>'1','created_at'=>'1559260691','updated_at'=>'1559260741']);
        $this->insert('{{%common_config}}',['id'=>'69','title'=>'accessSecret','name'=>'sms_aliyun_accesskeysecret','type'=>'text','cate_id'=>'28','extra'=>'','remark'=>'','is_hide_remark'=>'1','default_value'=>'','sort'=>'1','status'=>'1','created_at'=>'1559260724','updated_at'=>'1559260854']);
        $this->insert('{{%common_config}}',['id'=>'70','title'=>'签名','name'=>'sms_aliyun_sign_name','type'=>'text','cate_id'=>'28','extra'=>'','remark'=>'','is_hide_remark'=>'1','default_value'=>'','sort'=>'2','status'=>'1','created_at'=>'1559260809','updated_at'=>'1559260855']);
        $this->insert('{{%common_config}}',['id'=>'71','title'=>'模版','name'=>'sms_aliyun_template','type'=>'multipleInput','cate_id'=>'28','extra'=>'group:组别,
template:模版ID,','remark'=>'','is_hide_remark'=>'1','default_value'=>'','sort'=>'3','status'=>'1','created_at'=>'1559260837','updated_at'=>'1559384533']);
        $this->insert('{{%common_config}}',['id'=>'72','title'=>'ak','name'=>'map_baidu_ak','type'=>'text','cate_id'=>'30','extra'=>'','remark'=>'开发者中心：<a href=\"http://lbsyun.baidu.com/\" target=\"_blank\">立即申请</a>','is_hide_remark'=>'0','default_value'=>'','sort'=>'0','status'=>'1','created_at'=>'1559402573','updated_at'=>'1559417197']);
        $this->insert('{{%common_config}}',['id'=>'73','title'=>'key','name'=>'map_tencent_key','type'=>'text','cate_id'=>'31','extra'=>'','remark'=>'开发者中心：<a href=\"https://lbs.qq.com/\" target=\"_blank\">立即申请</a>','is_hide_remark'=>'0','default_value'=>'','sort'=>'0','status'=>'1','created_at'=>'1559402617','updated_at'=>'1559417326']);
        $this->insert('{{%common_config}}',['id'=>'74','title'=>'key','name'=>'map_amap_key','type'=>'text','cate_id'=>'32','extra'=>'','remark'=>'开发者中心: <a href=\"https://lbs.amap.com/dev/key/app\" target=\"_blank\">立即申请</a>，申请类型为web端(JS API)','is_hide_remark'=>'0','default_value'=>'','sort'=>'0','status'=>'1','created_at'=>'1559402658','updated_at'=>'1559417302']);
        $this->insert('{{%common_config}}',['id'=>'75','title'=>'accessKeyId','name'=>'storage_cos_accesskey','type'=>'text','cate_id'=>'33','extra'=>'','remark'=>'','is_hide_remark'=>'1','default_value'=>'','sort'=>'1','status'=>'1','created_at'=>'1559528032','updated_at'=>'1559528076']);
        $this->insert('{{%common_config}}',['id'=>'76','title'=>'accessSecret','name'=>'storage_cos_secrectkey','type'=>'text','cate_id'=>'33','extra'=>'','remark'=>'','is_hide_remark'=>'1','default_value'=>'','sort'=>'2','status'=>'1','created_at'=>'1559528052','updated_at'=>'1559528071']);
        $this->insert('{{%common_config}}',['id'=>'77','title'=>'appID','name'=>'storage_cos_appid','type'=>'text','cate_id'=>'33','extra'=>'','remark'=>'域名中数字部分','is_hide_remark'=>'0','default_value'=>'','sort'=>'0','status'=>'1','created_at'=>'1559528110','updated_at'=>'1559528110']);
        $this->insert('{{%common_config}}',['id'=>'78','title'=>'所属区域','name'=>'storage_cos_region','type'=>'text','cate_id'=>'33','extra'=>'','remark'=>'区域，例如：ap-guangzhou','is_hide_remark'=>'0','default_value'=>'','sort'=>'3','status'=>'1','created_at'=>'1559528187','updated_at'=>'1559528541']);
        $this->insert('{{%common_config}}',['id'=>'79','title'=>'空间名','name'=>'storage_cos_bucket','type'=>'text','cate_id'=>'33','extra'=>'','remark'=>'bucket','is_hide_remark'=>'0','default_value'=>'','sort'=>'4','status'=>'1','created_at'=>'1559528248','updated_at'=>'1559528248']);
        $this->insert('{{%common_config}}',['id'=>'80','title'=>'cdn','name'=>'storage_cos_cdn','type'=>'text','cate_id'=>'33','extra'=>'','remark'=>'','is_hide_remark'=>'1','default_value'=>'','sort'=>'5','status'=>'1','created_at'=>'1559528366','updated_at'=>'1559528366']);
        $this->insert('{{%common_config}}',['id'=>'81','title'=>'cdn可读','name'=>'read_from_cdn','type'=>'radioList','cate_id'=>'33','extra'=>'0:否,
1:是,','remark'=>'','is_hide_remark'=>'1','default_value'=>'0','sort'=>'6','status'=>'1','created_at'=>'1559528436','updated_at'=>'1559528482']);
        $this->insert('{{%common_config}}',['id'=>'82','title'=>'rsa_private','name'=>'oauth2_rsa_private','type'=>'text','cate_id'=>'35','extra'=>'','remark'=>'RSA私钥，注意权限为600/660','is_hide_remark'=>'0','default_value'=>'','sort'=>'1','status'=>'1','created_at'=>'1559705070','updated_at'=>'1559708918']);
        $this->insert('{{%common_config}}',['id'=>'83','title'=>'rsa_public','name'=>'oauth2_rsa_public','type'=>'text','cate_id'=>'35','extra'=>'','remark'=>'RSA私钥，注意权限为600/660','is_hide_remark'=>'0','default_value'=>'','sort'=>'0','status'=>'1','created_at'=>'1559705282','updated_at'=>'1559708917']);
        $this->insert('{{%common_config}}',['id'=>'84','title'=>'私钥文件加密','name'=>'oauth2_rsa_private_encryption','type'=>'radioList','cate_id'=>'35','extra'=>'1:是,
0:否,','remark'=>'如果加密请填写加密密码','is_hide_remark'=>'0','default_value'=>'0','sort'=>'2','status'=>'1','created_at'=>'1559705564','updated_at'=>'1559708918']);
        $this->insert('{{%common_config}}',['id'=>'85','title'=>'私钥加密密码','name'=>'oauth2_rsa_private_password','type'=>'password','cate_id'=>'35','extra'=>'','remark'=>'','is_hide_remark'=>'1','default_value'=>'','sort'=>'3','status'=>'1','created_at'=>'1559705610','updated_at'=>'1559705838']);
        $this->insert('{{%common_config}}',['id'=>'86','title'=>'加密密钥字符串','name'=>'oauth2_encryption_key','type'=>'secretKeyText','cate_id'=>'35','extra'=>'32','remark'=>'','is_hide_remark'=>'1','default_value'=>'','sort'=>'4','status'=>'1','created_at'=>'1559705722','updated_at'=>'1559705739']);
        $this->insert('{{%common_config}}',['id'=>'87','title'=>'绑定域名','name'=>'storage_aliyun_user_url','type'=>'text','cate_id'=>'20','extra'=>'','remark'=>'填写后默认返回绑定域名前缀的链接','is_hide_remark'=>'0','default_value'=>'','sort'=>'4','status'=>'1','created_at'=>'1560779551','updated_at'=>'1560779551']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%common_config}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}


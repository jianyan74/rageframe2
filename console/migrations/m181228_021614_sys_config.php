<?php

use yii\db\Migration;

class m181228_021614_sys_config extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%sys_config}}', [
            'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT \'主键\'',
            'title' => 'varchar(50) NOT NULL DEFAULT \'\' COMMENT \'配置标题\'',
            'name' => 'varchar(30) NOT NULL DEFAULT \'\' COMMENT \'配置标识\'',
            'type' => 'varchar(30) NOT NULL DEFAULT \'\' COMMENT \'配置类型\'',
            'cate_id' => 'int(10) unsigned NOT NULL DEFAULT \'0\' COMMENT \'配置分类\'',
            'extra' => 'varchar(255) NOT NULL DEFAULT \'\' COMMENT \'配置值\'',
            'remark' => 'varchar(1000) NOT NULL DEFAULT \'\' COMMENT \'配置说明\'',
            'value' => 'text NULL COMMENT \'配置值\'',
            'is_hide_remark' => 'tinyint(4) NULL DEFAULT \'1\' COMMENT \'是否隐藏说明\'',
            'sort' => 'int(10) unsigned NOT NULL DEFAULT \'0\' COMMENT \'排序\'',
            'status' => 'tinyint(4) NOT NULL DEFAULT \'1\' COMMENT \'状态[-1:删除;0:禁用;1启用]\'',
            'created_at' => 'int(10) unsigned NOT NULL DEFAULT \'0\' COMMENT \'创建时间\'',
            'updated_at' => 'int(10) unsigned NOT NULL DEFAULT \'0\' COMMENT \'修改时间\'',
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COMMENT='系统_公用配置表'");
        
        /* 索引设置 */
        $this->createIndex('uk_name','{{%sys_config}}','name',1);
        $this->createIndex('type','{{%sys_config}}','type',0);
        $this->createIndex('group','{{%sys_config}}','cate_id',0);
        
        
        /* 表数据 */
        $this->insert('{{%sys_config}}',['id'=>'1','title'=>'版权所有','name'=>'web_copyright','type'=>'text','cate_id'=>'17','extra'=>'','remark'=>'','value'=>'© 2016 - 2019 RageFrame All Rights Reserved.','is_hide_remark'=>'1','sort'=>'9','status'=>'1','created_at'=>'1526199058','updated_at'=>'1545277741']);
        $this->insert('{{%sys_config}}',['id'=>'2','title'=>'网站标题','name'=>'web_site_title','type'=>'text','cate_id'=>'17','extra'=>'','remark'=>'前台显示站点名称','value'=>'RageFrame应用开发引擎','is_hide_remark'=>'0','sort'=>'0','status'=>'1','created_at'=>'1526372845','updated_at'=>'1533200734']);
        $this->insert('{{%sys_config}}',['id'=>'3','title'=>'网站logo','name'=>'web_logo','type'=>'image','cate_id'=>'17','extra'=>'','remark'=>'','value'=>'','is_hide_remark'=>'1','sort'=>'1','status'=>'1','created_at'=>'1526372885','updated_at'=>'1529587467']);
        $this->insert('{{%sys_config}}',['id'=>'4','title'=>'备案号','name'=>'web_site_icp','type'=>'text','cate_id'=>'17','extra'=>'','remark'=>'','value'=>'','is_hide_remark'=>'1','sort'=>'2','status'=>'1','created_at'=>'1526372926','updated_at'=>'1526717240']);
        $this->insert('{{%sys_config}}',['id'=>'5','title'=>'网站访问量统计代码','name'=>'web_visit_code','type'=>'textarea','cate_id'=>'17','extra'=>'','remark'=>'','value'=>'','is_hide_remark'=>'1','sort'=>'11','status'=>'1','created_at'=>'1526373044','updated_at'=>'1526400608']);
        $this->insert('{{%sys_config}}',['id'=>'6','title'=>'百度自动推送代码','name'=>'web_baidu_push','type'=>'textarea','cate_id'=>'17','extra'=>'','remark'=>'','value'=>'','is_hide_remark'=>'1','sort'=>'10','status'=>'1','created_at'=>'1526373086','updated_at'=>'1526717240']);
        $this->insert('{{%sys_config}}',['id'=>'7','title'=>'后台默认分页','name'=>'sys_page','type'=>'dropDownList','cate_id'=>'18','extra'=>'10:10;
20:20;
30:30;','remark'=>'','value'=>'10','is_hide_remark'=>'1','sort'=>'0','status'=>'1','created_at'=>'1526374033','updated_at'=>'1526400321']);
        $this->insert('{{%sys_config}}',['id'=>'8','title'=>'后台允许访问IP','name'=>'sys_allow_ip','type'=>'textarea','cate_id'=>'18','extra'=>'','remark'=>'多个用逗号分隔，如果不配置表示不限制IP访问','value'=>'','is_hide_remark'=>'0','sort'=>'6','status'=>'1','created_at'=>'1526374098','updated_at'=>'1537951409']);
        $this->insert('{{%sys_config}}',['id'=>'9','title'=>'公众号帐号','name'=>'wechat_account','type'=>'text','cate_id'=>'19','extra'=>'','remark'=>'填写公众号的帐号，一般为英文帐号','value'=>'','is_hide_remark'=>'0','sort'=>'0','status'=>'1','created_at'=>'1526374732','updated_at'=>'1526376340']);
        $this->insert('{{%sys_config}}',['id'=>'10','title'=>'原始ID','name'=>'wechat_id','type'=>'text','cate_id'=>'19','extra'=>'','remark'=>'在给粉丝发送客服消息时,原始ID不能为空。建议您完善该选项','value'=>'','is_hide_remark'=>'0','sort'=>'1','status'=>'1','created_at'=>'1526374769','updated_at'=>'1536050694']);
        $this->insert('{{%sys_config}}',['id'=>'11','title'=>'级别','name'=>'wechat_rank','type'=>'radioList','cate_id'=>'19','extra'=>'1:普通订阅号,
2:普通服务号,
3:认证订阅号,
4:认证服务号/认证媒体/政府订阅号','remark'=>'注意：即使公众平台显示为“未认证”, 但只要【公众号设置】/【账号详情】下【认证情况】显示资质审核通过, 即可认定为认证号.','value'=>'4','is_hide_remark'=>'0','sort'=>'2','status'=>'1','created_at'=>'1526374841','updated_at'=>'1526376345']);
        $this->insert('{{%sys_config}}',['id'=>'12','title'=>'App ID','name'=>'wechat_appid','type'=>'text','cate_id'=>'19','extra'=>'','remark'=>'请填写微信公众平台后台的AppId','value'=>'','is_hide_remark'=>'0','sort'=>'3','status'=>'1','created_at'=>'1526376099','updated_at'=>'1539928226']);
        $this->insert('{{%sys_config}}',['id'=>'13','title'=>'App Secret','name'=>'wechat_appsecret','type'=>'text','cate_id'=>'19','extra'=>'','remark'=>'请填写微信公众平台后台的AppSecret, 只有填写这两项才能管理自定义菜单','value'=>'','is_hide_remark'=>'0','sort'=>'4','status'=>'1','created_at'=>'1526376188','updated_at'=>'1539928226']);
        $this->insert('{{%sys_config}}',['id'=>'14','title'=>'Token','name'=>'wechat_token','type'=>'secretKeyText','cate_id'=>'19','extra'=>'32','remark'=>'与公众平台接入设置值一致，必须为英文或者数字，长度为3到32个字符. 请妥善保管, Token 泄露将可能被窃取或篡改平台的操作数据','value'=>'','is_hide_remark'=>'0','sort'=>'5','status'=>'1','created_at'=>'1526376249','updated_at'=>'1539134202']);
        $this->insert('{{%sys_config}}',['id'=>'15','title'=>'EncodingAESKey','name'=>'wechat_encodingaeskey','type'=>'text','cate_id'=>'19','extra'=>'','remark'=>'与公众平台接入设置值一致，必须为英文或者数字，长度为43个字符. 请妥善保管, EncodingAESKey 泄露将可能被窃取或篡改平台的操作数据','value'=>'','is_hide_remark'=>'0','sort'=>'6','status'=>'1','created_at'=>'1526376295','updated_at'=>'1526376340']);
        $this->insert('{{%sys_config}}',['id'=>'16','title'=>'微信关注二维码','name'=>'wechat_qrcode','type'=>'image','cate_id'=>'19','extra'=>'','remark'=>'','value'=>NULL,'is_hide_remark'=>'1','sort'=>'7','status'=>'1','created_at'=>'1526376328','updated_at'=>'1526376393']);
        $this->insert('{{%sys_config}}',['id'=>'17','title'=>'分享标题','name'=>'wechat_share_title','type'=>'text','cate_id'=>'22','extra'=>'','remark'=>'','value'=>'','is_hide_remark'=>'1','sort'=>'0','status'=>'1','created_at'=>'1526376418','updated_at'=>'1526717264']);
        $this->insert('{{%sys_config}}',['id'=>'18','title'=>'分享详情','name'=>'wechat_share_details','type'=>'textarea','cate_id'=>'22','extra'=>'','remark'=>'','value'=>'','is_hide_remark'=>'1','sort'=>'1','status'=>'0','created_at'=>'1526376464','updated_at'=>'1543138253']);
        $this->insert('{{%sys_config}}',['id'=>'19','title'=>'分享图片','name'=>'wechat_share_pic','type'=>'image','cate_id'=>'22','extra'=>'','remark'=>'','value'=>NULL,'is_hide_remark'=>'1','sort'=>'2','status'=>'1','created_at'=>'1526376489','updated_at'=>'1526376489']);
        $this->insert('{{%sys_config}}',['id'=>'20','title'=>'分享链接','name'=>'wechat_share_url','type'=>'text','cate_id'=>'22','extra'=>'','remark'=>'','value'=>'','is_hide_remark'=>'1','sort'=>'3','status'=>'1','created_at'=>'1526376520','updated_at'=>'1526717264']);
        $this->insert('{{%sys_config}}',['id'=>'22','title'=>'扩展模块菜单','name'=>'sys_addon_show','type'=>'radioList','cate_id'=>'18','extra'=>'1:开启,
0:关闭,','remark'=>'顶部菜单入口显示，修改后请刷新页面','value'=>'1','is_hide_remark'=>'0','sort'=>'5','status'=>'1','created_at'=>'1526400242','updated_at'=>'1542961236']);
        $this->insert('{{%sys_config}}',['id'=>'23','title'=>'App ID','name'=>'login_qq_appid','type'=>'text','cate_id'=>'11','extra'=>'','remark'=>'','value'=>NULL,'is_hide_remark'=>'1','sort'=>'0','status'=>'1','created_at'=>'1526438194','updated_at'=>'1526438194']);
        $this->insert('{{%sys_config}}',['id'=>'24','title'=>'App Secret','name'=>'login_qq_app_secret','type'=>'text','cate_id'=>'11','extra'=>'','remark'=>'','value'=>NULL,'is_hide_remark'=>'1','sort'=>'1','status'=>'1','created_at'=>'1526438237','updated_at'=>'1526438237']);
        $this->insert('{{%sys_config}}',['id'=>'25','title'=>'App ID','name'=>'login_sina_appid','type'=>'text','cate_id'=>'12','extra'=>'','remark'=>'','value'=>'','is_hide_remark'=>'1','sort'=>'0','status'=>'1','created_at'=>'1526438194','updated_at'=>'1526438194']);
        $this->insert('{{%sys_config}}',['id'=>'26','title'=>'App Secret','name'=>'login_sing_app_secret','type'=>'text','cate_id'=>'12','extra'=>'','remark'=>'','value'=>'','is_hide_remark'=>'1','sort'=>'1','status'=>'1','created_at'=>'1526438237','updated_at'=>'1526438237']);
        $this->insert('{{%sys_config}}',['id'=>'27','title'=>'App Secret','name'=>'login_wechat_app_secret','type'=>'text','cate_id'=>'13','extra'=>'','remark'=>'','value'=>'','is_hide_remark'=>'1','sort'=>'1','status'=>'1','created_at'=>'1526438237','updated_at'=>'1526438237']);
        $this->insert('{{%sys_config}}',['id'=>'28','title'=>'App ID','name'=>'login_wechat_appid','type'=>'text','cate_id'=>'13','extra'=>'','remark'=>'','value'=>'','is_hide_remark'=>'1','sort'=>'0','status'=>'1','created_at'=>'1526438194','updated_at'=>'1526438194']);
        $this->insert('{{%sys_config}}',['id'=>'29','title'=>'App ID','name'=>'login_github_appid','type'=>'text','cate_id'=>'14','extra'=>'','remark'=>'','value'=>'','is_hide_remark'=>'1','sort'=>'0','status'=>'1','created_at'=>'1526438194','updated_at'=>'1526438194']);
        $this->insert('{{%sys_config}}',['id'=>'30','title'=>'App Secret','name'=>'login_github_app_secret','type'=>'text','cate_id'=>'14','extra'=>'','remark'=>'','value'=>'','is_hide_remark'=>'1','sort'=>'1','status'=>'1','created_at'=>'1526438237','updated_at'=>'1526438237']);
        $this->insert('{{%sys_config}}',['id'=>'31','title'=>'SMTP服务器','name'=>'smtp_host','type'=>'text','cate_id'=>'16','extra'=>'','remark'=>'例如:smtp.163.com','value'=>'smtp.163.com','is_hide_remark'=>'0','sort'=>'0','status'=>'1','created_at'=>'1526438237','updated_at'=>'1544060631']);
        $this->insert('{{%sys_config}}',['id'=>'32','title'=>'SMTP帐号','name'=>'smtp_username','type'=>'text','cate_id'=>'16','extra'=>'','remark'=>'例如:mobile@163.com','value'=>'','is_hide_remark'=>'0','sort'=>'1','status'=>'1','created_at'=>'1526438237','updated_at'=>'1544060631']);
        $this->insert('{{%sys_config}}',['id'=>'33','title'=>'SMTP客户端授权码','name'=>'smtp_password','type'=>'password','cate_id'=>'16','extra'=>'','remark'=>'如果是163邮箱，此处要填授权码','value'=>'','is_hide_remark'=>'0','sort'=>'2','status'=>'1','created_at'=>'1526438237','updated_at'=>'1544060734']);
        $this->insert('{{%sys_config}}',['id'=>'34','title'=>'SMTP端口','name'=>'smtp_port','type'=>'text','cate_id'=>'16','extra'=>'','remark'=>'25、109、110、143、465、995、993','value'=>'25','is_hide_remark'=>'0','sort'=>'4','status'=>'1','created_at'=>'1526438237','updated_at'=>'1544060631']);
        $this->insert('{{%sys_config}}',['id'=>'35','title'=>'发件人名称','name'=>'smtp_name','type'=>'text','cate_id'=>'16','extra'=>'','remark'=>'例如:RageFrame','value'=>'rageframe','is_hide_remark'=>'0','sort'=>'5','status'=>'1','created_at'=>'1526438237','updated_at'=>'1544060631']);
        $this->insert('{{%sys_config}}',['id'=>'36','title'=>'使用SSL加密','name'=>'smtp_encryption','type'=>'radioList','cate_id'=>'16','extra'=>'0:关闭;
1:开启;','remark'=>'开启此项后，连接将用SSL的形式，此项需要SMTP服务器支持','value'=>'0','is_hide_remark'=>'0','sort'=>'3','status'=>'1','created_at'=>'1526438237','updated_at'=>'1526439059']);
        $this->insert('{{%sys_config}}',['id'=>'37','title'=>'商户号','name'=>'wechat_mchid','type'=>'text','cate_id'=>'9','extra'=>'','remark'=>'公众号支付的商户账号','value'=>'','is_hide_remark'=>'0','sort'=>'0','status'=>'1','created_at'=>'1526710975','updated_at'=>'1545285079']);
        $this->insert('{{%sys_config}}',['id'=>'38','title'=>'支付密钥','name'=>'wechat_api_key','type'=>'secretKeyText','cate_id'=>'9','extra'=>'32','remark'=>'','value'=>'','is_hide_remark'=>'1','sort'=>'1','status'=>'1','created_at'=>'1526711047','updated_at'=>'1545285079']);
        $this->insert('{{%sys_config}}',['id'=>'39','title'=>'证书公钥','name'=>'wechat_cert_path','type'=>'text','cate_id'=>'9','extra'=>'','remark'=>'如需使用敏感接口(如退款、发送红包等)需要配置 API 证书路径(登录商户平台下载 API 证书),注意路径为绝对路径','value'=>'','is_hide_remark'=>'0','sort'=>'2','status'=>'1','created_at'=>'1526711138','updated_at'=>'1545285079']);
        $this->insert('{{%sys_config}}',['id'=>'40','title'=>'证书私钥','name'=>'wechat_key_path','type'=>'text','cate_id'=>'9','extra'=>'','remark'=>'如需使用敏感接口(如退款、发送红包等)需要配置 API 证书路径(登录商户平台下载 API 证书),注意路径为绝对路径','value'=>'','is_hide_remark'=>'0','sort'=>'3','status'=>'1','created_at'=>'1526711237','updated_at'=>'1545285077']);
        $this->insert('{{%sys_config}}',['id'=>'41','title'=>'App ID','name'=>'miniprogram_appid','type'=>'text','cate_id'=>'25','extra'=>'','remark'=>'','value'=>'','is_hide_remark'=>'1','sort'=>'0','status'=>'1','created_at'=>'1526711433','updated_at'=>'1539653447']);
        $this->insert('{{%sys_config}}',['id'=>'42','title'=>'App Secret','name'=>'miniprogram_secret','type'=>'text','cate_id'=>'25','extra'=>'','remark'=>'','value'=>'','is_hide_remark'=>'1','sort'=>'1','status'=>'1','created_at'=>'1526711464','updated_at'=>'1539653447']);
        $this->insert('{{%sys_config}}',['id'=>'43','title'=>'accessKeyId','name'=>'aliyun_live_access_key_id','type'=>'text','cate_id'=>'27','extra'=>'','remark'=>'','value'=>'','is_hide_remark'=>'1','sort'=>'0','status'=>'1','created_at'=>'1526870007','updated_at'=>'1526873477']);
        $this->insert('{{%sys_config}}',['id'=>'44','title'=>'accessSecret','name'=>'aliyun_live_access_secret','type'=>'text','cate_id'=>'27','extra'=>'','remark'=>'','value'=>'','is_hide_remark'=>'1','sort'=>'1','status'=>'1','created_at'=>'1526870037','updated_at'=>'1526873477']);
        $this->insert('{{%sys_config}}',['id'=>'46','title'=>'播放域名','name'=>'aliyun_live_domain','type'=>'text','cate_id'=>'27','extra'=>'','remark'=>'domain','value'=>'','is_hide_remark'=>'0','sort'=>'3','status'=>'1','created_at'=>'1526870102','updated_at'=>'1526873477']);
        $this->insert('{{%sys_config}}',['id'=>'47','title'=>'推流鉴权KEY','name'=>'aliyun_live_push_auth','type'=>'text','cate_id'=>'27','extra'=>'','remark'=>'pushAuth','value'=>'','is_hide_remark'=>'0','sort'=>'4','status'=>'1','created_at'=>'1526870125','updated_at'=>'1526873477']);
        $this->insert('{{%sys_config}}',['id'=>'48','title'=>'用户凭证','name'=>'storage_qiniu_accesskey','type'=>'text','cate_id'=>'15','extra'=>'','remark'=>'ak','value'=>'','is_hide_remark'=>'0','sort'=>'0','status'=>'1','created_at'=>'1527032360','updated_at'=>'1543397135']);
        $this->insert('{{%sys_config}}',['id'=>'49','title'=>'签名密钥','name'=>'storage_qiniu_secrectkey','type'=>'text','cate_id'=>'15','extra'=>'','remark'=>'sk','value'=>'','is_hide_remark'=>'0','sort'=>'1','status'=>'1','created_at'=>'1527032390','updated_at'=>'1543397135']);
        $this->insert('{{%sys_config}}',['id'=>'50','title'=>'域名','name'=>'storage_qiniu_domain','type'=>'text','cate_id'=>'15','extra'=>'','remark'=>'domain','value'=>'','is_hide_remark'=>'0','sort'=>'2','status'=>'1','created_at'=>'1527032506','updated_at'=>'1543397135']);
        $this->insert('{{%sys_config}}',['id'=>'51','title'=>'空间名','name'=>'storage_qiniu_bucket','type'=>'text','cate_id'=>'15','extra'=>'','remark'=>'七牛的后台管理页面自己创建的空间名','value'=>'','is_hide_remark'=>'0','sort'=>'3','status'=>'1','created_at'=>'1527032578','updated_at'=>'1543397135']);
        $this->insert('{{%sys_config}}',['id'=>'52','title'=>'accessKeyId','name'=>'storage_aliyun_accesskeyid','type'=>'text','cate_id'=>'21','extra'=>'','remark'=>'','value'=>'','is_hide_remark'=>'1','sort'=>'0','status'=>'1','created_at'=>'1527032713','updated_at'=>'1543397135']);
        $this->insert('{{%sys_config}}',['id'=>'53','title'=>'accessSecret','name'=>'storage_aliyun_accesskeysecret','type'=>'text','cate_id'=>'21','extra'=>'','remark'=>'','value'=>'','is_hide_remark'=>'1','sort'=>'1','status'=>'1','created_at'=>'1527032738','updated_at'=>'1543397135']);
        $this->insert('{{%sys_config}}',['id'=>'54','title'=>'EndPoint','name'=>'storage_aliyun_endpoint','type'=>'text','cate_id'=>'21','extra'=>'','remark'=>'接收消息的终端地址 例如:cn-hangzhou.log.aliyuncs.com','value'=>'','is_hide_remark'=>'0','sort'=>'2','status'=>'1','created_at'=>'1527032773','updated_at'=>'1543397826']);
        $this->insert('{{%sys_config}}',['id'=>'55','title'=>'空间名','name'=>'storage_aliyun_bucket','type'=>'text','cate_id'=>'21','extra'=>'','remark'=>'bucket','value'=>'rageframe','is_hide_remark'=>'0','sort'=>'3','status'=>'1','created_at'=>'1527032796','updated_at'=>'1543397826']);
        $this->insert('{{%sys_config}}',['id'=>'56','title'=>'合作者身份','name'=>'alipay_appid','type'=>'text','cate_id'=>'8','extra'=>'','remark'=>'支付宝签约用户请在此处填写支付宝分配给您的合作者身份，签约用户的手续费按照您与支付宝官方的签约协议为准。如果您还未签约，<a href=\"https://memberprod.alipay.com/account/reg/enterpriseIndex.htm\" target=\"_blank\">请点击这里签约</a>；如果已签约,<a href=\"https://b.alipay.com/order/pidKey.htm?pid=2088501719138773&product=fastpay\" target=\"_blank\">请点击这里获取PID、Key</a>;如果在签约时出现合同模板冲突，请咨询0571-88158090','value'=>'','is_hide_remark'=>'0','sort'=>'0','status'=>'1','created_at'=>'1527668387','updated_at'=>'1545643402']);
        $this->insert('{{%sys_config}}',['id'=>'57','title'=>'收款账号','name'=>'alipay_account','type'=>'text','cate_id'=>'8','extra'=>'','remark'=>'如果开启兑换或交易功能，请填写真实有效的支付宝账号。如账号无效或安全码有误，将导致用户支付后无法正确进行正常的交易。 如您没有支付宝帐号，<a href=\"https://memberprod.alipay.com/account/reg/enterpriseIndex.htm\" target=\"_blank\">请点击这里注册</a>','value'=>'','is_hide_remark'=>'0','sort'=>'1','status'=>'1','created_at'=>'1527668428','updated_at'=>'1545285078']);
        $this->insert('{{%sys_config}}',['id'=>'58','title'=>'证书公钥','name'=>'alipay_cert_path','type'=>'text','cate_id'=>'8','extra'=>'','remark'=>'如需使用敏感接口(如退款、发送红包等)需要配置 API 证书路径(登录商户平台下载 API 证书),注意路径为绝对路径','value'=>'','is_hide_remark'=>'0','sort'=>'2','status'=>'1','created_at'=>'1526711138','updated_at'=>'1545285078']);
        $this->insert('{{%sys_config}}',['id'=>'59','title'=>'证书私钥','name'=>'alipay_key_path','type'=>'text','cate_id'=>'8','extra'=>'','remark'=>'如需使用敏感接口(如退款、发送红包等)需要配置 API 证书路径(登录商户平台下载 API 证书),注意路径为绝对路径','value'=>'','is_hide_remark'=>'0','sort'=>'3','status'=>'1','created_at'=>'1526711237','updated_at'=>'1545285080']);
        $this->insert('{{%sys_config}}',['id'=>'60','title'=>'开发模式','name'=>'sys_dev','type'=>'radioList','cate_id'=>'18','extra'=>'1:开启,
0:关闭,','remark'=>'开启后某些菜单功能可见，修改后请刷新页面','value'=>'1','is_hide_remark'=>'0','sort'=>'4','status'=>'1','created_at'=>'1529117534','updated_at'=>'1542961236']);
        $this->insert('{{%sys_config}}',['id'=>'64','title'=>'水印状态','name'=>'sys_image_watermark_status','type'=>'radioList','cate_id'=>'30','extra'=>'1:开启,
0:关闭,','remark'=>'','value'=>'0','is_hide_remark'=>'1','sort'=>'1','status'=>'1','created_at'=>'1537949984','updated_at'=>'1545273956']);
        $this->insert('{{%sys_config}}',['id'=>'65','title'=>'图片水印','name'=>'sys_image_watermark_img','type'=>'image','cate_id'=>'30','extra'=>'','remark'=>'如果图片尺寸小于水印尺寸则水印不生效','value'=>'','is_hide_remark'=>'0','sort'=>'2','status'=>'1','created_at'=>'1537950064','updated_at'=>'1543631145']);
        $this->insert('{{%sys_config}}',['id'=>'66','title'=>'水印位置','name'=>'sys_image_watermark_location','type'=>'radioList','cate_id'=>'30','extra'=>'1:左上,
2:上中,
3:右上,
4:左中,
5:正中,
6:右中,
7:左下,
8:中下,
9:右下,','remark'=>'','value'=>'1','is_hide_remark'=>'1','sort'=>'2','status'=>'1','created_at'=>'1537951491','updated_at'=>'1545273956']);
        $this->insert('{{%sys_config}}',['id'=>'67','title'=>'商户号','name'=>'union_mchid','type'=>'text','cate_id'=>'10','extra'=>'','remark'=>'','value'=>NULL,'is_hide_remark'=>'1','sort'=>'0','status'=>'1','created_at'=>'1540003843','updated_at'=>'1545285072']);
        $this->insert('{{%sys_config}}',['id'=>'68','title'=>'证书公钥','name'=>'union_cert_id','type'=>'text','cate_id'=>'10','extra'=>'','remark'=>'','value'=>NULL,'is_hide_remark'=>'1','sort'=>'1','status'=>'1','created_at'=>'1540004005','updated_at'=>'1545285072']);
        $this->insert('{{%sys_config}}',['id'=>'69','title'=>'证书秘钥','name'=>'union_private_key','type'=>'text','cate_id'=>'10','extra'=>'','remark'=>'','value'=>NULL,'is_hide_remark'=>'1','sort'=>'2','status'=>'1','created_at'=>'1540004031','updated_at'=>'1540004031']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%sys_config}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}


## 数据字典

### api_授权秘钥表 : rf_api_access_token
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | bigint(20) unsigned | 否 | | 无
refresh_token | varchar(60) | 是 | | 刷新令牌
access_token | varchar(60) | 是 | | 授权令牌
member_id | bigint(20) unsigned | 是 | 0 | 关联的用户id
group | varchar(30) | 是 | | 组别
allowance | int(10) | 否 | 0 | 规定时间可获取次数
allowance_updated_at | int(10) | 否 | 0 | 最后一次提交时间
status | tinyint(4) | 否 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) | 否 | 0 | 创建时间
updated_at | int(10) | 是 | 0 | 修改时间

### api_接口日志 : rf_common_log
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(11) | 否 | | 无
member_id | int(11) | 是 | 0 | 用户id
method | varchar(20) | 是 | | 提交类型
module | varchar(50) | 是 | | 模块
controller | varchar(50) | 是 | | 控制器
action | varchar(50) | 是 | | 方法
url | varchar(1000) | 是 | | 提交url
get_data | text | 是 | | get数据
post_data | longtext | 是 | | post数据
ip | varchar(16) | 是 | | ip地址
error_code | int(10) | 是 | 0 | 报错code
error_msg | varchar(200) | 是 | | 报错信息
error_data | longtext | 是 | | 报错日志
req_id | varchar(50) | 是 | | 对外id
status | tinyint(4) | 否 | 1 | 状态(-1:已删除,0:禁用,1:正常)
created_at | int(10) | 是 | 0 | 创建时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

### 系统_支付日志 : rf_common_pay_log
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) | 否 | | 主键
order_sn | varchar(20) | 是 | | 关联订单号
order_group | tinyint(3) | 是 | 1 | 组别[默认统一支付类型]
openid | varchar(50) | 是 | | openid
mch_id | varchar(20) | 是 | | 商户支付账户
out_trade_no | varchar(32) | 是 | | 商户订单号
transaction_id | varchar(50) | 是 | | 微信订单号
total_fee | double(10,2) | 是 | 0 | 微信充值金额
fee_type | varchar(10) | 是 | | 标价币种
pay_type | tinyint(3) | 否 | 0 | 支付类型[1:微信;2:支付宝;3:银联]
pay_fee | double(10,2) | 否 | 0 | 支付金额
pay_status | tinyint(2) | 是 | 0 | 支付状态
pay_time | int(10) | 是 | 0 | 创建时间
trade_type | varchar(16) | 是 | | 交易类型，取值为：JSAPI，NATIVE，APP等
refund_sn | varchar(100) | 是 | | 退款编号
refund_fee | double(10,2) | 否 | 0 | 退款金额
is_refund | tinyint(1) | 是 | 0 | 退款情况[0:未退款;1已退款]
status | tinyint(1) | 是 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) | 是 | 0 | 创建时间
updated_at | int(10) | 是 | 0 | 修改时间

### 公用_省市区记录表 : rf_common_provinces
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) | 否 | | ID
title | varchar(50) | 否 | | 栏目名
pid | int(10) | 否 | 0 | 父栏目
short_title | varchar(50) | 是 | | 缩写
areacode | int(6) | 是 | 0 | 区域编码
zipcode | int(10) | 是 | 0 | 邮政编码
pinyin | varchar(100) | 是 | | 拼音
lng | varchar(20) | 是 | | 经度
lat | varchar(20) | 是 | | 纬度
level | tinyint(1) | 否 | 1 | 级别
position | varchar(255) | 否 | | 无
sort | tinyint(3) unsigned | 是 | 0 | 排序

### 系统_短信发送日志 : rf_common_sms_log
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(11) | 否 | | 无
member_id | int(11) | 是 | 0 | 用户id
mobile | varchar(20) | 是 | | 手机号码
content | varchar(1000) | 是 | | 内容
error_code | int(10) | 是 | 0 | 报错code
error_msg | varchar(200) | 是 | | 报错信息
error_data | longtext | 是 | | 报错日志
status | tinyint(4) | 否 | 1 | 状态(-1:已删除,0:禁用,1:正常)
created_at | int(10) | 是 | 0 | 创建时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

### 用户_收货地址表 : rf_member_address
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | bigint(20) | 否 | | 主键
member_id | int(11) | 是 | 0 | 用户id
provinces | int(10) | 是 | 0 | 省id
city | int(10) | 是 | 0 | 市id
area | int(10) | 是 | 0 | 区id
address_name | varchar(255) | 是 | | 地址
address_details | varchar(255) | 是 | | 详细地址
is_default | tinyint(1) | 是 | 0 | 默认地址
zip_code | int(10) | 是 | 0 | 邮编
realname | varchar(100) | 是 | | 真实姓名
tel | varchar(20) | 是 | | 家庭号码
mobile | varchar(20) | 是 | | 手机号码
status | tinyint(4) | 否 | 1 | 状态(-1:已删除,0:禁用,1:正常)
created_at | int(10) | 是 | 0 | 创建时间
updated_at | int(10) | 是 | 0 | 修改时间

### 用户_第三方登录 : rf_member_auth
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) | 否 | | 主键
member_id | int(10) | 是 | 0 | 用户id
unionid | varchar(64) | 是 | | 唯一ID
oauth_client | varchar(20) | 是 | | 授权组别
oauth_client_user_id | varchar(100) | 是 | | 授权id
sex | tinyint(4) | 是 | 1 | 性别
nickname | varchar(200) | 是 | | 昵称
head_portrait | varchar(200) | 是 | | 头像
birthday | date | 是 | | 生日
country | varchar(100) | 是 | | 国家
province | varchar(100) | 是 | | 省
city | varchar(100) | 是 | | 市
status | tinyint(4) | 否 | 1 | 状态(-1:已删除,0:禁用,1:正常)
created_at | int(10) | 是 | 0 | 创建时间
updated_at | int(10) | 是 | 0 | 修改时间

### 会员_积分等变动表 : rf_member_credits_log
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | bigint(20) | 否 | | 无
member_id | int(11) | 是 | 0 | 会员id
credit_type | varchar(30) | 否 | | 变动类型[integral:积分;money:余额]
credit_group | varchar(30) | 是 | | 变动的详细组别
old_num | double(10,2) | 是 | 0 | 之前的数据
new_num | double(10,2) | 是 | 0 | 变动后的数据
num | double(10,2) | 是 | 0 | 变动的数据
remark | varchar(200) | 是 | | 备注
status | tinyint(4) | 是 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) | 是 | 0 | 创建时间
updated_at | int(10) | 是 | 0 | 修改时间

### 用户_会员表 : rf_member_info
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(11) | 否 | | 主键
username | varchar(20) | 是 | | 帐号
password_hash | varchar(255) | 是 | | 密码
auth_key | varchar(32) | 是 | | 授权令牌
password_reset_token | varchar(255) | 是 | | 密码重置令牌
type | tinyint(1) | 是 | 1 | 类别[1:普通会员;10管理员]
nickname | varchar(50) | 是 | | 昵称
realname | varchar(50) | 是 | | 真实姓名
head_portrait | varchar(255) | 是 | | 头像
sex | tinyint(1) | 是 | 1 | 性别[1:男;2:女]
qq | varchar(20) | 是 | | qq
email | varchar(60) | 是 | | 邮箱
birthday | date | 是 | | 生日
user_money | decimal(10,2) | 是 | 0.00 | 余额
accumulate_money | decimal(10,2) | 是 | 0.00 | 累积消费
frozen_money | decimal(10,2) | 是 | 0.00 | 累积金额
user_integral | int(11) | 是 | 0 | 当前积分
address_id | mediumint(8) unsigned | 是 | 0 | 默认地址
visit_count | smallint(5) unsigned | 是 | 1 | 访问次数
home_phone | varchar(20) | 是 | | 家庭号码
mobile_phone | varchar(20) | 是 | | 手机号码
role | smallint(6) | 是 | 10 | 权限
last_time | int(10) | 是 | 0 | 最后一次登陆时间
last_ip | varchar(16) | 是 | | 最后一次登陆ip
provinces | int(11) | 是 | 0 | 省
city | int(11) | 是 | 0 | 城市
area | int(11) | 是 | 0 | 地区
status | smallint(6) | 是 | 1 | 状态
created_at | int(10) | 否 | 0 | 创建时间
updated_at | int(10) | 是 | 0 | 修改时间

### 系统_行为表 : rf_sys_action_log
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) | 否 | | 主键
manager_id | int(10) | 否 | 0 | 执行用户id
behavior | varchar(50) | 是 | | 行为类别
method | varchar(20) | 是 | | 提交类型
module | varchar(50) | 是 | | 模块
controller | varchar(50) | 是 | | 控制器
action | varchar(50) | 是 | | 控制器方法
url | varchar(1000) | 是 | | 提交url
get_data | text | 是 | | get数据
post_data | longtext | 是 | | post数据
ip | varchar(16) | 是 | | ip地址
remark | varchar(255) | 是 | | 日志备注
country | varchar(50) | 是 | | 国家
provinces | varchar(50) | 是 | | 省
city | varchar(50) | 是 | | 城市
status | tinyint(4) | 否 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) | 否 | 0 | 创建时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

### 系统_插件表 : rf_sys_addons
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(11) | 否 | | 主键
title | varchar(20) | 否 | | 中文名
name | varchar(40) | 否 | | 插件名或标识
title_initial | varchar(1) | 否 | | 首字母拼音
cover | varchar(200) | 是 | | 封面
group | varchar(20) | 是 | | 组别
brief_introduction | varchar(140) | 是 | | 简单介绍
description | varchar(1000) | 是 | | 插件描述
config | text | 是 | | 配置
author | varchar(40) | 是 | | 作者
version | varchar(20) | 是 | | 版本号
wechat_message | varchar(1000) | 是 | | 接收微信回复类别
is_setting | tinyint(1) | 是 | -1 | 设置
is_hook | tinyint(1) | 是 | 0 | 钩子[0:不支持;1:支持]
is_rule | tinyint(4) | 是 | 0 | 是否要嵌入规则
is_mini_program | tinyint(4) | 是 | 0 | 小程序[0:不支持;1:支持]
status | tinyint(4) | 否 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) | 否 | 0 | 创建时间
updated_at | int(10) | 否 | 0 | 修改时间

### 系统_插件_权限表 : rf_sys_addons_auth_item
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
addons_name | varchar(40) | 否 | | 插件名称
route | varchar(64) | 否 | | 插件路由
description | text | 是 | | 无

### 系统_插件_权限授权表 : rf_sys_addons_auth_item_child
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
parent | varchar(64) | 否 | | 无
child | varchar(64) | 否 | | 无
addons_name | varchar(30) | 否 | | 插件名称

### 系统_插件菜单表 : rf_sys_addons_binding
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(11) | 否 | | 主键
addons_name | varchar(30) | 否 | | 插件名称
entry | varchar(10) | 否 | | 入口类别[menu,cover]
title | varchar(50) | 否 | | 名称
route | varchar(30) | 否 | | 路由
icon | varchar(50) | 是 | | 图标

### 系统_角色分配表 : rf_sys_auth_assignment
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
item_name | varchar(64) | 否 | | 无
user_id | varchar(64) | 否 | | 无
created_at | int(11) | 是 | | 无

### 系统_角色路由表 : rf_sys_auth_item
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
name | varchar(64) | 否 | | 无
type | int(11) | 否 | | 无
key | int(10) | 否 | 0 | 唯一key
description | text | 是 | | 无
rule_name | varchar(64) | 是 | | 规则名称
data | text | 是 | | 无
parent_key | int(10) | 是 | 0 | 父级key
level | int(5) | 是 | 1 | 级别
sort | int(10) | 是 | 0 | 排序
position | varchar(2000) | 否 | | 树
created_at | int(11) | 是 | 0 | 无
updated_at | int(11) | 是 | 0 | 无

### 系统_角色权限表 : rf_sys_auth_item_child
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
parent | varchar(64) | 否 | | 无
child | varchar(64) | 否 | | 无

### 系统_权限规则表 : rf_sys_auth_rule
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
name | varchar(64) | 否 | | 无
data | text | 是 | | 无
created_at | int(11) | 是 | | 无
updated_at | int(11) | 是 | | 无

### 系统_公用配置表 : rf_sys_config
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) unsigned | 否 | | 主键
title | varchar(50) | 否 | | 配置标题
name | varchar(30) | 否 | | 配置标识
type | varchar(30) | 否 | | 配置类型
cate_id | int(10) unsigned | 否 | 0 | 配置分类
extra | varchar(255) | 否 | | 配置值
remark | varchar(1000) | 否 | | 配置说明
value | text | 是 | | 配置值
is_hide_remark | tinyint(4) | 是 | 1 | 是否隐藏说明
sort | int(10) unsigned | 否 | 0 | 排序
status | tinyint(4) | 否 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) unsigned | 否 | 0 | 创建时间
updated_at | int(10) unsigned | 否 | 0 | 修改时间

### 系统_配置分类表 : rf_sys_config_cate
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) | 否 | | 主键
title | varchar(50) | 否 | | 标题
pid | int(10) unsigned | 是 | 0 | 上级id
level | tinyint(1) unsigned | 是 | 1 | 级别
sort | int(5) | 是 | 0 | 排序
status | tinyint(4) | 是 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) unsigned | 是 | 0 | 添加时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

### 系统_后台管理员表 : rf_sys_manager
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(11) | 否 | | 无
username | varchar(20) | 否 | | 帐号
password_hash | varchar(255) | 否 | | 密码
auth_key | varchar(32) | 否 | | 授权令牌
password_reset_token | varchar(255) | 是 | | 密码重置令牌
type | tinyint(1) | 是 | 1 | 1:普通管理员;10超级管理员
realname | varchar(10) | 是 | | 真实姓名
head_portrait | char(255) | 是 | | 无
sex | tinyint(1) | 是 | 1 | 性别[1:男;2:女]
qq | varchar(20) | 是 | | qq
email | varchar(60) | 是 | | 邮箱
birthday | date | 是 | | 生日
provinces | int(11) | 是 | 0 | 省
city | int(11) | 是 | 0 | 城市
area | int(11) | 是 | 0 | 地区
address | varchar(100) | 是 | | 默认地址
tel | varchar(20) | 是 | | 家庭号码
mobile | varchar(20) | 是 | | 手机号码
visit_count | smallint(5) unsigned | 是 | 0 | 访问次数
last_time | int(10) | 是 | 0 | 最后一次登陆时间
last_ip | varchar(16) | 是 | | 最后一次登陆ip
role | smallint(6) | 是 | 10 | 权限
status | smallint(6) | 否 | 1 | 状态
created_at | int(11) | 否 | 0 | 创建时间
updated_at | int(11) | 是 | 0 | 修改时间

### 系统_菜单导航表 : rf_sys_menu
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(11) | 否 | | 无
title | varchar(50) | 否 | | 标题
pid | int(50) | 是 | 0 | 上级id
url | varchar(50) | 是 | | 链接地址
menu_css | varchar(50) | 是 | | 样式
sort | int(5) | 是 | 0 | 排序
level | tinyint(1) | 是 | 1 | 级别
cate_id | tinyint(5) | 是 | 0 | 无
dev | tinyint(4) | 是 | 0 | 开发者[0:都可见;开发模式可见]
params | varchar(1000) | 是 | | 参数
status | tinyint(1) | 是 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) | 是 | 0 | 添加时间
updated_at | int(10) | 是 | 0 | 修改时间

### 系统_菜单分类表 : rf_sys_menu_cate
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) | 否 | | 主键
title | varchar(50) | 否 | | 标题
icon | varchar(20) | 是 | | icon
is_default_show | tinyint(4) unsigned | 是 | 0 | 默认显示
sort | int(5) | 是 | 0 | 排序
status | tinyint(4) | 是 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) unsigned | 是 | 0 | 添加时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

### 系统_风格 : rf_sys_style
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(11) | 否 | | 无
manager_id | int(11) unsigned | 是 | 0 | 管理员id
skin_id | tinyint(4) unsigned | 是 | 0 | 皮肤id[0:默认;1;蓝色3:黄色]
created_at | int(10) unsigned | 是 | 0 | 创建时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

### 微信_资源表 : rf_wechat_attachment
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) unsigned | 否 | | 无
file_name | varchar(255) | 是 | | 文件原始名
local_url | varchar(255) | 是 | | 本地地址
media_type | varchar(15) | 否 | | 类别
media_id | varchar(255) | 是 | | 微信资源ID
media_url | varchar(5000) | 是 | | 资源Url
width | int(10) unsigned | 是 | 0 | 宽度
height | int(10) unsigned | 是 | 0 | 高度
is_temporary | varchar(10) | 是 | | 类型[临时:tmp永久:perm]
link_type | tinyint(4) | 是 | 1 | 1微信2本地
status | tinyint(4) | 否 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) unsigned | 是 | 0 | 创建时间
updated_at | int(10) | 是 | 0 | 修改时间

### 微信_文章表 : rf_wechat_attachment_news
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(11) | 否 | | 无
attachment_id | int(10) unsigned | 是 | 0 | 关联的资源id
title | varchar(50) | 否 | | 标题
thumb_media_id | varchar(255) | 是 | | 图文消息的封面图片素材id（必须是永久mediaID）
thumb_url | varchar(255) | 是 | | 缩略图Url
author | varchar(64) | 是 | | 作者
digest | varchar(255) | 是 | | 简介
show_cover_pic | tinyint(4) | 是 | 0 | 0为false，即不显示，1为true，即显示
content | mediumtext | 是 | | 图文消息的具体内容，支持HTML标签，必须少于2万字符
content_source_url | varchar(255) | 是 | | 图文消息的原文地址，即点击“阅读原文”后的URL
media_url | varchar(255) | 是 | | 资源Url
sort | int(11) | 是 | 0 | 排序
status | tinyint(4) | 否 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) unsigned | 是 | 0 | 创建时间
updated_at | int(10) | 是 | 0 | 修改时间

### 微信_粉丝表 : rf_wechat_fans
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) unsigned | 否 | | 无
member_id | int(10) unsigned | 是 | 0 | 用户id
unionid | varchar(64) | 是 | | 唯一公众号ID
openid | varchar(50) | 否 | | openid
nickname | varchar(50) | 是 | | 昵称
head_portrait | varchar(255) | 是 | | 头像
sex | tinyint(2) | 是 | 0 | 性别
follow | tinyint(1) | 是 | 1 | 是否关注[1:关注;0:取消关注]
followtime | int(10) unsigned | 是 | 0 | 关注时间
unfollowtime | int(10) unsigned | 是 | 0 | 取消关注时间
group_id | int(10) | 是 | 0 | 分组id
tag | varchar(1000) | 是 | | 标签
last_longitude | varchar(10) | 是 | | 最后一次经纬度上报
last_latitude | varchar(10) | 是 | | 最后一次经纬度上报
last_address | varchar(100) | 是 | | 最后一次经纬度上报地址
last_updated | int(10) | 是 | 0 | 最后更新时间
country | varchar(100) | 是 | | 国家
province | varchar(100) | 是 | | 省
city | varchar(100) | 是 | | 市
status | tinyint(4) | 是 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) unsigned | 是 | 0 | 添加时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

### 微信_关注统计表 : rf_wechat_fans_stat
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) unsigned | 否 | | 无
new_attention | int(10) | 否 | 0 | 今日新关注
cancel_attention | int(10) | 否 | 0 | 今日取消关注
cumulate_attention | int(10) | 否 | 0 | 累计关注
date | date | 否 | | 无
status | tinyint(4) | 是 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) unsigned | 是 | 0 | 添加时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

### 微信_粉丝标签关联表 : rf_wechat_fans_tag_map
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(11) unsigned | 否 | | 无
fans_id | int(11) unsigned | 否 | 0 | 粉丝id
tag_id | int(10) unsigned | 否 | 0 | 标签id

### 微信_粉丝标签表 : rf_wechat_fans_tags
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) | 否 | | 无
tags | longtext | 是 | | 标签
status | tinyint(4) | 是 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) unsigned | 是 | 0 | 创建时间
updated_at | int(10) | 否 | 0 | 修改时间

### 微信_群发记录 : rf_wechat_mass_record
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) unsigned | 否 | | 无
tag_name | varchar(50) | 是 | | 标签名称
fans_num | int(10) unsigned | 是 | 0 | 粉丝数量
msg_id | bigint(20) | 是 | 0 | 微信消息id
msg_type | varchar(10) | 是 | | 回复类别
content | varchar(10000) | 是 | | 内容
tag_id | int(10) | 是 | 0 | 标签id
attachment_id | int(10) unsigned | 是 | 0 | 资源id
media_id | varchar(100) | 是 | | 媒体id
media_type | varchar(10) | 是 | | 资源类别
send_time | int(10) unsigned | 是 | 0 | 发送时间
send_status | tinyint(4) | 是 | 0 | 0未发送 1已发送
final_send_time | int(10) unsigned | 是 | 0 | 最终发送时间
error_content | varchar(255) | 是 | | 报错原因
status | tinyint(4) | 是 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) unsigned | 是 | 0 | 无
updated_at | int(10) | 否 | 0 | 修改时间

### 微信_自定义菜单 : rf_wechat_menu
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) | 否 | | 公众号id
menu_id | int(10) unsigned | 是 | 0 | 微信菜单id
type | tinyint(3) unsigned | 是 | 1 | 1:默认菜单；2个性化菜单
title | varchar(30) | 是 | | 标题
sex | tinyint(3) unsigned | 是 | | 性别
tag_id | int(10) | 是 | 0 | 标签id
client_platform_type | tinyint(3) unsigned | 是 | | 手机系统
country | varchar(100) | 是 | 中国 | 国家
province | varchar(100) | 是 | | 省
city | varchar(50) | 是 | | 市
language | varchar(50) | 是 | | 语言
menu_data | text | 是 | | 微信菜单
status | tinyint(3) | 是 | 0 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) unsigned | 是 | 0 | 创建时间
updated_at | int(10) | 是 | 0 | 修改时间

### 微信_自定义菜单省市区 : rf_wechat_menu_provinces
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(11) | 否 | | 无
title | varchar(50) | 否 | | 栏目名
pid | int(10) | 否 | 0 | 父栏目
level | tinyint(1) | 否 | 1 | 级别
status | tinyint(4) | 否 | 1 | 状态(-1:已删除,0:禁用,1:正常)
created_at | int(10) unsigned | 是 | 0 | 创建时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

### 微信_历史记录表 : rf_wechat_msg_history
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) unsigned | 否 | | 无
rule_id | int(10) unsigned | 是 | 0 | 规则id
keyword_id | int(10) | 是 | 0 | 关键字id
openid | varchar(50) | 是 | | 无
module | varchar(50) | 是 | | 触发模块
message | varchar(1000) | 是 | | 微信消息
type | varchar(20) | 是 | | 无
event | varchar(20) | 是 | | 详细事件
status | tinyint(4) | 否 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) unsigned | 是 | 0 | 创建时间
updated_at | int(10) | 否 | 0 | 修改时间

### 微信_二维码表 : rf_wechat_qrcode
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) unsigned | 否 | | 无
name | varchar(50) | 是 | | 场景名称
keyword | varchar(100) | 是 | | 关联关键字
scene_id | int(10) unsigned | 是 | 0 | 场景ID
scene_str | varchar(64) | 是 | | 场景值
model | tinyint(1) unsigned | 是 | 0 | 类型
ticket | varchar(250) | 是 | | ticket
expire_seconds | int(10) unsigned | 是 | 2592000 | 有效期
subnum | int(10) unsigned | 是 | 0 | 扫描次数
type | varchar(10) | 是 | | 二维码类型
extra | int(10) unsigned | 是 | 0 | 无
url | varchar(80) | 是 | | url
end_time | int(10) | 是 | 0 | 无
status | tinyint(4) | 否 | 1 | 状态(-1:已删除,0:禁用,1:正常)
created_at | int(10) | 是 | 0 | 创建时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

### 微信_二维码扫描记录表 : rf_wechat_qrcode_stat
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) unsigned | 否 | | 无
qrcord_id | int(10) unsigned | 是 | 0 | 二维码id
openid | varchar(50) | 是 | | 微信openid
type | tinyint(1) unsigned | 是 | 0 | 1:关注;2:扫描
name | varchar(50) | 是 | | 场景名称
scene_str | varchar(64) | 是 | | 场景值
scene_id | int(10) unsigned | 是 | 0 | 场景ID
status | tinyint(4) | 否 | 1 | 状态(-1:已删除,0:禁用,1:正常)
created_at | int(10) | 是 | 0 | 创建时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

### 微信_模块回复 : rf_wechat_reply_addon
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(11) | 否 | | 无
rule_id | int(10) | 是 | 0 | 规则id
addon | varchar(50) | 是 | | 模块名称

### 微信_默认回复表 : rf_wechat_reply_default
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(11) | 否 | | 无
follow_content | varchar(200) | 是 | | 关注回复关键字
default_content | varchar(200) | 是 | | 默认回复关键字
status | tinyint(4) | 是 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) unsigned | 是 | 0 | 创建时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

### 微信_图片回复 : rf_wechat_reply_images
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) | 否 | | 无
rule_id | int(10) | 是 | | 无
media_id | varchar(255) | 否 | | 无

### 微信_图文回复 : rf_wechat_reply_news
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) | 否 | | 无
rule_id | int(10) | 是 | | 无
attachment_id | int(10) | 是 | 0 | 无

### 微信_文字回复 : rf_wechat_reply_text
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) | 否 | | 无
rule_id | int(10) | 是 | 0 | 规则id
content | varchar(1000) | 是 | | 内容

### 微信_自定义接口回复 : rf_wechat_reply_user_api
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) | 否 | | 无
rule_id | int(10) | 是 | 0 | 规则id
api_url | varchar(255) | 否 | | 接口地址
description | varchar(255) | 是 | | 说明
default | varchar(50) | 是 | | 默认回复
cache_time | int(10) | 是 | 0 | 缓存时间 0默认为不缓存

### 微信_视频回复 : rf_wechat_reply_video
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) | 否 | | 无
rule_id | int(10) | 是 | | 无
title | varchar(50) | 否 | | 无
description | varchar(255) | 否 | | 无
media_id | varchar(255) | 否 | | 无

### 微信_语音回复 : rf_wechat_reply_voice
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) | 否 | | 无
rule_id | int(10) | 是 | 0 | 规则id
media_id | varchar(255) | 否 | | 无

### 微信_回复规则名称表 : rf_wechat_rule
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) unsigned | 否 | | 无
name | varchar(50) | 否 | | 规则名称
module | varchar(50) | 否 | | 模块
sort | int(10) | 否 | 0 | 排序
status | tinyint(4) | 是 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) | 是 | 0 | 创建时间
updated_at | int(10) | 是 | 0 | 修改时间

### 微信_回复关键字表 : rf_wechat_rule_keyword
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) unsigned | 否 | | 无
rule_id | int(10) unsigned | 是 | 0 | 规则ID
module | varchar(50) | 否 | | 模块名
content | varchar(255) | 否 | | 内容
type | tinyint(1) unsigned | 否 | 1 | 类别
sort | tinyint(3) unsigned | 否 | 1 | 优先级
status | tinyint(1) | 是 | 1 | 状态[-1:删除;0:禁用;1启用]

### 微信_触发关键字记录表 : rf_wechat_rule_keyword_stat
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) unsigned | 否 | | 无
rule_id | int(10) | 是 | 0 | 规则id
keyword_id | int(10) unsigned | 是 | 0 | 关键字id
rule_name | varchar(50) | 是 | | 规则名称
keyword_type | tinyint(1) unsigned | 否 | 1 | 类别
keyword_content | varchar(255) | 是 | | 触发的关键字内容
hit | int(10) unsigned | 是 | 1 | 关键字id
status | tinyint(4) | 否 | 1 | 状态(-1:已删除,0:禁用,1:正常)
created_at | int(10) | 是 | 0 | 创建时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

### 微信_触发规则记录表 : rf_wechat_rule_stat
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) unsigned | 否 | | 无
rule_id | int(10) unsigned | 是 | 0 | 规则id
rule_name | varchar(50) | 是 | | 规则名称
hit | int(10) unsigned | 是 | 1 | 无
status | tinyint(4) | 否 | 1 | 状态(-1:已删除,0:禁用,1:正常)
created_at | int(10) | 是 | 0 | 创建时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

### 微信_参数设置 : rf_wechat_setting
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(11) | 否 | | 无
history | varchar(200) | 是 | | 历史消息参数设置
special | text | 是 | | 特殊消息回复参数
status | tinyint(4) | 是 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) | 否 | 0 | 创建时间
updated_at | int(10) | 否 | 0 | 修改时间
## 数据字典

### rf_api_access_token
#### api_授权秘钥表
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) unsigned | 否 | | 无
merchant_id | int(10) unsigned | 是 | 0 | 商户id
refresh_token | varchar(60) | 是 | | 刷新令牌
access_token | varchar(60) | 是 | | 授权令牌
member_id | int(10) unsigned | 是 | 0 | 用户id
openid | varchar(50) | 是 | | 授权对象openid
group | varchar(100) | 是 | | 组别
status | tinyint(4) | 是 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) unsigned | 是 | 0 | 创建时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

### rf_backend_member
#### 系统_后台管理员表
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(11) | 否 | | 无
username | varchar(20) | 否 | | 帐号
password_hash | varchar(150) | 否 | | 密码
auth_key | varchar(32) | 否 | | 授权令牌
password_reset_token | varchar(150) | 是 | | 密码重置令牌
type | tinyint(1) | 是 | 1 | 1:普通管理员;10超级管理员
realname | varchar(10) | 是 | | 真实姓名
head_portrait | char(150) | 是 | | 头像
gender | tinyint(1) unsigned | 是 | 0 | 性别[0:未知;1:男;2:女]
qq | varchar(20) | 是 | | qq
email | varchar(60) | 是 | | 邮箱
birthday | date | 是 | | 生日
province_id | int(10) | 是 | 0 | 省
city_id | int(10) | 是 | 0 | 城市
area_id | int(10) | 是 | 0 | 地区
address | varchar(100) | 是 | | 默认地址
mobile | varchar(20) | 是 | | 手机号码
home_phone | varchar(20) | 是 | | 家庭号码
dingtalk_robot_token | varchar(100) | 是 | | 钉钉机器人token
visit_count | smallint(5) unsigned | 是 | 0 | 访问次数
last_time | int(10) | 是 | 0 | 最后一次登录时间
last_ip | varchar(16) | 是 | | 最后一次登录ip
role | smallint(6) | 是 | 10 | 权限
status | tinyint(4) | 是 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) unsigned | 是 | 0 | 创建时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

### rf_backend_notify
#### 系统_消息公告表
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | bigint(20) | 否 | | 主键
title | varchar(150) | 是 | | 标题
content | text | 是 | | 消息内容
type | tinyint(1) | 是 | 0 | 消息类型[1:公告;2:提醒;3:信息(私信)
target_id | int(10) | 是 | 0 | 目标id
target_type | varchar(100) | 是 | | 目标类型
target_display | int(10) | 是 | 1 | 接受者是否删除
action | varchar(100) | 是 | | 动作
view | int(10) | 是 | 0 | 浏览量
sender_id | int(10) | 是 | 0 | 发送者id
sender_display | tinyint(1) | 是 | 1 | 发送者是否删除
sender_withdraw | tinyint(1) | 是 | 1 | 是否撤回 0是撤回
status | tinyint(4) | 否 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) | 否 | 0 | 创建时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

### rf_backend_notify_member
#### 系统_消息查看时间记录表
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) | 否 | | 无
member_id | int(10) unsigned | 否 | 0 | 管理员id
notify_id | int(10) | 是 | 0 | 消息id
is_read | tinyint(2) | 是 | 0 | 是否已读 1已读
type | tinyint(1) | 是 | 0 | 消息类型[1:公告;2:提醒;3:信息(私信)
status | tinyint(4) | 否 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) | 否 | 0 | 创建时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

### rf_backend_notify_pull_time
#### 系统_消息拉取表
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(11) | 否 | | 无
member_id | int(10) | 否 | | 管理员id
type | tinyint(4) | 是 | 0 | 消息类型[1:公告;2:提醒;3:信息(私信)
alert_type | varchar(20) | 是 | 0 | 提醒消息类型[sys:系统;wechat:微信]
last_time | int(10) | 是 | | 最后拉取时间

### rf_backend_notify_subscription_config
#### 系统_消息配置表
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
member_id | int(10) unsigned | 否 | | 用户id
action | json | 是 | | 订阅事件

### rf_common_action_behavior
#### 系统_行为表
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) | 否 | | 主键
app_id | varchar(50) | 是 | | 应用id
url | varchar(200) | 是 | | 提交url
method | varchar(20) | 是 | | 提交类型 *为不限
behavior | varchar(50) | 是 | | 行为类别
action | tinyint(4) unsigned | 是 | 1 | 前置/后置
level | varchar(20) | 是 | | 级别
is_record_post | tinyint(4) unsigned | 是 | 1 | 是否记录post[0;否;1是]
is_ajax | tinyint(4) unsigned | 是 | 2 | 是否ajax请求[1;否;2是;3不限]
remark | varchar(100) | 是 | | 备注
addons_name | varchar(100) | 否 | | 插件名称
is_addon | tinyint(1) unsigned | 是 | 0 | 是否插件
status | tinyint(4) | 是 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) | 是 | 0 | 创建时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

### rf_common_action_log
#### 系统_行为表
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) | 否 | | 主键
merchant_id | int(10) unsigned | 是 | 0 | 商户id
app_id | varchar(50) | 是 | | 应用id
user_id | int(10) | 否 | 0 | 用户id
behavior | varchar(50) | 是 | | 行为类别
method | varchar(20) | 是 | | 提交类型
module | varchar(50) | 是 | | 模块
controller | varchar(50) | 是 | | 控制器
action | varchar(50) | 是 | | 控制器方法
url | varchar(200) | 是 | | 提交url
get_data | json | 是 | | get数据
post_data | json | 是 | | post数据
header_data | json | 是 | | header数据
ip | varchar(16) | 是 | | ip地址
addons_name | varchar(100) | 否 | | 插件名称
remark | varchar(1000) | 是 | | 日志备注
country | varchar(50) | 是 | | 国家
provinces | varchar(50) | 是 | | 省
city | varchar(50) | 是 | | 城市
device | varchar(200) | 是 | | 设备信息
status | tinyint(4) | 否 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) | 否 | 0 | 创建时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

### rf_common_addons
#### 公用_插件表
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(11) | 否 | | 主键
title | varchar(20) | 否 | | 中文名
name | varchar(100) | 否 | | 插件名或标识
title_initial | varchar(1) | 否 | | 首字母拼音
bootstrap | varchar(200) | 是 | | 启用文件
service | varchar(200) | 是 | | 服务调用类
cover | varchar(200) | 是 | | 封面
group | varchar(20) | 是 | | 组别
brief_introduction | varchar(140) | 是 | | 简单介绍
description | varchar(1000) | 是 | | 插件描述
author | varchar(40) | 是 | | 作者
version | varchar(20) | 是 | | 版本号
wechat_message | json | 是 | | 接收微信回复类别
is_setting | tinyint(1) | 是 | -1 | 设置
is_rule | tinyint(4) | 是 | 0 | 是否要嵌入规则
is_merchant_route_map | tinyint(1) | 是 | 0 | 商户路由映射
default_config | json | 是 | | 默认配置
console | json | 是 | | 控制台
status | tinyint(4) | 是 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) unsigned | 是 | 0 | 创建时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

### rf_common_addons_binding
#### 公用_插件菜单表
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(11) | 否 | | 主键
addons_name | varchar(100) | 否 | | 插件名称
app_id | varchar(20) | 否 | | 应用id
entry | varchar(10) | 否 | | 入口类别[menu,cover]
title | varchar(50) | 否 | | 名称
route | varchar(200) | 否 | | 路由
icon | varchar(50) | 是 | | 图标
params | json | 是 | | 参数

### rf_common_addons_config
#### 公用_插件配置值表
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) unsigned | 否 | | 主键
addons_name | varchar(100) | 否 | | 插件名或标识
merchant_id | int(10) unsigned | 是 | 0 | 商户id
data | json | 是 | | 配置

### rf_common_attachment
#### 公用_文件管理
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(11) | 否 | | 无
merchant_id | int(10) unsigned | 是 | 0 | 商户id
drive | varchar(50) | 是 | | 驱动
upload_type | varchar(10) | 是 | | 上传类型
specific_type | varchar(100) | 否 | | 类别
base_url | varchar(1000) | 是 | | url
path | varchar(1000) | 是 | | 本地路径
md5 | varchar(100) | 是 | | md5校验码
name | varchar(1000) | 是 | | 文件原始名
extension | varchar(50) | 是 | | 扩展名
size | int(11) | 是 | 0 | 长度
year | int(10) unsigned | 是 | 0 | 年份
month | int(10) | 是 | 0 | 月份
day | int(10) unsigned | 是 | 0 | 日
width | int(10) unsigned | 是 | 0 | 宽度
height | int(10) unsigned | 是 | 0 | 高度
upload_ip | varchar(16) | 是 | | 上传者ip
status | tinyint(4) | 否 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) unsigned | 是 | 0 | 创建时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

### rf_common_auth_assignment
#### 公用_会员授权角色表
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
role_id | int(11) | 否 | | 无
user_id | int(11) | 否 | | 无
app_id | varchar(20) | 是 | | 类型

### rf_common_auth_item
#### 公用_权限表
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(11) | 否 | | 无
title | varchar(200) | 是 | | 标题
name | varchar(64) | 否 | | 别名
app_id | varchar(20) | 否 | | 应用
addons_name | varchar(100) | 否 | | 插件名称
pid | int(10) | 是 | 0 | 父级id
level | int(5) | 是 | 1 | 级别
is_menu | tinyint(1) unsigned | 是 | 0 | 是否菜单
is_addon | tinyint(1) unsigned | 是 | 0 | 是否插件
sort | int(10) | 是 | 9999 | 排序
tree | varchar(500) | 是 | | 树
status | tinyint(4) | 否 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(11) | 是 | 0 | 无
updated_at | int(11) | 是 | 0 | 无

### rf_common_auth_item_child
#### 公用_授权角色权限表
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
role_id | int(11) unsigned | 否 | 0 | 角色id
item_id | int(10) unsigned | 否 | 0 | 权限id
name | varchar(64) | 否 | | 别名
app_id | varchar(20) | 否 | | 类别
addons_name | varchar(100) | 否 | | 插件名称
is_menu | tinyint(1) unsigned | 是 | 0 | 是否菜单
is_addon | tinyint(1) unsigned | 是 | 0 | 是否插件

### rf_common_auth_role
#### 公用_角色表
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) | 否 | | 主键
merchant_id | int(10) unsigned | 是 | 0 | 商户id
title | varchar(50) | 否 | | 标题
app_id | varchar(20) | 否 | | 应用
pid | int(10) unsigned | 是 | 0 | 上级id
level | tinyint(1) unsigned | 是 | 1 | 级别
sort | int(5) | 是 | 0 | 排序
tree | varchar(300) | 否 | | 树
status | tinyint(4) | 是 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) unsigned | 是 | 0 | 添加时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

### rf_common_config
#### 公用_配置表
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) unsigned | 否 | | 主键
title | varchar(50) | 否 | | 配置标题
name | varchar(50) | 否 | | 配置标识
app_id | varchar(20) | 否 | | 应用
type | varchar(30) | 否 | | 配置类型
cate_id | int(10) unsigned | 否 | 0 | 配置分类
extra | varchar(1000) | 否 | | 配置值
remark | varchar(1000) | 否 | | 配置说明
is_hide_remark | tinyint(4) | 是 | 1 | 是否隐藏说明
default_value | varchar(500) | 是 | | 默认配置
sort | int(10) unsigned | 是 | 0 | 排序
status | tinyint(4) | 否 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) unsigned | 否 | 0 | 创建时间
updated_at | int(10) unsigned | 否 | 0 | 修改时间

### rf_common_config_cate
#### 公用_配置分类表
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) | 否 | | 主键
title | varchar(50) | 否 | | 标题
pid | int(10) unsigned | 是 | 0 | 上级id
app_id | varchar(20) | 否 | | 应用
level | tinyint(1) unsigned | 是 | 1 | 级别
sort | int(5) | 是 | 0 | 排序
tree | varchar(300) | 否 | | 树
status | tinyint(4) | 是 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) unsigned | 是 | 0 | 添加时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

### rf_common_config_value
#### 公用_配置值表
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) unsigned | 否 | | 主键
config_id | int(10) | 否 | 0 | 配置id
merchant_id | int(10) unsigned | 是 | 0 | 商户id
data | text | 是 | | 配置内

### rf_common_ip_blacklist
#### 公用_ip黑名单
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) | 否 | | 无
merchant_id | int(10) unsigned | 是 | 0 | 无
remark | varchar(200) | 是 | | 备注
ip | varchar(20) | 否 | | ip地址
status | tinyint(4) | 是 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) unsigned | 是 | | 创建时间
updated_at | int(10) unsigned | 是 | | 修改时间

### rf_common_log
#### 公用_日志
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(11) | 否 | | 无
app_id | varchar(50) | 是 | | 应用id
merchant_id | int(10) unsigned | 是 | 0 | 商户id
user_id | int(11) | 是 | 0 | 用户id
method | varchar(20) | 是 | | 提交类型
module | varchar(50) | 是 | | 模块
controller | varchar(100) | 是 | | 控制器
action | varchar(50) | 是 | | 方法
url | varchar(1000) | 是 | | 提交url
get_data | json | 是 | | get数据
post_data | json | 是 | | post数据
header_data | json | 是 | | header数据
ip | varchar(16) | 是 | | ip地址
error_code | int(10) | 是 | 0 | 报错code
error_msg | varchar(1000) | 是 | | 报错信息
error_data | json | 是 | | 报错日志
req_id | varchar(50) | 是 | | 对外id
user_agent | varchar(200) | 是 | | UA信息
device | varchar(30) | 是 | | 设备信息
device_uuid | varchar(50) | 是 | | 设备唯一号
device_version | varchar(20) | 是 | | 设备版本
device_app_version | varchar(20) | 是 | | 设备app版本
status | tinyint(4) | 否 | 1 | 状态(-1:已删除,0:禁用,1:正常)
created_at | int(10) | 是 | 0 | 创建时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

### rf_common_menu
#### 系统_菜单导航表
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(11) | 否 | | 无
title | varchar(50) | 否 | | 标题
app_id | varchar(20) | 否 | | 应用
addons_name | varchar(100) | 否 | | 插件名称
is_addon | tinyint(1) unsigned | 是 | 0 | 是否插件
cate_id | tinyint(5) unsigned | 是 | 0 | 分类id
pid | int(50) unsigned | 是 | 0 | 上级id
url | varchar(50) | 是 | | 路由
icon | varchar(50) | 是 | | 样式
level | tinyint(1) unsigned | 是 | 1 | 级别
dev | tinyint(4) unsigned | 是 | 0 | 开发者[0:都可见;开发模式可见]
sort | int(5) | 是 | 999 | 排序
params | json | 是 | | 参数
tree | varchar(300) | 否 | | 树
status | tinyint(4) | 是 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) unsigned | 是 | 0 | 添加时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

### rf_common_menu_cate
#### 系统_菜单分类表
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) | 否 | | 主键
title | varchar(50) | 否 | | 标题
app_id | varchar(20) | 否 | | 应用
addons_name | varchar(100) | 否 | | 插件名称
icon | varchar(20) | 是 | | icon
is_default_show | tinyint(2) unsigned | 是 | 0 | 默认显示
is_addon | tinyint(1) unsigned | 是 | 0 | 是否插件
addon_centre | tinyint(1) | 是 | 0 | 应用中心
sort | int(10) | 是 | 999 | 排序
level | tinyint(1) unsigned | 是 | 1 | 级别
tree | varchar(300) | 否 | | 树
pid | int(10) unsigned | 是 | 0 | 上级id
status | tinyint(4) | 是 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) unsigned | 是 | 0 | 添加时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

### rf_common_pay_log
#### 公用_支付日志
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) | 否 | | 主键
merchant_id | int(10) unsigned | 是 | 0 | 商户id
member_id | int(10) unsigned | 是 | 0 | 用户id
app_id | varchar(50) | 是 | | 应用id
addons_name | varchar(100) | 是 | | 插件名称
order_sn | varchar(20) | 是 | | 关联订单号
order_group | varchar(20) | 是 | | 组别[默认统一支付类型]
openid | varchar(50) | 是 | | openid
mch_id | varchar(20) | 是 | | 商户支付账户
body | varchar(100) | 是 | | 支付内容
detail | varchar(100) | 是 | | 支付详情
auth_code | varchar(50) | 是 | | 刷卡码
out_trade_no | varchar(32) | 是 | | 商户订单号
transaction_id | varchar(50) | 是 | | 微信订单号
total_fee | decimal(10,2) | 是 | 0.00 | 微信充值金额
fee_type | varchar(10) | 是 | | 标价币种
pay_type | tinyint(3) | 否 | 0 | 支付类型[1:微信;2:支付宝;3:银联]
pay_fee | decimal(10,2) | 否 | 0.00 | 支付金额
pay_status | tinyint(2) | 是 | 0 | 支付状态
pay_time | int(10) | 是 | 0 | 支付时间
trade_type | varchar(16) | 是 | | 交易类型
refund_sn | varchar(100) | 是 | | 退款编号
refund_fee | decimal(10,2) | 否 | 0.00 | 退款金额
is_refund | tinyint(4) | 是 | 0 | 退款情况[0:未退款;1已退款]
create_ip | varchar(30) | 是 | | 创建者ip
pay_ip | varchar(30) | 是 | | 支付者ip
notify_url | varchar(100) | 是 | | 支付通知回调地址
return_url | varchar(100) | 是 | | 买家付款成功跳转地址
status | tinyint(4) | 是 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) | 是 | 0 | 创建时间
updated_at | int(10) | 是 | 0 | 修改时间

### rf_common_provinces
#### 公用_省市区记录表
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
level | tinyint(4) | 否 | 1 | 级别
tree | varchar(200) | 否 | | 无
sort | tinyint(3) unsigned | 是 | 0 | 排序

### rf_common_sms_log
#### 公用_短信发送日志
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(11) | 否 | | 无
merchant_id | int(10) unsigned | 是 | 0 | 商户id
member_id | int(11) unsigned | 是 | 0 | 用户id
mobile | varchar(20) | 是 | | 手机号码
code | varchar(6) | 是 | | 验证码
content | varchar(500) | 是 | | 内容
error_code | int(10) | 是 | 0 | 报错code
error_msg | varchar(200) | 是 | | 报错信息
error_data | longtext | 是 | | 报错日志
usage | varchar(20) | 是 | | 用途
used | tinyint(1) | 是 | 0 | 是否使用[0:未使用;1:已使用]
use_time | int(10) | 是 | 0 | 使用时间
ip | varchar(30) | 是 | | ip地址
status | tinyint(4) | 否 | 1 | 状态(-1:已删除,0:禁用,1:正常)
created_at | int(10) unsigned | 是 | 0 | 创建时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

### rf_member
#### 用户_会员表
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(11) | 否 | | 主键
merchant_id | int(10) unsigned | 是 | 0 | 商户id
username | varchar(20) | 是 | | 帐号
password_hash | varchar(150) | 是 | | 密码
auth_key | varchar(32) | 是 | | 授权令牌
password_reset_token | varchar(150) | 是 | | 密码重置令牌
type | tinyint(1) | 是 | 1 | 类别[1:普通会员;10管理员]
nickname | varchar(50) | 是 | | 昵称
realname | varchar(50) | 是 | | 真实姓名
head_portrait | varchar(150) | 是 | | 头像
current_level | tinyint(4) | 是 | 1 | 当前级别
gender | tinyint(1) unsigned | 是 | 0 | 性别[0:未知;1:男;2:女]
qq | varchar(20) | 是 | | qq
email | varchar(60) | 是 | | 邮箱
birthday | date | 是 | | 生日
visit_count | int(10) unsigned | 是 | 1 | 访问次数
home_phone | varchar(20) | 是 | | 家庭号码
mobile | varchar(20) | 是 | | 手机号码
role | smallint(6) | 是 | 10 | 权限
last_time | int(10) | 是 | 0 | 最后一次登录时间
last_ip | varchar(16) | 是 | | 最后一次登录ip
province_id | int(10) | 是 | 0 | 省
city_id | int(10) | 是 | 0 | 城市
area_id | int(10) | 是 | 0 | 地区
pid | int(10) unsigned | 是 | 0 | 上级id
status | tinyint(4) | 是 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) unsigned | 是 | 0 | 创建时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

### rf_member_account
#### 会员_账户统计表
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) unsigned | 否 | | 无
merchant_id | int(10) unsigned | 是 | 0 | 商户id
member_id | int(10) unsigned | 是 | 0 | 用户id
level | int(11) | 是 | -1 | 会员等级
user_money | decimal(10,2) | 是 | 0.00 | 当前余额
accumulate_money | decimal(10,2) | 是 | 0.00 | 累计余额
give_money | decimal(10,2) | 是 | 0.00 | 累计赠送余额
consume_money | decimal(10,2) | 是 | 0.00 | 累计消费金额
frozen_money | decimal(10,2) | 是 | 0.00 | 冻结金额
user_integral | int(11) | 是 | 0 | 当前积分
accumulate_integral | int(11) | 是 | 0 | 累计积分
give_integral | int(11) | 是 | 0 | 累计赠送积分
consume_integral | decimal(10,2) | 是 | 0.00 | 累计消费积分
frozen_integral | int(11) | 是 | 0 | 冻结积分
status | tinyint(4) | 是 | 1 | 状态[-1:删除;0:禁用;1启用]

### rf_member_address
#### 用户_收货地址表
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) | 否 | | 主键
merchant_id | int(10) unsigned | 是 | 0 | 商户id
member_id | int(11) unsigned | 是 | 0 | 用户id
province_id | int(10) unsigned | 是 | 0 | 省id
city_id | int(10) unsigned | 是 | 0 | 市id
area_id | int(10) unsigned | 是 | 0 | 区id
address_name | varchar(200) | 是 | | 地址
address_details | varchar(200) | 是 | | 详细地址
is_default | tinyint(4) unsigned | 是 | 0 | 默认地址
zip_code | int(10) unsigned | 是 | 0 | 邮编
realname | varchar(100) | 是 | | 真实姓名
home_phone | varchar(20) | 是 | | 家庭号码
mobile | varchar(20) | 是 | | 手机号码
status | tinyint(4) | 否 | 1 | 状态(-1:已删除,0:禁用,1:正常)
created_at | int(10) unsigned | 是 | 0 | 创建时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

### rf_member_auth
#### 用户_第三方登录
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) unsigned | 否 | | 主键
merchant_id | int(10) unsigned | 是 | 0 | 商户id
member_id | int(10) unsigned | 是 | 0 | 用户id
unionid | varchar(64) | 是 | | 唯一ID
oauth_client | varchar(20) | 是 | | 授权组别
oauth_client_user_id | varchar(100) | 是 | | 授权id
gender | tinyint(1) unsigned | 是 | 0 | 性别[0:未知;1:男;2:女]
nickname | varchar(100) | 是 | | 昵称
head_portrait | varchar(150) | 是 | | 头像
birthday | date | 是 | | 生日
country | varchar(100) | 是 | | 国家
province | varchar(100) | 是 | | 省
city | varchar(100) | 是 | | 市
status | tinyint(4) | 是 | 1 | 状态(-1:已删除,0:禁用,1:正常)
created_at | int(10) unsigned | 是 | 0 | 创建时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

### rf_member_balance_withdraw
#### 会员_余额提现记录表
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(11) | 否 | | 无
merchant_id | int(10) unsigned | 是 | 0 | 商户id
withdraw_no | varchar(100) | 是 | | 提现流水号
member_id | int(11) | 是 | | 会员id
bank_name | varchar(50) | 是 | | 提现银行名称
account_number | varchar(50) | 是 | | 提现银行账号
realname | varchar(30) | 是 | | 提现账户姓名
mobile | varchar(20) | 是 | | 手机
cash | decimal(10,2) | 是 | 0.00 | 提现金额
memo | varchar(200) | 是 | | 备注
withdraw_status | smallint(6) | 是 | 0 | 当前状态 0已申请(等待处理) 1已同意 -1 已拒绝
payment_date | int(11) | 是 | 0 | 到账日期
transfer_type | int(11) | 是 | 1 | 转账方式 1 线下转账 2线上转账
transfer_name | varchar(50) | 是 | | 转账银行名称
transfer_money | decimal(10,2) | 是 | 0.00 | 转账金额
transfer_status | int(11) | 是 | 0 | 转账状态 0未转账 1已转账 -1转账失败
transfer_remark | varchar(200) | 是 | | 转账备注
transfer_result | varchar(200) | 是 | | 转账结果
transfer_no | varchar(100) | 是 | | 转账流水号
transfer_account_no | varchar(100) | 是 | | 转账银行账号
status | tinyint(4) | 是 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) unsigned | 是 | 0 | 创建时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

### rf_member_bank_account
#### 会员提现账号
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(11) | 否 | | 无
member_id | int(11) | 否 | | 会员id
branch_bank_name | varchar(50) | 是 | | 支行信息
realname | varchar(50) | 否 | | 真实姓名
account_number | varchar(50) | 否 | | 银行账号
bank_code | varchar(50) | 是 | | 银行编号
mobile | varchar(20) | 否 | | 手机号
is_default | int(11) | 否 | 0 | 是否默认账号
account_type | int(11) | 是 | 1 | 账户类型，1：银行卡，2：微信，3：支付宝
account_type_name | varchar(30) | 是 | 银行卡 | 账户类型名称：银行卡，微信，支付宝
status | tinyint(4) | 是 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) unsigned | 是 | 0 | 创建时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

### rf_member_credits_log
#### 会员_积分等变动表
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) | 否 | | 无
merchant_id | int(10) unsigned | 是 | 0 | 商户id
member_id | int(11) unsigned | 是 | 0 | 用户id
pay_type | tinyint(4) | 是 | 0 | 支付类型
credit_type | varchar(30) | 否 | | 变动类型[integral:积分;money:余额]
app_id | varchar(50) | 是 | | 应用id
credit_group | varchar(30) | 是 | | 变动的组别
addons_name | varchar(100) | 否 | | 插件名称
old_num | decimal(10,2) | 是 | 0.00 | 之前的数据
new_num | decimal(10,2) | 是 | 0.00 | 变动后的数据
num | decimal(10,2) | 是 | 0.00 | 变动的数据
remark | varchar(200) | 是 | | 备注
ip | varchar(30) | 是 | | ip地址
map_id | int(10) | 是 | 0 | 关联id
status | tinyint(4) | 是 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) unsigned | 是 | 0 | 创建时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

### rf_member_invoice
####
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(11) unsigned | 否 | | 无
merchant_id | int(10) unsigned | 是 | 0 | 商户id
member_id | int(11) unsigned | 是 | 0 | 用户id
title | varchar(200) | 是 | | 公司抬头
duty_paragraph | varchar(200) | 是 | | 税号
is_default | tinyint(2) unsigned | 是 | 0 | 默认
type | tinyint(4) | 是 | 1 | 类型 1企业 2个人
status | tinyint(4) | 是 | 1 | 状态(-1:已删除,0:禁用,1:正常)
created_at | int(10) unsigned | 是 | | 创建时间
updated_at | int(10) unsigned | 是 | | 修改时间

### rf_member_level
#### 会员等级表
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(11) | 否 | | 主键
merchant_id | int(11) unsigned | 是 | 0 | 商户id
level | int(11) | 是 | 0 | 等级（数字越大等级越高）
name | varchar(255) | 是 | | 等级名称
money | decimal(10,2) | 是 | 0.00 | 消费额度满足则升级
check_money | tinyint(1) unsigned | 是 | 0 | 选中消费额度
integral | int(11) | 是 | 0 | 消费积分满足则升级
check_integral | tinyint(1) unsigned | 是 | 0 | 选中消费积分
middle | tinyint(1) | 是 | 0 | 条件（0或 1且）
discount | decimal(10,1) | 是 | 10.0 | 折扣
status | int(11) | 是 | 1 | 状态[-1:删除;0:禁用;1启用]
detail | varchar(255) | 是 | | 会员介绍
created_at | int(10) unsigned | 是 | 0 | 创建时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

### rf_member_recharge_config
#### 会员_充值配置
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) unsigned | 否 | | 无
merchant_id | int(11) | 是 | 0 | 商户
price | decimal(10,2) unsigned | 是 | 0.00 | 充值金额
give_price | decimal(10,2) unsigned | 是 | 0.00 | 赠送金额
sort | int(5) | 是 | 0 | 排序
status | tinyint(4) | 是 | 1 | 状态
created_at | int(10) unsigned | 否 | 0 | 创建时间
updated_at | int(10) unsigned | 否 | 0 | 更新时间

### rf_merchant
####
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) unsigned | 否 | | 无
title | varchar(200) | 是 | | 商户名称
user_money | decimal(10,2) | 是 | 0.00 | 当前余额
accumulate_money | decimal(10,2) | 是 | 0.00 | 累计余额
give_money | decimal(10,2) | 是 | 0.00 | 累计赠送余额
consume_money | decimal(10,2) | 是 | 0.00 | 累计消费金额
frozen_money | decimal(10,2) | 是 | 0.00 | 冻结金额
term_of_validity_type | int(1) | 是 | 0 | 有效期类型 0固定时间 1不限
start_time | int(10) | 是 | 0 | 开始时间
end_time | int(10) | 是 | 0 | 结束时间
status | tinyint(4) | 是 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) unsigned | 是 | 0 | 创建时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

### rf_merchant_member
#### 系统_后台管理员表
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(11) | 否 | | 无
merchant_id | int(10) unsigned | 是 | 0 | 商户id
username | varchar(20) | 否 | | 帐号
password_hash | varchar(150) | 否 | | 密码
auth_key | varchar(32) | 否 | | 授权令牌
password_reset_token | varchar(150) | 是 | | 密码重置令牌
type | tinyint(1) | 是 | 1 | 1:普通管理员;10超级管理员
realname | varchar(10) | 是 | | 真实姓名
head_portrait | char(150) | 是 | | 头像
gender | tinyint(1) unsigned | 是 | 0 | 性别[0:未知;1:男;2:女]
qq | varchar(20) | 是 | | qq
email | varchar(60) | 是 | | 邮箱
birthday | date | 是 | | 生日
province_id | int(10) | 是 | 0 | 省
city_id | int(10) | 是 | 0 | 城市
area_id | int(10) | 是 | 0 | 地区
address | varchar(100) | 是 | | 默认地址
mobile | varchar(20) | 是 | | 手机号码
home_phone | varchar(20) | 是 | | 家庭号码
dingtalk_robot_token | varchar(100) | 是 | | 钉钉机器人token
visit_count | smallint(5) unsigned | 是 | 0 | 访问次数
last_time | int(10) | 是 | 0 | 最后一次登录时间
last_ip | varchar(16) | 是 | | 最后一次登录ip
role | smallint(6) | 否 | 10 | 权限
status | tinyint(4) | 是 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) unsigned | 是 | 0 | 创建时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

### rf_oauth2_access_token
#### oauth2_授权令牌
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(10) unsigned | 否 | | 无
access_token | varchar(80) | 否 | | 无
merchant_id | int(10) unsigned | 是 | 0 | 商户id
client_id | varchar(64) | 否 | | 无
member_id | varchar(100) | 是 | | 无
expires | timestamp | 否 | | 无
scope | json | 是 | | 无
grant_type | varchar(30) | 是 | | 组别
status | tinyint(4) | 是 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) unsigned | 是 | 0 | 创建时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

### rf_oauth2_authorization_code
#### oauth2_授权回调code
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
authorization_code | varchar(100) | 否 | | 无
merchant_id | int(10) unsigned | 是 | 0 | 商户id
client_id | varchar(64) | 否 | | 无
member_id | varchar(100) | 是 | | 无
redirect_uri | varchar(2000) | 是 | | 无
expires | timestamp | 否 | | 无
scope | json | 是 | | 无
status | tinyint(4) | 是 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) unsigned | 是 | 0 | 创建时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

### rf_oauth2_client
#### oauth2_授权客户端
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
id | int(11) unsigned | 否 | | 无
merchant_id | int(10) unsigned | 是 | 0 | 商户id
title | varchar(100) | 否 | | 标题
client_id | varchar(64) | 否 | | 无
client_secret | varchar(64) | 否 | | 无
redirect_uri | varchar(2000) | 否 | | 回调Url
remark | varchar(200) | 是 | | 备注
group | varchar(30) | 是 | | 组别
status | tinyint(4) | 是 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) unsigned | 是 | 0 | 创建时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

### rf_oauth2_refresh_token
#### oauth2_授权令牌
字段 | 类型 | 允许为空 | 默认值 | 字段说明
---|---|---|---|---
refresh_token | varchar(80) | 否 | | 无
merchant_id | int(10) unsigned | 是 | 0 | 商户id
client_id | varchar(64) | 否 | | 无
member_id | varchar(100) | 是 | | 无
expires | timestamp | 否 | | 无
scope | json | 是 | | 无
grant_type | varchar(30) | 是 | | 组别
status | tinyint(4) | 是 | 1 | 状态[-1:删除;0:禁用;1启用]
created_at | int(10) unsigned | 是 | 0 | 创建时间
updated_at | int(10) unsigned | 是 | 0 | 修改时间

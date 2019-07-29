<?php

namespace common\enums;

/**
 * Class CacheKeyEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class CacheKeyEnum
{
    const API_MINI_PROGRAM_LOGIN = 'api:mini-program:auth:'; // 小程序授权
    const API_ACCESS_TOKEN = 'api:access-token:'; // 用户信息记录
    const SYS_CONFIG = 'sys:config'; // 公用参数
    const SYS_PROBE = 'sys:probe'; // 系统探针
    const WECHAT_FANS_STAT = 'wechat:fans:stat:'; // 粉丝统计缓存
    const COMMON_ADDONS = 'common:addons:'; // 插件
    const COMMON_ADDONS_CONFIG = 'common:addons-config:'; // 插件配置
    const COMMON_PROVINCES = 'common:provinces:'; // 省市区
    const COMMON_IP_BLACKLIST = 'common:ip-blacklist:'; // ip黑名单
    const COMMON_ACTION_BEHAVIOR = 'common:action-behavior'; // 需要被记录的行为
}
<?php

namespace common\helpers;

use common\enums\AppEnum;
use Yii;

/**
 * Class WechatHelper
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class WechatHelper
{
    /**
     * 验证token是否一致
     *
     * @param string $signature 微信加密签名，signature结合了开发者填写的token参数和请求中的timestamp参数、nonce参数
     * @param integer $timestamp 时间戳
     * @param integer $nonce 随机数
     * @return bool
     */
    public static function verifyToken($signature, $timestamp, $nonce)
    {
        $config = Yii::$app->debris->configAll(true);

        $token = $config['wechat_token'] ?? '';
        $tmpArr = [$token, $timestamp, $nonce];
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        return $tmpStr == $signature ? true : false;
    }

    /**
     * 生成微信分享代码
     *
     * @param array $attr
     *    [
     *         'title'=>标题,
     *         'desc'=>内容,
     *         'url'=>跳转网址,
     *         'img'=>图片
     *     ]
     * @param bool $importJs 默认导入js，false则不导入
     * @return string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public static function share(array $attr, $importJs = true)
    {
        if (!Yii::$app->wechat->getIsWechat()) {
            return '';
        }

        // 截止到Mar 29, 2019, 17:00，android版本的分享新接口还是失效，需要将老接口加入进去申明列表，则功能正常
        $jsapiConfig = Yii::$app->wechat->app->jssdk->buildConfig([
            'updateAppMessageShareData',
            'updateTimelineShareData',
            'onMenuShareTimeline',
            'onMenuShareAppMessage'
        ], false);
        // 是否引入js
        $importJs = $importJs
            ? '<script src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js" type="text/javascript" charset="utf-8"></script>'
            : '';
        $str
            = <<< EOF
			$importJs
			<script type="text/javascript" charset="utf-8">
				wx.config($jsapiConfig);
				wx.ready(function(){
			
					//“分享给朋友”及“分享到QQ”
					wx.updateAppMessageShareData({
						title: '{$attr['title']}', // 分享标题
						desc:   '{$attr['desc']}', // 分享描述
						link:   '{$attr['url']}', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
						imgUrl: '{$attr['img']}', // 分享图标
						success: function () {
							// 设置成功
						}
					});
			
					//“分享到朋友圈”及“分享到QQ空间”
					wx.updateTimelineShareData({
						title: '{$attr['title']}', // 分享标题
						link:   '{$attr['url']}', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
						imgUrl: '{$attr['img']}', // 分享图标
						success: function () {
							// 设置成功
						}
					})
			
				});
			</script>
EOF;
        return $str;
    }

    /**
     * 告诉微信已经成功了
     *
     * @return bool|string
     */
    public static function success()
    {
        return ArrayHelper::toXml(['return_code' => 'SUCCESS', 'return_msg' => 'OK']);
    }

    /**
     * 告诉微信失败了
     *
     * @return bool|string
     */
    public static function fail()
    {
        return ArrayHelper::toXml(['return_code' => 'FAIL', 'return_msg' => 'OK']);
    }
}
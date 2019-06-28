<?php
namespace backend\modules\wechat\userapis;

use Yii;
use backend\interfaces\AddonWidget;

/**
 * 系统默认天气Api Demo
 *
 * Class WeatherApi
 * @package backend\modules\wechat\userapis
 * @author jianyan74 <751393839@qq.com>
 */
class WeatherApi implements AddonWidget
{
    /**
     * 接口案例
     *
     * 请在自定义接口设置匹配关键字 (.+)天气$
     * @param array $message 微信用户传递过来的消息
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function run($message)
    {
        if (!isset($message['Content'])) {
            return '小伙子你过分了，给点内容好不好';
        }

        $ret = preg_match('/(.+)天气/i', $message['Content'], $matchs);
        if(!$ret) {
            return '请输入合适的格式, 城市+天气, 例如: 北京天气';
        }

        return $message['Content'] . Yii::$app->formatter->asDatetime(time());
    }
}

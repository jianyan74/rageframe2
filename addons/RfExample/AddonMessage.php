<?php
namespace addons\RfExample;

use Yii;
use backend\interfaces\WechatMessageInterface;

/**
 * AddonMessage
 *
 * Class AddonMessage
 * @package addons\RfExample
 */
class AddonMessage implements WechatMessageInterface
{
    /**
     * @param $message
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function run($message)
    {
        return '示例模块' . Yii::$app->formatter->asDatetime(time());
    }
}
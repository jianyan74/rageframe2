<?php

namespace addons\Merchants;

use Yii;
use common\interfaces\AddonWidget;

/**
 * 微信消息处理
 *
 * Class AddonMessage
 * @package addons\Merchants */
class AddonMessage implements AddonWidget
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
<?php
namespace common\models\wechat;

use Yii;
use common\helpers\AddonHelper;
use common\helpers\ExecuteHelper;

/**
 * Class WechatMessage
 * @package common\models\wechat
 */
class WechatMessage
{
    /**
     * 文字匹配回复
     *
     * @return bool|mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public static function text()
    {
        // 查询用户关键字匹配
        if (!($reply = RuleKeyword::match(Yii::$app->params['wechatMessage']['Content'])))
        {
            $replyDefault = ReplyDefault::getFirstData();
            if ($replyDefault->default_content)
            {
                $reply = RuleKeyword::match($replyDefault->default_content);
            }
        }

        return $reply;
    }

    /**
     * 关注匹配回复
     *
     * @return bool|mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public static function follow()
    {
        $replyDefault = ReplyDefault::getFirstData();
        if ($replyDefault->follow_content)
        {
            return RuleKeyword::match($replyDefault->follow_content);
        }

        return false;
    }

    /**
     * 其他匹配回复
     *
     * @return bool|mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public static function other()
    {
        $message = Yii::$app->params['wechatMessage'];

        $msgType = $message['MsgType'];
        $special = Setting::getData('special');
        if (isset($special[$msgType]))
        {
            // 关键字
            if ($special[$msgType]['type'] == Setting::SPECIAL_TYPE_KEYWORD)
            {
                if ($default = RuleKeyword::match($special[$msgType]['content']))
                {
                    return $default;
                }
            }

            // 模块处理
            if (!empty($special[$msgType]['selected']))
            {
                $class = AddonHelper::getAddonMessage($special[$msgType]['selected']);
                return ExecuteHelper::map($class, 'run', $message);
            }
        }

        return false;
    }
}

<?php
namespace api\behaviors;

use Yii;
use yii\base\Behavior;
use yii\web\Controller;
use yii\web\UnprocessableEntityHttpException;
use common\helpers\EncryptionHelper;

/**
 * http 签名验证
 *
 * Class HttpSignAuth
 * @package api\behaviors
 * @author jianyan74 <751393839@qq.com>
 */
class HttpSignAuth extends Behavior
{
    /**
     * @return array
     */
    public function events()
    {
        return [Controller::EVENT_BEFORE_ACTION => 'beforeAction'];
    }

    /**
     * @param $event
     * @return bool
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function beforeAction($event)
    {
        if (false === Yii::$app->params['user.httpSignValidity'])
        {
            return true;
        }

        $appId = Yii::$app->request->get('appId', null);

        if (!$appId)
        {
            throw new UnprocessableEntityHttpException('缺少 appId 参数');
        }

        if (!isset(Yii::$app->params['user.httpSignAccount'][$appId]))
        {
            throw new UnprocessableEntityHttpException('appId 无效');
        }

        return EncryptionHelper::decodeUrlParam(Yii::$app->request->get(), Yii::$app->params['user.httpSignAccount'][$appId]);
    }
}
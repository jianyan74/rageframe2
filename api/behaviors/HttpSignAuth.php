<?php

namespace api\behaviors;

use Yii;
use yii\base\Behavior;
use yii\web\Controller;
use yii\web\UnprocessableEntityHttpException;
use common\helpers\EncryptionHelper;
use api\forms\SignAuthForm;

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
     * @var bool
     */
    public $switch = false;

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
     * @throws UnprocessableEntityHttpException
     */
    public function beforeAction($event)
    {
        if (false === $this->switch) {
            return true;
        }

        $data = Yii::$app->request->get();
        $model = new SignAuthForm();
        $model->attributes = $data;
        if (!$model->validate()) {
            throw new UnprocessableEntityHttpException(Yii::$app->debris->analyErr($model->getFirstErrors()));
        }

        return EncryptionHelper::decodeUrlParam($data, $model->appSecret);
    }
}
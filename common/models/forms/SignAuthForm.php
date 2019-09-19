<?php

namespace common\models\forms;

use Yii;
use yii\base\Model;

/**
 * Class SignAuthForm
 * @package api\forms
 * @author jianyan74 <751393839@qq.com>
 */
class SignAuthForm extends Model
{
    public $time;
    public $sign;
    public $nonceStr;
    public $appId;

    public $appSecret;

    /**
     * 时间过期容差
     *
     * @var int
     */
    public $toleranceTime = 60;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['time', 'sign', 'appId', 'nonceStr'], 'required'],
            [['nonceStr'], 'string', 'min' => 8],
            [['time'], 'isPastDue'],
            [['appId'], 'isAuth'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'time' => '时间戳',
            'nonceStr' => '随机数',
            'sign' => '签名',
            'appId' => 'appId',
        ];
    }

    /**
     * @param $attribute
     */
    public function isPastDue($attribute)
    {
        if (time() - $this->toleranceTime > $this->time) {
            $this->addError($attribute, '时间已过期');
        }
    }

    /**
     * @param $attribute
     */
    public function isAuth($attribute)
    {
        if (!isset(Yii::$app->params['user.httpSignAccount'][$this->appId])) {
            $this->addError($attribute, 'appId 无效');
        }

        $this->appSecret = Yii::$app->params['user.httpSignAccount'][$this->appId];
    }
}
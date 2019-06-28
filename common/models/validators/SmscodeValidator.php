<?php
namespace common\models\validators;

use common\enums\StatusEnum;
use common\models\common\SmsLog;
use yii\validators\Validator;

/**
 * 短信验证码验证器
 *
 * 在rule使用：
 *
 * ['verifyCode', '\common\models\validators\SmsCodeValidator', 'usage' => 'userRegister'],
 *
 * Class SmsCodeValidator
 * @package common\models\common
 * @author jianyan74 <751393839@qq.com>
 */
class SmsCodeValidator extends Validator
{
    /**
     * 对应Smslog表中的usage字段，用来匹配不同用途的验证码
     *
     * @var string sms code type
     */
    public $usage;

    /**
     * Model或者form中提交的手机号字段名称
     *
     * @var string
     */
    public $phoneAttribute = 'mobile';

    /**
     * 验证码过期时间
     *
     * @var int
     */
    public $expireTime = 60 * 15;

    /**
     * @param \yii\base\Model $model
     * @param string $attribute
     */
    public function validateAttribute($model, $attribute)
    {
        $fieldName = $this->phoneAttribute;
        $cellPhone = $model->$fieldName;

        $smsLog = SmsLog::find()->where([
            'mobile' => $cellPhone,
            'error_code' => 200,
            'used' => StatusEnum::DISABLED,
            'usage' => $this->usage,
        ])->orderBy('id desc')->one();

        /** @var $smsLog SmsLog */
        $time = time();
        if (
            is_null($smsLog) ||
            ($smsLog->code != $model->$attribute) ||
            ($smsLog->created_at > $time || $time > ($smsLog->created_at + $this->expireTime) )
        ) {
            $this->addError($model, $attribute, '验证码错误');
        } else {
            $smsLog->used = StatusEnum::ENABLED;
            $smsLog->use_time = $time;
            $smsLog->save();
        }
    }
}

<?php
namespace api\modules\v1\models;

use Yii;
use common\models\member\MemberInfo;

/**
 * Class AddressForm
 * @package api\modules\v1\models
 */
class AddressForm extends \common\models\member\Address
{
    /**
     * 是否设置为默认地址
     *
     * @var
     */
    public $is_default;

    public function rules()
    {
        $rule = parent::rules();
        $rule[] = [['is_default'], 'integer'];

        return $rule;
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert && $this->is_default)
        {
            MemberInfo::setDefaultAddress(Yii::$app->user->identity->member_id, $this->id);
        }

        parent::afterSave($insert, $changedAttributes);
    }
}

<?php

namespace addons\Merchants\backend\forms;

use Yii;
use yii\base\Model;
use common\enums\AppEnum;
use common\enums\MerchantStateEnum;
use common\helpers\ArrayHelper;
use common\models\merchant\Merchant;


/**
 * Class PassFrom
 * @package addons\Merchants\backend\forms
 * @author jianyan74 <751393839@qq.com>
 */
class PassFrom extends Model
{
    public $role_id;

    public $merchant_id;
    /**
     * @var Merchant
     */
    public $merchant;

    public function rules()
    {
        return [
            [['role_id', 'merchant_id'], 'integer'],
            [['merchant_id'], 'required'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'role_id' => '授权角色',
        ];
    }

    /**
     * @return bool
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function pass()
    {
        if ($this->merchant->state != MerchantStateEnum::AUDIT) {
            return false;
        }

        $this->merchant->state = MerchantStateEnum::ENABLED;
        $this->merchant->save();

        // 分配角色
        if ($this->role_id) {
            $members = Yii::$app->services->merchantMember->findByMerchantId($this->merchant->id);
            foreach ($members as $member) {
                // 角色授权
                Yii::$app->services->rbacAuthAssignment->assign([$this->role_id], $member['id'], AppEnum::MERCHANT);
            }
        }

        return true;
    }
}
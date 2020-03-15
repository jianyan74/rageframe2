<?php

namespace addons\Merchants\common\models\forms;

use Yii;
use yii\base\Model;
use common\models\merchant\Merchant;
use common\models\merchant\BankAccount;
use common\enums\AccountTypeEnum;
use common\helpers\StringHelper;
use common\enums\StatusEnum;
use common\models\forms\MerchantCreditsLogForm;
use common\models\merchant\CommissionWithdraw;
use addons\Merchants\common\models\SettingForm;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class CommissionWithdrawForm
 * @package addons\TinyDistribution\common\models\forms
 * @author jianyan74 <751393839@qq.com>
 */
class CommissionWithdrawForm extends Model
{
    /**
     * @var int
     */
    public $bank_account_id;
    /**
     * @var double
     */
    public $money;
    /**
     * @var
     */
    public $merchant_id;
    /**
     * @var Merchant
     */
    public $merchant;
    /**
     * @var SettingForm
     */
    public $config;
    /**
     * @var BankAccount
     */
    protected $bank_account;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bank_account_id', 'money'], 'required'],
            [['bank_account_id'], 'integer'],
            [['money'], 'number', 'min' => $this->config->withdraw_lowest_money, 'max' => $this->merchant->account->user_money],
            [['bank_account_id'], 'verifyBankAccount'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'bank_account_id' => '提现账号',
            'money' => '金额',
        ];
    }

    /**
     * @param $attribute
     */
    public function verifyBankAccount($attribute)
    {
        if (!($this->bank_account = Yii::$app->services->merchantBankAccount->findById($this->bank_account_id))) {
            $this->addError($attribute, '找不到提现账号');

            return;
        }

        if (!in_array($this->bank_account->account_type, $this->config->withdraw_account)) {
            $this->addError($attribute, '不支持该提现账号类型');
        }

        if ($this->merchant->account->user_money < $this->money) {
            $this->addError($attribute, '余额不足');
        }

        if ($this->config->withdraw_is_open == StatusEnum::DISABLED) {
            $this->addError($attribute, '未开启提现');
        }
    }

    /**
     * @throws UnprocessableEntityHttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function save()
    {
        $model = new CommissionWithdraw();
        $model->cash = $this->money;
        $model->merchant_id = $this->merchant_id;
        $model->withdraw_no = StringHelper::randomNum(time());
        $model->account_type = $this->bank_account->account_type;
        $model->bank_name = $this->bank_account->branch_bank_name;
        $model->account_type != AccountTypeEnum::UNION && $model->bank_name = AccountTypeEnum::getValue($this->bank_account->account_type);
        $model->account_number = $this->bank_account->account_number;
        $model->ali_number = $this->bank_account->ali_number;
        $model->realname = $this->bank_account->realname;
        $model->mobile = $this->bank_account->mobile;
        if (!$model->save()) {
            throw new UnprocessableEntityHttpException(Yii::$app->debris->analyErr($model->getFirstErrors()));
        }

        // 记录提现
        Yii::$app->services->merchantCreditsLog->decrMoney(new MerchantCreditsLogForm([
            'merchant' => $this->merchant,
            'num' => $this->money,
            'credit_group' => 'withdraw',
            'remark' => '余额提现',
            'map_id' => $model->id,
        ]));
    }
}
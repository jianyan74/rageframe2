<?php

namespace merchant\forms;

use common\enums\MerchantStateEnum;
use Yii;
use yii\base\Model;
use yii\web\NotFoundHttpException;
use yii\helpers\Html;
use common\enums\StatusEnum;
use common\enums\WhetherEnum;
use common\models\merchant\Member;
use common\models\merchant\Merchant;

/**
 * Class SignUpForm
 * @package merchant\forms
 * @author jianyan74 <751393839@qq.com>
 */
class SignUpForm extends Model
{
    public $id;
    public $title;
    public $username;
    public $cate_id;
    public $mobile;
    public $company_name;
    public $password;
    public $re_pass;
    public $rememberMe;

    protected $member;
    protected $merchant;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['cate_id'], 'integer'],
            [['rememberMe'], 'isRequired'],
            [['title', 'cate_id', 'company_name', 'username', 'mobile', 'password', 're_pass'], 'required'],
            ['mobile', 'string', 'max' => 15],
            [['title', 'company_name'], 'unique', 'targetClass' => '\common\models\merchant\Merchant', 'message' => '{attribute}已经被占用.'],
            ['mobile', 'unique', 'targetClass' => '\common\models\merchant\Member', 'message' => '该手机号码已经被占用.'],
            ['mobile', 'match', 'pattern' => '/^1[3456789]\d{9}$/', 'message' => '手机号码格式不正确'],
            [['username'], 'unique', 'targetClass' => '\common\models\merchant\Member', 'message' => '该用户名已经被占用了.'],
            [
                'username',
                'match',
                'pattern' => '/^[(\x{4E00}-\x{9FA5})a-zA-Z]+[(\x{4E00}-\x{9FA5})a-zA-Z_\d]*$/u',
                'message' => '用户名由字母，汉字，数字，下划线组成，且不能以数字和下划线开头。',
            ],
            ['username', 'string', 'min' => 6, 'max' => 20],
            [['password', 're_pass'], 'string', 'min' => 6, 'max' => 20],
            ['re_pass', 'compare', 'compareAttribute' => 'password', 'message' => '两次输入的密码不一致'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'title' => '商家名称',
            'cate_id' => '商家分类',
            'username' => '商家账户',
            'mobile' => '手机号码',
            'company_name' => '公司名称',
            'password' => '账户密码',
            're_pass' => '确认密码',
            'rememberMe' => '',
        ];
    }

    /**
     * @param $attribute
     */
    public function isRequired($attribute)
    {
        if (empty($this->rememberMe)) {
            $this->addError($attribute, '请同意商家入驻协议');
        }
    }

    /**
     * @return bool|Merchant
     */
    public function register()
    {
        // 事务
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $merchant = new Merchant();
            $merchant = $merchant->loadDefaultValues();
            $merchant->title = $this->title;
            $merchant->cate_id = $this->cate_id;
            $merchant->mobile = $this->mobile;
            $merchant->company_name = $this->company_name;
            $merchant->status = StatusEnum::ENABLED;
            //判断商家注册是否开启审核
            if (Yii::$app->debris->backendConfig('merchant_register_audit') == WhetherEnum::ENABLED) {
                $merchant->state = MerchantStateEnum::ENABLED;
            } else {
                $merchant->state = MerchantStateEnum::AUDIT;
            }

            if (!$merchant->save()) {
                $this->addErrors($merchant->getErrors());
                throw new NotFoundHttpException('商户信息编辑错误');
            }

            $member = new Member();
            $member->merchant_id = $merchant->id;
            $member->username = $this->username;
            $member->mobile = $this->mobile;
            $member->password_hash = Yii::$app->security->generatePasswordHash($this->password);

            if (!$member->save()) {
                $this->addErrors($member->getErrors());
                throw new NotFoundHttpException('用户信息编辑错误');
            }

            $transaction->commit();

            return $merchant;
        } catch (\Exception $e) {
            $transaction->rollBack();

            return false;
        }
    }
}
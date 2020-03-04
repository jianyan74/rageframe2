<?php

namespace merchant\forms;


use common\enums\StatusEnum;
use common\enums\WhetherEnum;
use common\models\merchant\Member;
use common\models\merchant\Merchant;
use Yii;
use yii\base\Model;
use yii\web\NotFoundHttpException;

class SignUpForm extends Model
{
    public $id;
    public $title;
    public $username;
    public $category_id;
    public $mobile;
    public $email;
    public $password;
    public $re_pass;

    protected $member;

    public function rules()
    {
        return [
            [['category_id'], 'integer'],
            [['title','username', 'email'], 'trim'],
            [['title', 'category_id', 'email', 'username', 'mobile', 'password', 're_pass'], 'required'],
            ['mobile', 'string', 'max' => 15],
            ['mobile', 'unique', 'targetClass' => '\common\models\merchant\Member', 'message' => '该手机号码已经被占用.'],
            ['mobile', 'match', 'pattern' => '/^1[3456789]\d{9}$/','message' => '手机号码格式不正确'],
            [['username'], 'unique', 'targetClass' => '\common\models\merchant\Member', 'message' => '该用户名已经被占用了.'],
            ['username', 'match','pattern'=>'/^[(\x{4E00}-\x{9FA5})a-zA-Z]+[(\x{4E00}-\x{9FA5})a-zA-Z_\d]*$/u','message'=>'用户名由字母，汉字，数字，下划线组成，且不能以数字和下划线开头。'],
            ['username', 'string', 'min' => 2, 'max' => 20],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\merchant\Member', 'message' => '这个邮箱地址已经被占用了.'],
            [['password', 're_pass'],'string', 'min' => 6, 'max' => 20],
            ['re_pass', 'compare', 'compareAttribute' => 'password','message'=>'两次输入的密码不一致'],
        ];
    }

    public function isUnique()
    {
        $member = Member::findOne(['username' => $this->username]);
        if ($member && $member->id != $this->id) {
            $this->addError('username', '用户名称已经被占用');
        }
    }



    public function register()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $merchant = new Merchant();
            $merchant->title = $this->title;
            //判断商家注册是否开启审核
            if( Yii::$app->debris->config('merchant_register_audit') == WhetherEnum::ENABLED ){
                $merchant->status = StatusEnum::DISABLED;
            }else{
                $merchant->status = StatusEnum::ENABLED;
            }

            if (!$merchant->save()) {
                $this->addErrors($merchant->getErrors());
                throw new NotFoundHttpException('商户信息编辑错误');
            }
            $member = new Member();
            $member -> merchant_id = $merchant->attributes['id'];
            $member ->username = $this->username;
            $member ->mobile = $this->mobile;
            $member ->email = $this->email;
            $member->password_hash = Yii::$app->security->generatePasswordHash($this->password);;
            if (!$member->save()) {
                $this->addErrors($member->getErrors());
                throw new NotFoundHttpException('用户信息编辑错误');
            }
            $transaction->commit();
            return true;
        }catch (\Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }

    public function attributeLabels()
    {
        return [
            'title' => '商家名称',
            'category_id' => '商家分类',
            'username' => '商家账户',
            'mobile' => '手机号码',
            'email' => '电子邮箱',
            'password' => '账户密码',
            're_pass' => '确认密码',
        ];
    }
}
<?php

namespace addons\Merchants\merchant\modules\base\forms;

use Yii;
use yii\base\Model;
use yii\web\NotFoundHttpException;
use common\models\rbac\AuthRole;
use common\models\merchant\Member;

/**
 * Class MemberForm
 * @package merchant\modules\base\models
 * @author jianyan74 <751393839@qq.com>
 */
class MemberForm extends Model
{
    public $id;
    public $password;
    public $username;
    public $role_id;

    /**
     * @var \common\models\merchant\Member
     */
    protected $member;

    /*
     * @var \common\models\merchant\AuthItem
     */
    protected $authItemModel;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['password', 'username'], 'required'],
            ['password', 'string', 'min' => 6],
            [
                ['role_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => AuthRole::class,
                'targetAttribute' => ['role_id' => 'id'],
            ],
            [['username'], 'isUnique'],
            [['role_id'], 'required'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'password' => '登录密码',
            'username' => '登录名',
            'role_id' => '角色',
        ];
    }

    /**
     * 加载默认数据
     */
    public function loadData()
    {
        if ($this->member = Yii::$app->services->merchantMember->findByIdWithAssignment($this->id)) {
            $this->username = $this->member->username;
            $this->password = $this->member->password_hash;
        } else {
            $this->member = new Member();
        }

        $this->role_id = $this->member->assignment->role_id ?? '';
    }

    /**
     * 场景
     *
     * @return array
     */
    public function scenarios()
    {
        return [
            'default' => ['username', 'password'],
            'generalAdmin' => array_keys($this->attributeLabels()),
        ];
    }

    /**
     * 验证用户名称
     */
    public function isUnique()
    {
        $member = Member::findOne(['username' => $this->username]);
        if ($member && $member->id != $this->id) {
            $this->addError('username', '用户名称已经被占用');
        }
    }

    /**
     * @return bool
     * @throws \yii\db\Exception
     */
    public function save()
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $member = $this->member;
            if ($member->isNewRecord) {
                $member->last_ip = '0.0.0.0';
                $member->last_time = time();
            }
            $member->username = $this->username;

            // 验证密码是否修改
            if ($this->member->password_hash != $this->password) {
                $member->password_hash = Yii::$app->security->generatePasswordHash($this->password);;
            }

            if (!$member->save()) {
                $this->addErrors($member->getErrors());
                throw new NotFoundHttpException('用户编辑错误');
            }

            // 验证超级管理员
            if ($this->id == Yii::$app->params['adminAccount']) {
                $transaction->commit();

                return true;
            }

            // 角色授权
            Yii::$app->services->rbacAuthAssignment->assign([$this->role_id], $member->id, Yii::$app->id);

            $transaction->commit();

            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();

            return false;
        }
    }
}
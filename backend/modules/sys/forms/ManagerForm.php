<?php

namespace backend\modules\sys\forms;

use Yii;
use yii\base\Model;
use yii\web\NotFoundHttpException;
use common\models\common\AuthRole;
use common\models\sys\Manager;

/**
 * Class ManagerForm
 * @package backend\modules\sys\models
 * @author jianyan74 <751393839@qq.com>
 */
class ManagerForm extends Model
{
    public $id;
    public $password;
    public $username;
    public $role_id;

    /**
     * @var \common\models\sys\Manager
     */
    protected $managerModel;

    /*
     * @var \common\models\sys\AuthItem
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
                'targetAttribute' => ['role_id' => 'id']
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
        $this->managerModel = Manager::find()
            ->where(['id' => $this->id])
            ->with('assignment')
            ->one();

        if ($this->managerModel) {
            $this->username = $this->managerModel->username;
            $this->password = $this->managerModel->password_hash;
        } else {
            $this->managerModel = new Manager();
        }

        $this->role_id = $this->managerModel->assignment->role_id ?? '';
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
        $manager = Manager::findOne(['username' => $this->username]);
        if ($manager && $manager->id != $this->id) {
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
            $manager = $this->managerModel;
            if ($manager->isNewRecord) {
                $manager->last_ip = '0.0.0.0';
                $manager->last_time = time();
            }
            $manager->username = $this->username;

            // 验证密码是否修改
            if ($this->managerModel->password_hash != $this->password) {
                $manager->password_hash = Yii::$app->security->generatePasswordHash($this->password);;
            }

            if (!$manager->save()) {
                $this->addErrors($manager->getErrors());
                throw new NotFoundHttpException('用户编辑错误');
            }

            // 验证超级管理员
            if ($this->id == Yii::$app->params['adminAccount']) {
                $transaction->commit();
                return true;
            }

            // 角色授权
            if (!Yii::$app->services->authAssignment->authorization($manager->id, $this->role_id, Yii::$app->id)) {
                throw new NotFoundHttpException('权限写入错误');
            }

            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }
}
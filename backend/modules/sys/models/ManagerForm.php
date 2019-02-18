<?php
namespace backend\modules\sys\models;

use Yii;
use yii\base\Model;
use common\models\sys\AuthAssignment;
use common\models\sys\AuthItem;
use common\models\sys\Manager;
use yii\web\NotFoundHttpException;

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
    public $auth_key;

    /**
     * @var \common\models\sys\Manager
     */
    protected $managerModel;

    /**
     * @var \common\models\sys\AuthAssignment
     */
    protected $authAssignment;

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
            [['auth_key'], 'exist', 'skipOnError' => true, 'targetClass' => AuthItem::class, 'targetAttribute' => ['auth_key' => 'key']],
            [['username'], 'isUnique'],
            [['auth_key'], 'required'],
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
            'auth_key' => '角色',
        ];
    }

    /**
     * 加载默认数据
     */
    public function loadData()
    {
        $this->managerModel = Manager::findOne($this->id);
        if ($this->managerModel)
        {
            $this->username = $this->managerModel->username;
            $this->password = $this->managerModel->password_hash;
        }
        else
        {
            $this->managerModel = new Manager();
        }

        $this->authAssignment = AuthAssignment::find()
            ->where(['user_id' => $this->id])
            ->with('itemName')
            ->one();

        if ($this->authAssignment)
        {
            $this->auth_key = $this->authAssignment->itemName->key;
        }
        else
        {
            $this->authAssignment = new AuthAssignment();
        }
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
        if ($manager && $manager->id != $this->id)
        {
            $this->addError('username', '用户名称已经被占用');
        }
    }

    /**
     * @return bool
     * @throws \yii\db\Exception
     */
    public function saveData()
    {
        $transaction = Yii::$app->db->beginTransaction();

        try
        {
            $manager = $this->managerModel;
            $manager->username = $this->username;

            // 验证密码是否修改
            if ($this->managerModel->password_hash != $this->password)
            {
                $manager->password_hash = Yii::$app->security->generatePasswordHash($this->password);;
            }

            if (!$manager->save())
            {
                $this->addErrors($manager->getErrors());
                throw new NotFoundHttpException('用户编辑错误');
            }

            // 验证超级管理员
            if ($this->id == Yii::$app->params['adminAccount'])
            {
                $transaction->commit();
                return true;
            }

            $authAssignment = $this->authAssignment;
            $authAssignment->user_id = $manager->id;
            $authAssignment->item_name = (AuthItem::findOne(['key' => $this->auth_key]))->name;
            $authAssignment->save();
            if (!$authAssignment->save())
            {
                $this->addErrors($authAssignment->getErrors());
                throw new NotFoundHttpException('权限写入错误');
            }

            $transaction->commit();
            return true;
        }
        catch (\Exception $e)
        {
            $transaction->rollBack();
            return false;
        }
    }
}
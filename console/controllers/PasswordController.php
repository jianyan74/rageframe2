<?php
namespace console\controllers;

use Yii;
use yii\helpers\Console;
use yii\console\Controller;
use common\models\sys\Manager;
use common\helpers\StringHelper;

/**
 * 密码初始化
 *
 * Class PasswordController
 * @package console\controllers
 */
class PasswordController extends Controller
{
    /**
     * 初始化
     *
     * @throws \yii\base\Exception
     */
    public function actionInit()
    {
        Yii::$app->set('user', [
            'class' => 'common\models\sys\Manager',
        ]);

        if ($model = Manager::findOne(['username' => 'admin'])) {
            $password_hash = StringHelper::random(10);
            $model->username = StringHelper::random(5);
            $model->password_hash = Yii::$app->security->generatePasswordHash($password_hash);
            if ($model->save()) {
                Console::output('username; ' . $model->username);
                Console::output('password; ' . $password_hash);
                exit();
            }

            Console::stdout('Password initialization failed');
            exit();
        }

        Console::stdout('密码已经初始化生成成功，如果想重新生成请删除数据库后重新执行数据迁移和密码初始化');
        exit();
    }
}
<?php

namespace console\controllers;

use Yii;
use yii\helpers\Console;
use yii\console\Controller;
use common\models\backend\Member;
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
        if ($model = Member::findOne(1)) {
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

        Console::stdout('Cannot find administrator');
        exit();
    }
}
<?php

namespace backend\controllers;

use common\behaviors\ActionLogBehavior;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\forms\LoginForm;

/**
 * Class SiteController
 * @package backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class SiteController extends Controller
{
    /**
     * 默认布局文件
     *
     * @var string
     */
    public $layout = "default";

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'captcha'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'actionLog' => [
                'class' => ActionLogBehavior::class
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'maxLength' => 6, // 最大显示个数
                'minLength' => 6, // 最少显示个数
                'padding' => 5, // 间距
                'height' => 32, // 高度
                'width' => 100, // 宽度
                'offset' => 4, // 设置字符偏移量
                'backColor' => 0xffffff, // 背景颜色
                'foreColor' => 0x62a8ea, // 字体颜色
            ]
        ];
    }

    /**
     * 登录
     *
     * @return string|\yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            // 记录行为日志
            Yii::$app->services->actionLog->create('login', '自动登录', false);

            return $this->goHome();
        }

        $model = new LoginForm();
        $model->loginCaptchaRequired();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            // 记录行为日志
            Yii::$app->services->actionLog->create('login', '账号登录', false);

            return $this->goHome();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @return \yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}

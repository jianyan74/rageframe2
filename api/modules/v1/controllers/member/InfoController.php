<?php
namespace api\modules\v1\controllers\member;

use Yii;
use yii\web\NotFoundHttpException;
use api\controllers\OnAuthController;
use common\enums\StatusEnum;
use common\models\member\MemberInfo;

/**
 * 会员接口
 *
 * Class InfoController
 * @package api\modules\v1\controllers\member
 */
class InfoController extends OnAuthController
{
    public $modelClass = 'common\models\member\MemberInfo';

    /**
     * 测试查询方法
     *
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function actionSearch()
    {
        return '测试查询';
    }

    /**
     * 单个显示
     *
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = MemberInfo::find()
            ->where(['id' => $id, 'status' => StatusEnum::ENABLED])
            ->select(['id', 'username', 'nickname', 'realname', 'head_portrait', 'sex', 'qq', 'email', 'birthday', 'user_money', 'user_integral', 'status', 'created_at'])
            ->asArray()
            ->one();

        if (!$model)
        {
            throw new NotFoundHttpException('请求的数据不存在或您的权限不足.');
        }

        return $model;
    }

    /**
     * 权限验证
     *
     * @param string $action 当前的方法
     * @param null $model 当前的模型类
     * @param array $params $_GET变量
     * @throws \yii\web\BadRequestHttpException
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        // 方法名称
        if (in_array($action, ['delete', 'index']))
        {
            throw new \yii\web\BadRequestHttpException('权限不足');
        }
    }
}

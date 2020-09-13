<?php

namespace api\modules\v1\controllers\common;

use api\controllers\OnAuthController;
use common\models\common\BankNumber;

/**
 * 提现银行卡列表
 *
 * Class BankNumberController
 * @package api\modules\v1\controllers\common
 * @author jianyan74 <751393839@qq.com>
 */
class BankNumberController extends OnAuthController
{
    /**
     * @var BankNumber
     */
    public $modelClass = BankNumber::class;

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
        if (in_array($action, ['delete', 'create', 'update'])) {
            throw new \yii\web\BadRequestHttpException('权限不足');
        }
    }
}
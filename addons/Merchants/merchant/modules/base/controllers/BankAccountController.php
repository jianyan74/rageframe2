<?php

namespace addons\Merchants\merchant\modules\base\controllers;

use Yii;
use common\models\base\SearchModel;
use common\enums\StatusEnum;
use addons\Merchants\common\models\forms\BankAccountForm;
use addons\Merchants\merchant\controllers\BaseController;
use common\traits\MerchantCurd;

/**
 * Class BankAccountController
 * @package addons\Merchants\merchant\modules\base\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class BankAccountController extends BaseController
{
    use MerchantCurd;

    /**
     * @var BankAccountForm
     */
    public $modelClass = BankAccountForm::class;

    /**
     * 积分日志
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'partialMatchAttributes' => [], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['merchant_id' => $this->getMerchantId()]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
}
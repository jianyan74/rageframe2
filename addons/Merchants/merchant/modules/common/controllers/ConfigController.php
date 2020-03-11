<?php

namespace addons\Merchants\merchant\modules\common\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use common\models\common\Config;
use common\helpers\ResultHelper;
use common\traits\Curd;
use common\enums\AppEnum;
use addons\Merchants\merchant\controllers\BaseController;

/**
 * Class ConfigController
 * @package addons\Merchants\merchant\modules\common\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ConfigController extends BaseController
{
    use Curd;

    /**
     * @var \yii\db\ActiveRecord
     */
    public $modelClass = Config::class;

    /**
     * 网站设置
     *
     * @return string
     */
    public function actionEditAll()
    {
        return $this->render('@backend/modules/common/views/config/edit-all', [
            'cates' => Yii::$app->services->configCate->getItemsMergeForConfig(AppEnum::MERCHANT)
        ]);
    }

    /**
     * ajax批量更新数据
     *
     * @return array
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdateInfo()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            $config = $request->post('config', []);
            Yii::$app->services->config->updateAll(Yii::$app->id, $config);
            return ResultHelper::json(200, "修改成功");
        }

        throw new NotFoundHttpException('请求出错!');
    }
}
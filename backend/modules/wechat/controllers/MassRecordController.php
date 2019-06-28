<?php
namespace backend\modules\wechat\controllers;

use Yii;
use yii\data\Pagination;
use common\components\Curd;
use common\models\wechat\MassRecord;
use common\enums\StatusEnum;
use backend\modules\wechat\forms\SendForm;
use backend\controllers\BaseController;
use yii\web\UnprocessableEntityHttpException;

/**
 * 群发消息控制器
 *
 * Class MassRecordController
 * @package backend\modules\wechat\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class MassRecordController extends BaseController
{
    use Curd;

    /**
     * @var MassRecord
     */
    public $modelClass = MassRecord::class;

    /**
     * 首页
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $data = MassRecord::find()->filterWhere(['merchant_id' => $this->getMerchantId()]);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $this->pageSize]);
        $models = $data->offset($pages->offset)
            ->orderBy('id desc')
            ->limit($pages->limit)
            ->all();

        return $this->render($this->action->id, [
            'models' => $models,
            'pages' => $pages,
        ]);
    }

    /**
     * 编辑/创建
     *
     * @return mixed|string|\yii\web\Response
     * @throws UnprocessableEntityHttpException
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \yii\base\ExitException
     */
    public function actionEdit()
    {
        $request = Yii::$app->request;
        $id = $request->get('id', null);
        $model = $this->findModel($id);
        $model->send_status == StatusEnum::DISABLED && $model->send_type = 2;

        $this->activeFormValidate($model);
        if ($model->load($request->post())) {
            try {
                if (!$model->save()) {
                    throw new UnprocessableEntityHttpException($this->getError($model));
                }

                return $this->redirect(['index']);
            } catch (\Exception $e) {
                return $this->message($e->getMessage(), $this->redirect(['index']), 'error');
            }
        }

        return $this->render($this->action->id, [
            'model' => $model,
            'tags' => Yii::$app->services->wechatFansTags->getList(),
        ]);
    }

    /**
     * @param $id
     * @return SendForm|null
     */
    protected function findModel($id)
    {
        if (empty($id) || empty(($model = SendForm::findOne($id)))) {
            $model = new SendForm();
            return $model->loadDefaultValues();
        }

        return $model;
    }
}
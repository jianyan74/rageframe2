<?php
namespace backend\modules\sys\controllers;

use Yii;
use common\helpers\ResultDataHelper;
use common\models\sys\Style;

/**
 * 主题样式控制器
 *
 * Class StyleController
 * @package backend\modules\member\controllers
 */
class StyleController extends SController
{
    /**
     * 更新主题
     *
     * @param $skin_id
     * @return array
     */
    public function actionUpdate($skin_id)
    {
        $model = Style::findByManagerId(Yii::$app->user->id);
        $model->skin_id = $skin_id;

        if ($model->save())
        {
            return ResultDataHelper::json(200, '更改成功');
        }

        return ResultDataHelper::json(422, $this->analyErr($model->getFirstErrors()));
    }
}
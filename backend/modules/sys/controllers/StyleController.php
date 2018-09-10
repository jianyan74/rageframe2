<?php
namespace backend\modules\sys\controllers;

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
     * @param int $skin_id 皮肤id
     * @return array
     */
    public function actionUpdate($skin_id)
    {
        $model = Style::getStyle();
        $model->skin_id = $skin_id;

        if ($model->save())
        {
            return ResultDataHelper::result(200, '更改成功');
        }

        return ResultDataHelper::result(422, $this->analyErr($model->getFirstErrors()));
    }
}
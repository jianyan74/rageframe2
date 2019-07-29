<?php

namespace services\member;

use common\components\Service;
use common\models\forms\CreditsLogForm;
use common\models\member\MoneyLog;
use yii\web\NotFoundHttpException;

/**
 * Class MoneyLogService
 * @package services\member
 * @author jianyan74 <751393839@qq.com>
 */
class MoneyLogService extends Service
{
    /**
     * 创建
     *
     * @param CreditsLogForm $creditsLogForm
     * @throws NotFoundHttpException
     */
    public function create(CreditsLogForm $creditsLogForm)
    {
        $model = new MoneyLog();
        $model = $model->loadDefaultValues();
        $model->member_id = $creditsLogForm->member->id;
        $model->pay_type = $creditsLogForm->pay_type;
        $model->num = $creditsLogForm->num;
        $model->credit_group = $creditsLogForm->credit_group;
        $model->credit_group_detail = $creditsLogForm->credit_group_detail;
        $model->remark = $creditsLogForm->remark;
        $model->map_id = $creditsLogForm->map_id;

        if (!$model->save()) {
            throw new NotFoundHttpException($this->getError($this->member));
        }
    }
}
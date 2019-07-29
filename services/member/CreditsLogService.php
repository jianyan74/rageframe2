<?php

namespace services\member;

use Yii;
use yii\web\NotFoundHttpException;
use common\models\forms\CreditsLogForm;
use common\components\Service;
use common\models\member\CreditsLog;

/**
 * Class CreditsLogService
 * @package services\member
 * @author jianyan74 <751393839@qq.com>
 */
class CreditsLogService extends Service
{
    /**
     * 类型
     *
     * @var
     */
    protected $creditType;

    /**
     * @var
     */
    protected $oldNum;

    /**
     * @var
     */
    protected $newNum;

    /**
     * 增加积分
     *
     * @param CreditsLogForm $creditsLogForm
     * @throws NotFoundHttpException
     */
    public function incrInt(CreditsLogForm $creditsLogForm)
    {
        if ($creditsLogForm->num <= 0) {
            return;
        }

        $creditsLogForm->num = abs($creditsLogForm->num);

        $this->creditType = CreditsLog::CREDIT_TYPE_USER_INTEGRAL;
        $this->oldNum = $creditsLogForm->member->user_integral;
        $creditsLogForm->member->user_integral += $creditsLogForm->num;
        $creditsLogForm->member->accumulate_integral += $creditsLogForm->num;
        $this->newNum = $creditsLogForm->member->user_integral;

        if (!$creditsLogForm->member->save()) {
            throw new NotFoundHttpException($this->getError($creditsLogForm->member));
        }

        $this->create($creditsLogForm);
    }

    /**
     * 减少积分
     *
     * @param CreditsLogForm $creditsLogForm
     * @throws NotFoundHttpException
     * @throws NotFoundHttpException
     */
    public function decrInt(CreditsLogForm $creditsLogForm)
    {
        if ($creditsLogForm->num <= 0) {
            return;
        }

        $creditsLogForm->num = - abs($creditsLogForm->num);

        $this->creditType = CreditsLog::CREDIT_TYPE_USER_INTEGRAL;
        $this->oldNum = $creditsLogForm->member->user_integral;
        $creditsLogForm->member->user_integral += $creditsLogForm->num;
        $this->newNum = $creditsLogForm->member->user_integral;

        if ($this->newNum < 0) {
            throw new NotFoundHttpException('积分不足');
        }

        if (!$creditsLogForm->member->save()) {
            throw new NotFoundHttpException($this->getError($creditsLogForm->member));
        }

        $this->create($creditsLogForm);
    }

    /**
     * 增加金额
     *
     * @param CreditsLogForm $creditsLogForm
     * @throws NotFoundHttpException
     */
    public function incrMoney(CreditsLogForm $creditsLogForm)
    {
        if ($creditsLogForm->num <= 0) {
            return;
        }

        $creditsLogForm->num = abs($creditsLogForm->num);

        $this->creditType = CreditsLog::CREDIT_TYPE_USER_MONEY;
        $this->oldNum = $creditsLogForm->member->user_money;
        $creditsLogForm->member->user_money += $creditsLogForm->num;
        $creditsLogForm->member->accumulate_money += $creditsLogForm->num;
        $this->newNum = $creditsLogForm->member->user_money;

        if (!$creditsLogForm->member->save()) {
            throw new NotFoundHttpException($this->getError($creditsLogForm->member));
        }

        $model = $this->create($creditsLogForm);
        $creditsLogForm->map_id = $model->id;
        // 记录到总日志
        Yii::$app->services->memberMoneyLog->create($creditsLogForm);
    }

    /**
     * 减少金额
     *
     * @param CreditsLogForm $creditsLogForm
     * @throws NotFoundHttpException
     */
    public function decrMoney(CreditsLogForm $creditsLogForm)
    {
        if ($creditsLogForm->num <= 0) {
            return;
        }

        $creditsLogForm->num = - abs($creditsLogForm->num);

        $this->creditType = CreditsLog::CREDIT_TYPE_USER_MONEY;
        $this->oldNum = $creditsLogForm->member->user_money;
        $creditsLogForm->member->user_money += $creditsLogForm->num;
        $this->newNum = $creditsLogForm->member->user_money;

        if ($this->newNum < 0) {
            throw new NotFoundHttpException('余额不足');
        }

        if (!$creditsLogForm->member->save()) {
            throw new NotFoundHttpException($this->getError($creditsLogForm->member));
        }

        $model = $this->create($creditsLogForm);
        $creditsLogForm->map_id = $model->id;
        // 记录到总日志
        Yii::$app->services->memberMoneyLog->create($creditsLogForm);
    }

    /**
     * 创建
     *
     * @param CreditsLogForm $creditsLogForm
     * @throws NotFoundHttpException
     */
    private function create(CreditsLogForm $creditsLogForm)
    {
        $model = new CreditsLog();
        $model = $model->loadDefaultValues();
        $model->member_id = $creditsLogForm->member->id;
        $model->old_num = $this->oldNum;
        $model->new_num = $this->newNum;
        $model->num = $creditsLogForm->num;
        $model->credit_type = $this->creditType;
        $model->credit_group = $creditsLogForm->credit_group;
        $model->credit_group_detail = $creditsLogForm->credit_group_detail;
        $model->remark = $creditsLogForm->remark;
        $model->map_id = $creditsLogForm->map_id;

        if (!$model->save()) {
            throw new NotFoundHttpException($this->getError($this->member));
        }

        return $model;
    }
}
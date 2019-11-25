<?php

namespace services\member;

use Yii;
use yii\web\NotFoundHttpException;
use common\models\forms\CreditsLogForm;
use common\components\Service;
use common\models\member\CreditsLog;
use common\models\member\Account;

/**
 * Class CreditsLogService
 * @package services\member
 * @author jianyan74 <751393839@qq.com>
 */
class CreditsLogService extends Service
{
    /**
     * 字段类型
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
     * 累计字段
     *
     * @var string
     */
    protected $accumulate;

    /**
     * 增加积分
     *
     * @param CreditsLogForm $creditsLogForm
     * @throws NotFoundHttpException
     */
    public function incrInt(CreditsLogForm $creditsLogForm)
    {
        if ($creditsLogForm->num < 0) {
            return;
        }

        /** @var Account $account */
        $account = $creditsLogForm->member->account;
        if ($creditsLogForm->num == 0) {
            $this->creditType = CreditsLog::CREDIT_TYPE_USER_INTEGRAL;
            $this->oldNum = $account->user_integral;
            $this->newNum = $account->user_integral;
            $this->create($creditsLogForm);

            return;
        }

        $creditsLogForm->num = abs($creditsLogForm->num);
        $this->creditType = CreditsLog::CREDIT_TYPE_USER_INTEGRAL;
        $this->oldNum = $account->user_integral;
        $account->user_integral += $creditsLogForm->num;
        $this->accumulate = 'accumulate_integral';
        $this->newNum = $account->user_integral;

        // 更新账户
        $this->updateAccount($account);
        // 记录日志
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
        if ($creditsLogForm->num < 0) {
            return;
        }

        /** @var Account $account */
        $account = $creditsLogForm->member->account;
        if ($creditsLogForm->num == 0) {
            $this->creditType = CreditsLog::CREDIT_TYPE_USER_INTEGRAL;
            $this->oldNum = $account->user_integral;
            $this->newNum = $account->user_integral;
            $this->create($creditsLogForm);

            return;
        }

        $creditsLogForm->num = -abs($creditsLogForm->num);
        $this->creditType = CreditsLog::CREDIT_TYPE_USER_INTEGRAL;
        $this->oldNum = $account->user_integral;
        $account->user_integral += $creditsLogForm->num;
        $this->newNum = $account->user_integral;

        if ($this->newNum < 0) {
            throw new NotFoundHttpException('积分不足');
        }

        // 更新账户
        $this->updateAccount($account);
        // 记录日志
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
        if ($creditsLogForm->num < 0) {
            return;
        }

        /** @var Account $account */
        $account = $creditsLogForm->member->account;
        // 增加金额为0不变更
        if ($creditsLogForm->num == 0) {
            $this->creditType = CreditsLog::CREDIT_TYPE_USER_MONEY;
            $this->oldNum = $account->user_money;
            $this->newNum = $account->user_money;
            $this->create($creditsLogForm);

            return;
        }

        $creditsLogForm->num = abs($creditsLogForm->num);
        $this->creditType = CreditsLog::CREDIT_TYPE_USER_MONEY;
        $this->oldNum = $account->user_money;
        $account->user_money += $creditsLogForm->num;
        $this->accumulate = 'accumulate_money';
        $this->newNum = $account->user_money;

        // 更新账户
        $this->updateAccount($account);
        // 记录日志
        $model = $this->create($creditsLogForm);
        $creditsLogForm->map_id = $model->id;
    }

    /**
     * 减少金额
     *
     * @param CreditsLogForm $creditsLogForm
     * @throws NotFoundHttpException
     */
    public function decrMoney(CreditsLogForm $creditsLogForm)
    {
        if ($creditsLogForm->num < 0) {
            return;
        }

        /** @var Account $account */
        $account = $creditsLogForm->member->account;
        // 消费金额为0不变更
        if ($creditsLogForm->num == 0) {
            $this->creditType = CreditsLog::CREDIT_TYPE_USER_MONEY;
            $this->oldNum = $account->user_money;
            $this->newNum = $account->user_money;
            $this->create($creditsLogForm);

            return;
        }

        $creditsLogForm->num = -abs($creditsLogForm->num);
        $this->creditType = CreditsLog::CREDIT_TYPE_USER_MONEY;
        $this->oldNum = $account->user_money;
        $account->user_money += $creditsLogForm->num;
        $this->newNum = $account->user_money;

        if ($this->newNum < 0) {
            throw new NotFoundHttpException('余额不足');
        }

        // 更新账户
        $this->updateAccount($account);
        // 记录日志
        $model = $this->create($creditsLogForm);
        $creditsLogForm->map_id = $model->id;
    }

    /**
     * 更新账户，尽量不拒绝，宁可记录不正确
     *
     * @param Account $account
     * @throws NotFoundHttpException
     */
    protected function updateAccount(Account $account)
    {
        $amount = $this->newNum - $this->oldNum;

        if ($amount > 0) {
            if (!$account->updateAllCounters([$this->creditType => $amount, $this->accumulate => $amount], ['id' => $account->id])) {
                throw new NotFoundHttpException('系统繁忙');
            }
        } else {
            if (!$account->updateAllCounters([$this->creditType => $amount], ['and', ['id' => $account->id], ['>=', $this->creditType, $amount]])) {
                throw new NotFoundHttpException('余额不足');
            }
        }
    }

    /**
     * 创建
     *
     * @param CreditsLogForm $creditsLogForm
     * @throws NotFoundHttpException
     */
    public function create(CreditsLogForm $creditsLogForm)
    {
        $model = new CreditsLog();
        $model = $model->loadDefaultValues();
        $model->member_id = $creditsLogForm->member->id;
        $model->pay_type = $creditsLogForm->pay_type;
        $model->old_num = $this->oldNum;
        $model->new_num = $this->newNum;
        $model->num = $creditsLogForm->num;
        $model->credit_type = $this->creditType;
        $model->credit_group = $creditsLogForm->credit_group;
        $model->credit_group_detail = $creditsLogForm->credit_group_detail;
        $model->remark = $creditsLogForm->remark;
        $model->map_id = $creditsLogForm->map_id;

        if (!$model->save()) {
            throw new NotFoundHttpException($this->getError($model));
        }

        return $model;
    }
}
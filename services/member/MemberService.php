<?php
namespace services\member;

use common\enums\StatusEnum;
use yii\web\NotFoundHttpException;
use common\components\Service;
use common\models\member\CreditsLog;
use common\models\member\Member;

/**
 * Class MemberService
 * @package services\member
 * @author jianyan74 <751393839@qq.com>
 */
class MemberService extends Service
{
    /**
     * 用户
     *
     * @var \common\models\member\Member
     */
    protected $member;

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
     * @param $member_id
     * @return $this
     * @throws NotFoundHttpException
     */
    public function set(Member $member)
    {
        $this->member = $member;
        return $this;
    }

    /**
     * @param $member_id
     * @return array|Member|null|\yii\db\ActiveRecord
     */
    public function get($id)
    {
        if (!$this->member) {
            $this->member = Member::find()
                ->where(['id' => $id, 'status' => StatusEnum::ENABLED])
                ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
                ->one();
        }

        return $this->member;
    }

    /**
     * 增加积分
     *
     * @param $num
     * @param $credit_group
     * @param string $credit_group_detail
     * @param string $remark
     */
    public function incrInt($num, $credit_group, $credit_group_detail = '', $remark = '')
    {
        if ($num <= 0) {
            return;
        }

        $this->creditType = CreditsLog::CREDIT_TYPE_USER_INTEGRAL;
        $this->oldNum = $this->member->user_integral;
        $this->member->user_integral += $num;
        $this->member->accumulate_integral += $num;
        $this->newNum = $this->member->user_integral;

        if ($this->member->save()) {
            $this->log($num, $credit_group, $credit_group_detail, $remark);
        }
    }

    /**
     * 减少积分
     *
     * @param $num
     * @param $credit_group
     * @param string $credit_group_detail
     * @param string $remark
     * @throws NotFoundHttpException
     */
    public function decrInt($num, $credit_group, $credit_group_detail = '', $remark = '')
    {
        if ($num <= 0) {
            return;
        }

        $this->creditType = CreditsLog::CREDIT_TYPE_USER_INTEGRAL;
        $this->oldNum = $this->member->user_integral;
        $this->member->user_integral -= $num;
        $this->newNum = $this->member->user_integral;

        if ($this->newNum < 0) {
            throw new NotFoundHttpException('积分不足');
        }

        if ($this->member->save()) {
            $this->log(-$num, $credit_group, $credit_group_detail, $remark);
        }
    }

    /**
     * 增加金额
     *
     * @param $num
     * @param $credit_group
     * @param string $credit_group_detail
     * @param string $remark
     */
    public function incrMoney($num, $credit_group, $credit_group_detail = '', $remark = '')
    {
        if ($num <= 0) {
            return;
        }

        $this->creditType = CreditsLog::CREDIT_TYPE_USER_MONEY;
        $this->oldNum = $this->member->user_money;
        $this->member->user_money += $num;
        $this->member->accumulate_money += $num;
        $this->newNum = $this->member->user_money;

        if ($this->member->save()) {
            $this->log($num, $credit_group, $credit_group_detail, $remark);
        }
    }

    /**
     * 减少金额
     *
     * 调用案例
     * Yii::$app->services->member->decrMoney(1, 'change', '', '订单付款');
     *
     * @param $num
     * @param string $credit_group
     * @param string $remark
     * @throws NotFoundHttpException
     */
    public function decrMoney($num, $credit_group, $credit_group_detail = '', $remark = '')
    {
        if ($num <= 0) {
            return;
        }

        $this->creditType = CreditsLog::CREDIT_TYPE_USER_MONEY;
        $this->oldNum = $this->member->user_money;
        $this->member->user_money -= $num;
        $this->newNum = $this->member->user_money;

        if ($this->newNum < 0) {
            throw new NotFoundHttpException('余额不足');
        }

        if ($this->member->save()) {
            $this->log(-$num, $credit_group, $credit_group_detail, $remark);
        }
    }

    /**
     * @param $num
     * @param $credit_group
     * @param $credit_group_detail
     * @param $remark
     * @return bool
     */
    private function log($num, $credit_group, $credit_group_detail, $remark)
    {
        $model = new CreditsLog();
        $model = $model->loadDefaultValues();
        $model->member_id = $this->member->id;
        $model->old_num = $this->oldNum;
        $model->new_num = $this->newNum;
        $model->num = $num;
        $model->credit_type = $this->creditType;
        $model->credit_group = $credit_group;
        $model->credit_group_detail = $credit_group_detail;
        $model->remark = $remark;

        return $model->save();
    }
}
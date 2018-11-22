<?php
namespace common\services\member;

use yii\web\NotFoundHttpException;
use common\services\Service;
use common\models\member\CreditsLog;
use common\models\member\MemberInfo;

/**
 * Class Member
 * @package common\services\member
 */
class Member extends Service
{
    /**
     * 用户
     *
     * @var
     */
    protected $member;

    /**
     * 类型
     *
     * @var
     */
    protected $credit_type;

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
    public function findById($member_id)
    {
        $this->member = MemberInfo::findIdentity($member_id);

        if (!$this->member)
        {
            throw new NotFoundHttpException('找不到用户');
        }

        return $this;
    }

    /**
     * 增加积分
     *
     * @param $num
     * @param string $credit_group
     * @param string $remark
     */
    public function incrInt($num, $credit_group, $remark = '')
    {
        $this->credit_type = 'user_integral';
        $this->oldNum = $this->member->user_integral;
        $this->member->user_integral += $num;
        $this->newNum = $this->member->user_integral;

        if ($this->member->save())
        {
            $this->log($num, $credit_group, $remark);
        }
    }

    /**
     * 减少积分
     *
     * @param $num
     * @param string $credit_group
     * @param string $remark
     * @throws NotFoundHttpException
     */
    public function decrInt($num, $credit_group, $remark = '')
    {
        $this->credit_type = 'user_integral';
        $this->oldNum = $this->member->user_integral;
        $this->member->user_integral -= $num;
        $this->newNum = $this->member->user_integral;

        if ($this->newNum <= 0)
        {
            throw new NotFoundHttpException('积分不足');
        }

        if ($this->member->save())
        {
            $this->log($num, $credit_group, $remark);
        }
    }

    /**
     * 增加金额
     *
     * @param $num
     * @param string $credit_group
     * @param string $remark
     */
    public function incrMoney($num, $credit_group, $remark = '')
    {
        $this->credit_type = 'user_money';
        $this->oldNum = $this->member->user_money;
        $this->member->user_money += $num;
        $this->member->accumulate_money += $num;
        $this->newNum = $this->member->user_money;

        if ($this->member->save())
        {
            $this->log($num, $credit_group, $remark);
        }
    }

    /**
     * 减少金额
     *
     * 调用案例
     * Yii::$app->services->member->findById(15)->decrMoney(1, 'change', '订单付款');
     *
     * @param $num
     * @param string $credit_group
     * @param string $remark
     * @throws NotFoundHttpException
     */
    public function decrMoney($num, $credit_group, $remark = '')
    {
        $this->credit_type = 'user_money';
        $this->oldNum = $this->member->user_money;
        $this->member->user_money -= $num;
        $this->newNum = $this->member->user_money;

        if ($this->newNum <= 0)
        {
            throw new NotFoundHttpException('余额不足');
        }

        if ($this->member->save())
        {
            $this->log($num, $credit_group, $remark);
        }
    }

    /**
     * @param $num
     * @param $credit_group
     * @param $remark
     * @return bool
     */
    private function log($num, $credit_group, $remark)
    {
        $model = new CreditsLog();
        $model = $model->loadDefaultValues();
        $model->member_id = $this->member->id;
        $model->old_num = $this->oldNum;
        $model->new_num = $this->newNum;
        $model->num = $num;
        $model->credit_type = $this->credit_type;
        $model->credit_group = $credit_group;
        $model->remark = $remark;

        return $model->save();
    }
}
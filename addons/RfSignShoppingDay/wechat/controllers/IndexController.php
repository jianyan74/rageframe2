<?php
namespace addons\RfSignShoppingDay\wechat\controllers;

use Yii;
use common\enums\StatusEnum;
use common\helpers\ArithmeticHelper;
use common\helpers\ResultDataHelper;
use addons\RfSignShoppingDay\common\models\Record;
use addons\RfSignShoppingDay\common\models\Award;

/**
 * Class IndexController
 * @package addons\RfSignShoppingDay\wechat\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class IndexController extends BaseController
{
    /**
     * 首页
     *
     * @return string
     */
    public function actionIndex()
    {
        $config = $this->getConfig();
        $start_time = isset($config['start_time']) ? strtotime($config['start_time']) : 0;
        $end_time = isset($config['end_time']) ? strtotime($config['end_time']) : 0;

        $isStart = time() < $start_time ? false : true;
        $isEnd = time() < $end_time ? false : true;

        return $this->render('index',[
            'config' => $config,
            'user' => $this->user,
            'app' => Yii::$app->wechat->app,
            'isStart' => $isStart,
            'isEnd' => $isEnd,
            'isSign' => Record::find()->where(['record_date' => date('Y-m-d'), 'openid' => Yii::$app->params['wechatMember']['id']])->andFilterWhere(['merchant_id' => $this->getMerchantId()])->count()
        ]);
    }

    /**
     * 抽奖
     *
     * @return array
     * @throws \yii\db\Exception
     */
    public function actionDraw()
    {
        $config = $this->getConfig();
        // 记录抽奖次数
        $model = new Record();
        $model = $model->loadDefaultValues();
        $model->openid = $this->openid;
        $model->record_date = date('Y-m-d');

        // 判断活动是否开启
        $start_time = isset($config['start_time']) ? strtotime($config['start_time']) : 0;
        $end_time = isset($config['end_time']) ? strtotime($config['end_time']) : 0;

        $isStart = time() < $start_time ? false : true;
        $isEnd = time() < $end_time ? false : true;

        if ($isStart == false || $isEnd == true) {
            return ResultDataHelper::json(404, '活动未开始或者已结束');
        }

        // 判断今日是否已抽奖
        if (Record::find()->where(['record_date' => date('Y-m-d'), 'openid' => Yii::$app->params['wechatMember']['id']])->count()) {
            return ResultDataHelper::json(404, '今日已抽奖');
        }

        // 记录今日抽奖步数和插入抽奖记录
        $user = $this->user;
        $user->sign_num += 1;
        $user->save();
        $model->save();

        // 开始获取抽奖奖品
        if (!($awards = Award::find()->where(['status' => StatusEnum::ENABLED])->andWhere(['>', 'surplus_num', 0])->andWhere(['>', 'prob', 0])->andFilterWhere(['merchant_id' => $this->getMerchantId()])->all())) {
            return ResultDataHelper::json(404, '奖品不足');
        }

        // 开始随机抽奖
        if (!($awardId = ArithmeticHelper::drawRandom($awards))) {
            return ResultDataHelper::json(404, '未中奖');
        }

        $award = Award::findOne($awardId);
        /**
         * 判断奖品是否已经超过限制
         */
        $max_day_num = $award['max_day_num'];// 每日限制中奖数
        $max_user_num = $award['max_user_num'];// 每人最多中奖数

        // 单日最多
        $dayMaxRecord = Record::find()
            ->where(['record_date' => date('Y-m-d'), 'is_win' => 1, 'award_id' => $award['id']])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->count();

        if ($dayMaxRecord >= $max_day_num) {
            return ResultDataHelper::json(404, '未中奖');
        }

        // 查询用户最多
        $dayMaxUserRecord = Record::find()
            ->where(['record_date' => date('Y-m-d'), 'is_win' => 1, 'award_id' => $award['id'], 'openid' => Yii::$app->params['wechatMember']['id']])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->count();

        if ($dayMaxUserRecord >= $max_user_num) {
            return ResultDataHelper::json(404, '未中奖');
        }

        /********** 插入记录 start ********/
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model->is_win = 1;
            $model->award_id = $award['id'];
            $model->award_title = $award['title'];
            $model->award_cate_id = $award['cate_id'];
            $model->save();

            $award->surplus_num -= 1;
            $award->save();

            $transaction->commit();
            return ResultDataHelper::json(200, '抽奖成功', $model);
        } catch (\Exception $e) {
            $transaction->rollBack();
            return ResultDataHelper::json(404, '未中奖');
        }
        /********** 插入记录 end **********/
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function actionRecord()
    {
        $models = Record::find()
            ->where(['openid' => Yii::$app->params['wechatMember']['id'], 'is_win' => 1])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->asArray()
            ->all();

        foreach ($models as &$model) {
            $model['created_at'] = Yii::$app->formatter->asDate($model['created_at']);
        }

        return ResultDataHelper::json(200, '获取成功', ['list' => $models]);
    }
}
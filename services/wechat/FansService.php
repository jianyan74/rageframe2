<?php
namespace services\wechat;

use Yii;
use common\helpers\ArrayHelper;
use common\components\Service;
use common\models\wechat\Fans;

/**
 * Class FansService
 * @package services\wechat
 * @author jianyan74 <751393839@qq.com>
 */
class FansService extends Service
{
    /**
     * @param $openid
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function follow($openid)
    {
        // 获取用户信息
        $user = Yii::$app->wechat->app->user->get($openid);
        $user = ArrayHelper::toArray($user);
        $fans = $this->findModel($openid);
        $fans->attributes = $user;
        $fans->group_id = $user['groupid'];
        $fans->head_portrait = $user['headimgurl'];
        $fans->followtime = $user['subscribe_time'];
        $fans->follow = Fans::FOLLOW_ON;
        $fans->save();

        Yii::$app->services->wechatFansStat->upFollowNum();
    }

    /**
     * 取消关注
     *
     * @param $openid
     */
    public function unFollow($openid)
    {
        if ($fans = Fans::findOne(['openid' => $openid])) {
            $fans->follow = Fans::FOLLOW_OFF;
            $fans->unfollowtime = time();
            $fans->save();

            Yii::$app->services->wechatFansStat->upUnFollowNum();
        }
    }

    /**
     * 同步关注的用户信息
     *
     * @param $openid
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function syncByOpenid($openid)
    {
        $app = Yii::$app->wechat->app;
        $user = $app->user->get($openid);
        if ($user['subscribe'] == Fans::FOLLOW_ON) {
            $fans = $this->findModel($openid);
            $fans->attributes = $user;
            $fans->group_id = $user['groupid'];
            $fans->head_portrait = $user['headimgurl'];
            $fans->followtime = $user['subscribe_time'];
            $fans->follow = Fans::FOLLOW_ON;
            $fans->save();

            // 同步标签
            $labelData = [];
            foreach ($user['tagid_list'] as $tag) {
                $labelData[] = [$fans->id, $tag, Yii::$app->services->merchant->getId()];
            }

            Yii::$app->services->wechatFansTagMap->add($fans->id, $labelData);
        }
    }

    /**
     * 同步所有粉丝openid
     *
     * @return array
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \yii\db\Exception
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function syncAllOpenid()
    {
        // 获取全部列表
        $fans_list = Yii::$app->wechat->app->user->list();
        Yii::$app->debris->getWechatError($fans_list);
        $fans_count = $fans_list['total'];

        $total_page = ceil($fans_count / 500);
        for ($i = 0; $i < $total_page; $i++) {
            $fans = array_slice($fans_list['data']['openid'], $i * 500, 500);
            // 系统内的粉丝
            $system_fans = Yii::$app->services->wechatFans->getListByOpenids($fans);
            $new_system_fans = ArrayHelper::arrayKey($system_fans, 'openid');

            $add_fans = [];
            foreach($fans as $openid) {
                if (empty($new_system_fans) || empty($new_system_fans[$openid])) {
                    $add_fans[] = [0, $openid, Fans::FOLLOW_ON, 0, '', Yii::$app->services->merchant->getId(), time(), time()];
                }
            }

            if (!empty($add_fans)) {
                // 批量插入数据
                $field = ['member_id', 'openid', 'follow', 'followtime', 'tag', 'merchant_id', 'created_at', 'updated_at'];
                Yii::$app->db->createCommand()->batchInsert(Fans::tableName(), $field, $add_fans)->execute();
            }

            // 更新当前粉丝为关注
            Fans::updateAll(['follow' => 1 ], ['in', 'openid', $fans]);
        }

        return [$fans_list['total'], !empty($fans_list['data']['openid']) ? $fans_count : 0, $fans_list['next_openid']];
    }

    /**
     * @param $fan_id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function findByIdWithTag($fan_id)
    {
        return Fans::find()
            ->where(['id' => $fan_id])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->with('tags')
            ->asArray()
            ->one();
    }

    /**
     * @param $openid
     * @return array|null|\yii\db\ActiveRecord
     */
    public function findByOpenId($openid)
    {
        return Fans::find()
            ->where(['openid' => $openid])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->one();
    }

    /**
     * @param array $openids
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getListByOpenids(array $openids)
    {
        return Fans::find()
            ->where(['in', 'openid', $openids])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->select('openid')
            ->asArray()
            ->all();
    }

    /**
     * @param int $page
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getFollowListByPage($page = 0)
    {
        return Fans::find()
            ->where(['follow' => Fans::FOLLOW_ON])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->offset(10 * $page)
            ->orderBy('id desc')
            ->limit(10)
            ->asArray()
            ->all();
    }

    /**
     * 获取关注的人数
     *
     * @return int|string
     */
    public function getCountFollow()
    {
        return Fans::find()
            ->where(['follow' => Fans::FOLLOW_ON])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->select(['follow'])
            ->count();
    }

    /**
     * 获取用户信息
     *
     * @param $openid
     * @return array|Fans|null|\yii\db\ActiveRecord
     */
    protected function findModel($openid)
    {
        if (empty($openid) || empty(($model = Fans::find()->where(['openid' => $openid])->andFilterWhere(['merchant_id' => $this->getMerchantId()])->one()))) {
            return new Fans();
        }

        return $model;
    }
}
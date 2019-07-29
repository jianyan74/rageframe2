<?php

namespace services\api;

use Yii;
use yii\db\ActiveRecord;
use yii\web\UnprocessableEntityHttpException;
use common\enums\CacheKeyEnum;
use common\helpers\ArrayHelper;
use common\models\member\Member;
use common\models\api\AccessToken;
use common\components\Service;

/**
 * Class AccessTokenService
 * @package services\api
 * @author jianyan74 <751393839@qq.com>
 */
class AccessTokenService extends Service
{
    /**
     * 是否加入缓存
     *
     * @var bool
     */
    public $cache = false;

    /**
     * @var int
     */
    public $timeout;

    /**
     * 获取token
     *
     * @param Member $member
     * @param $group
     * @param int $cycle_index
     * @return array
     * @throws \yii\base\Exception
     */
    public function getAccessToken(Member $member, $group, $cycle_index = 1)
    {
        $model = $this->findModel($member->id, $group);
        $model->member_id = $member->id;
        $model->group = $group;
        // 删除缓存
        !empty($model->access_token) && Yii::$app->cache->delete(CacheKeyEnum::API_ACCESS_TOKEN . $model->access_token);
        $model->refresh_token = Yii::$app->security->generateRandomString() . '_' . time();
        $model->access_token = Yii::$app->security->generateRandomString() . '_' . time();

        if (!$model->save()) {
            if ($cycle_index <= 3) {
                $cycle_index++;
                return self::getAccessToken($member, $group, $cycle_index);
            }

            throw new UnprocessableEntityHttpException(Yii::$app->debris->analyErr($model->getFirstErrors()));
        }

        $result = [];
        $result['refresh_token'] = $model->refresh_token;
        $result['access_token'] = $model->access_token;
        $result['expiration_time'] = Yii::$app->params['user.accessTokenExpire'];

        // 记录访问次数
        $member->visit_count += 1;
        $member->last_time = time();
        $member->last_ip = Yii::$app->request->getUserIP();
        $member->save();
        $member = ArrayHelper::toArray($member);
        unset($member['password_hash'], $member['auth_key'], $member['password_reset_token'], $member['access_token'], $member['refresh_token']);
        $result['member'] = $member;

        // 写入缓存
        $key = CacheKeyEnum::API_ACCESS_TOKEN . $model->access_token;
        if ($this->cache == true) {
            Yii::$app->cache->set($key, $model, $this->timeout);
        }

        return $result;
    }

    /**
     * @param $token
     * @param $type
     * @return array|mixed|null|ActiveRecord
     */
    public function getTokenToCache($token, $type)
    {
        if ($this->cache == false) {
            return $this->getTokenByAccessToken($token);
        }

        $key = CacheKeyEnum::API_ACCESS_TOKEN . $token;
        if (!($model = Yii::$app->cache->get($key))) {
            $model = $this->getTokenByAccessToken($token);
            Yii::$app->cache->set($key, $model, $this->timeout);
        }

        return $model;
    }

    /**
     * @param $token
     * @return array|null|ActiveRecord
     */
    public function getTokenByAccessToken($token)
    {
        return AccessToken::find()
            ->where(['access_token' => $token])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->one();
    }

    /**
     * 返回模型
     *
     * @param $member_id
     * @param $group
     * @return array|AccessToken|null|ActiveRecord
     */
    protected function findModel($member_id, $group)
    {
        if (empty(($model = AccessToken::find()->where([
            'member_id' => $member_id,
            'group' => $group
        ])->andFilterWhere(['merchant_id' => $this->getMerchantId()])->one()))) {
            $model = new AccessToken();
            return $model->loadDefaultValues();
        }

        return $model;
    }
}
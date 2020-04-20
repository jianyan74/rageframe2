<?php

namespace common\websockets;

use Yii;
use common\helpers\ArrayHelper;
use common\models\api\AccessToken;
use common\models\member\Member;
use common\models\websocket\FdMemberMap;
use common\components\BaseWebSocket;
use common\enums\StatusEnum;

/**
 * Class SiteWebsSocket
 * @package common\websockets
 * @author jianyan74 <751393839@qq.com>
 */
class SiteWebSocket extends BaseWebSocket
{
    /**
     * 心跳包
     *
     * {"route":"site.ping"}
     */
    public function actionPing()
    {
        return $this->push('', $this->frame->fd, 2002);
    }

    /**
     * 登录
     */
    public function actionLogin()
    {
        /** @var AccessToken $identity */
        $identity = !empty($this->token) ? Yii::$app->services->apiAccessToken->getTokenToCache($this->token, null, true) : '';
        // 找不到用户直接关闭
        if (!$identity) {
            return $this->disconnect($this->frame->fd, 4101, '登录失败');
        }

        /** @var Member $member */
        $member = $identity->member;
        /** @var FdMemberMap $model */
        if ($model = $this->getFdMemberMap($member->id, 'member')) {
            $model->status == StatusEnum::ENABLED && $this->disconnect($model->fd, 4101, '已在别处登录');
        } else {
            $model = new FdMemberMap();
        }

        $clientInfo = $this->server->getClientInfo($this->frame->fd);
        $model->attributes = ArrayHelper::toArray($member);
        $model->ip = $clientInfo['remote_ip'] ?? '';
        $model->fd = $this->frame->fd;
        $model->type = 'member';
        $model->member_id = $identity->member_id;
        $model->merchant_id = $identity->merchant_id;
        $model->status = StatusEnum::ENABLED;
        $model->save();

        // 修改当前的用户信息
        $this->member = $model;

        unset($clientInfo, $model, $member);

        return $this->push('登录成功');
    }
}
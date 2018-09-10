<?php
namespace api\modules\v1\controllers\live;

use Yii;
use api\controllers\OnAuthController;
use api\modules\v1\events\RoomEvent;
use common\helpers\ResultDataHelper;

/**
 * 直播房间
 *
 * Class RoomController
 * @package api\modules\v1\controllers\live
 */
class RoomController extends OnAuthController
{
    public $modelClass = 'common\models\live\Room';

    /**
     * 定义事件名字 创建直播拉流
     */
    const EVENT_LIVE_CREATE = 'live_create';

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        // 绑定阿里直播房间创建事件
        $this->on(self::EVENT_LIVE_CREATE, ['api\modules\v1\models\AliYunLive','create']);
        // 触发直播房间Redis创建事件
        $this->on(self::EVENT_LIVE_CREATE, ['api\modules\v1\models\WebSocket','create']);
    }

    /**
     * 创建
     *
     * @return bool
     */
    public function actionCreate()
    {
        $model = new $this->modelClass();
        $model->attributes = Yii::$app->request->post();
        $model->member_id = Yii::$app->user->id;
        $model->start_time = time(); // 直播推拉流有效开始时间
        $model->end_time = time() + 60 * 60 * 24 * 2;// 直播推拉流有效结束时间
        if (!$model->save())
        {
            return ResultDataHelper::apiResult(422, $this->analyErr($model->getFirstErrors()));
        }

        // 创建事件传递的属性
        $event = new RoomEvent();
        $event->room = $model;

        // 触发事件
        $this->trigger(self::EVENT_LIVE_CREATE, $event);
        return $model;
    }
}

<?php
namespace api\modules\v1\models;

use Yii;
use yii\base\Model;

/**
 * Class Websocket
 * @package api\modules\v1\models
 */
class WebSocket extends Model
{
    /**
     * @param $event
     */
    public static function create($event)
    {
        $model = $event->room;

        // TODO
    }
}

<?php
namespace backend\modules\wechat\controllers;

use common\components\CurdTrait;
use common\models\wechat\MsgHistory;

/**
 * 微信历史消息
 *
 * Class MsgHistoryController
 * @package backend\modules\wechat\controllers
 */
class MsgHistoryController extends WController
{
    use CurdTrait;

    /**
     * @var string
     */
    public $modelClass = 'common\models\wechat\MsgHistory';
}
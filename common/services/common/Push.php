<?php
namespace common\services\common;

use Yii;
use common\services\Service;
use JPush\Client as JPush;

/**
 * Class Push
 * @package common\services\common
 * @author jianyan74 <751393839@qq.com>
 */
class Push extends Service
{
    /**
     * @var \JPush\Client
     */
    protected $client;

    public function init()
    {
        parent::init();

        $this->client = new JPush(Yii::$app->debris->config('push_jpush_appid'), Yii::$app->debris->config('push_jpush_app_secret'));
    }

    public function send($form = 'all', $message)
    {
        $pusher = $this->client->push();
        $pusher->setPlatform($form);
        $pusher->addAllAudience();
        $pusher->setNotificationAlert($message);
        try
        {
            $pusher->send();
        }
        catch (\JPush\Exceptions\JPushException $e)
        {
            // try something else here
            p($e);
        }
    }
}
<?php

namespace addons\RfDevTool\common\queues;

use Yii;
use yii\base\BaseObject;
use linslin\yii2\curl\Curl;
use Overtrue\Pinyin\Pinyin;
use common\helpers\StringHelper;
use common\models\common\Provinces;

/**
 * Class MapJob
 *
 *
 * $all = Provinces::find()
 *     ->select(['id', 'title'])
 *     ->asArray()
 *     ->all();
 *
 * foreach ($all as $item) {
 *     $messageId = Yii::$app->queue->push(new \common\queues\MapJob([
 *         'id' => $item['id'],
 *         'address' => $item['title'],
 *     ]));
 * }
 *
 * @package common\queues
 * @author jianyan74 <751393839@qq.com>
 */
class MapJob extends BaseObject implements \yii\queue\JobInterface
{
    /**
     * @var int
     */
    public $id;

    /**
     * 地址
     *
     * @var string
     */
    public $address;

    /**
     * @var string
     */
    public $type = 'baidu';

    /**
     * @param \yii\queue\Queue $queue
     * @return mixed|void
     */
    public function execute($queue)
    {
        $type = $this->type;
        $model = Provinces::findOne($this->id);
        if (($result = $this->$type()) !== false) {
            list($lng, $lat) = $result;

            if ($model) {
                $model->lng = (string) $lng;
                $model->lat = (string) $lat;
            }
        }

        if ($model) {
            $model->pinyin = $this->pinyin($model->title);
            $model->save();
        }
    }

    /**
     * @param $title
     * @return string
     */
    public function pinyin($title)
    {
        // 首先字母转大写拼音
        if (($chinese = StringHelper::strToChineseCharacters($title)) && !empty($chinese[0])) {
            $title_initial = mb_substr($chinese[0][0], 0, 1, 'utf-8');
            return ucwords((new Pinyin())->abbr($title_initial));
        }

        return '';
    }

    /**
     * @throws \Exception
     */
    public function baidu()
    {
        $curl = new Curl();
        $data = $curl->setGetParams([
            'address' => $this->address,
            'output' => 'json',
            'ak' => Yii::$app->debris->backendConfig('map_baidu_ak'),
        ])->get("http://api.map.baidu.com/geocoder/v2/", false);

        if (isset($data['status']) && $data['status'] == 0) {
            return [
                $data['result']['location']['lng'],
                $data['result']['location']['lat']
            ];
        }

        return false;
    }
}
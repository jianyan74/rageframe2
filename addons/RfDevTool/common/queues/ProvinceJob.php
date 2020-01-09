<?php

namespace addons\RfDevTool\common\queues;

use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use common\helpers\StringHelper;
use common\models\common\Provinces;
use linslin\yii2\curl\Curl;
use QL\QueryList;

/**
 * Class ProvinceJob
 * @package addons\RfDevTool\common\queues
 * @author jianyan74 <751393839@qq.com>
 */
class ProvinceJob extends BaseObject implements JobInterface
{
    /**
     * @var string
     */
    public $baseUrl;

    /**
     * @var int
     */
    public $maxLevel;

    /**
     * @var int
     */
    public $job_id;

    /**
     * @param \yii\queue\Queue $queue
     * @return mixed|void
     */
    public function execute($queue)
    {
        /** @var QueryList $ql */
        $ql = QueryList::getInstance();
        // 注册一个myHttp方法到QueryList对象
        $ql->bind('http', function ($url) {
            $curl = new Curl();
            $html = $curl->get($url);
            $encode = mb_detect_encoding($html, ["ASCII", 'UTF-8', "GB2312", "GBK", 'BIG5']);
            $str_encode = mb_convert_encoding($html, 'UTF-8', $encode);
            $this->setHtml($str_encode);
            return $this;
        });

        $data = $ql->rules([
            'title' => ['table.provincetable td a', 'text'],
            'link' => ['table.provincetable td a', 'href']
        ])->http($this->baseUrl . 'index.html')->query()->getData()->all();

        foreach ($data as &$datum) {
            $code = StringHelper::replace('.html', '', $datum['link']);
            $datum['code'] = explode('/', $code);
            $datum['id'] = $datum['code'][0] . '0000';
            $datum['pid'] = 0;
            $datum['tree'] = 'tr_0 ';
            $datum['level'] = 1;
            $datum['chlidPrefix'] = $datum['code'][0];
            $datum['chlidLink'] = $this->baseUrl . $datum['link'];

            // 写入数据库
            if (!($model = Provinces::findOne(['id' => $datum['id']]))) {
                $model = new Provinces();
            }
            $model->attributes = $datum;
            $model->save();

            if ($datum['level'] + 1 <= $this->maxLevel) {
                $this->createJob($datum);
            }
        }
    }

    /**
     * @param $datum
     * @param $level
     */
    public function createJob($datum)
    {
        $queue = new ProvinceChildJob([
            'parent' => $datum,
            'baseUrl' => $this->baseUrl,
            'maxLevel' => $this->maxLevel,
            'job_id' => $this->job_id,
        ]);

        $messageId = Yii::$app->queue->push($queue);
    }
}
<?php

namespace addons\RfDevTool\common\queues;

use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\web\NotFoundHttpException;
use common\helpers\StringHelper;
use common\models\common\Provinces;
use linslin\yii2\curl\Curl;
use QL\QueryList;
use addons\RfDevTool\common\models\ProvinceGatherLog;

/**
 * Class ProvinceChildJob
 * @package addons\RfDevTool\common\queues
 * @author jianyan74 <751393839@qq.com>
 */
class ProvinceChildJob extends BaseObject implements JobInterface
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
     * @var array
     */
    public $parent;

    /**
     * @var int
     */
    public $job_id;

    /**
     * 重连次数
     *
     * @var int
     */
    public $reconnection = 5;

    /**
     * @var int
     */
    public $level = 2;

    /**
     * @var array
     */
    public $rule = [
        2 => [
            'title' => ['table.citytable td+td a', 'text'],
            'link' => ['table.citytable td+td a', 'href']
        ],
        3 => [
            'title' => ['table.countytable td+td a', 'text'],
            'link' => ['table.countytable td+td a', 'href']
        ],
        4 => [
            'title' => ['table.towntable td+td a', 'text'],
            'link' => ['table.towntable td+td a', 'href']
        ],
        5 => [
            'title' => [],
            'link' => []
        ],
    ];

    /**
     * @param \yii\queue\Queue $queue
     * @return mixed|void
     * @throws NotFoundHttpException
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

        $level = $this->level;
        // 东莞市、中山市、儋州市下面直接是镇所以规则要变
        if (isset($this->parent['code'][1]) && in_array($this->parent['code'][1], [4419, 4420, 4604])) {
            $level += 1;
        }

        $data = $ql->rules($this->rule[$level])->http($this->parent['chlidLink'])->query()->getData()->all();
        $codeSuffix = $this->level == 2 ? '00' : '';

        // 找不到数据库可能是抓取失败重新连接
        if (empty($data)) {
            if ($this->reconnection <= 0) {
                $this->log('采集彻底失败');
                return;
            }

            $queue = new ProvinceChildJob([
                'parent' => $this->parent,
                'baseUrl' => $this->baseUrl,
                'maxLevel' => $this->maxLevel,
                'level' => $this->level,
                'job_id' => $this->job_id,
                'reconnection' => $this->reconnection - 1,
            ]);

            // 延迟60秒再运行
            $messageId = Yii::$app->queue->delay(1 * 60)->push($queue);
            $this->log('采集失败,等待重试时间60秒', $messageId);
            return;
        }

        foreach ($data as &$datum) {
            $code = StringHelper::replace('.html', '', $datum['link']);
            $datum['code'] = explode('/', $code);
            $datum['id'] = $datum['code'][1] . $codeSuffix;
            $datum['level'] = $this->parent['level'] + 1;
            $datum['pid'] = $this->parent['id'];
            $datum['tree'] = $this->parent['tree'] . 'tr_' . $datum['pid'] . ' ';
            $datum['chlidPrefix'] = $datum['code'][0];
            $datum['chlidLink'] = $this->baseUrl . $datum['chlidPrefix'] . '/' . $datum['code'][1] . '.html';

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
     * 记录日志
     */
    protected function log($remark, $message_id = 0)
    {
        $model = new ProvinceGatherLog();
        $model->data = $this->parent;
        $model->url = $this->baseUrl;
        $model->max_level = $this->maxLevel;
        $model->level = $this->level;
        $model->job_id = $this->job_id;
        $model->message_id = $message_id;
        $model->reconnection = $this->reconnection;
        $model->remark = $remark;
        $model->save();
        if (!$model->save()) {
            Yii::error(Yii::$app->debris->analyErr($model->getFirstErrors()));
        }
    }

    /**
     * @param $datum
     * @param $level
     */
    protected function createJob($datum)
    {
        $queue = new ProvinceChildJob([
            'parent' => $datum,
            'baseUrl' => $this->baseUrl,
            'maxLevel' => $this->maxLevel,
            'level' => $this->level + 1,
            'job_id' => $this->job_id,
        ]);

        $messageId = Yii::$app->queue->push($queue);
    }
}
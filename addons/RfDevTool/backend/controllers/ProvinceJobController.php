<?php

namespace addons\RfDevTool\backend\controllers;

use Yii;
use yii\helpers\Json;
use common\models\common\Provinces;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use common\helpers\ArrayHelper;
use addons\RfDevTool\common\models\ProvinceJob;
use addons\RfDevTool\common\queues\MapJob;
use addons\RfDevTool\common\queues\ProvinceJob as QueueJob;

/**
 * Class ProvinceController
 * @package addons\RfDevTool\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ProvinceJobController extends BaseController
{
    const URL = 'http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/';

    /**
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => ProvinceJob::class,
            'scenario' => 'default',
            'partialMatchAttributes' => [], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'maxLevelExplain' => ProvinceJob::$maxLevelExplain,
        ]);
    }

    /**
     * @return mixed|string
     * @throws \yii\db\Exception
     */
    public function actionCreate()
    {
        $model = new ProvinceJob();
        $model = $model->loadDefaultValues();
        $model->year = date('Y') - 1;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $queue = new QueueJob([
                'baseUrl' => self::URL . $model->year . '/',
                'maxLevel' => $model->max_level,
                'job_id' => $model->id,
            ]);

            $model->message_id = Yii::$app->queue->push($queue);
            $model->save();

            // 插入港澳台数据
            $this->other();

            return $this->message('加入队列成功', $this->redirect(['index']));
        }

        return $this->render('create', [
            'model' => $model,
            'year' => ArrayHelper::numBetween(2009, date('Y')),
            'maxLevelExplain' => ProvinceJob::$maxLevelExplain,
        ]);
    }

    /**
     * 抓取经纬度和拼音开头
     */
    public function actionMap()
    {
        $all = Provinces::find()
            ->select(['id', 'title', 'pid'])
            ->with(['parent'])
            ->asArray()
            ->all();

        foreach ($all as $item) {
            $title = $item['title'];
            isset($item['parent']['title']) && $title = $item['parent']['title'] . $title;
            $messageId = Yii::$app->queue->push(new MapJob([
                'id' => $item['id'],
                'address' => $title,
            ]));
        }

        return $this->message('加入队列成功', $this->redirect(['index']));
    }

    /**
     * @throws \yii\db\Exception
     */
    protected function other()
    {
        $path = Yii::getAlias('@addons') . '/RfDevTool/common/file/list.json';
        $jsonData = Json::decode(file_get_contents($path));

        $data = [];
        $Hongkong = 810000;
        $Macao = 820000;
        $Taiwan = 830000;

        foreach ($jsonData as $key => $datum) {
            if ($key == $Hongkong || $key == $Macao || $key == $Taiwan) {
                $data[] = [
                    'id' => $key,
                    'title' => $datum,
                    'level' => 1,
                    'pid' => 0,
                    'tree' => 'tr_0 ',
                ];
            } elseif ($key > $Hongkong && $key < $Macao) {
                // 香港
                for ($i = $Hongkong; $i < 820000; $i += 100) {
                    if ($key == $i) {
                        $data[] = [
                            'id' => $key,
                            'title' => $datum,
                            'level' => 2,
                            'pid' => $Hongkong,
                            'tree' => "tr_0 tr_$Hongkong ",
                        ];
                    } elseif ($key < $i + 100 && $key > $i) {
                        $data[] = [
                            'id' => $key,
                            'title' => $datum,
                            'level' => 3,
                            'pid' => $i,
                            'tree' => "tr_0 tr_$Hongkong tr_$i ",
                        ];
                    }
                }

            }elseif ($key > $Macao && $key < $Taiwan) {
                // 澳门
                for ($i = $Macao; $i < 830000; $i += 100) {
                    if ($key == $i) {
                        $data[] = [
                            'id' => $key,
                            'title' => $datum,
                            'level' => 2,
                            'pid' => $Macao,
                            'tree' => "tr_0 tr_$Macao ",
                        ];
                    } elseif ($key < $i + 100 && $key > $i) {
                        $data[] = [
                            'id' => $key,
                            'title' => $datum,
                            'level' => 3,
                            'pid' => $i,
                            'tree' => "tr_0 tr_$Macao tr_$i ",
                        ];
                    }
                }

            } else {
                // 台湾
                for ($i = $Taiwan; $i < 840000; $i += 100) {
                    if ($key == $i) {
                        $data[] = [
                            'id' => $key,
                            'title' => $datum,
                            'level' => 2,
                            'pid' => $Taiwan,
                            'tree' => "tr_0 tr_$Taiwan ",
                        ];
                    } elseif ($key < $i + 100 && $key > $i) {
                        $data[] = [
                            'id' => $key,
                            'title' => $datum,
                            'level' => 3,
                            'pid' => $i,
                            'tree' => "tr_0 tr_$Taiwan tr_$i ",
                        ];
                    }
                }
            }
        }

        $command = Yii::$app->db->createCommand();
        $command->batchInsert(Provinces::tableName(), ['id', 'title', 'level', 'pid', 'tree'], $data)->execute();
    }
}
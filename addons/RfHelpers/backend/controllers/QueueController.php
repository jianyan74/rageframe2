<?php

namespace addons\RfHelpers\backend\controllers;

use Yii;

/**
 * Class QueueController
 * @package addons\RfHelpers\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class QueueController extends BaseController
{
    /**
     * 队列ID列表
     *
     * @var array
     */
    public $queueList = [
        'queue'
    ];

    /**
     * @return string
     */
    public function actionInfo()
    {
        $data = [];

        foreach ($this->queueList as $item) {
            if ($info = $this->getInfo($item)) {
                $data[$item] = $info;
            }
        }

        return $this->render($this->action->id, [
            'data' => $data
        ]);
    }

    /**
     * @param string $id
     * @return mixed
     */
    protected function getInfo($id)
    {
        $command = "cd " . Yii::getAlias('@root') . "&& php yii $id/info";
        exec($command, $output);

        return $output;
    }
}
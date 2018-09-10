<?php
namespace backend\modules\wechat\controllers;

use Yii;
use common\components\CurdTrait;
use common\enums\StatusEnum;
use common\models\wechat\FansTags;
use common\models\wechat\MassRecord;
use common\models\wechat\Attachment;

/**
 * 群发消息控制器
 *
 * Class MassRecordController
 * @package backend\modules\wechat\controllers
 */
class MassRecordController extends WController
{
    use CurdTrait;

    /**
     * @var string
     */
    public $modelClass = 'common\models\wechat\MassRecord';

    /**
     * 获取粉丝分组 - 群发
     *
     * @return string
     */
    public function actionSend()
    {
        return $this->renderAjax('send-fans',[
            'tags' => FansTags::getList(),
        ]);
    }
}
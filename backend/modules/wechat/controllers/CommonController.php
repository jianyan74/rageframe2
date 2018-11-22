<?php
namespace backend\modules\wechat\controllers;

use yii\helpers\Url;
use yii\data\Pagination;
use common\enums\StatusEnum;
use common\helpers\ResultDataHelper;
use common\models\wechat\Attachment;
use common\models\wechat\AttachmentNews;

/**
 * Class CommonController
 * @package backend\modules\wechat\controllers
 */
class CommonController extends WController
{
    /**
     * 获取图片/视频/音频
     *
     * @param $media_type
     * @return array
     */
    public function actionSelectAttachment($media_type)
    {
        $data = Attachment::find()->where(['media_type' => $media_type, 'status' => StatusEnum::ENABLED]);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => 15, 'validatePage' => false]);
        $models = $data->offset($pages->offset)
            ->orderBy('id desc')
            ->limit($pages->limit)
            ->asArray()
            ->all();

        if ($media_type == Attachment::TYPE_IMAGE)
        {
            foreach ($models as &$model)
            {
                $model['image_url'] = Url::to(['analysis/image', 'attach' => $model['media_url']]);
            }
        }

        return ResultDataHelper::json(200, '获取数据成功', $models);
    }

    /**
     * 获取图文
     *
     * @return array
     */
    public function actionSelectNews()
    {
        $data = AttachmentNews::find()->where(['sort' => 0, 'status' => StatusEnum::ENABLED]);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => 15, 'validatePage' => false]);
        $models = $data->offset($pages->offset)
            ->orderBy('id desc')
            ->with('attachment')
            ->limit($pages->limit)
            ->select('id, sort, status, thumb_url, title, attachment_id')
            ->asArray()
            ->all();

        foreach ($models as &$model)
        {
            $model['image_url'] = Url::to(['analysis/image', 'attach' => $model['thumb_url']]);
        }

        return ResultDataHelper::json(200, '获取数据成功', $models);
    }
}
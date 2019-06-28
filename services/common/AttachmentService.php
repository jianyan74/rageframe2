<?php
namespace services\common;

use Yii;
use yii\data\Pagination;
use common\models\common\Attachment;
use common\components\Service;
use common\enums\StatusEnum;
use yii\web\NotFoundHttpException;

/**
 * Class AttachmentService
 * @package services\common
 * @author jianyan74 <751393839@qq.com>
 */
class AttachmentService extends Service
{
    /**
     * 获取百度编辑器查询数据
     *
     * @param $uploadType
     * @param $offset
     * @param $limit
     * @return array
     */
    public function getBaiduListPage($uploadType, $offset, $limit)
    {
        $data = Attachment::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['upload_type' => $uploadType])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->orderBy('id desc');
        $countModel = clone $data;
        $models = $data->offset($offset)
            ->limit($limit)
            ->asArray()
            ->all();

        $files = [];
        foreach ($models as $model) {
            $files[] = [
                'url' => $model['base_url'],
                'mtime' => $model['created_at']
            ];
        }

        return [$files, $countModel->count()];
    }

    /**
     * @param string $uploadType
     * @param string $year
     * @param string $month
     * @return array
     */
    public function getListPage($uploadType = '', $drive = '', $year = '', $month = '', $keyword = '')
    {
        $data = Attachment::find()
            ->where(['status' => StatusEnum::ENABLED, 'upload_type' => $uploadType])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->andFilterWhere(['drive' => $drive])
            ->andFilterWhere(['year' => $year])
            ->andFilterWhere(['month' => $month])
            ->andFilterWhere(['like', 'name', $keyword]);

        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => 10, 'validatePage' => false]);
        $models = $data->offset($pages->offset)
            ->orderBy('id desc')
            ->limit($pages->limit)
            ->asArray()
            ->all();

        // 如果是以文件形式上传的图片手动修改为图片类型显示
        foreach ($models as &$model) {
            if (preg_match("/^image/", $model['specific_type']) && $model['extension'] != 'psd') {
                $model['upload_type'] = Attachment::UPLOAD_TYPE_IMAGES;
            }

            $model['size'] = Yii::$app->formatter->asShortSize($model['size'], 2);
        }

        return [$models, $pages];
    }

    /**
     * @param $data
     * @return int
     * @throws NotFoundHttpException
     */
    public function create($data)
    {
        $model = new Attachment();
        $model->attributes = $data;
        if (!$model->save()) {
            throw new NotFoundHttpException(Yii::$app->debris->analyErr($model->getFirstErrors()));
        }

        return $model->id;
    }
}
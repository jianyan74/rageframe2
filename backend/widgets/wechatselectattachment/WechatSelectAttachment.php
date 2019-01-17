<?php
namespace backend\widgets\wechatselectattachment;

use backend\widgets\wechatselectattachment\assets\AttachmentAsset;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use common\enums\StatusEnum;
use common\models\wechat\Attachment;
use common\models\wechat\AttachmentNews;
use common\helpers\ResultDataHelper;

/**
 * Class WechatSelectAttachment
 * @package backend\widgets\wechatselectattachment
 * @author jianyan74 <751393839@qq.com>
 */
class WechatSelectAttachment extends Controller
{
    /**
     * 行为控制
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],// 登录
                    ],
                ],
            ],
        ];
    }

    /**
     * 获取图片/视频/音频/图文
     *
     * @param $media_type
     * @return string
     */
    public function actionList()
    {
        $keyword = Yii::$app->request->get('keyword');
        $media_type = Yii::$app->request->get('media_type');
        $models = $media_type == Attachment::TYPE_NEWS ? $this->news($keyword) : $this->attachment($media_type, $keyword);

        // 资源注册
        AttachmentAsset::register($this->view);

        return $this->renderAjax('@backend/widgets/wechatselectattachment/views/list-model', [
            'models' => $models,
            'media_type' => $media_type,
            'boxId' => Yii::$app->request->get('boxId'),
        ]);
    }

    /**
     * @return array
     */
    public function actionAjaxList()
    {
        $keyword = Yii::$app->request->get('keyword');
        $media_type = Yii::$app->request->get('media_type');
        $models = $media_type == Attachment::TYPE_NEWS ? $this->news($keyword) : $this->attachment($media_type, $keyword);

        return ResultDataHelper::json(200, '获取成功', $models);
    }

    /**
     * 获取图片/视频/音频
     *
     * @param $media_type
     * @param $keyword
     * @return array
     */
    public function attachment($media_type, $keyword)
    {
        $data = Attachment::find()
            ->where(['media_type' => $media_type, 'status' => StatusEnum::ENABLED])
            ->andFilterWhere(['like', 'file_name', $keyword]);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => 10, 'validatePage' => false]);
        $models = $data->offset($pages->offset)
            ->orderBy('id desc')
            ->limit($pages->limit)
            ->asArray()
            ->all();

        $list = [];
        foreach ($models as $model)
        {
            $listTmp = [];
            $listTmp['key'] = $model['media_id'];
            $listTmp['title'] = $model['file_name'];
            $listTmp['type'] = $model['media_type'];
            $listTmp['imgUrl'] = '';
            if ($media_type == Attachment::TYPE_IMAGE)
            {
                $listTmp['imgUrl'] = Url::to(['/wechat/analysis/image', 'attach' => $model['media_url']]);
            }

            $list[] = $listTmp;
            unset($listTmp);
        }

        return $list;
    }

    /**
     * 获取图文
     *
     * @param $keyword
     * @return array
     */
    public function news($keyword)
    {
        $data = AttachmentNews::find()
            ->where(['sort' => 0, 'status' => StatusEnum::ENABLED])
            ->andFilterWhere(['like', 'title', $keyword]);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => 10, 'validatePage' => false]);
        $models = $data->offset($pages->offset)
            ->orderBy('id desc')
            ->with('attachment')
            ->limit($pages->limit)
            ->select('id, sort, status, thumb_url, title, attachment_id')
            ->asArray()
            ->all();

        $list = [];
        foreach ($models as $model)
        {
            $listTmp = [];
            $listTmp['key'] = $model['attachment_id'];
            $listTmp['title'] = $model['title'];
            $listTmp['type'] = Attachment::TYPE_IMAGE;
            $listTmp['imgUrl'] = Url::to(['/wechat/analysis/image', 'attach' => $model['thumb_url']]);

            $list[] = $listTmp;
            unset($listTmp);
        }

        return $list;
    }
}
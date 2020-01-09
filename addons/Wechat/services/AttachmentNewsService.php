<?php

namespace addons\Wechat\services;

use yii\helpers\Json;
use yii\data\Pagination;
use common\enums\StatusEnum;
use common\helpers\Url;
use common\components\Service;
use addons\Wechat\common\models\AttachmentNews;
use addons\Wechat\common\models\Attachment;

/**
 * Class AttachmentNewsService
 * @package addons\Wechat\services
 * @author jianyan74 <751393839@qq.com>
 */
class AttachmentNewsService extends Service
{
    /**
     * 获取图文
     *
     * @param $keyword
     * @return array
     */
    public function getFirstListPage($year = '', $month = '', $keyword = '')
    {
        $data = AttachmentNews::find()
            ->where(['sort' => 0, 'status' => StatusEnum::ENABLED])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->andFilterWhere(['year' => $year])
            ->andFilterWhere(['month' => $month])
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
        foreach ($models as $model) {
            $listTmp = [];
            $listTmp['key'] = $model['attachment_id'];
            $listTmp['title'] = $model['title'];
            $listTmp['type'] = Attachment::TYPE_IMAGE;
            $listTmp['imgUrl'] = Url::to(['analysis/image', 'attach' => $model['thumb_url']]);

            $list[] = $listTmp;
            unset($listTmp);
        }

        return $list;
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function first($id)
    {
        return AttachmentNews::find()
            ->where(['sort' => 0, 'attachment_id' => $id])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->one();
    }

    /**
     * 格式化
     *
     * @param $attachment_id
     * @return string
     */
    public function formattingList($attachment_id)
    {
        $list = AttachmentNews::find()
            ->where(['attachment_id' => $attachment_id])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->orderBy('sort asc')
            ->asArray()
            ->all();

        foreach ($list as &$item) {
            $item['thumb_url'] = urldecode(Url::to(['analysis/image', 'attach' => $item['thumb_url']]));
            preg_match_all('/<img[^>]*src\s*=\s*([\'"]?)([^\'" >]*)\1/isu', $item['content'], $match);

            $match_arr = [];
            foreach ($match[2] as $vo) {
                $match_arr[$vo] = $vo;
            }

            foreach ($match_arr as $src) {
                $url = Url::to(['analysis/image', 'attach' => $src]);
                $url = urldecode($url);
                $item['content'] = str_replace($src, $url, $item['content']);
            }
        }

        return Json::encode($list);
    }

    /**
     * @param $attachment_id
     * @return AttachmentNews|null
     */
    public function findModel($attachment_id)
    {
        if (empty($attachment_id) || empty(($model = AttachmentNews::findOne($attachment_id)))) {
            $model = new AttachmentNews();
            return $model->loadDefaultValues();
        }

        return $model;
    }
}
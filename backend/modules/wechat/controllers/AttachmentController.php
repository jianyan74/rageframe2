<?php
namespace backend\modules\wechat\controllers;

use Yii;
use yii\data\Pagination;
use yii\helpers\Url;
use common\enums\StatusEnum;
use common\helpers\ResultDataHelper;
use common\models\wechat\Attachment;
use common\models\wechat\AttachmentNews;
use common\models\wechat\FansTags;
use common\helpers\StringHelper;
use backend\modules\wechat\models\PreviewForm;
use backend\modules\wechat\models\SendForm;
use backend\modules\wechat\models\VideoForm;
use EasyWeChat\Kernel\Messages\Article;

/**
 * 资源
 *
 * Class AttachmentController
 * @package backend\modules\wechat\controllers
 */
class AttachmentController extends WController
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        $keywords = Yii::$app->request->get('keywords', '');
        $type = Yii::$app->request->get('type', Attachment::TYPE_NEWS);

        $data = Attachment::find()
            ->where(['media_type' => $type, 'status' => StatusEnum::ENABLED])
            ->andFilterWhere(['like', 'file_name', $keywords]);
        $pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' => 15]);
        $type == Attachment::TYPE_NEWS && $data = $data->with('news');
        $models = $data->offset($pages->offset)
            ->orderBy('id desc')
            ->limit($pages->limit)
            ->all();

        return $this->render($type, [
            'models' => $models,
            'pages' => $pages,
            'mediaType' => $type,
            'keywords' => $keywords,
            'allMediaType' => Attachment::$typeExplain,
        ]);
    }

    /**
     * 图文编辑
     *
     * @return string|array
     */
    public function actionNewsEdit()
    {
        $request  = Yii::$app->request;
        // 获取图片的链接地址
        $wecahtMediatUrl = Url::to(['analysis/image']) . "?attach=";

        $attach_id  = $request->get('attach_id', '');
        $link_type  = $request->get('link_type', Attachment::LINK_TYPE_WECHAT);
        $attachment = $this->findModel($attach_id);

        if ($request->isAjax)
        {
            // 素材库
            $material = $this->app->material;

            $attach_id = $request->post('attach_id');
            $attachment = $this->findModel($attach_id);
            $attachment->link_type = $request->post('link_type');
            $attachment->media_type = Attachment::TYPE_NEWS;
            $attachment->save();

            $list = json_decode($request->post('list'), true);

            // 图文详情
            foreach ($list as $key => &$item)
            {
                // 替换加入显示的数据
                $item['content'] = str_replace($wecahtMediatUrl, '', trim($item['content']));
                $item['thumb_url'] = str_replace($wecahtMediatUrl, '', trim($item['thumb_url']));
                // 原始封面
                $thumb_url = $item['thumb_url'];

                // 封面判断是否已经上传到微信了
                if (strpos(urldecode($item['thumb_url']), Attachment::WECHAT_MEDIAT_URL) === false)
                {
                    // 上传到微信
                    $imageMaterial = $material->uploadImage(StringHelper::getLocalFilePath($thumb_url));
                    $item['thumb_media_id'] = $imageMaterial['media_id'];
                    $item['thumb_url'] = $imageMaterial['url'];

                    Attachment::add($thumb_url, 'image', $imageMaterial['url'], $imageMaterial['media_id']);
                }

                // 循环上传文章图片到微信
                preg_match_all('/<img[^>]*src\s*=\s*([\'"]?)([^\'" >]*)\1/isu', $item['content'], $match);
                foreach ($match[2] as $src)
                {
                    // 判断是否已经上传到微信了
                    if (strpos(urldecode($src), Attachment::WECHAT_MEDIAT_URL) === false)
                    {
                        $result = $material->uploadArticleImage(StringHelper::getLocalFilePath($src));
                        // 替换图片上传
                        $item['content'] = str_replace($src, $result['url'], $item['content']);
                    }
                }

                $item['content'] = htmlspecialchars_decode($item['content']);

                // 默认微信返回值
                $article = new Article([
                    'title' => $item['title'],
                    'thumb_media_id' => $item['thumb_media_id'],
                    'author' => $item['author'],
                    'content' => $item['content'],
                    'digest' => $item['digest'],
                    'source_url' => $item['content_source_url'],
                    'show_cover' => $item['show_cover_pic'],
                ]);

                $article_list[] = $article;
            }

            // 如果是微信图文则上传更新到微信服务器
            if ($attachment->link_type == Attachment::LINK_TYPE_WECHAT)
            {
                if ($attach_id)
                {
                    // 更新到微信
                    $material->updateArticle($attachment['media_id'], $article_list);
                }
                else
                {
                    // 上传图文信息
                    $resource = $material->uploadArticle($article_list);
                    // 获取图文信息
                    $getNews = $material->get($resource['media_id']);
                    $news_item = $getNews['news_item'];

                    $attachment->media_id = $resource['media_id'];
                    $attachment->save();
                }
            }

            // 插入文章到表
            foreach ($list as $k => $vo)
            {
                $attachmentNewId = isset($vo['id']) ? $vo['id'] : null ;
                $news = AttachmentNews::findModel($attachmentNewId);
                $news->attributes = $vo;
                $news->attachment_id = $attachment->id;

                // 判断是否微信 否则直接拿取图文链接
                if ($attachment->link_type == Attachment::LINK_TYPE_WECHAT)
                {
                    !$attach_id && $news->media_url = $news_item[$k]['url'];
                }
                else
                {
                    $news->media_url = $vo['content_source_url'];
                }

                $news->sort = $k;
                $news->save();
            }

            return ResultDataHelper::json(200, '修改成功');
        }

        return $this->render('news-edit',[
            'attachment' => $attachment,
            'list' => json_encode(AttachmentNews::getEditList($attach_id)),
            'attach_id' => $attach_id,
            'link_type' => $link_type,
        ]);
    }

    /**
     * 图片添加
     *
     * @return string|\yii\web\Response
     */
    public function actionImageAdd()
    {
        $model = new Attachment;
        if ($model->load(Yii::$app->request->post()) && $model->local_url)
        {
            // 本地前缀
            $result = $this->app->material->uploadImage(StringHelper::getLocalFilePath($model->local_url));
            // 验证微信报错
            if ($error = Yii::$app->debris->getWechatError($result, false))
            {
                return $this->message($error, $this->redirect(['index', 'type' => 'image']), 'error');
            }

            Attachment::add($model->local_url, 'image', $result['url'], $result['media_id']);

            return $this->redirect(['index', 'type' => 'image']);
        }

        return $this->renderAjax('image-add',[
            'model' => $model
        ]);
    }

    /**
     * 音频添加
     *
     * @return string|\yii\web\Response
     */
    public function actionVoiceAdd()
    {
        $model = new Attachment;
        if ($model->load(Yii::$app->request->post()) && $model->local_url)
        {
            // 本地前缀
            $result = $this->app->material->uploadVoice(StringHelper::getLocalFilePath(StringHelper::iconvForWindows($model->local_url), 'voices'));
            // 验证微信报错
            if ($error = Yii::$app->debris->getWechatError($result, false))
            {
                return $this->message($error, $this->redirect(['index', 'type' => 'voice']), 'error');
            }

            Attachment::add($model->local_url, 'voice', '', $result['media_id']);

            return $this->redirect(['index', 'type' => 'voice']);
        }

        return $this->renderAjax('voice-add',[
            'model' => $model
        ]);
    }

    /**
     * 视频添加
     *
     * @return string|\yii\web\Response
     */
    public function actionVideoAdd()
    {
        $model = new VideoForm();
        if ($model->load(Yii::$app->request->post()))
        {
            // 本地前缀
            $result = $this->app->material->uploadVideo(StringHelper::getLocalFilePath($model->local_url, 'videos'), $model->file_name, $model->description);
            // 验证微信报错
            if ($error = Yii::$app->debris->getWechatError($result, false))
            {
                return $this->message($error, $this->redirect(['index', 'type' => 'video']), 'error');
            }

            Attachment::add($model->local_url, 'video', '', $result['media_id'], $model->file_name);

            return $this->redirect(['index', 'type' => 'video']);
        }

        return $this->renderAjax('video-add',[
            'model' => $model
        ]);
    }

    /**
     * 删除永久素材
     *
     * @param $attach_id
     * @param $mediaType
     * @return mixed
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($attach_id, $mediaType)
    {
        // 删除数据库
        $model = $this->findModel($attach_id);
        if ($model->delete())
        {
            // 删除微信服务器数据
            $result = $this->app->material->delete($model['media_id']);
            // 验证微信报错
            if ($error = Yii::$app->debris->getWechatError($result, false))
            {
                return $this->message($error, $this->redirect(['index', 'type' => $mediaType]), 'error');
            }

            return $this->message("删除成功", $this->redirect(['index', 'type' => $mediaType]));
        }

        return $this->message("删除失败", $this->redirect(['index', 'type' => $mediaType]), 'error');
    }

    /**
     * 手机预览
     *
     * @param $attach_id
     * @return mixed|string
     */
    public function actionPreview($attach_id, $mediaType)
    {
        $model = new PreviewForm();
        if ($model->load(Yii::$app->request->post()))
        {
            $attachment = Attachment::findOne($attach_id);

            // 发送预览群发消息给指定的 openId 用户
            $preview = [
                'text' => 'previewText',
                'news' => 'previewNews',
                'voice' => 'previewVoice',
                'image' => 'previewImage',
                'video' => 'previewVideo',
                'card' => 'previewCard',
            ];

            $method = $preview[$attachment->media_type];
            // 1:openid预览 2:微信号预览
            $model->type == 1 && $method = $method . 'ByName';
            $result = $this->app->broadcasting->$method($attachment->media_id, $model->content);
            // 验证微信报错
            if ($error = Yii::$app->debris->getWechatError($result, false))
            {
                return $this->message($error, $this->redirect(['index', 'type' => $mediaType]), 'error');
            }

            return $this->message("发送成功", $this->redirect(['index', 'type' => $mediaType]));
        }

        return $this->renderAjax('preview',[
            'model' => $model,
        ]);
    }

    /**
     * 消息群发
     *
     * @param $attach_id
     * @param $mediaType
     * @return mixed|string
     */
    public function actionSend($attach_id, $mediaType)
    {
        $model = new SendForm();
        if ($model->load(Yii::$app->request->post()))
        {
            $attachmentModel = $this->findModel($attach_id);
            $model->attachment_id = $attachmentModel->id;
            $model->media_id = $attachmentModel->media_id;
            $model->media_type = $attachmentModel->media_type;
            $model->final_send_time = time();
            $model->send_time = time();

            try
            {
                $model->send();
                return $this->message('发送成功', $this->redirect(['attachment/index', 'type' => $mediaType]));
            }
            catch (\Exception $e)
            {
                return $this->message($e->getMessage(), $this->redirect(['attachment/index', 'type' => $mediaType]), 'error');
            }
        }

        return $this->renderAjax('send',[
            'model' => $model,
            'tags' => FansTags::getList(),
        ]);
    }

    /**
     * 同步微信素材
     *
     * @param string $type 素材的类型，图片（image）、视频（video）、语音 （voice）、图文（news）
     * @param int $offset 从全部素材的该偏移位置开始返回，可选，默认 0，0 表示从第一个素材 返回
     * @param int $count 返回素材的数量，可选，默认 20, 取值在 1 到 20 之间
     * @throws \yii\db\Exception
     * @return mixed
     */
    public function actionGetAllAttachment($type, $offset = 0, $count = 20)
    {
        // 查找素材
        try
        {
            $lists = $this->app->material->list($type, $offset, $count);

            // 解析微信接口是否报错.报错则抛出错误信息
            Yii::$app->debris->getWechatError($lists);

            $total = $lists['total_count'];
            // 素材列表
            $list = $lists['item'];
            $addMaterial = [];
            $sysMaterial = [];
            foreach ($list as $vo)
            {
                $sysMaterial[] = $vo['media_id'];
            }
        }
        catch (\Exception $e)
        {
            return ResultDataHelper::json(404, $e->getMessage());
        }

        // 系统内的素材
        $systemMaterial = Attachment::find()
            ->where(['in', 'media_id', $sysMaterial])
            ->select('media_id')
            ->asArray()
            ->all();

        $newSystemMaterial = [];
        foreach ($systemMaterial as $li)
        {
            $newSystemMaterial[$li['media_id']] = $li;
        }

        switch ($type)
        {
            // ** 图文 **//
            case Attachment::TYPE_NEWS :

                foreach ($list as $vo)
                {
                    if (empty($newSystemMaterial) || empty($newSystemMaterial[$vo['media_id']]))
                    {
                        $attachment = new Attachment();
                        $attachment->media_id = $vo['media_id'];
                        $attachment->media_type = $type;
                        $attachment->is_temporary = Attachment::MODEL_PERM;
                        $attachment->created_at = $vo['update_time'];
                        $attachment->save();

                        // 插入文章
                        foreach ($vo['content']['news_item'] as $key => $news_item)
                        {
                            $news = new AttachmentNews();
                            $news->attributes = $news_item;
                            $news->media_url = $news_item['url'];
                            $news->content = str_replace("data-src", "src", $news->content);
                            $news->attachment_id = $attachment->id;
                            $news->sort = $key;
                            $news->save();
                        }
                    }
                }

                break;

            //** 图片/视频/语音 **//
            default :

                foreach ($list as $vo)
                {
                    if (empty($newSystemMaterial) || empty($newSystemMaterial[$vo['media_id']]))
                    {
                        $mediaUrl = isset($vo['url']) ? $vo['url'] : '';
                        $addMaterial[] = [$vo['name'], $vo['media_id'], $mediaUrl, $type, Attachment::MODEL_PERM, $vo['update_time'], time()];
                    }
                }

                if (!empty($addMaterial))
                {
                    // 批量插入数据
                    $field = ['file_name', 'media_id', 'media_url', 'media_type', 'is_temporary', 'created_at', 'updated_at'];
                    Yii::$app->db->createCommand()->batchInsert(Attachment::tableName(), $field, $addMaterial)->execute();
                }

                break;
        }

        if ($total - $count > 0)
        {
            return ResultDataHelper::json(200, '同步成功', [
                'offset' => ($offset + 1) * $count,
                'count' => $count + $count
            ]);
        }

        return ResultDataHelper::json(201, '同步完成');
    }

    /**
     * 返回模型
     *
     * @param $id
     * @return Attachment|null
     */
    protected function findModel($id)
    {
        if (empty($id) || empty(($model = Attachment::findOne($id))))
        {
            $model = new Attachment;
            return $model->loadDefaultValues();
        }

        return $model;
    }
}
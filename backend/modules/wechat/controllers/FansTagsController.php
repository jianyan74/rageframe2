<?php
namespace backend\modules\wechat\controllers;

use yii;
use common\models\wechat\FansTags;

/**
 * 粉丝标签
 *
 * Class FansTagsController
 * @package backend\modules\wechat\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class FansTagsController extends WController
{
    /**
     * 标签首页
     *
     * @return mixed|string
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws yii\web\UnprocessableEntityHttpException
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;
        if ($request->isPost)
        {
            $tag_add = $request->post('tag_add', []);
            $tag_update = $request->post('tag_update', []);

            // 更新标签
            foreach ($tag_update as $key => $value)
            {
                if (empty($value))
                {
                    return $this->message("标签名称不能为空", $this->redirect(['index']), 'error');
                }

                Yii::$app->wechat->app->user_tag->update($key, $value);
            }

            // 插入标签
            foreach ($tag_add as $value)
            {
                Yii::$app->wechat->app->user_tag->create($value);
            }

            FansTags::updateList();
            return $this->message("保存成功", $this->redirect(['index']));
        }

        return $this->render('index',[
            'tags' => FansTags::getList()
        ]);
    }

    /**
     * 同步标签
     *
     * @return mixed
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws yii\web\UnprocessableEntityHttpException
     */
    public function actionSynchro()
    {
        FansTags::updateList();
        return $this->message("粉丝同步成功", $this->redirect(['index']));
    }
}
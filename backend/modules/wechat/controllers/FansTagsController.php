<?php
namespace backend\modules\wechat\controllers;

use yii;
use common\models\wechat\FansTags;

/**
 * 粉丝标签
 *
 * Class FansTagsController
 * @package backend\modules\wechat\controllers
 */
class FansTagsController extends WController
{
    /**
     * 标签首页
     *
     * @return string
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
                if ($value)
                {
                    $this->app->user_tag->update($key, $value);
                }
                else
                {
                    return $this->message("标签名称不能为空", $this->redirect(['list'], 'error'));
                }
            }

            // 插入标签
            foreach ($tag_add as $value)
            {
                $this->app->user_tag->create($value);
            }

            FansTags::updateList();
        }

        return $this->render('index',[
            'tags' => FansTags::getList()
        ]);
    }

    /**
     * 同步标签
     *
     * @return mixed
     */
    public function actionSynchro()
    {
        FansTags::updateList();
        return $this->message("粉丝同步成功", $this->redirect(['index']));
    }
}
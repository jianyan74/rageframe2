<?php
namespace backend\modules\sys\controllers;

use yii\data\Pagination;
use common\components\CurdTrait;
use common\models\sys\MenuCate;

/**
 * 菜单分类控制器
 *
 * Class MenuCateController
 * @package backend\modules\sys\controllers
 */
class MenuCateController extends SController
{
    use CurdTrait;

    /**
     * @var
     */
    public $modelClass = 'common\models\sys\MenuCate';

    /**
     * 首页
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $data = MenuCate::find();
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $this->pageSize]);
        $models = $data->offset($pages->offset)
            ->orderBy('sort asc')
            ->limit($pages->limit)
            ->all();

        return $this->render($this->action->id, [
            'models' => $models,
            'pages' => $pages
        ]);
    }
}
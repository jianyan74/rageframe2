<?php
namespace backend\modules\sys\controllers;

use yii\data\Pagination;
use common\components\Curd;
use common\models\sys\MenuCate;

/**
 * 菜单分类控制器
 *
 * Class MenuCateController
 * @package backend\modules\sys\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class MenuCateController extends SController
{
    use Curd;

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
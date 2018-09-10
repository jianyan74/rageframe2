<?php
namespace backend\modules\sys\controllers;

use common\components\CurdTrait;

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
}
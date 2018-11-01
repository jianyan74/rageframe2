<?php

echo "<?php\n";
?>
namespace addons\<?= $model->name;?>\<?= $appID ?>\controllers;

use Yii;
use api\controllers\OffAuthController;
use api\controllers\OnAuthController;
use api\controllers\UserOnAuthController;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package addons\<?= $model->name;?>\<?= $appID ?>\controllers
 */
class DefaultController extends OffAuthController
{
    public $modelClass = '';

    /**
    * 首页
    *
    * @return string
    */
    public function actionIndex()
    {
        return 'Hello world';
    }
}
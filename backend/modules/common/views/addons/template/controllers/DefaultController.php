<?php

echo "<?php\n";
?>

namespace addons\<?= $model->name;?>\<?= $appID ?>\controllers;

use Yii;
use common\controllers\AddonsController;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package addons\<?= $model->name;?>\<?= $appID ?>\controllers
 */
class DefaultController extends BaseController
{
    /**
    * 首页
    *
    * @return string
    */
    public function actionIndex()
    {
        return $this->render('index',[

        ]);
    }
}
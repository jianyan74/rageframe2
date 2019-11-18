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
class BaseController extends AddonsController
{
    /**
    * @var string
    */
<?php if ($appID == 'merchant'){ ?>
    // public $layout = "@addons/<?= $model->name;?>/<?= $appID ?>/views/layouts/main";
<?php } else { ?>
    public $layout = "@addons/<?= $model->name;?>/<?= $appID ?>/views/layouts/main";
<?php } ?>
}
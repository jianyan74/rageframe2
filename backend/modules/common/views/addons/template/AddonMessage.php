<?php

echo "<?php\n";
?>
namespace addons\<?= $model->name;?>;

use Yii;
use backend\interfaces\AddonWidget;

/**
 * AddonMessage
 *
 * Class AddonMessage
 * @package addons\<?= $model->name . "\r";?>
 */
class AddonMessage implements AddonWidget
{
    /**
     * @param $message
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function run($message)
    {
        return '示例模块' . Yii::$app->formatter->asDatetime(time());
    }
}
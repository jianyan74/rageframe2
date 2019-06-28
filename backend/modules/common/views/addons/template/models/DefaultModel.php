<?php

echo "<?php\n";
?>
namespace addons\<?= $model->name;?>\common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%addon_default}}".
 */
class DefaultModel extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_default}}';
    }
}

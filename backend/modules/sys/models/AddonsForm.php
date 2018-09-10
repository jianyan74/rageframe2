<?php
namespace backend\modules\sys\models;

use Yii;
use common\helpers\ArrayHelper;
use common\models\sys\Addons;

/**
 * Class AddonsForm
 * @package backend\modules\sys\models
 */
class AddonsForm extends Addons
{
    /**
     * @var
     */
    public $install = 'install.php';

    /**
     * @var
     */
    public $uninstall = 'uninstall.php';

    /**
     * @var
     */
    public $upgrade = 'upgrade.php';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return ArrayHelper::merge([
            [['install', 'uninstall', 'upgrade'], 'required'],
        ], parent::rules());
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge([
            'install' => '安装文件',
            'uninstall' => '卸载文件',
            'upgrade' => '更新文件',
        ], parent::attributeLabels());
    }
}

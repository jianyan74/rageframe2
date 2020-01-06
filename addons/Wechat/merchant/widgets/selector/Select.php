<?php

namespace addons\Wechat\merchant\widgets\selector;

use Yii;
use yii\widgets\InputWidget;
use addons\Wechat\common\models\Attachment;
use addons\Wechat\common\models\AttachmentNews;
use common\helpers\StringHelper;
use common\helpers\Html;

/**
 * Class Select
 * @package addons\Wechat\merchant\widgets\selector
 * @author jianyan74 <751393839@qq.com>
 */
class Select extends InputWidget
{
    public $type;

    public $block;

    /**
     * 盒子ID
     *
     * @var
     */
    protected $boxId;

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \Exception
     */
    public function init()
    {
        parent::init();

        $this->boxId = md5($this->name) . StringHelper::uuid('uniqid');
        $this->value = $this->hasModel() ? Html::getAttributeValue($this->model, $this->attribute) : $this->value;
        $this->name = $this->hasModel() ? Html::getInputName($this->model, $this->attribute) : $this->name;
    }

    /**
     * @return string
     */
    public function run()
    {
        if ($this->type != Attachment::TYPE_NEWS) {
            if (empty($this->value) || empty(($model = Attachment::findOne([
                    'media_id' => $this->value,
                    'merchant_id' => Yii::$app->services->merchant->getId()
                ])))) {
                $model = new Attachment();
                $model = $model->loadDefaultValues();
            }
        } else {
            $data = AttachmentNews::find()
                ->where(['sort' => 0, 'attachment_id' => $this->value])
                ->andWhere(['merchant_id' => Yii::$app->services->merchant->getId()])
                ->one();

            $model = new Attachment();
            if (!empty($data)) {
                $model->file_name = $data->title;
                $model->media_url = $data->thumb_url;
            }
        }

        return $this->render('select', [
            'name' => $this->name,
            'value' => $this->value,
            'model' => $model,
            'boxId' => $this->boxId,
            'type' => $this->type,
            'block' => $this->block,
        ]);
    }
}
<?php
namespace backend\widgets\selector;

use yii\widgets\InputWidget;
use common\models\wechat\Attachment;
use common\models\wechat\AttachmentNews;
use common\helpers\StringHelper;

/**
 * Class Select
 * @package backend\widgets\selector
 * @author jianyan74 <751393839@qq.com>
 */
class Select extends InputWidget
{
    public $type;

    public $label;

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
        $this->boxId = md5($this->name) . StringHelper::uuid('uniqid');
        parent::init();
    }

    /**
     * @return string
     */
    public function run()
    {
        $model = $this->type != Attachment::TYPE_NEWS ? $this->findModel($this->value) : $this->findNew($this->value);

        return $this->render('select', [
            'name' => $this->name,
            'value' => $this->value,
            'model' => $model,
            'boxId' => $this->boxId,
            'type' => $this->type,
            'label' => $this->label,
            'block' => $this->block,
        ]);
    }

    /**
     * 返回模型
     *
     * @param $id
     * @return mixed
     */
    protected function findModel($media_id)
    {
        if (empty($media_id) || empty(($model = Attachment::findOne(['media_id' => $media_id]))))
        {
            $model = new Attachment();
            return $model->loadDefaultValues();
        }

        return $model;
    }

    /**
     * 返回模型
     *
     * @param $id
     * @return mixed
     */
    protected function findNew($attachment_id)
    {
        $data = AttachmentNews::find()
            ->where(['sort' => 0, 'attachment_id' => $attachment_id])
            ->one();

        $model = new Attachment();
        if (!empty($data))
        {
            $model->file_name = $data->title;
            $model->media_url = $data->thumb_url;
        }

        return $model;
    }
}
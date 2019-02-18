<?php
namespace backend\modules\sys\models;

use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\models\sys\Manager;
use yii\base\Model;

class NotifyMessageForm extends Model
{
    public $content;

    public $toManagerId;

    public $data;

    public function init()
    {
        $mamagers = Manager::find()
            ->select(['id', 'username'])
            ->where(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();

        $this->data = ArrayHelper::map($mamagers, 'id', 'username');
        parent::init();
    }

    public function rules()
    {
        return [
            [['content', 'toManagerId'], 'required']
        ];
    }

    public function attributeLabels()
    {
        return [
            'content' => '内容',
            'toManagerId' => '发送对象',
        ];
    }
}
<?php
namespace backend\modules\sys\forms;

use Yii;
use yii\base\Model;

/**
 * Class NotifyMessageForm
 * @package backend\modules\sys\forms
 * @author jianyan74 <751393839@qq.com>
 */
class NotifyMessageForm extends Model
{
    public $content;

    public $toManagerId;

    public $data;

    public function init()
    {
        $this->data = Yii::$app->services->sysManager->getMapList();
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
<?php
namespace backend\modules\wechat\models;

use common\models\wechat\Attachment;

/**
 * Class VideoForm
 * @package backend\modules\wechat\models
 * @author jianyan74 <751393839@qq.com>
 */
class VideoForm extends Attachment
{
    public $description;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['local_url', 'file_name', 'description'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'file_name' => '名称',
            'local_url' => '本地素材',
            'description' => '说明',
        ];
    }
}
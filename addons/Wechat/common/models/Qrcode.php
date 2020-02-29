<?php
namespace addons\Wechat\common\models;

use Yii;
use common\behaviors\MerchantBehavior;

/**
 * This is the model class for table "{{%addon_wechat_qrcode}}".
 *
 * @property string $id
 * @property string $name 场景名称
 * @property string $keyword 关联关键字
 * @property string $scene_id 场景ID
 * @property string $scene_str 场景值
 * @property int $model 类型
 * @property string $ticket ticket
 * @property string $expire_seconds 有效期
 * @property string $subnum 扫描次数
 * @property string $type 二维码类型
 * @property string $extra
 * @property string $url url
 * @property int $end_time
 * @property int $status 状态(-1:已删除,0:禁用,1:正常)
 * @property int $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class Qrcode extends \common\models\base\BaseModel
{
    use MerchantBehavior;

    /**
     * 临时
     */
    const MODEL_TEM = 1;

    /**
     * 永久
     */
    const MODEL_PERPETUAL = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_wechat_qrcode}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name','keyword', 'model'], 'required'],
            [['merchant_id', 'scene_id', 'model', 'expire_seconds', 'subnum', 'extra', 'end_time', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['keyword'], 'string', 'max' => 100],
            [['scene_str'], 'string', 'max' => 64],
            [['ticket'], 'string', 'max' => 250],
            [['type'], 'string', 'max' => 10],
            [['url'], 'string', 'max' => 80],
            ['model', 'verifyModel'],
            ['expire_seconds', 'compare', 'compareValue' => 2592000, 'operator' => '<='],
            ['expire_seconds', 'compare', 'compareValue' => 60, 'operator' => '>='],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '二维码名称',
            'keyword' => '二维码触发的关键字',
            'scene_id' => '场景ID',
            'scene_str' => '场景名称',
            'model' => '二维码类型',
            'ticket' => '微信Ticket',
            'expire_seconds' => '过期时间',
            'subnum' => '扫描次数',
            'type' => '类别',
            'extra' => 'Extra',
            'url' => '二维码图片解析后的地址',
            'end_time' => 'End Time',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * 验证提交的类别
     */
    public function verifyModel()
    {
        if ($this->isNewRecord) {
            // 临时
            if ($this->model == self::MODEL_TEM) {
                empty($this->expire_seconds) && $this->addError('expire_seconds', '临时二维码过期时间必填');
            } else {
                !$this->scene_str && $this->addError('scene_str', '永久二维码场景字符串必填');

                if (self::find()->where(['scene_str' => $this->scene_str, 'merchant_id' => Yii::$app->services->merchant->getId()])->one()) {
                    $this->addError('scene_str', '场景值已经存在');
                }
            }
        }
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->end_time = time() + (int) $this->expire_seconds;
        }

        return parent::beforeSave($insert);
    }
}

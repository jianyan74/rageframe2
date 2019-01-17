<?php
namespace common\models\common;

use Yii;

/**
 * This is the model class for table "{{%common_attachment}}".
 *
 * @property int $id
 * @property string $drive 驱动
 * @property string $upload_type 上传类型
 * @property string $specific_type 类别
 * @property string $base_url url
 * @property string $path 本地路径
 * @property string $name 文件原始名
 * @property string $extension 扩展名
 * @property int $size 长度
 * @property string $year 年份
 * @property int $month 月份
 * @property string $day 日
 * @property string $upload_ip 上传者ip
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property string $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class Attachment extends \common\models\common\BaseModel
{
    const UPLOAD_TYPE_IMAGES = 'images';
    const UPLOAD_TYPE_FILES = 'files';
    const UPLOAD_TYPE_VIDEOS = 'videos';
    const UPLOAD_TYPE_VOICES = 'voices';

    const DRIVE_LOCAL = 'local';
    const DRIVE_QINIU = 'qiniu';
    const DRIVE_OSS = 'oss';

    /**
     * @var array
     */
    public static $driveExplain = [
        self::DRIVE_LOCAL => '本地',
        self::DRIVE_QINIU => '七牛',
        self::DRIVE_OSS => 'OSS',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_attachment}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['size', 'year', 'month', 'day', 'status', 'created_at', 'updated_at'], 'integer'],
            [['drive', 'extension'], 'string', 'max' => 50],
            [['upload_type'], 'string', 'max' => 10],
            [['specific_type'], 'string', 'max' => 255],
            [['base_url', 'path', 'name'], 'string', 'max' => 100],
            [['upload_ip'], 'string', 'max' => 16],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'drive' => '驱动',
            'upload_type' => '上传类别',
            'specific_type' => '文件类别',
            'base_url' => 'Url',
            'path' => '本地路径',
            'name' => '文件名',
            'extension' => '扩展',
            'size' => '文件大小',
            'year' => '年',
            'month' => '月',
            'day' => '日',
            'upload_ip' => '上传者ip',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => 'Updated At',
        ];
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord)
        {
            $this->upload_ip = Yii::$app->request->userIP;
            $this->year = date('Y');
            $this->month = date('m');
            $this->day = date('d');
        }

        return parent::beforeSave($insert);
    }
}

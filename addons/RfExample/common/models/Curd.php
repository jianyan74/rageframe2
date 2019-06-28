<?php
namespace addons\RfExample\common\models;

use Yii;
use common\behaviors\MerchantBehavior;
use common\models\sys\Manager;
use common\helpers\StringHelper;

/**
 * This is the model class for table "{{%addon_example_curd}}".
 *
 * @property string $id ID
 * @property string $title 标题
 * @property string $cate_id 分类ID(单选)
 * @property string $manager_id 管理员ID
 * @property int $sort 排序
 * @property int $position 推荐位
 * @property int $sex 性别1男2女
 * @property string $content 内容
 * @property string $cover 图片
 * @property string $covers 图片组
 * @property string $file 文件
 * @property string $files 文件组
 * @property string $attachfile 附件
 * @property string $keywords 关键字
 * @property string $description 描述
 * @property double $price 价格
 * @property string $views 点击
 * @property int $start_time 开始时间
 * @property int $end_time 结束时间
 * @property string $email
 * @property string $provinces
 * @property string $city
 * @property string $area
 * @property string $ip ip
 * @property int $status 状态
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class Curd extends \common\models\common\BaseModel
{
    use MerchantBehavior;

    public $province_ids;
    public $city_ids;
    public $area_ids;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_example_curd}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'cate_id', 'manager_id', 'sort', 'position', 'sex', 'views', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title', 'content', 'covers', 'files', 'cover', 'file'], 'required'],
            [['content'], 'string'],
            [['price'], 'number'],
            [['start_time', 'end_time', 'files', 'covers', 'address'], 'safe'],
            [['title'], 'string', 'max' => 50],
            [['cover', 'attachfile', 'keywords', 'tag'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 200],
            [['email'], 'string', 'max' => 60],
            [['provinces', 'city', 'area'], 'integer'],
            [['ip'], 'string', 'max' => 16],
            [['color'], 'string', 'max' => 7],
            [['date', 'time'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'cate_id' => '分类ID',
            'manager_id' => '创建者ID',
            'sort' => '排序',
            'position' => '推荐位',
            'sex' => '性别',
            'content' => '内容',
            'tag' => '标签',
            'cover' => '封面',
            'covers' => '轮播图',
            'file' => '文件',
            'files' => '多文件上传',
            'attachfile' => '附件',
            'keywords' => '关键字',
            'description' => '简单介绍',
            'price' => '价格',
            'views' => '浏览量',
            'date' => '日期',
            'time' => '时间',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'status' => '状态',
            'email' => '邮箱',
            'provinces' => '省',
            'city' => '市',
            'area' => '区',
            'ip' => 'ip',
            'color' => '颜色',
            'address' => '经纬度选择',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * 关联当前用户
     *
     * @return \yii\db\ActiveQuery
     */
    public function getManager()
    {
        return $this->hasOne(Manager::class, ['id' => 'manager_id']);
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        //创建时候插入
        if ($this->isNewRecord) {
            $this->ip = Yii::$app->request->userIP;
            $this->manager_id = Yii::$app->user->id;
        }

        $this->start_time = StringHelper::dateToInt(($this->start_time));
        $this->end_time = StringHelper::dateToInt(($this->end_time));

        return parent::beforeSave($insert);
    }
}

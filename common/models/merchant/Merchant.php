<?php

namespace common\models\merchant;

use Yii;
use common\enums\AppEnum;
use common\enums\StatusEnum;

/**
 * This is the model class for table "rf_merchant".
 *
 * @property int $id
 * @property string $title 商户名称
 * @property string $tax_rate 税率
 * @property string $cover 头像
 * @property int $term_of_validity_type 有效期类型 0固定时间 1不限
 * @property int $cate_id 分类
 * @property int $start_time 开始时间
 * @property int $end_time 结束时间
 * @property string $email 邮箱
 * @property int $province_id 省
 * @property int $city_id 城市
 * @property int $area_id 地区
 * @property string $address_name 地址
 * @property string $address_details 详细地址
 * @property string $longitude 经度
 * @property string $latitude 纬度
 * @property string $mobile 手机号码
 * @property int $level_id 店铺等级
 * @property string $company_name 店铺公司名称
 * @property string $close_info 店铺关闭原因
 * @property int $sort 店铺排序
 * @property string $logo 店铺logo
 * @property string $banner 店铺横幅
 * @property string $keywords 店铺seo关键字
 * @property string $description 店铺seo描述
 * @property string $qq QQ
 * @property string $ww 阿里旺旺
 * @property int $is_recommend 推荐，0为否，1为是，默认为0
 * @property int $credit 店铺信用
 * @property double $desc_credit 描述相符度分数
 * @property double $service_credit 服务态度分数
 * @property double $delivery_credit 发货速度分数
 * @property int $collect 店铺收藏数量
 * @property string $stamp 店铺印章
 * @property string $print_desc 打印订单页面下方说明文字
 * @property string $sales 店铺销售额（不计算退款）
 * @property string $free_time 商家配送时间
 * @property string $region 店铺默认配送区域
 * @property string $qrcode 店铺公众号
 * @property int $state 店铺状态，0关闭，1开启，2审核中
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class Merchant extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%merchant}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'company_name', 'cate_id', 'tax_rate', 'mobile'], 'required'],
            [['tax_rate'], 'number', 'min' => 0, 'max' => 100],
            [['sales'], 'number'],
            [['desc_credit', 'service_credit', 'delivery_credit'], 'number', 'min' => 0, 'max' => 5],
            [['term_of_validity_type', 'cate_id', 'start_time', 'end_time', 'province_id', 'city_id', 'area_id', 'level_id', 'sort', 'is_recommend', 'credit', 'collect', 'state', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title', 'address_name', 'stamp'], 'string', 'max' => 200],
            [['cover'], 'string', 'max' => 150],
            [['email'], 'string', 'max' => 60],
            [['address_details', 'longitude', 'latitude', 'mobile', 'logo', 'banner', 'qrcode'], 'string', 'max' => 100],
            [['company_name', 'qq', 'ww', 'region'], 'string', 'max' => 50],
            [['close_info', 'keywords', 'description'], 'string', 'max' => 255],
            [['print_desc'], 'string', 'max' => 500],
            [['free_time', 'business_time'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '店铺名称',
            'tax_rate' => '税率',
            'cover' => '店铺封面',
            'term_of_validity_type' => '有效期类型 0固定时间 1不限',
            'cate_id' => '分类',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'email' => '邮箱',
            'province_id' => '省',
            'city_id' => '城市',
            'area_id' => '地区',
            'address_name' => '地址',
            'address_details' => '详细地址',
            'longitude' => '经度',
            'latitude' => '纬度',
            'mobile' => '手机号码',
            'level_id' => '店铺等级',
            'company_name' => '公司名称',
            'close_info' => '店铺关闭原因',
            'sort' => '店铺排序',
            'logo' => '店铺logo',
            'banner' => '店铺横幅',
            'keywords' => '店铺seo关键字',
            'description' => '店铺seo描述',
            'qq' => 'QQ',
            'ww' => '阿里旺旺',
            'is_recommend' => '推荐',
            'credit' => '店铺信用',
            'desc_credit' => '描述相符度分数',
            'service_credit' => '服务态度分数',
            'delivery_credit' => '发货速度分数',
            'collect' => '店铺收藏数量',
            'stamp' => '店铺印章',
            'print_desc' => '打印订单页面下方说明文字',
            'sales' => '店铺销售额（不计算退款）',
            'business_time' => '商家营业时间',
            'free_time' => '商家配送时间',
            'region' => '店铺默认配送区域',
            'qrcode' => '公众号二维码',
            'state' => '店铺状态',
            'status' => '状态[-1:删除;0:禁用;1启用]',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * 关联账号
     */
    public function getAccount()
    {
        return $this->hasOne(Account::class, ['merchant_id' => 'id']);
    }

    /**
     * 关联分类
     */
    public function getCate()
    {
        return $this->hasOne(Cate::class, ['id' => 'cate_id']);
    }

    public function beforeSave($insert)
    {
        $this->address_name = Yii::$app->services->provinces->getCityListName([$this->province_id, $this->city_id, $this->area_id]);

        // 修改启用状态
        if ($this->status != StatusEnum::DELETE) {
            if (
                !empty($this->title) &&
                !empty($this->company_name) &&
                !empty($this->cover) &&
                !empty($this->city_id) &&
                !empty($this->province_id) &&
                !empty($this->area_id) &&
                !empty($this->address_details)
            ) {
                $this->status = StatusEnum::ENABLED;
            } else {
                $this->status = StatusEnum::DISABLED;
            }
        }

        return parent::beforeSave($insert);
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     * @throws \yii\db\Exception
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            $account = new Account();
            $account->merchant_id = $this->id;
            $account->save();
            // 复制默认角色进入商户
            Yii::$app->services->rbacAuthRole->cloneInDefault(AppEnum::MERCHANT, $this->id);
        }

        if ($this->status == StatusEnum::DELETE) {
            Account::updateAll(['status' => StatusEnum::DELETE], ['merchant_id' => $this->id]);
        }

        parent::afterSave($insert, $changedAttributes);
    }
}

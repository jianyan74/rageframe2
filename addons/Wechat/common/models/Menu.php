<?php
namespace addons\Wechat\common\models;

use common\behaviors\MerchantBehavior;
use common\enums\StatusEnum;

/**
 * This is the model class for table "{{%addon_wechat_custom_menu}}".
 *
 * @property int $id 公众号id
 * @property string $menu_id 微信菜单id
 * @property int $type 1:默认菜单；2个性化菜单
 * @property string $title 标题
 * @property int $sex 性别
 * @property int $tag_id 标签id
 * @property int $client_platform_type 手机系统
 * @property string $province 省
 * @property string $city 市
 * @property string $language 语言
 * @property string $local_data
 * @property string $menu_data 微信菜单
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property string $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class Menu extends \common\models\base\BaseModel
{
    use MerchantBehavior;

    const TYPE_CUSTOM = 1;
    const TYPE_INDIVIDUATION = 2;

    public static $typeExplain = [
        self::TYPE_CUSTOM => '默认菜单',
        self::TYPE_INDIVIDUATION => '个性化菜单',
    ];

    /**
     * 菜单类型
     * 注: 有value属性的在提交菜单是该类型的值必须设置为此值, 没有的则不限制
     * @var array
     */
    public static $menuTypes = [
        'click' => [
            'name' => '发送消息 ',
            'meta' => 'key',
            'alert' => '微信服务器会通过消息接口推送消息类型为event的结构给开发者（参考消息接口指南），并且带上按钮中开发者填写的key值，开发者可以通过自定义的key值与用户进行交互；'
        ],
        'view' => [
            'name' => '跳转网页',
            'meta' => 'url',
            'alert' => '微信客户端将会打开开发者在按钮中填写的网页URL，可与网页授权获取用户基本信息接口结合，获得用户基本信息。'
        ],
        'scancode_waitmsg' => [
            'name' => '扫码',
            'meta' => 'key',
            'value' => 'rselfmenu_0_0',
            'alert' => '微信客户端将调起扫一扫工具，完成扫码操作后，将扫码的结果传给开发者，同时收起扫一扫工具，然后弹出“消息接收中”提示框。'
        ],
        'scancode_push' => [
            'name' => '扫码(等待信息)',
            'meta' => 'key',
            'value' => 'rselfmenu_0_1',
            'alert' => '微信客户端将调起扫一扫工具，完成扫码操作后显示扫描结果（如果是URL，将进入URL），且会将扫码的结果传给开发者。'
        ],
        'location_select' => [
            'name' => '地理位置',
            'meta' => 'key',
            'value' => 'rselfmenu_2_0',
            'alert' => '微信客户端将调起地理位置选择工具，完成选择操作后，将选择的地理位置发送给开发者的服务器，同时收起位置选择工具。'
        ],
        'pic_sysphoto' => [
            'name' => '拍照发图',
            'meta' => 'key',
            'value' => 'rselfmenu_1_0',
            'alert' => '微信客户端将调起系统相机，完成拍照操作后，会将拍摄的相片发送给开发者，并推送事件给开发者，同时收起系统相机。'
        ],
        'pic_photo_or_album' => [
            'name' => '拍照相册 ',
            'meta' => 'key',
            'value' => 'rselfmenu_1_1',
            'alert' => '微信客户端将弹出选择器供用户选择“拍照”或者“从手机相册选择”。用户选择后即走其他两种流程。'
        ],
        'pic_weixin' => [
            'name' => '相册发图 ',
            'meta' => 'key',
            'value' => 'rselfmenu_1_2',
            'alert' => '微信客户端将调起微信相册，完成选择操作后，将选择的相片发送给开发者的服务器，并推送事件给开发者，同时收起相册。'
        ],
        'miniprogram' => [
            'name' => '关联小程序',
            'meta' => 'key',
            'alert' => '点击该菜单跳转到关联的小程序'
        ],
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_wechat_menu}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['merchant_id', 'menu_id', 'type', 'sex', 'tag_id', 'client_platform_type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['menu_data'], 'safe'],
            [['title'], 'string', 'max' => 30],
            [['province', 'country'], 'string', 'max' => 100],
            [['city', 'language'], 'string', 'max' => 50],
            [['title'], 'verifyEmpty'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'menu_id' => 'Menu ID',
            'type' => '类型',
            'title' => '菜单名称',
            'sex' => '性别',
            'tag_id' => '标签',
            'client_platform_type' => '客户端类型',
            'province' => '省',
            'city' => '市',
            'language' => '语言',
            'menu_data' => '微信数据',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * 验证是否全部为空
     *
     * @return bool|void
     */
    public function verifyEmpty()
    {
        if($this->type == self::TYPE_INDIVIDUATION && empty($this->sex) && empty($this->tag_id) && empty($this->client_platform_type) && empty($this->city) && empty($this->province) && empty($this->language)) {
            $this->addError('sex', '菜单显示对象至少要有一个匹配信息是不为空的');
        }
    }

    /**
     * 修改默认菜单状态
     *
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $this->status = StatusEnum::ENABLED;
        return parent::beforeSave($insert);
    }

    /**
     * 修改其他菜单状态
     *
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($this->type == self::TYPE_CUSTOM) {
            self::updateAll(['status' => StatusEnum::DISABLED],
                [
                    'and',
                    ['not in', 'id', [$this->id]],
                    ['type' => self::TYPE_CUSTOM],
                    ['merchant_id' => \Yii::$app->services->merchant->getId()]
                ]);
        }

        parent::afterSave($insert, $changedAttributes);
    }
}

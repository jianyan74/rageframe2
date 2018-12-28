<?php
namespace common\models\wechat;

use common\helpers\ArrayHelper;
use common\models\sys\Addons;

/**
 * This is the model class for table "{{%wechat_setting}}".
 *
 * @property int $id
 * @property string $history 历史消息参数设置
 * @property string $special 特殊消息回复参数
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class Setting extends \common\models\common\BaseModel
{
    /**
     * 特殊消息回复类别 - 关键字
     */
    const SPECIAL_TYPE_KEYWORD = 1;
    /**
     * 特殊消息回复类别 - 模块
     */
    const SPECIAL_TYPE_MODUL = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%wechat_setting}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['special'], 'string'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['history'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'history' => '参数',
            'special' => '特殊消息回复',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * 获取特殊消息回复
     *
     * @return array
     */
    public static function specialConfig()
    {
        // 获取支持的模块
        $modules = Addons::getList();

        $list = Account::$typeExplanation;
        $defaultList = [];
        foreach ($list as $key => $value)
        {
            $defaultList[$key]['title'] = $value;
            $defaultList[$key]['type'] = self::SPECIAL_TYPE_KEYWORD;
            $defaultList[$key]['content'] = '';
            $defaultList[$key]['module'] = [];

            foreach ($modules as $module)
            {
                $wechat_message = !empty($module['wechat_message']) ? unserialize($module['wechat_message']) : [];
                $wechat_message = $wechat_message ?? [];

                foreach ($wechat_message as $item)
                {
                    if ($key == $item)
                    {
                        $defaultList[$key]['module'][$module['name']] = $module['title'];
                        break;
                    }
                }
            }
        }

        $model = Setting::find()->one();
        if (isset($model['special']))
        {
            $defaultList = ArrayHelper::merge($defaultList, json_decode($model['special'], true));
        }

        return $defaultList;
    }

    /**
     * 获取数据
     *
     * @return array|mixed
     */
    public static function getData($filds)
    {
        $setting = Setting::find()->asArray()->one();
        if (!empty($setting[$filds]))
        {
            return json_decode($setting[$filds], true);
        }

        return [];
    }

    /**
     * 写入字段数据
     *
     * @return array|mixed
     */
    public static function setData($filds, $data)
    {
        $setting = Setting::find()->one();
        if (!$setting)
        {
            $setting = new self();
        }

        $setting->$filds = json_encode($data);
        return $setting->save();
    }
}

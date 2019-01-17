<?php
namespace common\models\sys;

use Yii;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%sys_menu}}".
 *
 * @property int $id
 * @property string $title 标题
 * @property int $pid 上级id
 * @property string $url 链接地址
 * @property string $menu_css 样式
 * @property int $sort 排序
 * @property int $level 级别
 * @property string $params 参数
 * @property string $cate_id menu:菜单;sys:系统菜单
 * @property int $dev 开发者[0:都可见;开发模式可见]
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 添加时间
 * @property int $updated_at 修改时间
 */
class Menu extends \common\models\common\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%sys_menu}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'cate_id'], 'required'],
            [['cate_id', 'pid', 'sort', 'level', 'dev', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title', 'url', 'menu_css'], 'string', 'max' => 50],
            ['url', 'default', 'value' => "#"],
            [['pid','sort'], 'default', 'value' => 0],
            [['level'], 'default', 'value' => 1],
            [['params'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'pid' => '父级id',
            'url' => '路由',
            'menu_css' => '图标css',
            'sort' => '排序',
            'level' => '级别',
            'params' => '参数',
            'cate_id' => '分类',
            'dev' => '仅开发模式可见',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * 返回菜单列表
     *
     * @param bool $status 状态
     * @return array
     */
    public static function getAuthShowList($status = false)
    {
        $data = Menu::find()->andFilterWhere(['status' => $status]);
        // 关闭开发模式
        if (empty(Yii::$app->debris->config('sys_dev')))
        {
            $data = $data->andWhere(['dev' => StatusEnum::DISABLED]);
        }

        // 非总管理员菜单显示
        if (Yii::$app->user->id != Yii::$app->params['adminAccount'])
        {
            // 查询用户权限
            $authAssignment = AuthAssignment::finldByUserId(Yii::$app->user->id);
            $urls = [];
            if (!empty($authAssignment['authItemChild']))
            {
                foreach ($authAssignment['authItemChild'] as $item)
                {
                    $urls[] = $item['child'];
                }
            }

            $data = $data->andWhere(['in', 'url', $urls]);
        }

        $models = $data->orderBy('cate_id asc, sort asc')
            ->with('cate')
            ->asArray()
            ->all();

        // 让 url 支持参数传递
        foreach ($models as &$model)
        {
            $params = unserialize($model['params']);
            empty($params) && $params = [];
            $model['fullUrl'][] = $model['url'];
            foreach ($params as $param)
            {
                if (!empty($param['key']))
                {
                    $model['fullUrl'][$param['key']] = $param['value'];
                }
            }
        }

        return ArrayHelper::itemsMerge($models);
    }

    /**
     * 关联分类
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCate()
    {
        return $this->hasOne(MenuCate::className(), ['id' => 'cate_id']);
    }

    /**
     * 删除全部子类
     * 
     * @return bool
     */
    public function beforeDelete()
    {
        $ids = ArrayHelper::getChildIds(self::find()->all(), $this->id);
        self::deleteAll(['in', 'id', $ids]);

        return parent::beforeDelete();
    }
}

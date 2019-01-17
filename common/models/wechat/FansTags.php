<?php
namespace common\models\wechat;

use Yii;

/**
 * This is the model class for table "{{%wechat_fans_tags}}".
 *
 * @property int $id
 * @property string $tags 标签
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property string $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class FansTags extends \common\models\common\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%wechat_fans_tags}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tags'], 'string'],
            [['status', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tags' => '标签',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * 获取标签信息
     *
     * @return mixed
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public static function getList()
    {
        if (empty(($model = self::find()->one())))
        {
            return self::updateList();
        }

        return unserialize($model->tags);
    }

    /**
     * 获取单个标签信息
     *
     * @param $id
     * @return array|bool|null|\yii\db\ActiveRecord
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public static function findById($id)
    {
        if (empty(($model = self::find()->one())))
        {
            $tags = self::updateList();
        }
        else
        {
            $tags = unserialize($model->tags);
        }

        foreach ($tags as $vo)
        {
            if ($vo['id'] == $id)
            {
                return $vo;
            }
        }

        return false;
    }

    /**
     * 获取标签信息并保存到数据库
     *
     * @return mixed
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public static function updateList()
    {
        $list = Yii::$app->wechat->app->user_tag->list();
        Yii::$app->debris->getWechatError($list);

        $tags = $list['tags'];
        if (empty(($model = FansTags::find()->one())))
        {
            $model = new self();
        }

        $model->tags = serialize($tags);
        $model->save();

        return $tags;
    }

    /**
     * 删除粉丝关联标签
     *
     * @return bool
     */
    public function beforeDelete()
    {
        FansTagMap::deleteAll(['tag_id' => $this->id]);

        return parent::beforeDelete();
    }
}

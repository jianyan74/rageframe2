<?php
namespace common\models\wechat;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%wechat_fans_stat}}".
 *
 * @property string $id
 * @property int $new_attention 今日新关注
 * @property int $cancel_attention 今日取消关注
 * @property int $cumulate_attention 累计关注
 * @property string $date
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property string $created_at 添加时间
 * @property string $updated_at 修改时间
 */
class FansStat extends \common\models\common\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%wechat_fans_stat}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['new_attention', 'cancel_attention', 'cumulate_attention', 'status', 'created_at', 'updated_at'], 'integer'],
            [['date'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'new_attention' => '今日新关注',
            'cancel_attention' => '今日取消关注',
            'cumulate_attention' => '累计关注',
            'date' => '日期',
            'status' => 'Status',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * 关注计算
     */
    public static function upFollowNum()
    {
        if(!($today = self::find()->where(['date' => date('Y-m-d')])->one()))
        {
            $today = new self();
            $today->date = date('Y-m-d');
            $today->created_at = strtotime($today->date);
        }

        $today->new_attention += 1;
        $today->save();
    }

    /**
     * 取消关注计算
     */
    public static function upUnFollowNum()
    {
        if(!($today = self::find()->where(['date' => date('Y-m-d')])->one()))
        {
            $today = new self();
            $today->date = date('Y-m-d');
            $today->created_at = strtotime($today->date);
        }

        $today->cancel_attention += 1;
        $today->save();
    }

    /**
     * @param $app
     * @return bool
     */
    public static function getFansStat()
    {
        // 缓存设置
        $cacheKey = 'fans:status:todaylock';
        if (Yii::$app->cache->get($cacheKey))
        {
            return true;
        }

        $sevenDays = [
            date('Y-m-d', strtotime('-1 days')),
            date('Y-m-d', strtotime('-2 days')),
            date('Y-m-d', strtotime('-3 days')),
            date('Y-m-d', strtotime('-4 days')),
            date('Y-m-d', strtotime('-5 days')),
            date('Y-m-d', strtotime('-6 days')),
            date('Y-m-d', strtotime('-7 days')),
        ];

        $models = self::find()
            ->where(['in','date',$sevenDays])
            ->all();

        $statUpdate = false;
        $weekStat = [];
        foreach ($models as $model)
        {
            $weekStat[$model['date']] = $model;
        }

        // 查询数据是否有
        foreach ($sevenDays as $sevenDay)
        {
            if (empty($weekStat[$sevenDay]) || $weekStat[$sevenDay]['cumulate_attention'] <= 0)
            {
                $statUpdate = true;
                break;
            }
        }

        if (empty($statUpdate))
        {
            return true;
        }

        // 获取微信统计数据
        $stats = Yii::$app->wechat->app->data_cube;
        // 增减
        $userSummary = $stats->userSummary($sevenDays[6], $sevenDays[0]);
        // 累计用户
        $userCumulate = $stats->userCumulate($sevenDays[6], $sevenDays[0]);

        $list = [];
        if (!empty($userSummary['list']))
        {
            foreach ($userSummary['list'] as $row)
            {
                $key = $row['ref_date'];
                $list[$key]['new_attention'] = $row['new_user'];
                $list[$key]['cancel_attention'] = $row['cancel_user'];
            }
        }

        if (!empty($userCumulate['list']))
        {
            foreach ($userCumulate['list'] as $row)
            {
                $key = $row['ref_date'];
                $list[$key]['cumulate_attention'] = $row['cumulate_user'];
            }
        }

        // 更新到数据库
        foreach ($list as $key => $value)
        {
            $model = new self();
            if(isset($weekStat[$key]))
            {
                $model = $weekStat[$key];
            }

            $model->attributes = $value;
            $model->date = $key;
            $model->created_at = strtotime($key);
            $model->save();
        }

        // 今日累计关注统计计算
        $cumulate_attention = Fans::getCountFollowFans();
        if(!($today = self::find()->where(['date' => date('Y-m-d')])->one()))
        {
            $today = new self();
            $today->date = date('Y-m-d');
            $today->created_at = strtotime($today->date);
        }

        $today->cumulate_attention = $cumulate_attention;
        $today->save();

        Yii::$app->cache->set($cacheKey, true, 7200);
        return true;
    }

    /**
     * 行为
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['updated_at'],
                ],
            ],
        ];
    }
}

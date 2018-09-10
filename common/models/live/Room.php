<?php

namespace common\models\live;

use Yii;
use common\models\common\BaseModel;

/**
 * This is the model class for table "{{%live_room}}".
 *
 * @property int $id
 * @property int $member_id 会员id
 * @property string $title 房间名称
 * @property string $cover 封面
 * @property int $room_num 房间号
 * @property int $recommend 推荐位
 * @property int $like_num 喜欢人数
 * @property int $watch_num 观看人数
 * @property int $sort 排序
 * @property int $view 浏览量
 * @property string $push_path 推流地址
 * @property string $push_path_arg 推流变量地址
 * @property string $pull_path_rtmp 拉流rtmp地址
 * @property string $pull_path_flv 拉流rtmp地址
 * @property string $pull_path_m3u8 拉流rtmp地址
 * @property int $start_time 直播开始时间
 * @property int $end_time 直播结束时间
 * @property int $status 状态(-1:已删除,0:禁用,1:正常)
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class Room extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%live_room}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_id', 'room_num', 'recommend', 'like_num', 'watch_num', 'max_watch_num', 'sort', 'view', 'start_time', 'end_time', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title', 'push_path'], 'string', 'max' => 100],
            [['push_path_arg', 'pull_path_rtmp', 'pull_path_flv', 'pull_path_m3u8'], 'string', 'max' => 200],
            [['cover'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => '会员id',
            'title' => '标题',
            'cover' => '封面',
            'room_num' => '房间名',
            'recommend' => '推荐位',
            'like_num' => '喜欢人数',
            'watch_num' => '正在观看人数',
            'max_watch_num' => '最多正在观看人数',
            'sort' => '排序',
            'view' => '浏览量',
            'push_path' => '推流域名',
            'push_path_arg' => '推流变量地址',
            'pull_path_rtmp' => 'Rtmp拉流地址',
            'pull_path_flv' => 'Flv拉流地址',
            'pull_path_m3u8' => 'M3u8拉流地址',
            'start_time' => '直播开始时间',
            'end_time' => '直播结束时间',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}

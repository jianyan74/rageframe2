<?php

namespace common\models\common;

use Yii;

/**
 * This is the model class for table "{{%common_report_log}}".
 *
 * @property string $id
 * @property string $app_id 应用id
 * @property int $log_id 公用日志id
 * @property int $merchant_id 商户id
 * @property int $user_id 用户ID
 * @property string $device_id 设备ID
 * @property string $device_name 设备名称
 * @property int $width 屏幕宽度
 * @property int $height 屏幕高度
 * @property string $os 操作系统
 * @property string $os_version 操作系统版本
 * @property int $is_root 是否越狱， 0:未越狱， 1:已越狱
 * @property string $network 网络类型
 * @property string $wifi_ssid wifi的编号
 * @property string $wifi_mac WIFI的mac
 * @property string $xyz 三轴加速度
 * @property string $version_name APP版本名
 * @property string $api_version API的版本号
 * @property string $channel 渠道名
 * @property int $app_name APP编号， 1:android， 2:iphone
 * @property int $dpi 屏幕密度
 * @property int $api_level android的API的版本号
 * @property string $operator 运营商
 * @property string $idfa iphone的IDFA
 * @property string $idfv iphone的IDFV
 * @property string $open_udid iphone的OpenUdid
 * @property string $ip IP地址
 * @property string $wlan_ip 局网ip地址
 * @property string $user_agent 浏览器的UA
 * @property string $time 客户端时间
 * @property int $created_at 创建时间
 */
class ReportLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_report_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['log_id', 'merchant_id', 'user_id', 'width', 'height', 'is_root', 'app_name', 'dpi', 'api_level', 'created_at'], 'integer'],
            [['time'], 'safe'],
            [['app_id'], 'string', 'max' => 50],
            [['device_id', 'device_name', 'os', 'os_version', 'network', 'wifi_mac', 'xyz', 'channel', 'operator', 'idfa', 'wlan_ip'], 'string', 'max' => 64],
            [['wifi_ssid'], 'string', 'max' => 128],
            [['version_name'], 'string', 'max' => 16],
            [['api_version', 'idfv', 'open_udid', 'user_agent'], 'string', 'max' => 255],
            [['ip'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'app_id' => '应用id',
            'log_id' => '公用日志id',
            'merchant_id' => '商户id',
            'user_id' => '用户ID',
            'device_id' => '设备ID',
            'device_name' => '设备名称',
            'width' => '屏幕宽度',
            'height' => '屏幕高度',
            'os' => '操作系统',
            'os_version' => '操作系统版本',
            'is_root' => '是否越狱， 0:未越狱， 1:已越狱',
            'network' => '网络类型',
            'wifi_ssid' => 'wifi的编号',
            'wifi_mac' => 'WIFI的mac',
            'xyz' => '三轴加速度',
            'version_name' => 'APP版本名',
            'api_version' => 'API的版本号',
            'channel' => '渠道名',
            'app_name' => 'APP编号， 1:android， 2:iphone',
            'dpi' => '屏幕密度',
            'api_level' => 'android的API的版本号',
            'operator' => '运营商',
            'idfa' => 'iphone的IDFA',
            'idfv' => 'iphone的IDFV',
            'open_udid' => 'iphone的OpenUdid',
            'ip' => 'IP地址',
            'wlan_ip' => '局网ip地址',
            'user_agent' => '浏览器的UA',
            'time' => '客户端时间',
            'created_at' => '创建时间',
        ];
    }
}

<?php

namespace services\common;

use common\components\Service;
use common\models\common\Log;
use common\models\common\ReportLog;

/**
 * Class ReportLogService
 * @package services\common
 * @author jianyan74 <751393839@qq.com>
 */
class ReportLogService extends Service
{
    /**
     * 风控日志记录
     *
     * @param Log $log
     */
    public function create(Log $log)
    {
        $model = new ReportLog();
        $model->log_id = $log->id;
        $model->user_id = $log->user_id;
        $model->merchant_id = $log->merchant_id;
        $model->app_id = $log->app_id;
        $model->ip = (string) $log->ip;
        $model->created_at = $log->created_at;
        /** @var array $header_data */
        $header_data = $log->header_data;
        $header = [];
        foreach ($header_data as $key => $header_datum) {
            $header[$key] = $header_datum[0] ?? '';
        }

        $model->device_id = $header['device-id'] ?? '';
        $model->device_name = $header['device-name'] ?? '';
        $model->width = $header['width'] ?? '';
        $model->height = $header['height'] ?? '';
        $model->os = $header['os'] ?? '';
        $model->os_version = $header['os-version'] ?? '';
        $model->is_root = $header['is-root'] ?? '';
        $model->network = $header['network'] ?? '';
        $model->wifi_ssid = $header['wifi-ssid'] ?? '';
        $model->wifi_mac = $header['wifi-mac'] ?? '';
        $model->xyz = $header['xyz'] ?? '';
        $model->version_name = $header['version-name'] ?? '';
        $model->api_version = $header['api-version'] ?? '';
        $model->channel = $header['channel'] ?? '';
        $model->app_name = $header['app-name'] ?? '';
        $model->dpi = $header['dpi'] ?? '';
        $model->api_level = $header['api-level'] ?? '';
        $model->operator = $header['operator'] ?? '';
        $model->idfa = $header['idfa'] ?? '';
        $model->idfv = $header['idfv'] ?? '';
        $model->open_udid = $header['open-udid'] ?? '';
        $model->wlan_ip = $header['wlan-ip'] ?? '';
        $model->user_agent = $header['user-agent'] ?? '';
        $model->time = $header['time'] ?? '';
        $model->save();
    }
}
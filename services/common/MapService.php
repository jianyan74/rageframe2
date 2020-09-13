<?php

namespace services\common;

use Yii;
use yii\helpers\Json;
use linslin\yii2\curl\Curl;
use common\components\Service;

/**
 * Class MapService
 * @package services\common
 * @author jianyan74 <751393839@qq.com>
 */
class MapService extends Service
{
    /**
     * 高德根据地址获取经纬度
     *
     * @param $address
     * @return bool|false|string[]
     * @throws \Exception
     */
    public function aMapAddressToLocation($address)
    {
        $url = 'http://restapi.amap.com/v3/geocode/geo?address=' . $address . '&key=' . Yii::$app->debris->backendConfig('map_amap_web_key');
        $curl = new Curl();
        if ($result = $curl->get($url)) {
            $result = Json::decode($result);
            //判断是否成功
            if (!empty($result['count'])) {
                $geo = $result['geocodes']['0'];

                return [
                    'country' => $geo['country'] ?? '',
                    'province' => $geo['province'] ?? '',
                    'citycode' => $geo['citycode'] ?? '',
                    'city' => $geo['city'] ?? '',
                    'district' => $geo['district'] ?? '',
                    'township' => $geo['township'] ?? '',
                    'towncode' => $geo['towncode'] ?? '',
                    'location' => $geo['location'] ?? '',
                    'adcode' => $geo['adcode'] ?? '',
                    'level' => $geo['level'] ?? '',
                    'businessAreas' => $geo['businessAreas'] ?? '',
                ];
            }
        }

        return false;
    }

    /**
     * 高德经纬度转地址
     *
     * @param string $location 2322,12.15544
     * @return bool|mixed
     * @throws \Exception
     */
    public function aMapLocationToAddress($location)
    {
        $url = "http://restapi.amap.com/v3/geocode/regeo?output=json&location=" . $location . "&key=" . Yii::$app->debris->backendConfig('map_amap_web_key');
        $curl = new Curl();
        if ($result = $curl->get($url)) {
            $result = Json::decode($result);
            if (!empty($result['status']) && $result['status'] == 1) {
                $addressComponent = $result['regeocode']['addressComponent'];

                return [
                    'country' => $addressComponent['country'] ?? '',
                    'province' => $addressComponent['province'] ?? '',
                    'citycode' => $addressComponent['citycode'] ?? '',
                    'city' => $addressComponent['city'] ?? '',
                    'district' => $addressComponent['district'] ?? '',
                    'township' => $addressComponent['township'] ?? '',
                    'towncode' => $addressComponent['towncode'] ?? '',
                    'location' => $addressComponent['location'] ?? '',
                    'adcode' => $addressComponent['adcode'] ?? '',
                    'level' => $addressComponent['level'] ?? '',
                    'businessAreas' => $addressComponent['businessAreas'] ?? '',
                    'streetNumber' => $addressComponent['streetNumber'] ?? '',
                    'formatted_address' => $result['regeocode']['formatted_address']
                ];
            }
        }

        return false;
    }
}
<?php
namespace backend\widgets\selectmap;

use Yii;
use yii\helpers\Html;
use yii\widgets\InputWidget;
use yii\helpers\Json;
use common\helpers\StringHelper;

/**
 * 地图经纬度选择器
 *
 * Class Map
 * @package backend\widgets\selectmap
 * @author jianyan74 <751393839@qq.com>
 */
class Map extends InputWidget
{
    /**
     * 默认地址
     *
     * @var bool
     */
    public $defaultSearchAddress = '北京';

    /**
     * 秘钥
     *
     * @var string
     */
    public $secret_key = '';

    /**
     * 类型
     *
     * 默认高德
     *
     * amap 高德
     * tencent 腾讯
     * baidu 高德
     *
     * @var string
     */
    public $type = 'amap';

    /**
     * @return string
     * @throws \Exception
     */
    public function run()
    {
        $value = $this->hasModel() ? Html::getAttributeValue($this->model, $this->attribute) : $this->value;
        $name = $this->hasModel() ? Html::getInputName($this->model, $this->attribute) : $this->name;

        if ($value && !is_array($value)) {
            $value = json_decode($value, true);
            empty($value) && $value = unserialize($value);
            empty($value) && $value = [];
        }

        // 显示地址
        $address = empty($value) ? '' : implode(',', [$value['lng'] ?? '', $value['lat'] ?? '']);

        $defaultValue = [
            'lng' => $value['lng'] ?? '116.456270',
            'lat' => $value['lat'] ?? '39.919990',
        ];

        // 注册js
        $this->registerViewJs();

        return $this->render('index', [
            'name' => $name,
            'value' => $defaultValue,
            'type' => $this->type,
            'secret_key' => $this->secret_key,
            'address' => $address,
            'defaultSearchAddress' => $this->defaultSearchAddress,
            'boxId' => StringHelper::uuid('uniqid')
        ]);
    }

    public function registerViewJs()
    {
        $view = $this->view;
        switch ($this->type) {
            case 'baidu' :
                empty($this->secret_key) && $this->secret_key = Yii::$app->debris->config('map_baidu_ak');
                $view->registerJsFile('http://api.map.baidu.com/api?v=2.0&ak=' . $this->secret_key);
                break;
            case 'amap' :
                empty($this->secret_key) && $this->secret_key = Yii::$app->debris->config('map_amap_key');
                $view->registerJsFile('http://webapi.amap.com/maps?v=1.4.11&plugin=AMap.ToolBar,AMap.Autocomplete,AMap.PlaceSearch,AMap.Geocoder&key=' . $this->secret_key);
                $view->registerJsFile('http://webapi.amap.com/ui/1.0/main.js?v=1.0.11');
                break;
            case 'tencent' :
                empty($this->secret_key) && $this->secret_key = Yii::$app->debris->config('map_tencent_key');
                $view->registerJsFile('http://map.qq.com/api/js?v=2.exp&libraries=place&key=' . $this->secret_key);
                break;
        }

        $view->registerCss(<<<Css
    #container {
        position: absolute;
        left: 0;
        top: 0;
        right: 0;
        bottom: 0;
    }

    .search {
        position: absolute;
        width: 400px;
        top: 0;
        left: 50%;
        padding: 5px;
        margin-left: -200px;
    }
Css
        );
    }
}
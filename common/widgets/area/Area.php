<?php

namespace common\widgets\area;

use Yii;
use yii\base\Widget;
use common\helpers\StringHelper;

/**
 * Class Area
 * @package common\widgets\area
 * @author jianyan74 <751393839@qq.com>
 */
class Area extends Widget
{
    /**
     * 省字段名
     *
     * @var
     */
    public $provincesName = 'province_ids';

    /**
     * 市字段名
     *
     * @var
     */
    public $cityName = 'city_ids';

    /**
     * 区字段名
     *
     * @var
     */
    public $areaName = 'area_ids';

    /**
     * 不可选市数组
     *
     * @var
     */
    public $notChooseProvinceIds = [];

    /**
     * 不可选区数组
     *
     * @var
     */
    public $notChooseCityIds = [];

    /**
     * 不可选省数组
     *
     * @var
     */
    public $notChooseAreaIds = [];

    /**
     * 级别
     *
     * @var int
     */
    public $level = 3;

    /**
     * 模型
     *
     * @var array
     */
    public $model;

    /**
     * 表单
     * @var
     */
    public $form;

    public function init()
    {
        parent::init();

        $this->level = (int)$this->level;
        $this->level < 1 && $this->level = 1;
        $this->level > 3 && $this->level = 3;
    }

    /**
     * @return string
     */
    public function run()
    {
        $provincesName = $this->provincesName;
        $cityName = $this->cityName;
        $areaName = $this->areaName;

        $provinceIds = StringHelper::parseAttr($this->model->$provincesName);
        $cityIds = $this->level >= 2 ? StringHelper::parseAttr($this->model->$cityName) : [];
        $areaIds = $this->level == 3 ? StringHelper::parseAttr($this->model->$areaName) : [];

        // 获取选中数据
        $provinceIds = array_merge(array_diff($this->notChooseProvinceIds, $provinceIds));
        $cityIds = array_merge(array_diff($this->notChooseCityIds, $cityIds));
        $areaIds = array_merge(array_diff($this->notChooseAreaIds, $areaIds));

        $addressList = Yii::$app->services->provinces->getAreaTree($provinceIds, $cityIds, $areaIds);

        return $this->render('area', [
            'form' => $this->form,
            'model' => $this->model,
            'provincesName' => $provincesName,
            'cityName' => $cityName,
            'areaName' => $areaName,
            'addressList' => $addressList,
            'level' => $this->level,
        ]);
    }
}

?>
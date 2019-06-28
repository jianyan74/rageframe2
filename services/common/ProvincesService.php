<?php
namespace services\common;

use Yii;
use common\enums\CacheKeyEnum;
use common\models\common\Provinces;
use common\components\Service;
use common\helpers\ArrayHelper;

/**
 * Class ProvincesService
 * @package services\common
 * @author jianyan74 <751393839@qq.com>
 */
class ProvincesService extends Service
{
    /**
     * @return array|mixed|\yii\db\ActiveRecord[]
     */
    public function all()
    {
        // 获取缓存
        if (!($data = Yii::$app->cache->get(CacheKeyEnum::COMMON_PROVINCES))) {
            $data = Provinces::find()
                ->select(['id', 'title', 'pid', 'level'])
                ->where(['<=', 'level', 4])
                ->asArray()
                ->all();

            Yii::$app->cache->set(CacheKeyEnum::COMMON_PROVINCES, $data, 60 * 60 * 24 * 30);
        }

        return $data;
    }


    /**
     * 获取省市区禁用状态
     *
     * @param array $provinceIds
     * @param array $cityIds
     * @param array $areaIds
     * @return mixed
     */
    public function getAreaTree(array $provinceIds, array $cityIds, array $areaIds)
    {
        $address = $this->all();

        $allIds = [];
        foreach ($address as &$item) {
            $allIds[$item['pid']][] = $item['id'];
        }

        // 计算选中状态
        foreach ($address as &$item) {
            $item['is_disabled'] = true;
            $data = $allIds[$item['id']] ?? [];

            if ($item['level'] == 1) {
                foreach ($data as $datum) {
                    !in_array($datum, $cityIds) && $item['is_disabled'] = false;
                    $areas = $allIds[$datum] ?? [];

                    foreach ($areas as $area) {
                        !in_array($area, $areaIds) && $item['is_disabled'] = false;
                    }

                    unset($areas);
                }
            }

            if ($item['level'] == 2) {
                foreach ($data as $datum) {
                    !in_array($datum, $areaIds) && $item['is_disabled'] = false;
                }
            }

            if ($item['level'] == 3 && !in_array($item['id'], $areaIds)) {
                $item['is_disabled'] = false;
            }

            unset($data);
        }

        // 递归重组省市区
        $address = ArrayHelper::itemsMerge($address, 0);
        // 大区
        $regionalAll = $this->regionalAll();
        $regroupAddress = [];

        foreach ($address as $value) {
            foreach ($regionalAll as $key => $data) {
                foreach ($data as $datum) {
                    $datum == $value['title'] && $regroupAddress[$key][] = $value;
                }
            }
        }

        unset($address, $regionalAll, $allIds);
        return $regroupAddress;
    }

    /**
     * 获取大区
     *
     * @return array
     */
    public function regionalAll()
    {
        $region = [
            '华东' => [
                '江苏省',
                '上海市',
                '浙江省',
                '安徽省',
                '江西省',
            ],
            '华北' => [
                '天津市',
                '河北省',
                '山西省',
                '内蒙古自治区',
                '山东省',
                '北京市',
            ],
            '华南' => [
                '广东省',
                '广西壮族自治区',
                '海南省',
                '福建省',
            ],
            '华中' => [
                '湖南省',
                '河南省',
                '湖北省',
            ],
            '东北' => [
                '辽宁省',
                '吉林省',
                '黑龙江省',
            ],
            '西北' => [
                '陕西省',
                '陕西省',
                '青海省',
                '宁夏回族自治区',
                '新疆维吾尔自治区',
            ],
            '西南' => [
                '重庆市',
                '四川省',
                '贵州省',
                '云南省',
                '西藏自治区',
            ],
            '港澳台' => [
                '香港特别行政区',
                '澳门特别行政区',
                '台湾',
            ],
        ];

        return $region;
    }

    /**
     * @param int $pid
     * @return int|string
     */
    public function getCountByPid($pid = 0)
    {
        return Provinces::find()
            ->select(['id'])
            ->where(['pid' => $pid])
            ->count();
    }

    /**
     * @param $ids
     * @return array|\yii\db\ActiveRecord[]
     *
     */
    public function getAllByIds($ids)
    {
        return Provinces::find()
            ->select(['id', 'title', 'pid', 'level'])
            ->where(['in', 'id', $ids])
            ->asArray()
            ->all();
    }

    /**
     * 根据父级ID返回信息
     *
     * @param int $pid
     * @return array
     */
    public function getCityList($pid = 0, $level = '')
    {
        if ($pid === '') {
            return [];
        }

        $model = Provinces::find()
            ->where(['pid' => $pid])
            ->select(['id', 'title', 'pid'])
            ->andFilterWhere(['level' => $level])
            ->cache(600)
            ->asArray()
            ->all();

        return ArrayHelper::map($model, 'id', 'title');
    }

    /**
     * 根据id获取区域名称
     *
     * @param $id
     * @return mixed
     */
    public function getName($id)
    {
        if ($provinces = Provinces::findOne($id)) {
            return $provinces['title'];
        }

        return false;
    }

    /**
     * 根据id数组获取区域名称
     *
     * @param $id
     * @return mixed
     */
    public function getCityListName(array $ids)
    {
        if ($provinces = Provinces::find()->where(['in', 'id', $ids])->all()) {
            $address = '';

            foreach ($provinces as $province) {
                $address .= $province['title'] . ' ';
            }

            return $address;
        }

        return false;
    }
}
<?php
namespace common\helpers;

use Yii;
use common\models\common\Provinces;
use linslin\yii2\curl\Curl;

/**
 * 获取最新的省市区
 *
 * 注意：请先备份现有的 rf_common_provinces表
 * 可以的话请设置自定义ID为1
 *
 * Class ProvincesApiHelper
 * @package common\helpers
 */
class ProvincesApiHelper
{
    /**
     * 国家统计局Url
     *
     * @var string
     */
    protected static $indexUrl = "http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2017/index.html";

    /**
     * @var string
     */
    protected static $indexUrlPrefix = "http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2017/";

    /**
     * 匹配规则
     *
     * @var string
     */
    protected static $matchRule = '/<a href=\'\d{2}\/(.{1,30}).html\'>(.{1,30})<\/a><\/td><\/tr>/';

    /**
     * 执行程序
     *
     * @throws \yii\db\Exception
     */
    public static function run()
    {
        $db = Yii::$app->db;
        $command = $db->createCommand();
        $curl = new Curl();

        // 超时
        ini_set('max_execution_time', '80000');
        header("Content-type: text/html; charset=gb2312");

         $index = $curl->get(self::$indexUrl);
         preg_match_all('/<a href=\'(\d{2,4}).html\'>(.{3,20})<br\/><\/a>/', $index, $province);
        error_reporting(0);

        // 循环省份
        $provincesArr = $citysArr = $areasArr = [];
        for ($i = 0,$e = count($province[1]); $i < $e; $i++)
        {
            $provinceId = (string) $province[1][$i] . "0000";
            $encode = mb_detect_encoding($province[2][$i], array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
            $provinceTitle = mb_convert_encoding($province[2][$i], 'UTF-8', $encode);
            $provincesArr[] = [$provinceId, $provinceTitle, 0, 1, 'tr_0'];

            // 获取市
            $provinceUrl = $curl->get(self::$indexUrlPrefix . $province[1][$i] . '.html');
            preg_match_all(self::$matchRule, $provinceUrl, $city);
            for ($a = 0,$b = count($city[1]); $a < $b; $a++)
            {
                $cityId = (string) $city[1][$a] . "00";
                $encode = mb_detect_encoding($city[2][$a], array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
                $cityTitle = mb_convert_encoding($city[2][$a], 'UTF-8', $encode);
                $citysArr[] = [$cityId, $cityTitle, $provinceId, 2, 'tr_0 tr_'. $provinceId];

                // 获取区
                $areaUrl = $curl->get(self::$indexUrlPrefix . $province[1][$i].'/'.$city[1][$a].'.html');
                preg_match_all(self::$matchRule, $areaUrl, $area);
                for ($c = 0,$d = count($area[1]); $c < $d; $c++)
                {
                    $areaId = $area[1][$c];
                    $encode = mb_detect_encoding($area[2][$c], array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
                    $areaTitle = mb_convert_encoding($area[2][$c], 'UTF-8', $encode);
                    $areasArr[] = [$areaId, $areaTitle, $cityId, 3, 'tr_0 tr_'. $provinceId . ' tr_' . $cityId];

                    // 获取街道获县镇
//                    $aru = substr($city[1][$a], 2, 2);
//                    $index = file_get_contents(self::$indexUrlPrefix.$province[1][$i].'/'.$aru.'/'.$area[1][$c].'.html');
//                    preg_match_all(self::$matchRule, $index, $matc);
//
//                    //部分省市的html和大部分的不一样，重写规则
//                    if (!$matc[0]) preg_match_all('/<td>(.{1,30})<\/td><td>\d{1,10}<\/td><td>(.{1,30})<\/td><\/tr>/', $index, $matc);
//
//                    $sql = 'REPLACE INTO position (province_id,province_name,city_id,city_name,county_id,county_name,town_id,town_name) VALUES ';
//                    for ($v = 0,$n = count($matc[1]); $v < $n; $v++)
//                    {
//                        $jil = iconv("utf-8", "gbk//ignore", $province[2][$i]);
//                        $sql .= "({$province[1][$i]},'{$jil}',{$city[1][$a]},'{$city[2][$a]}',{$area[1][$c]},'{$area[2][$c]}',{$matc[1][$v]},'{$matc[2][$v]}'),";
//                    }
                }
            }
        }

        $command->batchInsert(Provinces::tableName(), ['id', 'title', 'pid', 'level', 'position'], $provincesArr)->execute();
        $command->batchInsert(Provinces::tableName(), ['id', 'title', 'pid', 'level', 'position'], $citysArr)->execute();
        $command->batchInsert(Provinces::tableName(), ['id', 'title', 'pid', 'level', 'position'], $areasArr)->execute();
    }
}
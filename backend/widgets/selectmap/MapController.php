<?php
namespace backend\widgets\selectmap;

use Yii;
use yii\web\Controller;

/**
 * Class MapController
 * @package backend\widgets\selectmap
 * @author jianyan74 <751393839@qq.com>
 */
class MapController extends Controller
{
    /**
     * 行为控制
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => Yii\filters\AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],// 登录
                    ],
                ],
            ],
        ];
    }

    /**
     * @param $type
     * @param $secret_key
     * @param string $lng
     * @param string $lat
     * @return string
     */
    public function actionMap($type, $lng = '', $lat = '', $zoom = 12, $boxId = 12, $defaultSearchAddress)
    {
        return $this->renderAjax('@backend/widgets/selectmap/views/' . $type, [
            'lng' => $lng,
            'lat' => $lat,
            'zoom' => $zoom,
            'boxId' => $boxId,
            'defaultSearchAddress' => $defaultSearchAddress,
        ]);
    }

    /**
     * 手动输入
     *
     * @param string $lng
     * @param string $lat
     * @param int $boxId
     * @return string
     */
    public function actionInput($lng = '', $lat = '', $boxId = 12)
    {
        return $this->renderAjax('@backend/widgets/selectmap/views/input', [
            'lng' => $lng,
            'lat' => $lat,
            'boxId' => $boxId,
        ]);
    }
}
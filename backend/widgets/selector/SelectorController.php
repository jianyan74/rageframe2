<?php
namespace backend\widgets\selector;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use common\helpers\ArrayHelper;
use common\models\wechat\Attachment;
use common\helpers\ResultDataHelper;

/**
 * 微信资源选择器
 *
 * Class SelectorController
 * @package backend\widgets\selector
 * @author jianyan74 <751393839@qq.com>
 */
class SelectorController extends Controller
{
    /**
     * 行为控制
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
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
     * 获取图片/视频/音频/图文
     *
     * @param bool $json 返回json格式
     * @return array|string
     */
    public function actionList($json = false)
    {
        $keyword = Yii::$app->request->get('keyword');
        $media_type = Yii::$app->request->get('media_type');
        $year = Yii::$app->request->get('year', '');
        $month = Yii::$app->request->get('month', '');

        if ($media_type == Attachment::TYPE_NEWS) {
            $models = Yii::$app->services->wechatAttachmentNews->getFirstListPage($year, $month, $keyword);
        } else {
            $models = Yii::$app->services->wechatAttachment->getListPage($media_type, $year, $month, $keyword);
        }

        if ($json == true) {
            return ResultDataHelper::json(200, '获取成功', $models);
        }

        return $this->renderAjax('@backend/widgets/selector/views/selector', [
            'models' => Json::encode($models),
            'media_type' => $media_type,
            'boxId' => Yii::$app->request->get('boxId'),
            'year' => ArrayHelper::numBetween(2014, date('Y')),
            'month' => ArrayHelper::numBetween(1, 12),
        ]);
    }
}
<?php
namespace addons\RfSignShoppingDay\backend\controllers;

use yii\data\Pagination;
use common\helpers\ExcelHelper;
use common\components\Curd;
use addons\RfSignShoppingDay\common\models\Record;

/**
 * Class RecordController
 * @package addons\RfSignShoppingDay\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class RecordController extends BaseController
{
    use Curd;

    /**
     * @var Record
     */
    public $modelClass = Record::class;

    /**
     * 首页
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $data = Record::find()
            ->where(['is_win' => 1])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()]);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $this->pageSize]);
        $models = $data->offset($pages->offset)
            ->orderBy('id desc')
            ->with('user')
            ->limit($pages->limit)
            ->all();

        return $this->render($this->action->id, [
            'models' => $models,
            'pages' => $pages
        ]);
    }

    /**
     * @return bool
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function actionExport()
    {
        $data = Record::find()->where(['is_win' => 1])
            ->orderBy('id desc')
            ->with('user')
            ->all();

        $header = [
            ['ID', 'id', 'text'],
            ['openid', 'user.openid', 'text'],
            ['昵称', 'user.nickname', 'text'],
            ['奖品名称', 'award_title', 'text'],
            ['奖品类型', 'award_cate_id', 'selectd', [1 => '积分', 2 => '卡卷']],
            ['创建时间', 'created_at', 'date', 'Y-m-d'],
        ];

        return ExcelHelper::exportData($data, $header);
    }
}
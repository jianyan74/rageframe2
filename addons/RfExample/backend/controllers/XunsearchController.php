<?php
namespace addons\RfExample\backend\controllers;

use yii;
use yii\data\Pagination;
use common\enums\StatusEnum;
use common\helpers\StringHelper;
use addons\RfExample\common\models\Xunsearch;

/**
 * Xunsearch
 *
 * Class XunsearchController
 * @package addons\RfExample\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class XunsearchController extends BaseController
{
    /**
     * 到时候正式请配置在main里面
     *
     * 注意需要composer.json里面加入 "hightman/xunsearch": "*@beta",
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        /** ------ xunsearch搜索引擎 ------ **/
        Yii::$app->set('xunsearch', [
            'class' => 'hightman\xunsearch\Connection', // 此行必须
            'iniDirectory' => '@common/config', // 搜索 ini 文件目录，默认：@vendor/hightman/xunsearch/app
            'charset' => 'utf-8', // 指定项目使用的默认编码，默认即时 utf-8，可不指定
        ]);

        parent::init();
    }

    /**
     * 首页
     *
     * @return string
     * @throws \XSException
     */
    public function actionIndex()
    {
        // note: 相关的 AR 索引操作均非实时的，如需实时更新索引，请通过 Database::getIndex()->flushIndex() 刷新。
        // 关于查询日志有关的功能，也建议通过原生的 XSSearch 和 XSIndex 对象来操作。
        // 强制刷新
        Xunsearch::getDb()->getIndex()->flushIndex();

        $condition = [];
        if ($keyword = Yii::$app->request->get('keyword', null)) {
            // $condition = ['IN', 'title', [$keyword]];
            // $condition = ['title' => $keyword, 'content' => $keyword];

            $condition = $keyword;	// 字符串原样保持，可包含 subject:xxx 这种形式

            /**
            $condition = 'hello world';	// 字符串原样保持，可包含 subject:xxx 这种形式
            $condition = ['WILD', 'key1', 'key2' ... ];	// 通过空格将多个查询条件连接
            $condition = ['AND', 'key1', 'key2' ... ]; // 通过 AND 连接，转换为：key1 AND key2
            $condition = ['OR', 'key1', 'key2' ... ]; // 通过 OR 连接
            $condition = ['XOR', 'key1', 'key2' ... ]; // 通过  XOR 连接
            $condition = ['NOT', 'key']; // 排除匹配 key 的结果
            $condition = ['pid' => '123', 'subject' => 'hello']; // 转换为：pid:123 subject:hello
            $condition = ['pid' => ['123', '456']]; // 相当于 IN，转换为：pid:123 OR pid:456
            $condition = ['IN', 'pid', ['123', '456']]; // 转换结果同上
            $condition = ['NOT IN', 'pid', ['123', '456']]; // 转换为：NOT (pid:123 OR pid:456)
            $condition = ['BETWEEN', 'chrono', 14918161631, 15918161631]; // 相当于 XSSearch::addRange(...)
            $condition = ['WEIGHT', 'subject', 'hello', 0.5]; // 相当于额外调用 XSSearch::addWeight('subject', 'hello', 0.5
            */
        }

        $data = Xunsearch::find()->where($condition);
        $pages = new Pagination([
            'totalCount' => $data->count(),
            'pageSize' => $this->pageSize
        ]);

        $models = $data->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('index',[
            'models' => $models,
            'pages' => $pages,
            'keyword' => $keyword
        ]);
    }

    /**
     * 编辑/创建
     *
     * @return string|yii\console\Response|yii\web\Response
     */
    public function actionEdit()
    {
        $request = Yii::$app->request;
        $id = $request->get('id', null);
        $model = $this->findModel($id);
        if ($model->load($request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('edit',[
            'model' => $model
        ]);
    }

    /**
     * 删除
     *
     * @param $id
     * @return mixed
     * @throws \Throwable
     * @throws yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        if ($this->findModel($id)->delete()) {
            return $this->message("删除成功",$this->redirect(['index']));
        }

        return $this->message("删除失败",$this->redirect(['index']),'error');
    }

    /**
     * 返回模型
     *
     * @param $id
     * @return Xunsearch|null
     * @throws \Exception
     */
    protected function findModel($id)
    {
        if (empty($id) || empty(($model = Xunsearch::findOne($id)))) {
            $model = new Xunsearch();
            $model->id = StringHelper::uuid('uniqid');
            $model->status = StatusEnum::ENABLED;
            return $model;
        }

        return $model;
    }
}
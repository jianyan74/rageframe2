<?php

namespace addons\RfDevTool\backend\controllers;

use Yii;
use common\helpers\FileHelper;
use common\helpers\ArrayHelper;
use addons\RfDevTool\common\models\MigrateForm;
use jianyan\migration\components\MigrateCreate;

/**
 * Class MigrateController
 * @package addons\RfDevTool\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class MigrateController extends BaseController
{
    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function actionIndex()
    {
        $model = new MigrateForm();
        // 表列表
        $tableList = array_map('array_change_key_case', Yii::$app->db->createCommand('SHOW TABLE STATUS')->queryAll());

        // 插件列表
        $addonList = Yii::$app->services->addons->getList();

        if ($model->load(Yii::$app->request->post())) {
            $path = Yii::getAlias('@addons')  . '/' . $model->addon . '/console/migrations/';
            FileHelper::mkdirs($path);

            /** @var MigrateCreate $migrate */
            $migrate = Yii::createObject([
                'class' => MigrateCreate::class,
                'migrationPath' => $path
            ]);

            foreach ($model->tables as $table) {
                $migrate->create($table);
            }

           return $this->message('数据迁移创建成功', $this->redirect(['index']));
        }

        return $this->render($this->action->id, [
            'tableList' => ArrayHelper::map($tableList, 'name', 'name'),
            'addonList' => ArrayHelper::map($addonList, 'name', 'title'),
            'model' => $model
        ]);
    }
}
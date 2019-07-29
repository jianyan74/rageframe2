<?php

namespace backend\modules\common\controllers;

use Yii;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use common\components\Curd;
use common\models\base\SearchModel;
use common\helpers\FileHelper;
use common\helpers\AddonHelper;
use common\models\common\Addons;
use common\helpers\ExecuteHelper;
use common\enums\AuthEnum;
use common\helpers\ArrayHelper;
use backend\modules\common\forms\AddonsForm;
use backend\controllers\BaseController;

/**
 * Class AddonsController
 * @package backend\modules\common\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class AddonsController extends BaseController
{
    use Curd;

    /**
     * @var Addons
     */
    public $modelClass = Addons::class;

    /**
     * 首页
     *
     * @return mixed|string
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => Addons::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['title', 'name'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'addonsGroup' => Yii::$app->params['addonsGroup']
        ]);
    }

    /**
     * 卸载
     *
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionUnInstall()
    {
        $name = Yii::$app->request->get('name');

        // 删除数据库
        if ($model = Yii::$app->services->addons->findByName($name)) {
            $model->delete();
        }

        if ($class = Yii::$app->services->addons->getConfigClass($name)) {
            // 进行卸载数据库
            $uninstallClass = AddonHelper::getAddonRoot($name) . (new $class)->uninstall;
            ExecuteHelper::map($uninstallClass, 'run', $model);
        }

        return $this->message('卸载成功', $this->redirect(['index']));
    }

    /**
     * 安装
     *
     * @return mixed|string
     * @throws \Throwable
     * @throws \yii\db\Exception
     */
    public function actionLocal()
    {
        return $this->render($this->action->id, [
            'list' => Yii::$app->services->addons->getLocalList()
        ]);
    }

    /**
     * 安装
     *
     * @return mixed|string
     * @throws \Throwable
     * @throws \yii\db\Exception
     */
    public function actionInstall($data = true)
    {
        $name = Yii::$app->request->get('name');

        if (!($class = Yii::$app->services->addons->getConfigClass($name))) {
            return $this->message('实例化失败,插件不存在或检查插件名称', $this->redirect(['index']), 'error');
        }

        // 开启事物
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $config = new $class;
            $rootPath = AddonHelper::getAddonRootPath($name);

            $allAuthItem = [];
            $allMenu = [];
            $allCover = [];

            foreach ($config->appsConfig as $appId => $item) {
                $file = $rootPath . $item;

                if (!in_array($appId, array_keys(AuthEnum::$typeExplain))) {
                    throw new NotFoundHttpException('找不到应用');
                }

                if (!file_exists($file)) {
                    throw new NotFoundHttpException("找不到 $appId 应用文件");
                }

                $appConfig = require $file;

                if (isset($appConfig['authItem']) && !empty($appConfig['authItem'])) {
                    $allAuthItem[$appId] = $appConfig['authItem'];
                }

                if (isset($appConfig['menu']) && !empty($appConfig['menu'])) {
                    $allMenu[$appId] = $appConfig['menu'];
                }

                if (isset($appConfig['cover']) && !empty($appConfig['cover'])) {
                    $allCover[$appId] = $appConfig['cover'];
                }
            }

            Yii::$app->services->addonsBinding->create($allMenu, $allCover, $name);
            Yii::$app->services->authItem->createOnAddons($allAuthItem, $allMenu, $name);

            // 更新信息
            $model = Yii::$app->services->addons->update($name, $config);

            // 进行安装数据库
            if ($data == true) {
                $installClass = AddonHelper::getAddonRoot($name) . $config->install;
                ExecuteHelper::map($installClass, 'run', $model);
            }

            $transaction->commit();

            return $this->message('安装/更新成功', $this->redirect(['index']));
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->message($e->getMessage(), $this->redirect(['index']), 'error');
        }
    }

    /**
     * 升级数据库
     *
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionUpgrade()
    {
        $name = Yii::$app->request->get('name');

        if (
            !($class = Yii::$app->services->addons->getConfigClass($name)) ||
            !($model = Yii::$app->services->addons->findByName($name))
        ) {
            return $this->message('实例化失败,插件不存在或检查插件名称', $this->redirect(['index']), 'error');
        }

        // 更新数据库
        $upgradeClass = AddonHelper::getAddonRoot($name) . (new $class)->upgrade;
        if (!class_exists($upgradeClass)) {
            throw new NotFoundHttpException($upgradeClass . '未找到');
        }

        $upgradeModel = new $upgradeClass;
        if (!method_exists($upgradeModel, 'run')) {
            throw new NotFoundHttpException($upgradeClass . '/run方法未找到');
        }

        if (!isset($upgradeModel->versions)) {
            throw new NotFoundHttpException($upgradeClass . '下 versions 属性未找到');
        }

        $versions = $upgradeModel->versions;
        $count = count($versions);
        for ($i = 0; $i < $count; $i++) {
            // 验证版本号和更新
            if ($model->version == $versions[$i] && isset($versions[$i + 1])) {
                // 开启事物
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    $model->version = $versions[$i + 1];
                    $upgradeModel->run($model);
                    $model->save();
                    $transaction->commit();
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    return $this->message($e->getMessage(), $this->redirect(['index']), 'error');
                }
            }
        }

        return $this->message('升级数据库成功', $this->redirect(['index']));
    }

    /**
     * 创建模块
     *
     * @return mixed|string
     */
    public function actionCreate()
    {
        $model = new AddonsForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $data = Yii::$app->request->post();

            if (!is_writable(Yii::getAlias('@addons'))) {
                return $this->message('您没有创建目录写入权限，无法使用此功能', $this->redirect(['create']), 'error');
            }

            $addonDir = Yii::getAlias('@addons') . '/' . trim($model->name) . '/';

            if (is_dir($addonDir)) {
                return $this->message('插件已经存在，请删除后在试', $this->redirect(['create']), 'error');
            }

            // 创建目录结构
            $files = [];
            $files[] = $addonDir;
            $files[] = "{$addonDir}AddonConfig.php";

            $wechatMessage = Json::encode([]);
            if ($model->wechat_message) {
                $files[] = "{$addonDir}AddonMessage.php";
                $wechatMessage = Json::encode($model->wechat_message);
            }

            $app = [
                AuthEnum::TYPE_BACKEND,
                AuthEnum::TYPE_FRONTEND,
                AuthEnum::TYPE_WECHAT,
                AuthEnum::TYPE_OAUTH2,
                AuthEnum::TYPE_API
            ];

            $files[] = "{$addonDir}console/";
            $files[] = "{$addonDir}console/controllers/";
            $files[] = "{$addonDir}console/migrations/";
            $files[] = "{$addonDir}common/";
            $files[] = "{$addonDir}common/config/";
            $files[] = "{$addonDir}common/components/";
            $files[] = "{$addonDir}common/components/Bootstrap.php";
            $files[] = "{$addonDir}common/models/";
            $files[] = "{$addonDir}common/models/DefaultModel.php";
            // 生成目录和空文件
            foreach ($app as $item) {
                $files[] = "{$addonDir}common/config/{$item}.php";
                $files[] = "{$addonDir}{$item}/";
                $files[] = "{$addonDir}{$item}/controllers/";

                // api特殊目录
                if ($item != AuthEnum::TYPE_API) {

                    $files[] = "{$addonDir}{$item}/controllers/DefaultController.php";
                    $files[] = "{$addonDir}{$item}/views/";
                    $files[] = "{$addonDir}{$item}/views/layouts/";
                    $files[] = "{$addonDir}{$item}/views/layouts/main.php";
                    $files[] = "{$addonDir}{$item}/views/default/";
                    $files[] = "{$addonDir}{$item}/views/default/index.php";
                    $files[] = "{$addonDir}{$item}/assets/";
                    $files[] = "{$addonDir}{$item}/assets/AppAsset.php";
                    $files[] = "{$addonDir}{$item}/resources/";
                } else {
                    $files[] = "{$addonDir}{$item}/controllers/v1/";
                    $files[] = "{$addonDir}{$item}/controllers/v1/DefaultController.php";
                    $files[] = "{$addonDir}{$item}/controllers/v2/";
                    $files[] = "{$addonDir}{$item}/controllers/v2/DefaultController.php";
                }
            }

            // 参数设置支持
            $files[] = "{$addonDir}common/models/SettingForm.php";
            $files[] = "{$addonDir}backend/controllers/SettingController.php";
            $files[] = "{$addonDir}backend/views/setting/";

            $model['install'] && $files[] = "{$addonDir}{$model['install']}.php";
            $model['uninstall'] && $files[] = "{$addonDir}{$model['uninstall']}.php";
            $model['upgrade'] && $files[] = "{$addonDir}{$model['upgrade']}.php";
            FileHelper::createDirOrFiles($files);

            // 写入文件
            foreach ($app as $item) {
                // 配置文件
                file_put_contents("{$addonDir}common/config/{$item}.php",
                    $this->renderPartial('template/config/app', ['bindings' => $data['bindings'] ?? [], 'appID' => $item]));

                if ($item === AuthEnum::TYPE_API) {
                    file_put_contents("{$addonDir}api/controllers/v1/DefaultController.php",
                        $this->renderPartial('template/controllers/ApiDefaultController',
                            ['appID' => $item, 'model' => $model, 'versions' => 'v1']));
                    file_put_contents("{$addonDir}api/controllers/v2/DefaultController.php",
                        $this->renderPartial('template/controllers/ApiDefaultController',
                            ['appID' => $item, 'model' => $model, 'versions' => 'v2']));

                    continue;
                }

                // 控制器
                file_put_contents("{$addonDir}{$item}/controllers/BaseController.php",
                    $this->renderPartial('template/controllers/BaseController', ['model' => $model, 'appID' => $item]));
                // 控制器
                file_put_contents("{$addonDir}{$item}/controllers/DefaultController.php",
                    $this->renderPartial('template/controllers/DefaultController',
                        ['model' => $model, 'appID' => $item]));
                // 资源目录
                file_put_contents("{$addonDir}{$item}/resources/.gitkeep", '*');
                // 写入默认视图
                file_put_contents("{$addonDir}{$item}/views/default/index.php",
                    $this->renderPartial('template/view/index', ['model' => $model, 'appID' => $item]));
                // 写入视图自动载入
                file_put_contents("{$addonDir}{$item}/views/layouts/main.php",
                    $this->renderPartial('template/view/main', ['model' => $model, 'appID' => $item]));
                // 写入前台/后台/微信资源
                file_put_contents("{$addonDir}{$item}/assets/AppAsset.php",
                    $this->renderPartial('template/AppAsset', ['model' => $model, 'appID' => $item]));
            }

            // 控制台控制器
            file_put_contents("{$addonDir}console/controllers/.gitkeep", '*');

            // 控制台数据迁移
            file_put_contents("{$addonDir}console/migrations/.gitkeep", '*');

            // 写入引导
            file_put_contents("{$addonDir}common/components/Bootstrap.php",
                $this->renderPartial('template/Bootstrap', ['model' => $model]));

            // 写入默认model
            file_put_contents("{$addonDir}common/models/DefaultModel.php",
                $this->renderPartial('template/models/DefaultModel', ['model' => $model, 'appID' => 'backend']));

            // 参数设置支持
            file_put_contents("{$addonDir}backend/controllers/SettingController.php",
                $this->renderPartial('template/controllers/SettingController',
                    ['model' => $model, 'appID' => 'backend']));
            file_put_contents("{$addonDir}common/models/SettingForm.php",
                $this->renderPartial('template/models/SettingFormModel', ['model' => $model, 'appID' => 'common']));
            file_put_contents("{$addonDir}backend/views/setting/hook.php",
                $this->renderPartial('template/view/hook', ['model' => $model]));
            file_put_contents("{$addonDir}backend/views/setting/display.php",
                $this->renderPartial('template/view/display', ['model' => $model]));

            // 写入微信消息回复
            file_put_contents("{$addonDir}AddonMessage.php",
                $this->renderPartial('template/AddonMessage', ['model' => $model]));

            // 写入配置
            file_put_contents("{$addonDir}AddonConfig.php", $this->renderPartial('template/AddonConfig', [
                'model' => $model,
                'wechatMessage' => $wechatMessage,
                'menus' => isset($data['bindings']['menu']) ? $data['bindings']['menu'] : [],
                'covers' => isset($data['bindings']['cover']) ? $data['bindings']['cover'] : [],
            ]));

            // 写入文件
            $model['install'] && file_put_contents("{$addonDir}/{$model['install']}.php",
                $this->renderPartial('template/Install', ['model' => $model]));
            $model['uninstall'] && file_put_contents("{$addonDir}/{$model['uninstall']}.php",
                $this->renderPartial('template/UnInstall', ['model' => $model]));
            $model['upgrade'] && file_put_contents("{$addonDir}/{$model['upgrade']}.php",
                $this->renderPartial('template/Upgrade', ['model' => $model]));

            return $this->message('模块创建成功', $this->redirect(['local']));
        }

        return $this->render($this->action->id, [
            'model' => $model,
            'coverTypes' => ArrayHelper::filter(AuthEnum::$typeExplain, [AuthEnum::TYPE_FRONTEND, AuthEnum::TYPE_API, AuthEnum::TYPE_WECHAT, AuthEnum::TYPE_OAUTH2]),
            'addonsGroup' => Yii::$app->params['addonsGroup'],
        ]);
    }

    /**
     * 返回模型
     *
     * @param $id
     * @return \yii\db\ActiveRecord
     */
    protected function findModel($id)
    {
        /* @var $model \yii\db\ActiveRecord */
        if (empty($id) || empty(($model = $this->modelClass::findOne($id)))) {
            $model = new $this->modelClass;
            return $model->loadDefaultValues();
        }

        return $model;
    }
}
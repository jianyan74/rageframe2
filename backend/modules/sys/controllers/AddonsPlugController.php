<?php
namespace backend\modules\sys\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Response;
use yii\widgets\ActiveForm;
use common\helpers\ResultDataHelper;
use common\helpers\FileHelper;
use common\helpers\AddonHelper;
use common\helpers\StringHelper;
use common\models\sys\Addons;
use common\models\sys\AddonsBinding;
use backend\modules\sys\models\AddonsForm;

/**
 * 模块插件控制器
 *
 * Class AddonsPlugController
 * @package backend\modules\sys\controllers
 */
class AddonsPlugController extends SController
{
    /**
     * 卸载
     *
     * @return mixed|string
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionUninstall()
    {
        $request = Yii::$app->request;
        if ($request->isPost)
        {
            $addonName = $request->get('name');
            // 删除数据库
            if ($model = Addons::find()->where(['name' => $addonName])->one())
            {
                $model->delete();
            }

            // 验证模块信息
            $class = AddonHelper::getAddonConfig($addonName);
            if (!class_exists($class))
            {
                return $this->message('卸载成功', $this->redirect(['uninstall']));
            }

            // 引入卸载文件进行卸载
            $addons = new $class;
            if (StringHelper::strExists($addons->uninstall, '.php'))
            {
                $installFile = AddonHelper::getAddonRootPath($addonName) . $addons->uninstall;
                if ($addons->uninstall && file_exists($installFile))
                {
                    include_once $installFile;
                }
            }

            return $this->message('卸载成功', $this->redirect(['uninstall']));
        }

        $list = Addons::find()
            ->where(['like', 'title', $request->get('keyword', '')])
            ->orderBy('id desc')
            ->asArray()
            ->all();

        foreach ($list as &$item)
        {
            $item['cover'] = AddonHelper::getAddonIcon($item['name']);
            $item['upgradeConfigUrl'] = Url::to(['upgrade-config', 'name' => $item['name']]);
            $item['upgradeUrl'] = Url::to(['upgrade', 'name' => $item['name']]);
            $item['ajaxEditUrl'] = Url::to(['ajax-edit', 'id' => $item['id']]);
            $item['uninstallUrl'] = Url::to(['uninstall', 'name' => $item['name']]);
        }

        if ($request->isAjax)
        {
            return ResultDataHelper::json(200, '查询成功',[
                'list' => $list
            ]);
        }

        return $this->render($this->action->id, [
            'list' => $list
        ]);
    }

    /**
     * 安装
     *
     * @return mixed|string
     * @throws \Throwable
     * @throws \yii\db\Exception
     */
    public function actionInstall()
    {
        $request  = Yii::$app->request;
        if($request->isPost)
        {
            $addonName = $request->get('name');
            $class = AddonHelper::getAddonConfig($addonName);
            if(!class_exists($class))
            {
                throw new \Exception('实例化失败,插件不存在或检查插件名称');
            }

            // 开启事物
            $transaction = Yii::$app->db->beginTransaction();
            try
            {
                $addonsConfig = new $class;
                // 安装文件
                $installFile = AddonHelper::getAddonRootPath($addonName) . $addonsConfig->install;
                if (StringHelper::strExists($addonsConfig->install, '.php'))
                {
                    if ($addonsConfig->install && file_exists($installFile))
                    {
                        include_once $installFile;
                    }
                }

                // 添加入口
                isset($addonsConfig->menu) && AddonsBinding::careteEntry($addonsConfig->menu, 'menu', $addonName);
                isset($addonsConfig->cover) && AddonsBinding::careteEntry($addonsConfig->cover, 'cover', $addonName);
                Addons::edit(new Addons(), $addonsConfig);

                $transaction->commit();
                return $this->message('安装成功', $this->redirect(['uninstall']));
            }
            catch (\Exception $e)
            {
                $transaction->rollBack();
                return $this->message($e->getMessage(), $this->redirect(['install']), 'error');
            }
        }

        return $this->render($this->action->id, [
            'list' => Addons::getLocalList()
        ]);
    }

    /**
     * 更新排序/状态字段
     *
     * @param $id
     * @return array
     */
    public function actionAjaxUpdate($id)
    {
        if (!($model = Addons::findOne($id)))
        {
            return ResultDataHelper::json(404, '找不到数据');
        }

        $getData = Yii::$app->request->get();
        foreach (['id', 'sort', 'status'] as $item)
        {
            isset($getData[$item]) && $model->$item = $getData[$item];
        }

        if (!$model->save())
        {
            return ResultDataHelper::json(422, $this->analyErr($model->getFirstErrors()));
        }

        return ResultDataHelper::json(200, '修改成功');
    }

    /**
     * 编辑/新增
     *
     * @return array|mixed|string|Response
     */
    public function actionAjaxEdit()
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $model = $this->findModel($id);
        if ($model->load($request->post()))
        {
            if ($request->isAjax)
            {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            return $model->save()
                ? $this->redirect(['uninstall'])
                : $this->message($this->analyErr($model->getFirstErrors()), $this->redirect(['uninstall']), 'error');
        }

        return $this->renderAjax($this->action->id, [
            'model' => $model,
        ]);
    }

    /**
     * 更新数据库
     *
     * @return mixed
     */
    public function actionUpgrade()
    {
        $request = Yii::$app->request;
        $addonName = $request->get('name');
        $class = AddonHelper::getAddonConfig($addonName);
        if (!class_exists($class))
        {
            return $this->message('实例化失败,插件不存在或检查插件名称', $this->redirect(['uninstall']), 'error');
        }

        // 更新文件
        $addonsConfig = new $class;
        if (StringHelper::strExists($addonsConfig->upgrade, '.php'))
        {
            $upgradeFile = AddonHelper::getAddonRootPath($addonName) . $addonsConfig->upgrade;
            if ($addonsConfig->upgrade && file_exists($upgradeFile))
            {
                include_once $upgradeFile;
            }
        }

        return $this->message('更新数据成功', $this->redirect(['uninstall']));
    }

    /**
     * 更新配置
     *
     * @return mixed
     * @throws \Exception
     */
    public function actionUpgradeConfig()
    {
        $request = Yii::$app->request;
        $addonName = $request->get('name');
        $addon = Addons::findByName($addonName);
        $class = AddonHelper::getAddonConfig($addonName);
        if (!class_exists($class))
        {
            return $this->message('实例化失败,插件不存在或检查插件名称', $this->redirect(['uninstall']), 'error');
        }

        // 更新配置
        $addonsConfig = new $class;
        isset($addonsConfig->menu) && AddonsBinding::careteEntry($addonsConfig->menu, 'menu', $addonName);
        isset($addonsConfig->cover) && AddonsBinding::careteEntry($addonsConfig->cover, 'cover', $addonName);
        Addons::edit($addon, $addonsConfig);

        return $this->message('更新配置成功', $this->redirect(['uninstall']));
    }

    /**
     * 创建模块
     *
     * @return mixed|string
     */
    public function actionCreate()
    {
        $model = new AddonsForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            $data = Yii::$app->request->post();
            if (!is_writable(Yii::getAlias('@addons')))
            {
                return $this->message('您没有创建目录写入权限，无法使用此功能',$this->redirect(['create']),'error');
            }

            $addonDir = Yii::getAlias('@addons') . '/' . trim($model->name) . '/';
            // 创建目录结构
            $files = [];
            $files[] = $addonDir;
            $files[] = "{$addonDir}AddonConfig.php";

            $wechatMessage = json_encode([]);
            if ($model->wechat_message)
            {
                $files[] = "{$addonDir}AddonMessage.php";
                $wechatMessage =json_encode($model->wechat_message);
            }

            // 后台初始化视图
            $adminViewName = StringHelper::toUnderScore($model->name);
            $files[] = "{$addonDir}common/";
            $files[] = "{$addonDir}common/models/";
            $files[] = "{$addonDir}common/models/DefaultModel.php";
            $files[] = "{$addonDir}backend/";
            $files[] = "{$addonDir}backend/controllers/";
            $files[] = "{$addonDir}backend/controllers/DefaultController.php";
            $files[] = "{$addonDir}backend/views/";
            $files[] = "{$addonDir}backend/views/default/";
            $files[] = "{$addonDir}backend/views/default/index.php";
            $files[] = "{$addonDir}frontend/";
            $files[] = "{$addonDir}frontend/controllers/";
            $files[] = "{$addonDir}frontend/controllers/DefaultController.php";
            $files[] = "{$addonDir}frontend/views/";
            $files[] = "{$addonDir}frontend/views/default/";
            $files[] = "{$addonDir}frontend/views/default/index.php";
            $files[] = "{$addonDir}wechat/";
            $files[] = "{$addonDir}wechat/controllers/";
            $files[] = "{$addonDir}wechat/controllers/DefaultController.php";
            $files[] = "{$addonDir}wechat/views/";
            $files[] = "{$addonDir}wechat/views/default/";
            $files[] = "{$addonDir}wechat/views/default/index.php";
            $files[] = "{$addonDir}resources/";
            $files[] = "{$addonDir}resources/backend/";
            $files[] = "{$addonDir}resources/frontend/";
            $files[] = "{$addonDir}resources/wechat/";
            $files[] = "{$addonDir}backend/assets/";
            $files[] = "{$addonDir}backend/assets/Asset.php";
            $files[] = "{$addonDir}frontend/assets/";
            $files[] = "{$addonDir}frontend/assets/Asset.php";
            $files[] = "{$addonDir}wechat/assets/";
            $files[] = "{$addonDir}wechat/assets/Asset.php";

            // 小程序支持
            if($model->is_mini_program)
            {
                $files[] = "{$addonDir}api/";
                $files[] = "{$addonDir}api/controllers/";
                $files[] = "{$addonDir}api/controllers/DefaultController.php";
            }

            // 参数设置支持
            if($model->is_setting == true)
            {
                $files[] = "{$addonDir}common/models/SettingForm.php";
                $files[] = "{$addonDir}backend/controllers/SettingController.php";
                $files[] = "{$addonDir}backend/views/setting/";
            }

            $model['install'] && $files[] = "{$addonDir}{$model['install']}";
            $model['uninstall'] && $files[] = "{$addonDir}{$model['uninstall']}";
            $model['upgrade'] && $files[] = "{$addonDir}{$model['upgrade']}";
            FileHelper::createDirOrFiles($files);

            // 写入控制器
            file_put_contents("{$addonDir}backend/controllers/DefaultController.php", $this->renderPartial('template/DefaultController',['model' => $model, 'appID' => 'backend']));
            file_put_contents("{$addonDir}frontend/controllers/DefaultController.php", $this->renderPartial('template/DefaultController',['model' => $model, 'appID' => 'frontend']));
            file_put_contents("{$addonDir}wechat/controllers/DefaultController.php", $this->renderPartial('template/DefaultController',['model' => $model, 'appID' => 'wechat']));
            $model->is_mini_program == true && file_put_contents("{$addonDir}api/controllers/DefaultController.php", $this->renderPartial('template/ApiDefaultController',['model' => $model, 'appID' => 'api',]));

            // 写入默认model
            file_put_contents("{$addonDir}common/models/DefaultModel.php", $this->renderPartial('template/DefaultModel',['model' => $model, 'appID' => 'backend']));

            // 资源目录
            file_put_contents("{$addonDir}resources/backend/.gitkeep", '*');
            file_put_contents("{$addonDir}resources/frontend/.gitkeep", '*');
            file_put_contents("{$addonDir}resources/wechat/.gitkeep", '*');

            // 写入默认视图
            file_put_contents("{$addonDir}backend/views/default/index.php", $this->renderPartial('template/view/index',['model' => $model, 'appID' => 'backend']));
            file_put_contents("{$addonDir}frontend/views/default/index.php", $this->renderPartial('template/view/index',['model' => $model, 'appID' => 'frontend']));
            file_put_contents("{$addonDir}wechat/views/default/index.php", $this->renderPartial('template/view/index',['model' => $model, 'appID' => 'wechat']));

            // 写入前台/后台/微信资源
            file_put_contents("{$addonDir}backend/assets/Asset.php", $this->renderPartial('template/Asset',['model' => $model, 'appID' => 'backend']));
            file_put_contents("{$addonDir}frontend/assets/Asset.php", $this->renderPartial('template/Asset',['model' => $model, 'appID' => 'frontend']));
            file_put_contents("{$addonDir}wechat/assets/Asset.php", $this->renderPartial('template/Asset',['model' => $model, 'appID' => 'wechat']));

            // 参数设置支持
            if($model->is_setting == true)
            {
                // 写入设置
                file_put_contents("{$addonDir}backend/controllers/SettingController.php", $this->renderPartial('template/SettingController', ['model' => $model, 'appID' => 'backend']));
                file_put_contents("{$addonDir}common/models/SettingForm.php", $this->renderPartial('template/SettingFormModel', ['model' => $model, 'appID' => 'common']));
                file_put_contents("{$addonDir}backend/views/setting/hook.php", $this->renderPartial('template/view/hook', ['model' => $model]));
                file_put_contents("{$addonDir}backend/views/setting/display.php", $this->renderPartial('template/view/display', ['model' => $model]));
            }

            // 写入微信消息回复
            if($model->wechat_message == true)
            {
                file_put_contents("{$addonDir}AddonMessage.php", $this->renderPartial('template/AddonMessage',['model' => $model]));
            }

            // 写入配置
            file_put_contents("{$addonDir}AddonConfig.php", $this->renderPartial('template/AddonConfig',[
                'model' => $model,
                'wechatMessage' => $wechatMessage,
                'menus' => isset($data['bindings']['menu']) ? $data['bindings']['menu'] : [],
                'covers' => isset($data['bindings']['cover']) ? $data['bindings']['cover'] : [],
            ]));

            // 写入文件
            $model['install'] && file_put_contents("{$addonDir}/{$model['install']}", $this->renderPartial('template/install',['model' => $model]));
            $model['uninstall'] && file_put_contents("{$addonDir}/{$model['uninstall']}", $this->renderPartial('template/uninstall',['model' => $model]));
            $model['upgrade'] && file_put_contents("{$addonDir}/{$model['upgrade']}", $this->renderPartial('template/upgrade',['model' => $model]));

            return $this->message('模块创建成功', $this->redirect(['install']));
        }

        return $this->render($this->action->id, [
            'model' => $model,
            'addonsGroup' => Yii::$app->params['addonsGroup'],
        ]);
    }

    /**
     * @param $id
     * @return mixed
     */
    protected function findModel($id)
    {
        if (empty($id) || empty(($model = Addons::findOne($id))))
        {
            $model = new Addons;
            return $model->loadDefaultValues();
        }

        return $model;
    }
}
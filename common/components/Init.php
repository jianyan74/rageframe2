<?php

namespace common\components;

use Yii;
use yii\base\BootstrapInterface;
use yii\web\UnauthorizedHttpException;
use common\models\backend\Member;
use common\helpers\StringHelper;
use common\enums\AppEnum;

/**
 * Class InitConfig
 * @package common\components
 * @author jianyan74 <751393839@qq.com>
 */
class Init implements BootstrapInterface
{
    /**
     * 应用ID
     *
     * @var
     */
    protected $id;

    /**
     * @param \yii\base\Application $application
     * @throws UnauthorizedHttpException
     * @throws \Exception
     */
    public function bootstrap($application)
    {
        Yii::$app->params['uuid'] = StringHelper::uuid('uniqid');

        $this->id = $application->id;// 初始化变量
        // 商户信息
        if (in_array(Yii::$app->id, [AppEnum::CONSOLE, AppEnum::BACKEND])) {
            $this->afreshLoad('');
        } elseif (in_array(Yii::$app->id, [AppEnum::MERCHANT, AppEnum::MER_API])) {
            /** @var Member $identity */
            $identity = Yii::$app->user->identity;
            $this->afreshLoad($identity->merchant_id ?? '');
        } else {
            $merchant_id = Yii::$app->request->headers->get('merchant-id', '');
            if (empty($merchant_id)) {
                $merchant_id = Yii::$app->request->get('merchant_id', '');
            }

            $this->afreshLoad($merchant_id);
        }
    }

    /**
     * 重载配置
     *
     * @param $merchant_id
     * @throws UnauthorizedHttpException
     */
    public function afreshLoad($merchant_id)
    {
        $sys_ip_blacklist_open = false;

        try {
            Yii::$app->services->merchant->setId($merchant_id);
            // 获取 ip 配置
            $sys_ip_blacklist_open = Yii::$app->debris->backendConfig('sys_ip_blacklist_open');
            // 初始化模块
            Yii::$app->setModules($this->getModulesByAddons());
        } catch (\Exception $e) {

        }

        // ip黑名单拦截器
        $sys_ip_blacklist_open == true && $this->verifyIp();

        unset($config);
    }

    /**
     * @throws UnauthorizedHttpException
     */
    protected function verifyIp()
    {
        $userIP = Yii::$app->request->userIP;
        $ips = Yii::$app->services->ipBlacklist->findIps();
        if (in_array($userIP, $ips)) {
            throw new UnauthorizedHttpException('你的访问被禁止');
        }

        unset($userIP, $ips);
    }

    /**
     * 获取模块
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function getModulesByAddons()
    {
        $addons = Yii::$app->services->addons->findAllNames();

        $modules = [];
        $merchant = AppEnum::MERCHANT;
        foreach ($addons as $addon) {
            $name = $addon['name'];
            $app_id = $this->id;
            // 模块映射
            if ($this->id == AppEnum::BACKEND && $addon['is_merchant_route_map'] == true) {
                $app_id = $merchant;
            }

            $modules[StringHelper::toUnderScore($name)] = [
                'class' => 'common\components\BaseAddonModule',
                'name' => $name,
                'app_id' => $app_id,
            ];

            // 初始化服务
            if (!empty($addon['service'])) {
                // 动态注入服务
                Yii::$app->set(lcfirst($name) . 'Service', [
                    'class' => $addon['service'],
                ]);
            }
        }

        return $modules;
    }
}
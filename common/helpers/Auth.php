<?php

namespace common\helpers;

use Yii;
use common\enums\AuthEnum;

/**
 * Class Auth
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class Auth
{
    protected static $auth = [];

    /**
     * 校验权限是否拥有
     *
     * @param string $route
     * @param array $defaultAuth
     * @return bool
     */
    public static function verify(string $route, $defaultAuth = [])
    {
        if (Yii::$app->services->auth->isSuperAdmin()) {
            return true;
        }

        $auth = !empty($defaultAuth) ? $defaultAuth : self::getAuth();
        if (in_array($route, $auth) || in_array('/*', $auth)) {
            return true;
        }

        return self::multistageCheck($route, $auth);
    }

    /**
     * 过滤自己拥有的权限
     *
     * @param array $route
     * @return array|bool
     */
    public static function verifyBatch(array $route)
    {
        if (Yii::$app->services->auth->isSuperAdmin()) {
            return $route;
        }

        return ArrayHelper::filter(self::getAuth(), $route);
    }

    /**
     * 支持通配符 *
     *
     * 例如：
     * /goods/*
     * /goods/index/*
     *
     * @param $route
     * @param array $auth
     * @return bool
     */
    public static function multistageCheck($route, array $auth)
    {
        $key = '/';
        $routeArr = explode('/', $route);
        foreach ($routeArr as $value) {
            if (!empty($value)) {
                $key .= $value . '/';

                if (in_array($key . '*', $auth)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * 获取权限信息
     *
     * @return array
     */
    public static function getAuth()
    {
        if (self::$auth) {
            return self::$auth;
        }

        $role = Yii::$app->services->authRole->getRole();
        // 获取权限数组
        if (true === Yii::$app->params['inAddon']) {
            $name = Yii::$app->params['addonInfo']['name'] ?? Yii::$app->request->get('addon');
            $name = StringHelper::strUcwords($name);
            self::$auth = Yii::$app->services->authRole->getAuthByRole($role, AuthEnum::TYPE_CHILD_ADDONS, $name);
        } else {
            self::$auth = Yii::$app->services->authRole->getAuthByRole($role);
        }

        return self::$auth;
    }
}
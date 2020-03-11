<?php

namespace common\helpers;

use Yii;

/**
 * Class Auth
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class Auth
{
    protected static $auth = [];

    /**
     * 校验权限
     *
     * @param string $route
     * @param array $defaultAuth
     * @return bool
     * @throws \yii\web\UnauthorizedHttpException
     */
    public static function verify(string $route, $defaultAuth = [])
    {
        if (Yii::$app->services->auth->isSuperAdmin()) {
            return true;
        }

        $route = trim($route);
        $auth = !empty($defaultAuth) ? $defaultAuth : self::getAuth();

        if (
            in_array('/*', $auth) ||
            in_array('*', $auth) ||
            in_array($route, $auth) ||
            in_array(Url::to([$route]), $auth)
        ) {
            return true;
        }

        return self::multistageCheck($route, $auth);
    }

    /**
     * 过滤自己拥有的权限
     *
     * @param array $route
     * @return array
     * @throws \yii\web\UnauthorizedHttpException
     */
    public static function verifyBatch(array $route)
    {
        if (Yii::$app->services->auth->isSuperAdmin()) {
            return $route;
        }

        return array_intersect(self::getAuth(), $route);
    }

    /**
     * 支持通配符 *
     *
     * 例如：
     * /goods/*
     * /goods/index/*
     *
     * @param string $route 权限名称
     * @param array $auth 所有权限组
     * @param string $separator 分隔符
     * @return bool
     */
    public static function multistageCheck($route, array $auth, $separator = '/')
    {
        $key = $separator;
        $routeArr = explode($separator, $route);
        foreach ($routeArr as $value) {
            if (!empty($value)) {
                $key .= $value . $separator;

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
     * @throws \yii\web\UnauthorizedHttpException
     */
    public static function getAuth()
    {
        if (self::$auth) {
            return self::$auth;
        }

        $role = Yii::$app->services->rbacAuthRole->getRole();
        self::$auth = Yii::$app->services->rbacAuthItemChild->getAuthByRole($role, Yii::$app->id);

        return self::$auth;
    }
}
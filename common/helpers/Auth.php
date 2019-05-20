<?php
namespace common\helpers;

use Yii;
use common\models\sys\AuthItem;

/**
 * Class Auth
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class Auth
{
    protected static $sysAuth;

    protected static $addonAuth;

    /**
     * 校验权限是否拥有
     *
     * @param string $route
     * @return bool
     */
    public static function verify(string $route)
    {
        if (Yii::$app->services->sys->isAuperAdmin())
        {
            return true;
        }

        // 开始校验
        return in_array($route, self::getAuth());
    }

    /**
     * 过滤自己拥有的权限
     *
     * @param array $route
     * @return array|bool
     */
    public static function verifyBatch(array $route)
    {
        if (Yii::$app->services->sys->isAuperAdmin())
        {
            return $route;
        }

        // 开始获取权限信息校验
        return ArrayHelper::filter(self::getAuth(), $route);
    }

    public static function getAuth()
    {
        if (Yii::$app->params['inAddon'])
        {
            return self::getSysAuth();
        }

        return self::getAddonAuth();
    }

    /**
     * 获取系统权限
     *
     * @return array
     */
    public static function getSysAuth()
    {
        if (empty(self::$sysAuth))
        {
            $auth = Yii::$app->services->sys->auth->getAllAuthByRole();
            self::$sysAuth = array_column($auth, 'name');
        }

        return self::$sysAuth;
    }

    /**
     * 获取插件模块权限
     *
     * @return array
     */
    public static function getAddonAuth()
    {
        if (empty(self::$addonAuth))
        {
            $auth = Yii::$app->services->sys->addonAuth->getAllAuthByRole();
            self::$addonAuth = $auth;
        }

        return self::$addonAuth;
    }

    /**
     * 更新依赖
     *
     * @return bool
     */
    public static function updateCache()
    {
        if ($authItem = AuthItem::find()->orderBy('updated_at desc')->one())
        {
            return $authItem->save();
        }

        return true;
    }
}
<?php
namespace common\helpers;

use Yii;
use common\models\sys\AuthItem;

/**
 * Class AuthHelper
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class AuthHelper
{
    /**
     * @var string
     */
    public static $cachePrefix = 'sysAuth:';

    /**
     * 当前用户所有的系统权限
     *
     * @var array
     */
    protected static $auth = [];

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

    /**
     * 获取当前权限信息
     *
     * @return array
     */
    public static function getAuth()
    {
        if (empty(self::$auth))
        {
            $role = Yii::$app->services->sys->auth->getRole();
            // 获取缓存
            if (!($auth = Yii::$app->cache->get(self::$cachePrefix . $role['name'])))
            {
                $auth = Yii::$app->services->sys->auth->getUserAuth();
                // 数据库依赖缓存
                $dependency = new \yii\caching\DbDependency([
                    'sql' => AuthItem::find()
                        ->select('updated_at')
                        ->orderBy('updated_at desc')
                        ->createCommand()
                        ->getRawSql(),
                ]);

                Yii::$app->cache->set(self::$cachePrefix . $role['name'], $auth, 3600, $dependency);
            }

            self::$auth = array_column($auth, 'name');
        }

        return self::$auth;
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
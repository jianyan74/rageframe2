<?php
namespace common\helpers;

use Yii;
use common\models\sys\AddonsAuthItemChild;
use common\models\sys\AuthItem;

/**
 * Class AddonAuthHelper
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class AddonAuthHelper
{
    /**
     * @var string
     */
    public static $cachePrefix = 'sysAddonAuth:';

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
     * @return array
     */
    public static function getAuth()
    {
        if (empty(self::$auth))
        {
            $role = Yii::$app->services->sys->auth->getRole();
            $cacheKey = self::$cachePrefix . Yii::$app->request->get('addon') . ':' . $role['name'];
            // 获取缓存
            if (!($auth = Yii::$app->cache->get($cacheKey)))
            {
                $role = Yii::$app->services->sys->auth->getRole();
                $auth = AddonsAuthItemChild::find()
                    ->where(['addons_name' => StringHelper::strUcwords(Yii::$app->request->get('addon'))])
                    ->andWhere(['parent' => $role['name']])
                    ->asArray()
                    ->all();

                // 数据库依赖缓存
                $dependency = new \yii\caching\DbDependency([
                    'sql' => AuthItem::find()
                        ->select('updated_at')
                        ->orderBy('updated_at desc')
                        ->createCommand()
                        ->getRawSql(),
                ]);

                Yii::$app->cache->set($cacheKey, $auth, 7200, $dependency);
            }

            $childs = array_column($auth, 'child');
            foreach ($childs as $k => &$child)
            {
                $arrChild = explode(':', $child);
                if (count($arrChild) == 1)
                {
                    unset($childs[$k]);
                }
                else
                {
                    $childs[$k] = $arrChild[1];
                }

                unset($arrChild);
            }

            self::$auth = $childs;
        }

        return self::$auth;
    }

    /**
     * @return array
     */
    public static function getAllAuth()
    {
        // 获取缓存
        if (!($auth = Yii::$app->cache->get('sysAddonAllAuth:' . Yii::$app->user->id)))
        {
            $role = Yii::$app->services->sys->auth->getRole();
            $auth = AddonsAuthItemChild::find()
                ->andWhere(['parent' => $role['name']])
                ->asArray()
                ->all();

            // 数据库依赖缓存
            $dependency = new \yii\caching\DbDependency([
                'sql' => AuthItem::find()
                    ->select('updated_at')
                    ->orderBy('updated_at desc')
                    ->createCommand()
                    ->getRawSql(),
            ]);

            Yii::$app->cache->set('sysAddonAllAuth:' . Yii::$app->user->id, $auth, 7200, $dependency);
        }

        return array_column($auth, 'child');
    }
}
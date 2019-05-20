<?php
namespace services\sys;

use Yii;
use common\helpers\StringHelper;
use common\components\Service;
use common\models\sys\AuthItem;
use common\models\sys\AddonsAuthItemChild;

/**
 * Class AddonAuthService
 * @package services\sys
 * @author jianyan74 <751393839@qq.com>
 */
class AddonAuthService extends Service
{
    const CACHE_KEY = 'sys:addonAuth:';

    /**
     * 根据角色获取所有的权限
     *
     * @return array
     */
    public function getAllAuthByRole()
    {
        $role = Yii::$app->services->sys->auth->getRole();
        $cacheKey = self::CACHE_KEY . Yii::$app->request->get('addon') . ':' . $role['name'];
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

        return $childs;
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

    /**
     * 设置扩展模块权限前缀
     *
     * @param $name
     * @param $child
     * @return string
     */
    private function getAddonName($name, $child)
    {
        return $name . ':' . $child;
    }
}
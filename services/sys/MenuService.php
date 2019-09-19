<?php

namespace services\sys;

use Yii;
use yii\helpers\Json;
use common\helpers\ArrayHelper;
use common\components\Service;
use common\models\sys\Menu;
use common\enums\StatusEnum;
use common\helpers\Auth;

/**
 * Class MenuService
 * @package services\sys
 * @author jianyan74 <751393839@qq.com>
 */
class MenuService extends Service
{
    /**
     * 获取下拉
     *
     * @param string $id
     * @return array
     */
    public function getDropDownList($id = '')
    {
        $list = Menu::find()
            ->where(['>=', 'status', StatusEnum::DISABLED])
            ->andFilterWhere(['<>', 'id', $id])
            ->select(['id', 'title', 'pid', 'level'])
            ->orderBy('cate_id asc, sort asc')
            ->asArray()
            ->all();

        $models = ArrayHelper::itemsMerge($list);
        return ArrayHelper::map(ArrayHelper::itemsMergeDropDown($models), 'id', 'title');
    }

    /**
     * @return array
     */
    public function getList()
    {
        $data = Menu::find()->where(['status' => StatusEnum::ENABLED]);
        // 关闭开发模式
        if (empty(Yii::$app->debris->config('sys_dev'))) {
            $data = $data->andWhere(['dev' => StatusEnum::DISABLED]);
        }

        $models = $data->orderBy('cate_id asc, sort asc')
            ->with(['cate'])
            ->asArray()
            ->all();

        // 获取权限信息
        $auth = false;
        if (!Yii::$app->services->auth->isSuperAdmin()) {
            $role = Yii::$app->services->authRole->getRole();
            $auth = Yii::$app->services->authRole->getAuthByRole($role);
        }

        foreach ($models as $key => &$model) {
            if (!empty($model['url'])) {
                $params = Json::decode($model['params']);
                (empty($params) || !is_array($params)) && $params = [];
                $model['fullUrl'][] = $model['url'];

                foreach ($params as $param) {
                    if (!empty($param['key'])) {
                        $model['fullUrl'][$param['key']] = $param['value'];
                    }
                }
            } else {
                $model['fullUrl'] = '#';
            }

            // 移除无权限菜单
            if (false !== $auth && Auth::verify($model['url'], $auth) === false) {
                unset($models[$key]);
            }
        }

        return ArrayHelper::itemsMerge($models);
    }
}
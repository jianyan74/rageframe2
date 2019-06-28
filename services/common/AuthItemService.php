<?php
namespace services\common;

use Yii;
use yii\web\UnprocessableEntityHttpException;
use common\components\Service;
use common\enums\AuthEnum;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\models\common\AuthItem;
use common\models\common\AuthItemChild;

/**
 * Class AuthItemService
 * @package services\common
 * @author jianyan74 <751393839@qq.com>
 */
class AuthItemService extends Service
{
    /**
     * @param array $data
     * @throws UnprocessableEntityHttpException
     */
    public function create(array $data)
    {
        $model = new AuthItem();
        $model->attributes = $data;
        if (!$model->save()) {
            throw new UnprocessableEntityHttpException(Yii::$app->debris->analyErr($model->getFirstErrors()));
        }
    }

    /**
     * 卸载插件
     *
     * @param $name
     */
    public function uninstallAddonsByName($name)
    {
        AuthItem::deleteAll(['type_child' => AuthEnum::TYPE_CHILD_ADDONS, 'addons_name' => $name]);
        AuthItemChild::deleteAll(['type_child' => AuthEnum::TYPE_CHILD_ADDONS, 'addons_name' => $name]);
    }

    /**
     * @param array $ids
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getList($ids = [])
    {
        return AuthItem::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['in', 'id', $ids])
            ->select(['id', 'title', 'name', 'pid', 'level', 'type', 'type_child', 'addons_name', 'is_menu'])
            ->orderBy('sort asc, id asc')
            ->asArray()
            ->all();
    }

    /**
     * 获取下拉
     *
     * @param string $id
     * @return array
     */
    public function getEditDropDownList($id = '')
    {
        $list = AuthItem::find()
            ->where(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['type' => AuthEnum::TYPE_BACKEND, 'type_child' => AuthEnum::TYPE_CHILD_DEFAULT])
            ->andFilterWhere(['<>', 'id', $id])
            ->select(['id', 'title', 'pid', 'level'])
            ->orderBy('sort asc')
            ->asArray()
            ->all();

        $models = ArrayHelper::itemsMerge($list);
        $data = ArrayHelper::map(ArrayHelper::itemsMergeDropDown($models), 'id', 'title');
        return ArrayHelper::merge([0 => '顶级权限'], $data);
    }
}
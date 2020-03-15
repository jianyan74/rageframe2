<?php

namespace addons\Merchants\backend\controllers;

use Yii;
use common\traits\AuthRoleTrait;
use common\models\rbac\AuthRole;
use common\enums\AppEnum;

/**
 * Class RoleController
 * @package addons\Merchants\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class AuthRoleController extends BaseController
{
    use AuthRoleTrait;

    /**
     * @var AuthRole
     */
    public $modelClass = AuthRole::class;

    /**
     * 默认应用
     *
     * @var string
     */
    public $appId = AppEnum::MERCHANT;

    /**
     * 权限来源
     *
     * false:所有权限，true：当前角色
     *
     * @var bool
     */
    public $sourceAuthChild = false;

    /**
     * 渲染视图前缀
     *
     * @var string
     */
    public $viewPrefix = '@backend/modules/base/views/auth-role/';

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        $this->merchant_id = Yii::$app->request->get('merchant_id');
        $this->merchant_id && Yii::$app->services->merchant->setId($this->merchant_id);
    }
}
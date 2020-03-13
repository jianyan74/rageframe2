<?php

namespace addons\Merchants\backend\controllers;

use common\enums\AppEnum;
use common\models\rbac\AuthItem;
use common\traits\AuthItemTrait;

/**
 * Class AuthItemController
 * @package backend\modules\common\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class AuthItemController extends BaseController
{
    use AuthItemTrait;

    /**
     * @var AuthItem
     */
    public $modelClass = AuthItem::class;

    /**
     * 默认应用
     *
     * @var string
     */
    public $appId = AppEnum::MERCHANT;

    /**
     * 渲染视图前缀
     *
     * @var string
     */
    public $viewPrefix = '@backend/modules/base/views/auth-item/';
}
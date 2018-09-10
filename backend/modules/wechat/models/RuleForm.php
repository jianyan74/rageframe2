<?php
namespace backend\modules\wechat\models;

use common\models\wechat\Rule;

/**
 * Class RuleForm
 * @package backend\modules\sys\models
 */
class RuleForm extends Rule
{
    public $keyword;

    public function rules()
    {
        $rule = parent::rules();
        $rule[] = [['keyword'], 'required', 'message' => '关键字不能为空'];

        return $rule;
    }
}

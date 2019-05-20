<?php
namespace api\modules\v1\models;

use common\enums\StatusEnum;
use common\helpers\RegularHelper;
use common\models\api\AccessToken;
use common\models\member\Member;
use yii\base\Model;

/**
 * Class MobileLogin
 * @package api\modules\v1\models
 * @author jianyan74 <751393839@qq.com>
 */
class MobileLogin extends Model
{
    const VERCODE_USAGE = 'userLogin';

    /**
     * @var
     */
    public $mobile;

    /**
     * @var
     */
    public $code;

    /**
     * @var
     */
    public $group;

    /**
     * @var
     */
    protected $_user;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['mobile', 'code', 'group'], 'required'],
            ['code', '\common\models\validators\SmscodeValidator', 'usage' => self::VERCODE_USAGE],
            ['code', 'filter', 'filter' => 'trim'],
            ['mobile', 'match', 'pattern' => RegularHelper::mobile(), 'message' => '请输入正确的手机号'],
            ['mobile', 'validateMobile'],
            ['group', 'in', 'range' => AccessToken::$ruleGroupRnage]
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'mobile' => '手机号码',
            'code' => '验证码',
            'group' => '组别',
        ];
    }

    /**
     * @param $attribute
     */
    public function validateMobile($attribute)
    {
        if (!$this->getUser())
        {
            $this->addError($attribute, '找不到用户');
        }
    }

    /**
     * 获取用户信息
     *
     * @return mixed|null|static
     */
    public function getUser()
    {
        if ($this->_user == false)
        {
            $this->_user = Member::findOne(['mobile' => $this->mobile, 'status' => StatusEnum::ENABLED]);
        }

        return $this->_user;
    }
}
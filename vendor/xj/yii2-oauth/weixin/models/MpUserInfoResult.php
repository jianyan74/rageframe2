<?php
namespace xj\oauth\weixin\models;

/**
 * @author xjflyttp <xjflyttp@gmail.com>
 * @see http://mp.weixin.qq.com/wiki/14/bb5031008f1494a59c6f71fa0f319c66.html
 */
class MpUserInfoResult extends MpBaseModel
{
    const SUBSCRIBE_Y = 1;
    const SUBSCRIBE_N = 0;

    /**
     * @var string
     */
    public $subscribe;
    public $subscribe_time;
    public $openid;
    public $nickname;
    public $sex;
    public $city;
    public $country;
    public $province;
    public $language;
    public $headimgurl;
    public $unionid;
    public $remark;
    public $groupid;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [[
                'subscribe', 'subscribe_time', 'openid', 'nickname', 'sex',
                'city', 'country', 'province', 'language', 'headimgurl',
                'unionid', 'remark', 'groupid'
            ], 'safe'],
        ]);
    }

    /**
     * @return bool
     */
    public function isSubscribe()
    {
        return $this->subscribe === self::SUBSCRIBE_Y;
    }

}
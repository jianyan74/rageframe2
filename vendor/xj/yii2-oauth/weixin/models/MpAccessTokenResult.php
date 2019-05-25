<?php
namespace xj\oauth\weixin\models;

/**
 * 公众号AccessToken
 * @author xjflyttp <xjflyttp@gmail.com>
 * @see http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html#.E5.8F.91.E8.B5.B7.E4.B8.80.E4.B8.AA.E5.BE.AE.E4.BF.A1.E6.94.AF.E4.BB.98.E8.AF.B7.E6.B1.82
 */
class MpAccessTokenResult extends MpBaseModel
{

    public $access_token;
    public $expires_in;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['access_token', 'expires_in'], 'safe'],
        ]);
    }

    /**
     * @return WeixinMpToken
     */
    public function getAccessToken()
    {
        $token = new WeixinMpToken();
        $token->setToken($this->access_token);
        $token->setExpireDuration($this->expires_in);
        return $token;
    }
}
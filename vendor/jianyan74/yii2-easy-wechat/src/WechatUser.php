<?php
namespace jianyan\easywechat;

use yii\base\Component;

/**
 * Class WechatUser
 * @package jianyan74\easywechat
 */
class WechatUser extends Component
{
	/**
	 * @var string
	 */
	public $id;
	/**
	 * @var string
	 */
	public $nickname;
	/**
	 * @var string
	 */
	public $name;
	/**
	 * @var string
	 */
	public $email;
	/**
	 * @var string
	 */
	public $avatar;
	/**
	 * @var array
	 */
	public $original;
	/**
	 * @var \Overtrue\Socialite\AccessToken
	 */
	public $token;
	/**
	 * @var string
	 */
	public $provider;

	/**
	 * @return string
	 */
	public function getOpenId()
	{
		return isset($this->original['openid']) ? $this->original['openid'] : '';
	}
}

<?php

namespace xj\oauth;

/**
 * Oauth Interface
 * @author xjflyttp <xjflyttp@gmail.com>
 */
interface IAuth
{

    /**
     *
     * @return []
     */
    public function getUserInfo();

    /**
     *
     * @return mixed
     */
    public function getOpenid();
}

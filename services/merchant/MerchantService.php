<?php
namespace services\merchant;

use common\components\Service;

/**
 * Class Merchant
 * @package services\merchant
 * @author jianyan74 <751393839@qq.com>
 */
class MerchantService extends Service
{
    /**
     * 商户id
     *
     * @var int
     */
    protected $id;

    /**
     * 获取商户id
     *
     * @return int
     */
    public function getId()
    {
        if (!$this->id)
        {
            $this->setId();
        }

        return $this->id;
    }

    /**
     * 写入商户id
     */
    public function setId()
    {
        $this->id = 0;
    }

    /**
     * 获取商户信息
     *
     * @param $id
     * @return array
     */
    public function getInfo($id)
    {
        return [];
    }
}
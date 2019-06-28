<?php
namespace services\merchant;

use common\components\Service;

/**
 * 商户
 *
 * Class MerchantService
 * @package services\merchant
 * @author jianyan74 <751393839@qq.com>
 */
class MerchantService extends Service
{
    /**
     * @var array
     */
    protected $info = [];

    protected $merchant_id = 1;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->merchant_id;
    }

    /**
     * @param $merchant_id
     */
    public function setId($merchant_id)
    {
        $this->merchant_id = $merchant_id;
        $this->setInfo($merchant_id);
    }

    /**
     * @return array
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @param $merchant_id
     */
    public function setInfo($merchant_id)
    {
        // TODO 查询商户是否存在

        $this->info = [];
    }
}
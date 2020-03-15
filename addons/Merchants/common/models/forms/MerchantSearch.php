<?php

namespace addons\Merchants\common\models\forms;

use yii\base\Model;
use common\enums\SortEnum;

/**
 * Class MerchantSearch
 * @package addons\Merchants\common\models\forms
 * @author jianyan74 <751393839@qq.com>
 */
class MerchantSearch extends Model
{
    /*-- 查询 --*/

    public $keyword;
    public $is_recommend;
    public $cate_id;

    /*-- 排序 --*/

    public $collect;
    public $sales;
    public $credit;
    public $desc_credit;
    public $service_credit;
    public $delivery_credit;

    public $page_size = 10;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['cate_id', 'is_recommend', 'cate_id', 'page_size'], 'integer'],
            [['collect', 'credit', 'sales', 'desc_credit', 'service_credit', 'delivery_credit', 'keyword'], 'string'],
        ];
    }

    /**
     * @return array
     */
    public function getOrderBy()
    {
        // 排序
        $orderBy = [];

        $this->collect == SortEnum::ASC && $orderBy[] = 'collect ' . SortEnum::ASC;
        $this->collect == SortEnum::DESC && $orderBy[] = 'collect ' . SortEnum::DESC;
        $this->sales == SortEnum::ASC && $orderBy[] = 'sales ' . SortEnum::ASC;
        $this->sales == SortEnum::DESC && $orderBy[] = 'sales ' . SortEnum::DESC;
        $this->credit == SortEnum::ASC && $orderBy[] = 'credit ' . SortEnum::ASC;
        $this->credit == SortEnum::DESC && $orderBy[] = 'credit ' . SortEnum::DESC;
        $this->desc_credit == SortEnum::ASC && $orderBy[] = 'desc_credit ' . SortEnum::ASC;
        $this->desc_credit == SortEnum::DESC && $orderBy[] = 'desc_credit ' . SortEnum::DESC;
        $this->service_credit == SortEnum::ASC && $orderBy[] = 'service_credit ' . SortEnum::ASC;
        $this->service_credit == SortEnum::DESC && $orderBy[] = 'service_credit ' . SortEnum::DESC;
        $this->delivery_credit == SortEnum::ASC && $orderBy[] = 'delivery_credit ' . SortEnum::ASC;
        $this->delivery_credit == SortEnum::DESC && $orderBy[] = 'delivery_credit ' . SortEnum::DESC;

        return $orderBy;
    }
}
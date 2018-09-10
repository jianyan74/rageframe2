<?php
namespace common\helpers;

use yii\helpers\BaseHtml;
use common\enums\StatusEnum;

/**
 * Class HtmlHelper
 * @package common\helpers
 */
class HtmlHelper extends BaseHtml
{
    /**
     * 状态标签
     *
     * @param int $status
     * @return mixed
     */
    public static function statusSpan($status = 1)
    {
        $listBut = [
            StatusEnum::DISABLED => '<span class="btn btn-primary btn-sm" onclick="rfStatus(this)">启用</span>',
            StatusEnum::ENABLED => '<span class="btn btn-default btn-sm" onclick="rfStatus(this)">禁用</span>',
        ];

        return $listBut[$status];
    }

    /**
     * 头像
     *
     * @param $head_portrait
     * @return mixed
     */
    public static function headPortrait($head_portrait)
    {
        return !empty($head_portrait) ? $head_portrait : '/backend/resources/img/profile_small.jpg';
    }
}
<?php

namespace common\enums;

use yii\helpers\Html;

/**
 * Class AuditStateEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class AuditStateEnum extends BaseEnum
{
    const ENABLED = 1;
    const DISABLED = 0;
    const DELETE = -1;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::ENABLED => '已通过',
            self::DISABLED => '审核中',
             self::DELETE => '已拒绝',
        ];
    }

    /**
     * @return array
     */
    public static function audit(): array
    {
        return [
            self::DISABLED => '审核中',
            self::DELETE => '已拒绝',
        ];
    }

    /**
     * @param $key
     * @return mixed|string
     */
    public static function html($key)
    {
        $html = [
            self::ENABLED => Html::tag('span', self::getValue(self::ENABLED), array_merge(
                [
                    'class' => "label label-primary",
                ]
            )),
            self::DISABLED => Html::tag('span', self::getValue(self::DISABLED), array_merge(
                [
                    'class' => "label label-default",
                ]
            )),
            self::DELETE => Html::tag('span', self::getValue(self::DELETE), array_merge(
                [
                    'class' => "label label-warning",
                ]
            )),
        ];

        return $html[$key] ?? '';
    }
}
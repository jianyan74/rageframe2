<?php

namespace common\enums;

/**
 * Class ConfigTypeEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class ConfigTypeEnum extends BaseEnum
{
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            'text' => "文本框",
            'password' => "密码框",
            'secretKeyText' => "密钥文本框",
            'textarea' => "文本域",
            'date' => "日期",
            'time' => "时间",
            'datetime' => "日期时间",
            'dropDownList' => "下拉文本框",
            'multipleInput' => "Input组",
            'radioList' => "单选按钮",
            'checkboxList' => "复选框",
            'baiduUEditor' => "百度编辑器",
            'image' => "图片上传",
            'images' => "多图上传",
            'file' => "文件上传",
            'files' => "多文件上传",
            'cropper' => "图片裁剪上传",
            'latLngSelection' => "经纬度选择",
        ];
    }
}
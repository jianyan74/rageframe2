<?php

namespace common\components\gii\crud;

use Yii;

/**
 * Class Generator
 * @package backend\components\gii\crud
 * @author jianyan74 <751393839@qq.com>
 */
class Generator extends \yii\gii\generators\crud\Generator
{
    public $listFields;
    public $formFields;
    public $inputType;

    /**
     * @return array
     */
    public function fieldTypes()
    {
        return [
            'text' => "文本框",
            'textarea' => "文本域",
            'time' => "时间",
            'date' => "日期",
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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['listFields', 'formFields', 'inputType'], 'safe'],
        ]);
    }

    /**
     * Generates code for active field
     * @param string $attribute
     * @return string
     */
    public function generateActiveField($attribute)
    {
        $tableSchema = $this->getTableSchema();
        $type = $this->inputType[$attribute] ?? '';

        switch ($type) {
            case 'text':
                return parent::generateActiveField($attribute);
                break;
            case 'textarea':
                return "\$form->field(\$model, '$attribute')->textarea()";
                break;
            case 'dropDownList':
                return "\$form->field(\$model, '$attribute')->dropDownList([])";
                break;
            case 'radioList':
                return "\$form->field(\$model, '$attribute')->radioList(\common\\enums\StatusEnum::getMap())";
                break;
            case 'checkboxList':
                return "\$form->field(\$model, '$attribute')->checkboxList(\common\\enums\StatusEnum::getMap())";
                break;
            case 'baiduUEditor':
                return "\$form->field(\$model, '$attribute')->widget(\common\widgets\ueditor\UEditor::class, [])";
                break;
            case 'color':
                return "\$form->field(\$model, '$attribute')->widget(\kartik\color\ColorInput::class, [
                            'options' => ['placeholder' => '请选择颜色'],
                    ]);";
                break;
            case 'time':
                return "\$form->field(\$model, '$attribute')->widget(kartik\\time\TimePicker::class, [
                        'language' => 'zh-CN',
                        'pluginOptions' => [
                            'showSeconds' => true
                        ]
                    ])";
                break;
            case 'date':
                return "\$form->field(\$model, '$attribute')->widget(kartik\date\DatePicker::class, [
                        'language' => 'zh-CN',
                        'layout'=>'{picker}{input}',
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd',
                            'todayHighlight' => true, // 今日高亮
                            'autoclose' => true, // 选择后自动关闭
                            'todayBtn' => true, // 今日按钮显示
                        ],
                        'options'=>[
                            'class' => 'form-control no_bor',
                        ]
                    ])";
                break;
            case 'datetime':
                return "\$form->field(\$model, '$attribute')->widget(kartik\datetime\DateTimePicker::class, [
                        'language' => 'zh-CN',
                        'options' => [
                            'value' => \$model->isNewRecord ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s',\$model->$attribute),
                        ],
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd hh:ii',
                            'todayHighlight' => true, // 今日高亮
                            'autoclose' => true, // 选择后自动关闭
                            'todayBtn' => true, // 今日按钮显示
                        ]
                    ])";
                break;
            case 'multipleInput':
                return "\$form->field(\$model, '$attribute')->widget(unclead\multipleinput\MultipleInput::class, [
                        'max' => 4,
                        'columns' => [
                            [
                                'name'  => 'user_id',
                                'type'  => 'dropDownList',
                                'title' => 'User',
                                'defaultValue' => 1,
                                'items' => [
                                   1 => 'User 1',
                                    2 => 'User 2'
                                ]
                            ],
                            [
                                'name'  => 'day',
                                'type'  => \kartik\date\DatePicker::class,
                                'title' => 'Day',
                                'value' => function(\$data) {
                                    return \$data['day'];
                                },
                                'items' => [
                                    '0' => 'Saturday',
                                    '1' => 'Monday'
                                ],
                                'options' => [
                                    'pluginOptions' => [
                                        'format' => 'dd.mm.yyyy',
                                        'todayHighlight' => true
                                    ]
                                ]
                            ],
                            [
                                'name'  => 'priority',
                                'title' => 'Priority',
                                'enableError' => true,
                                'options' => [
                                    'class' => 'input-priority'
                                ]
                            ]
                        ]
                     ])";
                break;
            case 'cropper':
                return "\$form->field(\$model, '$attribute')->widget(\common\widgets\cropper\Cropper::class, [
                            'config' => [
                                  // 可设置自己的上传地址, 不设置则默认地址
                                  // 'server' => '',
                             ],
                            'formData' => [
                                // 'drive' => 'local',// 默认本地 支持 qiniu/oss/cos 上传
                            ],
                    ]);";
                break;
            case 'latLngSelection':
                return "\$form->field(\$model, '$attribute')->widget(\common\widgets\selectmap\Map::class, [
                            'type' => 'amap', // amap高德;tencent:腾讯;baidu:百度
                    ])->hint('点击地图某处才会获取到经纬度，否则默认北京')";
                break;
            case 'image':
                return "\$form->field(\$model, '$attribute')->widget(\common\widgets\webuploader\Files::class, [
                            'type' => 'images',
                            'theme' => 'default',
                            'themeConfig' => [],
                            'config' => [
                                // 可设置自己的上传地址, 不设置则默认地址
                                // 'server' => '',
                                'pick' => [
                                    'multiple' => false,
                                ],
                            ]
                    ]);";
                break;
            case 'images':
                return "\$form->field(\$model, '$attribute')->widget(\common\widgets\webuploader\Files::class, [
                            'type' => 'images',
                            'theme' => 'default',
                            'themeConfig' => [],
                            'config' => [
                                // 可设置自己的上传地址, 不设置则默认地址
                                // 'server' => '',
                                'pick' => [
                                    'multiple' => true,
                                ],
                            ]
                    ]);";
                break;
            case 'file':
                return "\$form->field(\$model, '$attribute')->widget(\common\widgets\webuploader\Files::class, [
                            'type' => 'files',
                            'theme' => 'default',
                            'themeConfig' => [],
                            'config' => [
                                // 可设置自己的上传地址, 不设置则默认地址
                                // 'server' => '',
                                'pick' => [
                                    'multiple' => false,
                                ],
                            ]
                    ]);";
                break;
            case 'files':
                return "\$form->field(\$model, '$attribute')->widget(\common\widgets\webuploader\Files::class, [
                            'type' => 'files',
                            'theme' => 'default',
                            'themeConfig' => [],
                            'config' => [
                                // 可设置自己的上传地址, 不设置则默认地址
                                // 'server' => '',
                                'pick' => [
                                    'multiple' => true,
                                ],
                            ]
                    ]);";
                break;
        }
    }
}
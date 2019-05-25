<?php

/**
 * @link https://github.com/unclead/yii2-multiple-input
 * @copyright Copyright (c) 2014 unclead
 * @license https://github.com/unclead/yii2-multiple-input/blob/master/LICENSE.md
 */

namespace unclead\multipleinput;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\ActiveRecordInterface;
use yii\bootstrap\Widget;
use yii\widgets\ActiveForm;
use unclead\multipleinput\renderers\TableRenderer;
use unclead\multipleinput\renderers\RendererInterface;

/**
 * Class TabularInput
 * @package unclead\multipleinput
 */
class TabularInput extends Widget
{
    const POS_HEADER    = RendererInterface::POS_HEADER;
    const POS_ROW       = RendererInterface::POS_ROW;
    const POS_FOOTER    = RendererInterface::POS_FOOTER;
    const POS_ROW_BEGIN = RendererInterface::POS_ROW_BEGIN;

    const THEME_DEFAULT = 'default';
    const THEME_BS      = 'bootstrap';

    const ICONS_SOURCE_GLYPHICONS  = 'glyphicons';
    const ICONS_SOURCE_FONTAWESOME = 'fa';

    /**
     * @var array
     */
    public $columns = [];

    /**
     * @var integer maximum number of rows
     */
    public $max;

    /**
     * @var int minimum number of rows
     */
    public $min;

    /**
     * @var array client-side attribute options, e.g. enableAjaxValidation. You may use this property in case when
     * you use widget without a model, since in this case widget is not able to detect client-side options
     * automatically.
     */
    public $attributeOptions = [];

    /**
     * @var array the HTML options for the `remove` button
     */
    public $removeButtonOptions;

    /**
     * @var array the HTML options for the `add` button
     */
    public $addButtonOptions;

    /**
     * @var array the HTML options for the `clone` button
     */
    public $cloneButtonOptions;

    /**
     * @var bool whether to allow the empty list
     */
    public $allowEmptyList = false;

    /**
     * @var Model[]|ActiveRecordInterface[]
     */
    public $models = [];

    /**
     * @var string|array position of add button.
     */
    public $addButtonPosition;

    /**
     * @var array|\Closure the HTML attributes for the table body rows. This can be either an array
     * specifying the common HTML attributes for all body rows, or an anonymous function that
     * returns an array of the HTML attributes. It should have the following signature:
     *
     * ```php
     * function ($model, $index, $context)
     * ```
     *
     * - `$model`: the current data model being rendered
     * - `$index`: the zero-based index of the data model in the model array
     * - `$context`: the TabularInput widget object
     *
     */
    public $rowOptions = [];


    /**
     * @var string the name of column class. You can specify your own class to extend base functionality.
     * Defaults to `unclead\multipleinput\TabularColumn`
     */
    public $columnClass;

    /**
     * @var string the name of renderer class. Defaults to `unclead\multipleinput\renderers\TableRenderer`.
     * @since 1.4
     */
    public $rendererClass;

    /**
     * @var ActiveForm an instance of ActiveForm which you have to pass in case of using client validation
     * @since 2.1
     */
    public $form;

    /**
     * @var bool allow sorting.
     * @internal this property is used when need to allow sorting rows.
     */
    public $sortable = false;

    /**
     * @var bool whether to render inline error for all input. Default to `false`. Can be override in `columns`
     * @since 2.10
     */
    public $enableError = false;

    /**
     * @var bool whether to render clone button. Default to `false`.
     */
    public $cloneButton = false;

    /**
     * @var string|\Closure the HTML content that will be rendered after the buttons.
     *
     * ```php
     * function ($model, $index, $context)
     * ```
     *
     * - `$model`: the current data model being rendered
     * - `$index`: the zero-based index of the data model in the model array
     * - `$context`: the MultipleInput widget object
     *
     */
    public $extraButtons;

    /**
     * @var string a class of model which is used to render the widget.
     * You have to specify this property in case you set `min` property to 0 (when you want to allow an empty list)
     * @since 2.13
     */
    public $modelClass;

    /**
     * @var array CSS grid classes for horizontal layout. This must be an array with these keys:
     *  - 'offsetClass' the offset grid class to append to the wrapper if no label is rendered
     *  - 'labelClass' the label grid class
     *  - 'wrapperClass' the wrapper grid class
     *  - 'errorClass' the error grid class
     */
    public $layoutConfig = [];

    /**
     * @var array
     * --icon library classes mapped for various controls
     */
    public $iconMap = [
        self::ICONS_SOURCE_GLYPHICONS => [
            'drag-handle'   => 'glyphicon glyphicon-menu-hamburger',
            'remove'        => 'glyphicon glyphicon-remove',
            'add'           => 'glyphicon glyphicon-plus',
            'clone'         => 'glyphicon glyphicon-duplicate',
        ],
        self::ICONS_SOURCE_FONTAWESOME => [
            'drag-handle'   => 'fa fa-bars',
            'remove'        => 'fa fa-times',
            'add'           => 'fa fa-plus',
            'clone'         => 'fa fa-files-o',
        ],
    ];

    /**
     * @var string the CSS theme of the widget
     *
     * @todo Use bootstrap theme for BC. We can switch to default theme in major release
     */
    public $theme = self::THEME_BS;

    /**
     * @var string the name of default icon library
     */
    public $iconSource = self::ICONS_SOURCE_GLYPHICONS;

    /**
     * Initialization.
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        if (empty($this->models) && !$this->modelClass) {
            throw new InvalidConfigException('You must at least specify "models" or "modelClass"');
        }

        if ($this->form !== null && !$this->form instanceof ActiveForm) {
            throw new InvalidConfigException('Property "form" must be an instance of yii\widgets\ActiveForm');
        }

        if (!is_array($this->models)) {
            throw new InvalidConfigException('Property "models" must be an array');
        }

        if ($this->models) {
            $modelClasses = [];
            foreach ($this->models as $model) {
                if (!$model instanceof Model) {
                    throw new InvalidConfigException('Model has to be an instance of yii\base\Model');
                }

                $modelClasses[get_class($model)] = true;
            }

            if (count($modelClasses) > 1) {
                throw new InvalidConfigException("You cannot use models of different classes");
            }

            $this->modelClass = key($modelClasses);
        }

        parent::init();
    }

    /**
     * Run widget.
     */
    public function run()
    {
        return $this->createRenderer()->render();
    }

    /**
     * @return TableRenderer
     */
    protected function createRenderer()
    {
        if($this->sortable) {
            $drag = [
                'name'  => 'drag',
                'type'  => TabularColumn::TYPE_DRAGCOLUMN,
                'headerOptions' => [
                    'style' => 'width: 20px;',
                ]
            ];

            array_unshift($this->columns, $drag);
        }

        $available_themes = [
            self::THEME_BS,
            self::THEME_DEFAULT
        ];

        if (!in_array($this->theme, $available_themes, true)) {
            $this->theme = self::THEME_BS;
        }

        /**
         * set default icon map
         */
        $iconMap = array_key_exists($this->iconSource, $this->iconMap)
            ? $this->iconMap[$this->iconSource]
            : $this->iconMap[self::ICONS_SOURCE_GLYPHICONS];

        $config = [
            'id'                => $this->getId(),
            'columns'           => $this->columns,
            'min'               => $this->min,
            'max'               => $this->max,
            'attributeOptions'  => $this->attributeOptions,
            'data'              => $this->models,
            'columnClass'       => $this->columnClass !== null ? $this->columnClass : TabularColumn::className(),
            'allowEmptyList'    => $this->allowEmptyList,
            'rowOptions'        => $this->rowOptions,
            'addButtonPosition' => $this->addButtonPosition,
            'context'           => $this,
            'form'              => $this->form,
            'sortable'          => $this->sortable,
            'enableError'       => $this->enableError,
            'cloneButton'       => $this->cloneButton,
            'extraButtons'      => $this->extraButtons,
            'layoutConfig'      => $this->layoutConfig,
            'iconMap'           => $iconMap,
            'theme'             => $this->theme
        ];

        if ($this->removeButtonOptions !== null) {
            $config['removeButtonOptions'] = $this->removeButtonOptions;
        }

        if ($this->addButtonOptions !== null) {
            $config['addButtonOptions'] = $this->addButtonOptions;
        }

        if ($this->cloneButtonOptions !== null) {
            $config['cloneButtonOptions'] = $this->cloneButtonOptions;
        }

        if (!$this->rendererClass) {
            $this->rendererClass = TableRenderer::className();
        }

        $config['class'] = $this->rendererClass ?: TableRenderer::className();

        return Yii::createObject($config);
    }
}

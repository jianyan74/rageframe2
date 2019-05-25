<?php

/**
 * @link https://github.com/unclead/yii2-multiple-input
 * @copyright Copyright (c) 2014 unclead
 * @license https://github.com/unclead/yii2-multiple-input/blob/master/LICENSE.md
 */

namespace unclead\multipleinput\renderers;

use yii\base\InvalidConfigException;
use yii\db\ActiveRecordInterface;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use unclead\multipleinput\components\BaseColumn;

/**
 * Class ListRenderer
 * @package unclead\multipleinput\renderers
 */
class ListRenderer extends BaseRenderer
{
    /**
     * @return mixed
     */
    protected function internalRender()
    {
        $content = [];

        $content[] = $this->renderHeader();
        $content[] = $this->renderBody();
        $content[] = $this->renderFooter();

        $options = [];
        Html::addCssClass($options, 'multiple-input-list list-renderer');

        if ($this->isBootstrapTheme()) {
            Html::addCssClass($options, 'table form-horizontal');
        }

        $content = Html::tag('table', implode("\n", $content), $options);

        return Html::tag('div', $content, [
            'id' => $this->id,
            'class' => 'multiple-input'
        ]);
    }

    /**
     * Renders the header.
     *
     * @return string
     */
    public function renderHeader()
    {
        if ($this->min !== 0 || !$this->isAddButtonPositionHeader()) {
            return '';
        }

        $button = $this->isAddButtonPositionHeader() ? $this->renderAddButton() : '';

        $content = [];
        $content[] = Html::tag('td', '&nbsp;');

        if ($this->cloneButton) {
            $content[] = Html::tag('td', '&nbsp;');
        }

        $content[] = Html::tag('td', $button, [
            'class' => 'list-cell__button',
        ]);

        return Html::tag('thead', Html::tag('tr', implode("\n", $content)));
    }

    /**
     * Renders the footer.
     *
     * @return string
     */
    public function renderFooter()
    {
        if (!$this->isAddButtonPositionFooter()) {
            return '';
        }

        $cells = [];
        $cells[] = Html::tag('td', '&nbsp;');
        $cells[] = Html::tag('td', $this->renderAddButton(), [
            'class' => 'list-cell__button'
        ]);

        return Html::tag('tfoot', Html::tag('tr', implode("\n", $cells)));
    }

    /**
     * Renders the body.
     *
     * @return string
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\InvalidParamException
     */
    protected function renderBody()
    {
        $rows = [];

        if ($this->data) {
            $j = 0;
            foreach ($this->data as $index => $item) {
                if ($j++ <= $this->max) {
                    $rows[] = $this->renderRowContent($index, $item);
                } else {
                    break;
                }
            }
            for ($i = $j; $i < $this->min; $i++) {
                $rows[] = $this->renderRowContent($i);
            }
        } elseif ($this->min > 0) {
            for ($i = 0; $i < $this->min; $i++) {
                $rows[] = $this->renderRowContent($i);
            }
        }

        return Html::tag('tbody', implode("\n", $rows));
    }

    /**
     * Renders the row content.
     *
     * @param int $index
     * @param ActiveRecordInterface|array $item
     * @return mixed
     * @throws InvalidConfigException
     */
    private function renderRowContent($index = null, $item = null)
    {
        $elements = [];

        $columnIndex = 0;
        foreach ($this->columns as $column) {
            /* @var $column BaseColumn */
            $column->setModel($item);
            $elements[] = $this->renderCellContent($column, $index, $columnIndex++);
        }

        $content = [];
        $content[] = Html::tag('td', implode("\n", $elements));
        if ($this->max !== $this->min) {
            $content[] = $this->renderActionColumn($index);
        }

        if ($this->cloneButton) {
            $content[] = $this->renderCloneColumn();
        }

        $content = Html::tag('tr', implode("\n", $content), $this->prepareRowOptions($index, $item));

        if ($index !== null) {
            $content = str_replace('{' . $this->getIndexPlaceholder() . '}', $index, $content);
        }

        return $content;
    }

    /**
     * Prepares the row options.
     *
     * @param int $index
     * @param ActiveRecordInterface|array $item
     * @return array
     */
    protected function prepareRowOptions($index, $item)
    {
        if (is_callable($this->rowOptions)) {
            $options = call_user_func($this->rowOptions, $item, $index, $this->context);
        } else {
            $options = $this->rowOptions;
        }

        Html::addCssClass($options, 'multiple-input-list__item');

        return $options;
    }

    /**
     * Renders the cell content.
     *
     * @param BaseColumn $column
     * @param int|null $index
     * @return string
     */
    public function renderCellContent($column, $index, $columnIndex = null)
    {
        $id    = $column->getElementId($index);
        $name  = $column->getElementName($index);

        /**
         * This class inherits iconMap from BaseRenderer
         * If the input to be rendered is a drag column, we give it the appropriate icon class
         * via the $options array
         */
        $options = ['id' => $id];
        if (substr($id, -4) === 'drag') {
            $options = ArrayHelper::merge($options, ['class' => $this->iconMap['drag-handle']]);
        }

        $input = $column->renderInput($name, $options, [
            'id' => $id,
            'name' => $name,
            'indexPlaceholder' => $this->getIndexPlaceholder(),
            'index' => $index,
            'columnIndex' => $columnIndex,
            'context' => $this->context,
        ]);

        if ($column->isHiddenInput()) {
            return $input;
        }

        $layoutConfig = array_merge([
            'offsetClass'   => $this->isBootstrapTheme() ? 'col-sm-offset-3' : '',
            'labelClass'    => $this->isBootstrapTheme() ? 'col-sm-3' : '',
            'wrapperClass'  => $this->isBootstrapTheme() ? 'col-sm-6' : '',
            'errorClass'    => $this->isBootstrapTheme() ? 'col-sm-offset-3 col-sm-6' : '',
        ], $this->layoutConfig);

        Html::addCssClass($column->errorOptions, $layoutConfig['errorClass']);

        $hasError = false;
        $error = '';

        if ($index !== null) {
            $error = $column->getFirstError($index);
            $hasError = !empty($error);
        }

        $wrapperOptions = [];

        if ($hasError) {
            Html::addCssClass($wrapperOptions, 'has-error');
        }

        Html::addCssClass($wrapperOptions, $layoutConfig['wrapperClass']);

        $options = [
            'class' => "field-$id list-cell__$column->name" . ($hasError ? ' has-error' : '')
        ];

        if ($this->isBootstrapTheme()) {
            Html::addCssClass($options, 'form-group');
        }

        if (is_callable($column->columnOptions)) {
            $columnOptions = call_user_func($column->columnOptions, $column->getModel(), $index, $this->context);
        } else {
            $columnOptions = $column->columnOptions;
        }

        $options = array_merge_recursive($options, $columnOptions);

        $content = Html::beginTag('div', $options);

        if (empty($column->title)) {
            Html::addCssClass($wrapperOptions, $layoutConfig['offsetClass']);
        } else {
            $labelOptions = ['class' => $layoutConfig['labelClass']];
            if ($this->isBootstrapTheme()) {
                Html::addCssClass($labelOptions, 'control-label');
            }

            $content .= Html::label($column->title, $id, $labelOptions);
        }

        $content .= Html::tag('div', $input, $wrapperOptions);

        if ($column->enableError) {
            $content .= "\n" . $column->renderError($error);
        }

        $content .= Html::endTag('div');

        return $content;
    }

    /**
     * Renders the action column.
     *
     * @param null|int $index
     * @param null|ActiveRecordInterface|array $item
     * @return string
     * @throws \Exception
     */
    private function renderActionColumn($index = null, $item = null)
    {
        $content = $this->getActionButton($index) . $this->getExtraButtons($index, $item);

        return Html::tag('td', $content, [
            'class' => 'list-cell__button',
        ]);
    }

    /**
     * Renders the clone column.
     *
     * @return string
     * @throws \Exception
     */
    private function renderCloneColumn()
    {
        return Html::tag('td', $this->renderCloneButton(), [
            'class' => 'list-cell__button',
        ]);
    }

    private function getActionButton($index)
    {
        if ($index === null || $this->min === 0) {
            return $this->renderRemoveButton();
        }

        $index++;
        if ($index < $this->min) {
            return '';
        }

        if ($index === $this->min) {
            return $this->isAddButtonPositionRow() ? $this->renderAddButton() : '';
        }

        return $this->renderRemoveButton();
    }

    private function renderAddButton()
    {
        $options = [
            'class' => 'multiple-input-list__btn js-input-plus',
        ];
        Html::addCssClass($options, $this->addButtonOptions['class']);

        return Html::tag('div', $this->addButtonOptions['label'], $options);
    }

    /**
     * Renders remove button.
     *
     * @return string
     */
    private function renderRemoveButton()
    {
        $options = [
            'class' => 'multiple-input-list__btn js-input-remove',
        ];
        Html::addCssClass($options, $this->removeButtonOptions['class']);

        return Html::tag('div', $this->removeButtonOptions['label'], $options);
    }

    /**
     * Renders clone button.
     *
     * @return string
     */
    private function renderCloneButton()
    {
        $options = [
            'class' => 'multiple-input-list__btn js-input-clone',
        ];
        Html::addCssClass($options, $this->cloneButtonOptions['class']);

        return Html::tag('div', $this->cloneButtonOptions['label'], $options);
    }

    /**
     * Returns template for using in js.
     *
     * @return string
     *
     * @throws \yii\base\InvalidConfigException
     */
    protected function prepareTemplate()
    {
        return $this->renderRowContent();
    }
}

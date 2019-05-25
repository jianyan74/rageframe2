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
 * Class TableRenderer
 * @package unclead\multipleinput\renderers
 */
class TableRenderer extends BaseRenderer
{
    /**
     * @return mixed
     */
    protected function internalRender()
    {
        $content = [];

        if ($this->hasHeader()) {
            $content[] = $this->renderHeader();
        }

        $content[] = $this->renderBody();
        $content[] = $this->renderFooter();

        $options = [];
        Html::addCssClass($options, 'multiple-input-list');

        if ($this->isBootstrapTheme()) {
            Html::addCssClass($options, 'table table-condensed table-renderer');
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
        $cells = [];
        foreach ($this->columns as $column) {
            /* @var $column BaseColumn */
            $cells[] = $this->renderHeaderCell($column);
        }

        if ($this->max === null || ($this->max >= 1 && $this->max !== $this->min)) {
            $button = $this->isAddButtonPositionHeader() ? $this->renderAddButton() : '';

            if ($this->cloneButton) {
                $cells[] = $this->renderButtonHeaderCell();
            }

            $cells[] = $this->renderButtonHeaderCell($button);
        }

        return Html::tag('thead', Html::tag('tr', implode("\n", $cells)));
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

        $columnsCount = 0;
        foreach ($this->columns as $column) {
            if (!$column->isHiddenInput()) {
                $columnsCount++;
            }
        }

        if ($this->cloneButton) {
            $columnsCount++;
        }

        $cells = [];
        $cells[] = Html::tag('td', '&nbsp;', ['colspan' => $columnsCount]);
        $cells[] = Html::tag('td', $this->renderAddButton(), [
            'class' => 'list-cell__button'
        ]);

        return Html::tag('tfoot', Html::tag('tr', implode("\n", $cells)));
    }


    /**
     * Check that at least one column has a header.
     *
     * @return bool
     */
    private function hasHeader()
    {
        if ($this->min === 0 || $this->isAddButtonPositionHeader()) {
            return true;
        }

        foreach ($this->columns as $column) {
            /* @var $column BaseColumn */
            if ($column->title) {
                return true;
            }
        }

        return false;
    }

    /**
     * Renders the header cell.
     * @param BaseColumn $column
     * @return null|string
     */
    private function renderHeaderCell($column)
    {
        if ($column->isHiddenInput()) {
            return null;
        }

        $options = $column->headerOptions;
        Html::addCssClass($options, 'list-cell__' . $column->name);

        return Html::tag('th', $column->title, $options);
    }

    /**
     * Renders the button header cell.
     * @param string
     * @return string
     */
    private function renderButtonHeaderCell($button = '')
    {
        return Html::tag('th', $button, [
            'class' => 'list-cell__button'
        ]);
    }

    /**
     * Renders the body.
     *
     * @return string
     *
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
        $cells = [];
        $hiddenInputs = [];
        $isLastRow = $this->max === $this->min;
        if (!$isLastRow && $this->isAddButtonPositionRowBegin()) {
            $cells[] = $this->renderActionColumn($index, $item, true);
        }

        $columnIndex = 0;
        foreach ($this->columns as $column) {
            /* @var $column BaseColumn */
            $column->setModel($item);
            if ($column->isHiddenInput()) {
                $hiddenInputs[] = $this->renderCellContent($column, $index, $columnIndex++);
            } else {
                $cells[] = $this->renderCellContent($column, $index, $columnIndex++);
            }
        }
        if ($this->cloneButton) {
            $cells[] = $this->renderCloneColumn();
        }

        if (!$isLastRow) {
            $cells[] = $this->renderActionColumn($index, $item);
        }

        if ($hiddenInputs) {
            $hiddenInputs = implode("\n", $hiddenInputs);
            $cells[0] = preg_replace('/^(<td[^>]+>)(.*)(<\/td>)$/s', '${1}' . $hiddenInputs . '$2$3', $cells[0]);
        }

        $content = Html::tag('tr', implode("\n", $cells), $this->prepareRowOptions($index, $item));

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
     * @param int|null $columnIndex
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

        $hasError = false;
        $error = '';

        if ($index !== null) {
            $error = $column->getFirstError($index);
            $hasError = !empty($error);
        }

        if ($column->enableError) {
            $input .= "\n" . $column->renderError($error);
        }

        $wrapperOptions = ['class' => 'field-' . $id];
        if ($this->isBootstrapTheme()) {
            Html::addCssClass($options, 'form-group');
        }

        if ($hasError) {
            Html::addCssClass($wrapperOptions, 'has-error');
        }

        if (is_callable($column->columnOptions)) {
            $columnOptions = call_user_func($column->columnOptions, $column->getModel(), $index, $this->context);
        } else {
            $columnOptions = $column->columnOptions;
        }

        Html::addCssClass($columnOptions, 'list-cell__' . $column->name);

        $input = Html::tag('div', $input, $wrapperOptions);

        return Html::tag('td', $input, $columnOptions);
    }


    /**
     * Renders the action column.
     *
     * @param null|int $index
     * @param null|ActiveRecordInterface|array $item
     * @param bool $isFirstColumn
     * @return string
     */
    private function renderActionColumn($index = null, $item = null, $isFirstColumn = false)
    {
        $content = $this->getActionButton($index, $isFirstColumn) . $this->getExtraButtons($index, $item);

        return Html::tag('td', $content, [
            'class' => 'list-cell__button',
        ]);
    }

    /**
     * Renders the clone column.
     *
     * @return string
     */
    private function renderCloneColumn()
    {
        return Html::tag('td', $this->renderCloneButton(), [
            'class' => 'list-cell__button',
        ]);
    }

    private function getActionButton($index, $isFirstColumn)
    {
        if ($index === null || $this->min === 0) {
            if ($isFirstColumn) {
                return $this->isAddButtonPositionRowBegin() ? $this->renderRemoveButton() : '';
            }

            return $this->isAddButtonPositionRowBegin() ? '' : $this->renderRemoveButton();
        }

        $index++;
        if ($index < $this->min) {
            return '';
        }

        if ($index === $this->min) {
            if ($isFirstColumn) {
                return $this->isAddButtonPositionRowBegin() ? $this->renderAddButton() : '';
            }

            return $this->isAddButtonPositionRow() ? $this->renderAddButton() : '';
        }

        if ($isFirstColumn) {
            return $this->isAddButtonPositionRowBegin() ? $this->renderRemoveButton() : '';
        }

        return $this->isAddButtonPositionRowBegin() ? '' : $this->renderRemoveButton();
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

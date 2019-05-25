<?php

/**
 * @link https://github.com/unclead/yii2-multiple-input
 * @copyright Copyright (c) 2014 unclead
 * @license https://github.com/unclead/yii2-multiple-input/blob/master/LICENSE.md
 */

namespace unclead\multipleinput;

use unclead\multipleinput\components\BaseColumn;
use yii\base\Model;

/**
 * Class TabularColumn
 * @package unclead\multipleinput
 *
 * @property TabularInput $context
 */
class TabularColumn extends BaseColumn
{   
    /**
     * Returns element's name.
     *
     * @param int|null|string $index current row index
     * @param bool $withPrefix whether to add prefix.
     * @return string
     */
    public function getElementName($index, $withPrefix = true)
    {
        if ($index === null) {
            $index = '{' . $this->renderer->getIndexPlaceholder() . '}';
        }

        $elementName = '[' . $index . '][' . $this->name . ']';
        $prefix = $withPrefix ? $this->getModel()->formName() : '';

        return $prefix . $elementName . (empty($this->nameSuffix) ? '' : ('_' . $this->nameSuffix));
    }

    /**
     * Returns first error of the current model.
     *
     * @param $index
     * @return string
     */
    public function getFirstError($index)
    {
        return $this->getModel()->getFirstError($this->name);
    }

    /**
     * Ensure that model is an instance of yii\base\Model.
     *
     * @param $model
     * @return bool
     */
    protected function ensureModel($model)
    {
        return $model instanceof Model;
    }

    /**
     * @inheritdoc
     */
    public function setModel($model)
    {
        if ($model === null) {
            $model = \Yii::createObject(['class' => $this->context->modelClass]);
        }

        parent::setModel($model);
    }
}

<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\debug\panels;

use Yii;
use yii\debug\models\Router;
use yii\debug\Panel;
use yii\log\Logger;

/**
 * RouterPanel provides a panel which displays information about routing process.
 *
 * @property array $categories Note that the type of this property differs in getter and setter. See
 * [[getCategories()]] and [[setCategories()]] for details.
 *
 * @author Dmitriy Bashkarev <dmitriy@bashkarev.com>
 * @since 2.0.8
 */
class RouterPanel extends Panel
{
    /**
     * @var array
     */
    private $_categories = [
        'yii\web\UrlManager::parseRequest',
        'yii\web\UrlRule::parseRequest',
        'yii\web\CompositeUrlRule::parseRequest',
        'yii\rest\UrlRule::parseRequest'
    ];


    /**
     * @param string|array $values
     */
    public function setCategories($values)
    {
        if (!is_array($values)) {
            $values = [$values];
        }
        $this->_categories = array_merge($this->_categories, $values);
    }

    /**
     * Listens categories of the messages.
     * @return array
     */
    public function getCategories()
    {
        return $this->_categories;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Router';
    }

    /**
     * {@inheritdoc}
     */
    public function getDetail()
    {
        return Yii::$app->view->render('panels/router/detail', ['model' => new Router($this->data)]);
    }

    /**
     * {@inheritdoc}
     */
    public function save()
    {
        $target = $this->module->logTarget;
        return [
            'messages' => $target::filterMessages($target->messages, Logger::LEVEL_TRACE, $this->_categories)
        ];
    }
}

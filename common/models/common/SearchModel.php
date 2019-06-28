<?php

namespace common\models\common;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\ActiveQuery;
use yii\web\NotFoundHttpException;

/**
 * // 示例一
 *
 * ```php
 * $searchModel = new SearchModel(
 * [
 *      'model' => Topic::class,
 *      'scenario' => 'default',
 * ]
 * );
 *
 * $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
 *
 * return $this->render('index', [
 *      'dataProvider' => $dataProvider,
 * ]);
 * ```
 *
 * // 示例二
 *
 *```php
 * $searchModel = new SearchModel(
 * [
 *      'defaultOrder' => ['id' => SORT_DESC],
 *      'model' => Topic::class,
 *      'scenario' => 'default',
 *      'relations' => ['comment' => []], // 关联表（可以是Model里面的关联）
 *      'partialMatchAttributes' => ['title'], // 模糊查询
 *      'pageSize' => 15
 * ]
 * );
 *
 * $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
 * $dataProvider->query->andWhere([Topic::tableName() . '.user_id' => 23, Comment::tableName() . '.status' => 1]);
 *
 * return $this->render('index', [
 *      'dataProvider' => $dataProvider,
 * ]);
 * ```
 *
 * Class SearchModel
 * @package common\components
 * @property \yii\db\ActiveRecord|\yii\base\Model $model
 */
class SearchModel extends Model
{
    private $attributes;
    private $attributeLabels;
    private $internalRelations;
    private $model;
    private $modelClassName;
    private $relationAttributes = [];
    private $rules;
    private $scenarios;

    /**
     * @var string 默认排序
     */
    public $defaultOrder;

    /**
     * @var string 分组
     */
    public $groupBy;

    /**
     * @var int 每页大小
     */
    public $pageSize = 10;

    /**
     * @var array 模糊查询
     */
    public $partialMatchAttributes = [];

    /**
     * @var array
     */
    public $relations = [];

    /**
     * SearchModel constructor.
     * @param $params
     * @throws NotFoundHttpException
     */
    public function __construct($params)
    {
        $this->scenario = 'search';
        parent::__construct($params);
        if ($this->model === null) {
            throw new NotFoundHttpException('Param "model" cannot be empty');
        }

        $this->rules = $this->model->rules();
        $this->scenarios = $this->model->scenarios();
        $this->attributeLabels = $this->model->attributeLabels();
        foreach ($this->safeAttributes() as $attribute) {
            $this->attributes[$attribute] = '';
        }
    }

    /**
     * @param ActiveQuery $query
     * @param string $attribute
     * @param bool $partialMath
     */
    private function addCondition($query, $attribute, $partialMath = false)
    {
        if (isset($this->relationAttributes[$attribute])) {
            $attributeName = $this->relationAttributes[$attribute];
        } else {
            $attributeName = call_user_func([$this->modelClassName, 'tableName']) . '.' . $attribute;
        }

        $value = $this->$attribute;
        if ($value === '') {
            return;
        }

        if ($partialMath) {
            $query->andWhere(['like', $attributeName, trim($value)]);
        } else {
            $query->andWhere($this->conditionTrans($attributeName, $value));
        }
    }

    /**
     * 可以查询大于小于和IN
     *
     * @param $attributeName
     * @param $value
     * @return array
     */
    private function conditionTrans($attributeName, $value)
    {
        switch (true) {
            case is_array($value):
                return [$attributeName => $value];
                break;
            case stripos($value, '>=') !== false:
                return ['>=', $attributeName, substr($value, 2)];
                break;
            case stripos($value, '<=') !== false:
                return ['<=', $attributeName, substr($value, 2)];
                break;
            case stripos($value, '<') !== false:
                return ['<', $attributeName, substr($value, 1)];
                break;
            case stripos($value, '>') !== false:
                return ['>', $attributeName, substr($value, 1)];
                break;
            case stripos($value, ',') !== false:
                return [$attributeName => explode(',', $value)];
                break;
            default:
                return [$attributeName => $value];
                break;
        }
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param mixed $value
     */
    public function setModel($value)
    {
        if ($value instanceof Model) {
            $this->model = $value;
            $this->scenario = $this->model->scenario;
            $this->modelClassName = get_class($value);
        } else {
            $this->model = new $value;
            $this->modelClassName = $value;
        }
    }

    /**
     * @return array
     */
    public function rules()
    {
        return $this->rules;
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return $this->attributeLabels;
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        return $this->scenarios;
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = call_user_func([$this->modelClassName, 'find']);
        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => new Pagination(
                    [
                        'forcePageParam' => false,
                        'pageSize' => $this->pageSize,
                    ]
                ),
            ]
        );

        if (is_array($this->relations)) {
            foreach ($this->relations as $relation => $attributes) {
                $pieces = explode('.', $relation);
                $path = '';
                $parentPath = '';
                foreach ($pieces as $i => $piece) {
                    if ($i == 0) {
                        $path = $piece;
                    } else {
                        $parentPath = $path;
                        $path .= '.' . $piece;
                    }

                    if (!isset($this->internalRelations[$path])) {
                        if ($i == 0) {
                            $relationClass = call_user_func([$this->model, 'get' . $piece]);
                        } else {
                            $className = $this->internalRelations[$parentPath]['className'];
                            $relationClass = call_user_func([new $className, 'get' . $piece]);
                        }

                        $this->internalRelations[$path] = [
                            'className' => $relationClass->modelClass,
                            'tableName' => call_user_func([$relationClass->modelClass, 'tableName']),
                        ];
                    }
                }

                foreach ((array)$attributes as $attribute) {
                    $attributeName = str_replace('.', '_', $relation) . '_' . $attribute;
                    $tableAttribute = $this->internalRelations[$relation]['tableName'] . '.' . $attribute;
                    $this->rules[] = [$attributeName, 'safe'];
                    $this->scenarios[$this->scenario][] = $attributeName;
                    $this->attributes[$attributeName] = '';
                    $this->relationAttributes[$attributeName] = $tableAttribute;
                    $dataProvider->sort->attributes[$attributeName] = [
                        'asc' => [$tableAttribute => SORT_ASC],
                        'desc' => [$tableAttribute => SORT_DESC],
                    ];
                }
            }

            $query->joinWith(array_keys($this->relations));
        }

        if (is_array($this->defaultOrder)) {
            $dataProvider->sort->defaultOrder = $this->defaultOrder;
        }

        if (is_array($this->groupBy)) {
            $query->addGroupBy($this->groupBy);
        }

        $this->load($params);
        foreach ($this->attributes as $name => $value) {
            $this->addCondition($query, $name, in_array($name, $this->partialMatchAttributes));
        }

        return $dataProvider;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws \yii\base\UnknownPropertyException
     */
    public function __get($name)
    {
        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }

        return parent::__get($name);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @throws \yii\base\UnknownPropertyException
     */
    public function __set($name, $value)
    {
        if (isset($this->attributes[$name])) {
            $this->attributes[$name] = $value;
        } else {
            parent::__set($name, $value);
        }
    }
}

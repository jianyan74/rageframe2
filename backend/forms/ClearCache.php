<?php

namespace backend\forms;

use Yii;
use yii\base\Model;

/**
 * Class ClearCache
 * @package backend\forms
 * @author jianyan74 <751393839@qq.com>
 */
class ClearCache extends Model
{
    /**
     * @var int
     */
    public $cache = 1;

    /**
     * @var bool
     */
    protected $status = true;

    public function rules()
    {
        return [
            [['cache'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cache' => '缓存',
        ];
    }

    public function save()
    {
        if ($this->cache == true) {
            $status = Yii::$app->cache->flush();
            !$status && $this->addError('cache', '缓存清理失败');
        }

        return $this->hasErrors() == false;
    }
}
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
    public $cache = 1;
    public $backupCache = 1;

    protected $status = true;

    public function rules()
    {
        return [
            [['cache', 'backupCache'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cache' => '缓存',
            'backupCache' => '备份缓存',
        ];
    }

    public function save()
    {
        if ($this->cache == true) {
            $status = Yii::$app->cache->flush();
            !$status && $this->addError('cache', '缓存清理失败');
        }

        if ($this->backupCache == true) {
            $path = Yii::$app->params['dataBackupPath'];
            $lock = realpath($path) . DIRECTORY_SEPARATOR . Yii::$app->params['dataBackLock'];

            if (file_exists($lock)) {
                $status = array_map("unlink", glob($lock));
                !$status && $this->addError('cache', '备份缓存清理失败');
            }
        }

        return $this->hasErrors() == false;
    }
}
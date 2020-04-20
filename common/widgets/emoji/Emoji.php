<?php

namespace common\widgets\emoji;

use Yii;
use yii\base\Widget;
use common\widgets\emoji\assets\AppAsset;

/**
 * 表情选择器
 *
 * Class Emoji
 * @package common\widgets\emoji
 * @author jianyan74 <751393839@qq.com>
 */
class Emoji extends Widget
{
    /**
     * 主题
     *
     * default 和 wechat 二种类型
     *
     * @var string
     */
    public $theme = 'default';
    /**
     * @var string
     */
    public $name = 'emoji';

    /**
     * @var array
     */
    public $options = [];

    /**
     * 绑定文本域
     *
     * @var string
     */
    public $bind_id = '';

    /**
     * @return string
     */
    public function run()
    {
        $baseUrl = $this->registerClientScript();

        return $this->render('emoji', [
            'name' => $this->name,
            'theme' => $this->theme,
            'options' => $this->options,
            'baseUrl' => $baseUrl,
            'bind_id' => $this->bind_id,
        ]);
    }

    /**
     * 注册资源
     */
    protected function registerClientScript()
    {
        $view = $this->getView();
        AppAsset::register($view);

        return Yii::$app->view->assetBundles[AppAsset::class]->baseUrl . '/';
    }
}
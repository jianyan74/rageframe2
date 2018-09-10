<?php
namespace backend\widgets\provinces;

use yii;
use yii\base\Widget;

/**
 * Class Provinces
 * @package backend\widgets\left
 */
class Provinces extends Widget
{
    /**
     * 省字段名
     *
     * @var
     */
    public $provincesName = 'provinces';

    /**
     * 市字段名
     *
     * @var
     */
    public $cityName = 'city';

    /**
     * 区字段名
     *
     * @var
     */
    public $areaName = 'area';

    /**
     * 显示类型
     *
     * @var string
     */
    public $template = 'long';

    /**
     * 关联的ajax url
     *
     * @var
     */
    public $url;

    /**
     * 模型
     *
     * @var array
     */
    public $model;

    /**
     * 表单
     * @var
     */
    public $form;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        empty($this->url) && $this->url = Yii::$app->urlManager->createUrl(['provinces/index']);
    }

    /**
     * @return string
     */
    public function run()
    {
        return $this->render('index', [
            'form' => $this->form,
            'model' => $this->model,
            'provincesName' => $this->provincesName,
            'cityName' => $this->cityName,
            'areaName' => $this->areaName,
            'url' => $this->url,
            'template' => $this->template,
        ]);
    }
}
?>
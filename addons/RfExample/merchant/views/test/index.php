<?php
use common\helpers\Url;

$this->title = '测试多级控制器';
$this->params['breadcrumbs'][] = $this->title;

echo Url::to(['update']);
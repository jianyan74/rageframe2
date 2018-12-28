<?php
use common\helpers\AddonUrl;

$this->title = '测试多级控制器';
$this->params['breadcrumbs'][] = $this->title;

echo AddonUrl::to(['update']);
<?php

use common\helpers\Html;

$this->title = 'PHP信息';
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-body">
                <div class="col-sm-12">
                    <?php
                    ob_start();
                    phpinfo();
                    $pinfo = ob_get_contents();
                    ob_end_clean();
                    $phpinfo = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $pinfo);
                    $phpinfo = str_replace('<table', '<div class="table-responsive"><table class="table table-condensed table-bordered table-striped table-hover config-php-info-table" ', $phpinfo);
                    $phpinfo = str_replace('</table>', '</table></div>', $phpinfo);
                    echo $phpinfo;
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
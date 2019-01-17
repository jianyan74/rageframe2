<?php

$this->title = '系统信息';
$this->params['breadcrumbs'][] = ['label' =>  $this->title];
?>

<div class="row">
    <div class="col-xs-7">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-cog"></i> 开发环境</h3>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover">
                    <tr>
                        <td>PHP版本</td>
                        <td><?= phpversion(); ?></td>
                    </tr>
                    <tr>
                        <td>Mysql版本</td>
                        <td><?= Yii::$app->db->pdo->getAttribute(\PDO::ATTR_SERVER_VERSION); ?></td>
                    </tr>
                    <tr>
                        <td>解析引擎</td>
                        <td><?= $_SERVER['SERVER_SOFTWARE']; ?></td>
                    </tr>
                    <tr>
                        <td>数据库大小</td>
                        <td><?= Yii::$app->formatter->asShortSize($mysql_size); ?></td>
                    </tr>
                    <tr>
                        <td>附件目录</td>
                        <td><?= Yii::$app->request->hostInfo . Yii::getAlias('@attachurl'); ?>/</td>
                    </tr>
                    <tr>
                        <td>附件目录大小</td>
                        <td><?= Yii::$app->formatter->asShortSize($attachment_size); ?></td>
                    </tr>
                    <tr>
                        <td>文件上传限制</td>
                        <td><?= ini_get('upload_max_filesize'); ?></td>
                    </tr>
                    <tr>
                        <td>超时时间</td>
                        <td><?= ini_get('max_execution_time'); ?>秒</td>
                    </tr>
                    <tr>
                        <td>客户端信息</td>
                        <td><?= $_SERVER['HTTP_USER_AGENT'] ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-xs-5">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-code"></i> 开发系统</h3>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover">
                    <tr>
                        <td width="150px">系统名称</td>
                        <td><?= Yii::$app->params['exploitSysName']?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>为二次开发而生，让开发变得更简单。</td>
                    </tr>
                    <tr>
                        <td>系统版本</td>
                        <td><?= Yii::$app->params['exploitVersions']?></td>
                    </tr>
                    <tr>
                        <td width="150px">Yii2版本</td>
                        <td><?= Yii::getVersion(); ?></td>
                    </tr>
                    <tr>
                        <td>官网</td>
                        <td><?= Yii::$app->params['exploitOfficialWebsite']?></td>
                    </tr>
                    <tr>
                        <td>官方QQ群</td>
                        <td><a href="https://jq.qq.com/?_wv=1027&amp;k=4BeVA2r" target="_blank">655084090</a></td>
                    </tr>
                    <tr>
                        <td>Gitee</td>
                        <td><?= Yii::$app->params['exploitGitee']?></td>
                    </tr>
                    <tr>
                        <td>GitHub</td>
                        <td><?= Yii::$app->params['exploitGitHub']?></td>
                    </tr>
                    <tr>
                        <td>开发者</td>
                        <td><?= Yii::$app->params['exploitName']?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-lemon-o"></i> PHP信息</h3>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover">
                    <tr>
                        <td>PHP执行方式</td>
                        <td><?= php_sapi_name(); ?></td>
                    </tr>
                    <tr>
                        <td>扩展支持</td>
                        <td>
                            <?= extension_loaded('Zend OPcache')
                                ? '<span class="label label-primary">opcache支持</span>'
                                : '<span class="label label-default">opcache不支持</span>'; ?>
                            <?= extension_loaded('memcache')
                                ? '<span class="label label-primary">memcache支持</span>'
                                : '<span class="label label-default">memcache不支持</span>'; ?>
                            <?= extension_loaded('memcached')
                                ? '<span class="label label-primary">memcached支持</span>'
                                : '<span class="label label-default">memcached不支持</span>'; ?>
                            <?= extension_loaded('redis')
                                ? '<span class="label label-primary">redis支持</span>'
                                : '<span class="label label-default">redis不支持</span>'; ?>
                            <?= extension_loaded('swoole')
                                ? '<span class="label label-primary">swoole支持</span>'
                                : '<span class="label label-default">swoole不支持</span>'; ?>
                            <?= extension_loaded('mongodb')
                                ? '<span class="label label-primary">mongodb支持</span>'
                                : '<span class="label label-default">mongodb不支持</span>'; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>禁用的函数</td>
                        <td >
                            <?php if (is_array($disable_functions)){ ?>
                                <?php foreach ($disable_functions as $function){ ?>
                                    <span class="label label-default"><?= $function; ?></span>
                                <?php } ?>
                            <?php }else{ ?>
                                <span class="label label-default"><?= $disable_functions; ?></span>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>脚本内存限制</td>
                        <td><?= ini_get('memory_limit'); ?></td>
                    </tr>
                    <tr>
                        <td>Socket超时时间</td>
                        <td><?= ini_get('default_socket_timeout'); ?> 秒</td>
                    </tr>
                    <tr>
                        <td>POST数据最大尺寸</td>
                        <td><?= ini_get('post_max_size'); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
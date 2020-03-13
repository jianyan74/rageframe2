<?php
/**
 * @var \omnilight\scheduling\Schedule $schedule
 */

$path = Yii::getAlias('@runtime') . '/logs/';

/**
 * 清理过期的微信历史消息记录
 *
 * 每天凌晨执行一次
 */
$filePath = $path . 'msgHistory.log';
$schedule->command('wechat/msg-history/index')->cron('0 0 * * *')->sendOutputTo($filePath);

/**
 * 定时群发微信消息
 *
 * 每分钟执行一次
 */
$filePath = $path . 'sendMessage.log';
$schedule->command('wechat/send-message/index')->cron('* * * * *')->sendOutputTo($filePath);
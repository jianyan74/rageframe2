<?php
return [
    /** ------ 每分钟定时运行脚本;注意关闭 EXEC 系统函数 ------ **/
    'cron' => '* * * * *',
    'cronJobs' => [
        // 清理过期的微信历史消息记录
        'msg-history/index' => [
            'cron' => '0 0 * * *', // 每天凌晨执行一次
            'cron-stdout'=> '/tmp/MsgHistory.log',// 成功日志
            'cron-stderr'=> '/tmp/MsgHistoryError.log',// 错误日志
        ],
        // 定时群发微信消息
        'send-message/index' => [
            'cron' => '* * * * *', // 每分钟执行一次
            'cron-stdout'=> '/tmp/sendMessage.log',// 成功日志
            'cron-stderr'=> '/tmp/sendMessageError.log',// 错误日志
        ],
        //......更多的定时任务
    ],
];

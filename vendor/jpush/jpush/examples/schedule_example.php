<?php
// 这只是使用样例不应该直接用于实际生产环境中 !!

require 'config.php';

$payload = $client->push()
    ->setPlatform("all")
    ->addAllAudience()
    ->setNotificationAlert("Hi, 这是一条定时发送的消息")
    ->build();

// 创建一个2016-12-22 13:45:00触发的定时任务
$response = $client->schedule()->createSingleSchedule("每天14点发送的定时任务", $payload, array("time"=>"2016-12-22 13:45:00"));
print_r($response);

// 创建一个每天14点发送的定时任务
$response = $client->schedule()->createPeriodicalSchedule("每天14点发送的定时任务", $payload,
        array(
            "start"=>"2016-12-22 13:45:00",
            "end"=>"2016-12-25 13:45:00",
            "time"=>"14:00:00",
            "time_unit"=>"DAY",
            "frequency"=>1
        ));
print_r($response);


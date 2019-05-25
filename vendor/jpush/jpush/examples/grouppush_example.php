<?php
require __DIR__ . '/../autoload.php';

use JPush\Client as JPush;

$group_key = 'xxxx';
$group_master_secret = 'xxxx';

$client = new JPush('group-' . $group_key, $group_master_secret, null);

$push_payload = $client->push()
    ->setPlatform('all')
    ->addAllAudience()
    ->setNotificationAlert('Hi, JPush');
try {
    $response = $push_payload->send();
    print_r($response);
} catch (\JPush\Exceptions\APIConnectionException $e) {
    // try something here
    print $e;
} catch (\JPush\Exceptions\APIRequestException $e) {
    // try something here
    print $e;
}

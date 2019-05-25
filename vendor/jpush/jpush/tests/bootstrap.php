<?php
use JPush\Client;

$app_key = getenv('app_key');
$master_secret = getenv('master_secret');
$client = new Client($app_key, $master_secret);

$registration_id = getenv('registration_id');

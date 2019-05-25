<?php

require 'config.php';

$response = $client->push()->getCid();

print_r($response);

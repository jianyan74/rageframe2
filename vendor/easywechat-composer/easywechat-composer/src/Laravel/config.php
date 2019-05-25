<?php

declare(strict_types=1);

/*
 * This file is part of the EasyWeChatComposer.
 *
 * (c) mingyoung <mingyoungcheung@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

return [

    'encryption' => [

        'key' => env('EASYWECHAT_KEY'),

    ],

    'delegation' => [

        'enabled' => env('EASYWECHAT_DELEGATION', false),

        'host' => env('EASYWECHAT_DELEGATION_HOST'),
    ],

];

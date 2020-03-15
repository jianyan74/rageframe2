<?php
/**
 * The manifest of files that are local to specific environment.
 * This file returns a list of environments that the application
 * may be installed under. The returned data must be in the following
 * format:
 *
 * ```php
 * return [
 *     'environment name' => [
 *         'path' => 'directory storing the local files',
 *         'skipFiles'  => [
 *             // list of files that should only copied once and skipped if they already exist
 *         ],
 *         'setWritable' => [
 *             // list of directories that should be set writable
 *         ],
 *         'setExecutable' => [
 *             // list of files that should be set executable
 *         ],
 *         'setCookieValidationKey' => [
 *             // list of config files that need to be inserted with automatically generated cookie validation keys
 *         ],
 *         'createSymlink' => [
 *             // list of symlinks to be created. Keys are symlinks, and values are the targets.
 *         ],
 *     ],
 * ];
 * ```
 */
return [
    'Development' => [
        'path' => 'dev',
        'setWritable' => [
            'backend/runtime',
            'frontend/runtime',
            'html5/runtime',
            'api/runtime',
            'oauth2/runtime',
            'merchant/runtime',
            'merapi/runtime',
            '/web/assets',
            '/web/backend/assets',
            '/web/html5/assets',
            '/web/api/assets',
            '/web/oauth2/assets',
            '/web/merchant/assets',
            '/web/merapi/assets',
            'web/attachment',
            'addons',
        ],
        'setExecutable' => [
            'yii',
            'yii_test',
        ],
        'setCookieValidationKey' => [
            'backend/config/main-local.php',
            'frontend/config/main-local.php',
            'html5/config/main-local.php',
            'api/config/main-local.php',
            'oauth2/config/main-local.php',
            'merchant/config/main-local.php',
            'merapi/config/main-local.php',
        ],
    ],
    'Production' => [
        'path' => 'prod',
        'setWritable' => [
            'backend/runtime',
            'frontend/runtime',
            'html5/runtime',
            'api/runtime',
            'oauth2/runtime',
            'merchant/runtime',
            'merapi/runtime',
            '/web/assets',
            '/web/backend/assets',
            '/web/html5/assets',
            '/web/api/assets',
            '/web/oauth2/assets',
            '/web/merchant/assets',
            '/web/merapi/assets',
            'web/attachment',
            'addons',
        ],
        'setExecutable' => [
            'yii',
        ],
        'setCookieValidationKey' => [
            'backend/config/main-local.php',
            'frontend/config/main-local.php',
            'html5/config/main-local.php',
            'api/config/main-local.php',
            'oauth2/config/main-local.php',
            'merchant/config/main-local.php',
            'merapi/config/main-local.php',
        ],
    ],
];

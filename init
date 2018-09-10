#!/usr/bin/env php
<?php
/**
 * Yii Application Initialization Tool
 *
 * In order to run in non-interactive mode:
 *
 * init --env=Development --overwrite=n
 */

if (!extension_loaded('openssl')) {
    die('The OpenSSL PHP extension is required by Yii2.');
}

$params = getParams();
$root = str_replace('\\', '/', __DIR__);
$envs = require "$root/environments/index.php";
$envNames = array_keys($envs);

echo "Yii Application Initialization Tool v1.0\n\n";

$envName = null;
if (empty($params['env']) || $params['env'] === '1') {
    echo "Which environment do you want the application to be initialized in?\n\n";
    foreach ($envNames as $i => $name) {
        echo "  [$i] $name\n";
    }
    echo "\n  Your choice [0-" . (count($envs) - 1) . ', or "q" to quit] ';
    $answer = trim(fgets(STDIN));

    if (!ctype_digit($answer) || !in_array($answer, range(0, count($envs) - 1))) {
        echo "\n  Quit initialization.\n";
        exit(0);
    }

    if (isset($envNames[$answer])) {
        $envName = $envNames[$answer];
    }
} else {
    $envName = $params['env'];
}

if (!in_array($envName, $envNames)) {
    $envsList = implode(', ', $envNames);
    echo "\n  $envName is not a valid environment. Try one of the following: $envsList. \n";
    exit(2);
}

$env = $envs[$envName];

if (empty($params['env'])) {
    echo "\n  Initialize the application under '{$envNames[$answer]}' environment? [yes|no] ";
    $answer = trim(fgets(STDIN));
    if (strncasecmp($answer, 'y', 1)) {
        echo "\n  Quit initialization.\n";
        exit(0);
    }
}

echo "\n  Start initialization ...\n\n";
$files = getFileList("$root/environments/{$env['path']}");
if (isset($env['skipFiles'])) {
    $skipFiles = $env['skipFiles'];
    array_walk($skipFiles, function(&$value) use($env, $root) { $value = "$root/$value"; });
    $files = array_diff($files, array_intersect_key($env['skipFiles'], array_filter($skipFiles, 'file_exists')));
}
$all = false;
foreach ($files as $file) {
    if (!copyFile($root, "environments/{$env['path']}/$file", $file, $all, $params)) {
        break;
    }
}

$callbacks = ['setCookieValidationKey', 'setWritable', 'setExecutable', 'createSymlink'];
foreach ($callbacks as $callback) {
    if (!empty($env[$callback])) {
        $callback($root, $env[$callback]);
    }
}

echo "\n  ... initialization completed.\n\n";

function getFileList($root, $basePath = '')
{
    $files = [];
    $handle = opendir($root);
    while (($path = readdir($handle)) !== false) {
        if ($path === '.git' || $path === '.svn' || $path === '.' || $path === '..') {
            continue;
        }
        $fullPath = "$root/$path";
        $relativePath = $basePath === '' ? $path : "$basePath/$path";
        if (is_dir($fullPath)) {
            $files = array_merge($files, getFileList($fullPath, $relativePath));
        } else {
            $files[] = $relativePath;
        }
    }
    closedir($handle);
    return $files;
}

function copyFile($root, $source, $target, &$all, $params)
{
    if (!is_file($root . '/' . $source)) {
        echo "       skip $target ($source not exist)\n";
        return true;
    }
    if (is_file($root . '/' . $target)) {
        if (file_get_contents($root . '/' . $source) === file_get_contents($root . '/' . $target)) {
            echo "  unchanged $target\n";
            return true;
        }
        if ($all) {
            echo "  overwrite $target\n";
        } else {
            echo "      exist $target\n";
            echo "            ...overwrite? [Yes|No|All|Quit] ";


            $answer = !empty($params['overwrite']) ? $params['overwrite'] : trim(fgets(STDIN));
            if (!strncasecmp($answer, 'q', 1)) {
                return false;
            } else {
                if (!strncasecmp($answer, 'y', 1)) {
                    echo "  overwrite $target\n";
                } else {
                    if (!strncasecmp($answer, 'a', 1)) {
                        echo "  overwrite $target\n";
                        $all = true;
                    } else {
                        echo "       skip $target\n";
                        return true;
                    }
                }
            }
        }
        file_put_contents($root . '/' . $target, file_get_contents($root . '/' . $source));
        return true;
    }
    echo "   generate $target\n";
    @mkdir(dirname($root . '/' . $target), 0777, true);
    file_put_contents($root . '/' . $target, file_get_contents($root . '/' . $source));
    return true;
}

function getParams()
{
    $rawParams = [];
    if (isset($_SERVER['argv'])) {
        $rawParams = $_SERVER['argv'];
        array_shift($rawParams);
    }

    $params = [];
    foreach ($rawParams as $param) {
        if (preg_match('/^--(\w+)(=(.*))?$/', $param, $matches)) {
            $name = $matches[1];
            $params[$name] = isset($matches[3]) ? $matches[3] : true;
        } else {
            $params[] = $param;
        }
    }
    return $params;
}

function setWritable($root, $paths)
{
    foreach ($paths as $writable) {
        if (is_dir("$root/$writable")) {
            if (@chmod("$root/$writable", 0777)) {
                echo "      chmod 0777 $writable\n";
            } else {
                printError("Operation chmod not permitted for directory $writable.");
            }
        } else {
            printError("Directory $writable does not exist.");
        }
    }
}

function setExecutable($root, $paths)
{
    foreach ($paths as $executable) {
        if (file_exists("$root/$executable")) {
            if (@chmod("$root/$executable", 0755)) {
                echo "      chmod 0755 $executable\n";
            } else {
                printError("Operation chmod not permitted for $executable.");
            }
        } else {
            printError("$executable does not exist.");
        }
    }
}

function setCookieValidationKey($root, $paths)
{
    foreach ($paths as $file) {
        echo "   generate cookie validation key in $file\n";
        $file = $root . '/' . $file;
        $length = 32;
        $bytes = openssl_random_pseudo_bytes($length);
        $key = strtr(substr(base64_encode($bytes), 0, $length), '+/=', '_-.');
        $content = preg_replace('/(("|\')cookieValidationKey("|\')\s*=>\s*)(""|\'\')/', "\\1'$key'", file_get_contents($file));
        file_put_contents($file, $content);
    }
}

function createSymlink($root, $links)
{
    foreach ($links as $link => $target) {
        //first removing folders to avoid errors if the folder already exists
        @rmdir($root . "/" . $link);
        //next removing existing symlink in order to update the target
        if (is_link($root . "/" . $link)) {
            @unlink($root . "/" . $link);
        }
        if (@symlink($root . "/" . $target, $root . "/" . $link)) {
            echo "      symlink $root/$target $root/$link\n";
        } else {
            printError("Cannot create symlink $root/$target $root/$link.");
        }
    }
}

/**
 * Prints error message.
 * @param string $message message
 */
function printError($message)
{
    echo "\n  " . formatMessage("Error. $message", ['fg-red']) . " \n";
}

/**
 * Returns true if the stream supports colorization. ANSI colors are disabled if not supported by the stream.
 *
 * - windows without ansicon
 * - not tty consoles
 *
 * @return boolean true if the stream supports ANSI colors, otherwise false.
 */
function ansiColorsSupported()
{
    return DIRECTORY_SEPARATOR === '\\'
        ? getenv('ANSICON') !== false || getenv('ConEmuANSI') === 'ON'
        : function_exists('posix_isatty') && @posix_isatty(STDOUT);
}

/**
 * Get ANSI code of style.
 * @param string $name style name
 * @return integer ANSI code of style.
 */
function getStyleCode($name)
{
    $styles = [
        'bold' => 1,
        'fg-black' => 30,
        'fg-red' => 31,
        'fg-green' => 32,
        'fg-yellow' => 33,
        'fg-blue' => 34,
        'fg-magenta' => 35,
        'fg-cyan' => 36,
        'fg-white' => 37,
        'bg-black' => 40,
        'bg-red' => 41,
        'bg-green' => 42,
        'bg-yellow' => 43,
        'bg-blue' => 44,
        'bg-magenta' => 45,
        'bg-cyan' => 46,
        'bg-white' => 47,
    ];
    return $styles[$name];
}

/**
 * Formats message using styles if STDOUT supports it.
 * @param string $message message
 * @param string[] $styles styles
 * @return string formatted message.
 */
function formatMessage($message, $styles)
{
    if (empty($styles) || !ansiColorsSupported()) {
        return $message;
    }

    return sprintf("\x1b[%sm", implode(';', array_map('getStyleCode', $styles))) . $message . "\x1b[0m";
}

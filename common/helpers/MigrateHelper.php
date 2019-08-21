<?php

namespace common\helpers;

use Yii;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\db\MigrationInterface;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class MigrateHelper
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class MigrateHelper
{
    /**
     * 最长数据迁移数量
     */
    const MAX_NAME_LENGTH = 180;

    /**
     * 输出安装过程
     *
     * @var bool
     */
    protected static $compact = false;

    /**
     * 目录
     *
     * @addons/RfHelpers/console/migrations
     *
     * @var array
     */
    protected static $migrationPath = [];

    /**
     * 命名空间
     *
     * @var array
     */
    protected static $migrationNamespaces = [];

    /**
     * 数据迁移过程
     *
     * @var array
     */
    protected static $info = [];

    /**
     * 根据路径执行数据迁移
     *
     * @param array $path
     * @param bool $compact
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     * @throws UnprocessableEntityHttpException
     */
    public static function upByPath(array $path, $compact = false)
    {
        self::$migrationPath = $path;
        self::$compact = $compact;

        if (empty(self::$migrationPath)) {
            throw new InvalidConfigException('At least one of `migrationPath` should be specified.');
        }

        foreach (self::$migrationPath as $i => $path) {
            self::$migrationPath[$i] = Yii::getAlias($path);
        }

        return self::up();
    }

    /**
     *
     * 根据命名空间执行数据迁移
     *
     * @param array $namespaces
     * @param bool $compact
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     * @throws UnprocessableEntityHttpException
     */
    public static function upByNamespaces(array $namespaces, $compact = false)
    {
        self::$migrationNamespaces = $namespaces;
        self::$compact = $compact;

        if (empty(self::$migrationNamespaces)) {
            throw new InvalidConfigException('At least one of `migrationNamespaces` should be specified.');
        }

        foreach (self::$migrationNamespaces as $key => $value) {
            self::$migrationNamespaces[$key] = trim($value, '\\');
        }

        return self::up();
    }

    /**
     * 根据路径执行数据迁移
     *
     * @param array $path
     * @param bool $compact
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     * @throws UnprocessableEntityHttpException
     */
    public static function downByPath(array $path, $compact = false)
    {
        self::$migrationPath = $path;
        self::$compact = $compact;

        if (empty(self::$migrationPath)) {
            throw new InvalidConfigException('At least one of `migrationPath` should be specified.');
        }

        foreach (self::$migrationPath as $i => $path) {
            self::$migrationPath[$i] = Yii::getAlias($path);
        }

        return self::down();
    }

    /**
     *
     * 根据命名空间执行数据迁移
     *
     * @param array $namespaces
     * @param bool $compact
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     * @throws UnprocessableEntityHttpException
     */
    public static function downByNamespaces(array $namespaces, $compact = false)
    {
        self::$migrationNamespaces = $namespaces;
        self::$compact = $compact;

        if (empty(self::$migrationNamespaces)) {
            throw new InvalidConfigException('At least one of `migrationNamespaces` should be specified.');
        }

        foreach (self::$migrationNamespaces as $key => $value) {
            self::$migrationNamespaces[$key] = trim($value, '\\');
        }

        return self::down();
    }

    /**
     * @param int $limit
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     * @throws UnprocessableEntityHttpException
     */
    protected static function up($limit = 0)
    {
        $migrations = self::getNewMigrations();
        if (empty($migrations)) {
            throw new NotFoundHttpException('找不到可用的数据迁移');
        }

        $limit = (int)$limit;
        if ($limit > 0) {
            $migrations = array_slice($migrations, 0, $limit);
        }

        foreach ($migrations as $migration) {
            $nameLimit = static::MAX_NAME_LENGTH;
            if ($nameLimit !== null && strlen($migration) > $nameLimit) {
                throw new UnprocessableEntityHttpException("The migration name '$migration' is too long. Its not possible to apply this migration.");
            }
        }

        $applied = 0;
        foreach ($migrations as $migration) {
            if (!self::migrateUp($migration)) {
                throw new UnprocessableEntityHttpException( $migration . '迁移失败了。其余的迁移被取消');
            }
            $applied++;
        }

        return self::$info;
    }

    /**
     * @return array
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     * @throws UnprocessableEntityHttpException
     */
    protected static function down()
    {
        $migrations = self::getNewMigrations();
        if (empty($migrations)) {
            throw new NotFoundHttpException('找不到可用的数据迁移');
        }

        $reverted = 0;
        foreach ($migrations as $migration) {
            if (!self::migrateDown($migration)) {
                throw new UnprocessableEntityHttpException( $migration . '迁移失败了。其余的迁移被取消');
            }
            $reverted++;
        }

        self::$info[] = "Migrated down successfully.";

        return self::$info;
    }

    /**
     * @param $class
     * @return bool
     * @throws InvalidConfigException
     */
    protected static function migrateUp($class)
    {
        self::$info[] = "*** applying $class";

        // 打开输出缓冲区并获取内容
        ob_start();
        $start = microtime(true);
        $migration = self::createMigration($class);
        if ($migration->up() !== false) {
            $tmpInfo = explode('>', ob_get_contents());
            foreach ($tmpInfo as $item) {
                !empty(trim($item)) && self::$info[] = $item;
            }

            $time = microtime(true) - $start;
            self::$info[] = "*** applied $class (time: " . sprintf('%.3f', $time) . "s)";

            ob_end_clean();
            return true;
        }

        ob_end_clean();

        $time = microtime(true) - $start;
        self::$info[] = "*** failed to apply $class (time: " . sprintf('%.3f', $time) . "s)n";

        return false;
    }

    /**
     * @param $class
     * @return bool
     * @throws InvalidConfigException
     */
    protected static function migrateDown($class)
    {
        self::$info[] = "*** reverting $class";
        $start = microtime(true);

        // 打开输出缓冲区并获取内容
        ob_start();

        $migration = self::createMigration($class);
        if ($migration->down() !== false) {
            $time = microtime(true) - $start;
            self::$info[] = "*** reverted $class (time: " . sprintf('%.3f', $time) . "s)";

            ob_end_clean();
            return true;
        }

        ob_end_clean();

        $time = microtime(true) - $start;
        self::$info[] = "*** failed to revert $class (time: " . sprintf('%.3f', $time) . "s)";

        return false;
    }

    /**
     * @param $class
     * @return MigrationInterface
     * @throws InvalidConfigException
     */
    protected  static function createMigration($class)
    {
        self::includeMigrationFile($class);

        /** @var MigrationInterface $migration */
        $migration = Yii::createObject($class);
        if ($migration instanceof BaseObject && $migration->canSetProperty('compact')) {
            $migration->compact = self::$compact;
        }

        return $migration;
    }

    /**
     * 包含给定迁移类名称的迁移文件
     *
     * @param $class
     */
    protected static function includeMigrationFile($class)
    {
        $class = trim($class, '\\');
        if (strpos($class, '\\') === false) {
            if (is_array(self::$migrationPath)) {
                foreach (self::$migrationPath as $path) {
                    $file = $path . DIRECTORY_SEPARATOR . $class . '.php';
                    if (is_file($file)) {
                        require_once $file;
                        break;
                    }
                }
            } else {
                $file = self::$migrationPath . DIRECTORY_SEPARATOR . $class . '.php';
                require_once $file;
            }
        }
    }

    /**
     * 获取数据迁移文件
     *
     * @return array
     */
    protected static function getNewMigrations()
    {
        $migrationPaths = [];
        if (is_array(self::$migrationPath)) {
            foreach (self::$migrationPath as $path) {
                $migrationPaths[] = [$path, ''];
            }
        } elseif (!empty(self::$migrationPath)) {
            $migrationPaths[] = [self::$migrationPath, ''];
        }

        foreach (self::$migrationNamespaces as $namespace) {
            $migrationPaths[] = [self::getNamespacePath($namespace), $namespace];
        }

        $migrations = [];
        foreach ($migrationPaths as $item) {
            list($migrationPath, $namespace) = $item;
            if (!file_exists($migrationPath)) {
                continue;
            }
            $handle = opendir($migrationPath);
            while (($file = readdir($handle)) !== false) {
                if ($file === '.' || $file === '..') {
                    continue;
                }
                $path = $migrationPath . DIRECTORY_SEPARATOR . $file;
                if (preg_match('/^(m(\d{6}_?\d{6})\D.*?)\.php$/is', $file, $matches) && is_file($path)) {
                    $class = $matches[1];
                    if (!empty($namespace)) {
                        $class = $namespace . '\\' . $class;
                    }
                    $time = str_replace('_', '', $matches[2]);
                    $migrations[$time . '\\' . $class] = $class;
                }
            }
            closedir($handle);
        }
        ksort($migrations);

        return array_values($migrations);
    }

    /**
     * 根据命名空间获取路径
     *
     * @param $namespace
     * @return mixed
     */
    private static function getNamespacePath($namespace)
    {
        return str_replace('/', DIRECTORY_SEPARATOR, Yii::getAlias('@' . str_replace('\\', '/', $namespace)));
    }
}
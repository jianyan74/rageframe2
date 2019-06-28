<?php
namespace backend\modules\sys\controllers;

use Yii;
use common\helpers\ArrayHelper;
use common\helpers\ResultDataHelper;
use backend\modules\sys\forms\Database;
use backend\controllers\BaseController;

/**
 * 数据备份还原
 *
 * Class DataBaseController
 * @package backend\modules\sys\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class DataBaseController extends BaseController
{
    /**
     * 存储路径
     *
     * @var
     */
    public $path;
    /**
     * 配置信息
     *
     * @var
     */
    public $config;

    public function init()
    {
        $this->path = Yii::$app->params['dataBackupPath'];
        // 读取备份配置
        $this->config = [
            'path' => realpath($this->path) . DIRECTORY_SEPARATOR,
            'part' => Yii::$app->params['dataBackPartSize'],
            'compress' => Yii::$app->params['dataBackCompress'],
            'level' => Yii::$app->params['dataBackCompressLevel'],
            'lock' => Yii::$app->params['dataBackLock'],
        ];

        // 判断目测是否存在，不存在则创建
        if (!is_dir($this->path)) {
            mkdir($this->path, 0755, true);
        }
    }

    /**
     * 备份列表
     *
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionBackups()
    {
        $models = Yii::$app->db->createCommand('SHOW TABLE STATUS')->queryAll();
        $models = array_map('array_change_key_case', $models);

        return $this->render('backups', [
            'models' => $models
        ]);
    }

    /**
     * 备份检测
     *
     * @return array
     */
    public function actionExport()
    {
        $tables = Yii::$app->request->post('tables');
        if (empty($tables)) {
            return ResultDataHelper::json(404, '请选择要备份的表');
        }

        // 读取备份配置
        $config = $this->config;

        // 检查是否有正在执行的任务
        $lock = $config['path'] . $config['lock'];
        if (is_file($lock)) {
            return ResultDataHelper::json(404, '检测到有一个备份任务正在执行，请稍后或清理缓存后再试');
        }

        // 创建锁文件
        file_put_contents($lock, time());

        // 检查备份目录是否可写
        if (!is_writeable($config['path'])) {
            return ResultDataHelper::json(404, '备份目录不存在或不可写，请检查后重试！');
        }

        // 生成备份文件信息
        $file = [
            'name' => date('Ymd-His', time()),
            'part' => 1,
        ];

        // 创建备份文件
        $Database = new Database($file, $config);
        if (false !== $Database->create()) {
            // 缓存配置信息
            Yii::$app->session->set('backup_config', $config);
            // 缓存文件信息
            Yii::$app->session->set('backup_file', $file);
            // 缓存要备份的表
            Yii::$app->session->set('backup_tables', $tables);

            $tab = ['id' => 0, 'start' => 0];

            return ResultDataHelper::json(200, '初始化成功！', [
                'tables' => $tables,
                'tab' => $tab
            ]);
        }

        return ResultDataHelper::json(404, '初始化失败，备份文件创建失败！');
    }

    /**
     * 开始备份
     *
     * @return array
     * @throws \yii\db\Exception
     */
    public function actionExportStart()
    {
        $tables = Yii::$app->session->get('backup_tables');
        $file = Yii::$app->session->get('backup_file');
        $config = Yii::$app->session->get('backup_config');

        $id = Yii::$app->request->post('id');
        $start = Yii::$app->request->post('start');

        // 备份指定表
        $database = new Database($file,$config);
        $start = $database->backup($tables[$id], $start);
        if ($start === false) {
            return ResultDataHelper::json(404, '备份出错！');
        } elseif ($start === 0) {
            // 下一表
            if (isset($tables[++$id])) {
                $tab = ['id' => $id, 'start' => 0];
                return ResultDataHelper::json(200, '备份完成', [
                    'tablename' => $tables[--$id],
                    'achieveStatus' => 0,
                    'tab' => $tab,
                ]);
            }

            // 备份完成，清空缓存
            unlink($config['path'] . $config['lock']);
            Yii::$app->session->set('backup_tables', null);
            Yii::$app->session->set('backup_file', null);
            Yii::$app->session->set('backup_config', null);
            return ResultDataHelper::json(200, '备份完成', [
                'tablename' => $tables[--$id],
                'achieveStatus' => 1
            ]);
        } else {
            $tab = ['id' => $id, 'start' => $start[0]];
            $rate = floor(100 * ($start[0] / $start[1]));
            // 对下一个表进行备份
            return ResultDataHelper::json(200, "正在备份...({$rate}%)", [
                'tablename' => $tables[$id],
                'achieveStatus' => 0,
                'tab' => $tab,
            ]);
        }
    }

    /**
     * 优化表
     *
     * @return array
     * @param String|array $tables 表名
     * @throws \yii\db\Exception
     */
    public function actionOptimize()
    {
        $tables = Yii::$app->request->post('tables', '');
        if (!$tables) {
            return ResultDataHelper::json(404, '请指定要优化的表！');
        }

        // 判断是否是数组
        if (is_array($tables)) {
            $tables = implode('`,`', $tables);
            if (Yii::$app->db->createCommand("OPTIMIZE TABLE `{$tables}`")->queryAll()) {
                return ResultDataHelper::json(200, '数据表优化完成');
            }

            return ResultDataHelper::json(404, '数据表优化出错请重试！');
        }

        $list = Yii::$app->db->createCommand("REPAIR TABLE `{$tables}`")->queryOne();
        // 判断是否成功
        if ($list['Msg_text'] == "OK") {
            return ResultDataHelper::json(200, "数据表'{$tables}'优化完成！");
        }

        return ResultDataHelper::json(404, "数据表'{$tables}'优化出错！错误信息:" . $list['Msg_text']);
    }

    /**
     * 修复表
     *
     * @return array
     * @param String|array $tables 表名
     * @throws \yii\db\Exception
     */
    public function actionRepair()
    {
        $tables = Yii::$app->request->post('tables', '');
        if (!$tables) {
            return ResultDataHelper::json(404, '请指定要修复的表！');
        }

        // 判断是否是数组
        if (is_array($tables)) {
            $tables = implode('`,`', $tables);
            if (Yii::$app->db->createCommand("REPAIR TABLE `{$tables}`")->queryAll()) {
                return ResultDataHelper::json(200, '数据表修复化完成');
            }

            return ResultDataHelper::json(404, '数据表修复出错请重试！');
        }

        $list = Yii::$app->db->createCommand("REPAIR TABLE `{$tables}`")->queryOne();
        if ($list['Msg_text'] == "OK")
        {
            return ResultDataHelper::json(200, "数据表'{$tables}'修复完成！");
        }

        return ResultDataHelper::json(404, "数据表'{$tables}'修复出错！错误信息:" . $list['Msg_text']);
    }

    /********************************************************************************/
    /************************************还原数据库************************************/
    /********************************************************************************/

    /**
     * 还原列表
     */
    public function actionRestore()
    {
        Yii::$app->language = "";

        // 文件夹路径
        $path = $this->path;
        $flag = \FilesystemIterator::KEY_AS_FILENAME;
        $glob = new \FilesystemIterator($path, $flag);

        $list = [];
        foreach ($glob as $name => $file) {
            // 正则匹配文件名
            if (preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql(?:\.gz)?$/', $name)) {
                $name = sscanf($name, '%4s%2s%2s-%2s%2s%2s-%d');

                $date = "{$name[0]}-{$name[1]}-{$name[2]}";
                $time = "{$name[3]}:{$name[4]}:{$name[5]}";
                $part = $name[6];

                if (isset($list["{$date} {$time}"])) {
                    $info = $list["{$date} {$time}"];
                    $info['part'] = max($info['part'], $part);
                    $info['size'] = $info['size'] + $file->getSize();
                } else {
                    $info['part'] = $part;
                    $info['size'] = $file->getSize();
                }

                $extension = strtoupper(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
                $info['compress'] = ($extension === 'SQL') ? '-' : $extension;
                $info['time'] = strtotime("{$date} {$time}");
                $info['filename'] = $file->getBasename();
                $list["{$date} {$time}"] = $info;
            }
        }

        krsort($list);

        return $this->render('restore', [
            'list' => $list
        ]);
    }

    /**
     * 初始化还原
     */
    public function actionRestoreInit()
    {
        $time = Yii::$app->request->post('time');

        $config = $this->config;
        // 获取备份文件信息
        $name = date('Ymd-His', $time) . '-*.sql*';
        $path = realpath($config['path']) . DIRECTORY_SEPARATOR . $name;
        $files = glob($path);

        $list = [];
        $size = 0;
        foreach($files as $name => $file) {
            $size += filesize($file);
            $basename = basename($file);
            $match = sscanf($basename, '%4s%2s%2s-%2s%2s%2s-%d');
            $gz = preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql.gz$/', $basename);
            $list[$match[6]] = array($match[6], $file, $gz);
        }
        // 排序数组
        ksort($list);

        // 检测文件正确性
        $last = end($list);
        if (count($list) === $last[0]) {
            // 缓存备份列表
            Yii::$app->session->set('backup_list', $list);
            return ResultDataHelper::json(200, '初始化完成', [
                'part' => 1,
                'start' => 0,
            ]);
        }

        return ResultDataHelper::json(200, "备份文件可能已经损坏，请检查！");
    }

    /**
     * 开始还原到数据库
     *
     * @return array
     * @throws \yii\db\Exception
     */
    public function actionRestoreStart()
    {
        set_time_limit(0);

        $config = $this->config;
        $part  = Yii::$app->request->post('part');
        $start = Yii::$app->request->post('start');

        $list = Yii::$app->session->get('backup_list');
        $arr = [
            'path' => realpath($config['path']) . DIRECTORY_SEPARATOR,
            'compress' => $list[$part][2]
        ];

        $db = new Database($list[$part],$arr);
        $start = $db->import($start);

        if ($start === false) {
            return ResultDataHelper::json(200, "备份文件可能已经损坏，请检查！");
        } elseif ($start === 0) {
            // 下一卷
            if (isset($list[++$part])) {
                return ResultDataHelper::json(200, "正在还原...#{$part}", [
                    'part' => $part,
                    'start1' => $start,
                    'start' => 0,
                    'achieveStatus' => 0,
                ]);
            }

            Yii::$app->session->set('backup_list', null);
            return ResultDataHelper::json(200, "还原完成");
        } else {
            if ($start[1]) {
                $rate = floor(100 * ($start[0] / $start[1]));
                return ResultDataHelper::json(200, "正在还原...#{$part} ({$rate}%)", [
                    'part' => $part,
                    'start' => $start[0],
                    'achieveStatus' => 0,
                ]);
            }

            return ResultDataHelper::json(200, "正在还原...#{$part}", [
                'part' => $part,
                'start' => $start[0],
                'gz' => 1,
                'start1' => $start,
                'achieveStatus' => 0,
            ]);
        }
    }

    /**
     * 删除文件
     */
    public function actionDelete($time)
    {
        $config = $this->config;

        $name = date('Ymd-His', $time) . '-*.sql*';
        $path = realpath($config['path']) . DIRECTORY_SEPARATOR . $name;
        array_map("unlink", glob($path));
        if (count(glob($path))) {
            return $this->message('文件删除失败，请检查权限!', $this->redirect(['restore']), 'error');
        }

        return $this->message('文件删除成功', $this->redirect(['restore']));
    }

    /**
     * 数据字典
     *
     * @return array
     * @throws \yii\db\Exception
     */
    public function actionDataDictionary()
    {
        // 获取全部表结构信息
        $tableSchema = Yii::$app->db->schema->getTableSchemas();
        $tableSchema = ArrayHelper::toArray($tableSchema);

        // 获取全部表信息
        $tables = Yii::$app->db->createCommand('SHOW TABLE STATUS')->queryAll();
        $tables = array_map('array_change_key_case', $tables);

        $tableSchemas = [];
        foreach ($tableSchema as $item) {
            $key = $item['name'];

            $tableSchemas[$key]['table_name'] = $key;// 表名
            $tableSchemas[$key]['item'] = [];

            foreach ($item['columns'] as $column) {
                $tmpArr = [];
                $tmpArr['name'] = $column['name']; // 字段名称
                $tmpArr['type'] = $column['dbType']; // 类型
                $tmpArr['defaultValue'] = $column['defaultValue']; // 默认值
                $tmpArr['comment'] = $column['comment']; // 注释
                $tmpArr['isPrimaryKey'] = $column['isPrimaryKey']; // 是否主键
                $tmpArr['autoIncrement'] = $column['autoIncrement']; // 是否自动增长
                $tmpArr['unsigned'] = $column['unsigned']; // 是否无符号
                $tmpArr['allowNull'] = $column['allowNull']; // 是否允许为空

                $tableSchemas[$key]['item'][] = $tmpArr;
                unset($tmpArr);
            }
        }


        /*--------------- 开始生成 --------------*/
        $str = '';
        $i = 0;
        foreach ($tableSchemas as $key => $datum) {
            $table_comment = $tables[$i]['comment'];

            $str .= "### {$table_comment} : {$key}" . "<br>";
            $str .= "字段 | 类型 | 允许为空 | 默认值 | 字段说明" . "<br>";
            $str .= "---|---|---|---|---" . "<br>";

            foreach ($datum['item'] as $item) {
                empty($item['comment']) && $item['comment'] = "无";
                $item['allowNull'] = !empty($item['allowNull']) ? "是" : '否';
                $str .= "{$item['name']} | {$item['type']} | {$item['allowNull']} | {$item['defaultValue']} | {$item['comment']}" . "<br>";
            }

            $str .= "<br>";
            $i++;
        }

        return ResultDataHelper::json(200, '返回成功', ['str' => $str]) ;
    }
}
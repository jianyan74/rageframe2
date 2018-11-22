<?php
namespace backend\modules\sys\controllers;

use Yii;
use common\helpers\FileHelper;
use common\helpers\ResultDataHelper;
use backend\components\SystemInfo;

/**
 * 服务器信息控制器
 *
 * Class SystemController
 * @package backend\modules\sys\controllers
 */
class SystemController extends SController
{
    /**
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionInfo()
    {
        $db = Yii::$app->db;
        $models = $db->createCommand('SHOW TABLE STATUS')->queryAll();
        $models = array_map('array_change_key_case', $models);

        // 数据库大小
        $mysql_size = 0;
        foreach ($models as $model)
        {
            $mysql_size += $model['data_length'];
        }

        // 禁用函数
        $disable_functions = ini_get('disable_functions');
        $disable_functions = !empty($disable_functions) ? explode(',', $disable_functions) : '未禁用';

        // 附件大小
        $attachment_size = FileHelper::getDirSize(Yii::getAlias('@attachment'));

        return $this->render('info', [
            'mysql_size' => $mysql_size,
            'attachment_size' => $attachment_size ?? 0,
            'disable_functions' => $disable_functions,
        ]);
    }

    /**
     * 服务器信息
     *
     * @return array|string
     */
    public function actionServer()
    {
        $info = new SystemInfo();
        $systemInfo = [
            'environment' => $info->getEnvironment(),
            'hardDisk' => $info->getHardDisk(),
            'cpu' => $info->getCpu(),
            'cpuUse' => $info->getCpuUse(),
            'netWork' => $info->getNetwork(),
            'memory' => $info->getMemory(),
            'loadavg' => $info->getLoadavg(),
            'uptime' => $info->getUptime(),
            'time' => time()
        ];

        // 计算网络速度和CPU使用情况
        $oldSystemInfo = Yii::$app->cache->get('system:info');
        if (!empty($oldSystemInfo) && PHP_OS == 'Linux')
        {
            $oldNewwork = $oldSystemInfo['netWork'];
            $newNewwork = $systemInfo['netWork'];
            $networkFormatting = (time() - $oldSystemInfo['time']) * 1024;
            empty($networkFormatting) && $networkFormatting = 1;
            $systemInfo['netWork']['currentOutSpeed'] = round(($newNewwork['allOutSpeed'] - $oldNewwork['allOutSpeed']) / $networkFormatting, 2);
            $systemInfo['netWork']['currentInputSpeed'] = round(($newNewwork['allInputSpeed'] - $oldNewwork['allInputSpeed']) / $networkFormatting, 2);

            // 计算CPU情况
            $oldCpuUse = $oldSystemInfo['cpuUse'];
            unset($oldCpuUse['explain']);
            $newCpuUse = $systemInfo['cpuUse'];

            $cpus = [];
            $systemInfo['cpuUse']['explain'] = '';
            foreach ($oldCpuUse as $key => $item)
            {
                $cpuUse = [];
                foreach ($item as $k => $v)
                {
                    $cpuUse[$k] = $newCpuUse[$key][$k] - $oldCpuUse[$key][$k];
                }

                unset($v);
                $total = array_sum($cpuUse);

                $cpu = [];
                $prefix = '';
                foreach($cpuUse as $k => $v)
                {
                    $cpu['cpu'][$k] = round($v / $total * 100, 2);
                    $systemInfo['cpuUse']['explain'] .= $prefix . SystemInfo::$cpuExplain[$k] .' : ' . $cpu['cpu'][$k] . '% ';

                    $prefix = ' | ';
                }

                $systemInfo['cpuUse']['explain'] .= "<br>";
                $cpus[] = $cpu;
            }
        }

        empty($systemInfo['cpuUse']['explain']) && $systemInfo['cpuUse']['explain'] = '正在计算...';

        Yii::$app->cache->set('system:info', $systemInfo);

        // 网络数字转格式
        $systemInfo['netWork']['allOutSpeed'] = round($systemInfo['netWork']['allOutSpeed'] / (1024 * 1024 * 1024), 2);
        $systemInfo['netWork']['allInputSpeed'] = round($systemInfo['netWork']['allInputSpeed'] / (1024 * 1024 * 1024), 2);

        $num = 3;
        $num_arr = [];
        for ($i = 20; $i >= 1; $i--)
        {
            $num_arr[] = date('H:i:s', time() - $i * $num);
        }

        $systemInfo['chartTime'] = $num_arr;
        if (Yii::$app->request->isAjax)
        {
            return ResultDataHelper::json(200, '获取成功', $systemInfo);
        }

        return $this->render('server', [
            'info' => $systemInfo,
        ]);
    }
}
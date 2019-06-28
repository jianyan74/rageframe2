<?php
namespace backend\modules\sys\components;

/**
 * windows 探针
 *
 * Class SystemInfoWindows
 * @package backend\components
 * @author jianyan74 <751393839@qq.com>
 */
class SystemInfoWindows
{
    /**
     * @return array
     */
    public function getCpu()
    {
        return [
            'num' => '当前系统不支持',
            'model' => '当前系统不支持',
        ];
    }

    /**
     * @return array
     */
    public function getCpuUse()
    {
        $path = $this->getCupUsageVbsPath();
        exec("cscript -nologo $path", $usage);
        return [
            'usage_rate' => $usage[0],
            'explain' => $usage[0] . '%'
        ];
    }

    /**
     * 网络
     *
     * @return array
     */
    public function getNetwork()
    {
        return [
            'allOutSpeed' => '0',
            'allInputSpeed' => '0',
            'currentOutSpeed' => '0',
            'currentInputSpeed' => '0',
        ];
    }

    /**
     * 内存
     *
     * @return array
     */
    public function getMemory()
    {
        $path = $this->getMemoryUsageVbsPath();
        exec("cscript -nologo $path", $usage);
        $memory = json_decode($usage[0], true);
        $usage = round((($memory['TotalVisibleMemorySize'] - $memory['FreePhysicalMemory']) / $memory['TotalVisibleMemorySize']) * 100, 2);

        return [
            'memory' => [
                'total' => round($memory['TotalVisibleMemorySize'] / 1024, 2),
                'free' => round($memory['TotalVisibleMemorySize'] / 1024, 2),
                'usage_rate' => $usage,
                'buffers' => 0,
                'used' => round(($memory['TotalVisibleMemorySize'] - $memory['FreePhysicalMemory']) / 1024, 2),
            ],
            'cache' => [
                'total' => 0,
                'usage_rate' => 0,
                'real' => 0,
            ],
            'real' => [
                'used' => round(($memory['TotalVisibleMemorySize'] - $memory['FreePhysicalMemory']) / 1024, 2),
                'usage_rate' => $usage,
                'free' => round($memory['TotalVisibleMemorySize'] / 1024, 2),
            ],
            'swap' => [
                'total' => 0,
                'usage_rate' => 0,
                'used' => 0,
                'free' => 0,
            ],
        ];
    }

    public function getLoadavg()
    {
        $info = [];
        $info['loadavg'] = [];
        $info['explain'] = '当前系统不支持';

        return $info;
    }

    /**
     * @return string
     */
    public function getUptime()
    {
        return '当前系统不支持';
    }

    /***************************************************************/

    /**
     * 判断指定路径下指定文件是否存在，如不存在则创建
     *
     * @param string $fileName 文件名
     * @param string $content 文件内容
     * @return string 返回文件路径
     */
    private function getFilePath($fileName, $content)
    {
        $path = dirname(__FILE__) . "\\$fileName";
        if (!file_exists($path)) {
            file_put_contents($path, $content);
        }

        return $path;
    }

    /**
     * 获得cpu使用率vbs文件生成函数
     * @return string 返回vbs文件路径
     */
    private function getCupUsageVbsPath()
    {
        return $this->getFilePath(
            'cpu_usage.vbs',
            "On Error Resume Next
    Set objProc = GetObject(\"winmgmts:\\\\.\\root\cimv2:win32_processor='cpu0'\")
    WScript.Echo(objProc.LoadPercentage)"
        );
    }

    /**
     * 获得总内存及可用物理内存JSON vbs文件生成函数
     * @return string 返回vbs文件路径
     */
    private function getMemoryUsageVbsPath()
    {
        return $this->getFilePath(
            'memory_usage.vbs',
            "On Error Resume Next
    Set objWMI = GetObject(\"winmgmts:\\\\.\\root\cimv2\")
    Set colOS = objWMI.InstancesOf(\"Win32_OperatingSystem\")
    For Each objOS in colOS
     Wscript.Echo(\"{\"\"TotalVisibleMemorySize\"\":\" & objOS.TotalVisibleMemorySize & \",\"\"FreePhysicalMemory\"\":\" & objOS.FreePhysicalMemory & \"}\")
    Next"
        );
    }
}
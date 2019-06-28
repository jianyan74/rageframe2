<?php
namespace backend\modules\sys\components;

use yii\web\NotFoundHttpException;

/**
 * Class SystemInfo
 * @package backend\components
 * @author jianyan74 <751393839@qq.com>
 */
class SystemInfo
{
    /**
     * 系统类
     *
     * @var SystemInfoWindows
     */
    protected $system;

    /**
     * cpu状态说明
     *
     * @var array
     */
    public static $cpuExplain = [
        'idle' => 'CPU空闲',
        'user' => '用户进程',
        'sys' => '内核进程',
        'iowait' => '等待进行I/O',
        'nice' => '更改优先级',
        'irq' => '系统中断',
        'softirq' => '软件中断',
    ];

    /**
     * SystemInfo constructor.
     * @throws NotFoundHttpException
     */
    public function __construct()
    {
        // Darwin
        switch (PHP_OS)
        {
            case'Linux':
                $this->system = new SystemInfoLinux();
                break;
            case'WINNT':
                $this->system = new SystemInfoWindows();
                break;
            default :
                throw new NotFoundHttpException('当前系统为' . PHP_OS . ',不在支持范围，仅支持Linux和Windows');
                break;
        }
    }

    /**
     * 获取cpu信息
     *
     * @return array
     */
    public function getCpu()
    {
        return $this->system->getCpu();
    }

    /**
     * 获取cpu使用率
     *
     * @return array
     */
    public function getCpuUse()
    {
        return $this->system->getCpuUse();
    }

    /**
     * 获取网络情况
     *
     * @return array
     */
    public function getNetwork()
    {
        return $this->system->getNetwork();
    }

    /**
     * 获取运行时间
     *
     * @return string
     */
    public function getUptime()
    {
        return $this->system->getUptime();
    }

    /**
     * 获取内存情况
     *
     * @return array
     */
    public function getMemory()
    {
        return $this->system->getMemory();
    }

    /**
     * 获取负载情况
     *
     * @return array
     */
    public function getLoadavg()
    {
        return $this->system->getLoadavg();
    }


    /**
     * 获取环境信息
     *
     */
    public function getEnvironment()
    {
        $environment = [];
        $environment['ip'] = @$_SERVER['REMOTE_ADDR'];
        $domain = $this->os() ? $_SERVER['SERVER_ADDR'] : @gethostbyname($_SERVER['SERVER_NAME']);
        $os = explode(" ", php_uname());
        $environment['domainIP'] = @get_current_user() . ' - ' . $_SERVER['SERVER_NAME'] . '(' . $domain . ')';
        $environment['flag'] = php_uname();
        $environment['phpOs'] = PHP_OS;
        $environment['os'] = $os[0] . '内核版本：' . $this->os() ? $os[2] : $os[1];
        $environment['language'] = getenv("HTTP_ACCEPT_LANGUAGE");
        $environment['name'] =$this->os() ? $os[1] : $os[2];
        $environment['email'] = @$_SERVER['SERVER_ADMIN'];
        $environment['webEngine'] = $_SERVER['SERVER_SOFTWARE'];
        $environment['webPort'] = @$_SERVER['SERVER_PORT'];
        $environment['webPath'] = $_SERVER['DOCUMENT_ROOT'] ? str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']) : str_replace('\\', '/', dirname(__FILE__));
        $environment['probePath'] = str_replace('\\','/',__FILE__) ? str_replace('\\','/',__FILE__) : $_SERVER['SCRIPT_FILENAME'];
        $environment['newTime'] = date('Y-m-d H:i:s');

        return $environment;
    }

    /**
     * 获取硬盘情况
     *
     * total 总量
     * free 空闲
     * used 已用
     * usage_rate 使用率
     * @return mixed
     */
    public function getHardDisk()
    {
        $hardDisk['total'] = round(@disk_total_space(".") / (1024 * 1024 * 1024), 2);
        $hardDisk['free'] = round(@disk_free_space(".") / (1024 * 1024 * 1024), 2);
        $hardDisk['used'] = round($hardDisk['total'] - $hardDisk['free'], 2);
        $usage_rate = (floatval($hardDisk['total']) != 0) ? round($hardDisk['used'] / $hardDisk['total'] * 100, 2) : 0;
        $hardDisk['usage_rate'] = round($usage_rate, 2);

        return $hardDisk;
    }

    /**
     * 判断linux系统还是windows系统
     *
     * @return bool
     */
    private function os()
    {
        return DIRECTORY_SEPARATOR == '/' ? true : false;
    }
}
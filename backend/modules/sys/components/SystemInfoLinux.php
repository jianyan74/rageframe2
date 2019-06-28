<?php
namespace backend\modules\sys\components;

/**
 * linux 探针
 *
 * Class SystemInfoLinux
 * @package backend\components
 * @author jianyan74 <751393839@qq.com>
 */
class SystemInfoLinux
{
    /**
     * @return array
     */
    public function getCpu()
    {
        $res = [];

        // 获取CPU信息
        if ($cpuinfo = @file("/proc/cpuinfo")) {
            $cpuinfo = implode("",$cpuinfo);
            @preg_match_all("/model\s+name\s{0,}\:+\s{0,}([\w\s\)\(\@.-]+)([\r\n]+)/s",$cpuinfo, $model);// CPU 名称
            @preg_match_all("/cpu\s+MHz\s{0,}\:+\s{0,}([\d\.]+)[\r\n]+/",$cpuinfo, $mhz);// CPU频率
            @preg_match_all("/cache\s+size\s{0,}\:+\s{0,}([\d\.]+\s{0,}[A-Z]+[\r\n]+)/",$cpuinfo, $cache);// CPU缓存
            @preg_match_all("/bogomips\s{0,}\:+\s{0,}([\d\.]+)[\r\n]+/",$cpuinfo, $bogomips);

            if (is_array($model[1])) {
                $cpunum = count($model[1]);

                $mhz[1][0] =' | 频率(MHz):' . $mhz[1][0];
                $cache[1][0] =' | 二级缓存:' . $cache[1][0];
                $bogomips[1][0] =' | Bogomips:' . $bogomips[1][0];

                $res['num'] = $cpunum;
                $res['model'] = $model[1][0] . $mhz[1][0] . $cache[1][0] . $bogomips[1][0];
                // if (is_array($res['model'])) {$res['model'] = implode("<br />", $res['model']);}
                // if(is_array($res['mhz']))$res['mhz'] = implode("<br />", $res['mhz']);
                // if(is_array($res['cache']))$res['cache'] = implode("<br />", $res['cache']);
                // if(is_array($res['bogomips']))$res['bogomips'] = implode("<br />", $res['bogomips']);
            }
        }

        return $res;
    }

    /**
     * 获取cpu使用情况
     *
     * @return array
     */
    public function getCpuUse()
    {
        $cores= [];
        $data= @file('/proc/stat');
        foreach ($data as $line) {
            if (preg_match('/^cpu[0-9]/',$line)) {
                $info = explode(' ',$line);
                $cores[] = [
                    'idle' => $info[4],
                    'user' => $info[1],
                    'nice' => $info[2],
                    'sys' => $info[3],
                    'iowait' => $info[5],
                    'irq' => $info[6],
                    'softirq' => $info[7]
                ];
            }
        }

        return $cores;
    }

    /**
     * @return array
     */
    public function getNetwork()
    {
        $res = [];
        $res['allOutSpeed']  = 0;
        $res['allInputSpeed']  = 0;
        // 网络速度
        $res['currentOutSpeed']  = 0;
        $res['currentInputSpeed']  = 0;

        $strs = @file("/proc/net/dev");
        $lines = count($strs);
        for ($i = 2; $i < $lines; $i++) {
            preg_match_all("/([^\s]+):[\s]{0,}(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)/", $strs[$i], $info);

            $name = $info[1][0];
            $res[$name]['name'] = $name;
            $res[$name]['outSpeed'] = $info[10][0];
            $res[$name]['inputSpeed'] = $info[2][0];

            $res['allOutSpeed'] += $info[10][0];
            $res['allInputSpeed'] += $info[2][0];
        }

        return $res;
    }

    /**
     * 获取内存信息
     *
     * @return array
     */
    public function getMemory()
    {
        // 内存
        if ($meminfo = @file("/proc/meminfo")) {
            $meminfo = implode("",$meminfo);
            preg_match_all("/MemTotal\s{0,}\:+\s{0,}([\d\.]+).+?MemFree\s{0,}\:+\s{0,}([\d\.]+).+?Cached\s{0,}\:+\s{0,}([\d\.]+).+?SwapTotal\s{0,}\:+\s{0,}([\d\.]+).+?SwapFree\s{0,}\:+\s{0,}([\d\.]+)/s",$meminfo, $buf);
            preg_match_all("/Buffers\s{0,}\:+\s{0,}([\d\.]+)/s", $meminfo, $buffers);
            // 内存
            $memory = [];
            $memory['total'] = round($buf[1][0] / 1024, 2);
            $memory['free'] = round($buf[2][0] / 1024, 2);
            $memory['used'] = round($memory['total'] - $memory['free'], 2);
            $memory['buffers'] = round($buffers[1][0] / 1024, 2);
            $memory['usage_rate'] = (floatval($memory['total']) != 0) ? round($memory['used'] / $memory['total'] * 100, 2) : 0;

            // 缓存
            $cache = [];
            $cache['total'] = round($buf[3][0] / 1024, 2);
            $cache['usage_rate'] = (floatval($cache['total']) != 0) ? round($cache['total'] / $memory['total'] * 100,2) : 0;
            $cache['real'] = $cache['total'] * $cache['usage_rate'];
            !empty($cache['real']) && $cache['real'] = round($cache['real'] / 100, 2);

            // 真实
            $real = [];
            $real['used'] = round($memory['total'] - $memory['free'] - $cache['total'] - $memory['buffers'], 2);
            $real['free'] = $memory['total'] - $real['used'];
            $real['usage_rate'] = (floatval($memory['total']) != 0 ) ? round($real['used'] / $memory['total'] * 100,2) : 0 ;

            $swap = [];
            $swap['total'] = round($buf[4][0] / 1024, 2);
            $swap['free'] = round($buf[5][0] / 1024, 2);
            $swap['used'] = round($swap['total'] - $swap['free'], 2);
            $swap['usage_rate'] = (floatval($swap['total']) != 0) ? round($swap['used'] / $swap['total'] * 100,2) : 0;
        }

        return [
            'memory' => $memory,
            'cache' => $cache,
            'real' => $real,
            'swap' => $swap,
        ];
    }

    /**
     * 系统负载
     */
    public function getLoadavg()
    {
        $info = [];
        $info['loadavg'] = [];

        if ($loadavg = @file("/proc/loadavg")) {
            $loadavg = explode(" ", implode("", $loadavg));
            $loadavg = array_chunk($loadavg, 4);
            $info['loadavg'] = $loadavg;
            $info['explain'] = implode(" ", $loadavg[0]);
        }

        return $info;
    }

    /**
     * 获取系统已在时间
     *
     * @return string
     */
    public function getUptime()
    {
        $info = '获取失败';

        if ($uptime = @file("/proc/uptime")) {
            $str = explode(" ", implode("", $uptime));
            $str = trim($str[0]);
            $min = $str / 60;
            $hours = $min / 60;
            $days = floor($hours / 24);
            $hours = floor($hours - ($days * 24));
            $min = floor($min-($days * 60 * 24)-($hours * 60));
            $info = $days . " 天 " . $hours . " 小时 " . $min . " 分钟 ";
        }

        return $info;
    }
}
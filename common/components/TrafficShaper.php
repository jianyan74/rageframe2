<?php

namespace common\components;

use Yii;
use yii\redis\Connection;

/**
 * 令牌桶 - 限流
 *
 * 令牌桶算法 (Token Bucket) 和 Leaky Bucket 效果一样但方向相反的算法，更加容易理解。
 * 随着时间流逝，系统会按恒定 1/QPS 时间间隔 (如果 QPS=100, 则间隔是 10ms) 往桶里加入 Token (想象和漏洞漏水相反，有个水龙头在不断的加水), 如果桶已经满了就不再加了。
 * 新请求来临时，会各自拿走一个 Token, 如果没有 Token 可拿了就阻塞或者拒绝服务.
 *
 * 令牌桶的另外一个好处是可以方便的改变速度。
 * 一旦需要提高速率，则按需提高放入桶中的令牌的速率。
 * 一般会定时 (比如 1000 毫秒) 往桶中增加一定数量的令牌，有些变种算法则实时的计算应该增加的令牌的数量.
 *
 * Class TrafficShaper
 * @package common\components
 * @author jianyan74 <751393839@qq.com>
 */
class TrafficShaper
{
    /**
     * 令牌桶
     *
     * @var string
     */
    public $container;

    /**
     * 最大令牌数
     *
     * @var int
     */
    public $max;

    /**
     * @var Connection
     */
    protected $redis;

    /**
     * TrafficShaper constructor.
     * @param int $max
     * @param string $container
     */
    public function __construct($max = 300, $container = 'container')
    {
        $this->redis = Yii::$app->redis;
        $this->max = $max;
        $this->container = $container;
    }

    /**
     * 加入令牌
     *
     * 注意：需要加入定时任务，定时增加令牌数量
     *
     * @param int $num 加入的令牌数量
     * @return int 加入的数量
     */
    public function add($num = 0)
    {
        // 当前剩余令牌数
        $curnum = intval($this->redis->llen($this->container));
        // 最大令牌数
        $maxnum = intval($this->max);
        // 计算最大可加入的令牌数量，不能超过最大令牌数
        $num = $maxnum >= $curnum + $num ? $num : $maxnum - $curnum;
        // 加入令牌
        if ($num > 0) {
            $token = array_fill(0, $num, 1);
            $this->redis->lpush($this->container, ...$token);
            return $num;
        }

        return 0;
    }

    /**
     * 获取令牌
     *
     * @return bool
     */
    public function get()
    {
        return $this->redis->rpop($this->container) ? true : false;
    }

    /**
     * 重设令牌桶，填满令牌
     */
    public function reset()
    {
        $this->redis->lrem($this->container, 0, $this->max);
        $this->add($this->max);
    }
}
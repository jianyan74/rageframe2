<?php

namespace common\replaces;

/**
 * 新增加执行sql时断开重连
 * 数据库连接断开异常
 * errorInfo = [''HY000',2006,'错误信息']
 *
 * Class Command
 * @package common\replaces
 * @author jianyan74 <751393839@qq.com>
 */
class Command extends \yii\db\Command
{
    public $retry;

    /**
     * 处理修改类型sql的断线重连问题
     *
     * @return int
     * @throws \Exception
     * @throws \yii\db\Exception
     */
    public function execute()
    {
        try {
            return parent::execute();
        } catch (\yii\db\Exception $e) {
            if ($this->handleException($e)) {
                return parent::execute();
            }

            throw $e;
        }
    }

    /**
     * 处理查询类sql断线重连问题
     *
     * @param string $method
     * @param null $fetchMode
     * @return mixed
     * @throws \Exception
     * @throws \yii\db\Exception
     */
    protected function queryInternal($method, $fetchMode = null)
    {
        try {
            return parent::queryInternal($method, $fetchMode);
        } catch (\yii\db\Exception $e) {
            if ($this->handleException($e)) {
                return parent::queryInternal($method, $fetchMode);
            }

            throw $e;
        }
    }

    /**
     * 判断该数据库异常是否需要重试.一般情况下链接断开的错误才需要重试
     * 2006: MySQL server has gone away
     * 2013: Lost connection to MySQL server during query
     * 但是实际使用中发现，由于Yii2对数据库异常进行了处理并封装成\yii\db\Exception异常
     * 因此2006错误的错误码并不能在errorInfo中获取到，因此需要判断errorMsg内容
     * @param \yii\db\Exception $ex
     * @return bool
     * @throws \yii\db\Exception
     */
    private function handleException(\yii\db\Exception $e)
    {
        $errorMsg = $e->getMessage();
        if (strpos($errorMsg, 'MySQL server has gone away') || strpos($errorMsg, 'Error while sending QUERY packet')) {
            $this->retry = true;
            $this->pdoStatement = null;
            $this->db->close();
            $this->db->open();

            return true;
        }

        if (!empty($e->errorInfo) && in_array($e->errorInfo[1], [2006, 2013])) {
            $this->retry = true;
            $this->pdoStatement = null;
            $this->db->close();
            $this->db->open();

            return true;
        }

        return false;
    }

    /**
     * 利用$this->retry属性，标记当前是否是数据库重连
     * 重写bindPendingParams方法，当当前是数据库重连之后重试的时候
     * 调用bindValues方法重新绑定一次参数.
     */
    protected function bindPendingParams()
    {
        if ($this->retry) {
            $this->retry = false;
            $this->bindValues($this->params);
        }

        parent::bindPendingParams();
    }
}
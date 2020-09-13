<?php

namespace services\common;

use Yii;
use common\components\Service;
use App\Config\YlyConfig;
use App\Oauth\YlyOauthClient;
use App\Api\PrinterService;
use App\Api\PrintService;
use App\Api\PicturePrintService;
use common\helpers\StringHelper;
use common\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class PrinterYiLianYunService
 * @package services\common
 * @author jianyan74 <751393839@qq.com>
 */
class PrinterYiLianYunService extends Service
{
    /**
     * @var YlyConfig
     */
    protected $config;
    /**
     * 打印数量
     *
     * @var int
     */
    protected $printNum;
    /**
     * 机器码
     *
     * @var string
     */
    protected $machineCode;

    public function init()
    {
        $this->machineCode = Yii::$app->debris->backendConfig('printer_yilianyun_terminal_number');
        $this->printNum = Yii::$app->debris->backendConfig('printer_yilianyun_print_num');
        $this->config = new YlyConfig(Yii::$app->debris->backendConfig('printer_yilianyun_app_id'),
            Yii::$app->debris->backendConfig('printer_yilianyun_app_secret _key'));


        // 授权打印机(自有型应用使用,开放型应用请跳过该步骤)
        // $printer = new PrinterService($this->token->access_token, $this->config);
        // $data = $printer->addPrinter('你的机器码', '你的机器密钥');

        parent::init();
    }

    /**
     * @param $data
     *
     * 58mm排版 排版指令详情请看 http://doc2.10ss.net/332006
     *
     * $content = "<FS2><center>**#1 美团**</center></FS2>";
     * $content .= str_repeat('.', 32);
     * $content .= "<FS2><center>--在线支付--</center></FS2>";
     * $content .= "<FS><center>张周兄弟烧烤</center></FS>";
     * $content .= "订单时间:". date("Y-m-d H:i") . "\n";
     * $content .= "订单编号:40807050607030\n";
     * $content .= str_repeat('*', 14) . "商品" . str_repeat("*", 14);
     * $content .= "<table>";
     * $content .= "<tr><td>烤土豆(超级辣)</td><td>x3</td><td>5.96</td></tr>";
     * $content .= "<tr><td>烤豆干(超级辣)</td><td>x2</td><td>3.88</td></tr>";
     * $content .= "<tr><td>烤鸡翅(超级辣)</td><td>x3</td><td>17.96</td></tr>";
     * $content .= "<tr><td>烤排骨(香辣)</td><td>x3</td><td>12.44</td></tr>";
     * $content .= "<tr><td>烤韭菜(超级辣)</td><td>x3</td><td>8.96</td></tr>";
     * $content .= "</table>";
     * $content .= str_repeat('.', 32);
     * $content .= "<QR>这是二维码内容</QR>";
     * $content .= "小计:￥82\n";
     * $content .= "折扣:￥４ \n";
     * $content .= str_repeat('*', 32);
     * $content .= "订单总价:￥78 \n";
     * $content .= "<FS2><center>**#1 完**</center></FS2>";
     *
     * @param string $uuid
     */
    public function text($content, $uuid = '')
    {
        // 格式化内容
        $content = $this->formattedContent($content);
        $token = $this->getToken();
        empty($uuid) && $uuid = StringHelper::random(32, true);
        $print = new PrintService($token['access_token'], $this->config);
        $data = $print->index($this->machineCode, $content, $uuid);
        if ($data->error != 0) {
            throw new UnprocessableEntityHttpException($data->error_description);
        }

        return $data->body;
    }

    /**
     * @param $data
     * @param string $uuid
     */
    public function image($data, $uuid = '')
    {
        !$uuid && $uuid == StringHelper::random(32, true);
        $token = $this->getToken();

        $print = new PicturePrintService($token['access_token'], $this->config);
        $data = $print->index($this->machineCode, '打印内容排版可看Demo下的callback.php', $uuid);
        var_dump($data);
    }

    protected function formattedContent($data)
    {
        if (!is_array($data)) {
            return $data;
        }

        $content = "<MN>{$this->printNum}</MN>";
        $content .= "<FS><center>****** {$data['title']} ******</center></FS>";
        $content .= str_repeat('.', 32);
        $content .= "<FS><center>---{$data['payType']}---</center></FS>";
        // $content .= "<center>{$data['merchantTitle']}</center>";
        $content .= "打印时间:" . Yii::$app->formatter->asDatetime(time()) . "\n";
        $content .= "下单时间:" . $data['orderTime'] . "\n";
        $content .= str_repeat('*', 13) . " 商品 " . str_repeat("*", 13);
        $content .= "<table>";
        $content .= "<tr><td>商品名称</td><td>数量</td><td>金额</td></tr>";
        foreach ($data['products'] as $product) {
            $content .= "<tr><td>{$product['title']}</td><td>x{$product['num']}</td><td>￥{$product['price']}</td></tr>";
        }
        $content .= "</table>";
        $content .= str_repeat('*', 32);
        $content .= "<table>";
        $content .= "<tr><td>商品总价</td><td></td><td>￥{$data['productOriginalMoney']}</td></tr>";
        foreach ($data['marketingDetails'] as $marketingDetail) {
            $content .= "<tr><td>{$marketingDetail['marketing_name']}</td><td></td><td>-￥{$marketingDetail['discount_money']}</td></tr>";
        }
        $data['pointMoney'] > 0 && $content .= "<tr><td>积分抵扣</td><td></td><td>-￥{$data['pointMoney']}</td></tr>";
        $data['taxMoney'] > 0 && $content .= "<tr><td>发票税额</td><td></td><td>-￥{$data['taxMoney']}</td></tr>";
        $content .= "<tr><td>配送费</td><td></td><td>￥{$data['shippingMoney']}</td></tr>";
        $content .= "<tr><td></td><td></td><td></td></tr>";
        $content .= "<tr><td>应付金额</td><td></td><td>￥{$data['payMoney']}</td></tr>";
        $content .= "</table>";
        $content .= str_repeat('.', 32);
        $content .= "昵称: " . $data['nickname'] . "\n";
        isset($data['receiverName']) && $content .= "客户: " . $data['receiverName'] . "\n";
        isset($data['receiverMobile']) && $content .= "电话: " . StringHelper::hideStr($data['receiverMobile'], 3) . "\n";
        isset($data['receiverAddress']) && $content .= "地址: " . $data['receiverAddress'] . "\n";
        isset($data['buyerMessage']) && $content .= "备注: " . !empty($data['buyerMessage']) ? $data['buyerMessage'] : '无' . "\n";
        if (!empty($data['qr'])) {
            $content .= str_repeat('.', 32);
            $content .= "<center>二维码</center>";
            $content .= "<QR>{$data['qr']}</QR>";
        } else {
            $content .= str_repeat('.', 32);
            $content .= "<center>扫码出库二维码</center>";
            $content .= "<QR>order:{$data['orderSn']}</QR>";
        }

        $content .= str_repeat('.', 32);
        $content .= "<center>订单号</center>";
        $content .= "<BR3>{$data['orderSn']}</BR3>";
        $content .= "<center></center>";
        $content .= "<FS><center>****** 完 ******</center></FS>";

        return $content;
    }

    /**
     * @var string
     */
    protected $cachePrefix = 'printer.yiLianYun.token.';

    /**
     * @return array
     */
    public function getRefreshedToken(): array
    {
        return $this->getToken(true);
    }

    /**
     * @param bool $refresh
     * @return array|object
     * @throws NotFoundHttpException
     */
    public function getToken(bool $refresh = false): array
    {
        $cacheKey = $this->getCacheKey();
        if (!$refresh && Yii::$app->cache->exists($cacheKey)) {
            return Yii::$app->cache->get($cacheKey);
        }

        $token = $this->requestToken();
        $this->setToken($token->access_token, $token->refresh_token, $token->expires_in);

        return ArrayHelper::toArray($token);
    }

    /**
     * @param string $token
     * @param int $lifetime
     * @return $this
     * @throws NotFoundHttpException
     */
    public function setToken(string $token, string $refresh_token, int $lifetime = 7200)
    {
        $status = Yii::$app->cache->set($this->getCacheKey(), [
            'access_token' => $token,
            'refresh_token' => $refresh_token,
            'expireIn' => $lifetime - 500,
        ], $lifetime);

        if ($status == false || !Yii::$app->cache->exists($this->getCacheKey())) {
            throw new NotFoundHttpException('Failed to cache cloud token.');
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function refresh()
    {
        $this->getToken(true);

        return $this;
    }

    /**
     * @return mixed|object
     */
    public function requestToken()
    {
        $client = new YlyOauthClient($this->config);

        return $client->getToken();
    }

    /**
     * @return string
     */
    protected function getCacheKey()
    {
        return $this->cachePrefix;
    }
}
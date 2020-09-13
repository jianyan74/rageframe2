<?php

namespace services\common;

use common\helpers\StringHelper;
use Yii;
use yii\helpers\Json;
use common\components\Service;
use linslin\yii2\curl\Curl;
use yii\web\UnprocessableEntityHttpException;

/**
 * 飞鹅云打印机
 *
 * Class PrinterFeiEYunService
 * @package services\common
 * @author jianyan74 <751393839@qq.com>
 */
class PrinterFeiEYunService extends Service
{
    const IP = 'https://api.feieyun.cn'; //接口IP或域名
    const PORT = 80; // 接口IP端口
    const PATH = '/Api/Open/'; //接口路径

    protected $user;
    protected $uKey;
    protected $sn;
    protected $printNum;

    public function init()
    {
        $this->user = Yii::$app->debris->backendConfig('printer_feieyun_user');
        $this->uKey = Yii::$app->debris->backendConfig('printer_feieyun_ukey');
        $this->sn = Yii::$app->debris->backendConfig('printer_feieyun_sn');
        $this->printNum = Yii::$app->debris->backendConfig('printer_feieyun_print_num');

        parent::init();
    }

    /**
     * @param $content
     * @param int $times
     */
    public function print($content)
    {
        // 格式化内容
        $content = $this->formattedContent($content);

        $time = time(); // 请求时间
        $msgInfo = [
            'user' => $this->user,
            'stime' => $time,
            'sig' => $this->signature($time),
            'apiname' => 'Open_printMsg',
            'sn' => $this->sn,
            'content' => $content,
            'times' => $this->printNum // 打印次数
        ];

        $curl = new Curl();
        $result = $curl->setHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded',
        ])->setPostParams($msgInfo)->post(self::IP . self::PATH);
        $result = Json::decode($result);
        if ($result['ret'] != 0) {
            throw new UnprocessableEntityHttpException($result['msg']);
        }

        return $result['data'];
    }

    /**
     * @param $data
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    protected function formattedContent($data)
    {
        if (!is_array($data)) {
            return $data;
        }

        $content = "<BOLD><C>****** {$data['title']} ******</C></BOLD>" . "<BR>";
        $content .= str_repeat('.', 32) . "<BR>";
        $content .= "<BOLD><C>---{$data['payType']}---</C></BOLD>" . "<BR>";
        // $content .= "<C>{$data['merchantTitle']}</C>";
        $content .= "打印时间:" . Yii::$app->formatter->asDatetime(time()) . "<BR>";
        $content .= "下单时间:" . $data['orderTime'] . "<BR>";
        $content .= str_repeat('*', 13) . " 商品 " . str_repeat("*", 13) . "<BR>";
        $content .= $this->composing($data['products']);

        $content .= "商品总价：￥{$data['productOriginalMoney']}<BR>";
        foreach ($data['marketingDetails'] as $marketingDetail) {
            $content .= "{$marketingDetail['marketing_name']}：-￥{$marketingDetail['discount_money']}<BR>";
        }
        $data['pointMoney'] > 0 && $content .= "积分抵扣：-￥{$data['pointMoney']}<BR>";
        $data['taxMoney'] > 0 && $content .= "发票税额：-￥{$data['taxMoney']}<BR>";
        $content .= "配送费：￥{$data['shippingMoney']}<BR>";
        $content .= "应付金额：￥{$data['payMoney']}<BR>";

        $content .= str_repeat('.', 32) . "<BR>";
        $content .= "昵称: " . $data['nickname'] . "<BR>";
        isset($data['receiverName']) && $content .= "客户: " . $data['receiverName'] . "<BR>";
        isset($data['receiverMobile']) && $content .= "电话: " . StringHelper::hideStr($data['receiverMobile'],
                3) . "<BR>";
        isset($data['receiverAddress']) && $content .= "地址: " . $data['receiverAddress'] . "<BR>";
        isset($data['buyerMessage']) && $content .= "备注: " . !empty($data['buyerMessage']) ? $data['buyerMessage'] : '无' . "<BR>";
        if (!empty($data['qr'])) {
            $content .= str_repeat('.', 32) . "<BR>";
            $content .= "<C>二维码</C>" . "<BR>";
            $content .= "<QR>{$data['qr']}</QR>" . "<BR>";
        } else {
            $content .= str_repeat('.', 32) . "<BR>";
            $content .= "<C>扫码出库二维码</C>" . "<BR>";
            $content .= "<QR>order:{$data['orderSn']}</QR>" . "<BR>";
        }

        $content .= str_repeat('.', 32) . "<BR>";
        $content .= "<C>订单号</C>" . "<BR>";
        $content .= $this->barCode($data['orderSn']) . "<BR>";
        $content .= "<BOLD><C>****** 完 ******</C></BOLD>" . "<BR>";

        return $content;
    }

    /**
     *
     * @param $products
     * @param int $A 名称
     * @param int $B 单价
     * @param int $C 数量
     * @param int $D 金额
     * @return string
     */
    protected function composing($products, $A = 14, $B = 6, $C = 3, $D = 6)
    {
        $orderInfo = '商品名称　　　　　   数量 金额<BR>';
        $orderInfo .= '--------------------------------<BR>';
        foreach ($products as $k5 => $v5) {
            $name = $v5['title'];
            // $price = $v5['price'];
            $price = '';
            $num = $v5['num'];
            $prices = $v5['price'];
            $kw3 = '';
            $kw1 = '';
            $kw2 = '';
            $kw4 = '';
            $str = $name;
            $blankNum = $A;//名称控制为14个字节
            $lan = mb_strlen($str, 'utf-8');
            $m = 0;
            $j = 1;
            $blankNum++;
            $result = array();
            if (strlen($price) < $B) {
                $k1 = $B - strlen($price);
                for ($q = 0; $q < $k1; $q++) {
                    $kw1 .= ' ';
                }
                $price = $price . $kw1;
            }
            if (strlen($num) < $C) {
                $k2 = $C - strlen($num);
                for ($q = 0; $q < $k2; $q++) {
                    $kw2 .= ' ';
                }
                $num = $num . $kw2;
            }
            if (strlen($prices) < $D) {
                $k3 = $D - strlen($prices);
                for ($q = 0; $q < $k3; $q++) {
                    $kw4 .= ' ';
                }
                $prices = $prices . $kw4;
            }
            for ($i = 0; $i < $lan; $i++) {
                $new = mb_substr($str, $m, $j, 'utf-8');
                $j++;
                if (mb_strwidth($new, 'utf-8') < $blankNum) {
                    if ($m + $j > $lan) {
                        $m = $m + $j;
                        $tail = $new;
                        $lenght = iconv("UTF-8", "GBK//IGNORE", $new);
                        $k = $A - strlen($lenght);
                        for ($q = 0; $q < $k; $q++) {
                            $kw3 .= ' ';
                        }
                        if ($m == $j) {
                            $tail .= $kw3 . ' ' . $price . ' ' . $num . ' ' . $prices;
                        } else {
                            $tail .= $kw3 . '<BR>';
                        }
                        break;
                    } else {
                        $next_new = mb_substr($str, $m, $j, 'utf-8');
                        if (mb_strwidth($next_new, 'utf-8') < $blankNum) {
                            continue;
                        } else {
                            $m = $i + 1;
                            $result[] = $new;
                            $j = 1;
                        }
                    }
                }
            }
            $head = '';
            foreach ($result as $key => $value) {
                if ($key < 1) {
                    $v_lenght = iconv("UTF-8", "GBK//IGNORE", $value);
                    $v_lenght = strlen($v_lenght);
                    if ($v_lenght == 13) {
                        $value = $value . " ";
                    }
                    $head .= $value . ' ' . $price . ' ' . $num . ' ' . $prices;
                } else {
                    $head .= $value . '<BR>';
                }
            }
            $orderInfo .= $head . $tail;
        }

        $orderInfo .= '--------------------------------<BR>';

        return $orderInfo;
    }

    /**
     *    飞鹅技术支持-2020-03-25
     *    #########################################################################################################
     *    一，纯数字条件下：
     *        58mm打印机最大支持28位纯数字，80mm打印机最大支持46位纯数字，超出无效
     *
     *        26-28位数字条形码，在数字中不可以出现2个及以上连续的0存在
     *        23-25位数字条形码，在数字中不可以出现3个及以上连续的0存在
     *        21-22位数字条形码，在数字中不可以出现4个及以上连续的0存在
     *        19-20位数字条形码，在数字中不可以出现6个及以上连续的0存在
     *        17-18位数字条形码，在数字中不可以出现8个及以上连续的0存在
     *        15-16位数字条形码，在数字中不可以出现10个及以上连续的0存在
     *        少于或等于14位数字的条形码，0的数量没有影响
     *    #########################################################################################################
     *    二，非纯数字混合条件下：
     *        58mm打印机最大支持14位字符，80mm打印机最大支持23位字符，超出无效
     *
     *        支持数字，大小写字母，特殊字符例如:  !@#$%^&*()-=+_
     *    #########################################################################################################
     */
    protected function barCode($strnum)
    {
        $chr = '';
        $codeB = ["\x30", "\x31", "\x32", "\x33", "\x34", "\x35", "\x36", "\x37", "\x38", "\x39"];//匹配字符集B
        $codeC = [
            "\x00",
            "\x01",
            "\x02",
            "\x03",
            "\x04",
            "\x05",
            "\x06",
            "\x07",
            "\x08",
            "\x09",
            "\x0A",
            "\x0B",
            "\x0C",
            "\x0D",
            "\x0E",
            "\x0F",
            "\x10",
            "\x11",
            "\x12",
            "\x13",
            "\x14",
            "\x15",
            "\x16",
            "\x17",
            "\x18",
            "\x19",
            "\x1A",
            "\x1B",
            "\x1C",
            "\x1D",
            "\x1E",
            "\x1F",
            "\x20",
            "\x21",
            "\x22",
            "\x23",
            "\x24",
            "\x25",
            "\x26",
            "\x27",
            "\x28",
            "\x29",
            "\x2A",
            "\x2B",
            "\x2C",
            "\x2D",
            "\x2E",
            "\x2F",
            "\x30",
            "\x31",
            "\x32",
            "\x33",
            "\x34",
            "\x35",
            "\x36",
            "\x37",
            "\x38",
            "\x39",
            "\x3A",
            "\x3B",
            "\x3C",
            "\x3D",
            "\x3E",
            "\x3F",
            "\x40",
            "\x41",
            "\x42",
            "\x43",
            "\x44",
            "\x45",
            "\x46",
            "\x47",
            "\x48",
            "\x49",
            "\x4A",
            "\x4B",
            "\x4C",
            "\x4D",
            "\x4E",
            "\x4F",
            "\x50",
            "\x51",
            "\x52",
            "\x53",
            "\x54",
            "\x55",
            "\x56",
            "\x57",
            "\x58",
            "\x59",
            "\x5A",
            "\x5B",
            "\x5C",
            "\x5D",
            "\x5E",
            "\x5F",
            "\x60",
            "\x61",
            "\x62",
            "\x63"
        ];//匹配字符集C
        $length = strlen($strnum);
        $b = [];
        $b[0] = "\x1b";
        $b[1] = "\x64";
        $b[2] = "\x02";
        $b[3] = "\x1d";
        $b[4] = "\x48";
        $b[5] = "\x32";//条形码显示控制，\x32上图下字，\x31上字下图，\x30只显示条形码
        $b[6] = "\x1d";
        $b[7] = "\x68";
        $b[8] = "\x50";// \x30 设置条形码高度，7F是最大的高度
        $b[9] = "\x1d";
        $b[10] = "\x77";
        $b[11] = "\x02";// \x01 设置条形码宽度,1-6
        $b[12] = "\x1d";
        $b[13] = "\x6b";
        $b[14] = "\x49";//选择条形码类型code128,code39,codabar等等
        $b[15] = chr($length + 2);
        $b[16] = "\x7b";
        $b[17] = "\x42";//选择字符集
        if ($length > 14 && is_numeric($strnum)) {//大于14个字符,且为纯数字的进来这个区间
            $b[17] = "\x43";
            $j = 0;
            $key = 18;
            $ss = $length / 2;//初始化数组长度
            if ($length % 2 == 1) {//判断条形码为单数
                $ss = $ss - 0.5;
            }
            for ($i = 0; $i < $ss; $i++) {
                $temp = substr($strnum, $j, 2);
                $iindex = intval($temp);
                $j = $j + 2;
                if ($iindex == 0) {
                    $chr = '';
                    if ($b[$key + $i - 1] == '0' && $b[$key + $i - 2] == '0') {//判断前面的为字符集B,此时不需要转换字符集
                        $b[$key + $i] = $codeB[0];
                        $b[$key + $i + 1] = $codeB[0];
                        $key += 1;
                    } else {
                        if ($b[$key + $i - 1] == 'C' && $b[$key + $i - 2] == '{') {//判断前面的为字符集C时转换字符集B
                            $b[$key + $i - 2] = "\x7b";
                            $b[$key + $i - 1] = "\x42";
                            $b[$key + $i] = $codeB[0];
                            $b[$key + $i + 1] = $codeB[0];
                            $key += 1;
                        } else {
                            $b[$key + $i] = "\x7b";
                            $b[$key + $i + 1] = "\x42";
                            $b[$key + $i + 2] = $codeB[0];
                            $b[$key + $i + 3] = $codeB[0];
                            $key += 3;
                        }
                    }
                } else {
                    if ($b[$key + $i - 1] == '0' && $b[$key + $i - 2] == '0' && $chr != 'chr') {//判断前面的为字符集B,此时要转换字符集C
                        $b[$key + $i] = "\x7b";
                        $b[$key + $i + 1] = "\x43";
                        $b[$key + $i + 2] = $codeC[$iindex];
                        $key += 2;
                    } else {
                        $chr = '';
                        $b[$key + $i] = $codeC[$iindex];
                        if ($iindex == 48) {
                            $chr = 'chr';
                        }//判断chr(48)等于0的情况
                    }
                }
            }
            @$lastkey = end(array_keys($b));//取得数组的最后一个元素的键
            if ($length % 2 > 0) {
                $lastnum = substr($strnum, -1);//取得字符串的最后一个数字
                if ($b[$lastkey] == '0' && $b[$lastkey - 1] == '0') {//判断前面的为字符集B,此时不需要转换字符集
                    $b[$lastkey + 1] = $codeB[$lastnum];
                } else {
                    $b[$lastkey + 1] = "\x7b";
                    $b[$lastkey + 2] = "\x42";
                    $b[$lastkey + 3] = $codeB[$lastnum];
                }
            }
            @$b[15] = chr(end(array_keys($b)) - 15);//得出条形码长度
            $str = implode("", $b);
        } else {//1-14个字符的纯数字和非纯数字的条形码进来这个区间，支持数字，大小写字母，特殊字符例如:  !@#$%^&*()-=+_
            $str = "\x1b\x64\x02\x1d\x48\x32\x1d\x68\x50\x1d\x77\x02\x1d\x6b\x49" . chr($length + 2) . "\x7b\x42" . $strnum;
        }

        return $str;
    }

    /**
     * @param $time
     * @return string
     */
    protected function signature($time)
    {
        // 公共参数，请求公钥
        return sha1($this->user . $this->uKey . $time);
    }
}
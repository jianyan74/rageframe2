<?php namespace Zhuzhichao\IpLocationZh;

use Exception;

class Ip
{
    const IPV4 = 1;
    const IPV6 = 2;

    private $file = NULL;
    private $fileSize = 0;
    private $nodeCount = 0;
    private $nodeOffset = 0;

    private $meta = [];

    private $database = '20180702.ipdb';

    private static $reader = null;

    private static $cached = [];

    /**
     * 查询 IP 信息
     *
     * @param $ip
     * @return mixed|string
     */
    public static function find($ip)
    {
        if (empty($ip) === true) {
            return 'N/A';
        }

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6) === FALSE) {
            throw new \InvalidArgumentException("The value \"$ip\" is not a valid IP address.");
        }

        $nip = gethostbyname($ip);
        if (isset(self::$cached[$nip]) === true) {
            return self::$cached[$nip];
        }

        try {
            $reader = self::init();
            $node   = $reader->findNode($ip);

            if ($node > 0) {
                $data               = $reader->resolve($node);
                $values             = explode("\t", $data);
                $location           = array_slice($values, $reader->meta['languages']['CN'], count($reader->meta['fields']));
                $location[]         = '';
                $locationCode       = self::getLocationCode($location);
                $location[]         = $locationCode;
                self::$cached[$nip] = $location;

                return self::$cached[$nip];
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return 'N/A';
    }

    /**
     * @param $ip
     * @return int
     * @throws \Exception
     */
    private function findNode($ip)
    {
        static $v4offset = 0;
        static $v6offsetCache = [];

        $binary   = inet_pton($ip);
        $bitCount = strlen($binary) * 8; // 32 | 128
        $key      = substr($binary, 0, 2);
        $node     = 0;
        $index    = 0;
        if ($bitCount === 32) {
            if ($v4offset === 0) {
                for ($i = 0; $i < 96 && $node < $this->nodeCount; $i++) {
                    if ($i >= 80) {
                        $idx = 1;
                    } else {
                        $idx = 0;
                    }
                    $node = $this->readNode($node, $idx);
                    if ($node > $this->nodeCount) {
                        return 0;
                    }
                }
                $v4offset = $node;
            } else {
                $node = $v4offset;
            }
        } else {
            if (isset($v6offsetCache[$key])) {
                $index = 16;
                $node  = $v6offsetCache[$key];
            }
        }

        for ($i = $index; $i < $bitCount; $i++) {
            if ($node >= $this->nodeCount) {
                break;
            }

            $node = $this->readNode($node, 1 & ((0xFF & ord($binary[$i >> 3])) >> 7 - ($i % 8)));

            if ($i == 15) {
                $v6offsetCache[$key] = $node;
            }
        }

        if ($node === $this->nodeCount) {
            return 0;
        } elseif ($node > $this->nodeCount) {
            return $node;
        }

        throw new \Exception("find node failed");
    }


    /**
     * @param $node
     * @param $index
     * @return mixed
     * @throws \Exception
     */
    private function readNode($node, $index)
    {
        return unpack('N', $this->read($this->file, $node * 8 + $index * 4, 4))[1];
    }


    /**
     * @param $node
     * @return mixed
     * @throws \Exception
     */
    private function resolve($node)
    {
        $resolved = $node - $this->nodeCount + $this->nodeCount * 8;
        if ($resolved >= $this->fileSize) {
            return NULL;
        }

        $bytes = $this->read($this->file, $resolved, 2);
        $size  = unpack('N', str_pad($bytes, 4, "\x00", STR_PAD_LEFT))[1];

        $resolved += 2;

        return $this->read($this->file, $resolved, $size);
    }

    public function close()
    {
        if (is_resource($this->file) === TRUE) {
            fclose($this->file);
        }
    }

    /**
     * @param $stream
     * @param $offset
     * @param $length
     * @return bool|string
     * @throws \Exception
     */
    private function read($stream, $offset, $length)
    {
        if ($length > 0) {
            if (fseek($stream, $offset + $this->nodeOffset) === 0) {
                $value = fread($stream, $length);
                if (strlen($value) === $length) {
                    return $value;
                }
            }

            throw new \Exception("The Database file read bad data");
        }

        return '';
    }

    /**
     * 初始化单例
     *
     * @return Ip|null
     * @throws Exception
     */
    private static function init()
    {
        if (self::$reader) {
            return self::$reader;
        }

        $reader      = new self();
        $databaseSrc = __DIR__ . '/' . $reader->database;
        if (is_readable($databaseSrc) === FALSE) {
            throw new \InvalidArgumentException("The IP Database file \"{$databaseSrc}\" does not exist or is not readable.");
        }
        $reader->file = @fopen($databaseSrc, 'rb');
        if ($reader->file === FALSE) {
            throw new \InvalidArgumentException("IP Database File opening \"{$databaseSrc}\".");
        }
        $reader->fileSize = @filesize($databaseSrc);
        if ($reader->fileSize === FALSE) {
            throw new \UnexpectedValueException("Error determining the size of \"{$databaseSrc}\".");
        }

        $metaLength = unpack('N', fread($reader->file, 4))[1];
        $text       = fread($reader->file, $metaLength);

        $reader->meta = (array)json_decode($text, true);

        if (!isset($reader->meta['fields']) || !isset($reader->meta['languages'])) {
            throw new \Exception('IP Database metadata error.');
        }

        $fileSize = 4 + $metaLength + $reader->meta['total_size'];
        if ($fileSize != $reader->fileSize) {
            throw  new \Exception('IP Database size error.');
        }

        $reader->nodeCount  = $reader->meta['node_count'];
        $reader->nodeOffset = 4 + $metaLength;

        self::$reader = $reader;

        return $reader;
    }

    /**
     * 获取城市的行政区划编码
     *
     * @param $arr
     * @return string
     */
    private static function getLocationCode($arr)
    {
        $province     = $arr[1];
        $city         = $arr[2];
        $locationCode = self::locations();
        $code         = "";
        if (!isset($locationCode[$province])) {
            return $code;
        }
        $code = $locationCode[$province]["code"];
        if (!empty($city)) {
            foreach ($locationCode[$province]["cities"] as $key => $loc) {
                if (strpos($key, $city) !== false) {
                    $code = $loc;
                    break;
                }
            }
        }

        return $code;
    }

    /**
     * 城市的行政区划信息
     *
     * @return array
     */
    public static function locations()
    {
        $locationCode        = [];
        $locationCode["北京"]  = [
            "code"   => "110000",
            "cities" => [],
        ];
        $locationCode["天津"]  = [
            "code"   => "120000",
            "cities" => [],
        ];
        $locationCode["河北"]  = [
            "code"   => "130000",
            "cities" => ["石家庄市" => "130100", "唐山市" => "130200", "秦皇岛市" => "130300", "邯郸市" => "130400", "邢台市" => "130500", "保定市" => "130600", "张家口市" => "130700", "承德市" => "130800", "沧州市" => "130900", "廊坊市" => "131000", "衡水市" => "131100"],
        ];
        $locationCode["山西"]  = [
            "code"   => "140000",
            "cities" => ["太原市" => "140100", "大同市" => "140200", "阳泉市" => "140300", "长治市" => "140400", "晋城市" => "140500", "朔州市" => "140600", "晋中市" => "140700", "运城市" => "140800", "忻州市" => "140900", "临汾市" => "141000", "吕梁市" => "141100"],
        ];
        $locationCode["内蒙古"] = [
            "code"   => "150000",
            "cities" => ["呼和浩特市" => "150100", "包头市" => "150200", "乌海市" => "150300", "赤峰市" => "150400", "通辽市" => "150500", "鄂尔多斯市" => "150600", "呼伦贝尔市" => "150700", "巴彦淖尔市" => "150800", "乌兰察布市" => "150900", "兴安盟" => "152200", "锡林郭勒盟" => "152500", "阿拉善盟" => "152900"],
        ];
        $locationCode["辽宁"]  = [
            "code"   => "210000",
            "cities" => ["沈阳市" => "210100", "大连市" => "210200", "鞍山市" => "210300", "抚顺市" => "210400", "本溪市" => "210500", "丹东市" => "210600", "锦州市" => "210700", "营口市" => "210800", "阜新市" => "210900", "辽阳市" => "211000", "盘锦市" => "211100", "铁岭市" => "211200", "朝阳市" => "211300", "葫芦岛市" => "211400"],
        ];
        $locationCode["吉林"]  = [
            "code"   => "220000",
            "cities" => ["长春市" => "220100", "吉林市" => "220200", "四平市" => "220300", "辽源市" => "220400", "通化市" => "220500", "白山市" => "220600", "松原市" => "220700", "白城市" => "220800", "延边朝鲜族自治州" => "222400"],
        ];
        $locationCode["黑龙江"] = [
            "code"   => "230000",
            "cities" => ["哈尔滨市" => "230100", "齐齐哈尔市" => "230200", "鸡西市" => "230300", "鹤岗市" => "230400", "双鸭山市" => "230500", "大庆市" => "230600", "伊春市" => "230700", "佳木斯市" => "230800", "七台河市" => "230900", "牡丹江市" => "231000", "黑河市" => "231100", "绥化市" => "231200", "大兴安岭地区" => "232700"],
        ];
        $locationCode["上海"]  = [
            "code"   => "310000",
            "cities" => [],
        ];
        $locationCode["江苏"]  = [
            "code"   => "320000",
            "cities" => ["南京市" => "320100", "无锡市" => "320200", "徐州市" => "320300", "常州市" => "320400", "苏州市" => "320500", "南通市" => "320600", "连云港市" => "320700", "淮安市" => "320800", "盐城市" => "320900", "扬州市" => "321000", "镇江市" => "321100", "泰州市" => "321200", "宿迁市" => "321300"],
        ];
        $locationCode["浙江"]  = [
            "code"   => "330000",
            "cities" => ["杭州市" => "330100", "宁波市" => "330200", "温州市" => "330300", "嘉兴市" => "330400", "湖州市" => "330500", "绍兴市" => "330600", "金华市" => "330700", "衢州市" => "330800", "舟山市" => "330900", "台州市" => "331000", "丽水市" => "331100"],
        ];
        $locationCode["安徽"]  = [
            "code"   => "340000",
            "cities" => ["合肥市" => "340100", "芜湖市" => "340200", "蚌埠市" => "340300", "淮南市" => "340400", "马鞍山市" => "340500", "淮北市" => "340600", "铜陵市" => "340700", "安庆市" => "340800", "黄山市" => "341000", "滁州市" => "341100", "阜阳市" => "341200", "宿州市" => "341300", "巢湖市" => "341400", "六安市" => "341500", "亳州市" => "341600", "池州市" => "341700", "宣城市" => "341800"],
        ];
        $locationCode["福建"]  = [
            "code"   => "350000",
            "cities" => ["福州市" => "350100", "厦门市" => "350200", "莆田市" => "350300", "三明市" => "350400", "泉州市" => "350500", "漳州市" => "350600", "南平市" => "350700", "龙岩市" => "350800", "宁德市" => "350900"],
        ];
        $locationCode["江西"]  = [
            "code"   => "360000",
            "cities" => ["南昌市" => "360100", "景德镇市" => "360200", "萍乡市" => "360300", "九江市" => "360400", "新余市" => "360500", "鹰潭市" => "360600", "赣州市" => "360700", "吉安市" => "360800", "宜春市" => "360900", "抚州市" => "361000", "上饶市" => "361100"],
        ];
        $locationCode["山东"]  = [
            "code"   => "370000",
            "cities" => ["济南市" => "370100", "青岛市" => "370200", "淄博市" => "370300", "枣庄市" => "370400", "东营市" => "370500", "烟台市" => "370600", "潍坊市" => "370700", "济宁市" => "370800", "泰安市" => "370900", "威海市" => "371000", "日照市" => "371100", "莱芜市" => "371200", "临沂市" => "371300", "德州市" => "371400", "聊城市" => "371500", "滨州市" => "371600", "菏泽市" => "371700"],
        ];
        $locationCode["河南"]  = [
            "code"   => "410000",
            "cities" => ["郑州市" => "410100", "开封市" => "410200", "洛阳市" => "410300", "平顶山市" => "410400", "安阳市" => "410500", "鹤壁市" => "410600", "新乡市" => "410700", "焦作市" => "410800", "濮阳市" => "410900", "许昌市" => "411000", "漯河市" => "411100", "三门峡市" => "411200", "南阳市" => "411300", "商丘市" => "411400", "信阳市" => "411500", "周口市" => "411600", "驻马店市" => "411700", "济源市" => "419001"],
        ];
        $locationCode["湖北"]  = [
            "code"   => "420000",
            "cities" => ["武汉市" => "420100", "黄石市" => "420200", "十堰市" => "420300", "宜昌市" => "420500", "襄樊市" => "420600", "鄂州市" => "420700", "荆门市" => "420800", "孝感市" => "420900", "荆州市" => "421000", "黄冈市" => "421100", "咸宁市" => "421200", "随州市" => "421300", "恩施土家族苗族自治州" => "422800", "仙桃市" => "429004", "潜江市" => "429005", "天门市" => "429006", "神农架林区" => "429021"],
        ];
        $locationCode["湖南"]  = [
            "code"   => "430000",
            "cities" => ["长沙市" => "430100", "株洲市" => "430200", "湘潭市" => "430300", "衡阳市" => "430400", "邵阳市" => "430500", "岳阳市" => "430600", "常德市" => "430700", "张家界市" => "430800", "益阳市" => "430900", "郴州市" => "431000", "永州市" => "431100", "怀化市" => "431200", "娄底市" => "431300", "湘西土家族苗族自治州" => "433100"],
        ];
        $locationCode["广东"]  = [
            "code"   => "440000",
            "cities" => ["广州市" => "440100", "韶关市" => "440200", "深圳市" => "440300", "珠海市" => "440400", "汕头市" => "440500", "佛山市" => "440600", "江门市" => "440700", "湛江市" => "440800", "茂名市" => "440900", "肇庆市" => "441200", "惠州市" => "441300", "梅州市" => "441400", "汕尾市" => "441500", "河源市" => "441600", "阳江市" => "441700", "清远市" => "441800", "东莞市" => "441900", "中山市" => "442000", "潮州市" => "445100", "揭阳市" => "445200", "云浮市" => "445300"],
        ];
        $locationCode["广西"]  = [
            "code"   => "450000",
            "cities" => ["南宁市" => "450100", "柳州市" => "450200", "桂林市" => "450300", "梧州市" => "450400", "北海市" => "450500", "防城港市" => "450600", "钦州市" => "450700", "贵港市" => "450800", "玉林市" => "450900", "百色市" => "451000", "贺州市" => "451100", "河池市" => "451200", "来宾市" => "451300", "崇左市" => "451400"],
        ];
        $locationCode["海南"]  = [
            "code"   => "460000",
            "cities" => ["海口市" => "460100", "三亚市" => "460200", "五指山市" => "469001", "琼海市" => "469002", "儋州市" => "469003", "文昌市" => "469005", "万宁市" => "469006", "东方市" => "469007", "定安县" => "469021", "屯昌县" => "469022", "澄迈县" => "469023", "临高县" => "469024", "白沙黎族自治县" => "469025", "昌江黎族自治县" => "469026", "乐东黎族自治县" => "469027", "陵水黎族自治县" => "469028", "保亭黎族苗族自治县" => "469029", "琼中黎族苗族自治县" => "469030", "西沙群岛" => "469031", "南沙群岛" => "469032", "中沙群岛的岛礁及其海域" => "469033"],
        ];
        $locationCode["重庆"]  = [
            "code"   => "500000",
            "cities" => [],
        ];
        $locationCode["四川"]  = [
            "code"   => "510000",
            "cities" => ["成都市" => "510100", "自贡市" => "510300", "攀枝花市" => "510400", "泸州市" => "510500", "德阳市" => "510600", "绵阳市" => "510700", "广元市" => "510800", "遂宁市" => "510900", "内江市" => "511000", "乐山市" => "511100", "南充市" => "511300", "眉山市" => "511400", "宜宾市" => "511500", "广安市" => "511600", "达州市" => "511700", "雅安市" => "511800", "巴中市" => "511900", "资阳市" => "512000", "阿坝藏族羌族自治州" => "513200", "甘孜藏族自治州" => "513300", "凉山彝族自治州" => "513400"],
        ];
        $locationCode["贵州"]  = [
            "code"   => "520000",
            "cities" => ["贵阳市" => "520100", "六盘水市" => "520200", "遵义市" => "520300", "安顺市" => "520400", "铜仁地区" => "522200", "黔西南布依族苗族自治州" => "522300", "毕节地区" => "522400", "黔东南苗族侗族自治州" => "522600", "黔南布依族苗族自治州" => "522700"],
        ];
        $locationCode["云南"]  = [
            "code"   => "530000",
            "cities" => ["昆明市" => "530100", "曲靖市" => "530300", "玉溪市" => "530400", "保山市" => "530500", "昭通市" => "530600", "丽江市" => "530700", "普洱市" => "530800", "临沧市" => "530900", "楚雄彝族自治州" => "532300", "红河哈尼族彝族自治州" => "532500", "文山壮族苗族自治州" => "532600", "西双版纳傣族自治州" => "532800", "大理白族自治州" => "532900", "德宏傣族景颇族自治州" => "533100", "怒江傈僳族自治州" => "533300", "迪庆藏族自治州" => "533400"],
        ];
        $locationCode["西藏"]  = [
            "code"   => "540000",
            "cities" => ["拉萨市" => "540100", "昌都地区" => "542100", "山南地区" => "542200", "日喀则地区" => "542300", "那曲地区" => "542400", "阿里地区" => "542500", "林芝地区" => "542600"],
        ];
        $locationCode["陕西"]  = [
            "code"   => "610000",
            "cities" => ["西安市" => "610100", "铜川市" => "610200", "宝鸡市" => "610300", "咸阳市" => "610400", "渭南市" => "610500", "延安市" => "610600", "汉中市" => "610700", "榆林市" => "610800", "安康市" => "610900", "商洛市" => "611000"],
        ];
        $locationCode["甘肃"]  = [
            "code"   => "620000",
            "cities" => ["兰州市" => "620100", "嘉峪关市" => "620200", "金昌市" => "620300", "白银市" => "620400", "天水市" => "620500", "武威市" => "620600", "张掖市" => "620700", "平凉市" => "620800", "酒泉市" => "620900", "庆阳市" => "621000", "定西市" => "621100", "陇南市" => "621200", "临夏回族自治州" => "622900", "甘南藏族自治州" => "623000"],
        ];
        $locationCode["青海"]  = [
            "code"   => "630000",
            "cities" => ["西宁市" => "630100", "海东地区" => "632100", "海北藏族自治州" => "632200", "黄南藏族自治州" => "632300", "海南藏族自治州" => "632500", "果洛藏族自治州" => "632600", "玉树藏族自治州" => "632700", "海西蒙古族藏族自治州" => "632800"],
        ];
        $locationCode["宁夏"]  = [
            "code"   => "640000",
            "cities" => ["银川市" => "640100", "石嘴山市" => "640200", "吴忠市" => "640300", "固原市" => "640400", "中卫市" => "640500"],
        ];
        $locationCode["新疆"]  = [
            "code"   => "650000",
            "cities" => ["乌鲁木齐市" => "650100", "克拉玛依市" => "650200", "吐鲁番地区" => "652100", "哈密地区" => "652200", "昌吉回族自治州" => "652300", "博尔塔拉蒙古自治州" => "652700", "巴音郭楞蒙古自治州" => "652800", "阿克苏地区" => "652900", "克孜勒苏柯尔克孜自治州" => "653000", "喀什地区" => "653100", "和田地区" => "653200", "伊犁哈萨克自治州" => "654000", "塔城地区" => "654200", "阿勒泰地区" => "654300", "石河子市" => "659001", "阿拉尔市" => "659002", "图木舒克市" => "659003", "五家渠市" => "659004"],
        ];

        return $locationCode;
    }
}

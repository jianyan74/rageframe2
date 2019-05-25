# yii2-oauth
===

### composer.json
---
```json
"require": {
        "xj/yii2-oauth": "~2.0.0"
},
```

### config
---
```php
'components' => [
    'authClientCollection' => [
        'class' => 'yii\authclient\Collection',
        'clients' => [
            'qq' => [
                'class' => 'xj\oauth\QqAuth',
                'clientId' => '111',
                'clientSecret' => '111',

            ],
            'weibo' => [
                'class' => 'xj\oauth\WeiboAuth',
                'clientId' => '111',
                'clientSecret' => '111',
            ],
            'weixin' => [
                'class' => 'xj\oauth\WeixinAuth',
                'clientId' => '111',
                'clientSecret' => '111',
            ],
            'renren' => [
                'class' => 'xj\oauth\RenrenAuth',
                'clientId' => '111',
                'clientSecret' => '111',
            ],
            'douban' => [
                'class' => 'xj\oauth\DoubanAuth',
                'clientId' => '111',
                'clientSecret' => '111',
            ],
            'weixin-mp' => [
                'class' => 'xj\oauth\WeixinMpAuth',
                'clientId' => '111',
                'clientSecret' => '111',
            ],
            'amazon' => [
                'class' => 'xj\oauth\AmazonAuth',
                'clientId' => '',
                'clientSecret' => '',
            ],
        ]
    ]
    ...
]
```

### Controller
---
```php
class SiteController extends Controller
{
    public function actions()
    {
        return [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'successCallback'],
            ],
        ];
    }

    /**
     * Success Callback
     * @param QqAuth|WeiboAuth $client
     * @see http://wiki.connect.qq.com/get_user_info
     * @see http://stuff.cebe.cc/yii2docs/yii-authclient-authaction.html
     */
    public function successCallback($client) {
        $id = $client->getId(); // qq | sina | weixin
        $attributes = $client->getUserAttributes(); // basic info
        $openid = $client->getOpenid(); //user openid
        $userInfo = $client->getUserInfo(); // user extend info
        var_dump($id, $attributes, $openid, $userInfo);
    }
}
```

### View
---
```php
<?=
yii\authclient\widgets\AuthChoice::widget([
    'baseAuthUrl' => ['site/auth'],
    'popupMode' => false,
])
?>
```


### WeixinMp
```php
$weixinMp = Yii::$app->authClientCollection->getClient('weixin-mp');

// http://mp.weixin.qq.com/wiki/11/0e4b294685f817b95cbed85ba5e82b8f.html
// getAccessToken
$accessTokenResult = $weixinMp->getMpAccessToken();
if ($accessTokenResult->validate()) {
    $accessTokenResult->access_token;
    $accessTokenResult->expires_in;
    $accessTokenResult->getAccessToken(); // WeixinMpToken
} else {
    var_dump($accessTokenResult->getErrCodeText());
    var_dump($accessTokenResult->getErrors());
}

// http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html#.E8.8E.B7.E5.8F.96api_ticket
// getTicket
$accessTokenResult = $weixinMp->getMpAccessToken();
$ticketType = 'jsapi'; // wx_card
$ticketResult = $weixinMp->getTicket($accessTokenResult->access_token, $ticketType);
if ($ticketResult->validate()) {
    $accessTokenResult->ticket; // TicketString
} else {
    var_dump($ticketResult->getErrCodeText());
    var_dump($ticketResult->getErrors());
}
```

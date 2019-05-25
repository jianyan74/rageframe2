QQ Exmail
===

Configure
---
```php
'components' => [
    'authClientCollection' => [
        'class' => 'yii\authclient\Collection',
        'clients' => [
            'qqexmail' => [
                'class' => 'xj\oauth\QqExmailAuth',
                'clientId' => '', //enter your id
                'clientSecret' => '', //enter your key
            ],
        ]
    ],
]
```

ExampleCode
---
```php
$testEmail = 'test1@domain.com';
$testAddEmail = 'test2@domain.com';
$testAddEmailName = 'testAddName';
$testGroupName = 'testGroupName';
$testGroupAdmin = 'testGroupAdmin_MustBeUnused@nfcmag.com';

$authClientCollection = Yii::$app->authClientCollection;
/* @var $authClientCollection \yii\authclient\Collection */
$exmailAuthClient = $authClientCollection->getClient('qqexmail');
/* @var $exmailAuthClient \xj\oauth\QqExmailAuth */

//get Admin AccessToken
$accessToken = $exmailAuthClient->getAccessToken();
/* @var $accessToken yii\authclient\OAuthToken */
var_dump('accessToken', $accessToken->getToken());

try {
    //get OneKey Login Url
    $oneKeyLoginUrl = $exmailAuthClient->getOneKeyLogin($testEmail);
    var_dump('oneKeyLoginUrl', $oneKeyLoginUrl);

    //get Member AuthKey
    $authKey = $exmailAuthClient->getMemberAuthKey($testEmail);
    var_dump('authKey', $authKey);

    //get Member Info
    $memberInfo = $exmailAuthClient->getMemberInfo($testEmail);
    var_dump('memberInfo', $memberInfo);

    $statusAvailableResult = $exmailAuthClient->getMemberStatusAvailable($testAddEmail);
    var_dump('statusAvailableResult', $statusAvailableResult);

    //add Member
    $syncResult = $exmailAuthClient->syncMember($testAddEmail, [
        'action' => \xj\oauth\QqExmailAuth::ACTION_ADD,
        'name' => $testAddEmailName,
        'password' => md5(uniqid()),
        'gender' => \xj\oauth\QqExmailAuth::GENDER_MALE,
        'md5' => \xj\oauth\QqExmailAuth::MD5_ENCYPT,
        'OpenType' => \xj\oauth\QqExmailAuth::OPEN_TYPE_ENABLE,
    ]);
    var_dump('syncResult', $syncResult);

    //get Member Status
    $statusResult = $exmailAuthClient->getMemberStatus($testAddEmail);
    var_dump('statusResult', $statusResult);

    //get Member List
    $memberListByVersionResult = $exmailAuthClient->getMemberListByVersion(0);
    var_dump('memberListByVersionResult', $memberListByVersionResult);

    //未读邮件
    $mailUnreadCount = $exmailAuthClient->getMailNewCount($testAddEmail);
    var_dump('mailUnreadCount', $mailUnreadCount);

    //Add Group
    $addGroupResult = $exmailAuthClient->addGroup($testGroupName, $testGroupAdmin, \xj\oauth\QqExmailAuth::GROUP_STATUS_ALL, $testEmail);
    var_dump('addGroupResult', $addGroupResult);

    //Add Group Member
    $addGroupMemberResult = $exmailAuthClient->addGroupMember($testGroupAdmin, $testAddEmail);
    var_dump('addGroupMemberResult', $addGroupMemberResult);

    //Del Group Member
    $delGroupMemberResult = $exmailAuthClient->deleteGroupMember($testGroupAdmin, $testAddEmail);
    var_dump('delGroupMemberResult', $delGroupMemberResult);

    //Del Group
    $delGroupResult = $exmailAuthClient->delGroup($testGroupAdmin);
    var_dump('delGroupResult', $delGroupResult);

    //delete Member
    $deleteMemberResult = $exmailAuthClient->delMember($testAddEmail);
    var_dump('delete Member', $deleteMemberResult);

} catch (\xj\oauth\exception\QqExmailException $ex) {
    //function Request Fail
    var_dump($ex->getMessage(), $ex->getCode());
} catch (\yii\authclient\InvalidResponseException $ex) {
    //fetchAccessToken Fail
    var_dump($ex->getMessage(), $ex->getCode());
}
```
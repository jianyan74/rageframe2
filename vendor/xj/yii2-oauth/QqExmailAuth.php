<?php

namespace xj\oauth;

use xj\oauth\exception\QqExmailException;
use yii\authclient\InvalidResponseException;
use yii\authclient\OAuth2;
use yii\authclient\OAuthToken;

/**
 * Tencent Exmail OAuth
 * 腾讯企业邮箱API OAUTH
 * @version 1.4
 * @author xjflyttp <xjflyttp@gmail.com>
 * @see PDF
 * @see http://exmail.qq.com/cgi-bin/download?path=bizopenapidoc&filename=%cc%da%d1%b6%c6%f3%d2%b5%d3%ca%cf%e4OpenApi%d0%ad%d2%e9v1.4.pdf
 */
class QqExmailAuth extends OAuth2
{

    //Action
    const ACTION_DEL = 1;
    const ACTION_ADD = 2;
    const ACTION_MOD = 3;
    //Gender
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;
    //OpenType
    const OPEN_TYPE_UNSET = 0;
    const OPEN_TYPE_ENABLE = 1;
    const OPEN_TYPE_DISABLE = 2;
    //Md5
    const MD5_PLAINTEXT = 0;
    const MD5_ENCYPT = 1;
    //EmailAvailable
    const ACCOUNT_TYPE_UNAVAILABLE = -1; //帐号名无效
    const ACCOUNT_TYPE_AVAILABLE = 0; //帐号名没被占用
    const ACCOUNT_TYPE_MAIN = 1; //主帐号名
    const ACCOUNT_TYPE_ALIAS = 2; //别名账户
    const ACCOUNT_TYPE_MAILGROUP = 3; //群组账户
    //GroupStatus
    const GROUP_STATUS_ALL = 'all';
    const GROUP_STATUS_INNER = 'inner';
    const GROUP_STATUS_GROUP = 'group';
    const GROUP_STATUS_LIST = 'list';

    public $authUrl = '';
    public $tokenUrl = 'https://exmail.qq.com/cgi-bin/token';
    public $apiBaseUrl = 'http://openapi.exmail.qq.com:12211';
    public $templateOneKeyLoginUrl = 'https://exmail.qq.com/cgi-bin/login?fun=bizopenssologin&method=bizauth&agent=<agent>&user=<email>&ticket=<ticket>';

    protected function initUserAttributes()
    {
        return [];
    }

    /**
     * Fetches access token
     * @param string $authCode ignore in this time
     * @param array $params additional request params.
     * @return OAuthToken access token.
     * @throws InvalidResponseException
     */
    public function fetchAccessToken($authCode = null, array $params = [])
    {
        $defaultParams = [
            'grant_type' => 'client_credentials',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
        ];

        $response = $this->sendRequest('POST', $this->tokenUrl, array_merge($defaultParams, $params));
        $token = $this->createToken(['params' => $response]);
        $this->setAccessToken($token);

        return $token;
    }

    /**
     * @return OAuthToken auth token instance.
     * @throws QqExmailException
     */
    public function getAccessToken()
    {
        $accessToken = parent::getAccessToken();
        if (null === $accessToken || !$accessToken->getIsValid()) {
            $accessToken = $this->fetchAccessToken();
        }
        if (null === $accessToken) {
            throw new QqExmailException('getAccessToken Fail.');
        }
        return $accessToken;
    }

    /**
     *
     * @param string $email MemberEmail
     * @return string Member Auth Key
     * @throws QqExmailException
     */
    public function getMemberAuthKey($email)
    {
        $result = $this->api('openapi/mail/authkey', 'GET', ['alias' => $email]);

        if (isset($result['errcode']) || isset($result['error'])) {
            throw new QqExmailException($result['error'], $result['errcode']);
        }

        return $result['auth_key'];
    }

    /**
     *
     * @param string $email login EMAIL
     * @param string $ticket getAuthKey()
     * @return string Login Web Url
     * @throws QqExmailException
     */
    public function getOneKeyLogin($email, $ticket = null)
    {
        $urlTemplate = $this->templateOneKeyLoginUrl;
        $agent = $this->clientId;
        if (null === $ticket) {
            $ticket = $this->getMemberAuthKey($email);
        }
        $requestUrl = str_replace([
            '<agent>', '<email>', '<ticket>'
        ], [
            $agent, $email, $ticket
        ], $urlTemplate);
        return $requestUrl;
    }

    /**
     *
     * @param string $email MemberEmail
     * @return string MemberInfo
     * @throws QqExmailException
     */
    public function getMemberInfo($email)
    {
        $result = $this->api('openapi/user/get', 'POST', ['alias' => $email]);
        if (isset($result['errcode']) || isset($result['error'])) {
            throw new QqExmailException($result['error'], $result['errcode']);
        }
        return $result;
    }

    /**
     * Add Mod Del Member
     * @param string $email
     * @param [] $options
     * @return []
     * @throws QqExmailException
     */
    public function syncMember($email, $options)
    {
        $options['alias'] = $email;
        $result = $this->api('openapi/user/sync', 'POST', $options);
        if (isset($result['errcode']) || isset($result['error'])) {
            throw new QqExmailException($result['error'], $result['errcode']);
        }
        return true;
    }

    /**
     *
     * @param string $email
     * @return boolean
     */
    public function delMember($email)
    {
        try {
            $this->syncMember($email, ['action' => self::ACTION_DEL]);
        } catch (QqExmailException $ex) {
            return false;
        }
        return true;
    }

    /**
     * Add Mod Del Patry
     * @param [] $options  Del/Add only DstPath , Mod need SrcPath & DstPath
     * @return []
     * @throws QqExmailException
     */
    public function syncParty($options)
    {
        $result = $this->api('openapi/party/sync', 'POST', $options);
        if (isset($result['errcode']) || isset($result['error'])) {
            throw new QqExmailException($result['error'], $result['errcode']);
        }
        return true;
    }

    /**
     *
     * @param string $partyPath
     * @return []
     * @throws QqExmailException
     */
    public function getPartList($partyPath)
    {
        $options = [
            'partypath' => $partyPath,
        ];
        $result = $this->api('openapi/party/list', 'POST', $options);
        if (isset($result['errcode']) || isset($result['error'])) {
            throw new QqExmailException($result['error'], $result['errcode']);
        }
        return $result;
    }

    /**
     *
     * @param string $partyPath
     * @return []
     * @throws QqExmailException
     */
    public function getMemberListByPartyPath($partyPath)
    {
        $options = [
            'partypath' => $partyPath,
        ];
        $result = $this->api('openapi/partyuser/list', 'POST', $options);
        if (isset($result['errcode']) || isset($result['error'])) {
            throw new QqExmailException($result['error'], $result['errcode']);
        }
        return $result;
    }

    /**
     *
     * @param string $email
     * @return bool
     * @throws QqExmailException
     */
    public function getMemberStatus($email)
    {
        $options = [
            'email' => $email,
        ];
        $result = $this->api('openapi/user/check', 'POST', $options);
        if (isset($result['errcode']) || isset($result['error']) || !isset($result['List'])) {
            throw new QqExmailException($result['error'], $result['errcode']);
        }
        return intval($result['List'][0]['Type']);
    }

    /**
     *
     * @param string $email
     * @return bool
     */
    public function getMemberStatusAvailable($email)
    {
        $type = $this->getMemberStatus($email);
        return $type === self::ACCOUNT_TYPE_AVAILABLE;
    }

    /**
     *
     * @param int $ver 0=all
     * @return []
     * @throws QqExmailException
     */
    public function getMemberListByVersion($ver)
    {
        $options = [
            'Ver' => $ver,
        ];
        $result = $this->api('openapi/user/list', 'POST', $options);
        if (isset($result['errcode']) || isset($result['error'])) {
            throw new QqExmailException($result['error'], $result['errcode']);
        }
        return $result;
    }

    /**
     *
     * @param string $email
     * @return int
     * @throws QqExmailException
     */
    public function getMailNewCount($email)
    {
        $options = [
            'alias' => $email,
        ];
        $result = $this->api('openapi/mail/newcount', 'POST', $options);
        if (isset($result['errcode']) || isset($result['error']) || !isset($result['NewCount'])) {
            throw new QqExmailException($result['error'], $result['errcode']);
        }
        return intval($result['NewCount']);
    }

    /**
     *
     * @param string $groupName 组名
     * @param string $groupAdmin 组管理员(需要使用一个域中不存在的邮箱地址)
     * @param string $status 组状态
     * @param string $members 成员列表
     * @return bool
     * @throws QqExmailException
     */
    public function addGroup($groupName, $groupAdmin, $status, $members)
    {
        $options = [
            'group_name' => $groupName,
            'group_admin' => $groupAdmin,
            'status' => $status,
            'members' => $members,
        ];
        $result = $this->api('openapi/group/add', 'POST', $options);
        if (isset($result['errcode']) || isset($result['error'])) {
            throw new QqExmailException($result['error'], $result['errcode']);
        }
        return true;
    }

    /**
     *
     * @param string $groupAlias AdminEmail
     * @return boolean
     * @throws QqExmailException
     */
    public function delGroup($groupAlias)
    {
        $options = [
            'group_alias' => $groupAlias,
        ];
        $result = $this->api('openapi/group/delete', 'POST', $options);
        if (isset($result['errcode']) || isset($result['error'])) {
            throw new QqExmailException($result['error'], $result['errcode']);
        }
        return true;
    }

    /**
     *
     * @param string $groupAlias AdminEmail
     * @param string $members MemberEmail
     * @return boolean
     * @throws QqExmailException
     */
    public function addGroupMember($groupAlias, $members)
    {
        $options = [
            'group_alias' => $groupAlias,
            'members' => $members,
        ];
        $result = $this->api('openapi/group/addmember', 'POST', $options);
        if (isset($result['errcode']) || isset($result['error'])) {
            throw new QqExmailException($result['error'], $result['errcode']);
        }
        return true;
    }

    /**
     *
     * @param string $groupAlias
     * @param string $members
     * @return boolean
     * @throws QqExmailException
     */
    public function deleteGroupMember($groupAlias, $members)
    {
        $options = [
            'group_alias' => $groupAlias,
            'members' => $members,
        ];
        $result = $this->api('openapi/group/deletemember', 'POST', $options);
        if (isset($result['errcode']) || isset($result['error'])) {
            throw new QqExmailException($result['error'], $result['errcode']);
        }
        var_dump($result);
        return true;
    }

    /**
     *
     * @param int $ver
     * @return []
     * @throws QqExmailException
     * @see PDF
     */
    public function listen($ver)
    {
        $options = [
            'Ver' => $ver,
        ];
        $result = $this->api('openapi/listen', 'POST', $options);
        if (isset($result['errcode']) || isset($result['error'])) {
            throw new QqExmailException($result['error'], $result['errcode']);
        }
        return $result;
    }

}

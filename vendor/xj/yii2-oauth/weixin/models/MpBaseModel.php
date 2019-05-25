<?php
namespace xj\oauth\weixin\models;

/**
 * @author xjflyttp <xjflyttp@gmail.com>
 */
class MpBaseModel extends BaseModel
{
    const ERRORCODE_SUCCESS = 0;
    const UNKNOW_ERROR = 99999;
    const INVALID_CREDENTIAL = 40001;
    const INVALID_GRANT_TYPE = 40002;
    const INVALID_OPENID = 40003;
    const INVALID_MEDIA_TYPE = 40004;
    const INVALID_MEDIA_ID = 40007;
    const INVALID_MESSAGE_TYPE = 40008;
    const INVALID_IMAGE_SIZE = 40009;
    const INVALID_VOICE_SIZE = 40010;
    const INVALID_VIDEO_SIZE = 40011;
    const INVALID_THUMB_SIZE = 40012;
    const INVALID_APPID = 40013;
    const INVALID_ACCESS_TOKEN = 40014;
    const INVALID_MENU_TYPE = 40015;
    const INVALID_BUTTON_SIZE = 40016;
    const INVALID_BUTTON_TYPE = 40017;
    const INVALID_BUTTON_NAME_SIZE = 40018;
    const INVALID_BUTTON_KEY_SIZE = 40019;
    const INVALID_BUTTON_URL_SIZE = 40020;
    const INVALID_SUB_BUTTON_SIZE = 40023;
    const INVALID_SUB_BUTTON_TYPE = 40024;
    const INVALID_SUB_BUTTON_NAME_SIZE = 40025;
    const INVALID_SUB_BUTTON_KEY_SIZE = 40026;
    const INVALID_SUB_BUTTON_URL_SIZE = 40027;
    const INVALID_CODE = 40029;
    const INVALID_REFRESH_TOKEN = 40030;
    const INVALID_TEMPLATE_ID_SIZE = 40036;
    const INVALID_TEMPLATE_ID = 40037;
    const INVALID_URL_SIZE = 40039;
    const INVALID_URL_DOMAIN = 40048;
    const INVALID_SUB_BUTTON_URL_DOMAIN = 40054;
    const INVALID_BUTTON_URL_DOMAIN = 40055;
    const INVALID_URL = 40066;
    const ACCESS_TOKEN_MISSING = 41001;
    const APPID_MISSING = 41002;
    const REFRESH_TOKEN_MISSING = 41003;
    const APPSECRET_MISSING = 41004;
    const MEDIA_DATA_MISSING = 41005;
    const MEDIA_ID_MISSING = 41006;
    const SUB_MENU_DATA_MISSING = 41007;
    const MISSING_CODE = 41008;
    const MISSING_OPENID = 41009;
    const MISSING_URL = 41010;
    const ACCESS_TOKEN_EXPIRED = 42001;
    const REFRESH_TOKEN_EXPIRED = 42002;
    const CODE_EXPIRED = 42003;
    const REQUIRE_GET_METHOD = 43001;
    const REQUIRE_POST_METHOD = 43002;
    const REQUIRE_HTTPS = 43003;
    const REQUIRE_SUBSCRIBE = 43004;
    const EMPTY_MEDIA_DATA = 44001;
    const EMPTY_POST_DATA = 44002;
    const EMPTY_NEWS_DATA = 44003;
    const EMPTY_CONTENT = 44004;
    const EMPTY_LIST_SIZE = 44005;
    const MEDIA_SIZE_OUT_OF_LIMIT = 45001;
    const CONTENT_SIZE_OUT_OF_LIMIT = 45002;
    const TITLE_SIZE_OUT_OF_LIMIT = 45003;
    const DESCRIPTION_SIZE_OUT_OF_LIMIT = 45004;
    const URL_SIZE_OUT_OF_LIMIT = 45005;
    const PICURL_SIZE_OUT_OF_LIMIT = 45006;
    const PLAYTIME_OUT_OF_LIMIT = 45007;
    const ARTICLE_SIZE_OUT_OF_LIMIT = 45008;
    const API_FREQ_OUT_OF_LIMIT = 45009;
    const CREATE_MENU_LIMIT = 45010;
    const API_LIMIT = 45011;
    const TEMPLATE_SIZE_OUT_OF_LIMIT = 45012;
    const CANT_MODIFY_SYS_GROUP = 45016;
    const CANT_SET_GROUP_NAME_TOO_LONG_SYS_GROUP = 45017;
    const TOO_MANY_GROUP_NOW_NO_NEED_TO_ADD_NEW = 45018;
    const API_UNAUTHORIZED = 50001;

    public $errcode;
    public $errmsg;

    public function rules()
    {
        return [
            [['errcode', 'errmsg'], 'safe'],
            ['errcode', 'checkCode'],
        ];
    }

    public function checkCode($attributeName)
    {
        if ($this->isFail()) {
            $this->addError($attributeName, $this->errmsg);
        }
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return null === $this->errcode || intval($this->errcode) === self::ERRORCODE_SUCCESS;
    }

    /**
     * @return bool
     */
    public function isFail()
    {
        return !$this->isSuccess();
    }


    public static function getErrCodeOptions()
    {
        return [
            self::INVALID_CREDENTIAL => '不合法的调用凭证',
            self::INVALID_GRANT_TYPE => '不合法的grant_type',
            self::INVALID_OPENID => '不合法的OpenID',
            self::INVALID_MEDIA_TYPE => '不合法的媒体文件类型',
            self::INVALID_MEDIA_ID => '不合法的media_id',
            self::INVALID_MESSAGE_TYPE => '不合法的message_type',
            self::INVALID_IMAGE_SIZE => '不合法的图片大小',
            self::INVALID_VOICE_SIZE => '不合法的语音大小',
            self::INVALID_VIDEO_SIZE => '不合法的视频大小',
            self::INVALID_THUMB_SIZE => '不合法的缩略图大小',
            self::INVALID_APPID => '不合法的AppID',
            self::INVALID_ACCESS_TOKEN => '不合法的access_token',
            self::INVALID_MENU_TYPE => '不合法的菜单类型',
            self::INVALID_BUTTON_SIZE => '不合法的菜单按钮个数',
            self::INVALID_BUTTON_TYPE => '不合法的按钮类型',
            self::INVALID_BUTTON_NAME_SIZE => '不合法的按钮名称长度',
            self::INVALID_BUTTON_KEY_SIZE => '不合法的按钮KEY长度',
            self::INVALID_BUTTON_URL_SIZE => '不合法的url长度',
            self::INVALID_SUB_BUTTON_SIZE => '不合法的子菜单按钮个数',
            self::INVALID_SUB_BUTTON_TYPE => '不合法的子菜单类型',
            self::INVALID_SUB_BUTTON_NAME_SIZE => '不合法的子菜单按钮名称长度',
            self::INVALID_SUB_BUTTON_KEY_SIZE => '不合法的子菜单按钮KEY长度',
            self::INVALID_SUB_BUTTON_URL_SIZE => '不合法的子菜单按钮url长度',
            self::INVALID_CODE => '不合法或已过期的code',
            self::INVALID_REFRESH_TOKEN => '不合法的refresh_token',
            self::INVALID_TEMPLATE_ID_SIZE => '不合法的template_id长度',
            self::INVALID_TEMPLATE_ID => '不合法的template_id',
            self::INVALID_URL_SIZE => '不合法的url长度',
            self::INVALID_URL_DOMAIN => '不合法的url域名',
            self::INVALID_SUB_BUTTON_URL_DOMAIN => '不合法的子菜单按钮url域名',
            self::INVALID_BUTTON_URL_DOMAIN => '不合法的菜单按钮url域名',
            self::INVALID_URL => '不合法的url',
            self::ACCESS_TOKEN_MISSING => '缺失access_token参数',
            self::APPID_MISSING => '缺失appid参数',
            self::REFRESH_TOKEN_MISSING => '缺失refresh_token参数',
            self::APPSECRET_MISSING => '缺失secret参数',
            self::MEDIA_DATA_MISSING => '缺失二进制媒体文件',
            self::MEDIA_ID_MISSING => '缺失media_id参数',
            self::SUB_MENU_DATA_MISSING => '缺失子菜单数据',
            self::MISSING_CODE => '缺失code参数',
            self::MISSING_OPENID => '缺失openid参数',
            self::MISSING_URL => '缺失url参数',
            self::ACCESS_TOKEN_EXPIRED => 'access_token超时',
            self::REFRESH_TOKEN_EXPIRED => 'refresh_token超时',
            self::CODE_EXPIRED => 'code超时',
            self::REQUIRE_GET_METHOD => '需要使用GET方法请求',
            self::REQUIRE_POST_METHOD => '需要使用POST方法请求',
            self::REQUIRE_HTTPS => '需要使用HTTPS',
            self::REQUIRE_SUBSCRIBE => '需要订阅关系',
            self::EMPTY_MEDIA_DATA => '空白的二进制数据',
            self::EMPTY_POST_DATA => '空白的POST数据',
            self::EMPTY_NEWS_DATA => '空白的news数据',
            self::EMPTY_CONTENT => '空白的内容',
            self::EMPTY_LIST_SIZE => '空白的列表',
            self::MEDIA_SIZE_OUT_OF_LIMIT => '二进制文件超过限制',
            self::CONTENT_SIZE_OUT_OF_LIMIT => 'content参数超过限制',
            self::TITLE_SIZE_OUT_OF_LIMIT => 'title参数超过限制',
            self::DESCRIPTION_SIZE_OUT_OF_LIMIT => 'description参数超过限制',
            self::URL_SIZE_OUT_OF_LIMIT => 'url参数长度超过限制',
            self::PICURL_SIZE_OUT_OF_LIMIT => 'picurl参数超过限制',
            self::PLAYTIME_OUT_OF_LIMIT => '播放时间超过限制（语音为60s最大）',
            self::ARTICLE_SIZE_OUT_OF_LIMIT => 'article参数超过限制',
            self::API_FREQ_OUT_OF_LIMIT => '接口调动频率超过限制',
            self::CREATE_MENU_LIMIT => '建立菜单被限制',
            self::API_LIMIT => '频率限制',
            self::TEMPLATE_SIZE_OUT_OF_LIMIT => '模板大小超过限制',
            self::CANT_MODIFY_SYS_GROUP => '不能修改默认组',
            self::CANT_SET_GROUP_NAME_TOO_LONG_SYS_GROUP => '修改组名过长',
            self::TOO_MANY_GROUP_NOW_NO_NEED_TO_ADD_NEW => '组数量过多',
            self::API_UNAUTHORIZED => '接口未授权',
            self::UNKNOW_ERROR => '未知错误',
        ];
    }

    /**
     * @return string
     */
    public function getErrCodeText()
    {
        $options = static::getErrCodeOptions();
        return isset($options[$this->errcode]) ? $options[$this->errcode] : $options[self::UNKNOW_ERROR];
    }
}
<?php

namespace common\models\websocket;

use yii\base\Model;

/**
 * 内容格式
 *
 * Class DataForm
 * @package common\models\websocket
 * @author jianyan74 <751393839@qq.com>
 */
class DataForm extends Model
{
    public $route;
    public $token;
    public $params;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['route'], 'required'],
            [['route', 'token'], 'string'],
            [['params'], 'safe'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'route' => '路由',
            'token' => 'token 授权',
            'params' => '参数',
        ];
    }
}
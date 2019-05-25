<?php
namespace xj\oauth\weixin\models;

use SimpleXMLElement;
use common\oauth\weixin\common\ToolsHelper;
use yii\base\Model;
use yii\helpers\Json;

/**
 * @author xjflyttp <xjflyttp@gmail.com>
 */
class BaseModel extends Model
{

    /**
     * @param string $json
     * @return static
     */
    public static function createByJson($json)
    {
        $model = new static();
        $attributes = Json::decode($json);
        $model->load($attributes, '');
        return $model;
    }

    /**
     * @param string $xml
     * @return static
     */
    public static function createByXml($xml)
    {
        $model = new static();
        $attributes = ToolsHelper::xmlToArray($xml);
        foreach ($attributes as $name => $value) {
            if ($value instanceof SimpleXMLElement) {
                unset($attributes[$name]);
            }
        }
        $model->load($attributes, '');
        return $model;
    }
}
<?php

namespace api\rest;

use yii\web\Request;
use yii\base\InvalidConfigException;

/**
 * Class UrlRule
 * @package api\rest
 * @author jianyan74 <751393839@qq.com>
 */
class UrlRule extends \yii\rest\UrlRule
{
    /**
     * @param $manager
     * @param $request Request
     * @throws InvalidConfigException if the path info cannot be determined due to unexpected server configuration
     * @return array|bool
     */
    public function parseRequest($manager, $request)
    {
        $pathInfo = $request->getPathInfo();
        $pathInfoArr = explode('/', $pathInfo);
        if (count($pathInfoArr) >= 4 && in_array($pathInfoArr[0], $this->controller)) {
            return [$pathInfo, []];
        }

        return false;
    }
}
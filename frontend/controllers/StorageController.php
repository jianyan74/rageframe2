<?php

namespace frontend\controllers;

use Yii;
use linslin\yii2\curl\Curl;
use common\helpers\ResultHelper;
use common\models\common\Attachment;
use yii\web\Response;

/**
 * Class StorageController
 * @package frontend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class StorageController extends BaseController
{
    /**
     * 关闭csrf
     *
     * @var bool
     */
    public $enableCsrfValidation = false;

    /**
     *
     * 建议增加字段 upload_id,type,host,merchant_id
     *
     * @return array
     * @throws \yii\web\NotFoundHttpException
     * @throws \Exception
     */
    public function actionOss()
    {
        if ($this->signVerify() === false) {
            return ResultHelper::json(422, '签名校验失败');
        }

        $data = Yii::$app->request->post();
        $baseUrlArr = explode('/', $data['filename']);
        $fileName = end($baseUrlArr);
        $fileName = explode('.', $fileName);
        unset($fileName[count($fileName) - 1]);
        $name = implode('.', $fileName);

        $baseInfo = [
            'drive' => Attachment::DRIVE_OSS,
            'upload_type' => $data['type'],
            'specific_type' => $data['mimeType'],
            'size' => $data['size'],
            'extension' => $data['format'],
            'name' => $name,
            'width' => $data['width'],
            'height' => $data['height'],
            'base_url' => urldecode($data['host']) . '/' . $data['filename'],
            'path' => $data['filename'],
            'upload_id' => !empty($data['upload_id']) ? ip2long($data['upload_id']) : '',
            'md5' => $data['md5'],
        ];

        Yii::$app->services->merchant->setId($data['merchant_id'] ?? 1);
        $attachment_id = Yii::$app->services->attachment->create($baseInfo);
        $baseInfo['url'] = $baseInfo['base_url'];
        $baseInfo['id'] = $attachment_id;
        $baseInfo['formatter_size'] = Yii::$app->formatter->asShortSize($baseInfo['size'], 2);

        // 百度编辑器返回
        if (isset($data['is_ueditor']) && $data['is_ueditor'] == 'ueditor') {
            Yii::$app->response->format = Response::FORMAT_JSON;

            return [
                "state" => 'SUCCESS',
                "url" => $baseInfo['url'],
            ];
        }

        return ResultHelper::json(200, '获取成功', $baseInfo);
    }

    /**
     * @return array|bool
     * @throws \Exception
     */
    protected function signVerify()
    {
        // 1.获取OSS的签名header和公钥url header
        $authorizationBase64 = Yii::$app->request->headers->get('authorization');
        $pubKeyUrlBase64 = Yii::$app->request->headers->get('x-oss-pub-key-url');
        if (!$authorizationBase64 || !$pubKeyUrlBase64) {
            return false;
        }

        // 2.获取OSS的签名
        $authorization = base64_decode($authorizationBase64);

        // 3.获取公钥
        $pubKeyUrl = base64_decode($pubKeyUrlBase64);
        $curl = new Curl();
        $pubKey = $curl->get($pubKeyUrl);
        if ($pubKey == "") {
            return false;
        }

        // 4.获取回调body
        $body = file_get_contents('php://input');

        // 5.拼接待签名字符串
        $path = $_SERVER['REQUEST_URI'];
        $pos = strpos($path, '?');
        if ($pos === false) {
            $authStr = urldecode($path) . "\n" . $body;
        } else {
            $authStr = urldecode(substr($path, 0, $pos)) . substr($path, $pos, strlen($path) - $pos) . "\n" . $body;
        }

        // 6.验证签名
        $res = openssl_verify($authStr, $authorization, $pubKey, OPENSSL_ALGO_MD5);
        if ($res == 1) {
            return true;
        }

        return false;
    }
}
<?php

namespace addons\RfHelpers\backend\controllers;


use Yii;
use yii\web\Response;
use Da\QrCode\QrCode;
use Da\QrCode\Label;
use addons\RfHelpers\common\models\QrForm;
use common\helpers\StringHelper;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class QrController
 * @package addons\RfHelpers\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class QrController extends BaseController
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        $model = new QrForm();
        $model->content = Yii::$app->request->hostInfo;

        return $this->render('index', [
            'model' => $model
        ]);
    }

    /**
     * @return string
     * @throws UnprocessableEntityHttpException
     * @throws \Da\QrCode\Exception\InvalidPathException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate()
    {
        $model = new QrForm();
        $model->load(Yii::$app->request->get());
        if (!$model->validate()) {
            throw new UnprocessableEntityHttpException($this->getError($model));
        }

        /** @var \Da\QrCode\Component\QrCodeComponent $qr */
        $qr = Yii::$app->get('qr');
        Yii::$app->response->format = Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', $qr->getContentType());

        $label = new Label(
            $model->label,
            $font = null,
            $fontSize = $model->label_size,
            $alignment = $model->label_location,
            $margins = [
                't' => 0,
                'r' => 10,
                'b' => 10,
                'l' => 10,
            ]
        );

        // 前景色
        list($f_r, $f_g, $f_b) = sscanf($model->foreground, "#%02x%02x%02x");
        // 背景色
        list($b_r, $b_g, $b_b) = sscanf($model->background, "#%02x%02x%02x");

        $data = (new QrCode($model->content))
            ->useForegroundColor($f_r, $f_g, $f_b)
            ->useBackgroundColor($b_r, $b_g, $b_b)
            ->useEncoding('UTF-8');

        if (!empty($model->logo)) {
            $localFilePath = StringHelper::getLocalFilePath($model->logo);
            $data = $data->useLogo($localFilePath);
        }

        $data = $data
            ->setErrorCorrectionLevel($model->error_correction_level)
            ->setLogoWidth($model->logo_size)
            ->setSize($model->size)
            ->setMargin($model->margin);

        if (!empty($model->label_size) && !empty($model->label)) {
            $data = $data->setLabel($label);
        }

        // $data->writeFile(__DIR__ . '/codes/my-code.png');
        return $data->writeString();
    }
}
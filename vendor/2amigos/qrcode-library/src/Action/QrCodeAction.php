<?php

/*
 * This file is part of the 2amigos/yii2-qrcode-component project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Da\QrCode\Action;

use Da\QrCode\Component\QrCodeComponent;
use Yii;
use yii\base\Action;
use yii\web\Response;

class QrCodeAction extends Action
{
    /**
     * @var string the text to render if there are no parameter. Defaults to null, which means the component should
     *             render the text given as a parameter.
     */
    public $text;
    /**
     * @var string the parameter
     */
    public $param = 'text';
    /**
     * @var string whether the URL parameter is passed via GET or POST. Defaults to 'get'.
     */
    public $method = 'get';
    /**
     * @var string the qr component name configured on the Yii2 app. The component should have configured all the
     *             possible options like adding a logo, styling, labelling, etc.
     */
    public $component = 'qr';

    /**
     * Runs the action.
     */
    public function run()
    {
        $text = call_user_func_array([Yii::$app->request, $this->method], [$this->param, $this->text]);

        $qr = Yii::$app->get($this->component);

        if ($text && $qr instanceof QrCodeComponent) {
            Yii::$app->response->format = Response::FORMAT_RAW;
            Yii::$app->response->headers->add('Content-Type', $qr->getContentType());

            return $qr->setText((string)$text)->writeString();
        }
    }
}

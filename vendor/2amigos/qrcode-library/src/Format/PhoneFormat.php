<?php

/*
 * This file is part of the 2amigos/yii2-qrcode-component project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Da\QrCode\Format;

/**
 * Class Phone formats a string to properly create a Phone QrCode
 *
 * @author Antonio Ramirez <hola@2amigos.us>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @package Da\QrCode\Format
 */
class PhoneFormat extends AbstractFormat
{
    /**
     * @var string the phone
     */
    public $phone;

    /**
     * @return string
     */
    public function getText()
    {
        return "TEL:{$this->phone}";
    }
}

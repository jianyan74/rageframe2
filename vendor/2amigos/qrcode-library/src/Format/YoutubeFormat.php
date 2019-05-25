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
 * Class Youtube formats a string to a valid youtube video link
 *
 * @author Antonio Ramirez <hola@2amigos.us>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @package Da\QrCode\Format
 */
class YoutubeFormat extends AbstractFormat
{
    /**
     * @var string the video ID
     */
    public $videoId;

    /**
     * @return string the formatted string to be encoded
     */
    public function getText()
    {
        return "youtube://{$this->videoId}";
    }
}

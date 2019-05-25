<?php

/*
 * This file is part of the 2amigos/yii2-qrcode-component project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Da\QrCode\Traits;

use Da\QrCode\Exception\InvalidConfigException;

trait UrlTrait
{
    /**
     * @var string a valid URL
     */
    protected $url;

    /**
     * @param string $value the URL
     *
     * @throws InvalidConfigException
     */
    public function setUrl($value)
    {
        $error = null;

        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            throw new InvalidConfigException('Url seems invalid.');
        }

        $this->url = $value;
    }

    /**
     * @return string the URL
     */
    public function getUrl()
    {
        return $this->url;
    }
}

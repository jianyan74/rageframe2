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

use Da\QrCode\Exception\InvalidConfigException;

/**
 * Class Wifi formats a string to properly create a Wifi QrCode
 *
 * @author Antonio Ramirez <hola@2amigos.us>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @package Da\QrCode\Format
 */
class WifiFormat extends AbstractFormat
{
    /**
     * @var string the authentication type. e.g., WPA
     */
    public $authentication;
    /**
     * @var string the network SSID
     */
    public $ssid;
    /**
     * @var string the wifi password
     */
    public $password;
    /**
     * @var string hidden SSID (optional; equals false if omitted): either true or false
     */
    public $hidden;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        if ($this->ssid === null) {
            throw new InvalidConfigException("'ssid' cannot be null");
        }
    }

    /**
     * @inheritdoc
     */
    public function getText()
    {
        $data = [];
        $data[] = $this->authentication !== null ? "T:{$this->authentication}" : "";
        $data[] = "S:{$this->ssid}";
        $data[] = $this->password !== null ? "P:{$this->password}" : "";
        $data[] = $this->hidden !== null ? "H:{$this->hidden}" : "";
        return "WIFI:" . implode(";", $data) . ";";
    }
}

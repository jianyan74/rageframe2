<?php

/*
 * This file is part of the 2amigos/yii2-qrcode-component project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Da\QrCode\Contracts;

interface QrCodeInterface
{
    /**
     * @return string
     */
    public function getText();

    /**
     * @return int
     */
    public function getSize();

    /**
     * @return int
     */
    public function getMargin();

    /**
     * @return int[]
     */
    public function getForegroundColor();

    /**
     * @return int[]
     */
    public function getBackgroundColor();

    /**
     * @return string
     */
    public function getEncoding();

    /**
     * @return string
     */
    public function getErrorCorrectionLevel();

    /**
     * @return string
     */
    public function getLogoPath();

    /**
     * @return int
     */
    public function getLogoWidth();

    /**
     * @return LabelInterface
     */
    public function getLabel();
}

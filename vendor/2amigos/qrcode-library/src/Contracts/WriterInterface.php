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

interface WriterInterface
{
    /**
     * @param QrCodeInterface $qrCode
     *
     * @return string
     */
    public function writeString(QrCodeInterface $qrCode);

    /**
     * @param QrCodeInterface $qrCode
     *
     * @return string
     */
    public function writeDataUri(QrCodeInterface $qrCode);

    /**
     * @param QrCodeInterface $qrCode
     * @param string          $path
     *
     * @return bool|int the number of bytes that were written to the file, or false on failure.
     */
    public function writeFile(QrCodeInterface $qrCode, $path);

    /**
     * @return string
     */
    public function getContentType();

    /**
     * @return string
     */
    public function getName();
}

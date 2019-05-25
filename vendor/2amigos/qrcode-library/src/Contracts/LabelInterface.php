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

use Da\QrCode\Exception\InvalidPathException;

interface LabelInterface
{
    const ALIGN_LEFT = 'left';
    const ALIGN_CENTER = 'center';
    const ALIGN_RIGHT = 'right';

    /**
     * Updates the font size and returns a copy of the instance with the new values.
     *
     * @param int $size
     *
     * @return LabelInterface
     */
    public function updateFontSize($size);

    /**
     * Sets the font of the label in the QrCode. Returns a copy of the instance with the new values.
     *
     * @param string $path where the font is located.
     *
     * @throws InvalidPathException
     * @return LabelInterface
     */
    public function useFont($path);

    /**
     * @return string the font path.
     */
    public function getFont();

    /**
     * @return string the label text
     */
    public function getText();

    /**
     * @return int the label font size
     */
    public function getFontSize();

    /**
     * @return string the alignment value
     */
    public function getAlignment();

    /**
     * @return array the margins to position the label.
     */
    public function getMargins();
}

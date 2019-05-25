<?php

/*
 * This file is part of the 2amigos/yii2-qrcode-component project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Da\QrCode\Writer;

use Da\QrCode\Renderer\Jpg;
use Da\QrCode\Traits\ImageTrait;

class JpgWriter extends AbstractWriter
{
    use ImageTrait;

    /**
     * JpgWriter constructor.
     */
    public function __construct()
    {
        parent::__construct(new Jpg());
    }

    /**
     * @inheritdoc
     */
    public function getContentType()
    {
        return 'image/jpeg';
    }

    /**
     * @param resource $image
     *
     * @return string
     */
    protected function imageToString($image)
    {
        ob_start();
        imagejpeg($image);

        return ob_get_clean();
    }
}

<?php

/*
 * This file is part of the 2amigos/yii2-qrcode-component project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Da\QrCode;

use Da\QrCode\Contracts\LabelInterface;
use Da\QrCode\Exception\InvalidPathException;

class Label implements LabelInterface
{
    /**
     * @var string
     */
    protected $text;
    /**
     * @var int
     */
    protected $fontSize;
    /**
     * @var string
     */
    protected $font;
    /**
     * @var string
     */
    protected $alignment;
    /**
     * @var array
     */
    protected $margins = [
        't' => 0,
        'r' => 10,
        'b' => 10,
        'l' => 10,
    ];

    /**
     * Label constructor.
     *
     * @param $text
     * @param string|null $font
     * @param int|null    $fontSize
     * @param string|null $alignment
     * @param array       $margins
     */
    public function __construct($text, $font = null, $fontSize = null, $alignment = null, array $margins = [])
    {
        $this->text = $text;
        $this->font = $font ?: __DIR__ . '/../resources/fonts/noto_sans.otf';
        $this->fontSize = $fontSize ?: 16;
        $this->alignment = $alignment ?: LabelInterface::ALIGN_CENTER;
        $this->margins = array_merge($this->margins, $margins);
    }

    /**
     * @inheritdoc
     */
    public function updateFontSize($size)
    {
        $cloned = clone $this;
        $cloned->fontSize = $size;

        return $cloned;
    }

    /**
     * @inheritdoc
     */
    public function useFont($font)
    {
        $path = realpath($font);
        if (!is_file($path)) {
            throw new InvalidPathException(sprintf('Invalid label font path "%s"', $path));
        }

        $cloned = clone $this;

        $cloned->font = $path;

        return $cloned;
    }

    /**
     * @inheritdoc
     */
    public function getFont()
    {
        return $this->font;
    }

    /**
     * @inheritdoc
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @inheritdoc
     */
    public function getFontSize()
    {
        return $this->fontSize;
    }

    /**
     * @inheritdoc
     */
    public function getAlignment()
    {
        return $this->alignment;
    }

    /**
     * @inheritdoc
     */
    public function getMargins()
    {
        return $this->margins;
    }
}

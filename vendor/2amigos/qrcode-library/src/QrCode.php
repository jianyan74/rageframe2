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

use Da\QrCode\Contracts\ErrorCorrectionLevelInterface;
use Da\QrCode\Contracts\LabelInterface;
use Da\QrCode\Contracts\QrCodeInterface;
use Da\QrCode\Contracts\WriterInterface;
use Da\QrCode\Exception\InvalidPathException;
use Da\QrCode\Exception\UnknownWriterException;
use Da\QrCode\Writer\PngWriter;

class QrCode implements QrCodeInterface
{
    /**
     * @var string
     */
    protected $text;
    /**
     * @var int
     */
    protected $size = 300;
    /**
     * @var int
     */
    protected $margin = 10;
    /**
     * @var array
     */
    protected $foregroundColor = [
        'r' => 0,
        'g' => 0,
        'b' => 0
    ];
    /**
     * @var array
     */
    protected $backgroundColor = [
        'r' => 255,
        'g' => 255,
        'b' => 255
    ];
    /**
     * @var string
     */
    protected $encoding = 'UTF-8';
    /**
     * @var string ErrorCorrectionLevelInterface value
     */
    protected $errorCorrectionLevel;
    /**
     * @var string
     */
    protected $logoPath;
    /**
     * @var int
     */
    protected $logoWidth;
    /**
     * @var LabelInterface
     */
    protected $label;
    /**
     * @var WriterInterface
     */
    protected $writer;

    /**
     * QrCode constructor.
     *
     * @param string               $text
     * @param null                 $errorCorrectionLevel
     * @param WriterInterface|null $writer
     */
    public function __construct($text = '', $errorCorrectionLevel = null, WriterInterface $writer = null)
    {
        $this->text = (string)$text;
        $this->errorCorrectionLevel = $errorCorrectionLevel ?: ErrorCorrectionLevelInterface::LOW;
        $this->writer = $writer ?: new PngWriter();
    }

    /**
     * @param int $red
     * @param int $green
     * @param int $blue
     *
     * @return QrCode
     */
    public function useForegroundColor($red, $green, $blue)
    {
        $cloned = clone $this;
        $cloned->foregroundColor = [
            'r' => $red,
            'g' => $green,
            'b' => $blue
        ];

        return $cloned;
    }

    /**
     * @param $red
     * @param $green
     * @param $blue
     *
     * @return QrCode
     */
    public function useBackgroundColor($red, $green, $blue)
    {
        $cloned = clone $this;
        $cloned->backgroundColor = [
            'r' => $red,
            'g' => $green,
            'b' => $blue
        ];

        return $cloned;
    }

    /**
     * @param $path
     *
     * @throws InvalidPathException
     * @return QrCode
     */
    public function useLogo($path)
    {
        $logo = realpath($path);
        if (!is_file($logo)) {
            throw new InvalidPathException(sprintf('Invalid logo path: "%s"', $logo));
        }
        $cloned = clone $this;
        $cloned->logoPath = $logo;

        return $cloned;
    }

    /**
     * @param $encoding
     *
     * @return QrCode
     */
    public function useEncoding($encoding)
    {
        $cloned = clone $this;
        $cloned->encoding = $encoding;

        return $cloned;
    }

    /**
     * @param WriterInterface $writer
     *
     * @return QrCode
     */
    public function useWriter(WriterInterface $writer)
    {
        $cloned = clone $this;
        $cloned->writer = $writer;

        return $cloned;
    }

    /**
     * @param string $errorCorrectionLevel
     *
     * @return QrCode
     */
    public function setErrorCorrectionLevel($errorCorrectionLevel)
    {
        $cloned = clone $this;
        $cloned->errorCorrectionLevel = $errorCorrectionLevel;

        return $cloned;
    }

    /**
     * @param string $text
     *
     * @return QrCode
     */
    public function setText($text)
    {
        $cloned = clone $this;
        $cloned->text = $text;

        return $cloned;
    }

    /**
     * @param int $size
     *
     * @return QrCode
     */
    public function setSize($size)
    {
        $cloned = clone $this;
        $cloned->size = $size;

        return $cloned;
    }

    /**
     * @param int $margin
     *
     * @return QrCode
     */
    public function setMargin($margin)
    {
        $cloned = clone $this;
        $cloned->margin = $margin;

        return $cloned;
    }

    /**
     * @param int $width
     *
     * @return QrCode
     */
    public function setLogoWidth($width)
    {
        $cloned = clone $this;
        $cloned->logoWidth = $width;

        return $cloned;
    }

    /**
     * @param LabelInterface|string $label
     *
     * @return QrCode
     */
    public function setLabel($label)
    {
        $cloned = clone $this;
        $cloned->label = $label instanceof LabelInterface ? $label : new Label($label);

        return $cloned;
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
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @inheritdoc
     */
    public function getMargin()
    {
        return $this->margin;
    }

    /**
     * @inheritdoc
     */
    public function getForegroundColor()
    {
        return $this->foregroundColor;
    }

    /**
     * @inheritdoc
     */
    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }

    /**
     * @inheritdoc
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * @inheritdoc
     */
    public function getErrorCorrectionLevel()
    {
        return $this->errorCorrectionLevel;
    }

    /**
     * @inheritdoc
     */
    public function getLogoPath()
    {
        return $this->logoPath;
    }

    /**
     * @inheritdoc
     */
    public function getLogoWidth()
    {
        return $this->logoWidth;
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function writeString()
    {
        return $this->writer->writeString($this);
    }

    /**
     * @return string
     */
    public function writeDataUri()
    {
        return $this->writer->writeDataUri($this);
    }

    /**
     * @param $path
     *
     * @return bool|int
     */
    public function writeFile($path)
    {
        return $this->writer->writeFile($this, $path);
    }

    /**
     * @throws UnknownWriterException
     * @return string
     */
    public function getContentType()
    {
        return $this->writer->getContentType();
    }
}

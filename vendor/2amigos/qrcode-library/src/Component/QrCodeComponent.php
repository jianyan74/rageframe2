<?php

/*
 * This file is part of the 2amigos/yii2-qrcode-component project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Da\QrCode\Component;

use Da\QrCode\Contracts\LabelInterface;
use Da\QrCode\Contracts\QrCodeInterface;
use Da\QrCode\Contracts\WriterInterface;
use Da\QrCode\QrCode;
use yii\base\Component;

/**
 * Class QrCodeComponent
 *
 * @author Antonio Ramirez <hola@2amigos.us>
 * @package Da\QrCode\Component
 *
 * @method QrCode useForegroundColor(integer $red, integer $green, integer $blue)
 * @method QrCode useBackgroundColor(integer $red, integer $green, integer $blue)
 * @method QrCode useEncoding(string $encoding)
 * @method QrCode useWriter(WriterInterface $writer)
 * @method QrCode useLogo(string $logo)
 * @method QrCode setText(string $text)
 * @method QrCode setSize(integer $size)
 * @method QrCode setLogoWidth(integer $width)
 * @method QrCode setLabel(LabelInterface $label)
 * @method QrCode setMargin(integer $margin)
 * @method QrCode setErrorCorrectionLevel(string $errorCorrectionLevel)
 * @method string getText()
 * @method integer getSize()
 * @method array getMargin()
 * @method array getForegroundColor()
 * @method array getBackgroundColor()
 * @method string getEncoding()
 * @method string getErrorCorrectionLevel()
 * @method string getLogoPath()
 * @method integer getLogoWidth()
 * @method LabelInterface getLabel()
 * @method string writeString()
 * @method string writeDataUri()
 * @method bool|integer writeFile(string $path)
 * @method string getContentType()
 */
class QrCodeComponent extends Component
{
    /**
     * @var string
     */
    public $text;
    /**
     * @var int
     */
    public $size = 300;
    /**
     * @var int
     */
    public $margin = 10;
    /**
     * @var array the foreground color. Syntax is:
     *
     * ```
     * [
     *  'r' => 0, // RED
     *  'g' => 0, // GREEN
     *  'b' => 0  // BLUE
     * ]
     * ```
     */
    public $foregroundColor;
    /**
     * @var array the background color. Syntax is:
     *
     * ```
     * [
     *  'r' => 255, // RED
     *  'g' => 255, // GREEN
     *  'b' => 255  // BLUE
     * ]
     * ```
     *
     */
    public $backgroundColor;
    /**
     * @var string
     */
    public $encoding = 'UTF-8';
    /**
     * @var string ErrorCorrectionLevelInterface value
     */
    public $errorCorrectionLevel;
    /**
     * @var string
     */
    public $logoPath;
    /**
     * @var int
     */
    public $logoWidth;
    /**
     * @var LabelInterface|string
     */
    public $label;
    /**
     * @var WriterInterface
     */
    public $writer;
    /**
     * @var QrCodeInterface
     */
    protected $qrCode;

    /**
     * @inheritdoc
     */
    public function __call($name, $params)
    {
        return call_user_func_array([$this->qrCode, $name], $params);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->qrCode = (new QrCode($this->text, $this->errorCorrectionLevel, $this->writer))
            ->setSize(($this->size ?: 300))
            ->setMargin(($this->margin ?: 10))
            ->useEncoding(($this->encoding ?: 'UTF-8'));

        $this->qrCode = $this->logoPath ? $this->qrCode->useLogo($this->logoPath) : $this->qrCode;
        $this->qrCode = $this->logoWidth ? $this->qrCode->setLogoWidth($this->logoWidth) : $this->qrCode;
        $this->qrCode = $this->label ? $this->qrCode->setLabel($this->label) : $this->qrCode;

        if ($this->foregroundColor) {
            list($r, $g, $b) = $this->foregroundColor;
            $this->qrCode = $this->qrCode->useForegroundColor($r, $g, $b);
        }
        if ($this->backgroundColor) {
            list($r, $g, $b) = $this->backgroundColor;
            $this->qrCode = $this->qrCode->useBackgroundColor($r, $g, $b);
        }
    }
}

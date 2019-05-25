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

use BaconQrCode\Renderer\Image\Png;
use BaconQrCode\Writer;
use Da\QrCode\Contracts\LabelInterface;
use Da\QrCode\Contracts\QrCodeInterface;
use Da\QrCode\Exception\BadMethodCallException;
use Da\QrCode\Exception\ValidationException;
use Da\QrCode\Renderer\Jpg;
use QrReader;

trait ImageTrait
{
    protected $validate = false;

    /**
     * Whether to validate result or not.
     *
     * @param $validate
     *
     * @return $this
     */
    public function validateResult($validate)
    {
        $cloned = clone $this;
        $cloned->validate = $validate;

        return $cloned;
    }

    /**
     * {@inheritdoc}
     */
    public function writeString(QrCodeInterface $qrCode)
    {
        /** @var Png|Jpg $renderer */
        $renderer = $this->renderer;

        $renderer->setWidth($qrCode->getSize());
        $renderer->setHeight($qrCode->getSize());
        $renderer->setMargin(0);
        $renderer->setForegroundColor($this->convertColor($qrCode->getForegroundColor()));
        $renderer->setBackgroundColor($this->convertColor($qrCode->getBackgroundColor()));
        $writer = new Writer($renderer);
        $string = $writer->writeString(
            $qrCode->getText(),
            $qrCode->getEncoding(),
            $this->convertErrorCorrectionLevel($qrCode->getErrorCorrectionLevel())
        );
        $image = imagecreatefromstring($string);
        $image = $this->addMargin(
            $image,
            $qrCode->getMargin(),
            $qrCode->getSize(),
            $qrCode->getForegroundColor(),
            $qrCode->getBackgroundColor()
        );

        if ($qrCode->getLogoPath()) {
            $image = $this->addLogo($image, $qrCode->getLogoPath(), $qrCode->getLogoWidth());
        }

        if ($qrCode->getLabel()) {
            $image = $this->addLabel(
                $image,
                $qrCode->getLabel(),
                $qrCode->getForegroundColor(),
                $qrCode->getBackgroundColor()
            );
        }
        $string = $this->imageToString($image);
        if ($this->validate) {
            $reader = new QrReader($string, QrReader::SOURCE_TYPE_BLOB);
            if ($reader->text() !== $qrCode->getText()) {
                throw new ValidationException(
                    sprintf(
                        'Built-in validation reader read "%s" instead of "%s"' .
                        'Adjust your parameters to increase readability or disable built-in validation.',
                        $reader->text(),
                        $qrCode->getText()
                    )
                );
            }
        }

        return $string;
    }

    /**
     * @param resource $sourceImage
     * @param int      $margin
     * @param int      $size
     * @param int[]    $foregroundColor
     * @param int[]    $backgroundColor
     *
     * @return resource
     */
    protected function addMargin($sourceImage, $margin, $size, array $foregroundColor, array $backgroundColor)
    {
        $additionalWhitespace = $this->calculateAdditionalWhiteSpace($sourceImage, $foregroundColor);

        if ($additionalWhitespace == 0 && $margin == 0) {
            return $sourceImage;
        }

        $targetImage = imagecreatetruecolor($size + $margin * 2, $size + $margin * 2);
        $backgroundColor = imagecolorallocate(
            $targetImage,
            $backgroundColor['r'],
            $backgroundColor['g'],
            $backgroundColor['b']
        );
        imagefill($targetImage, 0, 0, $backgroundColor);
        imagecopyresampled(
            $targetImage,
            $sourceImage,
            $margin,
            $margin,
            $additionalWhitespace,
            $additionalWhitespace,
            $size,
            $size,
            $size - 2 * $additionalWhitespace,
            $size - 2 * $additionalWhitespace
        );

        return $targetImage;
    }

    /**
     * @param resource $image
     * @param int[]    $foregroundColor
     *
     * @return int
     */
    protected function calculateAdditionalWhiteSpace($image, array $foregroundColor)
    {
        $width = imagesx($image);
        $height = imagesy($image);
        $foregroundColor = imagecolorallocate(
            $image,
            $foregroundColor['r'],
            $foregroundColor['g'],
            $foregroundColor['b']
        );
        $whitespace = $width;
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $color = imagecolorat($image, $x, $y);
                if ($color == $foregroundColor || $x == $whitespace) {
                    $whitespace = min($whitespace, $x);
                    break;
                }
            }
        }

        return $whitespace;
    }

    /**
     * @param resource $sourceImage
     * @param string   $logoPath
     * @param int      $logoWidth
     *
     * @return resource
     */
    protected function addLogo($sourceImage, $logoPath, $logoWidth = null)
    {
        $logoImage = imagecreatefromstring(file_get_contents($logoPath));
        $logoSourceWidth = imagesx($logoImage);
        $logoSourceHeight = imagesy($logoImage);
        $logoTargetWidth = $logoWidth;

        if ($logoTargetWidth === null) {
            $logoTargetWidth = $logoSourceWidth;
            $logoTargetHeight = $logoSourceHeight;
        } else {
            $scale = $logoTargetWidth / $logoSourceWidth;
            $logoTargetHeight = intval($scale * imagesy($logoImage));
        }

        $logoX = imagesx($sourceImage) / 2 - $logoTargetWidth / 2;
        $logoY = imagesy($sourceImage) / 2 - $logoTargetHeight / 2;

        imagecopyresampled(
            $sourceImage,
            $logoImage,
            $logoX,
            $logoY,
            0,
            0,
            $logoTargetWidth,
            $logoTargetHeight,
            $logoSourceWidth,
            $logoSourceHeight
        );

        return $sourceImage;
    }

    /**
     * @param resource       $sourceImage
     * @param LabelInterface $label
     * @param int[]          $foregroundColor
     * @param int[]          $backgroundColor
     *
     * @throws BadMethodCallException
     * @return resource
     */
    protected function addLabel(
        $sourceImage,
        LabelInterface $label,
        array $foregroundColor,
        array $backgroundColor
    ) {
        if (!function_exists('imagettfbbox')) {
            throw new BadMethodCallException('Missing function "imagettfbbox". Did you install the FreeType library?');
        }
        $labelText = $label->getText();
        $labelFontSize = $label->getFontSize();
        $labelFontPath = $label->getFont();
        $labelMargin = $label->getMargins();
        $labelAlignment = $label->getAlignment();

        $labelBox = imagettfbbox($labelFontSize, 0, $labelFontPath, $labelText);
        $labelBoxWidth = intval($labelBox[2] - $labelBox[0]);
        $labelBoxHeight = intval($labelBox[0] - $labelBox[7]);
        $sourceWidth = imagesx($sourceImage);
        $sourceHeight = imagesy($sourceImage);
        $targetWidth = $sourceWidth;
        $targetHeight = $sourceHeight + $labelBoxHeight + $labelMargin['t'] + $labelMargin['b'];

        // Create empty target image
        $targetImage = imagecreatetruecolor($targetWidth, $targetHeight);
        $foregroundColor = imagecolorallocate(
            $targetImage,
            $foregroundColor['r'],
            $foregroundColor['g'],
            $foregroundColor['b']
        );
        $backgroundColor = imagecolorallocate(
            $targetImage,
            $backgroundColor['r'],
            $backgroundColor['g'],
            $backgroundColor['b']
        );
        imagefill($targetImage, 0, 0, $backgroundColor);
        // Copy source image to target image
        imagecopyresampled(
            $targetImage,
            $sourceImage,
            0,
            0,
            0,
            0,
            $sourceWidth,
            $sourceHeight,
            $sourceWidth,
            $sourceHeight
        );
        switch ($labelAlignment) {
            case LabelInterface::ALIGN_LEFT:
                $labelX = $labelMargin['l'];
                break;
            case LabelInterface::ALIGN_RIGHT:
                $labelX = $targetWidth - $labelBoxWidth - $labelMargin['r'];
                break;
            default:
                $labelX = intval($targetWidth / 2 - $labelBoxWidth / 2);
                break;
        }
        $labelY = $targetHeight - $labelMargin['b'];
        imagettftext($targetImage, $labelFontSize, 0, $labelX, $labelY, $foregroundColor, $labelFontPath, $labelText);

        return $targetImage;
    }
}

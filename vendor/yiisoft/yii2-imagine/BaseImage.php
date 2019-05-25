<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\imagine;

use Yii;
use Imagine\Image\Box;
use Imagine\Image\BoxInterface;
use Imagine\Image\Color;
use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;
use Imagine\Image\ManipulatorInterface;
use Imagine\Image\Point;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\helpers\ArrayHelper;

/**
 * BaseImage provides concrete implementation for [[Image]].
 *
 * Do not use BaseImage. Use [[Image]] instead.
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class BaseImage
{
    /**
     * GD2 driver definition for Imagine implementation using the GD library.
     */
    const DRIVER_GD2 = 'gd2';
    /**
     * imagick driver definition.
     */
    const DRIVER_IMAGICK = 'imagick';
    /**
     * gmagick driver definition.
     */
    const DRIVER_GMAGICK = 'gmagick';

    /**
     * @var array|string the driver to use. This can be either a single driver name or an array of driver names.
     * If the latter, the first available driver will be used.
     */
    public static $driver = [self::DRIVER_GMAGICK, self::DRIVER_IMAGICK, self::DRIVER_GD2];
    /**
     * @var ImagineInterface instance.
     */
    private static $_imagine;


    /**
     * @var string background color to use when creating thumbnails in `ImageInterface::THUMBNAIL_INSET` mode with
     * both width and height specified. Default is white.
     *
     * @since 2.0.4
     */
    public static $thumbnailBackgroundColor = 'FFF';
    /**
     * @var string background alpha (transparency) to use when creating thumbnails in `ImageInterface::THUMBNAIL_INSET`
     * mode with both width and height specified. Default is solid.
     *
     * @since 2.0.4
     */
    public static $thumbnailBackgroundAlpha = 100;

    /**
     * Returns the `Imagine` object that supports various image manipulations.
     * @return ImagineInterface the `Imagine` object
     */
    public static function getImagine()
    {
        if (self::$_imagine === null) {
            self::$_imagine = static::createImagine();
        }

        return self::$_imagine;
    }

    /**
     * @param ImagineInterface $imagine the `Imagine` object.
     */
    public static function setImagine($imagine)
    {
        self::$_imagine = $imagine;
    }

    /**
     * Creates an `Imagine` object based on the specified [[driver]].
     * @return ImagineInterface the new `Imagine` object
     * @throws InvalidConfigException if [[driver]] is unknown or the system doesn't support any [[driver]].
     */
    protected static function createImagine()
    {
        foreach ((array) static::$driver as $driver) {
            switch ($driver) {
                case self::DRIVER_GMAGICK:
                    if (class_exists('Gmagick', false)) {
                        return new \Imagine\Gmagick\Imagine();
                    }
                    break;
                case self::DRIVER_IMAGICK:
                    if (class_exists('Imagick', false)) {
                        return new \Imagine\Imagick\Imagine();
                    }
                    break;
                case self::DRIVER_GD2:
                    if (function_exists('gd_info')) {
                        return new \Imagine\Gd\Imagine();
                    }
                    break;
                default:
                    throw new InvalidConfigException("Unknown driver: $driver");
            }
        }
        throw new InvalidConfigException("Your system does not support any of these drivers: " . implode(',', (array) static::$driver));
    }

    /**
     * Crops an image.
     *
     * For example,
     *
     * ~~~
     * $obj->crop('path\to\image.jpg', 200, 200, [5, 5]);
     *
     * $point = new \Imagine\Image\Point(5, 5);
     * $obj->crop('path\to\image.jpg', 200, 200, $point);
     * ~~~
     *
     * @param string $filename the image file path or path alias.
     * @param integer $width the crop width
     * @param integer $height the crop height
     * @param array $start the starting point. This must be an array with two elements representing `x` and `y` coordinates.
     * @return ImageInterface
     * @throws InvalidParamException if the `$start` parameter is invalid
     */
    public static function crop($filename, $width, $height, array $start = [0, 0])
    {
        if (!isset($start[0], $start[1])) {
            throw new InvalidParamException('$start must be an array of two elements.');
        }

        return static::getImagine()
            ->open(Yii::getAlias($filename))
            ->copy()
            ->crop(new Point($start[0], $start[1]), new Box($width, $height));
    }

    /**
     * Creates a thumbnail image.
     *
     * If one of thumbnail dimensions is set to `null`, another one is calculated automatically based on aspect ratio of
     * original image. Note that calculated thumbnail dimension may vary depending on the source image in this case.
     *
     * If both dimensions are specified, resulting thumbnail would be exactly the width and height specified. How it's
     * achieved depends on the mode.
     *
     * If `ImageInterface::THUMBNAIL_OUTBOUND` mode is used, which is default, then the thumbnail is scaled so that
     * its smallest side equals the length of the corresponding side in the original image. Any excess outside of
     * the scaled thumbnail’s area will be cropped, and the returned thumbnail will have the exact width and height
     * specified.
     *
     * If thumbnail mode is `ImageInterface::THUMBNAIL_INSET`, the original image is scaled down so it is fully
     * contained within the thumbnail dimensions. The rest is filled with background that could be configured via
     * [[Image::$thumbnailBackgroundColor]] and [[Image::$thumbnailBackgroundAlpha]].
     *
     * @param string $filename the image file path or path alias.
     * @param integer $width the width in pixels to create the thumbnail
     * @param integer $height the height in pixels to create the thumbnail
     * @param string $mode mode of resizing original image to use in case both width and height specified
     * @return ImageInterface
     */
    public static function thumbnail($filename, $width, $height, $mode = ManipulatorInterface::THUMBNAIL_OUTBOUND)
    {
        $img = static::getImagine()->open(Yii::getAlias($filename));

        $sourceBox = $img->getSize();
        $thumbnailBox = static::getThumbnailBox($sourceBox, $width, $height);

        if (($sourceBox->getWidth() <= $thumbnailBox->getWidth() && $sourceBox->getHeight() <= $thumbnailBox->getHeight()) || (!$thumbnailBox->getWidth() && !$thumbnailBox->getHeight())) {
            return $img->copy();
        }

        $img = $img->thumbnail($thumbnailBox, $mode);

        if ($mode == ManipulatorInterface::THUMBNAIL_OUTBOUND) {
            return $img;
        }

        $size = $img->getSize();

        if ($size->getWidth() == $width && $size->getHeight() == $height) {
            return $img;
        }

        // create empty image to preserve aspect ratio of thumbnail
        $thumb = static::getImagine()->create($thumbnailBox, new Color(static::$thumbnailBackgroundColor, static::$thumbnailBackgroundAlpha));

        // calculate points
        $startX = 0;
        $startY = 0;
        if ($size->getWidth() < $width) {
            $startX = ceil($width - $size->getWidth()) / 2;
        }
        if ($size->getHeight() < $height) {
            $startY = ceil($height - $size->getHeight()) / 2;
        }

        $thumb->paste($img, new Point($startX, $startY));

        return $thumb;
    }

    /**
     * Adds a watermark to an existing image.
     * @param string $filename the image file path or path alias.
     * @param string $watermarkFilename the file path or path alias of the watermark image.
     * @param array $start the starting point. This must be an array with two elements representing `x` and `y` coordinates.
     * @return ImageInterface
     * @throws InvalidParamException if `$start` is invalid
     */
    public static function watermark($filename, $watermarkFilename, array $start = [0, 0])
    {
        if (!isset($start[0], $start[1])) {
            throw new InvalidParamException('$start must be an array of two elements.');
        }

        $img = static::getImagine()->open(Yii::getAlias($filename));
        $watermark = static::getImagine()->open(Yii::getAlias($watermarkFilename));
        $img->paste($watermark, new Point($start[0], $start[1]));

        return $img;
    }

    /**
     * Draws a text string on an existing image.
     * @param string $filename the image file path or path alias.
     * @param string $text the text to write to the image
     * @param string $fontFile the file path or path alias
     * @param array $start the starting position of the text. This must be an array with two elements representing `x` and `y` coordinates.
     * @param array $fontOptions the font options. The following options may be specified:
     *
     * - color: The font color. Defaults to "fff".
     * - size: The font size. Defaults to 12.
     * - angle: The angle to use to write the text. Defaults to 0.
     *
     * @return ImageInterface
     * @throws InvalidParamException if `$fontOptions` is invalid
     */
    public static function text($filename, $text, $fontFile, array $start = [0, 0], array $fontOptions = [])
    {
        if (!isset($start[0], $start[1])) {
            throw new InvalidParamException('$start must be an array of two elements.');
        }

        $fontSize = ArrayHelper::getValue($fontOptions, 'size', 12);
        $fontColor = ArrayHelper::getValue($fontOptions, 'color', 'fff');
        $fontAngle = ArrayHelper::getValue($fontOptions, 'angle', 0);

        $img = static::getImagine()->open(Yii::getAlias($filename));
        $font = static::getImagine()->font(Yii::getAlias($fontFile), $fontSize, new Color($fontColor));

        $img->draw()->text($text, $font, new Point($start[0], $start[1]), $fontAngle);

        return $img;
    }

    /**
     * Adds a frame around of the image. Please note that the image size will increase by `$margin` x 2.
     * @param string $filename the full path to the image file
     * @param integer $margin the frame size to add around the image
     * @param string $color the frame color
     * @param integer $alpha the alpha value of the frame.
     * @return ImageInterface
     */
    public static function frame($filename, $margin = 20, $color = '666', $alpha = 100)
    {
        $img = static::getImagine()->open(Yii::getAlias($filename));

        $size = $img->getSize();

        $pasteTo = new Point($margin, $margin);
        $padColor = new Color($color, $alpha);

        $box = new Box($size->getWidth() + ceil($margin * 2), $size->getHeight() + ceil($margin * 2));

        $image = static::getImagine()->create($box, $padColor);

        $image->paste($img, $pasteTo);

        return $image;
    }
    
    /**
     * Returns box for a thumbnail to be created. If one of the dimensions is set to `null`, another one is calculated
     * automatically based on width to height ratio of original image box.
     *
     * @param BoxInterface $sourceBox original image box
     * @param integer $width thumbnail width
     * @param integer $height thumbnail height
     * @return BoxInterface thumbnail box
     *
     * @since 2.0.4
     */
    protected static function getThumbnailBox(BoxInterface $sourceBox, $width, $height)
    {
        if ($width !== null && $height !== null) {
            return new Box($width, $height);
        }

        if ($width === null && $height === null) {
            throw new InvalidParamException('Width and height cannot be null at same time.');
        }

        $ratio = $sourceBox->getWidth() / $sourceBox->getHeight();
        if ($height === null) {
            $height = ceil($width / $ratio);
        } else {
            $width = ceil($height * $ratio);
        }

        return new Box($width, $height);
    }
}

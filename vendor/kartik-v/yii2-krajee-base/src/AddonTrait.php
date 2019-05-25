<?php

/**
 * @package   yii2-krajee-base
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2019
 * @version   2.0.5
 */

namespace kartik\base;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * AddonTrait includes methods to render addons based on `addon` setting
 *
 * @property array $addon
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 */
trait AddonTrait
{
    /**
     * Parses and returns addon content.
     *
     * @param string $type the addon type `prepend` or `append`. If any other value is set, it will default to `prepend`
     * @param bool $isBs4 whether addon is to be rendered for Bootstrap 4.x version
     * @return string
     */
    protected function getAddonContent($type, $isBs4)
    {
        $addon = ArrayHelper::getValue($this->addon, $type, '');
        if (!is_array($addon)) {
            return $addon;
        }
        if (isset($addon['content'])) {
            $out = static::renderAddonItem($addon, $isBs4);
        } else {
            $out = '';
            foreach ($addon as $item) {
                if (is_array($item) && isset($item['content'])) {
                    $out .= static::renderAddonItem($item, $isBs4);
                }
            }
        }
        if (!$isBs4) {
            return $out;
        }
        $pos = $type === 'append' ? 'append' : 'prepend';
        return Html::tag('div', $out, ['class' => "input-group-{$pos}"]);
    }

    /**
     * Renders an addon item based on its configuration
     *
     * @param array $config the addon item configuration
     * @param bool $isBs4 whether addon is to be rendered for Bootstrap 4.x version
     * @return string
     */
    protected static function renderAddonItem($config, $isBs4)
    {
        $content = ArrayHelper::getValue($config, 'content', '');
        $options = ArrayHelper::getValue($config, 'options', []);
        $asButton = ArrayHelper::getValue($config, 'asButton', false);
        if (!$isBs4) {
            Html::addCssClass($options, 'input-group-' . ($asButton ? 'btn' : 'addon'));
            return Html::tag('span', $content, $options);
        }
        if ($asButton) {
            return $content;
        }
        Html::addCssClass($options, 'input-group-text');
        return Html::tag('span', $content, $options);
    }
}

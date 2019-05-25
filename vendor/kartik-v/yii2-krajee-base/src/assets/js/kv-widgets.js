/*!
 * @package    yii2-krajee-base
 * @subpackage yii2-widget-activeform
 * @author     Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright  Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2019
 * @version    2.0.5
 *
 * Common client validation file for all Krajee widgets.
 *
 * For more JQuery/Bootstrap plugins and demos visit http://plugins.krajee.com
 * For more Yii related demos visit http://demos.krajee.com
 */
var kvInitHtml5;
(function ($) {
    "use strict";
    /**
     * Initialize HTML5 Input Widgets
     * @param idCap string, the id of the caption element
     * @param idInp string, the id of the input element
     */
    kvInitHtml5 = function (idCap, idInp) {
        var $caption = $(idCap), $input = $(idInp);
        $(document).on('change', idCap, function () {
            $input.val(this.value);
        }).on('input change', idInp, function (e) {
            $caption.val(this.value);
            if (e.type === 'change') {
                $caption.trigger('change');
            }
        });
    };
})(window.jQuery);
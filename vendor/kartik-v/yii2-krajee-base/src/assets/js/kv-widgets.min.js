/*!
 * @package    yii2-krajee-base
 * @subpackage yii2-widget-activeform
 * @author     Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright  Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2019
 * @version    2.0.5
 *
 * Common script file for all kartik\widgets.
 *
 * For more JQuery/Bootstrap plugins and demos visit http://plugins.krajee.com
 * For more Yii related demos visit http://demos.krajee.com
 */var kvInitHtml5;!function(a){"use strict";kvInitHtml5=function(b,c){var d=a(b),e=a(c);a(document).on("change",b,function(){e.val(this.value)}).on("input change",c,function(a){d.val(this.value),"change"===a.type&&d.trigger("change")})}}(window.jQuery);
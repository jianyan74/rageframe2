/*!
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2018
 * @version 1.0.5
 * 
 * Additional jQuery plugin enhancements for ColorInput Spectrum plugin by Krajee.
 * 
 * Author: Kartik Visweswaran
 * Copyright: 2015, Kartik Visweswaran, Krajee.com
 * For more Yii related demos visit http://demos.krajee.com
 */
(function ($) {
    "use strict";
    $(document).on('ready', function () {
        $('.spectrum-group').on('change', 'input', function (e, color) {
            var $el = $(this), $group = $el.closest('.spectrum-group'), val = $el.val();
            if ($el.is('.spectrum-source')) {
                if(color !== null) {
                    val = color.toString();
                }
                $group.find('.spectrum-input').val(val);
            }
            $group.find('.spectrum-source').spectrum('set', val);
        });
    });
})(window.jQuery);

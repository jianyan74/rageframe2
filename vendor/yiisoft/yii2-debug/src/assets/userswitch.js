(function () {
    'use strict';

    var sendSetIdentity = function(e) {
        var form = $(this);
        var formData = form.serialize();
        $.ajax({
            url: form.attr("action"),
            type: form.attr("method"),
            data: formData,
            success: function (data) {
                window.top.location.reload();
            },
            error: function (data) {
                form.yiiActiveForm('updateMessages', data.responseJSON, true);
            }
        });
    };


    $('#debug-userswitch__set-identity').on('beforeSubmit', sendSetIdentity)
        .on('submit', function(e){
            e.preventDefault();
        });
    $('#debug-userswitch__reset-identity').on('beforeSubmit', sendSetIdentity)
        .on('submit', function(e){
            e.preventDefault();
        });
    $('#debug-userswitch__filter').on("click", "tbody tr", function(event) {
        $('#debug-userswitch__set-identity #user_id').val($(this).data('key'));
        $('#debug-userswitch__set-identity').submit();
        event.stopPropagation();
    });
})();
$(document).on("click",".rfAttachmentActive",function() {
    if (!$(this).hasClass('active')){
        $(this).addClass('active');
    }else{
        $(this).removeClass('active');
    }
});
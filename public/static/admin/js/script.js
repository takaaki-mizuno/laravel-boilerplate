/**
 * Created by thanhnt on 12/16/16.
 */

toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": false,
    "progressBar": false,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "7000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};

$(document).on('click', function(){
    if( $(".t-pagination-number").hasClass('ope') ) {
        $(".t-pagination-number").removeClass('ope');
    } else {
        $(".t-pagination-dropdown").hide();
    }
});
$(".t-pagination-number").on('click', function(){
    $(".t-pagination-dropdown").toggle();
    $(".t-pagination-number").toggleClass('ope');
});
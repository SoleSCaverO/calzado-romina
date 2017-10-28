// We are going to show a custom modal to explain with more detail each option
$(function () {
    $('[data-info]').on('click', showInfoModal);
});

function showInfoModal() {
    var target_modal = $(this).data('info');
    $('#modal'+target_modal).modal('show');
}

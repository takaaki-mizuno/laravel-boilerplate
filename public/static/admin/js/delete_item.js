$(function () {
    $('.delete-button').click(function () {
        var self = $(this),
            url = self.attr('data-delete-url');

        $.ajax({
            url: url,
            method: 'DELETE',
            data: {
                '_token': Boiler.csrfToken
            },
            error: function (xhr, error) {
                console.log(error);
                self.loading = false;
            },
            success: function (response) {
                location.reload();
            }
        });
    });
});
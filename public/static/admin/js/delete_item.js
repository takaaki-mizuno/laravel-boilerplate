$(function () {
    $('.delete-button').click(function () {
        if (window.confirm("Are you sure to delete this item ?") === true) {

            var self = $(this),
                url = self.attr('data-delete-url');

            $.ajax({
                url: url,
                method: 'DELETE',
                data: {
                    '_token': Boilerplate.csrfToken
                },
                error: function (xhr, error) {
                    console.log(error);
                    self.loading = false;
                    location.reload();
                },
                success: function (response) {
                    location.reload();
                }
            });
        }
    });
});
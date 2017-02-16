$(function () {
    $('.datetime-field').datetimepicker({'format': 'YYYY-MM-DD HH:mm:ss', 'defaultDate': new Date()});

    $('#cover-image').change(function (event) {
        $('#cover-image-preview').attr('src', URL.createObjectURL(event.target.files[0]));
    });

    // for Froala editor
    $('#froala-editor').froalaEditor({
        toolbarInline: false,
        pastePlain: true,
        heightMin: 200,
        heightMax: 500,
        imageUploadURL: Boilerplate.imageUploadUrl,
        imageUploadParams: Boilerplate.imageUploadParams,
        imageManagerLoadURL: Boilerplate.imagesLoadURL,
        imageManagerLoadParams: Boilerplate.imagesLoadParams,
        imageManagerDeleteURL: Boilerplate.imageDeleteURL,
        imageManagerDeleteParams: Boilerplate.imageDeleteParams,
        imageManagerDeleteMethod: "DELETE"
    }).on('froalaEditor.initialized', function (e, editor) {

    }).on('froalaEditor.focus', function (e, editor) {

    }).on('froalaEditor.image.inserted', function (e, editor, img) {
        img.attr("width", "100%");
        console.log(img);
    }).on('froalaEditor.image.error', function (e, editor, error) {
        console.log(error);
    }).on('froalaEditor.image.removed', function (e, editor, $img) {
        var params = {};
        params.url = $img.attr('src');
        editor.options.imageDeleteParams = params;
        editor.deleteImage($img);
    });

    $('#button-preview').click(function () {
        var editor = $('#edit'),
            html = editor.froalaEditor('html.get', false, false);
        $('#preview-title').val($('#title').val());
        $('#preview-locale').val($('#locale').val());
        $('#preview-content').val($('#froala-editor').val());
        $('#form-preview').submit();
        return false;
    });
});

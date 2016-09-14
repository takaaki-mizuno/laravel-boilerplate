$(function () {
    $('#input-publish-started-at').datetimepicker({'format': 'YYYY-MM-DD HH:mm:ss'});
    $('#input-publish-ended-at').datetimepicker({'format': 'YYYY-MM-DD HH:mm:ss'});

    var content = $('#input-content').val(),
        editor = $('#edit');
    if (!content) {
        content = "";
    }

    // Content Editor
    editor.froalaEditor({
        toolbarInline: false,
        pastePlain: true,
        height: 500,
        heightMin: 500,
        heightMax: 500,
        imageUploadURL: Boilerplate.imageUploadUrl,
        imageUploadParams: Boilerplate.imageUploadParams,
        imageManagerLoadURL: Boilerplate.imagesLoadURL,
        imageManagerLoadParams: Boilerplate.imagesLoadParams,
        imageManagerDeleteURL: Boilerplate.imageDeleteURL,
        imageManagerDeleteParams: Boilerplate.imageDeleteParams,
        imageManagerDeleteMethod: "DELETE"
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
    editor.froalaEditor('html.set', content, true);

    var saveButton = $("#button-save");
    saveButton.click(function () {
        var editor = $('#edit'),
            html = editor.froalaEditor('html.get', false, false);
        $('#input-content').val(html);
        $('#form-article').submit();
        return false;
    });

    $('#button-preview').click(function () {
        var editor = $('#edit'),
            html = editor.froalaEditor('html.get', false, false);
        $('#preview-title').val($('#input-title').val());
        $('#preview-locale').val($('#locale').val());
        $('#preview-content').val(html);
        $('#form-preview').submit();
        return false;
    });

});

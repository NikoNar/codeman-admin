CKEDITOR.plugins.add('media', {
    icons: 'upload',
    init: function (editor) {
        editor.ui.addButton('Media', {
            icon: this.path + 'icons/upload.png',
            label: 'Upload Media',
            command: 'uploadMedia',
            toolbar: 'insert',
            attributes: {'class': 'media-open'},
        });
        editor.addCommand('uploadMedia', {
            exec: function (editor) {
                $('body').append('<div id="ck" data-editor="ckeditor" class="media-open multichoose"></div>');
                $("#ck").trigger("click");
                $('#ck').remove();

                $('body').off('click', '.use_in_editor').on('click', '.use_in_editor', function (e) {
                        e.preventDefault();
                        e.stopPropagation();

                        if (!$('.img-selected').length) {
                            alert("No File Chosen");
                        } else {
                            var data = "";
                            $('.img-selected').each(function () {
                                if ($(this).parent().find($('input[name=source]')).length) {          // ie. is not an image
                                    var href = $(this).parent().find($('input[name=source]')).val();
                                    var icon = $(this).parent().find($('.icon')).attr('src');
                                    var name = $(this).parent().find($('input[name=filename]')).val();
                                    var ext = href.substr(href.lastIndexOf('.'));
                                    data += '<a href="' + href + '" download="' + name+ext + '"><img src="' + icon + '" alt="icon"  width="35px" height="35px">' + name + '</a>';
                                } else {
                                    var src = $(this).parent().find($('input[name=full-size-url]')).val();
                                    var alt = $(this).parent().find($('input[name=alt]')).val();
                                    data = data + '<img src="' + src + '" alt="' + alt + '">';
                                }

                            });
                            editor.insertHtml(data);
                            $('#media-popup').modal('hide');
                        }
                    }
                );
            }
        });
    }
});
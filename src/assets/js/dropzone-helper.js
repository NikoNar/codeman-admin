var photo_counter = 0;
Dropzone.options.realDropzone = {

    uploadMultiple: false,
    parallelUploads: 100,
    maxFilesize: 8,
    previewsContainer: '#dropzonePreview',
    previewTemplate: document.querySelector('#preview-template').innerHTML,
    addRemoveLinks: false,
    dictRemoveFile: 'Remove',
    dictFileTooBig: 'Image is bigger than 8MB',

    // The setting up of the dropzone
    init:function() {

        this.on("removedfile", function(file) {
            console.log(file);
            $.ajax({
                type: 'POST',
                url: app.ajax_url+ '/media/delete',
                data: {id: file.name, _token: $('#csrf-token').val()},
                dataType: 'html',
                success: function(data){
                    var rep = JSON.parse(data);
                    if(rep.code == 200)
                    {
                        photo_counter--;
                        // $("#photoCounter").text( "(" + photo_counter + ")");
                    }

                }
            });

        } );
    },
    error: function(file, response) {
        if($.type(response) === "string")
            var message = response; //dropzone sends it's own error messages in string
        else
            var message = response.message;
        file.previewElement.classList.add("dz-error");
        _ref = file.previewElement.querySelectorAll("[data-dz-errormessage]");
        _results = [];
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i];
            _results.push(node.textContent = message);
        }
        return _results;
    },
    success: function(file,done) {
        console.log(file);
        console.log(done);
        if(done && done.image){
            var item = '';
            item += '<div class="item" data-id="'+done.image.filename+'">';
                item += '<div class="actions">';
                    item += '<button class="btn btn-sm btn-primary btn-flat details" type="button" >';
                        item += '<i class="fa fa-info-circle"></i> Details';
                    item += '</button>';
                    item += '<button class="btn btn-sm btn-danger btn-flat delete-file" type="button" >';
                        item += '<i class="fa fa-trash"></i> Delete';
                    item += '</button>';
                item += '</div>';
                item += '<img src="'+app.ajax_url+'/media/icon_size/'+done.image.filename+'" alt="'+done.image.alt+'" class="thumbnail img-responsive">';
                // item += '<span class="filename">';
                //     item += done.image.original_name;
                // item += '</span>';
                item += '<input type="hidden" name="filename" value="'+done.image.original_name+'">';
                item += '<input type="hidden" name="alt" value="'+done.image.alt+'">';
                item += '<input type="hidden" name="width" value="'+done.image.width+'">';
                item += '<input type="hidden" name="height" value="'+done.image.height+'">';
                item += '<input type="hidden" name="file_size" value="'+done.image.file_size+'">';
                item += '<input type="hidden" name="file_type" value="'+done.image.file_type+'">';
                item += '<input type="hidden" name="full-size-url" value="'+app.ajax_url+'/media/full_size/'+done.image.filename+'">';
                item += '<input type="hidden" name="created_at" value="'+done.image.created_at+'">';
            item += '</div>';
            $('.media-container').prepend(item);
        }

        else if(done && done.file){
            var ext = 'doc.png';
            switch (done.ext) {
                case 'pdf':
                    ext = "pdf.png"
                    break;
                case 'ppt':
                    ext = "ppt.png"
                    break;
                case 'txt':
                    ext = "txt.png"
                    break;
                case 'xls':
                    ext = "xls.png"
                    break;
                case 'xlsx':
                    ext = "xls.png"
                    break;
            }
            var item = '';
            item += '<div class="item" data-id="'+done.file.filename+'">';
            item += '<div class="actions">';
            item += '<button class="btn btn-sm btn-primary btn-flat details" type="button" >';
            item += '<i class="fa fa-info-circle"></i> Details';
            item += '</button>';
            item += '<button class="btn btn-sm btn-danger btn-flat delete-file" type="button" >';
            item += '<i class="fa fa-trash"></i> Delete';
            item += '</button>';
            item += '</div>';
            item += '<img src="'+app.ajax_url+'/admin-panel/images/icons/extentions/'+ext+'" alt="'+done.file.alt+'" class="img-responsive" style="background-color: #fff">';
            // item += '<span class="filename">';
            //     item += done.image.original_name;
            // item += '</span>';
            item += '<input type="hidden" name="filename" value="'+done.file.original_name+'">';
            item += '<input type="hidden" name="alt" value="'+done.file.alt+'">';
            item += '<input type="hidden" name="width" value="'+done.file.width+'">';
            item += '<input type="hidden" name="height" value="'+done.file.height+'">';
            item += '<input type="hidden" name="file_size" value="'+done.file.file_size+'">';
            item += '<input type="hidden" name="file_type" value="'+done.file.file_type+'">';
            item += '<input type="hidden" name="full-size-url" value="'+app.ajax_url+'/media/full_size/'+done.file.filename+'">';
            item += '<input type="hidden" name="created_at" value="'+done.file.created_at+'">';
            item += '</div>';
            $('.media-container').prepend(item);
        }
        // photo_counter++;
        // $("#photoCounter").text( "(" + photo_counter + ")");
    }
}

$('body').off('click', '.upload-image-dropzone').on('click', '.upload-image-dropzone', function(e){
    e.preventDefault();
    $('.upload_section').fadeIn();
});
$('body').off('click', '.close-upload-file').on('click', '.close-upload-file', function(e){
    e.preventDefault();
    $('.upload_section').fadeOut();
});
$('body').off('click', '.dz-choose-file-btn').on('click', '.dz-choose-file-btn', function(e){
    $('form.dropzone').trigger('click');
});





var directors_table = $('#directors-table').DataTable({
    'paging'      : false,
    'lengthChange': false,
    'searching'   : false,
    'ordering'    : false,
    'info'        : false,
    'autoWidth'   : false
});

// var dCounter = "{!! $film->directors()->count() !!}";
$(document).ready(function () {
    var dCounter = $('.color-count').data('count');


    $('body').off('click', '#add-color-row').on('click', '#add-color-row', function (e) {
        e.preventDefault();
        directors_table.row.add([
            '<input type="text" name="colors[name][]" required class="form-control no-padding-right" placeholder="Color Name" style="width:100%">',
            '<input type="color" name="colors[color][]" class="form-control"  style="width:100%;">',
            '<div class="fileupload fileupload-new" data-provides="fileupload border-right"> <div class="fileupload-preview thumbnail color-image-upload"> <img src="" class="img-responsive" alt="No Featured Image" onerror="imgError(this);" id="thumbnail-image"> </div> <div> <span class="btn btn-file btn-primary btn-flat col-md-6 media-open film-director-img"> <span class="fileupload-new"><i class="fa fa-camera"></i></span> </span> <a href="javascript:void(0)" class="btn fileupload-exists btn-danger btn-flat col-md-6" data-dismiss="fileupload" id="remove-thumbnail"><i class="fa fa-trash"></i></a> <div class="clearfix"></div> <br> <div class="form-group  col-md-12 no-padding"> <label for="thumbnail">Image Url</label><input type="text" name="colors[pic][]" class="form-control no-padding-right" placeholder="Enter image url or chosse from media"  style="width:100%"> </div> </div> </div>',
            '<div class="fileupload fileupload-new" data-provides="fileupload border-right"> <div class="fileupload-preview thumbnail" style="width: 100%;"> <img src="" class="img-responsive" alt="No Featured Image" onerror="imgError(this);" id="thumbnail-image"> </div> <div> <span class="btn btn-file btn-primary btn-flat col-md-6 media-open film-director-img"> <span class="fileupload-new"><i class="fa fa-camera"></i></span> </span> <a href="javascript:void(0)" class="btn fileupload-exists btn-danger btn-flat col-md-6" data-dismiss="fileupload" id="remove-thumbnail"><i class="fa fa-trash"></i></a> <div class="clearfix"></div> <br> <div class="form-group  col-md-12 no-padding"> <label for="thumbnail">Image Url</label><input type="text" name="colors[prod][]" class="form-control no-padding-right" placeholder="Enter image url or chosse from media"  style="width:100%"> </div> </div> </div>',
            '<button class="btn btn-md btn-flat btn-danger remove-color-row" type="button"><i class="fa fa-minus"> </i></button>'
        ]).draw();
        dCounter++;
    });

    $('body').off('click', '.remove-color-row').on('click', '.remove-color-row', function (e) {
        e.preventDefault();
        directors_table.row($(this).closest('tr')).remove().draw();
        dCounter--;
    });
});
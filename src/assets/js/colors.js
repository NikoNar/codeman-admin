var directors_table = $('#options-table').DataTable({
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


    $('body').off('click', '#add-option-row').on('click', '#add-option-row', function (e) {
        e.preventDefault();
        let optionGroupType = $('#options-table').data('group-type');
        console.log(optionGroupType);
        let optionFiled = '<input type="text" name="option[value][]" class="form-control" value="" style="width:100%;">'
        if(optionGroupType == 'colorpicker'){
            optionFiled = '<input type="color" name="option[value][]" class="form-control" value="" style="width:100%;">';
        }else if(optionGroupType == 'image'){
           optionFiled = '<div class="fileupload fileupload-new" data-provides="fileupload border-right"> <div class="fileupload-preview thumbnail color-image-upload"> <img src="" class="img-responsive" alt="No Featured Image" onerror="imgError(this);" id="thumbnail-image"> </div> <div> <span class="btn btn-file btn-primary btn-flat col-md-6 media-open film-director-img"> <span class="fileupload-new"><i class="fa fa-camera"></i></span> </span> <a href="javascript:void(0)" class="btn fileupload-exists btn-danger btn-flat col-md-6" data-dismiss="fileupload" id="remove-thumbnail"><i class="fa fa-trash"></i></a> <div class="clearfix"></div> <br> <div class="form-group  col-md-12 no-padding"> <label for="thumbnail">Image Url</label><input type="text" name="option[value][]" class="form-control no-padding-right" placeholder="Enter image url or chosse from media"  style="width:100%"> </div> </div> </div>';
        }
        directors_table.row.add([
            '<input type="text" name="option[name][]" required class="form-control no-padding-right" placeholder="Option Name" style="width:100%">',
            optionFiled,
            '<button class="btn btn-md btn-flat btn-danger remove-color-row pull-right" type="button"><i class="fa fa-minus"> </i></button>'
        ]).draw();
        dCounter++;
    });

    $('body').off('click', '.remove-option-row').on('click', '.remove-option-row', function (e) {
        e.preventDefault();
        directors_table.row($(this).closest('tr')).remove().draw();
        dCounter--;
    });
});
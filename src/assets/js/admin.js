imgError = function (image) {
    image.onerror = "";
    image.src = "/admin-panel/images/no-image.jpg";
    return true;
}
function isJson(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('.thumbnail').find('img')
                .attr('src', e.target.result)
        };

        reader.readAsDataURL(input.files[0]);
    }
}
function bytesToSize(bytes) {
    var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    if (bytes == 0) return '0 Byte';
    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
};
Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};
$(document).ajaxStart(function () {
    Pace.restart()
})
function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev) {
    ev.dataTransfer.setData("text/html", ev.target.id);
}

function drop(ev) {
    console.log(ev);
    ev.preventDefault();
    var data = ev.dataTransfer.getData("text/html");
    console.log(data);
    ev.target.appendChild(document.getElementById(data));
}
function checkImageAvailability()
{
    $('body').find("img").on('error', function () {
        $(this).unbind("error").attr("src", "/admin-panel/images/no-image.jpg");
    });
}
function generateDataTable()
{
    if($('table').is('#data-table')){
        dataTable = $('#data-table').DataTable({
            'paging'      : false,
            'lengthChange': false,
            'searching'   : false,
            'ordering'    : true,
            'info'        : false,
            'autoWidth'   : false,
            "order": [],
            "columnDefs": [ {
                "targets"  : 'no-sort',
                "orderable": false,
            }]
        });
    }
}

function generateSortableDataTable()
{
    if($('table').is('#sortable-table')){
        sortableTable = $('#sortable-table').DataTable({
            'paging'      : false,
            'lengthChange': false,
            'searching'   : false,
            'ordering'    : false,
            'info'        : false,
            'autoWidth'   : false,
            "order": [],
            "columnDefs": [ {
                "targets"  : 'no-sort',
                "orderable": false,
            }],
        });
        setToBySortableTable();
    }
}

//Helper function to keep table row from collapsing when being sorted
var fixHelperModified = function(e, tr) {
    console.log(e);
    console.log($(tr).data('id'));
    var $originals = tr.children();
    var $helper = tr.clone();
    $helper.children().each(function(index)
    {
        $(this).width($originals.eq(index).width())
    });
    return $helper;
};

//Make diagnosis table sortable
function setToBySortableTable()
{
    $("#sortable-table tbody").sortable({
        helper: fixHelperModified,
        stop: function(event,ui) {
            renumber_table('#sortable-table')
        }
    }).disableSelection();
}

//Renumber table rows
function renumber_table(tableID) {
    ids = [];
    $(tableID + " tr").each(function() {
        if($(this).data('id') != undefined){
            ids.push($(this).data('id'));
        }
    });
    updateOrder(ids.reverse());
}
function updateOrder(ids)
{
    $.ajax({
        type: 'POST',
        url: app.ajax_url+ '/admin/resource/update-order',
        dataType: 'JSON',
        data: {'ids' : ids, 'model' : $('#modelName').val(), '_token' : $('meta[name="csrf-token"]').attr('content')},
        success: function(data){
            console.log(data);
        }
    });
}

$(document).ready(function(){

    $('select').select2();
    generateDataTable();
    checkImageAvailability();
    generateSortableDataTable();

    $("img").on('error', function () {
        $(this).unbind("error").attr("src", "/admin-panel/images/no-image.jpg");
    });
    $.each($("img"), function(){
        if ($(this).attr('src') === "unknown" || $(this).attr('src') === "undefined" ||  $(this).attr('src') === "") {
            $(this).attr("src", "/admin-panel/images/no-image.jpg");
        }
    })

    $('input#thumbnail').on('change', function(){
        console.log(readURL(this));
    });
    $('#remove-thumbnail').on('click', function(e){
        e.preventDefault();
        $('#thumbnail-image').attr('src', null);
        $('input#thumbnail').val('');
        imgError(document.getElementById('thumbnail-image'));
    });

    $('body').off('click', '.remove-thumbnail').on('click', '.remove-thumbnail', function(e){
        e.preventDefault();
        var container = $(this).closest('.fileupload');

        container.find('.thumbnail-image').attr('src', null);
        container.find('input.thumbnail').val('');
        imgError(document.getElementById('thumbnail-image'));
    });

    $('body').off('click', '.media-open').on('click', '.media-open', function(e){
        e.preventDefault();
        var ckeditor = ($('#ck').data("editor") == 'ckeditor')? true : false;
        var isMultichoose = $(this).hasClass('multichoose');
        var isPdf = $(this).hasClass('pdf');
        var meta = $(this).data('meta');
        // console.log('meta', meta);
        thumbnail_container = $(this).closest('.fileupload');
        resource_id = $(this).hasClass('featured-img-change') ? $(this).closest('tr').data('id') : 0;
        chnage_just_image = $(this).hasClass('img-change') ? $(this) : 0;
        if(chnage_just_image == 0 && $(this).closest('.media-attach-bg').length){
            chnage_just_image = $(this).closest('.media-attach-bg');
        }
        film_director_img_tr = $(this).hasClass('film-director-img') ? $(this).closest('td') : 0;

        $('#media-popup').remove();
        $.ajax({
            type: 'GET',
            url: app.ajax_url+ '/admin/media/popup',
            dataType: 'JSON',
            data: {'multichoose' : isMultichoose,
                'ckeditor' : ckeditor,
                'pdf': isPdf,
                'meta' : meta

            },
            success: function(data){
                var dropzoneCss = document.createElement("link");
                dropzoneCss.rel = "stylesheet";
                dropzoneCss.href = app.ajax_url + "/admin-panel/plugins/dropzone/dropzone.css";

                $("head").prepend(dropzoneCss);
                // $("body").append(dropzoneHelper);
                $('body').append(data.html);
                Dropzone.discover();

                $('#media-popup').modal('show');

            }
        });
    });


    $('body').off('click', '.gallery-show-container .media-item .remove').on('click', '.gallery-show-container .media-item .remove', function(e){
        e.preventDefault();
        e.stopPropagation();
        var itemIndex = $(this).closest('.media-item').index() - 1;
        // console.log(itemIndex);
        // console.log(galleryImagesArr);

        $(this).closest('.media-item').fadeOut(400, function(){$(this).remove()});

        var index = $(this).closest('.gallery-show-container').data('meta');

        if($('.gallery-container[data-id='+index+']').find('.meta_images').length > 0){
            var res = galleryObj[index]? galleryObj[index] : galleryImagesArr;
            // console.log(res);

            res.splice(itemIndex, 1);
            if(res.length === 0){
                $(this).closest('.gallery-show-container').find('.empty-gallery').fadeIn();
            }
            // console.log(res);
            $('.gallery-container[data-id='+index+']').find('.meta_images').val(JSON.stringify(res));

        } else {
            galleryImagesArr.splice(itemIndex, 1);
            $('#images').val(JSON.stringify(galleryImagesArr));
        }

    });

    $('body').off('keyup', '.gallery-show-container .media-item input[name="thumbnail-alt"]').on('keyup', '.gallery-show-container .media-item input[name="thumbnail-alt"]', function(e){
        e.preventDefault();
        e.stopPropagation();
        var itemIndex = $(this).closest('.media-item').index() - 1;
        var itemImageAlt =  this.value;
        console.log(itemIndex);
        // console.log(galleryImagesArr);

        // $(this).closest('.media-item').fadeOut(400, function(){$(this).remove()});
        galleryImagesArr[itemIndex].alt = itemImageAlt;
        var galleryId = $(this).closest('.gallery-container').data('id');
        if (galleryId) {
            $('.gallery-container[data-id='+galleryId+']').find('.meta_images').val(JSON.stringify(galleryImagesArr));
        } else {
            $('#images').val(JSON.stringify(galleryImagesArr));
        }

    });

    $('body').off('click', '.media-container .item .details').on('click', '.media-container .item .details', function(e){
        e.preventDefault();
        // console.log(e.target)
        // if($(e.target).is('.delete-file') || $(e.target).is('.delete-file-icon')){
        //     console.log('is delete')
        //     e.preventDefault();
        //     return;
        // }
        var mediaItem = $(this).closest('.item');
        var filename = mediaItem.find('input[name="filename"]').val();
        var alt = mediaItem.find('input[name="alt"]').val();
        var fullSizeUrl = mediaItem.find('input[name="full-size-url"]').val();
        var created_at  = mediaItem.find('input[name="created_at"]').val();
        var fileSize  = bytesToSize(mediaItem.find('input[name="file_size"]').val());
        var fileType  = mediaItem.find('input[name="file_type"]').val();
        var dimensions  = mediaItem.find('input[name="width"]').val()+' x '+mediaItem.find('input[name="height"]').val();

        // var
        $('#file-info-modal').find('.full-image').attr('src', fullSizeUrl);
        $('#file-info-modal').find('.modal-title').text(filename);

        $('#file-info-modal').find('input#url').val(fullSizeUrl);
        $('#file-info-modal').find('input#original_name').val(filename);
        $('#file-info-modal').find('input#alt').val(alt);
        $('#file-info-modal').find('#created_at').text(created_at);
        $('#file-info-modal').find('#dimensions').text(dimensions);
        $('#file-info-modal').find('#file_size').text(fileSize);
        $('#file-info-modal').find('#file_type').text(fileType);
        $('#file-info-modal').find('input#data-id').val(mediaItem.data('id'));

        $('#file-info-modal').modal('show');
    });

    $('body').off('click', '.delete-file').on('click', '.delete-file', function(e){
        e.preventDefault();
        // console.log($(this).parents('div').is('.item'));
        if($(this).parents('div').is('.item')){
            var image = $(this).parents('.item');
            var id = image.data('id');
        }else{
            var id = $(this).siblings('input#data-id').val();
            var image = $('div[data-id="'+id+'"]');
        }
        // console.log(image)
        $.ajax({
            type: 'POST',
            url: app.ajax_url+ '/admin/media/delete',
            data: {id: id, _token: $('#csrf-token').val()},
            dataType: 'json',
            success: function(rep){
                // console.log(data.code);
                // var rep = JSON.parse(data);
                if(rep.code == 200)
                {
                    image.remove();
                    $('#file-info-modal').modal('hide');

                }

            }
        });
    });

    $('body').off('click', '#resource-bulk-action').on('click', '#resource-bulk-action', function(e){
        e.preventDefault();
        e.stopPropagation();
        // var query = this.value;
        ids = [];
        $.each($('input[name="checked"]'), function(k, v){
            if($(this).prop('checked') && this.value != 'on'){
                ids.push(this.value);
            }
        });
        if(ids.length <= 0) return false;
        console.log($('#modelName').val());
        console.log($('meta[name="csrf-token"]').attr('content'));
        $.ajax({
            type: 'POST',
            url: app.ajax_url+ '/admin/resource/bulk-delete',
            dataType: 'JSON',
            data: {'ids' : ids, 'model' : $('#modelName').val(), '_token' : $('meta[name="csrf-token"]').attr('content')},
            success: function(data){
                console.log(data);
                // console.log(typeof data.ids);
                $('#resource-container').html(data.html);
                checkImageAvailability();
                generateDataTable();
                // if(data.success == true && typeof data.ids == 'object'){
                // for (var i = 0; i < data.ids.length; i++) {
                // dataTable.row( $('body').find('table tr[data-id="'+ data.ids[i] +'"]') ).remove().draw();
                // $('body').find('table tr[data-id="'+ data.ids[i] +'"]').fadeOut(400, function(){$(this).remove()});
                // }
                // }
            }
        });
    });

    $('body').off('keyup', '#resource-search').on('keyup', '#resource-search', function(e){
        filterAjax($(this));
    });
    $('body').off('keyup', '#email-search').on('keyup', '#email-search', function(e){
        filterAjax($(this));
    });
    $('body').off('change', 'form#listing-filter select').on('change', 'form#listing-filter select', function(){
        filterAjax($(this));
    });
    $('body').off('keyup', '#resource-perpage').on('keyup', '#resource-perpage', function(){
        filterAjax($(this));
    });



    $('body').off('keyup', '#media-search').on('keyup', '#media-search', function(e){
        e.preventDefault();
        e.stopPropagation();
        var query = this.value;
        var multichoose = $(this).hasClass('multiple')? 1 : 0;
        $.ajax({
            type: 'GET',
            url: app.ajax_url+ '/admin/media/search',
            dataType: 'JSON',
            data: {
                'query' : query,
                'multichoose' :multichoose
            },
            success: function(data){
                // console.log(data);
                if(data.success == true){
                    $('.media-container').html(data.html)
                }
            }
        });
    });

    $('body').off('click', '#update-media').on('click', '#update-media', function(e){
        e.preventDefault();
        var formData = $('form#media-info').serialize();
        // console.log(formData);
        $.ajax({
            type: 'POST',
            url: app.ajax_url+ '/admin/media/update',
            dataType: 'JSON',
            data: formData,
            success: function(data){

            }
        });
    });

    $('body').off('click', '#add-category').on('click', '#add-category', function(e){
        e.preventDefault();
        // console.log('#add-category click');
        // $('#store_category').trigger('reset');
        // $('#category-add-edit').modal('show');
        var type = $(this).data('type');
        $.ajax({
            type: 'GET',
            url: app.ajax_url+ '/admin/categories/create/'+type,
            dataType: 'JSON',
            success: function(data){
                if(data.success == true){
                    $('#category-add-edit').remove();
                    setTimeout(function(){
                        $('body').append(data.html);
                        $('#category-add-edit').modal('show');
                    },1000);
                }
            }
        });
    });
    $('body').off('submit', '#store_category').on('submit', '#store_category', function(e){
        e.preventDefault();
        var formData = $(this).serialize();
        var type = $('#add-category').data('type');
        var selected = $('#category').val();
        $.ajax({
            type: 'POST',
            url: app.ajax_url+ '/admin/categories/store',
            data: formData+'&type='+type+'&selected='+selected,
            success: function(data){
                $('#category').html('');
                $('#category').html(data.html);
                $('#category-add-edit').modal('hide');
            }
        });

    });
    // $('body').off('click', '.edit-category').on('click', '.edit-category', function(e){
    //     e.preventDefault();
    //     console.log('#edit-category click')
    //     var id = $(this).data('id');
    //     var type = $(this).data('type');
    //     $.ajax({
    //         type: 'GET',
    //         url: app.ajax_url+ '/admin/categories/edit/'+id+'/'+type,
    //         dataType: 'JSON',
    //         success: function(data){
    //             if(data.success == true){
    //                 $('#category-add-edit').remove();
    //                 setTimeout(function(){
    //                     $('body').append(data.html);
    //                     $('#category-add-edit').modal('show');
    //                 },1000);
    //             }
    //         }
    //     });
    // });

    $('body').off('click', '.confirm-del').on('click', '.confirm-del', function(e){
        if (!confirm("Are you sure?")) {
            e.preventDefault();
        }
    });
});
function filterAjax($this)
{

    var form = $this.closest('form');
    var queryString = form.serialize();
    // console.log(queryString);
    var key = $this.attr('name');
    var value = $this.val();
    gueryStringBuilder(queryString);
    $.ajax({
        type: 'GET',
        url: app.ajax_url+ '/admin/resource/filter',
        dataType: 'JSON',
        data: queryString + '&model=' + $('#modelName').val()+ '&type=' + $('#resource_type').val(),
        success: function(data){
            // console.log(data);
            if(data.success == true){
                $('#resource-container').html(data.html);
                checkImageAvailability();
                generateDataTable();
                setToBySortableTable();

            }
        }
    });
}
function gueryStringBuilder(queryString) {
    if (history.pushState) {
        var url = window.location.protocol + "//" + window.location.host + window.location.pathname;
        var newurl = url + '?' + queryString;
        window.history.pushState({path:newurl},'',newurl);
    }
}

function checkAll(source) {
    checkboxes = document.getElementsByName('checked');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}

$(function() {
    if($('div').is('#submit-fixed')){
        var $sidebar   = $("#submit-fixed"),
            $window    = $(window),
            offset     = $sidebar.offset(),
            topPadding = 0;

        var sidebarPossitions = document.getElementById('submit-fixed').getBoundingClientRect();
        // console.log(sidebarPossitions.top, sidebarPossitions.right, sidebarPossitions.bottom, sidebarPossitions.left);

        var mainContainer = document.getElementById('main-container').getBoundingClientRect();
        // console.log(mainContainer.top, mainContainer.right, mainContainer.bottom, mainContainer.left);
        // var start =


        // console.log($window.scrollTop());
        // console.log(offset.top);
        // console.log($window.scrollTop() - offset.top)
        $window.scroll(function() {
            // console.log($window.scrollTop());
            // console.log(offset.top);
            // console.log($window.scrollTop() - offset.top)
            if(offset.top - $window.scrollTop() <= 54){
                $sidebar.css({
                    'position':'fixed',
                    'width': $sidebar.width(),
                    'top': -100

                });
                setTimeout(function(){
                    $sidebar.animate({
                        'top' : 55,
                        'transition': 1
                    });
                }, 1000)
            }else{
                $sidebar.css({
                    'position' : 'static',
                    'top' : 'initial !important',

                });
            }
        });
    }
    $('body').on('click', '.remove_file_name', function(){
        var container = $(this).closest('.download_file_container');
        container.find('.file_name').val('');
        container.find('.file_name_text').html('');
    });

    $('body').on('change', '.video_url_preview', function(){

        var videobox = document.getElementById('video-preview');
        if($(this).val() === "") {
            videobox.style.display = "none";
        }else{
            var url = $(this).val();
            var ifrm = document.createElement('iframe');
            ifrm.src = (!url.includes('vimeo')) ? "//www.youtube.com/embed/"+ url.split("=")[1] : "//player.vimeo.com/video/"+ url.split("/")[3];
            ifrm.width= "100%";
            ifrm.height = "300";
            ifrm.frameborder="0";
            ifrm.scrolling="no";
            $('#video-preview').html(ifrm);
            videobox.style.display = "block";
        }
    });



    // $sidebar.css({
    //     'position':'fixed',
    //     'width': $sidebar.width(),
    //     'top': sidebarPossitions.top,
    //     'left': sidebarPossitions.left,
    //     // 'top' :  $('#main-container').outerHeight(true) - mainContainer.top,
    // });


    $("#colors .fa-plus ").click(function() {
        $(this).closest('.row').clone(true, true).insertAfter($(this).closest('.row'));
        $(this).closest('.row').next().find('.fa-plus').removeClass('fa fa-plus').addClass('fa fa-minus');
    });

    // $(".fa-minus").click(function() {
    //     console.log($(this).closest('.row'));
    //     $(this).closest('.row').delete();
    // });



    // Translations

    $('body').off('change', '.menu_languages').on('change', '.menu_languages', function(e) {
        var id = $('input[name="menu_id"]').val();
        var default_lang = $('#def_lang').val();
        if(id){
            if(!confirm("translate? All not saved data will be lost!")){
                e.preventDefault();
                $(this).val(default_lang);
                $(this).select2('destroy');
                $(this).select2();
            } else {
                var lang = $('.menu_languages').val();
                window.location.href='/admin/menus?menu='+id+'&language='+lang;

            }
        }
    });

    var def_lang = $('.languages').val();


    $('body').off('change', '.languages').on('change', '.languages', function(e){
        var id = $('input[name="resource_id"]').val();
        var segments      = location.pathname.split('/');
        var type = segments[2];
        var resource = $(this).data('resource');
        if(id && type){
            if(!confirm("translate? All not saved data will be lost!")){
                e.preventDefault();
                $(this).val(def_lang);
                $(this).select2('destroy');
                $(this).select2();
            } else {
                var lang = $('.languages').val();
                if(resource){
                    window.location.href='/admin/'+type+'/'+resource+'/translate/'+id+'/'+lang;
                }else{
                    window.location.href='/admin/'+type+'/translate/'+id+'/'+lang;
                }
                // console.log('/admin/'+type+'/translate/'+id+'/'+lang);
                // console.log(id, type);
            }
        } else {
            if(type === 'categories') {
                $.ajax({
                    type: 'GET',
                    url: app.ajax_url+ '/admin/categories/' + segments[4] +'/'+ $(this).val()+'/parent',
                    success: function(data){
                        $('#parent_id').html('');
                        $('#parent_id').html(data.html);
                    }
                });
            } else{
                $.ajax({
                    type: 'GET',
                    url: app.ajax_url+ '/admin/categories/' + segments[3] +'/'+ $(this).val(),
                    success: function(data){
                        $('#category').html('');
                        $('#category').html(data.html);
                    }
                });
            }
        }
    });
});
<script>
    $(document).ready(function() {
        const dataFiltersForm = $('form#data-filters-form');

        const currencyFormatter = new Intl.NumberFormat('ru-RU', {
            style: 'currency',
            currency: 'rub',
            minimumFractionDigits: 0
        });

        var dataTable = $('#ajax-table').DataTable( {
            lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
            scrollX: true,
            // scrollY: window.innerHeight - document.getElementById("ajax-table").offsetTop,
            processing: true,
            serverSide: true,
            // fixedColumns:   {
            //  rightColumns: 1 //this braking dataTableSort function
            // },
            order: [ 5, 'desc' ],
            ajax: {
                url: '{!! url()->current() !!}',
                data : function(data){
                    dataFilters = dataFiltersForm.serializeArray();
                    data.filters = dataFilters;

                    let categories = $('#category').val();
                    let product = $('#table-product').val();

                    data.relations = {
                        'product' : product,
                        'categories' : categories
                    }
                    dataSerialize = function(obj, prefix) {
                      var str = [];
                      for (var p in obj)
                            if (obj.hasOwnProperty(p)) {
                              var k = prefix ? prefix + "[" + p + "]" : p,
                                v = obj[p];
                              str.push((v !== null && typeof v === "object") ?
                                dataSerialize(v, k) :
                                encodeURIComponent(k) + "=" + encodeURIComponent(v));
                            }
                      return str.join("&");
                    }
                    data.order[0].column = data.columns[data.order[0].column].data
                    let UrlDataObj = data;
                    delete UrlDataObj['columns'];
                    let newUrl = dataSerialize(UrlDataObj)
                    window.history.pushState(newUrl, '', window.location.origin+window.location.pathname+'?'+newUrl);
                }
            },
            sDom: 'rtip',
            columns: [
                {   data: 'thumbnail', render: function(data,display, row){
                        if (row.variation && row.variation.thumbnail) {
                            return '<a href="javascript:void(0)">\
		                    		<img src="'+iconSize(row.variation.thumbnail)+'" class="thumbnail img-xs ">\
		                    	</a>';
                        }
                    },
                    orderable: false,
                    defaultContent: '<a href="javascript:void(0)">\
				                    		<img src="/admin-panel/images/no-image.jpg" class="thumbnail img-xs ">\
				                    	</a>',
                    width:'80px',
                },
                { data: 'title', render: function(data,display, row){
                        return row.variation.title;
                    }, width: '200px', orderable: false },
                { data: 'email', render: function(data, display, row){
                        return data;
                    } },
                { data: 'full_name', render: function(data, display, row){
                    return row.user ? row.user.full_name : 'GUEST';
                }, orderable: false },
                {   data: 'created_at', render: function(data){
                        date = new Date(data);
                        return date.toLocaleString('en-US');
                    },
                    // width: '120px'
                },
                {   data: 'updated_at', render: function(data){
                        date = new Date(data);
                        return date.toLocaleString('en-US');
                    },
                    // width: '120px'
                },
                { data: 'notified_at', render: function(data){
                    return (data == null) ? "No"  : "Yes";
                } }
            ],
            select: {
                style:    'os',
                selector: 'td:not(:first-child)'
            },
            createdRow: function (row, data, dataIndex) {
                $(row).attr('data-id', data.id);
            },
            drawCallback: function( settings ) {
                $('[data-toggle="tooltip"]').tooltip();
            }
        } );

        dataTable.on( 'xhr', function () {
            var data = dataTable.ajax.params();
        });

        $('#table-product').on('change', function (e) {
           // dataTable.columns(4).search( $(this).val() ).draw();
           e.preventDefault();
           dataTable.draw();
        } );

        $('#category').on('change', function (e) {
           // dataTable.columns(3).search( $(this).val() ).draw();
           e.preventDefault();
           dataTable.draw();
        } );

        $('#table-search').on('keyup change', function () {
           dataTable.search( $(this).val() ).draw();
        } );

        $('#table-perpage').on('change', function () {
           dataTable.page.len( $(this).val() ).draw();
        } );

        $('form#data-filters-form').on('submit', function(e){
            e.preventDefault();
            dataTable.draw();
        });
    });
</script>

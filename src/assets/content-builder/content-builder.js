var editors = [];
dragula([document.getElementById('content-elements'), document.getElementById('builder-content')], {
	copy: function (el, source) {
		return source === document.getElementById('content-elements');
	},
	accepts: function (el, target) {
		return target === document.getElementById('builder-content');
	},
	moves: function (el, container, handle) {
		if($(container).attr('id') == 'builder-content'){
	    	return $(handle).hasClass('handle-move');
		}
		return true;
	}
}).on('drag', function (el, source) {
	dragElementPosition = [].slice.call(el.parentElement.children).indexOf(el);
}).on('cancel', function(el, container, source){
	//
}).on('drop', function (el, container, source, sibling) {
	var nodes = Array.prototype.slice.call( document.getElementById('builder-content').children );

	var container = $('#builder-content');
	var elID = $(el).attr('id');
	var containerID = $(container).attr('id');
	var sourceID = $(source).attr('id');
	var position = nodes.indexOf(el);

	if($(source).attr('id') == 'content-elements' && position !== -1){

		var id = rendomID();
		var itemID = rendomID()+1;
		if(elID == 'b-slider'){
			var slideHeight = sliderHeight(id);
			var tabHeight = slideHeight+60;
		    $(el).html(makeSlider(id, itemID)).css('min-height', tabHeight);
		    buildCkEditor('editor-'+itemID, id, itemID);
		    addToStorage(elID, id, position);

		    builderOptionsObj = builderOptions.find(obj => {
		      return obj.id === parseInt(id);
		    });
		    // console.log('builderOptionsObj', builderOptionsObj);
		    var elIndex = builderOptions.indexOf(builderOptionsObj);
		    var newItemObj = {'id': itemID, 'image'  : null, 'html'   : null, url : null};
		    // console.log(builderOptionsObj.items);
		    builderOptionsObj.items.push(newItemObj);
    		elementStyleChangeObserver(itemID, id);

		}
		if(elID == 'b-image'){
			var slideHeight = sliderHeight(id);
			var tabHeight = slideHeight+25;
		    $(el).html(makeImage(id)).css('min-height', tabHeight);
		    addToStorage(elID, id, position);
    		elementStyleChangeObserver(id, id);
		}
		if(elID == 'b-text'){
		    $(el).html(makeTextBlock(id));
		    CKEDITOR.disableAutoInline = true;
		    CKEDITOR.inline( 'text-block-editor-'+id ).on('change', function(e) {
		    	var editor = this;
		    	setTimeout(function(){
			        var builderOptionsObj = builderOptions.find(obj => {
			          return obj.id === id
			        });
			        var elIndex = builderOptions.indexOf(builderOptionsObj);
			        var	 html = editor.getData();
			        builderOptionsObj.html = html;
			        builderOptions[elIndex] = builderOptionsObj;
					localStorage.setItem('builderOptions', JSON.stringify(builderOptions));
		    	},1500);

		    });
		    addToStorage(elID, id, position);
		}

		if(elID == 'b-text-2images'){
		    $(el).html(makeTextBlock(id));
		    CKEDITOR.disableAutoInline = true;
		    CKEDITOR.inline( 'text-block-editor-'+id ).on('change', function(e) {
		    	var editor = this;
		    	setTimeout(function(){
			        var builderOptionsObj = builderOptions.find(obj => {
			          return obj.id === id
			        });
			        var elIndex = builderOptions.indexOf(builderOptionsObj);
			        var	 html = editor.getData();
			        builderOptionsObj.html = html;
			        builderOptions[elIndex] = builderOptionsObj;
					localStorage.setItem('builderOptions', JSON.stringify(builderOptions));
		    	},1500);

		    });
		    addToStorage(elID, id, position);
		}
		if(elID == 'b-image-left'){
			var slideHeight = sliderHeight(id);
			var tabHeight = slideHeight+25;
		
		    // $(el).html(makeImage(id, undefined, true)).css('min-height', tabHeight);
		    var img = makeImage(id, undefined, true);
		    var text = makeTextBlock(id, undefined, true);
		    var html = '';
		    
		    html += '<div class="controls">';
			html += '<i class="fa fa-trash fz-25 pull-right cursor-pointer remove-block-content"></i>';
			html += '<i class="handle-move fa fa-arrows-alt"></i>';
			html += '</div>';
			html += '<div class="row">';
		    html += img;
		    html += text;
		    html += '</div>';
		    $(el).html(html);

		    addToStorage(elID, id, position);
    		elementStyleChangeObserver(id, id);
    	
		    CKEDITOR.disableAutoInline = true;
		    CKEDITOR.inline( 'text-block-editor-'+id ).on('change', function(e) {
		    	var editor = this;
		    	setTimeout(function(){
			        var builderOptionsObj = builderOptions.find(obj => {
			          return obj.id === id
			        });
			        var elIndex = builderOptions.indexOf(builderOptionsObj);
			        var	 html = editor.getData();
			        builderOptionsObj.html = html;
			        builderOptions[elIndex] = builderOptionsObj;
					localStorage.setItem('builderOptions', JSON.stringify(builderOptions));
		    	},1500);

		    });


		}

		if(elID == 'b-image-right'){
			var slideHeight = sliderHeight(id);
			var tabHeight = slideHeight+25;
		
		    // $(el).html(makeImage(id, undefined, true)).css('min-height', tabHeight);
		    var img = makeImage(id, undefined, true);
		    var text = makeTextBlock(id, undefined, true);
		    var html = '';
		    
		    html += '<div class="controls">';
			html += '<i class="fa fa-trash fz-25 pull-right cursor-pointer remove-block-content"></i>';
			html += '<i class="handle-move fa fa-arrows-alt"></i>';
			html += '</div>';
			html += '<div class="row">';
		    html += text;
		    html += img;
		    html += '</div>';
		    $(el).html(html);

		    addToStorage(elID, id, position);
    		elementStyleChangeObserver(id, id);
    	
		    CKEDITOR.disableAutoInline = true;
		    CKEDITOR.inline( 'text-block-editor-'+id ).on('change', function(e) {
		    	var editor = this;
		    	setTimeout(function(){
			        var builderOptionsObj = builderOptions.find(obj => {
			          return obj.id === id
			        });
			        var elIndex = builderOptions.indexOf(builderOptionsObj);
			        var	 html = editor.getData();
			        builderOptionsObj.html = html;
			        builderOptions[elIndex] = builderOptionsObj;
					localStorage.setItem('builderOptions', JSON.stringify(builderOptions));
		    	},1500);

		    });


		}



		if(elID == 'b-info-block'){
		    $(el).html(makeInfoBlock(id));
	        CKEDITOR.disableAutoInline = true;
	        CKEDITOR.inline( 'info-block-editor-'+id ).on('change', function(e) {
		    	var editor = this;
		    	setTimeout(function(){
			        var builderOptionsObj = builderOptions.find(obj => {
			          return obj.id === id
			        });
			        var elIndex = builderOptions.indexOf(builderOptionsObj);
			        var	 html = editor.getData();
			        builderOptionsObj.html = html;
			        builderOptions[elIndex] = builderOptionsObj;
					localStorage.setItem('builderOptions', JSON.stringify(builderOptions));
		    	},1500);

		    });
		    addToStorage(elID, id, position);
		}
		if(elID == 'b-offer-block'){
		    $(el).html(makeOffersBlock(id));
		    addToStorage(elID, id, position);
		}
		if(elID == 'b-products-block'){
		    $(el).html(makeProductsBlock(id));
		    addToStorage(elID, id, position);
		}
	}
	if($(source).attr('id') == 'builder-content'){

		changeWidgetOrder(parseInt(dragElementPosition), parseInt(position) );
		// console.log('old position', parseInt(dragElementPosition));
		// console.log('new position', parseInt(position));

	}

}).on('shadow', function(el, target){
	
});

function makeSlider(id, itemID, htmlContent = undefined, image_url = undefined){
	var slideHeight = sliderHeight();
	var tabHeight = slideHeight+39;
   	var html = '';
   	html += '<div class="block-content" data-type="b-slider" data-id="'+id+'" id="'+id+'">';
	   	html += '<ul class="nav nav-tabs slides-tabs">';
	   		html += '<i class="fa fa-trash fz-25 pull-right cursor-pointer remove-block-content" style="padding-top: 8px;"></i>';
	   		html += '<i class="handle-move fa fa-arrows-alt" style="padding-top: 8px; padding-left: 5px;"></i>';
	   		html += '<li class="active"><a data-toggle="tab" href="#slide-'+id+'-'+itemID+'">Slide 1</a><span class="remove-slide remove-icon fa fa-times-circle" data-slide="slide-'+id+'-'+itemID+'"></span></li>';
	   		html += '<li class="not-tab cursor-pointer add-slide"><a><i class="fa fa-plus-circle"></i></a></li>';
	   	html += '</ul>';

	   	html += '<div class="tab-content slides-content">';
	   		html += newSlideItem(id, itemID, 'fade in active"',  htmlContent, image_url);
	   	html += '</div>';
	html += '</div>';
	return html;
}

function newSlideItem(id, itemID, extra_class = '', htmlContent = undefined, image_url = undefined){
	var slideHeight = sliderHeight();
	if(image_url == undefined){
		image_url = newGradient();
	}else{
		image_url = 'url('+image_url+')';
	}
	if(htmlContent == undefined){
		htmlContent = '';
		htmlContent += '<h2>Lorem Ipsum</h2>';
		htmlContent += '<p>Lorem ipsum dolor sit amet, no ferri fabulas eum, duo tale cibo ad. Nam eu ubique repudiare</p>';
		htmlContent += '<br><span class="cta pink ">Read More</span>';
	}
	var	html = '';
	html += '<div id="slide-'+id+'-'+itemID+'" class="slider tab-pane '+extra_class+'" >';
		html += '<div class="slider-item media-attach-bg" id="'+itemID+'" style="min-height:'+slideHeight+'px; background:'+image_url+'" >';
			html += '<div class="controls">';
				html += '<i class="fa fa-camera fz-25 cursor-pointer media-open"></i>';
				html += '<i class="fa fa-link fz-25 pull-right cursor-pointer attach-link" data-slider_id="'+itemID+'"></i>';
				// html += '<i class="fa fa-sliders-h fz-25 pull-right cursor-pointer settings settings-slider"></i>';
			html += '</div>';
			html += '<div class="s-content ckeditor" contenteditable="true" id="editor-'+itemID+'">';
		        html += htmlContent;
		    html += '</div>';
		html += '</div>';
	html += '</div>';
	return html;
}

function makeImage(id, image_url = undefined, col6 = undefined){
	var html = "";
	var col_md_6 = ""
	if(image_url == undefined){
		image_url = newGradient();
	}else{
		image_url = 'url('+image_url+')';
	}

	if(col6 != undefined){
		col_md_6 = " col-md-6"
	} 
	var slideHeight = sliderHeight();
	var tabHeight = slideHeight+39;
   	html += '<div class="block-content'+col_md_6+'" data-type="b-image" data-id="'+id+'">';
		html += '<div id="image-'+id+'" class="b-image" >';
			html += '<div class="media-attach-bg" id="'+id+'" style="min-height:'+slideHeight+'px; background:'+image_url+';">';
				html += '<div class="controls">';
					html += '<i class="fa fa-camera fz-25 cursor-pointer media-open"></i>';
					if(col6 == undefined){				
		   				html += '<i class="fa fa-trash fz-25 pull-right cursor-pointer remove-block-content"></i>';
		   				html += '<i class="handle-move fa fa-arrows-alt"></i>';
		   				// html += '<i class="fa fa-sliders-h fz-25 pull-right cursor-pointer settings settings-image"></i>';
					}
				html += '</div>';
			html += '</div>';
		html += '</div>';
	html += '</div>';
	return html;
}

function makeTextBlock(id, htmlContent = undefined, col6 = undefined){
	var open_div = "";
	var close_div = "";
	

	if(col6 != undefined) {
		open_div = '<div class="col-md-6">';
		close_div = '</div>';
		
	} 
	if(!htmlContent){
		var htmlContent = '';
		htmlContent += '<h1>Heading 1, Lorem ipsum dolor sit amet</h1>';
	    htmlContent += '<h2>Heading 2, Lorem ipsum dolor sit amet</h2>';
	    htmlContent += '<h3>Heading 3, Lorem ipsum dolor sit amet</h3>';
	    htmlContent += '<h4>Heading 4, Lorem ipsum dolor sit amet</h4>';
	    htmlContent += '<h5>Heading 5, Lorem ipsum dolor sit amet</h5>';
	    htmlContent += '<h6>Heading 6, Lorem ipsum dolor sit amet</h6>';

	    htmlContent += '<p>Lorem ipsum dolor sit amet, no ferri fabulas eum, duo tale cibo ad. Nam eu ubique repudiare, ut iisque dignissim vim.</p>';
		htmlContent += '<ul>';
		    htmlContent += '<li>';
		    	htmlContent += 'Ipsum posidonium instructior cum ne, cu sale minimum has, at eam dicant nostro propriae. Nam magna populo dissentias an, eos facer possim labitur eu.';
		    htmlContent += '</li>';
		     htmlContent += '<li>';
		    	htmlContent += 'Ipsum posidonium instructior cum ne, cu sale minimum has, at eam dicant nostro propriae. Nam magna populo dissentias an, eos facer possim labitur eu.';
		    htmlContent += '</li>';
		     htmlContent += '<li>';
		    	htmlContent += 'Ipsum posidonium instructior cum ne, cu sale minimum has, at eam dicant nostro propriae. Nam magna populo dissentias an, eos facer possim labitur eu.';
		    htmlContent += '</li>';
		htmlContent += '</ul>';
	}

	var html = "";
	html+= open_div; 
	if(col6 == undefined) {
		html += '<div class="controls">';
		html += '<i class="fa fa-trash fz-25 pull-right cursor-pointer remove-block-content"></i>';
	   	html += '<i class="handle-move fa fa-arrows-alt"></i>';
		// html += '<i class="fa fa-sliders-h fz-25 pull-right cursor-pointer settings settings-text-block"></i>';
		html += '</div>';
	}
	
	html += '<div class="b-container module rtf intro-text ckeditor" contenteditable="true" id="text-block-editor-'+id+'">';
	    html += htmlContent;
	html += '</div>';
	html += close_div;
	return html;
}

function makeInfoBlock(id, htmlContent = undefined){
	if(!htmlContent){
		var htmlContent = '';
    	htmlContent += '<div class="ibc-content full-width">';
			htmlContent += '<h2>Կապ հաստատել անկախ խորհրդատուի հետ</h2>';
		htmlContent += '</div>';
        htmlContent += '<div class="col-sm-5 cf">';
            htmlContent += '<div class="ibc-content full-width">';
				htmlContent += '<h3>Lorem Ipsum-ը տպագրության և տպագրական արդյունաբերության համար</h3>';
				htmlContent += '<div>';
					htmlContent += '<ul>';
						htmlContent += '<li>Lorem Ipsum-ը տպագրության և տպագրական արդյունաբերության համար</li>';
						htmlContent += '<li>Lorem Ipsum-ը տպագրության և տպագրական արդյունաբերության համար</li>';
						htmlContent += '<li>Lorem Ipsum-ը տպագրության և տպագրական արդյունաբերության համար</li>';
					htmlContent += '</ul>';
				htmlContent += '</div>';
			htmlContent += '</div>';
        htmlContent += '</div>';
        htmlContent += '<div class="col-sm-5 cf">';
            htmlContent += '<div class="ibc-content full-width">';
				htmlContent += '<h3>Lorem Ipsum-ը տպագրության և տպագրական արդյունաբերության համար</h3>';
				htmlContent += '<div>';
					htmlContent += '<ul>';
						htmlContent += '<li>Lorem Ipsum-ը տպագրության և տպագրական արդյունաբերության համար</li>';
						htmlContent += '<li>Lorem Ipsum-ը տպագրության և տպագրական արդյունաբերության համար</li>';
						htmlContent += '<li>Lorem Ipsum-ը տպագրության և տպագրական արդյունաբերության համար</li>';
					htmlContent += '</ul>';
				htmlContent += '</div>';
			htmlContent += '</div>';
        htmlContent += '</div>';
	}

	var html = "";
	html += '<div class="controls">';
	   	html += '<i class="fa fa-trash fz-25 pull-right cursor-pointer remove-block-content"></i>';
	   	html += '<i class="handle-move fa fa-arrows-alt"></i>';
		// html += '<i class="fa fa-sliders-h fz-25 pull-right cursor-pointer settings settings-info-block"></i>';
	html += '</div>';
	html += '<div class="module ibc">';
	    html += '<div class="">';
	        html += '<div class="col-sm-12 ckeditor" contenteditable="true" id="info-block-editor-'+id+'">';
            	html += htmlContent;
	        html += '</div>';
	        html += '<img src="https://www.marykay.ru/-/media/images/mk/europe/russia/esuite/samples/ibc-girl.png?h=214&amp;w=175&amp;la=ru-RU&amp;hash=4C936518BC5AB2B8110A43256E0B200B2A822777" class="ibc-bg" alt="">';
	    html += '</div>';
	html += '</div>';
	return html;
}

function makeOffersBlock(id){
	var html = "";
	html += '<div class="b-offers-container" id="'+id+'">';
		html += '<div class="controls">';
		   	html += '<i class="fa fa-trash fz-25 pull-right cursor-pointer remove-block-content"></i>';
		   	html += '<i class="handle-move fa fa-arrows-alt"></i>';
			// html += '<i class="fa fa-sliders-h fz-25 pull-right cursor-pointer settings settings-info-block"></i>';
		html += '</div>';
		html += '<div class="module marketplace-grid grid-3 responsive vertical">';
			html += '<div class="col-md-12 no-padding">';
				html += '<div class="col-md-4 no-item">';
					html += '<div class="tile left-txt cf">';
						html += '<a href="javascript:void(0)">';
							html += '<div class="add-new-offer">';
								html += '<i class="fa fa-plus-circle fz-35"></i>';
							html += '</div>';
						html += '</a>';
					html += '</div>';
				html += '</div>';
	        html += '</div>';
		html += '</div>';
		html += '<div class="clear-both"></div>';
	html += '</div>';
	return html;
}

var newOfferItem = function(itemID, htmlContent = undefined, image_url = undefined){
	if(image_url == undefined){
		image_url = newGrayGradient();
	}else{
		image_url = 'url('+image_url+')';
	}
	if(htmlContent == undefined){
		htmlContent = '';
		htmlContent += '<h3>Lorem Ipsum Dollar Sit Amet</h3>';
		htmlContent += '<a href="#">';
			htmlContent += '<span class="cta">Read More </span>';
		htmlContent += '</a>';
	}
	var html = "";
	html += '<div class="col-md-4 item media-attach-bg" style="background:'+image_url+'" id="'+itemID+'">';
		html += '<div class="controls">';
			html += '<i class="fa fa-camera fz-25 cursor-pointer media-open"></i>';
				html += '<i class="fa fa-trash fz-25 pull-right cursor-pointer control-remove-icon remove-block-item" data-block_id="'+itemID+'"></i>';
				html += '<i class="fa fa-link fz-25 pull-right cursor-pointer attach-link" data-block_id="'+itemID+'"></i>';
				// html += '<i class="fa fa-sliders-h fz-25 pull-right cursor-pointer settings settings-offer-block-item"></i>';
		html += '</div>';
		html += '<div class="tile left-txt cf">';
			html += '<div class="b-content ckeditor" contenteditable="true" id="editor-'+itemID+'" >';
				html += htmlContent;
			html += '</div>';
		html += '</div>';
	html += '</div>';
	return html;
}

function makeProductsBlock(id, htmlContent = '<h4>Վաճառքի առաջատարներ</h4>', options = null ){
	var html = '';
	html += '<div class="b-products-container" id="'+id+'">';
		html += '<div class="controls">';
		   	html += '<i class="fa fa-trash fz-25 pull-right cursor-pointer remove-block-content"></i>';
		   	html += '<i class="handle-move fa fa-arrows-alt"></i>';
			html += '<i class="fa fa-sliders-h fz-25 pull-right cursor-pointer settings settings-products-block" style="margin-right: 10px;"></i>';
		html += '</div>';
		html +='<div class="b-container module rtf intro-text ">';
		    html +='<div class="title cf ckeditor" contenteditable="true" id="editor-'+id+'">';
		        html += htmlContent;
		    html +='</div>';
		html +='</div>';

		html +='<div class="b-container module product-listing product-spotlight">';
			for($i = 0; $i <= 3; $i++){
				html +='<div class="product cf" style="height: 163px;">';
			    	html +='<div class="img-wrap">';
			            html +='<img src="'+app.ajax_url+'/admin-panel/images/no-image.jpg">';
			    	html +='</div>';
			    	html +='<div class="b-content cf">';
				        html +='<a class="product-name" href="javascript:void(0)">';
				        	html +='Product Name of Mary Kay®';
				    	html +='</a>';
					html +='</div>';
			        html +='<div class="price-bag b-content">';
			            html +='<a class="cf" href="javascript:void(0);">';
			                html +='<p class="price">';
			                    html +='1500 դր';
			                html +='</p>';
			            html +='</a>';
			        html +='</div>';
			    html +='</div>';
		    }
		html +='</div>';
	html +='</div>';
	return html;
}

function buildCkEditor(editorid, containerID, itemID = null){
	// console.log(editorid);
	// console.log(containerID);
	// console.log(itemID);
    CKEDITOR.disableAutoInline = true;
    CKEDITOR.inline( editorid ).on('change', function(e) {
    	var editor = this;
    	setTimeout(function(){
	        builderOptionsObj = builderOptions.find(obj => {
	          return obj.id === parseInt(containerID);
	        });
		    var elIndex = builderOptions.indexOf(builderOptionsObj);
		    var	html = editor.getData();
	        if(builderOptionsObj.html !== undefined){
		        builderOptionsObj.html = html;
		        builderOptions[elIndex] = builderOptionsObj;
	        	// console.log('parent')
	        }else if(itemID != null){
	        	// console.log(builderOptionsObj);
	        	itemOptionsObj = builderOptionsObj.items.find(obj => {
	        	  return obj.id === parseInt(itemID);
	        	});
	        	// console.log(itemOptionsObj);

	        	if(itemOptionsObj.html !== undefined){
		        	itemOptionsObj.html = html;
			        builderOptions[elIndex] = builderOptionsObj;
		        	// console.log('child')
		        }else{
		        	// console.log('smth wrong')
		        }
	        }
			localStorage.setItem('builderOptions', JSON.stringify(builderOptions));
    	},1500);
    });
    // addToStorage(elID, id, position);
}

$('#builder-content').off('click', '.add-slide').on('click', '.add-slide', function(){
	var block = $(this).closest('.block-content');
	var id = block.data('id');
	var itemID = rendomID();
	var slideNumber = $(this).siblings('li').length + 1;

	$(this).before('<li><a data-toggle="tab" href="#slide-'+id+'-'+itemID+'">Slide '+slideNumber+'</a><span class="remove-slide remove-icon fa fa-times-circle" data-slide="slide-'+id+'-'+itemID+'"></span></li>');
	var	slide = newSlideItem(id, itemID) 
	block.find('.slides-content').append(slide);

	builderOptionsObj = builderOptions.find(obj => {
	  return obj.id === parseInt(id);
	});
	// console.log('builderOptionsObj', builderOptionsObj);
	var elIndex = builderOptions.indexOf(builderOptionsObj);
	var newItemObj = {'id': itemID, 'image'  : null, 'html'   : null, 'url' : null};
	// console.log(builderOptionsObj.items);
	builderOptionsObj.items.push(newItemObj);

	buildCkEditor('editor-'+itemID, id, itemID);
    elementStyleChangeObserver(itemID, id);


});

$('#builder-content').off('click', '.attach-link').on('click', '.attach-link', function(e) {
    e.preventDefault();

    var currentSlideId = $(this).data('slider_id');
    var currentBlockId = $(this).data('block_id');
    var currentItemID = "";
    var itemType = "";


    if (currentSlideId != undefined) {currentItemID = currentSlideId; itemType = "slide"; }
    if (currentBlockId != undefined) {currentItemID = currentBlockId; itemType = "block"; }

    if(itemType == 'slide'){
        builderOptionsObjId = $('[data-type="b-slider"]').attr('id');
        var builderOptionsObj = builderOptions.find(obj => {
            return obj.id == builderOptionsObjId;
        });
    }
    if(itemType == 'block') {
        builderOptionsObjId = $('.b-offers-container').attr('id');
        var builderOptionsObj = builderOptions.find(obj => {
            return obj.id == builderOptionsObjId;
        });
    }
    var currentUrl = (builderOptionsObj.items.find(obj=>{
    	return obj.id == currentItemID
	}))['url'];
    currentUrl ? $('.slide_link').val(currentUrl): $('.slide_link').val('');

    $('#attach-link').modal('show');

    var currentItem = builderOptionsObj.items.find(obj => {
        return obj.id === parseInt(currentItemID);
    });

    $(document).keypress(function(e) {
        if(e.which == 13) {
        	$('.save_link').click();
        }
    });



    $('body').off('click', '.save_link').on('click', '.save_link',function(e){
    	e.preventDefault();
    	var link = $('.slide_link').val();

    	if(link == ''){
    		alert('URL is empty');
    		return;
		}
		if(!link.match(/^((https?|ftp|smtp):\/\/)?(www.)?[a-z0-9]+(\.[a-z]{2,}){1,3}(#?\/?[a-zA-Z0-9#]+)*\/?(\?[a-zA-Z0-9-_]+=[a-zA-Z0-9-%]+&?)?$/)){
    		alert('Invalid URL');
    		return;
		}

        // if(currentItem.id === currentItemID){
     // }
		currentItem.url = link;
        console.log(currentItem.url, currentItemID);
        console.log(builderOptions);
        localStorage.setItem('builderOptions', JSON.stringify(builderOptions));
        $('#attach-link').modal('hide');
    });
});

$('#builder-content').off('click', '.remove-slide').on('click', '.remove-slide', function(){
	if(confirm("Are you sure that you want to delete it?")){
        var tab = $(this).closest('li');
        if(tab.siblings('li').length > 1){
            var block = $(this).closest('.block-content');
            var slideId = $(this).data('slide');
            var slide = block.find('#'+slideId);
            tab.remove();
            slide.remove();
            var slideItem = slideId.substr( slideId.lastIndexOf('-')+1);
            removeWidgetItemFromStorage('b-slider', slideItem);
	}

	}

});

$('#builder-content').off('click', '.remove-block-content').on('click', '.remove-block-content', function(){
	var is_delete = confirm("Are you sure that you want to delete it?");
	if(is_delete){
		var nodes = Array.prototype.slice.call( document.getElementById('builder-content').children );
		var el = $(this).closest('.content-element');
		var position = $(nodes).index( el )
		el.fadeOut().remove();
		removeWidgetFromStorage(position);
	}
});

$('#builder-content').off('click', '.remove-block-item').on('click', '.remove-block-item', function(){
	var is_delete = confirm("Are you sure that you want to delete it?");
	if(is_delete){
		$(this).closest('.item').fadeOut().remove();
	}
	var blockItemId =$(this).data('block_id');
    removeWidgetItemFromStorage("b-offer-block", blockItemId);
});

$('#builder-content').off('click', '.add-new-offer').on('click', '.add-new-offer', function(){
	var container = $(this).closest('.b-offers-container');
	var containerID = container.attr('id');
	var itemID = rendomID();
	var newItem = newOfferItem(itemID);
	$(this).closest('.no-item').before(newItem);
    // console.log(itemID);
    elementStyleChangeObserver(itemID, containerID);
    buildCkEditor('editor-'+itemID, containerID, itemID);

    builderOptionsObj = builderOptions.find(obj => {
      return obj.id === parseInt(containerID);
    });
    var elIndex = builderOptions.indexOf(builderOptionsObj);
    var newItemObj = {'id': itemID, 'image'  : null, 'html'   : null, 'url' : null};
    // console.log(builderOptionsObj.items);
    builderOptionsObj.items.push(newItemObj);

});


// elementStyleChangeObserver('builder-content');

$('#builder-content').off('click', '.settings.settings-products-block').on('click', '.settings.settings-products-block', function(){
	var elementId = $(this).closest('.b-products-container').attr('id');

	$('.settings-options').html('');
	var categories = undefined;
	var names = undefined;

	builderOptionsObj = builderOptions.find(obj => {
	  return obj.id === parseInt(elementId);
	});
	// var elIndex = builderOptions.indexOf(builderOptionsObj);
	var category =  builderOptionsObj.category;
	console.log(category);
	$.ajax({
	    type: 'GET',
	    url: app.ajax_url+ '/admin/resource/resource-categories-names/product?category='+category,
	    dataType: 'JSON',
	    success: function(data){
	    	if(data.success == true){
	    		
	        	categories = data.categories;
	        	names = data.names;

				var options_html = productsOptions(builderOptionsObj, categories, names);
				$('.settings-options').append(options_html);
                $('#options-save').attr('data-element-id', elementId);
				
				$('select').select2();

				$('#block-settings-modal').modal('show');
	    	}
	    }
	});
});

$('body').off('click', '#options-save').on('click', '#options-save', function(e){
	e.preventDefault();
	if($(this).data('option') == 'b-products-block'){
		var category = $('#category').val() != undefined ? $('#category').val() : null;
		var products_count = $('#products_count').val() != undefined ? $('#products_count').val() : null;
		var products_ids = $('#products_names').val() != undefined ? $('#products_names').val() : null;
        var elementId = $('#options-save').attr('data-element-id');
        // console.log(elementId);
        builderOptionsObj = builderOptions.find(obj => {
          return obj.id === parseInt(elementId);
        });
        var elIndex = builderOptions.indexOf(builderOptionsObj);
        // console.log(builderOptionsObj.items);
        builderOptionsObj.category = category;
        builderOptionsObj.products_count = products_count;
        builderOptionsObj.products_ids = products_ids;
        builderOptions[elIndex] = builderOptionsObj;
        // console.log(builderOptions[elIndex]);
		localStorage.setItem('builderOptions', JSON.stringify(builderOptions));
		$('#block-settings-modal').modal('hide');

	}
});
var productsOptions = function(builderOptionsObj, categories, names){
	
	var products_count =  builderOptionsObj.products_count;
	var products_ids =  builderOptionsObj.products_ids;
	var html = '';
	html += '<div class="col-md-6">';
		html += '<h4>Show Products By Category</h4>';
		html += '<hr>';
		html += '<div class="form-group">';
			html += '<label for="categories" class="col-md-12 no-padding">Choose Category</label>';
			html += categories;
		html += '</div>';
		html += '<div class="form-group">';
			html += '<label for="products_count" class="col-md-12 no-padding">Products Count</label>';
			html += '<input type="text" name="count" id="products_count" class="form-control" value="'+products_count+'">';
			html += '<small class="helper">Type -1 for show all</small>';
		html += '</div>'
	html += '</div>';
	html += '<div class="col-md-6">';
		html += '<h4>Show Products Manualy</h4>';
		html += '<hr>';
		html += '<div class="form-group">';
			html += '<label for="products_names" class="col-md-12 no-padding">Choose Products</label>';
			html += '<select name="products_names[]" id="products_names" class="form-control" multiple="multiple">';
				if(names && names.length){
					for (var i = names.length - 1; i >= 0; i--) {
						
						
						if(products_ids.length > 0 && products_ids.indexOf(names[i]['id'].toString()) != -1){
							html += '<option value="'+names[i]['id']+'" selected="selected">'+names[i]['title']+'</option>';
						}else{
							html += '<option value="'+names[i]['id']+'">'+names[i]['title']+'</option>';
						}
					}
				}
			html += '</select>';
		html += '</div>';
	html += '</div>';
	html += '<div class="clearfix"></div>';
	return html;
}
// Helpers

function elementStyleChangeObserver(itemID, containerID ){
	// Select the node that will be observed for mutations
	var targetNode = document.getElementById(itemID);

	// Options for the observer (which mutations to observe)
	var config = { attributes: true, childList: true, subtree: true };

	// Callback function to execute when mutations are observed
	var callback = function(mutationsList) {
	    for(var mutation of mutationsList) {
	        // if (mutation.type == 'childList') {
	            // console.log('A child node has been added or removed.');
	        // }else
	        if (mutation.type == 'attributes') {
	            console.log('The ' + mutation.attributeName + ' attribute was modified.');
	        	// console.log(mutation.target.attributes.style.nodeValue);
	        	// console.log(mutation);
	        	var image_url = undefined;
	        	var style = targetNode.currentStyle || window.getComputedStyle(targetNode, false),
	        	image_url = style.backgroundImage.slice(4, -1).replace(/"/g, "");
	        	// console.log(image_url);
	        	// var is_valid_image = valid URL(image_url);
		        builderOptionsObj = builderOptions.find(obj => {
		          return obj.id === parseInt(containerID);
		        });
			    var elIndex = builderOptions.indexOf(builderOptionsObj);
			    //var	html = editor.getData();
			    if(builderOptionsObj !== undefined){
    		        if(builderOptionsObj.image !== undefined){
    			        builderOptionsObj.image = image_url;
    			        builderOptions[elIndex] = builderOptionsObj;
    		        	// console.log('parent')
    		        }else if(itemID != null){
    		        	// console.log(builderOptionsObj);
    		        	itemOptionsObj = builderOptionsObj.items.find(obj => {
    		        	  return obj.id === parseInt(itemID);
    		        	});
    		        	// console.log(itemOptionsObj);

    		        	if(itemOptionsObj.image !== undefined){
    			        	itemOptionsObj.image = image_url;
    				        builderOptions[elIndex] = builderOptionsObj;
    			        	// console.log('child')
    			        }else{
    			        	// console.log('smth wrong')
    			        }
    		        }
					localStorage.setItem('builderOptions', JSON.stringify(builderOptions));
			    }
	        }
	    }
	};

	// Create an observer instance linked to the callback function
	var observer = new MutationObserver(callback);

	// Start observing the target node for configured mutations
	observer.observe(targetNode, config);
	// Later, you can stop observing
	//observer.disconnect();
}

function validURL(str) {
  var pattern = new RegExp('^(https?:\/\/)?'+ // protocol
    '((([a-z\d]([a-z\d-]*[a-z\d])*)\.)+[a-z]{2,}|'+ // domain name
    '((\d{1,3}\.){3}\d{1,3}))'+ // OR ip (v4) address
    '(\:\d+)?(\/[-a-z\d%_.~+]*)*'+ // port and path
    '(\?[;&a-z\d%_.~+=-]*)?'+ // query string
    '(\#[-a-z\d_]*)?$','i'); // fragment locater
  if(!pattern.test(str)) {
    alert("Please enter a valid URL.");
    return false;
  } else {
    return true;
  }
}

var rendomID = function()
{
	var d = new Date();
	var n = d.getTime();
	return n;
}

var sliderHeight = function()
{
	var	width = $('#builder-content').width() - 32;
	var ratio = 2.1140;
	var	height = width/ratio;
	return height;
}

function newGradient() {
  var c1 = {
        r: Math.floor(255),
        g: Math.floor(35+Math.random()*220),
        b: Math.floor(Math.random()*55)
      };
      var c2 = {
        r: Math.floor(255),
        g: Math.floor(35+Math.random()*220),
        b: Math.floor(Math.random()*85)
      };
  c1.rgb = 'rgb('+c1.r+','+c1.g+','+c1.b+')';
  c2.rgb = 'rgb('+c2.r+','+c2.g+','+c2.b+')';
  return 'radial-gradient(at top left, '+c1.rgb+', '+c2.rgb+')';
}
function newGrayGradient(){
	// return "radial-gradient(at top left, "+grayColorGenerator()+", "+grayColorGenerator()+")";
	return "radial-gradient(at top left, #e8e8e8, #f9f9f9)";
}
function grayColorGenerator(){
	var value = Math.random() * 0xFF | 0;
	var grayscale = (value << 16) | (value << 8) | value;
	return '#' + grayscale.toString(16);
}

// -------- Local Storage
// localStorage.clear('builderOptions');
// if(!localStorage.getItem('builderOptions')){
// 	builderOptions = []; 
// }else{
// 	builderOptions = localStorage.getItem('builderOptions');
// 	builderOptions = JSON.parse(builderOptions)
// }
// console.log(builderOptions);
if(typeof builderOptions == 'undefined'){
	builderOptions = [];
}else{
	var container = $('#builder-content');
	for (var i = 0; i <= builderOptions.length - 1; i++) {
		// console.log(builderOptions[i]);
		// var id = rendomID();
		var element = builderOptions[i];
		switch(element.widget){
			case 'b-slider':
				var slideHeight = sliderHeight(element.id);
				var tabHeight = slideHeight+60;
			    var el = makeSlider(element.id, element.items[0].id, element.items[0].html, element.items[0].image );
			    $(el).css('min-height', tabHeight);
			    container.append('<li class="content-element" style="min-height:'+tabHeight+'">'+el+'</li>');
    		    buildCkEditor('editor-'+element.items[0].id, element.id, element.items[0].id);
        		elementStyleChangeObserver(element.items[0].id, element.id);

	        	if(element.items.length > 1){
	        		for (var j = 1; j <= element.items.length - 1; j++) {
	        			var item = element.items[j];
	        			var slide = newSlideItem(element.id, item.id, '', item.html, item.image);

        				var block = $(el);
        				var slideNumber = j+1;

        				$('#'+element.id).find('.add-slide').before('<li><a data-toggle="tab" href="#slide-'+element.id+'-'+item.id+'">Slide '+slideNumber+'</a><span class="remove-slide remove-icon fa fa-times-circle" data-slide="slide-'+element.id+'-'+item.id+'"></span></li>');
        				$('#'+element.id).find('.slides-content').append(slide);

        				buildCkEditor('editor-'+item.id, element.id, item.id);
        			    elementStyleChangeObserver(item.id, element.id);
        			}
	        	}
			    
				break;
			case 'b-image':
				var slideHeight = sliderHeight(element.id);
				var tabHeight = slideHeight+25;
			    var el = makeImage(element.id, element.image);
			    $(el).css('min-height', tabHeight);
			    container.append('<li class="content-element" style="min-height:'+tabHeight+'">'+el+'</li>');
	    		elementStyleChangeObserver(element.id, element.id);
				break;
			case 'b-text':
			    var el = makeTextBlock(element.id, element.html);
			    container.append('<li class="content-element">'+el+'</li>');

        		buildCkEditor('text-block-editor-'+element.id , element.id);
				break;
			case 'b-text-2images':
			    var el = makeTextBlock(element.id, element.html);
			    container.append('<li class="content-element">'+el+'</li>');

        		buildCkEditor('text-block-editor-'+element.id , element.id);
				break;
			case 'b-image-left':
				var slideHeight = sliderHeight(element.id);
				var tabHeight = slideHeight+25;
			    var img = makeImage(element.id, element.image, true);
			    $(img).css('min-height', tabHeight);
			  
			    var text = makeTextBlock(element.id, element.html, true);
			   

			    var el = "";
			    el += '<div class="controls">';
				el += '<i class="fa fa-trash fz-25 pull-right cursor-pointer remove-block-content"></i>';
				el += '<i class="handle-move fa fa-arrows-alt"></i>';
				el += '</div>';
				el += '<div class="row">';
			    el += img;
			    el += text;
			    el += '</div>';
			   
			    container.append('<li class="content-element" style="min-height:'+tabHeight+'">'+el+'</li>');
			    elementStyleChangeObserver(element.id, element.id);
			    buildCkEditor('text-block-editor-'+element.id , element.id);	
				break;

			case 'b-image-right':
				var slideHeight = sliderHeight(element.id);
				var tabHeight = slideHeight+25;
			    var img = makeImage(element.id, element.image, true);
			    $(img).css('min-height', tabHeight);
			  
			    var text = makeTextBlock(element.id, element.html, true);
			   

			    var el = "";
			    el += '<div class="controls">';
				el += '<i class="fa fa-trash fz-25 pull-right cursor-pointer remove-block-content"></i>';
				el += '<i class="handle-move fa fa-arrows-alt"></i>';
				el += '</div>';
				el += '<div class="row">';
			    el += text;
			    el += img;
			    el += '</div>';
			   
			    container.append('<li class="content-element" style="min-height:'+tabHeight+'">'+el+'</li>');
			    elementStyleChangeObserver(element.id, element.id);
			    buildCkEditor('text-block-editor-'+element.id , element.id);	
				break;
			case 'b-info-block':
			    var el = makeInfoBlock(element.id, element.html);
			    container.append('<li class="content-element">'+el+'</li>');

        		buildCkEditor('info-block-editor-'+element.id , element.id);
				break;
			case 'b-offer-block':
		    	var el = makeOffersBlock(element.id);
			    container.append('<li class="content-element">'+el+'</li>');
		    	if(element.items.length != 0){
		    		for (var j = 0; j <= element.items.length - 1; j++) {
		    			var item = element.items[j];
				    	var newItem = newOfferItem( item.id, item.html, item.image );
						$('#'+element.id).find('.no-item').before(newItem);
				        elementStyleChangeObserver(item.id, element.id);
				        buildCkEditor('editor-'+item.id, element.id, item.id);
		    		}
		    	}
				break;
			case 'b-products-block':
		    	var el = makeProductsBlock(element.id);
			    container.append('<li class="content-element">'+el+'</li>');
				buildCkEditor('editor-'+element.id, element.id);
				break;
		}
	}
}

var addToStorage = function(widget, id, position){
	switch(widget){
		case 'b-slider':
			var widgetOptions = {
				'widget' : widget,
				'id' : id,
				'items' : [],
			};
			break;
		case 'b-image':
			var widgetOptions = {
				'widget' : widget,
				'id' : id,
				'image'  : null,
				'html'   : null, 
			};
			break;
		case 'b-image-left':
			var widgetOptions = {
				'widget' : widget,
				'id' : id,
				'image'  : null,
				'html'   : null, 
			};
			break;
		case 'b-image-right':
			var widgetOptions = {
				'widget' : widget,
				'id' : id,
				'image'  : null,
				'html'   : null, 
			};
			break;	
		case 'b-text':
			var widgetOptions = {
				'widget' : widget,
				'id' : id,
				'html'   : null, 
			};
			break;
		case 'b-text-2images':
			var widgetOptions = {
				'widget' : widget,
				'id' : id,
				'html'   : null, 
			};
			break;
		case 'b-info-block':
			var widgetOptions = {
				'widget' : widget,
				'id' : id,
				'image'  : null,
				'html'   : null, 
			};
			break;
		case 'b-offer-block':
			var widgetOptions = {
				'widget' : widget,
				'id' : id,
				'items' : [],
			};
			break;
		case 'b-products-block':
			var widgetOptions = {
				'widget' : widget,
				'id' : id,
				'html' : '<h4>Վաճառքի առաջատարներ</h4>',
				'category' : null,
				'products_count' : -1,
				'products_ids' : [],
			};
			break;
	}
	builderOptions.splice(position, 0, widgetOptions);
	localStorage.setItem('builderOptions', JSON.stringify(builderOptions));
}

function changeWidgetOrder(position, newPosition){
	elProperties = builderOptions[position];
	// if(position == 0){ //remove form the beganing of array
	// 	console.log('remove form the beganing of array');
	// 	builderOptions.shift();
	// 	builderOptions.splice(newPosition, 0, elProperties);
	// }else if( position + 1 == builderOptions.length){ //remove form the end of array
	// 	console.log('//remove form the end of array');
	// 	builderOptions.pop();
	// 	builderOptions.splice(newPosition, 0, elProperties);
	// }else{ //remove form the array
	// 	if(newPosition !== 0){
	// 		builderOptions.splice(position, 1);
	// 		builderOptions.splice(newPosition, 0, elProperties);
	// 	}else{
	// 		builderOptions.splice(newPosition, 0, elProperties);			
	// 		builderOptions.splice(position+1, 1);
	// 	}
	// }
	if(newPosition !== 0){
		builderOptions.splice(position, 1);
		builderOptions.splice(newPosition, 0, elProperties);
	}else{
		builderOptions.splice(newPosition, 0, elProperties);			
		builderOptions.splice(position+1, 1);
	}
	localStorage.setItem('builderOptions', JSON.stringify(builderOptions));
}

function removeWidgetFromStorage(position){
	builderOptions.splice(position, 1);
	localStorage.setItem('builderOptions', JSON.stringify(builderOptions));

}

function removeWidgetItemFromStorage(widgetName, itemId) {

    var  widget = builderOptions.find(obj => {
        return obj.widget === widgetName;
    });
    for (var i = 0; i < widget.items.length; i++) {
        var obj = widget.items[i];
        if (obj.id == itemId) {
            widget.items.splice(i, 1);
        }
    }
    localStorage.setItem('builderOptions', JSON.stringify(builderOptions));

}
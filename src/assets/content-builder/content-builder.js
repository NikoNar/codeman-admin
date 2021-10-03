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

		switch(elID){
			case 'b-slider' :
				addToStorage(elID, id, position);
				builderOptionsObj = builderOptions.find(obj => {
				  return obj.id === parseInt(id);
				});

				var slideHeight = sliderHeight(id);
				var tabHeight = slideHeight+60;
			    $(el).html(makeSlider(id, builderOptionsObj, itemID)).css('min-height', tabHeight);
			    buildCkEditor('editor-'+itemID, id, itemID);

			    var elIndex = builderOptions.indexOf(builderOptionsObj);
			    var newItemObj = sliderItemObj(itemID);
			    builderOptionsObj.items.push(newItemObj);
	    		elementStyleChangeObserver(itemID, id);
				break;
			case 'b-image':
				addToStorage(elID, id, position);
				builderOptionsObj = builderOptions.find(obj => {
				  return obj.id === parseInt(id);
				});
				var slideHeight = sliderHeight(id);
				var tabHeight = slideHeight+25;
			    $(el).html(makeImage(id, builderOptionsObj)).css('min-height', tabHeight);

	    		elementStyleChangeObserver(id, id);
				break;
			case 'b-video':
				addToStorage(elID, id, position);
				builderOptionsObj = builderOptions.find(obj => {
				  return obj.id === parseInt(id);
				});
				var slideHeight = sliderHeight(id);
				var tabHeight = slideHeight+25;
			    $(el).html(makeVideo(id, builderOptionsObj)).css('min-height', tabHeight);

	    		// elementStyleChangeObserver(id, id);
				break;
			case 'b-text':
				addToStorage(elID, id, position);
				builderOptionsObj = builderOptions.find(obj => {
				  return obj.id === parseInt(id);
				});

			    $(el).html(makeTextBlock(id, builderOptionsObj));
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
				break;
			case 'b-products-block':
				addToStorage(elID, id, position);
				builderOptionsObj = builderOptions.find(obj => {
				  return obj.id === parseInt(id);
				});
				$(el).html(makeProductsBlock(id, builderOptionsObj));
				break;
			case 'b-user-viewd-product':
				addToStorage(elID, id, position);
				builderOptionsObj = builderOptions.find(obj => {
				  return obj.id === parseInt(id);
				});
				$(el).html(makeUserViewedProductsBlock(id, builderOptionsObj));
				break;
            case 'b-instagram':
                addToStorage(elID, id, position);
                builderOptionsObj = builderOptions.find(obj => {
                    return obj.id === parseInt(id);
                });
                $(el).html(makeInstagramBlock(id, builderOptionsObj));
                break;
			case 'b-empty-space':
				addToStorage(elID, id, position);
				builderOptionsObj = builderOptions.find(obj => {
				  return obj.id === parseInt(id);
				});
				$(el).html(makeEmptySpace(id, builderOptionsObj));
				break;
			case 'b-offer-block':
				addToStorage(elID, id, position);
				builderOptionsObj = builderOptions.find(obj => {
				  return obj.id === parseInt(id);
				});
				$(el).html(makeOffersBlock(id));
				break;
			case 'b-image-grid':
				addToStorage(elID, id, position);
				builderOptionsObj = builderOptions.find(obj => {
				  return obj.id === parseInt(id);
				});
				$(el).html(makeImageGridBlock(id, builderOptionsObj));
				break;
			case 'b-accardion':
				addToStorage(elID, id, position);
				builderOptionsObj = builderOptions.find(obj => {
				  return obj.id === parseInt(id);
				});

			    $(el).html(makeAccardion(id, builderOptionsObj, itemID)).css('min-height', '300px');
			    buildCkEditor('editor-'+itemID, id, itemID);

			    var elIndex = builderOptions.indexOf(builderOptionsObj);
			    var newItemObj = accardionItemObj(itemID);
			    builderOptionsObj.items.push(newItemObj);
				break;
		}
	}
	if($(source).attr('id') == 'builder-content'){
		changeWidgetOrder(parseInt(dragElementPosition), parseInt(position) );
	}

}).on('shadow', function(el, target){

});

function makeAccardion(id, widgetObj, itemID, itemObj = null){
	var slideHeight = sliderHeight();
	var tabHeight = slideHeight+39;
   	var html = '';
   	html += '<div class="block-content widget" data-type="b-accardion" data-id="'+id+'" id="'+id+'">';
	   html += '<div class="controls">';
			html += '<div class="col-md-6 widget-title">';
				html += '<p class="text-white">Accordion</p>';
			html += '</div>';
			html += '<div class="col-md-6 no-padding">';
			   	html += '<i class="fa fa-trash pull-right cursor-pointer remove-block-content widget-control-blue btn btn-xs btn-default"></i>';
			   	html += '<i class="handle-move fa fa-arrows-alt widget-control-blue btn btn-xs btn-default"></i>';
			html += '</div>';
		html += '</div>';
		html +='<div class="b-container module" style="padding-top:15px">';
		    html +='<div class="title">';
                html += `<div class="form-group col-md-6">\
        		        	<label>Container Size</label>\
        		        	<select class="form-control section_container_size section_input" name="container_size" >\
        		        		<option>Select Option</option>\
        		        		<option value="full"';
        		        		html += widgetObj.container_size == "full" ? 'selected' : '';
        		        		html +='>Full width</option>';
        		        		html +='<option value="xl"';
        		        		html += widgetObj.container_size == "xl" ? 'selected' : ''
        		        		html +='>Container XL</option>';
        		        		html +='<option value="big"';
        		        		html += widgetObj.container_size == "big" ? 'selected' : ''
        		        		html +='>Container Big</option>';
        		        		html +='<option value="mid"';
        		        		html += widgetObj.container_size == "mid" ? 'selected' : ''
        		        		html +='>Container Mid</option>\
        		        	</select>\
        		        </div>`;
		        html += '<div class="form-group col-md-6">\
				        	<label>Section Background Image</label>\
				        	<input type="color" class="form-control section_bg_color section_input" name="bg_color" value="'+widgetObj.bg_color+'">\
				        </div>';
		    html +='</div>';
		html +='</div>';
		html +='<div class="clearfix"></div>';
	   	html += '<div class="tab-content widget-accardion accordion-container" style="padding: 20px">\
					<div class="panel-group" id="accordion-'+id+'" role="tablist" aria-multiselectable="true">\
						'+newAccardionItem(id, itemID, itemObj)+'\
					</div>';
	   	html += '</div>';
	   	html += '<div class="accardion-add-new">';
	   			html += '<hr>';
	   			html += '<button type="button" class="btn btn-flat btn-success w-100 add-accardion-item"> Add new  <i class="fa fa-plus"></i></button>';
	   		html += '</div>';
	html += '</div>';
	return html;
}

function newAccardionItem(parentId, itemID, itemObj = null){
	let title = itemObj ? itemObj.tab_title : 'Accordion Title';
	let htmlContent = itemObj ? itemObj.html : 'Accordion Content';
	let counter = $('#'+parentId).find('.accordion-item').length + 1;
	return '<div class="panel panel-default accordion-item item" data-item-id="'+itemID+'">\
				<div class="panel-heading" role="tab" id="heading-'+itemID+'">\
					<h4 class="panel-title row">\
						<div class="col-md-8">\
							<a role="button" data-toggle="collapse" data-parent="#accordion-'+parentId+'" href="#collapse-'+itemID+'" aria-expanded="false" aria-controls="collapse-'+itemID+'" class="collapsed">\
								Item #'+counter+'\
							</a>\
						</div>\
						<div class="col-md-4">\
							<i class="fa fa-trash pull-right cursor-pointer remove-item"></i>\
						</div>\
					</h4>\
				</div>\
				<div id="collapse-'+itemID+'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-'+itemID+'" aria-expanded="false" style="height: 0px;">\
					<div class="panel-body">\
			     		<div class="form-group">\
				     		<label>Heading</label>\
				       		<input type="text" class="form-control section_input" name="tab_title" value="'+title+'" data-item-id="'+itemID+'" data-parent-id="'+parentId+'">\
				       	</div>\
				       	<div class="form-group">\
			       			<div class="s-content ckeditor" contenteditable="true" id="editor-'+itemID+'">\
			       		        '+htmlContent+'\
			       		    </div>\
		       		    </div>\
					</div>\
				</div>\
			</div>';
}

function makeSlider(id, widgetObj, itemID, htmlContent = undefined, image_url = undefined){
	var slideHeight = sliderHeight();
	var tabHeight = slideHeight+39;
   	var html = '';
   	html += '<div class="block-content widget" data-type="b-slider" data-id="'+id+'" id="'+id+'">';
	   	html += '<ul class="nav nav-tabs slides-tabs">';
	   		html += '<i class="fa fa-trash pull-right cursor-pointer remove-block-content widget-control btn btn-xs btn-primary"></i>';
	   		html += '<i class="fa fa-arrows-alt handle-move widget-control btn btn-xs btn-primary"></i>';
	   		html += '<i class="fa fa-sliders-h pull-right settings settings-slider-block widget-control btn btn-xs btn-primary"></i>';
	   		html += '<i class="fa fa-angle-down pull-right widget-control btn btn-xs btn-primary" ></i>';
	   		html += '<li class="active"><a data-toggle="tab" href="#slide-'+id+'-'+itemID+'">Slide 1</a><span class="remove-slide remove-icon fa fa-times-circle" data-slide="slide-'+id+'-'+itemID+'"></span></li>';
	   		html += '<li class="not-tab cursor-pointer add-slide"><a><i class="fa fa-plus-circle"></i></a></li>';
	   	html += '</ul>';
		html +='<div class="b-container module" style="padding-top:15px">';
		    html +='<div class="title">';
                html += '<div class="form-group col-md-4">\
        		        	<label>Container Size</label>\
        		        	<select class="form-control section_container_size section_input" name="container_size" >\
        		        		<option>Select Option</option>\
        		        		<option value="full"';
        		        		html += widgetObj.container_size == "full" ? 'selected' : '';
        		        		html +='>Full width</option>';
        		        		html +='<option value="xl"';
        		        		html += widgetObj.container_size == "xl" ? 'selected' : ''
        		        		html +='>Container XL</option>';
        		        		html +='<option value="big"';
        		        		html += widgetObj.container_size == "big" ? 'selected' : ''
        		        		html +='>Container Big</option>';
        		        		html +='<option value="mid"';
        		        		html += widgetObj.container_size == "mid" ? 'selected' : ''
        		        		html +='>Container Mid</option>\
        		        	</select>\
        		        </div>';
        		html += '<div class="form-group col-md-4">\
        		        	<label>Controls</label>\
        		        	<select class="form-control slider_controls section_input" name="controls" >\
        		        		<option>Select Option</option>\
        		        		<option value="arrows_bottom"';
        		        		html += widgetObj.controls == "arrows_bottom" ? 'selected' : '';
        		        		html +='>Arrows Bottom</option>';
        		        		html +='<option value="arrows_middle"';
        		        		html += widgetObj.controls == "arrows_middle" ? 'selected' : ''
        		        		html +='>Arrows Middle</option>\
        		        	</select>\
        		        </div>';
		        html += '<div class="form-group col-md-4">\
				        	<label>Section Background Image</label>\
				        	<input type="color" class="form-control section_bg_color section_input" name="bg_color" value="'+widgetObj.bg_color+'">\
				        </div>';
		    html +='</div>';
		html +='</div>';
		html +='<div class="clearfix"></div>';
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
				html += '<i class="fa fa-cogs fz-25 pull-right cursor-pointer slider-item-settings" data-slider_id="'+itemID+'"></i>';
				// html += '<i class="fa fa-sliders-h fz-25 pull-right cursor-pointer settings settings-slider"></i>';
			html += '</div>';
			html += '<div class="s-content ckeditor" contenteditable="true" id="editor-'+itemID+'">';
		        html += htmlContent;
		    html += '</div>';
		html += '</div>';
	html += '</div>';
	return html;
}

function makeImage(id, widgetObj){
	var html = "";
	var col6 = undefined;

	if(widgetObj.image == undefined){
		image = newGradient();
	}else{
		image = 'url('+widgetObj.image+')';
	}

	var slideHeight = sliderHeight();
	// var tabHeight = slideHeight+39;
   	html += '<div class="block-content widget" data-type="b-image" data-id="'+id+'">';
   		html += '<div class="controls">';
			html += '<div class="col-md-6 widget-title">';
				html += '<p class="text-white">Image</p>';
			html += '</div>';
			html += '<div class="col-md-6 no-padding">';
			   	html += '<i class="fa fa-trash pull-right cursor-pointer remove-block-content widget-control-blue btn btn-xs btn-default"></i>';
			   	html += '<i class="fa fa-arrows-alt handle-move widget-control-blue btn btn-xs btn-default"></i>';
				html += '<i class="fa fa-sliders-h pull-right cursor-pointer settings settings-image-block widget-control-blue btn btn-xs btn-default"></i>';
			html += '</div>';
		html += '</div>';
		html +='<div class="b-container module">';
		    html +='<div class="title">';
                html += '<div class="form-group col-md-6">\
        		        	<label>Container Size</label>\
        		        	<select class="form-control section_container_size section_input" name="container_size" >\
        		        		<option>Select Option</option>\
        		        		<option value="full"';
        		        		html += widgetObj.container_size == "full" ? 'selected' : '';
        		        		html +='>Full width</option>';
        		        		html +='<option value="xl"';
        		        		html += widgetObj.container_size == "xl" ? 'selected' : ''
        		        		html +='>Container XL</option>';
        		        		html +='<option value="big"';
        		        		html += widgetObj.container_size == "big" ? 'selected' : ''
        		        		html +='>Container Big</option>';
        		        		html +='<option value="mid"';
        		        		html += widgetObj.container_size == "mid" ? 'selected' : ''
        		        		html +='>Container Mid</option>\
        		        	</select>\
        		        </div>';
		        html += '<div class="form-group col-md-6">\
				        	<label>Section Background Image</label>\
				        	<input type="color" class="form-control section_bg_color section_input" name="bg_color" value="'+widgetObj.bg_color+'">\
				        </div>';
		    html +='</div>';
		html +='</div>';
		html +='<div class="clearfix"></div>';
		html += '<div id="image-'+id+'" class="b-image" >';
			html += '<div class="media-attach-bg" id="'+id+'" style="min-height:'+slideHeight+'px; background:'+image+';">';
				html += '<div class="choose-media-controls">';
					html += '<i class="fa fa-camera fz-25 cursor-pointer media-open"></i>';
				html += '</div>';
			html += '</div>';
		html += '</div>';
	html += '</div>';
	return html;
}

function makeVideo(id, widgetObj){
	var html = "";
	var col6 = undefined;

	if(widgetObj.image == undefined){
		image = newGradient();
	}else{
		image = 'url('+widgetObj.image+')';
	}

	var slideHeight = sliderHeight();
	// var tabHeight = slideHeight+39;
   	html += '<div class="block-content widget" data-type="b-image" data-id="'+id+'">';
   		html += '<div class="controls">';
			html += '<div class="col-md-6 widget-title">';
				html += '<p class="text-white">Video</p>';
			html += '</div>';
			html += '<div class="col-md-6 no-padding">';
			   	html += '<i class="fa fa-trash pull-right cursor-pointer remove-block-content widget-control-blue btn btn-xs btn-default"></i>';
			   	html += '<i class="handle-move fa fa-arrows-alt widget-control-blue btn btn-xs btn-default"></i>';
                html += '<i class="btn btn-xs btn-default widget-control-blue fa fa-link pull-right attach-link" data-block_id="'+id+'"></i>';
				// html += '<i class="fa fa-sliders-h pull-right cursor-pointer settings settings-image-block widget-control-blue btn btn-xs btn-default"></i>';
			html += '</div>';
		html += '</div>';
		html +='<div class="b-container module">';
		    html +='<div class="title">';
                html += '<div class="form-group col-md-6">\
        		        	<label>Container Size</label>\
        		        	<select class="form-control section_container_size section_input" name="container_size" >\
        		        		<option>Select Option</option>\
        		        		<option value="full"';
        		        		html += widgetObj.container_size == "full" ? 'selected' : '';
        		        		html +='>Full width</option>';
        		        		html +='<option value="xl"';
        		        		html += widgetObj.container_size == "xl" ? 'selected' : ''
        		        		html +='>Container XL</option>';
        		        		html +='<option value="big"';
        		        		html += widgetObj.container_size == "big" ? 'selected' : ''
        		        		html +='>Container Big</option>';
        		        		html +='<option value="mid"';
        		        		html += widgetObj.container_size == "mid" ? 'selected' : ''
        		        		html +='>Container Mid</option>\
        		        	</select>\
        		        </div>';
		        html += '<div class="form-group col-md-6">\
				        	<label>Section Background Image</label>\
				        	<input type="color" class="form-control section_bg_color section_input" name="bg_color" value="'+widgetObj.bg_color+'">\
				        </div>';
				html += '<div class="form-group col-md-12">\
		        	<label>Video URL</label>\
		        	<input type="text" class="form-control video_url section_input" name="video_url" value="'+widgetObj.video_url+'">\
		        </div>';
		    html +='</div>';
		html +='</div>';
		html +='<div class="clearfix"></div>';
		html += '<div id="video-'+id+'" class="b-video" >';
			html += '<div class="media-attach-bg" id="'+id+'" style="min-height:'+slideHeight+'px; background:'+widgetObj.bg_color+'">';
				html += '<iframe src="'+widgetObj.video_url+'" style="min-height:'+slideHeight+'px; width:100%" class="video-iframe"></iframe>';
			html += '</div>';
		html += '</div>';
	html += '</div>';
	return html;
}

function makeTextBlock(id, widgetObj, y = undefined, col6 = undefined){
	var open_div = "";
	var close_div = "";


	if(col6 != undefined) {
		open_div = '<div class="col-md-6">';
		close_div = '</div>';

	}
	if(!widgetObj.html){
		var htmlContent = '';
		htmlContent += '<h1>H1 - Title - 28px bold</h1>';
	    htmlContent += '<h2>H2 - Sub title - 20px bold</h2>';
	    htmlContent += '<h3>H3 - Sub title - 18px bold</h3>';
	    htmlContent += '<h4>H4 - Sub title - 16px bold</h4>';
	    htmlContent += '<h5>H5 - Text - 16px - regular</h5>';
	    htmlContent += '<h6>H6 - Smal text - 14px - regular</h6>';

	    htmlContent += '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod\
			tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,\
			quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo\
			consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse\
			cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non\
			proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>';
	}else{
		var htmlContent = widgetObj.html;
	}

	var html = "";
	html+= open_div;
	if(col6 == undefined) {
		html += '<div class="controls" >';
			html += '<div class="col-md-6 widget-title">';
				html += '<p class="text-white">Text Block</p>';
			html += '</div>';
			html += '<div class="col-md-6 no-padding">';
			   	html += '<i class="fa fa-trash pull-right cursor-pointer remove-block-content widget-control-blue btn btn-xs btn-default"></i>';
	   			html += '<i class="handle-move fa fa-arrows-alt widget-control-blue btn btn-xs btn-default"></i>';
			html += '</div>';

			// html += '<i class="fa fa-sliders-h fz-25 pull-right cursor-pointer settings settings-text-block"></i>';
		html += '</div>';
	}
    html +='<div class="heading widget" id="'+id+'">';
        html += '<div class="form-group col-md-6">\
		        	<label>Container Size</label>\
		        	<select class="form-control section_container_size section_input" name="container_size" >\
		        		<option>Select Option</option>\
		        		<option value="full"';
		        		html += widgetObj.container_size == "full" ? 'selected' : '';
		        		html +='>Full width</option>';
		        		html +='<option value="xl"';
		        		html += widgetObj.container_size == "xl" ? 'selected' : ''
		        		html +='>Container XL</option>';
		        		html +='<option value="big"';
		        		html += widgetObj.container_size == "big" ? 'selected' : ''
		        		html +='>Container Big</option>';
		        		html +='<option value="mid"';
		        		html += widgetObj.container_size == "mid" ? 'selected' : ''
		        		html +='>Container Mid</option>\
		        	</select>\
		        </div>';

        html += '<div class="form-group col-md-6">\
		        	<label>Section Background Image</label>\
		        	<input type="color" class="form-control section_bg_color section_input" name="bg_color" value="'+widgetObj.bg_color+'">\
		        </div>';
    html +='</div>';

	html += '<div class="b-container module rtf intro-text ckeditor" contenteditable="true" id="text-block-editor-'+id+'" style="background-color:'+widgetObj.bg_color+'">';
	    html += htmlContent;
	html += '</div>';
	html += close_div;
	return html;
}

//Not used anymore
function makeOffersBlock(id){
	// 	var html = "";
	// 	html += '<div class="b-offers-container" id="'+id+'">';
	// 		html += '<div class="controls">';
	// 		   	html += '<i class="fa fa-trash fz-25 pull-right cursor-pointer remove-block-content"></i>';
	// 		   	html += '<i class="handle-move fa fa-arrows-alt"></i>';
	// 			// html += '<i class="fa fa-sliders-h fz-25 pull-right cursor-pointer settings settings-info-block"></i>';
	// 		html += '</div>';
	// 		html += '<div class="module marketplace-grid grid-3 responsive vertical">';
	// 			html += '<div class="col-md-12 no-padding">';
	// 				html += '<div class="col-md-4 no-item">';
	// 					html += '<div class="tile left-txt cf">';
	// 						html += '<a href="javascript:void(0)">';
	// 							html += '<div class="add-new-offer">';
	// 								html += '<i class="fa fa-plus-circle fz-35"></i>';
	// 							html += '</div>';
	// 						html += '</a>';
	// 					html += '</div>';
	// 				html += '</div>';
	// 	        html += '</div>';
	// 		html += '</div>';
	// 		html += '<div class="clear-both"></div>';
	// 	html += '</div>';
	// 	return html;
}

//Not used anymore
function newOfferItem(itemID, htmlContent = undefined, image_url = undefined){
	// 	if(image_url == undefined){
	// 		image_url = newGrayGradient();
	// 	}else{
	// 		image_url = 'url('+image_url+')';
	// 	}
	// 	if(htmlContent == undefined){
	// 		htmlContent = '';
	// 		htmlContent += '<h3>Lorem Ipsum Dollar Sit Amet</h3>';
	// 		htmlContent += '<a href="#">';
	// 			htmlContent += '<span class="cta">Read More </span>';
	// 		htmlContent += '</a>';
	// 	}
	// 	var html = "";
	// 	html += '<div class="col-md-4 item media-attach-bg" style="background:'+image_url+'" id="'+itemID+'">';
	// 		html += '<div class="controls">';
	// 			html += '<i class="fa fa-camera fz-25 cursor-pointer media-open"></i>';
	// 				html += '<i class="fa fa-trash fz-25 pull-right cursor-pointer control-remove-icon remove-block-item" data-block_id="'+itemID+'"></i>';
	// 				html += '<i class="fa fa-cogs fz-25 pull-right cursor-pointer attach-link" data-block_id="'+itemID+'"></i>';
	// 				// html += '<i class="fa fa-sliders-h fz-25 pull-right cursor-pointer settings settings-offer-block-item"></i>';
	// 		html += '</div>';
	// 		html += '<div class="tile left-txt cf">';
	// 			html += '<div class="b-content ckeditor" contenteditable="true" id="editor-'+itemID+'" >';
	// 				html += htmlContent;
	// 			html += '</div>';
	// 		html += '</div>';
	// 	html += '</div>';
	// 	return html;
}

function makeImageGridBlock(id, widgetObj){
	var html = "";
	html += '<div class="b-grid-image-container widget" id="'+id+'" data-items_per_row="'+widgetObj.items_per_row+'">';
		html += '<div class="controls">';
				html += '<div class="col-md-6 widget-title">';
					html += '<p class="text-white">Images Grid</p>';
				html += '</div>';
				html += '<div class="col-md-6 no-padding">';
					html += '<i class="fa fa-trash pull-right cursor-pointer remove-block-content widget-control-blue btn btn-xs btn-default"></i>';
					html += '<i class="handle-move fa fa-arrows-alt widget-control-blue btn btn-xs btn-default"></i>';
				html += '</div>';

			// html += '<i class="fa fa-sliders-h fz-25 pull-right cursor-pointer settings settings-info-block"></i>';
		html += '</div>';
		html +='<div class="b-container module" style="padding-top:15px">';
		html +='<div class="title">';
			html += '<div class="form-group col-md-4">\
						<label>Container Size</label>\
						<select class="form-control section_container_size section_input" name="container_size" >\
							<option>Select Option</option>\
							<option value="full"';
							html += widgetObj.container_size == "full" ? 'selected' : '';
							html +='>Full width</option>';
							html +='<option value="xl"';
							html += widgetObj.container_size == "xl" ? 'selected' : ''
							html +='>Container XL</option>';
							html +='<option value="big"';
							html += widgetObj.container_size == "big" ? 'selected' : ''
							html +='>Container Big</option>';
							html +='<option value="mid"';
							html += widgetObj.container_size == "mid" ? 'selected' : ''
							html +='>Container Mid</option>\
						</select>\
					</div>';
			// html += '<div class="form-group col-md-4">\
			// 			<label>Section Background Image</label>\
			// 			<input type="color" class="form-control section_bg_color section_input" name="bg_color" value="'+widgetObj.bg_color+'">\
			// 		</div>';
			html += '<div class="form-group col-md-4">\
						<label>Items per row</label>\
						<select class="form-control section_container_size section_input" name="items_per_row" >\
							<option value="1"';
							html += widgetObj.items_per_row == "1" ? 'selected' : '';
							html +='>1</option>';
							html +='<option value="2"';
							html += widgetObj.items_per_row == "2" ? 'selected' : ''
							html +='>2</option>';
							html +='<option value="3"';
							html += widgetObj.items_per_row == "3" ? 'selected' : ''
							html +='>3</option>';
							html +='<option value="4"';
							html += widgetObj.items_per_row == "4" ? 'selected' : ''
							html +='>4</option>';
							html +='<option value="6"';
							html += widgetObj.items_per_row == "6" ? 'selected' : ''
							html +='>6</option>\
						</select>\
					</div>';
		html +='</div>';
	html +='</div>';
	html +='<div class="clearfix"></div>';
		html += '<div class="module marketplace-grid grid-3 responsive vertical">';
			html += '<div class="col-md-12 no-padding">';
				html += '<div class="item no-item  col-md-'+12/widgetObj.items_per_row+'">';
					html += '<div class="tile left-txt cf">';
						html += '<a href="javascript:void(0)">';
							html += '<div class="add-new-grid-image-item">';
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

function newImageGridItem(itemID, itemObj, widgetID){
	let itemsPerRow = $('#'+widgetID).attr('data-items_per_row');
	console.log(itemsPerRow);
	if(itemObj.image == undefined || itemObj.image == null){
		image_url = newGrayGradient();
	}else{
		image_url = 'url('+itemObj.image+')';
	}
	html = "";
	html += '<div class="item media-attach-bg col-md-'+(12/parseInt(itemsPerRow))+'" style="background:'+image_url+'" id="'+itemID+'">';
		html += '<div class="controls">';
			html += '<i class="fa fa-camera fz-25 cursor-pointer media-open"></i>';
				html += '<i class="fa fa-trash fz-25 pull-right cursor-pointer control-remove-icon remove-block-item" data-block_id="'+itemID+'"></i>';
				html += '<i class="fa fa-link fz-25 pull-right cursor-pointer attach-link" data-grid_image_id="'+itemID+'"></i>';
				// html += '<i class="fa fa-sliders-h fz-25 pull-right cursor-pointer settings settings-offer-block-item"></i>';
		html += '</div>';
	html += '</div>';
	return html;
}

function makeProductsBlock(id, widgetObj){
	var html = '';
	html += '<div class="b-products-container widget" id="'+id+'">';
		html += '<div class="controls">';
			html += '<div class="col-md-6 widget-title">';
				html += '<p class="text-white">Products Slider (Selected '+widgetObj.products_ids.length+' items)</p>';
			html += '</div>';
			html += '<div class="col-md-6 no-padding">';
			   	html += '<i class="fa fa-trash pull-right cursor-pointer remove-block-content widget-control-blue btn btn-xs btn-default"></i>';
			   	html += '<i class="handle-move fa fa-arrows-alt widget-control-blue btn btn-xs btn-default"></i>';
				html += '<i class="fa fa-sliders-h pull-right cursor-pointer settings settings-products-block widget-control-blue btn btn-xs btn-default" ></i>';
			html += '</div>';
		html += '</div>';
		html +='<div class="b-container module">';
		    html +='<div class="title">';
		        html += '<div class="form-group col-md-6">\
				        	<label>Section Title</label>\
				        	<input class="form-control section_title section_input" name="section_title" value="'+widgetObj.section_title+'" placeholder="Enter section title">\
				        </div>';
                html += '<div class="form-group col-md-6">\
        		        	<label>Container Size</label>\
        		        	<select class="form-control section_container_size section_input" name="container_size" >\
        		        		<option>Select Option</option>\
        		        		<option value="full"';
        		        		html += widgetObj.container_size == "full" ? 'selected' : '';
        		        		html +='>Full width</option>';
        		        		html +='<option value="xl"';
        		        		html += widgetObj.container_size == "xl" ? 'selected' : ''
        		        		html +='>Container XL</option>';
        		        		html +='<option value="big"';
        		        		html += widgetObj.container_size == "big" ? 'selected' : ''
        		        		html +='>Container Big</option>';
        		        		html +='<option value="mid"';
        		        		html += widgetObj.container_size == "mid" ? 'selected' : ''
        		        		html +='>Container Mid</option>\
        		        	</select>\
        		        </div>';

		        html += '<div class="form-group col-md-6">\
				        	<label>Section Background Image</label>\
				        	<input type="color" class="form-control section_bg_color section_input" name="bg_color" value="'+widgetObj.bg_color+'">\
				        </div>';
				html += '<div class="form-group col-md-6">\
				        	<label>Items per slide</label>\
				        	<input type="number" class="form-control section_bg_color section_input" name="items_count" value="'+widgetObj.items_count+'">\
				        </div>';
		    html +='</div>';
		html +='</div>';
		html +='<div class="clearfix"></div>';
		html +='<div class="b-container module product-listing product-spotlight">';
			for($i = 0; $i <= 3; $i++){
				html +='<div class="product cf col-md-3">';
			    	html +='<div class="img-wrap">';
			            html +='<img src="'+app.ajax_url+'/admin-panel/images/no-image.jpg" height="200px">';
			    	html +='</div>';
			    	html +='<div class="b-content cf">';
				        html +='<a class="price href="javascript:void(0)">';
				        	html +='Product Name';
				    	html +='</a>';
				    	html +='<p class="price">';
				    	    html +='0 000 ₽';
				    	html +='</p>';
					html +='</div>';
			    html +='</div>';
		    }
		html +='</div>';
	html +='</div>';
	return html;
}

function makeUserViewedProductsBlock(id, widgetObj){
		var html = '';
		html += '<div class="b-products-container widget" id="'+id+'">';
			html += '<div class="controls">';
				html += '<div class="col-md-6 widget-title">';
					html += '<p class="text-white">User Viewed products</p>';
				html += '</div>';
				html += '<div class="col-md-6 no-padding">';
				   	html += '<i class="fa fa-trash pull-right cursor-pointer remove-block-content widget-control-blue btn btn-xs btn-default"></i>';
				   	html += '<i class="handle-move fa fa-arrows-alt widget-control-blue btn btn-xs btn-default"></i>';
				html += '</div>';
			html += '</div>';
			html +='<div class="b-container module">';
			    html +='<div class="title">';
			        html += '<div class="form-group col-md-6">\
					        	<label>Section Title</label>\
					        	<input class="form-control section_title section_input" name="section_title" value="'+widgetObj.section_title+'" placeholder="Enter section title">\
					        </div>';
	                html += '<div class="form-group col-md-6">\
	        		        	<label>Container Size</label>\
	        		        	<select class="form-control section_container_size section_input" name="container_size" >\
	        		        		<option>Select Option</option>\
	        		        		<option value="full"';
	        		        		html += widgetObj.container_size == "full" ? 'selected' : '';
	        		        		html +='>Full width</option>';
	        		        		html +='<option value="xl"';
	        		        		html += widgetObj.container_size == "xl" ? 'selected' : ''
	        		        		html +='>Container XL</option>';
	        		        		html +='<option value="big"';
	        		        		html += widgetObj.container_size == "big" ? 'selected' : ''
	        		        		html +='>Container Big</option>';
	        		        		html +='<option value="mid"';
	        		        		html += widgetObj.container_size == "mid" ? 'selected' : ''
	        		        		html +='>Container Mid</option>\
	        		        	</select>\
	        		        </div>';

			        html += '<div class="form-group col-md-6">\
					        	<label>Section Background Image</label>\
					        	<input type="color" class="form-control section_bg_color section_input" name="bg_color" value="'+widgetObj.bg_color+'">\
					        </div>';
					html += '<div class="form-group col-md-6">\
					        	<label>Items per slide</label>\
					        	<input type="number" class="form-control section_bg_color section_input" name="items_count" value="'+widgetObj.items_count+'">\
					        </div>';
			    html +='</div>';
			html +='</div>';
			html +='<div class="clearfix"></div>';
			html +='<div class="b-container module product-listing product-spotlight">';
				for($i = 0; $i <= 3; $i++){
					html +='<div class="product cf col-md-3">';
				    	html +='<div class="img-wrap">';
				            html +='<img src="'+app.ajax_url+'/admin-panel/images/no-image.jpg" height="200px">';
				    	html +='</div>';
				    	html +='<div class="b-content cf">';
					        html +='<a class="price href="javascript:void(0)">';
					        	html +='Product Name';
					    	html +='</a>';
					    	html +='<p class="price">';
					    	    html +='0 000 ₽';
					    	html +='</p>';
						html +='</div>';
				    html +='</div>';
			    }
			html +='</div>';
		html +='</div>';
		return html;
}

function makeInstagramBlock(id, widgetObj){
    var html = '';
    html += '<div class="b-products-container widget" id="'+id+'">';
        html += '<div class="controls">';
            html += '<div class="col-md-6 widget-title">';
                html += '<p class="text-white">Instagram Widget</p>';
            html += '</div>';
            html += '<div class="col-md-6 no-padding">';
                html += '<i class="fa fa-trash pull-right cursor-pointer remove-block-content widget-control-blue btn btn-xs btn-default"></i>';
                html += '<i class="handle-move fa fa-arrows-alt widget-control-blue btn btn-xs btn-default"></i>';
            html += '</div>';
        html += '</div>';
    html +='<div class="b-container module">';
    html +='<div class="title">';
    html += '<div class="form-group col-md-6">\
                <label>Section Title</label>\
                <input class="form-control section_title section_input" name="section_title" value="'+widgetObj.section_title+'" placeholder="Enter section title">\
            </div>';
    html += '<div class="form-group col-md-6">\
                <label>Container Size</label>\
                <select class="form-control section_container_size section_input" name="container_size" >\
                    <option>Select Option</option>\
                    <option value="full"';
                        html += widgetObj.container_size == "full" ? 'selected' : '';
                        html +='>Full width</option>';
                        html +='<option value="xl"';
                        html += widgetObj.container_size == "xl" ? 'selected' : ''
                        html +='>Container XL</option>';
                        html +='<option value="big"';
                        html += widgetObj.container_size == "big" ? 'selected' : ''
                        html +='>Container Big</option>';
                        html +='<option value="mid"';
                        html += widgetObj.container_size == "mid" ? 'selected' : ''
                        html +='>Container Mid</option>\
                </select>\
            </div>';

            html += '<div class="form-group col-md-6">\
                        <label>Section Background Image</label>\
                        <input type="color" class="form-control section_bg_color section_input" name="bg_color" value="'+widgetObj.bg_color+'">\
                    </div>';
            html += '<div class="form-group col-md-6">\
                        <label>Items per slide</label>\
                        <input type="number" class="form-control section_bg_color section_input" name="items_count" value="'+widgetObj.items_count+'">\
                    </div>';
        html +='</div>';
    html +='</div>';
    html +='<div class="clearfix"></div>';
    html +='<div class="b-container module product-listing product-spotlight">';
        for($i = 0; $i <= 3; $i++){
            html +='<div class="product cf col-md-3">';
            html +='<div class="img-wrap">';
            html +='<img src="'+app.ajax_url+'/admin-panel/images/no-image.jpg" height="200px">';
            html +='</div>';
            html +='</div>';
        }
    html +='</div>';
    html +='</div>';
    return html;
}

function makeEmptySpace(id, widgetObj){
	var html = '';
	html += '<div class="b-empty-space widget" id="'+id+'">';
		html += '<div class="controls">';
			html += '<div class="col-md-6 widget-title">';
				html += '<p class="text-white">Empty Space</p>';
			html += '</div>';
			html += '<div class="col-md-6 no-padding">';
			   	html += '<i class="fa fa-trash pull-right cursor-pointer remove-block-content widget-control-blue btn btn-xs btn-default"></i>';
			   	html += '<i class="handle-move fa fa-arrows-alt widget-control-blue btn btn-xs btn-default"></i>';
			html += '</div>';
		html += '</div>';
		html +='<div class="b-container module">';
		    html +='<div class="title">';
		        html += '<div class="form-group col-md-6">\
				        	<label>Gutter (px/vh/rem)</label>\
				        	<input type="text" class="form-control section_input" name="gutter" value="'+widgetObj.gutter+'">\
				        </div>';
		        html += '<div class="form-group col-md-6">\
				        	<label>Section Background Color</label>\
				        	<input type="color" class="form-control section_bg_color section_input" name="bg_color" value="'+widgetObj.bg_color+'">\
				        </div>';
		    html +='</div>';
		html += '</div>';
	html += '</div>';

	return html;
}

function buildCkEditor(editorid, containerID, itemID = null){
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

$('#builder-content').off('change input', '.section_input').on('change input', '.section_input', function(e){
	e.preventDefault();
 	let widgetID = $(this).closest('.widget').attr('id') ?? $(this).closest('.widget').data('id');
 	let widgetItemID = $(this).data('item-id') ?? null;
 	let inputName = $(this).attr('name');
 	let inputValue = e.target.value;

 	switch(inputName){
 		case 'bg_color':
 			$('#text-block-editor-'+widgetID).css('background-color', inputValue);
 			break;
 		case 'video_url':
 			$(this).closest('.widget').find('.video-iframe').attr('src', inputValue);
 			break;
 		case 'items_per_row':
 			$('#'+widgetID).attr('data-items_per_row', inputValue);
 			let itmes = $('#'+widgetID).find('.item');
 			$.each($(itmes), function(k, el){
 				$(el).removeClass (function (index, css) {
 				   return (css.match (/(^|\s)col-\S+/g) || []).join(' ');
 				});
 				$(el).addClass('col-md-'+ (12/parseInt(inputValue)));
 				$(el).css('height', $(el).innerWidth());
 			});
 			break;
 	}
 	updateWidgetData(widgetID, inputName, inputValue, widgetItemID);
});

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
	var newItemObj = sliderItemObj(itemID);
	// console.log(builderOptionsObj.items);
	builderOptionsObj.items.push(newItemObj);

	buildCkEditor('editor-'+itemID, id, itemID);
    elementStyleChangeObserver(itemID, id);
});

$('#builder-content').off('click', '.add-accardion-item').on('click', '.add-accardion-item', function(){
	var block = $(this).closest('.block-content.widget');
	var id = block.data('id');
	var itemID = rendomID();

	var	slide = newAccardionItem(id, itemID)
	block.find('.accordion-container').append(slide);

	builderOptionsObj = builderOptions.find(obj => {
	  return obj.id === parseInt(id);
	});
	// console.log('builderOptionsObj', builderOptionsObj);
	var elIndex = builderOptions.indexOf(builderOptionsObj);
	var newItemObj = accardionItemObj(itemID);
	// console.log(builderOptionsObj.items);
	builderOptionsObj.items.push(newItemObj);

	buildCkEditor('editor-'+itemID, id, itemID);
    // elementStyleChangeObserver(itemID, id);
});

$('#builder-content').off('click', '.attach-link').on('click', '.attach-link', function(e) {
    e.preventDefault();

    var currentSlideId = $(this).data('slider_id');
    var currentBlockId = $(this).data('block_id');
    var currentGridImageId = $(this).data('grid_image_id');
    var currentItemID = "";
    var itemType = "";

    if (currentSlideId != undefined) {currentItemID = currentSlideId; itemType = "slide"; }
    if (currentBlockId != undefined) {currentItemID = currentBlockId; itemType = "block"; }
    if (currentGridImageId != undefined) {currentItemID = currentGridImageId; itemType = "grid-image"; }

    if(itemType == 'slide'){
        builderOptionsObjId = $('[data-type="b-slider"]').attr('id');
        var builderOptionsObj = builderOptions.find(obj => {
            return obj.id == builderOptionsObjId;
        });
    }
    if(itemType == 'block') {
        builderOptionsObjId = currentBlockId;
        var builderOptionsObj = builderOptions.find(obj => {
            return obj.id == builderOptionsObjId;
        });
    }
    if(itemType == 'grid-image') {
        builderOptionsObjId = $(this).closest('.b-grid-image-container').attr('id');
        var builderOptionsObj = builderOptions.find(obj => {
            return obj.id == builderOptionsObjId;
        });
    }
    if(itemType != 'block'){
        var currentItem = (builderOptionsObj.items.find(obj=>{
            return obj.id == currentItemID
        }));
    }else{
        currentItem = builderOptionsObj;
    }

    currentItem.url ? $('.slide_link').val(currentItem.url): $('.slide_link').val('');
    currentItem.button_name ? $('.slide_name').val(currentItem.button_name): $('.slide_name').val('');

    $('#attach-link').modal('show');

    $(document).keypress(function(e) {
        if(e.which == 13) {
        	$('.save_link').click();
        }
    });


    $('body').off('click', '.save_link').on('click', '.save_link', function(e){
    	e.preventDefault();
    	let modal = $(this).closest('.modal');
    	let button_name = modal.find('.slide_name').val() != '' ? modal.find('.slide_name').val() : null;
    	var link = modal.find('.slide_link').val();

    	if(link == ''){
    		alert('URL is empty');
    		return;
		}
		if(!link.match(/^((https?|ftp|smtp):\/\/)?(www.)?[a-z0-9]+(\.[a-z]{2,}){1,3}(#?\/?[a-zA-Z0-9#]+)*\/?(\?[a-zA-Z0-9-_]+=[a-zA-Z0-9-%]+&?)?$/)){
    		alert('Invalid URL');
    		return;
		}

		currentItem.url = link;
		currentItem.button_name = button_name;
        localStorage.setItem('builderOptions', JSON.stringify(builderOptions));
        $('#attach-link').modal('hide');
    });
});

$('#builder-content').off('click', '.remove-slide').on('click', '.remove-slide', function(){

	if(confirm("Are you sure that you want to delete it?")){
        var tab = $(this).closest('li');
        if(tab.siblings('li').length > 1){
            var widget = $(this).closest('.widget');
            var widgetID = widget.attr('data-id');

            var slideId = $(this).data('slide');
            var slide = widget.find('#'+slideId);
            tab.remove();
            slide.remove();
            var slideItem = slideId.substr( slideId.lastIndexOf('-')+1);
            removeWidgetItemFromStorage(widgetID, slideItem);
		}
	}
});

$('#builder-content').off('click', '.remove-item').on('click', '.remove-item', function(){

	if(confirm("Are you sure that you want to delete it?")){
        let item = $(this).closest('.item');
        let itemID = item.attr('data-item-id');

        let widget = item.closest('.widget');
        let widgetID = widget.attr('data-id');

        item.remove();

        removeWidgetItemFromStorage(widgetID, itemID);

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

    elementStyleChangeObserver(itemID, containerID);
    buildCkEditor('editor-'+itemID, containerID, itemID);

    builderOptionsObj = builderOptions.find(obj => {
      return obj.id === parseInt(containerID);
    });
    var elIndex = builderOptions.indexOf(builderOptionsObj);
    var newItemObj = {'id': itemID, 'image'  : null, 'html'   : null, 'url' : null};

    builderOptionsObj.items.push(newItemObj);
});

$('#builder-content').off('click', '.add-new-grid-image-item').on('click', '.add-new-grid-image-item', function(){
	var container = $(this).closest('.b-grid-image-container');
	var containerID = container.attr('id');
	var itemID = rendomID();

    builderOptionsObj = builderOptions.find(obj => {
      return obj.id === parseInt(containerID);
    });
    // var elIndex = builderOptions.indexOf(builderOptionsObj);
    var newItemObj = {
    	'id': itemID,
    	'image'  : null,
    	'url' : null,
    	'button_name': null,
    };
    builderOptionsObj.items.push(newItemObj);

    var newItem = newImageGridItem(itemID, newItemObj, containerID);
	$(this).closest('.no-item').before(newItem);

	$.each($(this).closest('.widget').find('.item'), function(k, el){
		$(el).css('height', $(el).innerWidth());
	});

	elementStyleChangeObserver(itemID, containerID);
});

$('#builder-content').off('click', '.settings.settings-products-block').on('click', '.settings.settings-products-block', function(){
	var elementId = $(this).closest('.b-products-container').attr('id');

	$('.settings-options').html('');
	var categories = undefined;
	var names = undefined;

	builderOptionsObj = builderOptions.find(obj => {
	  return obj.id === parseInt(elementId);
	});

	$(this).attr('disabled', true);

	$.ajax({
	    type: 'POST',
	    url: app.ajax_url+ '/admin/variations/load_modal/0',
	    dataType: 'JSON',
	    data: {'_token' : $('meta[name="csrf-token"]').attr('content')},
	    success: function(data){
	    	$('body').append(data.html);
	        $('body').find('#variations_modal').modal('show');
	        $('body').find('#variations_modal select').select2();
	        $('body').find('#variations_modal .datepicker input').datepicker({format: 'YYYY-MM-DD'});
	        setTimeout(function(){
	        	modal_filter('builder-widget', elementId, builderOptionsObj);
	        },1500);
	        $(this).removeAttr('disabled');
	    }
	});
});

$('#builder-content').off('click', '.settings.settings-image-block').on('click', '.settings.settings-image-block', function(){
	var elementId = $(this).closest('.block-content').data('id');

	$('.settings-options').html('');
	var categories = undefined;
	var names = undefined;


	builderOptionsObj = builderOptions.find(obj => {
	  return obj.id === parseInt(elementId);
	});
	var options_html = imageOptions(builderOptionsObj);

	$('.settings-options').append(options_html);
    $('#options-save').attr('data-element-id', elementId);

	$('#block-settings-modal').find('#options-save').attr('data-option', 'b-image');
	$('#block-settings-modal').modal('show');
});

$('#builder-content').off('click', '.settings.settings-slider-block').on('click', '.settings.settings-slider-block', function(){
	var elementId = $(this).closest('.block-content').data('id');

	$('.settings-options').html('');
	var categories = undefined;
	var names = undefined;


	builderOptionsObj = builderOptions.find(obj => {
	  return obj.id === parseInt(elementId);
	});
	var options_html = sliderOptions(builderOptionsObj);

	$('.settings-options').append(options_html);
    $('#options-save').attr('data-element-id', elementId);

	$('#block-settings-modal').find('#options-save').attr('data-option', 'b-slider');
	$('#block-settings-modal').modal('show');
});

$('#builder-content').off('click', '.slider-item-settings').on('click', '.slider-item-settings', function(){
	var sliderID = $(this).closest('.block-content').data('id');
	var currentItemID = $(this).data('slider_id');

	$('.settings-options').html('');

	builderOptionsObj = builderOptions.find(obj => {
	  return obj.id === parseInt(sliderID);
	});

    var currentSlideOptionsObj = (builderOptionsObj.items.find(obj=>{
    	return obj.id == currentItemID
	}));

    console.log('current slide options', sliderID, currentItemID, currentSlideOptionsObj);

	var options_html = sliderItemOptions(builderOptionsObj, currentSlideOptionsObj);

	$('.settings-options').append(options_html);
    $('#options-save').attr('data-element-id', sliderID);
    $('#options-save').attr('data-element-item-id', currentItemID);

	$('#block-settings-modal').find('#options-save').attr('data-option', 'b-slider-item');
	$('#block-settings-modal').modal('show');
});

$('body').off('click', '#options-save').on('click', '#options-save', function(e){
	e.preventDefault();
	console.log('options-save data-option = ', $(this).attr('data-option'));
	switch($(this).attr('data-option')){
		case 'b-products-block':
			saveProductSettings(this);
			break;
		case 'b-image':
			saveImageSettings(this);
			break;
		case 'b-slider':
			saveSliderSettings(this);
			break;
		case 'b-slider-item':
			saveSliderItemSettings(this);
			break;
	}
	$('#block-settings-modal').modal('hide');
});

var saveProductSettings = function(btn){
	var category = $('#category').val() != undefined ? $('#category').val() : null;
	var products_count = $('#products_count').val() != undefined ? $('#products_count').val() : null;
	var products_ids = $('#products_names').val() != undefined ? $('#products_names').val() : null;
    var elementId = $('#options-save').attr('data-element-id');

    builderOptionsObj = builderOptions.find(obj => {
      return obj.id === parseInt(elementId);
    });
    var elIndex = builderOptions.indexOf(builderOptionsObj);

    builderOptionsObj.category = category;
    builderOptionsObj.products_count = products_count;
    builderOptionsObj.products_ids = products_ids;
    builderOptions[elIndex] = builderOptionsObj;

	localStorage.setItem('builderOptions', JSON.stringify(builderOptions));
}

var saveImageSettings = function(btn){
	var elementId = $('#options-save').attr('data-element-id');
	var formData = $('#block-settings-modal').find('form#widget-settings-form').serializeArray();

    builderOptionsObj = builderOptions.find(obj => {
      return obj.id === parseInt(elementId);
    });

   	var widgetID = builderOptions.indexOf(builderOptionsObj);
   	//set auto height 0, it will becobe 1 during switch case if was checked.
   	builderOptionsObj.height.auto = 0;
   	for (let input of formData) {
   		switch(input.name){
   			case 'position[xxl][x]':
   				builderOptionsObj.position.xxl.x = input.value;
   				break;
   			case 'position[xxl][y]':
   				builderOptionsObj.position.xxl.y = input.value;
   				break;
			case 'position[xl][x]':
   				builderOptionsObj.position.xl.x = input.value;
   				break;
   			case 'position[xl][y]':
   				builderOptionsObj.position.xl.y = input.value;
   				break;
			case 'position[lg][x]':
   				builderOptionsObj.position.lg.x = input.value;
   				break;
   			case 'position[lg][y]':
   				builderOptionsObj.position.lg.y = input.value;
   				break;
			case 'position[md][x]':
   				builderOptionsObj.position.md.x = input.value;
   				break;
   			case 'position[md][y]':
   				builderOptionsObj.position.md.y = input.value;
   				break;
			case 'position[sm][x]':
   				builderOptionsObj.position.sm.x = input.value;
   				break;
   			case 'position[sm][y]':
   				builderOptionsObj.position.sm.y = input.value;
   				break;
   			case 'cta_url':
   				builderOptionsObj.cta_url = input.value;
   				break;
   			case 'alt':
   				builderOptionsObj.alt = input.value;
   				break;
   			case 'height[auto]':
   				builderOptionsObj.height.auto = 1;
   				break;
   			case 'height[xxl]':
   				builderOptionsObj.height.xxl = input.value;
   				break;
   			case 'height[xl]':
   				builderOptionsObj.height.xl = input.value;
   				break;
   			case 'height[lg]':
   				builderOptionsObj.height.lg = input.value;
   				break;
   			case 'height[md]':
   				builderOptionsObj.height.md = input.value;
   				break;
   			case 'height[sm]':
   				builderOptionsObj.height.sm = input.value;
   				break;
   		}
   	}
   	localStorage.setItem('builderOptions', JSON.stringify(builderOptions));
}

var saveSliderSettings = function(btn){
	var elementId = $(btn).attr('data-element-id');
	var formData = $('#options-save').closest('#block-settings-modal').find('form#widget-settings-form').serializeArray();

    builderOptionsObj = builderOptions.find(obj => {
      return obj.id === parseInt(elementId);
    });

   	//set auto height 0, it will becobe 1 during switch case if was checked.
   	builderOptionsObj.height.auto = 0;
   	for (let input of formData) {
   		console.log(input.name, input.value);
   		switch(input.name){
   			case 'height[auto]':
   				builderOptionsObj.height.auto = 1;
   				break;
   			case 'height[xxl]':
   				builderOptionsObj.height.xxl = input.value;
   				break;
   			case 'height[xl]':
   				builderOptionsObj.height.xl = input.value;
   				break;
   			case 'height[lg]':
   				builderOptionsObj.height.lg = input.value;
   				break;
   			case 'height[md]':
   				builderOptionsObj.height.md = input.value;
   				break;
   			case 'height[sm]':
   				builderOptionsObj.height.sm = input.value;
   				break;
   		}
   	}

	// builderOptions[elIndex] = builderOptionsObj;
   	localStorage.setItem('builderOptions', JSON.stringify(builderOptions));
   	console.log('after save', builderOptions);
}

var saveSliderItemSettings = function(btn){
	var elementId = $('#options-save').attr('data-element-id');
	var itemId = $('#options-save').attr('data-element-item-id');
	var formData = $('#block-settings-modal').find('form#widget-settings-form').serializeArray();

    builderOptionsObj = builderOptions.find(obj => {
      return obj.id === parseInt(elementId);
    });

	var currentSlideOptionsObj = (builderOptionsObj.items.find(obj=>{
    	return obj.id == itemId
	}));

   	//set auto height 0, it will becobe 1 during switch case if was checked.
   	for (let input of formData) {
   		switch(input.name){
   			case 'position[xxl][x]':
   				currentSlideOptionsObj.position.xxl.x = input.value;
   				break;
   			case 'position[xxl][y]':
   				currentSlideOptionsObj.position.xxl.y = input.value;
   				break;
			case 'position[xl][x]':
   				currentSlideOptionsObj.position.xl.x = input.value;
   				break;
   			case 'position[xl][y]':
   				currentSlideOptionsObj.position.xl.y = input.value;
   				break;
			case 'position[lg][x]':
   				currentSlideOptionsObj.position.lg.x = input.value;
   				break;
   			case 'position[lg][y]':
   				currentSlideOptionsObj.position.lg.y = input.value;
   				break;
			case 'position[md][x]':
   				currentSlideOptionsObj.position.md.x = input.value;
   				break;
   			case 'position[md][y]':
   				currentSlideOptionsObj.position.md.y = input.value;
   				break;
			case 'position[sm][x]':
   				currentSlideOptionsObj.position.sm.x = input.value;
   				break;
   			case 'position[sm][y]':
   				currentSlideOptionsObj.position.sm.y = input.value;
   				break;
   			case 'content_class_name':
				   currentSlideOptionsObj.content_class_name = input.value;
				break;
   			case 'content_layout_class_name':
				   currentSlideOptionsObj.content_layout_class_name = input.value;
				break;
			case 'extra_link[text]':
				   currentSlideOptionsObj.extra_link.text = input.value;
				break;
			case 'extra_link[class_name]':
				   currentSlideOptionsObj.extra_link.class_name = input.value;
				break;
			case 'extra_link[text_color]':
				   currentSlideOptionsObj.extra_link.text_color = input.value;
				break;
			case 'extra_link[url]':
				   currentSlideOptionsObj.extra_link.url = input.value;
				break;
   		}
   	}
   	localStorage.setItem('builderOptions', JSON.stringify(builderOptions))
}

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

var imageOptions = function(builderOptionsObj){
	let html = '<div class="widget-settings">\
	    <div class="box-body">\
			<form id="widget-settings-form">';
		    	html += imageMediaPositions(builderOptionsObj);

		    	html +='<div class="col-md-6">\
		    		<div class="col-md-12">\
			    		<div class="form-group">\
			    			<label for="cta_url">CTA URL</label>\
			    			<input type="text" id="cta_url" class="form-control" name="cta_url" value="'+builderOptionsObj.cta_url+'">\
			    		</div>\
		    		</div>\
		    		<div class="col-md-12">\
		    			<div class="form-group">\
		    				<label for="alt">Image Alt</label>\
		    				<input type="text" id="alt" class="form-control" name="alt" value="'+builderOptionsObj.alt+'">\
		    			</div>\
		    		</div>\
		    	</div>\
		    	<div class="col-md-12 height-settings form-inline">\
		    		<div class="form-group">\
		    			<h5>Image Size</h5>\
		    			<label for="height-auto" style="margin-right: 10px">\
		    				<input type="checkbox" id="height-auto" class="" name="height[auto]" value="1"  ';
		    				html += builderOptionsObj.height.auto == 1 ? "checked" : "";
		    				html += '>';
		    				html += ' Auto Height\
		    			</label>\
		    		</div>\
		    		<div class="height-media-sized">\
			    		<div class="">\
				    		<div class="form-group">\
				    			<label for="height-xxl">\
				    				<input type="text" id="height-xxl" class="form-control" name="height[xxl]" value="'+builderOptionsObj.height.xxl+'">\
				    				<small class="text-muted">\
			    				    	Extra extra large (≥1400px)\
			    				    </small>\
				    			</label>\
				    		</div>\
				    	</div>\
	    	    		<div class="">\
	    		    		<div class="form-group">\
	    		    			<label for="height-xl" style="margin-right: 10px">\
	    		    				<input type="text" id="height-xl" class="form-control" name="height[xl]" value="'+builderOptionsObj.height.xl+'">\
				    				<small class="text-muted">\
			    				    	Extra large (≥1200px)\
			    				    </small>\
	    		    			</label>\
	    		    		</div>\
	    		    	</div>\
	    		    	<div class="">\
	    		    		<div class="form-group">\
	    		    			<label for="height-lg" style="margin-right: 10px">\
	    		    				<input type="text" id="height-lg" class="form-control" name="height[lg]" value="'+builderOptionsObj.height.lg+'">\
				    				<small class="text-muted">\
			    				    	Large (≥992px)\
			    				    </small>\
	    		    			</label>\
	    		    		</div>\
	    		    	</div>\
	    		    	<div class="">\
	    		    		<div class="form-group">\
	    		    			<label for="height-md" style="margin-right: 10px">\
	    		    				<input type="text" id="height-md" class="form-control" name="height[md]" value="'+builderOptionsObj.height.md+'">\
				    				<small class="text-muted">\
			    				    	Large (≥768px)\
			    				    </small>\
	    		    			</label>\
	    		    		</div>\
	    		    	</div>\
	    		    	<div class="">\
	    		    		<div class="form-group">\
	    		    			<label for="height-sm" style="margin-right: 10px">\
	    		    				<input type="text" id="height-sm" class="form-control" name="height[sm]" value="'+builderOptionsObj.height.sm+'">\
				    				<small class="text-muted">\
			    				    	Large (≥576px)\
			    				    </small>\
	    		    			</label>\
	    		    		</div>\
	    		    	</div>\
	    		    </div>\
		    	</div>\
		    	<div class="clearfix"></div>\
			</form>\
	    </div>\
	</div>';
	return html
}

var sliderOptions = function(builderOptionsObj){
	let html = '<div class="widget-settings">\
	    <div class="box-body">\
			<form id="widget-settings-form">\
		    	<div class="col-md-12 height-settings form-inline">\
		    		<div class="form-group">\
		    			<h5>Image Size</h5>\
		    			<label for="height-auto" style="margin-right: 10px">\
		    				<input type="checkbox" id="height-auto" class="" name="height[auto]" value="1"  ';
		    				html += builderOptionsObj.height.auto == 1 ? "checked" : "";
		    				html += '>';
		    				html += ' Auto Height\
		    			</label>\
		    		</div>\
		    		<div class="height-media-sized">\
			    		<div class="">\
				    		<div class="form-group">\
				    			<label for="height-xxl">\
				    				<input type="text" id="height-xxl" class="form-control" name="height[xxl]" value="'+builderOptionsObj.height.xxl+'">\
				    				<small class="text-muted">\
			    				    	Extra extra large (≥1400px)\
			    				    </small>\
				    			</label>\
				    		</div>\
				    	</div>\
	    	    		<div class="">\
	    		    		<div class="form-group">\
	    		    			<label for="height-xl" style="margin-right: 10px">\
	    		    				<input type="text" id="height-xl" class="form-control" name="height[xl]" value="'+builderOptionsObj.height.xl+'">\
				    				<small class="text-muted">\
			    				    	Extra large (≥1200px)\
			    				    </small>\
	    		    			</label>\
	    		    		</div>\
	    		    	</div>\
	    		    	<div class="">\
	    		    		<div class="form-group">\
	    		    			<label for="height-lg" style="margin-right: 10px">\
	    		    				<input type="text" id="height-lg" class="form-control" name="height[lg]" value="'+builderOptionsObj.height.lg+'">\
				    				<small class="text-muted">\
			    				    	Large (≥992px)\
			    				    </small>\
	    		    			</label>\
	    		    		</div>\
	    		    	</div>\
	    		    	<div class="">\
	    		    		<div class="form-group">\
	    		    			<label for="height-md" style="margin-right: 10px">\
	    		    				<input type="text" id="height-md" class="form-control" name="height[md]" value="'+builderOptionsObj.height.md+'">\
				    				<small class="text-muted">\
			    				    	Large (≥768px)\
			    				    </small>\
	    		    			</label>\
	    		    		</div>\
	    		    	</div>\
	    		    	<div class="">\
	    		    		<div class="form-group">\
	    		    			<label for="height-sm" style="margin-right: 10px">\
	    		    				<input type="text" id="height-sm" class="form-control" name="height[sm]" value="'+builderOptionsObj.height.sm+'">\
				    				<small class="text-muted">\
			    				    	Large (≥576px)\
			    				    </small>\
	    		    			</label>\
	    		    		</div>\
	    		    	</div>\
	    		    </div>\
		    	</div>\
		    	<div class="clearfix"></div>\
			</form>\
	    </div>\
	</div>';
	return html
}

var sliderItemOptions = function(builderOptionsObj, itemOptionsObj){
	let html = '<div class="widget-settings">\
	    <div class="box-body">\
			<form id="widget-settings-form">';
				html += imageMediaPositions(itemOptionsObj);

				html += '<div class="col-md-12 no-padding">\
					<div class="form-group col-md-4">\
						<h4>Content</h4>\
						<div class="form-group">\
							<label>Position</label>\
							<select class="form-control content_class_name section_input" name="content_class_name" >\
								<option>Select option</option>\
								<option value="content-left"';
								html += itemOptionsObj.content_class_name == "content-left" ? 'selected' : '';
								html +='>Left</option>';
								html +='<option value="content-center"';
								html += itemOptionsObj.content_class_name == "content-center" ? 'selected' : ''
								html +='>Center</option>';
								html +='<option value="content-right"';
								html += itemOptionsObj.content_class_name == "content-right" ? 'selected' : ''
								html +='>Right</option>';
							html +='</select>\
						</div>\
						<div class="form-group">\
							<label>Layout</label>\
							<select class="form-control content_class_name section_input" name="content_layout_class_name" >\
								<option>Select option</option>\
								<option value="new-collection"';
								html += itemOptionsObj.content_layout_class_name == "new-collection" ? 'selected' : '';
								html +='>Layout 1</option>';
								html +='<option value="sale-collection"';
								html += itemOptionsObj.content_layout_class_name == "sale-collection" ? 'selected' : ''
								html +='>Layout 2</option>';
								html +='<option value="outlet-collection"';
								html += itemOptionsObj.content_layout_class_name == "outlet-collection" ? 'selected' : ''
								html +='>Layout 3</option>';
								html +='<option value="no-layout"';
								html += itemOptionsObj.content_layout_class_name == "no-layout" ? 'selected' : ''
								html +='>No Layout</option>';
							html +='</select>\
						</div>\
					</div>\
					<div class="form-group col-md-8">\
						<h4>Bottom Link</h4>\
						<div class="col-md-6 no-padding">\
							<div class="form-group">\
								<label>Label</label>\
								<input type="text" class="form-control" name="extra_link[text]" value="'+itemOptionsObj.extra_link.text+'">\
							</div>\
							<div class="form-group">\
								<label>CTA URL</label>\
								<input type="text" class="form-control" name="extra_link[url]" value="'+itemOptionsObj.extra_link.url+'">\
							</div>\
						</div>\
						<div class="col-md-6">\
							<div class="form-group">\
								<label>Label Color</label>\
								<input type="color" class="form-control" name="extra_link[text_color]" value="'+itemOptionsObj.extra_link.text_color+'">\
							</div>\
							<div class="form-group">\
								<label>Position</label>\
								<select class="form-control content_class_name section_input" name="extra_link[class_name]" >\
									<option>Select option</option>\
									<option value="left"';
									html += itemOptionsObj.extra_link.class_name == "left" ? 'selected' : '';
									html +='>Left</option>';
									html +='<option value="center"';
									html += itemOptionsObj.extra_link.class_name == "center" ? 'selected' : ''
									html +='>Center</option>';
									html +='<option value="right"';
									html += itemOptionsObj.extra_link.class_name == "right" ? 'selected' : ''
									html +='>Right</option>';
								html +='</select>\
							</div>\
						</div>\
					</div>\
				</div>\
			</form>\
	    </div>\
	</div>';
	return html
}

var imageMediaPositions = function(itemOptionsObj){
	html = '<div class="col-md-12">\
				<h4>Image Position</h4>\
			</div>\
	    	<div class="col-md-4" style="border-bottom: 1px solid #eee;margin-bottom: 15px;">\
		    	<label for="position"><small class="text-muted">Extra extra large (≥1400px)</small></label>\
		    	<div class="form-group">\
		    		<h5>Horizontal</h5>\
		    		<label style="margin-right: 10px">\
		    			<input type="radio"  class="" name="position[xxl][x]" value="left" ';
		    			html += itemOptionsObj.position.xxl.x == "left" ? "checked" : "";
		    			html += '>';
		    			html += ' Left\
		    		</label>\
		    		<label  style="margin-right: 10px">\
		    			<input type="radio"  class="" name="position[xxl][x]" value="center" ';
		    			html += itemOptionsObj.position.xxl.x == "center" ? "checked" : "";
		    			html += '>';
		    			html += ' Center\
		    		</label>\
		    		<label >\
		    			<input type="radio"  class="" name="position[xxl][x]" value="right" ';
		    			html += itemOptionsObj.position.xxl.x == "right" ? "checked" : "";
		    			html += '>';
		    			html += ' Right\
		    		</label>\
		    	</div>\
		    	<div class="form-group">\
		    		<h5>Vertical</h5>\
		    		<label style="margin-right: 10px">\
		    			<input type="radio"  class="" name="position[xxl][y]" value="top"  ';
		    			html += itemOptionsObj.position.xxl.y == "top" ? "checked" : "";
		    			html += '>';
		    			html += ' Top\
		    		</label>\
		    		<label style="margin-right: 10px">\
		    			<input type="radio"  class="" name="position[xxl][y]" value="center" ';
		    			html += itemOptionsObj.position.xxl.y == "center" ? "checked" : "";
		    			html += '>';
		    			html += ' Center\
		    		</label>\
		    		<label >\
		    			<input type="radio"  class="" name="position[xxl][y]" value="bottom" ';
		    			html += itemOptionsObj.position.xxl.y == "bottom" ? "checked" : "";
		    			html += '>';
		    			html += 'Bottom\
		    		</label>\
		    	</div>\
	    	</div>\
	    	<div class="col-md-4" style="border-bottom: 1px solid #eee;margin-bottom: 15px;">\
		    	<label for="position"><small class="text-muted">Extra large (≥1200px)</small></label>\
		    	<div class="form-group">\
		    		<h5>Horizontal</h5>\
		    		<label style="margin-right: 10px">\
		    			<input type="radio"  class="" name="position[xl][x]" value="left" ';
		    			html += itemOptionsObj.position.xl.x == "left" ? "checked" : "";
		    			html += '>';
		    			html += ' Left\
		    		</label>\
		    		<label style="margin-right: 10px">\
		    			<input type="radio"  class="" name="position[xl][x]" value="center" ';
		    			html += itemOptionsObj.position.xl.x == "center" ? "checked" : "";
		    			html += '>';
		    			html += ' Center\
		    		</label>\
		    		<label >\
		    			<input type="radio" class="" name="position[xl][x]" value="right" ';
		    			html += itemOptionsObj.position.xl.x == "right" ? "checked" : "";
		    			html += '>';
		    			html += ' Right\
		    		</label>\
		    	</div>\
		    	<div class="form-group">\
		    		<h5>Vertical</h5>\
		    		<label  style="margin-right: 10px">\
		    			<input type="radio" class="" name="position[xl][y]" value="top"  ';
		    			html += itemOptionsObj.position.xl.y == "top" ? "checked" : "";
		    			html += '>';
		    			html += ' Top\
		    		</label>\
		    		<label  style="margin-right: 10px">\
		    			<input type="radio" class="" name="position[xl][y]" value="center" ';
		    			html += itemOptionsObj.position.xl.y == "center" ? "checked" : "";
		    			html += '>';
		    			html += ' Center\
		    		</label>\
		    		<label >\
		    			<input type="radio" class="" name="position[xl][y]" value="bottom" ';
		    			html += itemOptionsObj.position.xl.y == "bottom" ? "checked" : "";
		    			html += '>';
		    			html += 'Bottom\
		    		</label>\
		    	</div>\
	    	</div>\
	    	<div class="col-md-4" style="border-bottom: 1px solid #eee;margin-bottom: 15px;">\
		    	<label for="position"><small class="text-muted">Large (≥992px)</small></label>\
		    	<div class="form-group">\
		    		<h5>Horizontal</h5>\
		    		<label style="margin-right: 10px">\
		    			<input type="radio" class="" name="position[lg][x]" value="left" ';
		    			html += itemOptionsObj.position.lg.x == "left" ? "checked" : "";
		    			html += '>';
		    			html += ' Left\
		    		</label>\
		    		<label style="margin-right: 10px">\
		    			<input type="radio" class="" name="position[lg][x]" value="center" ';
		    			html += itemOptionsObj.position.lg.x == "center" ? "checked" : "";
		    			html += '>';
		    			html += ' Center\
		    		</label>\
		    		<label >\
		    			<input type="radio"  class="" name="position[lg][x]" value="right" ';
		    			html += itemOptionsObj.position.lg.x == "right" ? "checked" : "";
		    			html += '>';
		    			html += ' Right\
		    		</label>\
		    	</div>\
		    	<div class="form-group">\
		    		<h5>Vertical</h5>\
		    		<label style="margin-right: 10px">\
		    			<input type="radio" class="" name="position[lg][y]" value="top"  ';
		    			html += itemOptionsObj.position.lg.y == "top" ? "checked" : "";
		    			html += '>';
		    			html += ' Top\
		    		</label>\
		    		<label style="margin-right: 10px">\
		    			<input type="radio" class="" name="position[lg][y]" value="center" ';
		    			html += itemOptionsObj.position.lg.y == "center" ? "checked" : "";
		    			html += '>';
		    			html += ' Center\
		    		</label>\
		    		<label >\
		    			<input type="radio" class="" name="position[lg][y]" value="bottom" ';
		    			html += itemOptionsObj.position.lg.y == "bottom" ? "checked" : "";
		    			html += '>';
		    			html += 'Bottom\
		    		</label>\
		    	</div>\
	    	</div>\
	    	<div class="col-md-4" style="border-bottom: 1px solid #eee;margin-bottom: 15px;">\
		    	<label for="position"><small class="text-muted">Medium (≥768px)</small></label>\
		    	<div class="form-group">\
		    		<h5>Horizontal</h5>\
		    		<label  style="margin-right: 10px">\
		    			<input type="radio" class="" name="position[md][x]" value="left" ';
		    			html += itemOptionsObj.position.md.x == "left" ? "checked" : "";
		    			html += '>';
		    			html += ' Left\
		    		</label>\
		    		<label  style="margin-right: 10px">\
		    			<input type="radio"  class="" name="position[md][x]" value="center" ';
		    			html += itemOptionsObj.position.md.x == "center" ? "checked" : "";
		    			html += '>';
		    			html += ' Center\
		    		</label>\
		    		<label>\
		    			<input type="radio"  class="" name="position[md][x]" value="right" ';
		    			html += itemOptionsObj.position.md.x == "right" ? "checked" : "";
		    			html += '>';
		    			html += ' Right\
		    		</label>\
		    	</div>\
		    	<div class="form-group">\
		    		<h5>Vertical</h5>\
		    		<label  style="margin-right: 10px">\
		    			<input type="radio" class="" name="position[md][y]" value="top"  ';
		    			html += itemOptionsObj.position.md.y == "top" ? "checked" : "";
		    			html += '>';
		    			html += ' Top\
		    		</label>\
		    		<label  style="margin-right: 10px">\
		    			<input type="radio"  class="" name="position[md][y]" value="center" ';
		    			html += itemOptionsObj.position.md.y == "center" ? "checked" : "";
		    			html += '>';
		    			html += ' Center\
		    		</label>\
		    		<label >\
		    			<input type="radio" class="" name="position[md][y]" value="bottom" ';
		    			html += itemOptionsObj.position.md.y == "bottom" ? "checked" : "";
		    			html += '>';
		    			html += 'Bottom\
		    		</label>\
		    	</div>\
	    	</div>\
	    	<div class="col-md-4" style="border-bottom: 1px solid #eee;margin-bottom: 15px;">\
		    	<label for="position"><small class="text-muted">Small (≥576px)</small></label>\
		    	<div class="form-group">\
		    		<h5>Horizontal</h5>\
		    		<label style="margin-right: 10px">\
		    			<input type="radio"  class="" name="position[sm][x]" value="left" ';
		    			html += itemOptionsObj.position.sm.x == "left" ? "checked" : "";
		    			html += '>';
		    			html += ' Left\
		    		</label>\
		    		<label style="margin-right: 10px">\
		    			<input type="radio"  class="" name="position[sm][x]" value="center" ';
		    			html += itemOptionsObj.position.sm.x == "center" ? "checked" : "";
		    			html += '>';
		    			html += ' Center\
		    		</label>\
		    		<label>\
		    			<input type="radio" class="" name="position[sm][x]" value="right" ';
		    			html += itemOptionsObj.position.sm.x == "right" ? "checked" : "";
		    			html += '>';
		    			html += ' Right\
		    		</label>\
		    	</div>\
		    	<div class="form-group">\
		    		<h5>Vertical</h5>\
		    		<label style="margin-right: 10px">\
		    			<input type="radio"class="" name="position[sm][y]" value="top"  ';
		    			html += itemOptionsObj.position.sm.y == "top" ? "checked" : "";
		    			html += '>';
		    			html += ' Top\
		    		</label>\
		    		<label style="margin-right: 10px">\
		    			<input type="radio"  class="" name="position[sm][y]" value="center" ';
		    			html += itemOptionsObj.position.sm.y == "center" ? "checked" : "";
		    			html += '>';
		    			html += ' Center\
		    		</label>\
		    		<label>\
		    			<input type="radio" class="" name="position[sm][y]" value="bottom" ';
		    			html += itemOptionsObj.position.sm.y == "bottom" ? "checked" : "";
		    			html += '>';
		    			html += 'Bottom\
		    		</label>\
		    	</div>\
	    	</div>';
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

var rendomID = function(){
	var d = new Date();
	var n = d.getTime();
	return n;
}

var sliderHeight = function(){
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

if(typeof builderOptions == 'undefined'){
	builderOptions = [];
}else{
	var container = $('#builder-content');
	for (var i = 0; i <= builderOptions.length - 1; i++) {
		var element = builderOptions[i];
		switch(element.widget){
			case 'b-slider':
				var slideHeight = sliderHeight(element.id);
				var tabHeight = slideHeight+60;
			    var el = makeSlider(element.id, element, element.items[0].id, element.items[0].html, element.items[0].image );
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
			    var el = makeImage(element.id, element);
			    $(el).css('min-height', tabHeight);
			    container.append('<li class="content-element" style="min-height:'+tabHeight+'">'+el+'</li>');
	    		elementStyleChangeObserver(element.id, element.id);
				break;
			case 'b-video':
				var slideHeight = sliderHeight(element.id);
				var tabHeight = slideHeight+25;
			    var el = makeVideo(element.id, element);
			    $(el).css('min-height', tabHeight);
			    container.append('<li class="content-element" style="min-height:'+tabHeight+'">'+el+'</li>');
	    		// elementStyleChangeObserver(element.id, element.id);
				break;
			case 'b-text':
			    var el = makeTextBlock(element.id, element);
			    container.append('<li class="content-element">'+el+'</li>');
        		buildCkEditor('text-block-editor-'+element.id , element.id);

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
		    	var el = makeProductsBlock(element.id, element);
			    container.append('<li class="content-element">'+el+'</li>');
				// buildCkEditor('editor-'+element.id, element.id);
				break;
			case 'b-instagram':
		    	var el = makeInstagramBlock(element.id, element);
			    container.append('<li class="content-element">'+el+'</li>');
				break;
            case 'b-user-viewd-product':
                var el = makeUserViewedProductsBlock(element.id, element);
                container.append('<li class="content-element">'+el+'</li>');
                break;
			case 'b-empty-space':
		    	var el = makeEmptySpace(element.id, element);
			    container.append('<li class="content-element">'+el+'</li>');
				break;
			case 'b-image-grid':
		    	var el = makeImageGridBlock(element.id, element);
			    container.append('<li class="content-element">'+el+'</li>');
		    	if(element.items.length != 0){
		    		for (var j = 0; j <= element.items.length - 1; j++) {
		    			var item = element.items[j];
				    	var newItem = newImageGridItem( item.id, item, element.id );
						$('#'+element.id).find('.no-item').before(newItem);
						elementStyleChangeObserver(item.id, element.id);
		    		}
		    		$.each($('#'+element.id).find('.item'), function(k, el){
		    			$(el).css('height', $(el).innerWidth());
		    		});
		    	}
				break;
			case 'b-accardion':
			    var el = makeAccardion(element.id, element, element.items[0].id, element.items[0]);

			    container.append('<li class="content-element">'+el+'</li>');
    		    buildCkEditor('editor-'+element.items[0].id, element.id, element.items[0].id);

	        	if(element.items.length > 1){
	        		for (var j = 1; j <= element.items.length - 1; j++) {
	        			var item = element.items[j];
	        			var panel = newAccardionItem(element.id, item.id, item);

        				var block = $(el);
        				var slideNumber = j+1;

        				// $('#'+element.id).find('.add-slide').before('<li><a data-toggle="tab" href="#slide-'+element.id+'-'+item.id+'">Slide '+slideNumber+'</a><span class="remove-slide remove-icon fa fa-times-circle" data-slide="slide-'+element.id+'-'+item.id+'"></span></li>');
        				$('#'+element.id).find('.accordion-container').append(panel);

        				buildCkEditor('editor-'+item.id, element.id, item.id);
        			    // elementStyleChangeObserver(item.id, element.id);
        			}
	        	}

				break;

				$(el).html(makeAccardion(id, builderOptionsObj, itemID)).css('min-height', '300px');
			    buildCkEditor('editor-'+itemID, id, itemID);

			    var elIndex = builderOptions.indexOf(builderOptionsObj);
			    var newItemObj = accardionItemObj(itemID);
			    builderOptionsObj.items.push(newItemObj);
		}
	}
}

var addToStorage = function(widget, id, position){
	switch(widget){
		case 'b-slider':
			var widgetOptions = {
				'widget' : widget,
				'id' : id,
				'bg_color' : '#ffffff',
				'container_size' : 'full',
				'controls' : 'arrows_middle',
				'height' : {
					'auto' : true,
					'sm': '',
					'md' : '',
					'lg' : '',
					'xl' : '',
					'xxl' : '',
				},
				'items' : [],
			};
			break;
		case 'b-image':
			var widgetOptions = {
				'widget' : widget,
				'id' : id,
				'bg_color' : '#ffffff',
				'container_size' : 'big',
				'image'  : null,
				'position' : {
					'xxl' : {
						'x' : 'center',
						'y' : 'center',
					},
					'xl' : {
						'x' : 'center',
						'y' : 'center',
					},
					'lg' : {
						'x' : 'center',
						'y' : 'center',
					},
					'md' : {
						'x' : 'center',
						'y' : 'center',
					},
					'sm' : {
						'x' : 'center',
						'y' : 'center',
					},
				},
				'cta_url' : 'https://copcopine.ru',
				'height' : {
					'auto' : true,
					'sm': '',
					'md' : '',
					'lg' : '',
					'xl' : '',
					'xxl' : '',
				},
				'alt' : 'Alt Text',
			};
			break;
		case 'b-video':
			var widgetOptions = {
				'widget' : widget,
				'id' : id,
				'bg_color' : '#ffffff',
				'container_size' : 'big',
				'video_url'  : '',
                'button_name': null,
                'url' : null,
				'height' : {
					'auto' : true,
					'sm': '',
					'md' : '',
					'lg' : '',
					'xl' : '',
					'xxl' : '',
				},
				'extra_link' : {
					'text' : '',
					'class_name' : 'left', // left || right || center
					'text_color' : '#000',
					'url' : ''
				},
				'button' : {
					'text' : '',
					'url' : ''
				}
			};
			break;
		case 'b-text':
			var widgetOptions = {
				'widget' : widget,
				'id' : id,
				'html'   : null,
				'bg_color' : '#F0EDE7',
				'container_size': 'big',
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
				'section_title' : '',
				'bg_color' : '#F0EDE7',
				'container_size': 'big',
				'items_count' : 6,
				'category' : null,
				'products_count' : -1,
				'products_ids' : [],
			};
			break;
		case 'b-user-viewd-product':
			var widgetOptions = {
				'widget' : widget,
				'id' : id,
				'section_title' : '',
				'bg_color' : '#F0EDE7',
				'container_size' : 'big',
				'items_count' : 6,
			};
			break;
        case 'b-instagram':
            var widgetOptions = {
                'widget' : widget,
                'id' : id,
                'section_title' : '',
                'bg_color' : '#F0EDE7',
                'container_size' : 'big',
                'items_count' : 6,
            };
            break;
		case 'b-empty-space':
			var widgetOptions = {
				'widget' : widget,
				'id' : id,
				'gutter' : '100px',
				'bg_color' : '#FFFFFF',
			};
			break;
		case 'b-image-grid':
			var widgetOptions = {
				'widget' : widget,
				'id' : id,
				'bg_color' : '#FFF',
				'container_size': 'big',
				'items_per_row' : 3, // 1, 2, 3, 4, 6
				'items' : [],
			};
			break;
		case 'b-accardion':
			var widgetOptions = {
				'widget' : widget,
				'id' : id,
				'bg_color' : '#FFF',
				'container_size': 'big',
				'items' : [],
			};
			break;
	}
	builderOptions.splice(position, 0, widgetOptions);
	localStorage.setItem('builderOptions', JSON.stringify(builderOptions));
}

var sliderItemObj = function(itemID){
	return {
		'id'	 : itemID,
		'image'  : null,
		'html'   : null,
		'content_class_name': 'content-center', //content-center || content-left || content-right
		'content_layout_class_name': 'new-collection', //new-collection || sale-collection || outlet-collection
		'position' : {
			'xxl' : {
				'x' : 'center',
				'y' : 'center',
			},
			'xl' : {
				'x' : 'center',
				'y' : 'center',
			},
			'lg' : {
				'x' : 'center',
				'y' : 'center',
			},
			'md' : {
				'x' : 'center',
				'y' : 'center',
			},
			'sm' : {
				'x' : 'center',
				'y' : 'center',
			},
		},
		'extra_link' : {
			'text' : '',
			'class_name' : 'left', // left || right || center
			'text_color' : '#000',
			'url' : ''
		}
	}
}

var accardionItemObj = function(itemID){
	return {
		'id'	 : itemID,
		'tab_title'	 : 'Hello, Im Title',
		'html'   : 'Hello Im Content',
	}
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

function removeWidgetItemFromStorage(widgetID, itemId) {
	console.log(widgetID, itemId);
    var  widget = builderOptions.find(obj => {
        return obj.id === parseInt(widgetID);
    });
    for (var i = 0; i < widget.items.length; i++) {
        var obj = widget.items[i];
        if (obj.id == itemId) {
            widget.items.splice(i, 1);
        }
    }
    localStorage.setItem('builderOptions', JSON.stringify(builderOptions));
}

function updateWidgetData(widgetID, key, value, nestedItemID = null) {
	// find element by ID
	builderOptionsObj = builderOptions.find(obj => {
	  return obj.id === parseInt(widgetID);``
	});

    var elIndex = builderOptions.indexOf(builderOptionsObj);
    if(builderOptionsObj !== undefined && nestedItemID == null){

    	if(builderOptionsObj[key] !== undefined){
    		builderOptionsObj[key] = value;
    		builderOptions[elIndex] = builderOptionsObj;
			localStorage.setItem('builderOptions', JSON.stringify(builderOptions));
    	}
    }else if(builderOptionsObj !== undefined && nestedItemID){
    	itemOptionsObj = builderOptionsObj.items.find(obj => {
    	  return obj.id === parseInt(nestedItemID);
    	});

    	if(itemOptionsObj[key] !== undefined){
        	itemOptionsObj[key] = value;
	        builderOptions[elIndex] = builderOptionsObj;
	        localStorage.setItem('builderOptions', JSON.stringify(builderOptions));
        }
    }
}

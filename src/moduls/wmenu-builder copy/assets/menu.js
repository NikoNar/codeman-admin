var arraydata = [];
function getmenus() {
	arraydata = [];
	$("#spinsavemenu").show()

	var cont = 0;
	$("#menu-to-edit li").each(function(index) {
		var dept = 0;
		for (var i = 0; i < $("#menu-to-edit li").length; i++) {

			var n = $(this).attr("class").indexOf("menu-item-depth-" + i);
			if (n != -1) {
				dept = i;
			}
		};
		var textoiner = $(this).find(".item-edit").text();
		var id = this.id.split("-");
		var textoexplotado = textoiner.split("|");
		var padre = 0;
		if (!!textoexplotado[textoexplotado.length-2] && textoexplotado[textoexplotado.length-2]!= id[2]) {
			padre = textoexplotado[textoexplotado.length-2]
		}
		arraydata.push({
			depth : dept,
			id : id[2],
			parent : padre,
			sort : cont
		})
		cont++;
	});
	updateitem();
	actualizarmenu();
}

// function addcustommenu() {
// 	$("#spincustomu").show();
//
// 	$.ajax({
// 		data : {
// 			labelmenu : $("#custom-menu-item-name").val(),
// 			linkmenu : $("#custom-menu-item-url").val(),
// 			idmenu : $("#idmenu").val()
// 		},
//
// 		url : addcustommenur,
// 		type : 'POST',
// 		success : function(response) {
//
// 			window.location = "";
//
// 		},
// 		complete: function(){
// 			$("#spincustomu").hide();
// 		}
//
// 	});
// }

function addcustommenu(type) {

	var data = {};
	var spinner = '';
	if (type === "pages") {
		spinner = $("#pages_spinner");
		pages = [];
		$('.pages_for_menu input:checked').each(function () {
			pages.push({
				labelmenu: $(this).attr('name'),
				linkmenu: $(this).data('url'),
				idmenu: $("#idmenu").val()
			});
		});
		data.pages = pages;

	} else if (type === "custom") {
		spinner = $("#spincustomu");
		data = {
			labelmenu: $("#custom-menu-item-name").val(),
			linkmenu: $("#custom-menu-item-url").val(),
			idmenu: $("#idmenu").val()
		};
	}
	spinner.show();

	$.ajax({
		data: data,
		url: addcustommenur,
		type: 'POST',
		success: function (response) {
			window.location = "";
		},
		complete: function () {
			spinner.hide();
		}
	});
}

function updateitem(id = 0) {
	if(id){
		alert();
		var label = $("#idlabelmenu_" + id).val()
		var clases = $("#clases_menu_" + id).val()
		var url = $("#url_menu_" + id).val()
		var data = {
			label : label,
			clases : clases,
			url : url,
			id : id,
			languag : $("#language").val()

		}
	}else{
		var arr_data = [];
		$('.menu-item-settings').each(function(k, v){
			var id = $(this).find(".edit-menu-item-id").val();
			var label = $(this).find(".edit-menu-item-title").val();
			var clases = $(this).find(".edit-menu-item-classes").val();
			var url = $(this).find(".edit-menu-item-url").val();
			arr_data.push({
				id : id,
				label : label,
				class : clases,
				link : url,
				language: $("#language").val()
			});
		});
		var data = {arraydata: arr_data};

		console.log(data);
	}
	$.ajax({
		data : data,
		url :updateitemr,
		type : 'POST',
		beforeSend: function(xhr){
			if(id){
				$("#spincustomu2").show();
			}
		},
		success : function(response) {
		},
		complete: function(){
			if(id){
				$("#spincustomu2").hide();
			}
		}
	});
}

function actualizarmenu() {
	$.ajax({
		dataType : "json",
		data : {
			arraydata : arraydata,
			menuname : $("#menu-name").val(),
			idmenu : $("#idmenu").val(),
			language : $("#language").val(),
		},

		url : generatemenucontrolr,
		type : 'POST',
		beforeSend: function(xhr) {
			$("#spincustomu2").show();
		},
		success : function(response) {

			console.log("aqu llega")

		},
		complete: function(){
			$("#spincustomu2").hide();
		}
	});
}

function deleteitem(id) {
	$.ajax({
		dataType : "json",
		data : {

			id : id
		},

		url :deleteitemmenur,
		type : 'POST',
		success : function(response) {

		}
	});
}

function deletemenu() {

	var r = confirm("Do you want to delete this menu ?");
	if (r == true) {
		$.ajax({
			dataType : "json",

			data : {

				id : $("#idmenu").val()
			},

			url : deletemenugr,
			type : 'POST',
			beforeSend: function(xhr){
				$("#spincustomu2").show();
			},
			success : function(response) {

				if (!response.error) {
					alert(response.resp);
					window.location = menuwr
				}else{
					alert(response.resp)
				}

			},
			complete: function(){
				$("#spincustomu2").hide();
			}
		});

	} else {
		return false;
	}

}

function createnewmenu() {

	if (!!$("#menu-name").val()) {
		$.ajax({
			dataType : "json",

			data : {
				menuname : $("#menu-name").val(),
				language : $("#language").val(),
			},

			url :createnewmenur,
			type : 'POST',
			success : function(response) {

				window.location = menuwr+"?menu=" + response.resp

			}
		});
	} else {
		alert("Enter menu name!")
		$("#menu-name").focus();
		return false;
	}

}


function insertParam(key, value)
{
	key = encodeURI(key); value = encodeURI(value);

	var kvp = document.location.search.substr(1).split('&');

	var i=kvp.length; var x; while(i--)
{
	x = kvp[i].split('=');

	if (x[0]==key)
	{
		x[1] = value;
		kvp[i] = x.join('=');
		break;
	}
}

	if(i<0) {kvp[kvp.length] = [key,value].join('=');}

	//this will reload the page, it's likely better to store this until finished
	document.location.search = kvp.join('&');
}
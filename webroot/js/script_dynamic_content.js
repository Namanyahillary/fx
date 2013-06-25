/**
 * Author: Namanya Hillary
 * Email -> namanyahillary@gmail.com
**/
var lock=0;
$(document).ready(function(){
	
	var _long_numbers=$('.ln');
	$.each(_long_numbers,function(k,v){
		$(this).html(add_commas(myRound(Number($(this).html()),0)));
	});
	
	//Pagination
	$('.paging span a').addClass('btn').addClass('btn-small');
	
	//Edit Links
	prepare_ajax_links();
	
	//Fetch data for clicked links
	$('.dynamic-content a, .use-ajax').click(function (){	
		if(!($(this).hasClass('no-ajax'))){
			if(!(confirmRequest($(this))))	return false;showLoading();
			_obj=$(this);
			
			if(lock==0)lock=1;
			else return;
			
			var data = {};
			data={
				'fox_id':$('.fox-selected').attr('fox_selected_id'),
				'date_today':$('#dp_today_selected').val(),
				'date_from':$('#dp_from_selected').val(),
				'date_to':$('#dp_to_selected').val()
			};
			if(lock==1){
				$.ajax({
					url: $(this).attr('data-target'),
					data: data,
					success: function(data) {lock==0;afterFetch(_obj,data);},
					error: function() {lock==0;},
					complete : function () {lock==0;},
					statusCode: {403: function (response) {window.location.href='http://localhost/fx/users/login';}}
				});
			}
		}
	});
	
	
	//submit Form data
	$(".dynamic-content form, .modal-body form").submit(function(e){
		e.preventDefault();
		var $form = $( this ),
		my_url = $form.attr( 'action' );
		dataString = $( this ).serialize();
		
		if(!(confirmRequest($(this))))	return false;showLoading();
		_obj=$(this);
		$.ajax({type: "POST",url: my_url,data: dataString,dataType: "html",
			success: function(data) {lock==0;afterFetch(_obj,data);} ,
			error: function() {lock==0;},
			complete : function () {lock==0;},
			statusCode: {403: function (response) {window.location.href='http://localhost/fx/users/login';}}
		}); 
	});
	
	//Fade out Flash Message
	setTimeout(function(){
		$('.flash-message').fadeOut('slow');
	},4000);
	
	//Search for receipt
	$('.sold_search_query_btn, .purchased_search_query_btn').click(function (){	
		if(($('.search_query_string').val()).length==0) return false;
		showLoading();
		_obj=$(this);
		
		if(lock==0)lock=1;
		else return;
		
		var data = {};
		data={
			'fox_id':$('.fox-selected').attr('fox_selected_id'),
			'date_today':$('#dp_today_selected').val(),
			'date_from':$('#dp_from_selected').val(),
			'date_to':$('#dp_to_selected').val(),
			'search_query_string':$('.search_query_string').val()
		};
		if(lock==1){
			$.ajax({
				url: $(this).attr('search-target'),
				data: data,
				success: function(data) {lock==0;afterFetch(_obj,data);},
				error: function() {lock==0;},
				complete : function () {lock==0;},
				statusCode: {403: function (response) {window.location.href='http://localhost/fx/users/login';}}
			});
		}
	});
	
});

//Format long numbers by adding commas
function add_commas(nStr){
	nStr += '';	x = nStr.split('.');x1 = x[0];x2 = x.length > 1 ? '.' + x[1] : '';	var rgx = /(\d+)(\d{3})/;	
	while (rgx.test(x1)) {x1 = x1.replace(rgx, '$1' + ',' + '$2');}
	return x1 + x2;
}

//Show that data is being sent/fetched from the server
function showLoading(){
	var img="<img class='loading-animation' src='http://localhost/fx/img/spinner.gif' style='position:fixed;bottom:120px;left:220px;'>";
	$('.dynamic-content').prepend(img);
}

//Remove the loading animation 
function removeLoading(){
	$('.loading-animation').remove();
}

function prepare_ajax_links(){
	//remove all the hrefs(hyperlinks)
	$('.dynamic-content a, .use-ajax').each(function(){
		if(!($(this).hasClass('no-ajax')) && ($(this).attr('href')!='#')){
			var reference_link=$(this).attr('href');
			$(this).attr('href','#');
			$(this).attr('data-target',reference_link);
			$(this).attr('onclick','return false;');
		}
		
			
		
	});
}

//called before a request is sent to confirm user

function confirmRequest(obj){
	var bool=1;
	var attr = obj.attr('data-confirm-text');
	if(obj.hasClass('confirm-first') && (typeof attr !== 'undefined' && attr !== false)){
		if(!(confirm(obj.attr('data-confirm-text')))){
			bool=0;
		}	
	}
	return bool;
}


//function called after the data has been fetched and the request is successfull
function afterFetch(obj,data){
	if(obj.hasClass('for-modal')){
		removeLoading();
		$('.modal-body').html(data);
		$("#view-modal").modal('show');
	}else{
		$('.dynamic-content').html(data);
	}
}

function myRound(value, places) {
	var multiplier = Math.pow(10, places);
	return (Math.round(value * multiplier) / multiplier);
}



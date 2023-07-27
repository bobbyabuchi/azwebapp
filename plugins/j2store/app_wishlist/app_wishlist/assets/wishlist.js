/**
 * Setup (required for Joomla! 3)
 */
if(typeof(j2store) == 'undefined') {
	var j2store = {};
}
if(typeof(j2store.jQuery) == 'undefined') {
	j2store.jQuery = jQuery.noConflict();
}


(function($) {
	/*
	 * To check All Checkox or Remove Check
	 */
	// Ajax add to cart
	$( document ).on( 'click', 'input[name=checkall-toggle]', function(e) {
		if($(this).is(':checked')){
			$('input[name="cid[]"]').each(function() { //loop through each checkbox
			      this.checked = true;  //select all checkboxes with class "name cid"
            });
			$('#allitem-btn').show();
			$('#j2store-all-item-delete').show();
		}else{
			$('input[name="cid[]"]').each(function() { //loop through each checkbox
			
                this.checked = false;  //deselect all checkboxes with class "name cid"
            });
			$('#allitem-btn').hide();
			$('#j2store-all-item-delete').hide();
		}
      });

	/**
	 * Method to trigger changes when cid Checkbox is selected and Deselected
	 *
	 */
	$(document).ready(function(){
		$('input[name="cid[]"]').on('click' , function(){
			
			//check is checked true 
			if($('input[name="cid[]"]:checked').length){				 
				//show the add All Button 
				$('#allitem-btn').show();
				$('#j2store-all-item-delete').show();
				if($('input[name="cid[]"]:checked').length == $('input[name="cid[]"]').length ){
					$('input[name=checkall-toggle]').prop('checked',true);
				}
			}else{				
				//when there is no checkbox is selected				
				$('#allitem-btn').hide();	
				$('#j2store-all-item-delete').hide();
				if($('input[name="cid[]"]:checked').length){
					$('input[name=checkall-toggle]').prop('checked',true);
				}
			}				
			// check incase checked checkbox with the name don't match with checkbox  with name cid's  length
			//set the checkAll Checkbox to unchecked
			if($('input[name="cid[]"]:checked').length != $('input[name="cid[]"]').length){
				$('input[name=checkall-toggle]').prop('checked',false);
			}
		});
	});
	
	//call the triggers
	$('input[name=\'checkall-toggle\']').trigger('change');
	$('input[name=\'cid[]\']').trigger('change');
	
})(j2store.jQuery);


function addToWishlist(element,url) {
	
	(function($) {
		$('.j2store-wishlist-notification').remove();
		var thisElement = $(element);
		var product_id = $(thisElement).data('wishlist-product-id');		
		
		$('#icon-spinner-'+product_id).show('slow');			
		
		var form = $(element).closest('form');
		//	form.css('fa fa-spinner');
			
		//sanity check
		//if(form.data('product_id') != product_id) return;
		if(form.data('product_type') == 'variable') {
			var variant_id = form.find('input[name=\'variant_id\']').val();			
		}else {
			var variant_id = $(thisElement).data('wishlist-variant-id');
		}
		var aid = $(thisElement).data('wishlist-id');
			
		
		var options = [];
		var x = form.find('input[name="product_option[]"]').val();
		
		var new_data = form.serializeArray();
		
		var values = form.serializeArray();

		// pop these params from values-> task : add & view : mycart
		values.pop({
			name : "task",
			value : 'addItem'
		});

		values.pop({
			name : "view",
			value : 'carts'
		});
		
		var arrayClean = function(thisArray) {
		    "use strict";
		    $.each(thisArray, function(index, item) {
		        if (item.name == 'task' || item.name == 'view') {
		            delete values[index];      
		        }
		    });
		};
		
		arrayClean(values);
		
		values.push({
			name : "option",
			value : 'com_j2store'
		});
		values.push({
			name : "task",
			value : 'addtowishlist'
		});

		values.push({
			name : "view",
			value : 'carts'
		});
		
		values.push({
			name : "appTask",
			value : 'addWishlist'
		});
		
		values.push({
			name : "id",
			value : aid
		});
		if($.inArray('product_id' ) <= 0 ){
		
			values.push({
				name : "product_id",
				value : product_id
			});
		}
		
		
		
		//if($.inArray('product_qty') <= 0 ){
		var product_qty = 1;
		if(form.find('input[name=\'product_qty\']').val() >= 0){ 	
			product_qty = form.find('input[name=\'product_qty\']').val();
		}
		
		values.push({
			name : "product_qty",
			value : product_qty
		});
		
			
		values.push({
			name : "variant_id",
			value : variant_id
		});
		
		values = $.param(values);

		
		var j2Ajax = $.ajax({
			url : url,
			type : 'post',
			data : values,
			dataType : 'json'
		});

		j2Ajax.done(function(json) {			
			form.find('.j2success, .j2warning, .j2attention, .j2information, .j2error').remove();
					
			$('#icon-spinner-'+product_id).hide('slow');
			if (json['success']) {			
							
				// $(thisElement).after('' + json['resulthtml'] + '');
				
				var wishlist_layout = $(thisElement).data('wishlist-link');
				
				$(thisElement).attr('href', wishlist_layout);
				
				if($(thisElement).data('wishlist_link_type') =='text'){
					$(thisElement).html($(thisElement).data('wishlist-wishlist-view'));
				}else{
					$(thisElement).children('.fa').addClass('text-error');
					// $(thisElement + ' >  .fa').addClass('text-error');
				}
				
				$(thisElement).attr('onclick','');
				
				if( typeof j2storeModulegetUpdatedWishlistModule != 'undefined'  && $.isFunction(j2storeModulegetUpdatedWishlistModule)){
						j2storeModulegetUpdatedWishlistModule();			
				}
			}
			
			if (json['error']) {
				
				if (json['error']['option']) {
					for (i in json['error']['option']) {
						form.find('#option-' + i).after('<span class="j2error">' + json['error']['option'][i] + '</span>');
					}
				}
				$(thisElement).after('' + json['resulthtml'] + '');
			}		
					
			 if(json['redirect']){
					window.location.href = json['redirect'];				
			}else if($(element).data('enable_redirect') == true){
				window.location.href = $(element).data('wishlist-link');
			} 
		});

	})(j2store.jQuery);

}


var reload =true;

(function($) {
		$(document).ready(function(){
		$('.j2store-wishlist-form').each(function(){
			$(this).submit(function(e) {	
			e.preventDefault();
			var form = $(this);
					//this will help detect if the form is submitted via ajax or normal submit.
			//sometimes people will submit the form before the DOM loads
			form.find('input[name=\'ajax\']').val(1);
			/* Get input values from form */
			//var values = form.find('input[type=\'text\'], input[type=\'number\'], input[type=\'hidden\'], input[type=\'radio\']:checked, input[type=\'checkbox\']:checked, select, textarea');
			form.find('input[type=\'submit\']').val(form.find('input[type=\'submit\']').data('cart-action-always'));
			var cartitem_id = form.find('input[name=\'j2store_cartitem_id\']').attr('value');
						
			var values = form.serializeArray();
			
			
			var j2Ajax = $.ajax({
					url: j2storeURL,
					type: 'post',
					data: values,
					dataType: 'json'
						
		 	 });
		 	
		 	j2Ajax.done(function(json) {		 		
		 	 		form.find('.j2success, .j2warning, .j2attention, .j2information, .j2error').remove();
					$('.j2store-notification').hide();				
					if (json['error']) {
						
						form.find('input[type=\'submit\']').val(form.find('input[type=\'submit\']').data('cart-action-done'));
						
						if (json['error']['option']) {
							for (i in json['error']['option']) {
								
								form.find('.j2store-notifications').after('<span class="j2error">' + json['error']['option'][i] + '</span>');
								form.find('.j2store-cart-button').hide();
								form.find('.product-link').show();
							}
						}
						if (json['error']['stock']) {
							form.find('.j2store-notifications').html('<span class="j2error">' + json['error']['stock'] + '</span>');
						}
						
						if (json['error']['general']) {
							form.find('.j2store-notifications').html('<span class="j2error">' + json['error']['general'] + '</span>');
						}
						
						if (json['error']['product']) {
							form.find('.j2store-notifications').after('<span class="j2error">' + json['error']['product'] + '</span>');
						}
					}	
					
					if (json['redirect']) {
						window.location.href = json['redirect'];
					}
					
					if (json['success']) {						
						
						if( typeof j2storeModulegetUpdatedWishlistModule != 'undefined'  && $.isFunction(j2storeModulegetUpdatedWishlistModule)){
							j2storeModulegetUpdatedWishlistModule();			
						}
						
						setTimeout(function() {						
							form.find('input[type=\'submit\']').val(form.find('input[type=\'submit\']').data('cart-action-done'));
							form.find('.cart-action-complete').fadeIn('slow');							
						}, form.find('input[type=\'submit\']').data('cart-action-timeout'));
						var $thisbutton = $( this );
						$( 'body' ).trigger( 'after_adding_to_cart', [ $thisbutton, json, 'link'] );
						//if module is present, let us update it.									
						if(form.data('empty-wishlist')){
							var element =  $('#removeWishlist-btn-'+cartitem_id);
							reload = false;
							removeFromWishlist(element,j2storeURL);
						}
								
					}				
					
		 	})	 	
		 	.fail(function( jqXHR, textStatus, errorThrown) {
		 		form.find('input[type=\'submit\']').val(form.find('input[type=\'submit\']').data('cart-action-done'));
		 			
		 	})
		 	.always(function(jqXHR, textStatus, errorThrown) {
		 		//form.find('input[type=\'submit\']').val(form.find('input[type=\'submit\']').data('cart-action-always'));	 		
		 	});
			});	
		});		//end of ajax call
	  }); //end of document ready
	})(j2store.jQuery);




/**
 * Method to remove an item from Wishlist
 * @param element
 */
function removeFromWishlist(element,url) {
	(function($) {
		$('.j2store-wishlist-notify').remove();
		var aid = $(element).data('app_id');	
		var thisElement = $(element);	
			
		var item_id = $(thisElement).data('wishlist_item_id');
		var wishlist_id = $(thisElement).data('wishlist_id');
		
		var j2Ajax = $.ajax({
			url : url,
			type : 'post',
			data : {
				'option' 			: 'com_j2store',
				'view' 				: 'apps',
				'task' 				: 'view',						
				'appTask' 			: 'removeWishlistItems',
				'id' 				:	 aid,
				'wishlistitem_id' 		:	item_id,
				'wishlist_id'		: wishlist_id
				},
			dataType : 'json'

		});

		j2Ajax.done(function(json) {			
			if (json['success']) {				     
				if(reload){
					location.reload();
				}
			}			
		});

	})(j2store.jQuery);

}

/**
 * This method will allows you to add all items 
 */
(function($) {
	$(document).ready(function(){
		$('#j2store-wishlist-main-block #allitem-btn').on('click',function(e){ 
			
			$(this).attr('value' , $(this).data('cart-action-always'));		
	$('input[name="cid[]"]:checked').each(function(index, element) {
		
	var element_id = $(element).attr('value');
	var form = $('#j2store-wishlist-form-'+element_id);
	form.find('.wishlist-adding-item').show();
		var new_values = form.serializeArray();							
		var errors = [];		
		var j2Ajax = $.ajax({
				url: j2storeURL,
				type: 'post',
				data: new_values,
				dataType: 'json',							
	 	 });
		j2Ajax.done(function(json) {	 		
	 		form.find('.wishlist-adding-item').hide();
	 		
	 		$('allitem-btn').attr('value' , $(this).data('cart-action-done'));
	 	 		form.find('.j2success, .j2warning, .j2attention, .j2information, .j2error').remove();
				$('.j2store-notification').hide();				
				if (json['error']) {
					//$('#allitem-btn').attr('disabled',true);
					form.find('input[type=\'submit\']').val(form.find('input[type=\'submit\']').data('cart-action-done'));
					
					if (json['error']['option']) {
						for (i in json['error']['option']) {
							
							form.find('.j2store-notifications').after('<span class="j2error">' + json['error']['option'][i] + '</span>');
							form.find('.j2store-cart-button').hide();
							form.find('.product-link').show();
						}
					}
					if (json['error']['stock']) {
						form.find('.j2store-notifications').html('<span class="j2error">' + json['error']['stock'] + '</span>');
					}
					
					if (json['error']['general']) {
						form.find('.j2store-notifications').html('<span class="j2error">' + json['error']['general'] + '</span>');
					}
					
					if (json['error']['product']) {
						form.find('.j2store-notifications').after('<span class="j2error">' + json['error']['product'] + '</span>');
					}
				}
						
				if (json['success']) { 
					//location.reload();
					form.find('.wishlist-adding-item').hide();				 	 
					 $(element).prop('checked',false);
					 $(this).attr('value' , $(this).data('cart-action-done'));				 
					
						setTimeout(function() {						
						form.find('input[type=\'submit\']').val(form.find('input[type=\'submit\']').data('cart-action-done'));
						form.find('.cart-action-complete').fadeIn('slow');
						form.find('.j2success').fadeIn('slow');
					}, form.find('input[type=\'submit\']').data('cart-action-timeout'));
					var $thisbutton = $( this );
					$( 'body' ).trigger( 'after_adding_to_cart', [ $thisbutton, json, 'link'] );
					
				// only the config remove item afert add to cart is enabled
					if(form.data('empty-wishlist')){
						var cartitem_id = form.find('input[name=\'j2store_cartitem_id\']').attr('value');
						var tr_element =  $('#removeWishlist-btn-'+cartitem_id);		
						reload = false;						
						removeFromWishlist(tr_element,j2storeURL);
						$('#wishlist-cartitem-tr-'+cartitem_id).remove();
					}							
														
				}				
				
	 	})	 	
	 	.fail(function( jqXHR, textStatus, errorThrown) {
	 		form.find('input[type=\'submit\']').val(form.find('input[type=\'submit\']').data('cart-action-done'));
	 			 		
	 	})
	 	.always(function(jqXHR, textStatus, errorThrown) {	 		 		
	 	});
	 	 	
	});	
		$('#allitem-btn').attr('value',$('#allitem-btn').data('cart-action-done'));
		$('#allitem-notification').show();
	 	$('#j2store-product-loading').hide();
	 	$('allitem-btn').attr('disabled' ,false);
	 	$('allitem-btn').attr('value' , $(this).data('cart-action-done'));
	 	$('#wishlist-notify').show();
	 	$('.wishlist-add-success').show();
	 	$('.j2store-wishlist-checkall').prop('checked' ,false);
	 	doMiniCart();
	 	
	});		//end of ajax call
	});
})(j2store.jQuery);



(function($) {
	$(document).ready(function(){
		$('#j2store-wishlist-main-block #j2store-all-item-delete').on('click',function(e){		
			$('input[name="cid[]"]:checked').each(function(index, element) {
				var cartitem_id = $(this).attr('value');
				var tr_element =  $('#removeWishlist-btn-'+cartitem_id);		
				reload = false;						
				removeFromWishlist(tr_element,j2storeURL);
				$('#wishlist-cartitem-tr-'+cartitem_id).remove();
			});	//end of ajax call
			$('.j2store-wishlist-checkall').prop('checked' ,false);
			$('#wishlist-notify').show();
		 	$('.wishlist-delete-success').show();
			
			
			
		});
	});
})(j2store.jQuery);
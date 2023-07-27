<?php
/**
 * @package J2Store
 * @copyright Copyright (c)2014-17 Ramesh Elamathi / J2Store.org
 * @license GNU GPL v3 or later
 */
/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
require_once(JPATH_ADMINISTRATOR.'/components/com_j2store/helpers/router.php');
JFactory::getDocument()->addScript ( JURI::root ( true ) . '/plugins/j2store/app_wishlist/app_wishlist/assets/wishlist.js' );

$cart_text = JText::_('J2STORE_ADD_TO_CART');
$wish_url = JRoute::_('index.php');
?>
<style>
.j2store-productwishlist-img{
	width : <?php echo $vars->plugin_params->get('thumbimage_width',50)?>px;
}
</style>
<div class="wishlist" id="j2store-wishlist-main-block">

		<div class="alert alert-success" id="wishlist-notify" style="display:none;">
			<p class="wishlist-add-success wishlist-msg text text-success"  style="display:none;">
				<?php echo JText::_('J2STORE_WISHLIST_ADD_ALL_ITEM_ADDED_TO_CART_MESSAGE');?>
				<a id="allitem-notification" class="cart-link" href="<?php echo $vars->cart_url; ?>"  style="display:none;">
						<?php echo JText::_('J2STORE_VIEW_CART');?>
				</a>
			</p>

			<p class="wishlist-delete-success wishlist-msg text text-success"  style="display:none;">
				<?php echo JText::_('J2STORE_WISHLIST_ALL_ITEM_DELETED_SUCCESS_MESSAGE');?>
			</p>
		</div>

	<h4 class="center"><?php echo JText::_('J2STORE_WISHLIST_ITEMS');?></h4>

	<table class="table table-bordered">
		<thead>
			<tr>
				<th>
				<?php if(!empty($vars->products) && $vars->products): ?>
					<input type="checkbox" name="checkall-toggle" value="" class="j2store-wishlist-checkall" />
				<?php endif;?>
				</th>
				<th> <?php echo JText::_('J2STORE_PRODUCT_NAME')?></th>
				<th><?php echo JText::_('J2STORE_ADD_TO_CART')?></th>
				<th><?php echo JText::_('J2STORE_REMOVE')?></th>
			</tr>
		</thead>
		<?php
			if(!empty($vars->products) && $vars->products):
			$image_path = JUri::root();
			$image_type = $vars->params->get('image_type', 'thumbnail');
			$main_image="";
			$count = count($vars->products) > 0 ?  count($vars->products) + 1  : 1;
	?>
		<tbody>
			<?php foreach($vars->products as $item):

			if(!empty($item->addtocart_text)) {
				$cart_text = JText::_($item->addtocart_text);
			}
			// tried getting link from helper but it doesn't work
			if($vars->plugin_params->get('product_view_type', 'list') == 'list') {
				$qoptions = array (
					'option' => 'com_j2store',
					'view' => 'products',
					'task' => 'view',
					'id' => $item->product_id
				);
				$pro_menu = J2StoreRouterHelper::findProductMenu ( $qoptions );
				$menu_id = isset($pro_menu->id) ? $pro_menu->id:null;
				if($item->product_source != "com_content"){
					$item->product_link  = $item->product_view_url;
				}else{
					$item->product_link  = JRoute::_('index.php?option=com_j2store&view=products&task=view&id='.$item->product_id.'&Itemid='.$menu_id);
				}
				//$item->product_link  = JRoute::_('index.php?option=com_j2store&view=products&task=view&id='.$item->product_id.'&Itemid='.$menu_id);
			}else{
				$item->product_link  = $item->product_view_url;
			}
			?>
			<tr class="j2store-wishlist-items" id="wishlist-cartitem-tr-<?php echo $item->j2store_cartitem_id;?>">
				<td>
					<input id="cid<?php echo $item->j2store_cartitem_id;?>" type="checkbox" name="cid[]" value="<?php echo $item->j2store_cartitem_id;?>"/>
				</td>
				<td>
					<h5>
						<a class="product-title" href="<?php echo $item->product_link; ?>">
							<?php echo $item->product_name;?>
						</a>
					</h5>
					<?php
						//check image is exists
						$product_image = $item->thumb_image;
						if($image_type =='mainimage'){
							$product_image = $item->main_image;
						}
					?>
					<?php if(JFile::exists(JPATH_SITE.'/'.JPath::clean($product_image))):?>
						<img itemprop="image" alt="<?php echo $item->product_name ;?>" class="thumbnail j2store-productwishlist-img j2store-productwishlist-thumb-image-<?php echo $item->product_id; ?>"	src="<?php echo $image_path.$product_image;?>" />
						<?php else:?>
						<!-- Placholder image comes here  -->
						<img itemprop="image" alt="<?php echo $item->product_name ;?>"
							class="thumbnail j2store-productwishlist-img j2store-productwishlist-thumb-image-<?php echo $item->product_id; ?>"	src="<?php echo JURI::root ( true ) . '/plugins/j2store/app_wishlist/app_wishlist/assets/images/placholder.png';?>" />
					<?php endif; ?>

				<!-- Options -->
					<?php
					if(count($item->options)):?>
						<?php foreach ($item->options as  $attribute ):?>
							<small>
								-<?php echo JText::_($attribute['name']); ?> : <?php echo $attribute['option_value']; ?>
									<!-- Here Options will be in hidden inputs  -->
							</small>
							<br/>
							<?php endforeach;?>
						<?php endif;?>

					<?php if($vars->params->get('show_product_price',1)):?>
					<div class="wishlist-product-price-container">
						<span class="cart-item-title">
							<?php echo JText::_('J2STORE_CART_LINE_ITEM_UNIT_PRICE'); ?>
						</span>
						<span class="sale-price">
							<?php echo $item->item_price;?>
						</span>
					</div>
					<?php endif; ?>
				</td>
				<!-- Created  On
				<td>
					<?php  echo JHTML::_('date',$item->created_on, $vars->params->get('date_format', JText::_('DATE_FORMAT_LC1'))); ?>
				</td>
-->
				<td>

					<form action="<?php echo JRoute::_('index.php');?>"
						 method="post"
						class="j2store-wishlist-form"
						id="j2store-wishlist-form-<?php echo $item->j2store_cartitem_id;?>"
						name="j2store-addtocart-form-<?php echo $item->product_id; ?>"
						data-element_id="<?php echo $vars->aid;?>"
						data-empty-wishlist="<?php echo $vars->plugin_params->get('empty_wishlistitem');?>"
						enctype="multipart/form-data">
							<?php
						if(count($item->options)):?>
						<?php foreach ($item->options as  $attribute ):?>
						<?php if($attribute['type'] =='text' || $attribute['type'] =='date'  ||  $attribute['type'] =='file' || $attribute['type'] =='textarea' || $attribute['type'] =='time' || $attribute['type'] =='datetime'):?>
							<input type="hidden" value="<?php echo $attribute['option_value']; ?>" name="product_option[<?php echo $attribute['product_option_id'];?>]" />
						<?php else:?>
							<input type="hidden" value="<?php echo $attribute['product_optionvalue_id'];?>" name="product_option[<?php echo $attribute['product_option_id'];?>]" />
						<?php endif;?>
						<?php endforeach;?>
						<?php endif;?>

						<input id="wishlist-product-qty-<?php echo $item->j2store_cartitem_id;?>" class="input-mini" name="product_qty" value="<?php echo (int) $item->product_qty; ?>" min="1" type="number" />
						<input  data-cart-action-always="<?php echo JText::_('J2STORE_ADDING_TO_CART'); ?>"
							data-cart-action-done="<?php echo $cart_text; ?>"
							data-cart-action-alldone="<?php echo JText::_('J2STORE_APP_WISHLIST_ITEM_ADDED'); ?>"
							data-cart-action-timeout="1000"
							value="<?php echo $cart_text; ?>"
							type="submit" class="btn btn-primary" />

						<input type="hidden" name="option" value="com_j2store" />
						<input type="hidden" name="view" value="apps" />
						<input type="hidden" name="task" value="view" />
						<input type="hidden"  name="appTask" value="addwishlistItemToCart" />
						<input type="hidden" name="id" value="<?php echo $vars->aid;?>" />
						<input type="hidden" name="cart_id" value="<?php echo $item->cart_id;?>" />
						<input type="hidden" name="j2store_cartitem_id" value="<?php echo $item->j2store_cartitem_id;?>" />
						<input type="hidden" name="return" value="<?php echo base64_encode( JUri::getInstance()->toString() ); ?>" />

						<div class="cart-action-complete" style="display:none;">
							<p class="text-success">
								<?php echo JText::_('J2STORE_ITEM_ADDED_TO_CART');?>
								<a href="<?php echo $vars->cart_url; ?>" class="j2store-checkout-link">
									<?php echo JText::_('J2STORE_CHECKOUT'); ?>
								</a>
							</p>
						</div>

						<div class="j2store-notifications">
						<div class="wishlist-adding-item" style="display:none;">
								<img src="<?php echo JRoute::_(JURI::root ( true ).'/plugins/j2store/app_wishlist/app_wishlist/assets/images/ajax-loader.gif');?>" />
						</div>

							<br/>
							<a class="product-link btn btn-warning" href="<?php echo $item->product_link; ?>"  style="display:none;">
								<?php echo JText::_('J2STORE_GO_TO_DETAIL_PAGE');?>
							</a>
						</div>
					</form>

				</td>
				<td>
					<a id="removeWishlist-btn-<?php echo $item->j2store_cartitem_id;?>"
					   href="javascript:void(0);"
					   onclick="removeFromWishlist(this,'<?php echo $wish_url;?>');"
					   class="product-wishlist-remove btn btn-danger btn-mini"
					   data-wishlist_item_id ='<?php echo $item->j2store_cartitem_id;?>'
					   data-wishlist_id="<?php echo $item->cart_id;?>"
					   data-app_id="<?php echo $vars->aid;?>"
					   >
						<i class="icon icon-trash"></i>
					</a>
				</td>
			</tr>
			<?php endforeach;?>
		</tbody>
	<?php else:?>
		<tfooter>
			<tr>
			<td colspan="4">
				<?php echo JText::_('J2STORE_APP_WISHLIST_NO_ITEMS');?>
			</td>
			</tr>
		</tfooter>
	<?php endif; ?>
</table>

	<!-- It will redirect you to Continue shopping -->
	<div class="row-fluid">
			<div class="span6">

				<!-- Continue Url -->
				<?php if($vars->continue_shopping_url->type != 'previous'): ?>
						<input class="btn btn-success" type="button" onclick="window.location='<?php echo $vars->continue_shopping_url->url; ?>';" value="<?php echo JText::_('J2STORE_CART_CONTINUE_SHOPPING'); ?>" />
					<?php else: ?>
						<input class="btn btn-success" type="button" onclick="window.history.back();" value="<?php echo JText::_('J2STORE_CART_CONTINUE_SHOPPING'); ?>" />
					<?php endif;?>

					<?php if(!empty($vars->products)):?>
			   			<button class="btn btn-default btn-warning" type="button" onclick="getUpdateWishlist();"><?php echo JText::_('J2STORE_UPDATE');?></button>

					<?php if(empty(JFactory::getUser()->id)):?>
							<a class="btn btn-default btn-success" href="<?php echo JRoute::_('index.php?option=com_users&view=login');?>"><?php echo JText::_('J2STORE_SAVE_ITEM_TO_WISHLIST');?></a>
					<?php endif;?>
						<?php endif;?>
			</div>
			<!-- For ADding all items -->
			<div class="span6">

				<input id="allitem-btn"
					data-cart-action-always="<?php echo JText::_('J2STORE_ADDING_ALL_ITEMS_TO_CART'); ?>"
			   		data-cart-action-done="<?php echo JText::_('J2STORE_ADD_ALL_TO_CART'); ?>"
			   		data-cart-action-timeout="1000" value="<?php echo JText::_('J2STORE_ADD_ALL_TO_CART'); ?>" type="button"
			   		class="btn btn-success"
			   		style="display:none;"
			 	  />

			 	 <input id="j2store-all-item-delete" value="<?php echo JText::_('J2STORE_DELETE_ALL_ITEMS'); ?>"	 type="button" class="btn btn-danger"
			   		style="display:none;"
			 	  />

			   <div class="wishlist-notifications">
				<br/>
					<a id="allitem-notification" class="cart-link btn btn-warning" href="<?php echo $vars->cart_url; ?>"  style="display:none;">
						<?php echo JText::_('J2STORE_VIEW_CART');?>
					</a>
				</div>
			</div>
		</div>
</div>
<script type="text/javascript">

 function getUpdateWishlist(){
	var app_id = <?php echo $vars->aid;?>;
	 (function($) {
	 	var data =[];

	 	$('.j2store-wishlist-form').each(function(){
	 		var form  =$(this);
			data =  $(this).find('input[name=\'product_qty\'], input[name=\'j2store_cartitem_id\']').serializeArray();
			data.push(
					{
				 		name :'option',
				 		value:'com_j2store'
			 		},
			 		{
						name : "id",
						value : app_id
					},
			 		{
						name : "view",
						value : 'apps'
					},
					{
						name : "task",
						value : 'view'
					},
					{
						name : "appTask",
						value : 'updateWishlist'
					});
			 	var j2Ajax = $.ajax({
						url: 'index.php',
						type: 'post',
						data: data,
						dataType: 'json'
			 	 });
			 	j2Ajax.done(function(json) {
						$('.j2store-notification').hide();
						$('.j2store-wishlist-success').remove();
						if (json['success']) {
							setTimeout(function() {
								form.find('.j2store-notifications').append('<p class="j2store-wishlist-success text text-success">'+ json['success']+'</p></div>');
								form.find('.j2store-notifications').fadeIn('slow');
							}, 1000);
							location.reload();
					}
			 	});
		 	});

		})(j2store.jQuery);


 }
</script>

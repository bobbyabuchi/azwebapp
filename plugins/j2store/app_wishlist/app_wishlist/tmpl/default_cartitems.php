<?php
/**
 * @package J2Store
 * @copyright Copyright (c)2014-17 Ramesh Elamathi / J2Store.org
 * @license GNU GPL v3 or later
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<style>
.j2store-productwishlist-img{
	width :100px;
}
</style>

<div class="wishlist-cart-item">
		<a class="btn btn-default" href="index.php?option=com_j2store&view=apps&task=view&appTask=listWishlist&id=<?php echo $this->id;?>" >
			<i class="icon icon-backward"></i><?php echo JText::_('J2STORE_BACK')?>
		</a>
	<br/>
	<h4 class="center"><?php echo JText::_('J2STORE_WISHLIST_ITEMS');?></h4>
	<div class="row-fluid">
		<div class="span10">
		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<th>
						<?php echo JText::_('J2STORE_WISHLIST_ID');?>
					</th>
					<th>
						<?php echo JText::_('J2STORE_PRODUCT_NAME');?>
					</th>
					<th>
						<?php echo JText::_('J2STORE_PRODUCT_SKU');?>
					</th>
					<th>
						<?php echo JText::_('J2STORE_QUANTITY');?>
					</th>
					<th>
						<?php echo JText::_('J2STORE_REMOVE');?>
					</th>
				</tr>
			</thead>
			<?php if(!empty($this->cartitems)):?>
			<tbody>
				<?php foreach($this->cartitems as $item):
						$image_path = JUri::root();
						$image_type =  'thumbnail';
						$main_image="";
				?>
				<tr>
					<td>
						<?php echo $item->cart_id;?>
					</td>
					<td>
						<?php echo $item->product_name;?>
						<br/>

						<?php	//check image is exists
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
							<?php	echo J2Store::product()->displayPrice($item->price, $item ,$this->params);?>
						</td>
						<td>
							<?php echo $item->sku;?>
						</td>
						<td><?php echo (int)$item->product_qty;?></td>
						<td>
							<a class="btn btn-danger btn-mini" href="index.php?option=com_j2store&view=apps&task=view&appTask=removeAdminWishlistItems&id=<?php echo $this->id?>&wishlist_id=<?php echo $item->cart_id?>&wishlistitem_id=<?php echo $item->j2store_cartitem_id;?>">
								<i class="icon icon-trash"></i>
							</a>
						</td>
				</tr>
				<?php endforeach;?>
			</tbody>
			<?php else:?>
			<tfooter>
				<tr>
					<td colspan="5"><?php echo JText::_('J2STORE_NO_ITEMS_FOUND');?></td>
				</tr>
			</tfooter>
			<?php endif;?>

		</table>
		</div>
		<div class="span2">
		</div>
	</div>
</div>
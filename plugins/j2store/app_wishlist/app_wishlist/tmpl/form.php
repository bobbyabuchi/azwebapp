<?php
/**
 * @package J2Store
 * @copyright Copyright (c)2014-17 Ramesh Elamathi / J2Store.org
 * @license GNU GPL v3 or later
 */
/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
$wish_url = JRoute::_('index.php');
?>
<div class="product-wishlist">
<?php if(isset($vars->already_exists) && !empty($vars->already_exists)):?>
	<i id="icon-spinner-<?php echo $vars->product->j2store_product_id ?>" class="fa fa-spinner" style="display:none;" ></i>
			   <a    href="<?php echo JRoute::_('index.php?option=com_j2store&view=products&task=wishlist&layout=wishlist');//&Itemid='.$vars->item_id?>"
					    class="product-wishlist-link"
					    data-wishlist-link="<?php echo JRoute::_('index.php?option=com_j2store&view=products&task=wishlist&layout=wishlist');//&Itemid='.$vars->item_id?> "
						data-wishlist-action-done="<?php echo JText::_('J2STORE_ITEM_ADDED_TO_WISHLIST');?>"
						data-wishlist-wishlist-view="<?php echo JText::_($vars->params->get('link_display_text_wishlist','J2STORE_WIHSLIT_BROWSE_WIHSLIST_VIEW'));?>"
						data-wishlist-action-timeout="1000"
						data-wishlist-product-id="<?php echo $vars->product->j2store_product_id ?>"
						data-wishlist-variant-id="<?php echo $vars->product->variant->j2store_variant_id ?>"
						data-wishlist-id="<?php echo $vars->aid; ?>"
						data-enable_redirect="<?php echo $vars->params->get('enable_redirect_to_wishlist',0);?>"
						data-wishlist_link_type="<?php echo $vars->params->get('addwishlist_type' ,'icon');?>"

					    >
					    <?php
					if($vars->params->get('addwishlist_type') =='icon'):?>
						<?php $icons =  $vars->params->get('added_link_display_icon','fa-heart');?>
					   	<span class="fa <?php echo $icons;?> text-danger text-error" ></span>
				   <?php else:?>
					   <?php // echo JText::_('J2STORE_WIHSLIT_BROWSE_WIHSLIST_VIEW');?>
					   <?php echo JText::_($vars->params->get('link_display_text_wishlist','J2STORE_WIHSLIT_BROWSE_WIHSLIST_VIEW'));?>
			   	 <?php endif;?>
					   </a>

<?php else:?>
	<i id="icon-spinner-<?php echo $vars->product->j2store_product_id ?>" class="fa fa-spinner" style="display:none;" ></i>
			   <a		    href="javascript:void(0);"
					    class="product-wishlist-link"
					    data-wishlist-link="<?php echo JRoute::_('index.php?option=com_j2store&view=products&task=wishlist&layout=wishlist');//&Itemid='.$vars->item_id?> "
						data-wishlist-action-done="<?php echo JText::_('J2STORE_ITEM_ADDED_TO_WISHLIST');?>"
						data-wishlist-wishlist-view="<?php echo JText::_($vars->params->get('link_display_text_wishlist','J2STORE_WIHSLIT_BROWSE_WIHSLIST_VIEW'));?>"
						data-wishlist-action-timeout="1000"
						data-wishlist-product-id="<?php echo $vars->product->j2store_product_id ?>"
						data-wishlist-variant-id="<?php echo $vars->product->variant->j2store_variant_id ?>"
						data-wishlist-id="<?php echo $vars->aid; ?>"
						data-enable_redirect="<?php echo $vars->params->get('enable_redirect_to_wishlist',0);?>"
						data-wishlist_link_type="<?php echo $vars->params->get('addwishlist_type' ,'icon');?>"
					    onClick="addToWishlist(this,'<?php echo $wish_url;?>')"
					    >


					<?php
					if($vars->params->get('addwishlist_type') =='icon'):?>
						<?php $icons =  $vars->params->get('link_display_icon','fa-heart');?>
					   	<span class="fa <?php echo $icons;?>" ></span>
				   <?php else:?>
					   	<?php echo JText::_($vars->params->get('link_display_text','J2STORE_ADD_TO_WISHLIST'));?>
			   	 <?php endif;?>
		</a>
		<?php endif;?>
</div>

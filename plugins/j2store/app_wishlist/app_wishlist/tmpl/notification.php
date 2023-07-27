<?php
/**
 * @package J2Store
 * @copyright Copyright (c)2014-17 Ramesh Elamathi / J2Store.org
 * @license GNU GPL v3 or later
 */
/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
?>

	<div class="j2store-wishlist-notification">
	<?php if($vars->success): ?>
		<p class="text-success">
			<?php if($vars->params->get('show_message_after_item_added',0)):?>
				<?php echo JText::_($vars->params->get('message_after_item_added'),'J2STORE_ITEM_ADDED_TO_WISHLIST');?>
			<?php endif;?>

				<a class="j2store-checkout-link"
						href="<?php echo $vars->link; ?>">
						<?php echo  JText::_('J2STORE_VIEW_WISHLIST');?>
				</a>
		</p>
	<?php else: ?>
		<p class="text-error">
			<?php echo JText::_('J2STORE_WISHLIST_ERROR_WHILE_ADDING_TO_WISHLIST'); ?>
		</p>
	<?php endif; ?>
	</div>
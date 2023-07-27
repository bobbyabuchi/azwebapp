<?php
/*------------------------------------------------------------------------
# mod_j2store_wishlist - J2 Store Wishlist
# ------------------------------------------------------------------------
# author    Gokila Priya - Weblogicx India http://www.weblogicxindia.com
# copyright Copyright (C) 2014 - 19 Weblogicxindia.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://j2store.org
# Technical Support:  Forum - http://j2store.org/forum/index.html
-------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die('Restricted access');
$app = JFactory::getApplication();
$ajax = $app->getUserState('mod_j2store_wishlist.isAjax');
$hide = false;

if($list['product_count'] < 1) {
	$hide = true;
}
?>
<?php if(!$ajax): ?>
	<div  class="j2store_wishlist_module_<?php echo $module->id; ?>">
	<?php if($list['product_count']< 1): ?>
	<i class="fa fa-heart-o"></i>
	<span class="count">0</span>
	<?php endif; ?>
<?php endif; ?>
<?php if(!$hide): ?>
	<?php if($list['product_count'] > 0): ?>
		<a class="link" href="<?php echo JRoute::_('index.php?option=com_j2store&view=products&task=wishlist&layout=wishlist');?>">
			<i class="fa fa-heart-o"></i>
			<span class="count"><?php echo JText::sprintf($list['product_count']);?></span>
		</a>
	<?php else : ?>
		<?php echo JText::_('J2STORE_NO_ITEMS_IN_WISHLIST'); ?>
	<?php endif; ?>
<?php endif; ?>
<?php if(!$ajax):?>
</div>
<?php else: ?>
	<?php $app->setUserState('mod_j2store_wishlist.isAjax', 0); ?>
<?php endif; ?>
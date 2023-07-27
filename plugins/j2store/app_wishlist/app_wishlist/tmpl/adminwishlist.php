<?php
/**
 * @package J2Store
 * @copyright Copyright (c)2014-17 Ramesh Elamathi / J2Store.org
 * @license GNU GPL v3 or later
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.framework');
JHtml::_('behavior.modal');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('script', 'media/j2store/js/j2store.js', false, false);
?>
<div class="j2store-wishlist">
	<form class="form-horizontal" id="adminForm" name="adminForm" method="post" action="index.php">
		<?php echo J2Html::hidden('option','com_j2store');?>
		<?php echo J2Html::hidden('view','apps');?>
		<?php echo J2Html::hidden('app_id',$this->id);?>
		<?php echo J2Html::hidden('id',$this->id);?>
		<?php echo J2Html::hidden('appTask', 'listWishlist', array('id'=>'appTask'));?>
		<?php echo J2Html::hidden('task', 'view', array('id'=>'task'));?>
		<?php echo J2Html::hidden('boxchecked','0');?>
		<?php echo J2Html::hidden('filter_order','');?>
		<?php echo J2Html::hidden('filter_order_Dir','');?>
		<?php echo JHtml::_('form.token'); ?>
		<a class="btn btn-default" href="index.php?option=com_j2store&view=apps&task=view&layout=view&id=<?php echo $this->id;?>" >
			<i class="icon icon-backward"></i><?php echo JText::_('J2STORE_BACK')?>
		</a>
		<h4 class="center"><?php echo JText::_('J2STORE_WISHLIST');?></h4>
		<table class="adminlist table table-striped table-bordered table-condensed">
			<tr>
				<td colspan="4">
					<?php  echo  J2Html::button('go',JText::_( 'J2STORE_APPLY_FILTER' ) ,array('class'=>'btn btn-success','onclick'=>'this.form.submit();'));?>
					<?php echo J2Html::button('reset',JText::_( 'J2STORE_FILTER_RESET_ALL' ),array('id'=>'reset-filter','class'=>'btn btn-inverse' ,'onclick'=>'resetAdvancedFilters();'));?>
					<div class="pull-right">
						<?php echo $this->cart_pagiantion->getLimitBox();?>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<?php $search = htmlspecialchars($this->cart_state->filter_search);?>
					<div class="input-prepend">
						<span class="add-on"><?php echo JText::_( 'J2STORE_FILTER_SEARCH' ); ?></span>
						<?php echo  J2Html::text('filter_search',$search,array('id'=>'search' ,'class'=>'input j2store-wishlist-filters'));?>
					</div>
					<?php echo J2Html::button('go',JText::_( 'J2STORE_FILTER_GO' ) ,array('class'=>'btn btn-success','onclick'=>'this.form.submit();'));?>
					<?php echo J2Html::button('reset',JText::_( 'J2STORE_FILTER_RESET' ),array('id'=>'reset-filter-search','class'=>'btn btn-inverse',"onclick"=>"jQuery('#search').attr('value','');this.form.submit();"));?>
				</td>
				<td>
					<?php echo JText::_( 'J2STORE_CUSTOMER' ); ?>:
					<?php
					$this->cart_state->filter_user = isset($this->cart_state->filter_user) ? $this->cart_state->filter_user : '' ;
					$user_name = '';
					if($this->cart_state->filter_user){
						$user_name=J2Html::getUserNameById($this->cart_state->filter_user);
					}
					?>

					<div class="input-append">
						<input type="text"  class="input-small"  name="user_name" value="<?php echo $user_name;?>" id="jform_user_id_name" readonly="true" aria-invalid="false" />
						<input type="hidden" onchange="this.form.submit();" name="filter_user" value="" id="jform_user_id" class="j2store-order-filters"  readonly="true"/>
						<?php $url ='index.php?option=com_users&view=users&layout=modal&tmpl=component&field=jform_user_id';?>
						<?php echo J2StorePopup::popup($url,'<i class="icon icon-user"></i>', array('class'=>'btn btn-primary modal_jform_created_by'));?>
					</div>
					<?php  echo  J2Html::button('go',JText::_( 'J2STORE_FILTER_GO' ) ,array('class'=>'btn btn-success','onclick'=>'this.form.submit();'));?>
					<?php  echo  J2Html::button('reset',JText::_( 'J2STORE_FILTER_RESET' ),array('id'=>'reset-filter-user','class'=>'btn btn-inverse',"onclick"=>"jQuery('#jform_user_id').attr('value','');this.form.submit();"));?>
				</td>
				<td>
					<?php echo JText::_('J2STORE_FROM');?>
					<?php echo J2Html::calendar('filter_date_from',$this->cart_state->filter_date_from,array('class'=>'input-small j2store-wishlist-filters'));?>
				</td>
				<td>
					<?php echo JText::_('J2STORE_TO');?>
					<?php echo J2Html::calendar('filter_date_to',$this->cart_state->filter_date_to,array('class'=>'input-small j2store-wishlist-filters'));?>
				</td>
			<tr/>
		</table>
		<div class="span12">
			<span class="pull-right"><?php echo J2Html::button('deleteall',JText::_( 'J2STORE_DELETE' ) ,array('class'=>'btn btn-danger','onclick'=>'jQuery(\'#appTask\').attr(\'value\',\'bulkdelete\');this.form.submit();'));?>
			</span>
		</div>
		<table class="table table-striped">
			<thead>
			<tr>
				<th width="20">
					<input type="checkbox" name="checkall-toggle"  value=""
						   title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>"
						   onclick="Joomla.checkAll(this)" />
				</th>
				<th><?php  echo JHTML::_('grid.sort',  'J2STORE_WISHLIST_ID', 'tbl.j2store_cart_id', $this->cart_state->filter_order_Dir,$this->cart_state->filter_order  ); ?></th>
				<th><?php echo JHTML::_('grid.sort',  'J2STORE_CUSTOMER_NAME', 'u.username', $this->cart_state->filter_order_Dir,$this->cart_state->filter_order  ); ?></th>
				<th><?php echo JHTML::_('grid.sort',  'J2STORE_CUSTOMER_EMAIL', 'u.email', $this->cart_state->filter_order_Dir,$this->cart_state->filter_order  ); ?></th>
				<th><?php echo JHTML::_('grid.sort',  'J2STORE_ORDER_DATE', 'tbl.created_on', $this->cart_state->filter_order_Dir,$this->cart_state->filter_order  ); ?></th>
				<th><?php echo JText::_('J2STORE_TOTAL_ITEMS');?>
				<th><?php echo JHTML::_('grid.sort',  'J2STORE_CART_BROWSER', 'tbl.cart_browser', $this->cart_state->filter_order_Dir,$this->cart_state->filter_order  ); ?></th>
				<th><?php echo JText::_('J2STORE_CUSTOMER_IP');?></th>
			</tr>
			</thead>
			<?php if(!empty($this->cart_items)):?>
				<tbody>

				<?php
				$i = 0;
				foreach($this->cart_items as $item):?>
					<?php
					$link = 'index.php?option=com_j2store&view=apps&task=view&appTask=wishlistitems&id='.$this->id.'&wishlist_id='.$item->j2store_cart_id;
					$checked = JHTML::_('grid.id', $i, $item->j2store_cart_id );
					?>
					<tr>
						<td><?php echo $checked ?></td>
						<td>
							<a href="<?php echo $link; ?>">
								<?php echo $item->j2store_cart_id;?>
							</a>
						</td>
						<td>
							<?php 	if(!empty($item->user_id) && $item->user_id > 0 ):?>
								<?php echo JText::_('J2STORE_ADDRESS_USERNAME')?> : <?php echo $item->username; ?>
								<br/>
								<?php echo JText::_('J2STORE_ADDRESS_USER_ID')?> : <?php echo $item->user_id; ?>
								<br/>
								<?php echo JText::_('J2STORE_CUSTOMER_NAME')?> 	: <?php echo $item->name;?>
							<?php else:?>
								<?php echo JText::_('J2STORE_GUEST'); ?>
							<?php endif;?>
						</td>
						<td><?php echo $item->email;?></td>
						<td><?php  echo JHTML::_('date',$item->created_on, $this->params->get('date_format', JText::_('DATE_FORMAT_LC1'))); ?></td>
						<td>
							<a href="<?php echo $link; ?>">
								<?php echo $item->totalitems;?>
							</a>
						</td>
						<td><?php echo  $item->cart_browser;?></td>
						<td><?php echo  $item->customer_ip;?></td>
						<td><a class="btn btn-danger btn-mini" href="index.php?option=com_j2store&view=apps&task=view&appTask=removeWishlist&id=<?php echo $this->id?>&wishlist_id=<?php echo $item->j2store_cart_id?>"><i class="icon icon-trash"></i></a></td>
					</tr>
					<?php $i++;?>
				<?php endforeach;?>
				</tbody>
				<tfooter>
					<tr>
						<td colspan="6">
							<?php  echo $this->cart_pagiantion->getListFooter();?>
						</td>
					</tr>
				</tfooter>
			<?php else:?>
				<tfooter>
					<tr>
						<td colspan="6">
							<?php echo JText::_('J2STORE_NO_ITEMS_FOUND');?>
						</td>
					</tr>
				</tfooter>
			<?php endif;?>
		</table>
	</form>
</div>
<script type="text/javascript">
	function jSelectUser_jform_user_id(id, title) {
		var old_id = document.getElementById('jform_user_id').value;
		document.getElementById('jform_user_id').value = id;
		document.getElementById('jform_user_id_name').value = title;
		document.getElementById('jform_user_id').className = document.getElementById('jform_user_id').className.replace();
		SqueezeBox.close();
	};


	function resetAdvancedFilters(){
		jQuery(".j2store-wishlist-filters").each(function() {
			jQuery(this).attr('value','');
		});
		jQuery("#adminForm").submit();
	}

</script>

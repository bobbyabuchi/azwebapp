<?php
/**
 * @package J2Store
 * @copyright Copyright (c)2014-17 Ramesh Elamathi / J2Store.org
 * @license GNU GPL v3 or later
 */
/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
require_once(JPATH_ADMINISTRATOR.'/components/com_j2store/library/plugins/app.php');

class plgJ2StoreApp_wishlist extends J2StoreAppPlugin
{
	/**
	 * @var $_element  string  Should always correspond with the plugin's filename,
	 *                         forcing it to be unique
	 */
	var $_element   = 'app_wishlist';
	var $_wishlist = null;

	/**
	 * Overriding
	 *
	 * @param $options
	 * @return unknown_type
	 */
	function onJ2StoreGetAppView( $row )
	{

		if (!$this->_isMe($row))
		{
			return null;
		}

		$html = $this->viewList();


		return $html;
	}

	/**
	 * Validates the data submitted based on the suffix provided
	 * A controller for this plugin, you could say
	 *
	 * @param $task
	 * @return html
	 */
	function viewList()
	{
		$app = JFactory::getApplication();
		$option = 'com_j2store';
		$ns = $option.'.app.'.$this->_element;
		$html = "";
		JToolBarHelper::title(JText::_('J2STORE_APP').'-'.JText::_('PLG_J2STORE_'.strtoupper($this->_element)),'j2store-logo');
		JToolBarHelper::apply('apply');
		JToolBarHelper::save();
		JToolBarHelper::back('PLG_J2STORE_BACK_TO_APPS', 'index.php?option=com_j2store&view=apps');
		JToolBarHelper::back('J2STORE_BACK_TO_DASHBOARD', 'index.php?option=com_j2store');
		JToolBarHelper::back('J2STORE_WISHLIST', 'index.php?option=com_j2store&view=apps&task=view&appTask=listWishlist&id='.$app->input->getInt('id'));

		$vars = new JObject();
		//model should always be a plural
		$this->includeCustomModel('AppWishlist');

		$model = F0FModel::getTmpInstance('AppWishlist', 'J2StoreModel');

		$data = $this->params->toArray();
		$newdata = array();
		$newdata['params'] = $data;
		$form = $model->getForm($newdata);
		$vars->form = $form;

		$this->includeCustomTables();

		$id = $app->input->getInt('id', '0');
		$vars->id = $id;
		$vars->action = "index.php?option=com_j2store&view=app&task=view&id={$id}";
		return $this->_getLayout('default', $vars);

	}

	public function onJ2StoreAfterAddToCartButton($product) {
		$app = JFactory::getApplication();
		if($app->isAdmin()) return '';
		$doc = JFactory::getDocument();
		$doc->addScript(JUri::root(true).'/plugins/j2store/'.$this->_element.'/'.$this->_element.'/assets/wishlist.js');
		$vars = new JObject();
		$table = F0FTable::getInstance('App', 'J2StoreTable');
		$table->load(array('element'=>$this->_element));
		$vars->aid = $table->extension_id;
		//model should always be a plural
		$this->includeCustomModel('AppWishlist');
		$model = F0FModel::getTmpInstance('AppWishlist', 'J2StoreModel');
		$items = $model->getItems();
		$vars->already_exists  = false;
		if($items){
			foreach($items as $item){
				if($item->product_id == $product->j2store_product_id && $item->variant_id == $product->variant->j2store_variant_id ){
					$vars->already_exists = true;
				}
			}
		}

		$vars->product = $product;
		$vars->params = $this->params;
		return  $this->_getLayout('form', $vars);
	}
	public static function findProductMenu($qoptions) {

		$menus =JMenu::getInstance('site');
		$menu_id = null;
		$other_tasks = array('wishlist');
		foreach($menus->getMenu() as $item)
		{
			if(isset($item->query['view']) && $item->query['view']=='products') {
				if (isset($item->query['task']) && !empty($item->query['task']) && in_array($item->query['task'] , $other_tasks) ){
					$menu_id =$item->id;
					break;
				}
				if(self::checkMenuProducts($item, $qoptions)) {
					$menu_id =$item->id;
					//break on first found menu
					break;
				}
			}

		}
		return $menu_id;

	}
	public function onJ2StoreAfterAddingToWishlist($result) {
		$app = JFactory::getApplication();
		$registry = new JRegistry();
		if(is_object($result)) {
			$registry->loadObject($result);
			$json = $registry->toArray();
		} elseif(is_array($result)) {
			$json = $result;
		}else {
			$json = $result;
		}


		$vars = new JObject();
		$vars->item_id = $app->getMenu()->getActive()->id;
		$vars->link = JRoute::_('index.php?option=com_j2store&view=products&task=wishlist&Itemid='.$vars->item_id);
		$vars->success = 0;
		if(isset($json['success']) && $json['success'] == 1 ) {
			$vars->success = 1;
		}

		$vars->params = $this->params;
		$html = $this->_getLayout('notification', $vars);
		$json['resulthtml'] = $html;
		return $json;
	}

	/**
	 * Method to provide html for displaying wishlist layout
	 */
	public function onJ2StoreWishlistProductHtml() {
		$this->includeCustomModel('AppWishlist');
		$model = F0FModel::getTmpInstance('AppWishlist', 'J2StoreModel');
		$app = JFactory::getApplication();
		$vars = new JObject();
		$vars->params = J2Store::config();
		$product_helper = J2Store::product();
		$wishlist_items = $model->getItems();
		$productmodel = F0FModel::getTmpInstance('Products', 'J2StoreModel');
		foreach ($wishlist_items as $item){
			$price = $item->pricing->price + $item->option_price;
			$item->item_price =  $product_helper->displayPrice($price, $item ,$vars->params );
		}
		$vars->products = $wishlist_items;
		$vars->plugin_params = $this->params;
		$vars->continue_shopping_url = F0FModel::getTmpInstance('Carts','J2StoreModel')->getContinueShoppingUrl();
		$vars->cart_url = F0FModel::getTmpInstance('Carts','J2StoreModel')->getCartUrl();

		$table = F0FTable::getInstance('App', 'J2StoreTable');
		$table->load(array('element'=>$this->_element));
		$vars->aid = $table->extension_id;
		$active_menu	= $app->getMenu()->getActive();
		$menu_id = 0;
		if($active_menu){
			$menu_id  = $active_menu->id;
		}
		$item_id = $app->input->getInt('Itemid',0);
		$vars->item_id = !empty($item_id) ? $item_id:$menu_id;
		//$vars->item_id = $app->getMenu()->getActive()->id;
		return $this->_getLayout('wishlist', $vars);

	}

	/**
	 * Method resets the wishlist when a customer logs in
	 * @param int $session_id
	 * @param int $user_id
	 */

	public function onJ2StoreBeforeResetCart($session_id, $user_id) {
		$model = F0FModel::getTmpInstance('Carts', 'J2StoreModel');
		$model->setState( 'filter_session', $session_id );
		$model->setState( 'filter_cart_type', 'wishlist');
		$this->_wishlist = $model->loadCart();
	}

	public function onJ2StoreAfterResetCart($session_id, $user_id) {
		$cart_helper = J2Store::cart();
		$cart_helper->resetCartTable($this->_wishlist, $session_id, $user_id, 'wishlist');
	}

}

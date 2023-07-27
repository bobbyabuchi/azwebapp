<?php
/**
 * @package J2Store
 * @copyright Copyright (c)2014-17 Ramesh Elamathi / J2Store.org
 * @license GNU GPL v3 or later
 */
/**
 * ensure this file is being included by a parent file
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

require_once (JPATH_ADMINISTRATOR . '/components/com_j2store/library/appcontroller.php');
class J2StoreControllerAppWishlist extends J2StoreAppController {
	var $_element = 'app_wishlist';

	function __construct($config = array()) {
		parent::__construct ( $config );
		//there is problem in loading of language
		//this code will fix the language loading problem
		$language = JFactory::getLanguage();
		$extension = 'plg_j2store'.'_'. $this->_element;
		$language->load($extension, JPATH_ADMINISTRATOR, 'en-GB', true);
		$language->load($extension, JPATH_ADMINISTRATOR, null, true);
	}

	protected function onBeforeGenericTask($task)
	{
		$privilege = $this->configProvider->get(
			$this->component . '.views.' .
			F0FInflector::singularize($this->view) . '.acl.' . $task, ''
		);

		return $this->allowedTasks($task);
	}

	function allowedTasks($task) {
		$allowed = array('addWishlist' ,
			'removeWishlist',
			'updateWishlist',
			'addwishlistItemToCart',
			'listWishlist',
			'wishlistitems',
			'removeWishlistItems',
			'removeAdminWishlistItems',
			'bulkdelete'
		);
		if(in_array($task, $allowed)) {
			return true;
		}
		return false;
	}

	function removeWishlist(){
		$app = JFactory::getApplication();
		if($app->isAdmin() ){
			$id = $app->input->getInt('id');
			$wishlist_id = $app->input->getInt('wishlist_id');
			$wishlist = F0FTable::getInstance ( 'Cart', 'J2StoreTable' );
			$user = JFactory::getUser();
			$msg = JText::_('J2STORE_WISHLIST_DELETE_SUCCESS');
			//check user is super user
			if($user->authorise('core.admin') && $wishlist->load($wishlist_id)){
				if($wishlist->delete($wishlist_id)){
					$msg = JText::_('J2STORE_WISHLIST_DELETE_SUCCESS');
				}
			}else{
				$msg = JText::_('J2STORE_WISHLIST_DELETE_ERROR');
			}
			$app->redirect('index.php?option=com_j2store&view=apps&task=view&appTask=listWishlist&id='.$id,$msg,'message');
		}else{

			return false;
		}

	}

	/**
	 * Method to remove the wishlistitem items from the frontend
	 */
	function removeWishlistItems(){
		$errors = array();
		$json =array();

		$app = JFactory::getApplication();
		$sesssion = JFactory::getSession();

		$id = $app->input->getInt('id');
		$wishlistitem_id = $app->input->getInt('wishlistitem_id');

		$wishlist_id = $this->input->getInt('wishlist_id');

		$model = F0FModel::getTmpInstance('Carts','J2StoreModel');
		$wishlist = F0FTable::getInstance ( 'Cart', 'J2StoreTable' );

		$wishlistitem = F0FTable::getInstance ( 'Cartitem', 'J2StoreTable' );

		// laod  the item
		if($wishlist->load($wishlist_id) && $wishlistitem->load($wishlistitem_id)){
			//check valid user to delete the wishlistitem
			if(!empty($wishlist->user_id) && JFactory::getUser()->id == $wishlist->user_id  ){
				if(!$this->deleteWishlistItem($wishlist_id, $wishlistitem_id)){
					$json['error'] =  JText::_ ( 'J2STORE_WISHLIST_DELETE_ERROR' );
				}else{
					$json['success'] = true;
				}
			}elseif(empty(JFactory::getUser()->id) && !empty($wishlist->session_id) && JFactory::getSession()->getId() == $wishlist->session_id ){
				if(!$this->deleteWishlistItem($wishlist_id, $wishlistitem_id)){
					$json['error'] =  JText::_ ( 'J2STORE_WISHLIST_DELETE_ERROR' );
				}else{
					$json['success'] = true;
				}
			}
		}else{
			$json['error'] 	= JText::_('J2STORE_INVALID_USER');
		}
		echo json_encode($json);
		$app->close();
	}

	/**
	 * Method to remove wishlist items by the super user
	 */
	function removeAdminWishlistItems(){
		$app = JFactory::getApplication();
		if($app->isAdmin() ){
			$sesssion = JFactory::getSession();
			$json =array();
			$id = $app->input->getInt('id');
			$wishlist_id = $this->input->getInt('wishlist_id');
			$wishlistitem_id = $app->input->getInt('wishlistitem_id');
			$wishlist = F0FTable::getInstance ( 'Cart', 'J2StoreTable' );
			$wishlistitem = F0FTable::getInstance ( 'Cartitem', 'J2StoreTable' );
			$user = JFactory::getUser();
			$msg = JText::_('J2STORE_PRODUCT_REMOVED_FROM_WISHLIST');
			//check user is super user
			if($user->authorise('core.admin') && $wishlist->load($wishlist_id) && $wishlistitem->load($wishlistitem_id)){
				if($wishlistitem->cart_id != $wishlist_id) {
					$msg =  JText::_ ( 'J2STORE_CART_DELETE_ERROR' );
				}else{
					if(!$this->deleteWishlistItem($wishlist_id, $wishlistitem_id)){
						$msg =  JText::_ ( 'J2STORE_WISHLIST_DELETE_ERROR' );
					}

					/* if (!$wishlistitem->delete ($wishlistitem_id )) {

					} */
				}
			}
			$app->redirect('index.php?option=com_j2store&view=apps&task=view&appTask=wishlistitems&id='.$id.'&wishlist_id='.$wishlist_id ,$msg,'message');
		}else{
			return false;
		}


	}

	/**
	 * Method to update wishlist
	 */
	function updateWishlist(){
		$app = JFactory::getApplication();
		$json = array();
		$wishlist_id = $app->input->getInt('j2store_cartitem_id');
		$product_qty = $app->input->getInt('product_qty');
		if(!empty($wishlist_id)  && !empty($product_qty)){
			$cartitem = F0FTable::getInstance ( 'Cartitem', 'J2StoreTable' );
			if($cartitem->load($wishlist_id)){
				$cartitem->product_qty = $product_qty;
				if($cartitem->store ()){
					$json['success'] = JText::_('J2STORE_WISHLIST_UPDATE_SUCCESS');
				}
			}else{
				$json['error'] = JText::_('J2STORE_WISHLIST_UPDATE_ERROR');
			}
		}
		echo json_encode($json);
		$app->close();
	}

	function addwishlistItemToCart(){
		$app = JFactory::getApplication();
		$json = array();
		$wishlist_id = $app->input->getInt('j2store_cartitem_id');
		$model = F0FModel::getTmpInstance('Carts','J2StoreModel');
		$item = F0FModel::getTmpInstance('Cartitems','J2StoreModel')->getItem($wishlist_id);

		//set the input
		$app->input->set('product_id',$item->product_id);
		$model->setInput(array('product_id'=>$item->product_id));
		$json = $model->addCartItem();
		if($json){
			echo json_encode($json);
			$app->close();
		}
	}

	function listWishlist(){
		$app = JFactory::getApplication();
		$this->removeEmptyCart();
		$cart_model = F0FModel::getTmpInstance('Carts', 'J2StoreModel');
		$cart_model->setCartType('wishlist');
		$cart_model->set('limit',$this->input->getInt('limit'));
		$cart_model->set('limitstart',$this->input->getInt('limitstart'));
		$cart_model->setState('filter_usertable_join', true);
		$state = $this->getFilterStates();
		foreach($state as $key => $value){
			if($key == 'filter_user'){
				if($value > 0){
					$cart_model->setState($key,$value);
				}
			}else{
				$cart_model->setState($key,$value);
			}
		}
		$items = $cart_model->getList();
		$view = $this->getView( 'Apps', 'html' );
		$view->setModel( F0FModel::getTmpInstance( 'Apps' ,'J2StoreModel'), true );
		$view->addTemplatePath(JPATH_SITE.'/plugins/j2store/'.$this->_element.'/'.$this->_element.'/tmpl');
		$view->set('cart_items', $items);
		$view->set('cart_model',$cart_model);
		$view->set('cart_pagiantion',$cart_model->getPagination());
		$view->set('cart_state' ,$cart_model->getState());
		$view->set('id',$this->input->get('id',0));
		$view->set('task','view');
		$view->set('params',J2Store::config());
		$view->setLayout('adminwishlist');
		$view->display();
	}

	function removeEmptyCart(){
		$cart_model = F0FModel::getTmpInstance('Carts', 'J2StoreModel')->getClone ();
		$cart_model->setCartType('wishlist');
		$citems = $cart_model->getList();
		foreach ($citems as $item){
			if($item->totalitems == 0){
				$cart_table = F0FTable::getInstance ( 'Cart', 'J2StoreTable' )->getClone ();
				$cart_table->delete ($item->j2store_cart_id);
			}
		}
	}
	
	function wishlistitems(){
		$app = JFactory::getApplication();
		$cart_id = $this->input->getInt('wishlist_id');
		$cart_model = F0FModel::getTmpInstance('Cartitems', 'J2StoreModel');
		//	$cart_model->setCartType('wishlist');
		$cart_model->setState('filter_cart', $cart_id);
		$citems = $cart_model->getList();

		$view = $this->getView( 'Apps', 'html' );
		$view->setModel( F0FModel::getTmpInstance( 'Apps' ,'J2StoreModel'), true );
		$view->addTemplatePath(JPATH_SITE.'/plugins/j2store/'.$this->_element.'/'.$this->_element.'/tmpl');
		$view->set('cartitems', $citems);
		$view->set('id',$this->input->get('id',0));
		$view->set('params',J2Store::config());
		$view->setLayout('default_cartitems');
		$view->display();
	}

	public function getFilterStates() {
		$app = JFactory::getApplication();
		$state = array(
			'filter_search' => $app->input->getString('filter_search',''),
			'filter_user'=> $app->input->getInt('filter_user',''),
			'filter_date_from'=> $app->input->getString('filter_date_from',''),
			'filter_date_to' => $app->input->getString('filter_date_to',''),
			'filter_order' => $app->input->getString('filter_order','j2store_cart_id'),
			'filter_order_Dir'=> $app->input->getString('filter_order_Dir','ASC')
		);

		return $state;
	}

	public function deleteWishlistItem($wishlist_id , $wishlistitem_id){
		$status = true;
		if(empty($wishlist_id) || empty($wishlistitem_id)) $status = false;
		$wishlistitem = F0FTable::getInstance ( 'Cartitem', 'J2StoreTable' );
		if ($wishlistitem->load ( $wishlistitem_id )) {
			if($wishlistitem->cart_id != $wishlist_id) {
				$status = false;
			}
			if($status){
				$item = new JObject ();
				$item->product_id = $wishlistitem->product_id;
				$item->variant_id = $wishlistitem->variant_id;
				$item->product_options = $wishlistitem->product_options;
				if(!$wishlistitem->delete ($wishlistitem_id )) {
					$status = false;
				}
				/* J2Store::plugin()->event( 'RemoveFromCart', array (	$item) );
			}else{
				$errors[] =  JText::_ ( 'J2STORE_WISHLIST_DELETE_ERROR' );
			} */
			}
		}
		return $status;
	}

	function bulkdelete(){
		$app = JFactory::getApplication();
		$cart_ids = $app->input->get('cid',array());
		$id = $app->input->getInt('id',0);
		if(!empty( $cart_ids )){
			foreach ($cart_ids as $cart_id){
				$cart_table = F0FTable::getInstance ( 'Cart', 'J2StoreTable' )->getClone ();
				$cart_table->delete ($cart_id);
			}
			$msg = JText::_('J2STORE_WISHLIST_DELETE_ERROR');
			$app->redirect('index.php?option=com_j2store&view=apps&task=view&appTask=listWishlist&id='.$id,$msg,'message');
		}else{
			$msg = JText::_('J2STORE_WISHLIST_SLECT_DELETE_LIST');
			$app->redirect('index.php?option=com_j2store&view=apps&task=view&appTask=listWishlist&id='.$id,$msg,'error');
		}

	}
}


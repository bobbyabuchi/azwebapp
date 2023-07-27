<?php
/**
 * @package J2Store
 * @copyright Copyright (c)2014-17 Ramesh Elamathi / J2Store.org
 * @license GNU GPL v3 or later
 */
/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
require_once (JPATH_ADMINISTRATOR . '/components/com_j2store/library/appmodel.php');
class J2StoreModelAppWishlist extends J2StoreAppModel
{
	public $_element = 'app_wishlist';


	public function getItems() {
		$cart_model = F0FModel::getTmpInstance('Carts', 'J2StoreModel');
		$cart_model->setCartType('wishlist');
		$items = $cart_model->getItems();
		J2Store::plugin()->event('AfterGetWishlistItems', array(&$items));
		return $items;
	}

	public function getWishlist() {
		$cart_model = F0FModel::getTmpInstance('Carts', 'J2StoreModel');
		$cart_model->setCartType('wishlist');
		$wishlist = $cart_model->getCart();
		return $wishlist;
	}


	public function deleteWishlistItem($wishlist_id , $wishlistitem_id){
		$errors = array();
		if(empty($wishlist_id) || empty($wishlistitem_id)) return false;
		$wishlistitem = F0FTable::getInstance ( 'Cartitem', 'J2StoreTable' );
		if ($wishlistitem->load ( $wishlistitem_id )) {
				if($wishlistitem->cart_id != F0FModel::getTmpInstance('Carts','J2StoreModel')->getCartId()) {
					$errors[] =  JText::_ ( 'J2STORE_CART_DELETE_ERROR' );
				}elseif(empty($errors)){
					$item = new JObject ();
					$item->product_id = $wishlistitem->product_id;
					$item->variant_id = $wishlistitem->variant_id;
					$item->product_options = $wishlistitem->product_options;
					if ($cartitem->delete ($wishlist_id )) {
						J2Store::plugin()->event( 'RemoveFromCart', array (	$item) );
					}else{
						$errors[] =  JText::_ ( 'J2STORE_WISHLIST_DELETE_ERROR' );
					}
			}
		}
		return $errors;
	}

}
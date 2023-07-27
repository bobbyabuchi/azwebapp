<?php
/**
 * @package J2Store
 * @copyright Copyright (c)2014-17 Ramesh Elamathi / J2Store.org
 * @license GNU GPL v3 or later
 */
/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
class J2StoreTableWishlist extends F0FTable
{
	public function __construct($table, $key, $db,$config = array())
  {

        $tbl_key    = 'j2store_wishlist_id';//'shipping_postcodemethod_id';
        $tbl_suffix = '#__j2store_wishlists';

        parent::__construct( $tbl_suffix, $tbl_key, $db );
    }

    public function check()
    {

    	$result = parent::check();
    	//need to check product id is not empty
    	if(!$this->product_id){
    		$this->setError(JText::_('J2STORE_APP_WISHLIST_PRODUCT_ID_MISSING'));
    		$result =  false;
    	}

    	//need to check variant id  is not empty
    	if(empty($this->variant_id)){
    		$this->setError(JText::_('J2STORE_APP_WISHLIST_VARIANT_ID_MISSING'));
    		$result =  false;
    	}
    	//check session id exists
    	if(empty($this->session_id)){
    		$this->session_id = JFactory::getSession()->getId();
    	}
    	//to check similar items in the same user id exists
    	if(!$this->checkItemExists($this->product_id , $this->variant_id , $this->user_id ,$this->product_options)){
    		$this->setError(JText::_('J2STORE_WISHLIST_ITEM_ALREADY_EXISTS'));
    		$result = false;
    	}

    	return $result;

    }

    protected function checkItemExists($product_id , $variant_id , $user_id , $product_options)
    {
    	$return = true;
		$db = JFactory::getDbo();
		// Make sure we don't have a duplicate Item already exists in the table this table
		$query = $db->getQuery(true)
				->select('*')->from($this->_tbl)
				->where('product_id' . ' = ' . $db->q($product_id))
				->where('variant_id' . ' = ' . $db->q($variant_id))
				->where('user_id' . ' = ' . $db->q($user_id))
				->where('product_options' . '='. $db->q($product_options));
			$db->setQuery($query);
			$existingItems = $db->loadAssocList();
			if($existingItems){
				$return = false;
			}
			return $return;
    }




}

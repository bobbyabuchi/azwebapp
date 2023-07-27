<?php
/**
 * --------------------------------------------------------------------------------
 * App Plugin - Flexible Variable
 * --------------------------------------------------------------------------------
 * @package     Joomla  3.x
 * @subpackage  J2 Store
 * @author      Alagesan, J2Store <support@j2store.org>
 * @copyright   Copyright (c) 2018 J2Store . All rights reserved.
 * @license     GNU/GPL V3 or later
 * @link        http://j2store.org
 * --------------------------------------------------------------------------------
 *
 * */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
require_once(JPATH_ADMINISTRATOR.'/components/com_j2store/library/plugins/app.php');
require_once(JPATH_ADMINISTRATOR.'/components/com_j2store/helpers/j2store.php');
class plgJ2StoreApp_flexivariable extends J2StoreAppPlugin {

    /**
     * @var $_element  string  Should always correspond with the plugin's filename,
     *                         forcing it to be unique
     */
    var $_element   = 'app_flexivariable';
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
        $id = $app->input->getInt('id', '0');
        JToolBarHelper::title(JText::_('J2STORE_APP').'-'.JText::_('PLG_J2STORE_'.strtoupper($this->_element)),'j2store-logo');
        JToolBarHelper::back('J2STORE_BACK_TO_DASHBOARD', 'index.php?option=com_j2store');
        JToolBarHelper::back('PLG_J2STORE_BACK_TO_APPS', 'index.php?option=com_j2store&view=apps');
        JToolBarHelper::apply('apply');
        JToolBarHelper::save();

        $vars = new JObject();
        //model should always be a plural
        $this->includeCustomModel('AppFlexiVariables');
        $model = F0FModel::getTmpInstance('AppFlexiVariables', 'J2StoreModel');
        $data = $this->params->toArray();
        $newdata = array();
        $newdata['params'] = $data;
        $form = $model->getForm($newdata);
        $vars->form = $form;
        $vars->id = $id;
        $vars->action = "index.php?option=com_j2store&view=app&task=view&id={$id}";
        $html = $this->_getLayout('default', $vars);
        return $html;
    }

    public function	onJ2StoreGetProductTypes(&$types){
        $is_pro = J2Store::isPro();
        if($is_pro){
            $types['flexivariable'] = JText::_('J2STORE_PRODUCT_TYPE_FLEXIVARIABLE');
        }
    }

    public function onJ2StoreAfterAddJS(){
        $is_pro = J2Store::isPro();
        if($is_pro){
            $document = JFactory::getDocument();
            $document->addScript(JUri::root(true).'/plugins/j2store/'.$this->_element.'/'.$this->_element.'/js/flexivariable.js');
        }
    }

    public function onJ2StoreAfterProcessUpSellItem($upsell_product,&$show){
        if(isset($upsell_product->product_type) && $upsell_product->product_type == 'flexivariable'){
            $show = true;
        }
    }

    public function onJ2StoreAfterProcessCrossSellItem($cross_sell_product,&$show){
        if(isset($cross_sell_product->product_type) && $cross_sell_product->product_type == 'flexivariable'){
            $show = true;
        }
    }

    public function onJ2StoreAfterVariantListAjax(&$view,&$item){

        if($item->product_type == 'flexivariable'){
            $item->app_detail = $this->getAppDetails();

            $view->assign('item',  $item );
        }
    }

    protected function getAppDetails(){
        $db = JFactory::getDBo();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__extensions')
            ->where('folder='.$db->q('j2store'))
            ->where('element='.$db->q('app_flexivariable'))
            ->where('type='.$db->q('plugin'));
        $db->setQuery($query);
        return $db->loadObject();
    }
}
<?php
/**
 * @subpackage	mod_sp_date
 * @copyright	Copyright (C) 2010 - 2016 JoomShaper. All rights reserved.
 * @license		GNU General Public License version 2 or later; 
 */

// no direct access
defined('_JEXEC') or die;
?>

<div id="sp-team<?php echo $module->id; ?>" class="sp-date <?php echo $params->get('moduleclass_sfx') ?>">
    <div class="row-fluid">
    	<div class="sp-date-wrapper">
    		<span>
	    	<?php
    			echo JHtml::_('date', JFactory::getDate(), JText::_('DATE_FORMAT_LC1'));
	     	?>
	     	</span>
	     </div>
    </div><!--/.row-fluid-->
</div>
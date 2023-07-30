<?php
/**
 * @subpackage	mod_sp_date
 * @copyright	Copyright (C) 2010 - 2016 JoomShaper. All rights reserved.
 * @license		GNU General Public License version 2 or later; 
 */

// no direct access
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;

$moduleclass_sfx = $moduleclass_sfx ?? '';

?>

<div id="sp-team<?php echo $module->id; ?>" class="sp-date <?php echo $params->get('moduleclass_sfx') ?>">
    <div class="row-fluid">
    	<div class="sp-date-wrapper">
    		<span>
	    	<?php
    			echo HTMLHelper::_('date', Factory::getDate(), JText::_('D, M j, Y'));
	     	?>
	     	</span>
	     </div>
    </div><!--/.row-fluid-->
</div>
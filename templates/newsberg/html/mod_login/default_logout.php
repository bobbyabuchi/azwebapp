<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

HTMLHelper::_('behavior.keepalive');
?>
<div class="sp-custom-login-on">
	<div class="icons-wrap">
		<i class="fa fa-user-circle user-icon"></i>
		<span class="log-text">
			<?php echo Text::_('CUSTOM_LOGIN_HI'); ?>
			<?php echo Text::sprintf(htmlspecialchars($user->get('name'), ENT_COMPAT, 'UTF-8')); ?>
		</span>
		<i class="fa fa-chevron-down arrow-icon"></i>
	</div>
	<div class="form-login-wrap">
		<div class="form-users-wrapper">
			<?php echo Factory::getDocument()->getBuffer('modules', 'user-menu', array('style' => 'none')); ?>
			<form action="<?php echo Route::_('index.php', true, $params->get('usesecure', 0)); ?>" method="post" id="login-form" class="form-vertical">
			<?php if ($params->get('profilelink', 0)) : ?>
			
			<?php endif; ?>
				<div class="logout-button">
					<i class="fa fa-power-off"></i>
					<input type="submit" name="Submit" class="sppb-btn sppb-btn-link" value="<?php echo Text::_('JLOGOUT'); ?>" />
					<input type="hidden" name="option" value="com_users" />
					<input type="hidden" name="task" value="user.logout" />
					<input type="hidden" name="return" value="<?php echo $return; ?>" />
					<?php echo HTMLHelper::_('form.token'); ?>
				</div>
			</form>
		</div>
	</div>
</div>

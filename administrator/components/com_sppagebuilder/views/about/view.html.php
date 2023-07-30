<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2021 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('Restricted access');

use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\ToolbarHelper;

jimport('joomla.application.component.view');

class SppagebuilderViewAbout extends JViewLegacy {

	public function display( $tpl = null ) {

		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolBar() {
		ToolbarHelper::title(Text::_('COM_SPPAGEBUILDER') . ' - ' . Text::_('COM_SPPAGEBUILDER_ABOUT'), 'none pbfont pbfont-pagebuilder');
	}
}

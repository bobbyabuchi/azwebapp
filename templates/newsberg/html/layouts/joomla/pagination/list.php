<?php
/**
 * @package Helix Ultimate Framework
 * @author JoomShaper https://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2021 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/

defined('JPATH_BASE') or die;

$list = $displayData['list'];

$startDisabled = $list['start']['active'] ? '' : ' disabled'; 
$prevDisabled  = $list['previous']['active'] ? '' : ' disabled'; 
$nextDisabled  = $list['next']['active'] ? '' : ' disabled'; 
$endDisabled   = $list['end']['active'] ? '' : ' disabled'; 

?>
<ul class="pagination ms-0 mb-4">
	<li class="pagination-prev<?php echo $prevDisabled; ?> page-item"><?php echo $list['previous']['data']; ?></li>
	<?php foreach ($list['pages'] as $page) : ?>
		<?php $disabled = $page['active'] ? '' : ' disabled'; ?>
		<?php echo '<li class="page-item' . $disabled . '">' . $page['data'] . '</li>'; ?>
	<?php endforeach; ?>
	<li class="pagination-next<?php echo $nextDisabled; ?> page-item"><?php echo $list['next']['data']; ?></li>
</ul>
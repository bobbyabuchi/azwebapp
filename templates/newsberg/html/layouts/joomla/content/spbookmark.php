<?php
/**
 * @package Helix Ultimate Framework
 * @author JoomShaper https://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2019 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/

defined ('JPATH_BASE') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;

$user = Factory::getUser();
$item = $displayData['item'];

// Load bookmarks model
JLoader::register('SpauthorarchiveModelBookmarks', JPATH_SITE . '/components/com_spauthorarchive/models/bookmarks.php');
$bookmark_model = new SpauthorarchiveModelBookmarks();
$exisitng_bookmarks = $bookmark_model->getUserExistingBookmark($user->id);
$item_ids = !empty($exisitng_bookmarks) && count($item_ids = json_decode($exisitng_bookmarks->item_ids)) > 0 ? $item_ids : array();
?>

<div class="article-spbookmark">
    <form class="sp-bookmark-form" name="add-to-bookmark-<?php echo $item->id; ?>">
        <?php if(in_array($item->id, $item_ids)) {
            $bookmark_icon = 'fa-bookmark';
            $active = 'acitve';
        } else {
            $bookmark_icon = 'fa-bookmark-o';
            $active = '';
        } ?>
        <a class="btn-spbookmark-action <?php echo $active; ?>" href="javascript:void(0);" data-content-id="<?php echo $item->id; ?>">
            <span class="spbookmark-icon fa <?php echo $bookmark_icon; ?>"></span>
        </a>
        <input type="hidden" name="cid" value="<?php echo $item->id; ?>">
        <?php if(!$user->id) { ?>
            <input type="hidden" name="curl" value="<?php echo Uri::getInstance()->toString(); ?>">
        <?php } ?>
    </form>
</div>


<?php
/*------------------------------------------------------------------------
# mod_sp_tabbed_articles - Tabbed articles module by JoomShaper.com
# ------------------------------------------------------------------------
# author    JoomShaper http://www.joomshaper.com
# Copyright (C) 2010 - 2015 JoomShaper.com. All Rights Reserved.
# License - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomshaper.com
-------------------------------------------------------------------------*/

defined ('_JEXEC') or die('resticted aceess');

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

$moduleclass_sfx = $moduleclass_sfx ?? '';

Factory::getDocument()->addScript( Uri::root(true) . '/components/com_spauthorarchive/assets/js/spauthorarchive.js' );
?>
<?php if(count($categories)) { ?>
<div class="sp-vertical-tabs">
	<div class="row">
		<div class="col-sm-3 sp-tab-btns-wrap">
			<ul class="sp-tab-btns">
				<?php foreach ($categories as $key=>$cat) { ?>
				<li class="<?php echo ($key==0)? 'active': ''; ?>"><a href="<?php echo Route::_(ContentHelperRoute::getCategoryRoute($cat->id)); ?>"><?php echo $cat->title; ?></a></li>
				<?php } ?>
			</ul>
		</div>
		<div class="col-sm-9">
			<div class="sp-tab-content">
				<?php foreach ($categories as $key=>$category) { ?>
				<div class="sp-tab-pane <?php echo ($key==0)? 'active': ''; ?>">
					<?php $articles = modSpTabbedArticlesHelper::getArticles($limit, $ordering, $category->id); ?>
					<?php if(count($articles)) { ?>
					<div class="row">
						<?php foreach ($articles as $article) {
						$spbookmark = '';
						if (ComponentHelper::getComponent('com_spauthorarchive', true)->enabled) {
							$spbookmark .= LayoutHelper::render('joomla.content.spbookmark', array('item' => $article, 'params' => ''));
						} ?>
						<div itemscope itemtype="http://schema.org/Article" class="col-sm-<?php echo round(12/$columns); ?> sp-tab-article">
							<div class="sp-article-inner"  style="background-image: url(<?php echo $article->image_large; ?>);">
								<div class="sp-article-info">
									<h4 class="entry-title" itemprop="name">
										<a href="<?php echo $article->link; ?>" itemprop="url">
											<?php echo $article->title; ?>
										</a>
									</h4>
									<div class="sp-article-date">
										<?php if($spbookmark){ ?>
										<div class="d-flex">
										<?php } ?>
										<span class="sppb-meta-date" itemprop="datePublished"><?php echo HTMLHelper::_('date', $article->publish_up, 'DATE_FORMAT_LC3') ?></span>
										<?php echo $spbookmark;?>
										<?php if($spbookmark){ ?>
										</div>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
						<?php } ?>
						<div class="menu-tab-wrap">
							<?php
								jimport( 'joomla.application.module.helper' );
								$modules = ModuleHelper::getModules( 'megamenu-tab-add' );
								$attribs['style'] = 'sp_xhtml';

								foreach ($modules as $key => $module) {
									echo ModuleHelper::renderModule( $module, $attribs );
								}
							?>
						</div>
					</div>
					<?php } ?>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<?php } ?>
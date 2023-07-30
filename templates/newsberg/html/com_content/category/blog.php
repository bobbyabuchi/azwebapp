<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;


$doc = Factory::getDocument();

HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers');

$doc = Factory::getDocument();
$tpl_params = Factory::getApplication()->getTemplate(true)->params;

?>
<div class="blog<?php echo $this->pageclass_sfx; ?>" itemscope itemtype="http://schema.org/Blog">
    <?php if ($this->params->get('show_page_heading', 1)) : ?>
        <div class="page-header">
            <h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
        </div>
    <?php endif; ?>

    <?php if ($this->params->get('show_category_title', 1) or $this->params->get('page_subheading')) : ?>
        <h2> <?php echo $this->escape($this->params->get('page_subheading')); ?>
            <?php if ($this->params->get('show_category_title')) : ?>
                <span class="subheading-category"><?php echo $this->category->title; ?></span>
            <?php endif; ?>
        </h2>
    <?php endif; ?>

    <?php if ($this->params->get('show_cat_tags', 1) && !empty($this->category->tags->itemTags)) : ?>
        <?php $this->category->tagLayout = new FileLayout('joomla.content.tags'); ?>
        <?php echo $this->category->tagLayout->render($this->category->tags->itemTags); ?>
    <?php endif; ?>

    <?php if ($this->params->get('show_description', 1) || $this->params->def('show_description_image', 1)) : ?>
        <div class="category-desc clearfix">
            <?php if ($this->params->get('show_description_image') && $this->category->getParams()->get('image')) : ?>
                <img src="<?php echo $this->category->getParams()->get('image'); ?>" alt="<?php echo htmlspecialchars($this->category->getParams()->get('image_alt')); ?>" />
            <?php endif; ?>
            <?php if ($this->params->get('show_description') && $this->category->description) : ?>
                <?php echo HTMLHelper::_('content.prepare', $this->category->description, '', 'com_content.category'); ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if (empty($this->lead_items) && empty($this->link_items) && empty($this->intro_items)) : ?>
        <?php if ($this->params->get('show_no_articles', 1)) : ?>
            <p><?php echo Text::_('COM_CONTENT_NO_ARTICLES'); ?></p>
        <?php endif; ?>
    <?php endif; ?>


    <?php
    $totalItemNo = 0;
    $leadingcount = 0;
    ?>
    <?php if (!empty($this->lead_items)) : ?>

        <h3 class="newsberg-blog-title"><?php echo Text::_('COM_CONTENT_TITLE'); ?></h3>

        <div class="items-leading clearfix">
            <?php foreach ($this->lead_items as $key => &$item) : ?>
                <article class="item leading-<?php echo $leadingcount; ?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?><?php echo $item->featured ? ' item-featured' : ''; ?>" itemprop="blogPost" itemscope itemtype="http://schema.org/BlogPosting">
                    <?php
                    $this->item = &$item;
                    echo $this->loadTemplate('lead');
                    ?>
                </article>

                <?php $totalItemNo++;
                $leadingcount++; ?>

                <!-- if item has 3 then module position will be set -->
                <?php if (($totalItemNo == ($tpl_params->get('blog_list_add', 3))) && $doc->countModules('blog-list-add')) { ?>
                    <div class="newsberg-add items-row row clearfix">
                        <div class="col-sm-12">
                            <?php
                            jimport('joomla.application.module.helper');
                            $modules = ModuleHelper::getModules('blog-list-add');
                            $attribs['style'] = 'sp_xhtml';

                            foreach ($modules as $key => $module) {
                                echo ModuleHelper::renderModule($module, $attribs);
                            }
                            ?>
                        </div> <!-- /.col-sm- -->
                    </div> <!-- /.items-row -->
                <?php } ?>
                <!-- // END:: key condition -->

            <?php endforeach; ?>
        </div><!-- end items-leading -->
    <?php endif; ?>

    <?php
    $introcount = (count($this->intro_items));
    $counter = 0;
    $this->columns = $this->params->get('num_columns');
    ?>

    <?php if (!empty($this->intro_items)) : ?>
        <?php foreach ($this->intro_items as $key => &$item) : ?>
            <?php $rowcount = ((int) $key % (int) $this->columns) + 1; ?>
            <?php if ($rowcount == 1) : ?>
                <?php $row = $counter / $this->columns; ?>
                <div class="items-row <?php echo 'row-' . $row; ?> row clearfix">
                <?php endif; ?>
                <div class="col-sm-<?php echo round((12 / $this->columns)); ?>">
                    <article class="item column-<?php echo $rowcount; ?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?><?php echo $item->featured ? ' item-featured' : ''; ?>" itemprop="blogPost" itemscope itemtype="http://schema.org/BlogPosting">
                        <?php
                        $this->item = &$item;
                        echo $this->loadTemplate('item');
                        ?>
                    </article>
                    <!-- end item -->
                    <?php $totalItemNo++;
                    $counter++; ?>
                </div><!-- end col-sm-* -->
                <?php if (($rowcount == $this->columns) or ($counter == $introcount)) : ?>
                </div><!-- end row -->
            <?php endif; ?>

            <!-- if item has 3 then module position will be set -->
            <?php if (($totalItemNo == ($tpl_params->get('blog_list_add', 3))) && $doc->countModules('blog-list-add')) { ?>
                <div class="newsberg-add items-row row clearfix">
                    <div class="col-sm-12">
                        <?php
                        jimport('joomla.application.module.helper');
                        $modules = ModuleHelper::getModules('blog-list-add');
                        $attribs['style'] = 'sp_xhtml';

                        foreach ($modules as $key => $module) {
                            echo ModuleHelper::renderModule($module, $attribs);
                        }
                        ?>
                    </div> <!-- /.col-sm- -->
                </div> <!-- /.items-row -->
            <?php } ?>
            <!-- // END:: key condition -->

        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (!empty($this->link_items)) : ?>
        <div class="items-more">
            <?php echo $this->loadTemplate('links'); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($this->children[$this->category->id]) && $this->maxLevel != 0) : ?>
        <div class="cat-children">
            <?php if ($this->params->get('show_category_heading_title_text', 1) == 1) : ?>
                <h3> <?php echo Text::_('JGLOBAL_SUBCATEGORIES'); ?> </h3>
            <?php endif; ?>
            <?php echo $this->loadTemplate('children'); ?>
        </div>
    <?php endif; ?>
    <?php if (($this->params->def('show_pagination', 1) == 1 || ($this->params->get('show_pagination') == 2)) && ($this->pagination->pagesTotal > 1)) : ?>
        <div class="com-content-category-blog__navigation w-100">
            <div class="com-content-category-blog__pagination">
                <?php echo $this->pagination->getPagesLinks(); ?>
            </div>
        </div>
    <?php endif; ?>
</div>
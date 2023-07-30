<?php

/**
 * @package Helix Ultimate Framework
 * @author JoomShaper https://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2018 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
 */

defined('_JEXEC') or die();

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers');
$helix_path = JPATH_PLUGINS . '/system/helixultimate/core/helixultimate.php';

// template params for social template
$tmpl_params = Factory::getApplication()->getTemplate(true)->params;

$relatedArticles = [];
if (file_exists($helix_path) && $tmpl_params->get('related_article')) {
    require_once($helix_path);
    $args['catId'] =  $this->item->catid;
    $args['maximum'] = $tmpl_params->get('related_article_limit');
    $args['itemTags'] = $this->item->tags->itemTags;
    $args['item_id'] = $this->item->id;
    $relatedArticles = HelixUltimate::getRelatedArticles($args);
}

// Create shortcuts to some parameters.
$params  = $this->item->params;
$images  = json_decode($this->item->images);
$urls    = json_decode($this->item->urls);
$canEdit = $params->get('access-edit');
$user    = Factory::getUser();
$info    = $params->get('info_block_position', 0);
$page_header_tag = 'h1';
$attribs = json_decode($this->item->attribs);
$article_format = (isset($attribs->helix_ultimate_article_format) && $attribs->helix_ultimate_article_format) ? $attribs->helix_ultimate_article_format : 'standard';
$show_author_post = $tmpl_params->get('show_author_posts');

// Check if associations are implemented. If they are, define the parameter.
$assocParam = (Associations::isEnabled() && $params->get('show_associations'));

$author_posts = array();
if (ComponentHelper::getComponent('com_spauthorarchive', true)->enabled && $show_author_post) {
    // Load com_spauthorarchive components model
    BaseDatabaseModel::addIncludePath(JPATH_SITE . '/components/com_spauthorarchive/models');
    // Load article model
    $articles_model = BaseDatabaseModel::getInstance('Articles', 'SpauthorarchiveModel');
    $author_posts = $articles_model->getAuthorPosts($this->item->created_by);
}

$doc = Factory::getDocument();

$isExpired  = JVERSION < 4
    ? (strtotime($this->item->publish_down) < strtotime(Factory::getDate())) && $this->item->publish_down != Factory::getDbo()->getNullDate()
    : !is_null($this->item->publish_down) && $this->item->publish_down < $currentDate;

?>

<?php if (ComponentHelper::getComponent('com_spauthorarchive', true)->enabled) { ?>
    <?php echo LayoutHelper::render('joomla.content.spbookmark', array('item' => $this->item, 'params' => $params)); ?>
<?php } ?>
<div class="article-details <?php echo $this->pageclass_sfx; ?>" itemscope itemtype="https://schema.org/Article">
    <meta itemprop="inLanguage" content="<?php echo ($this->item->language === '*') ? Factory::getConfig()->get('language') : $this->item->language; ?>">
    <?php if ($this->params->get('show_page_heading')) : ?>
        <div class="page-header">
            <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
        </div>
        <?php $page_header_tag = 'h2'; ?>
    <?php endif;
    if (!empty($this->item->pagination) && $this->item->pagination && !$this->item->paginationposition && $this->item->paginationrelative) {
        echo $this->item->pagination;
    }
    ?>


    <div class="newsberg-details-img-wrapper">
        <div class="img-top-wrap">
            <?php $useDefList = ($params->get('show_modify_date') || $params->get('show_publish_date') || $params->get('show_create_date')
                || $params->get('show_hits') || $params->get('show_category') || $params->get('show_parent_category') || $params->get('show_author') || $assocParam); ?>
            <?php if (JVERSION >= 4) : ?>
                <?php if ($useDefList && ($info == 0 || $info == 2)) : ?>
                    <?php echo LayoutHelper::render('joomla.content.info_block', array('item' => $this->item, 'params' => $params, 'position' => 'above')); ?>
                <?php endif; ?>
            <?php else : ?>
                <?php if ($useDefList && ($info == 0 || $info == 2)) : ?>
                    <?php echo LayoutHelper::render('joomla.content.info_block.block', array('item' => $this->item, 'params' => $params, 'position' => 'above')); ?>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($params->get('show_title') || $params->get('show_author')) : ?>
                <div class="article-header">
                    <?php if ($params->get('show_title')) : ?>
                        <<?php echo $page_header_tag; ?> itemprop="headline">
                            <?php echo $this->escape($this->item->title); ?>
                        </<?php echo $page_header_tag; ?>>
                    <?php endif; ?>
                    <?php if ($this->item->state == 0) : ?>
                        <span class="label label-warning"><?php echo Text::_('JUNPUBLISHED'); ?></span>
                    <?php endif; ?>
                    <?php if (strtotime($this->item->publish_up) > strtotime(Factory::getDate())) : ?>
                        <span class="label label-warning"><?php echo Text::_('JNOTPUBLISHEDYET'); ?></span>
                    <?php endif; ?>
                    <?php if ($isExpired) : ?>
                        <span class="badge bg-warning text-dark"><?php echo Text::_('JEXPIRED'); ?></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php $useDefList = ($params->get('show_modify_date') || $params->get('show_publish_date') || $params->get('show_create_date')
                || $params->get('show_hits') || $params->get('show_category') || $params->get('show_parent_category') || $params->get('show_author') || $assocParam); ?>
            <?php if (JVERSION >= 4) : ?>
                <?php if ($useDefList && ($info == 0 || $info == 2)) : ?>
                    <?php echo LayoutHelper::render('joomla.content.info_block', array('item' => $this->item, 'params' => $params, 'position' => 'above')); ?>
                <?php endif; ?>
            <?php else : ?>
                <?php if ($useDefList && ($info == 0 || $info == 2)) : ?>
                    <?php echo LayoutHelper::render('joomla.content.info_block.block', array('item' => $this->item, 'params' => $params, 'position' => 'above')); ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php if ($article_format == 'gallery') : ?>
            <?php echo LayoutHelper::render('joomla.content.blog.gallery', array('attribs' => $attribs, 'id' => $this->item->id)); ?>
        <?php elseif ($article_format == 'video') : ?>
            <?php echo LayoutHelper::render('joomla.content.blog.video', array('attribs' => $attribs)); ?>
        <?php elseif ($article_format == 'audio') : ?>
            <?php echo LayoutHelper::render('joomla.content.blog.audio', array('attribs' => $attribs)); ?>
        <?php else : ?>
            <?php echo LayoutHelper::render('joomla.content.full_image', $this->item); ?>
        <?php endif; ?>
    </div>



    <?php if (isset($author_posts) && !empty($author_posts) && $show_author_post) { ?>
        <div class="row">

            <div class="col-sm-1 offset-sm-1">
                <?php
                if (($tmpl_params->get('social_share') || $params->get('show_vote')) && !$this->print) : ?>
                    <div class="article-ratings-social-share d-flex justify-content-end">
                        <div class="mr-auto align-self-center">
                            <?php if ($params->get('show_vote')) : ?>
                                <?php HTMLHelper::_('jquery.token'); ?>
                                <?php echo LayoutHelper::render('joomla.content.rating', array('item' => $this->item, 'params' => $params)) ?>
                            <?php endif; ?>
                        </div>
                        <div>
                            <?php echo LayoutHelper::render('joomla.content.social_share', $this->item); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-sm-6">
            <?php } ?>

            <?php if ($info == 0 && $params->get('show_tags', 1) && !empty($this->item->tags->itemTags)) : ?>
                <?php $this->item->tagLayout = new FileLayout('joomla.content.tags'); ?>
                <?php echo $this->item->tagLayout->render($this->item->tags->itemTags); ?>
            <?php endif; ?>

            <?php // Todo Not that elegant would be nice to group the params 
            ?>
            <?php $useDefList = ($params->get('show_modify_date') || $params->get('show_publish_date') || $params->get('show_create_date')
                || $params->get('show_hits') || $params->get('show_category') || $params->get('show_parent_category') || $params->get('show_author') || $assocParam); ?>

            <div class="article-can-edit d-flex flex-wrap justify-content-between">
                <?php // Content is generated by content plugin event "onContentAfterTitle" 
                ?>
                <?php echo $this->item->event->afterDisplayTitle; ?>
                <?php if ($canEdit && !$this->print) : ?>
                    <?php echo HTMLHelper::_('icon.edit', $this->item, $params); ?>
                <?php endif; ?>
            </div>
            <?php if ($useDefList && ($info == 0 || $info == 2)) : ?>
                <?php echo LayoutHelper::render('joomla.content.info_block.block', array('item' => $this->item, 'params' => $params, 'position' => 'above')); ?>
            <?php endif; ?>

            <?php // Content is generated by content plugin event "onContentBeforeDisplay" 
            ?>
            <?php echo $this->item->event->beforeDisplayContent; ?>

            <?php if (
                isset($urls) && ((!empty($urls->urls_position) && ($urls->urls_position == '0')) || ($params->get('urls_position') == '0' && empty($urls->urls_position)))
                || (empty($urls->urls_position) && (!$params->get('urls_position')))
            ) : ?>
                <?php echo $this->loadTemplate('links'); ?>
            <?php endif; ?>

            <?php if ($params->get('access-view')) : ?>

                <?php
                if (!empty($this->item->pagination) && $this->item->pagination && !$this->item->paginationposition && !$this->item->paginationrelative) :
                    echo $this->item->pagination;
                endif;
                ?>
                <?php if (isset($this->item->toc)) :
                    echo $this->item->toc;
                endif; ?>

                <div itemprop="articleBody">
                    <?php echo $this->item->text; ?>
                </div>

                <?php if ($info == 1 || $info == 2) : ?>
                    <?php if ($useDefList) : ?>
                        <?php echo LayoutHelper::render('joomla.content.info_block', array('item' => $this->item, 'params' => $params, 'position' => 'below')); ?>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if (!$this->print) : ?>
                    <?php if ($params->get('show_print_icon') || $params->get('show_email_icon')) : ?>
                        <hr>
                        <div class="article-print-email mt-3">
                            <?php if ($params->get('show_print_icon')) : ?>
                                <?php echo HTMLHelper::_('icon.print_popup', $this->item, $params); ?>&nbsp;
                            <?php endif; ?>

                            <?php if ($params->get('show_email_icon')) : ?>
                                <?php echo HTMLHelper::_('icon.email', $this->item, $params); ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php else : ?>
                    <?php if ($useDefList) : ?>
                        <div id="pop-print">
                            <?php echo HTMLHelper::_('icon.print_screen', $this->item, $params); ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if (isset($urls) && ((!empty($urls->urls_position) && ($urls->urls_position == '1')) || ($params->get('urls_position') == '1'))) : ?>
                    <?php echo $this->loadTemplate('links'); ?>
                <?php endif; ?>
                <?php // Optional teaser intro text for guests 
                ?>
            <?php elseif ($params->get('show_noauth') == true && $user->get('guest')) : ?>
                <?php echo LayoutHelper::render('joomla.content.intro_image', $this->item); ?>
                <?php echo HTMLHelper::_('content.prepare', $this->item->introtext); ?>
                <?php // Optional link to let them register to see the whole article. 
                ?>
                <?php if ($params->get('show_readmore') && $this->item->fulltext != null) : ?>
                    <?php $menu = Factory::getApplication()->getMenu(); ?>
                    <?php $active = $menu->getActive(); ?>
                    <?php $itemId = $active->id; ?>
                    <?php $link = new Uri(Route::_('index.php?option=com_users&view=login&Itemid=' . $itemId, false)); ?>
                    <?php $link->setVar('return', base64_encode(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid, $this->item->language))); ?>
                    <p class="readmore">
                        <a href="<?php echo $link; ?>" class="register">
                            <?php $attribs = json_decode($this->item->attribs); ?>
                            <?php
                            if ($attribs->alternative_readmore == null) :
                                echo Text::_('COM_CONTENT_REGISTER_TO_READ_MORE');
                            elseif ($readmore = $attribs->alternative_readmore) :
                                echo $readmore;
                                if ($params->get('show_readmore_title', 0) != 0) :
                                    echo HTMLHelper::_('string.truncate', $this->item->title, $params->get('readmore_limit'));
                                endif;
                            elseif ($params->get('show_readmore_title', 0) == 0) :
                                echo Text::sprintf('COM_CONTENT_READ_MORE_TITLE');
                            else :
                                echo Text::_('COM_CONTENT_READ_MORE');
                                echo HTMLHelper::_('string.truncate', $this->item->title, $params->get('readmore_limit'));
                            endif; ?>
                        </a>
                    </p>
                <?php endif; ?>
            <?php endif; ?>

            <?php // Content is generated by content plugin event "onContentAfterDisplay" 
            ?>
            <?php echo $this->item->event->afterDisplayContent; ?>

            <?php echo LayoutHelper::render('joomla.content.blog.author_info', $this->item); ?>

            <?php
            if (!empty($this->item->pagination) && $this->item->pagination && $this->item->paginationposition) :
                echo $this->item->pagination;
            ?>
            <?php endif; ?>

            <?php if (!$this->print) : ?>
                <?php echo LayoutHelper::render('joomla.content.blog.comments.comments', $this->item); ?>
            <?php endif; ?>
            </div> <!-- /.col-sm-8 -->

            <?php

            if (isset($author_posts) && !empty($author_posts) && $show_author_post) { ?>

                <div class="col-sm-3 col-sm-pull-1">
                    <div class="authors-posts-wrap">
                        <h3><?php echo Text::_('HELIX_ARTICLE_AUTHORS_POSTS'); ?></h3>
                        <ul class="author-post-items">
                            <?php foreach ($author_posts as $author_post) { ?>
                                <li>
                                    <a href="<?php echo $author_post->link; ?>">
                                        <h3><?php echo $author_post->title; ?></h3>
                                    </a>
                                    <?php //echo $author_post->introtext; 
                                    ?>
                                    <p><?php echo substr($author_post->introtext, 0, 135) . '...'; ?></p>
                                    <p><?php echo HTMLHelper::date($author_post->created, 'M d, Y'); ?></p>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div> <!-- /.col-sm-4 -->
            <?php } ?>
            <?php if (isset($author_posts) && !empty($author_posts) && $show_author_post) { ?>
        </div> <!-- /.row -->
    <?php } ?>

</div>

<?php if ($tmpl_params->get('related_article') && count($relatedArticles) > 0) : ?>
    <?php
    echo LayoutHelper::render('joomla.content.related_articles', ['articles' => $relatedArticles, 'item' => $this->item]);
    ?>
<?php endif; ?>
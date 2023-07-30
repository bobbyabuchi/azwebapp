<?php
/**
* @package SP Page Builder
* @author JoomShaper http://www.joomshaper.com
* @copyright Copyright (c) 2010 - 2016 JoomShaper
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/

//no direct accees
defined ('_JEXEC') or die ('resticted access');

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

class SppagebuilderAddonArticles_thumb_slider extends SppagebuilderAddons{

	public function render(){
		$settings = $this->addon->settings;
		$class = (isset($settings->class) && $settings->class) ? $settings->class : '';

		// Addon options
		$resource 		= (isset($settings->resource) && $settings->resource) ? $settings->resource : 'article';
		$catid 			= (isset($settings->catid) && $settings->catid) ? $settings->catid : 0;
		$tagids 		= (isset($settings->tagids) && $settings->tagids) ? $settings->tagids : array();
		$k2catid 		= (isset($settings->k2catid) && $settings->k2catid) ? $settings->k2catid : 0;
		$include_subcat = (isset($settings->include_subcat)) ? $settings->include_subcat : 1;
		$ordering 		= (isset($settings->ordering) && $settings->ordering) ? $settings->ordering : 'latest';
		$limit 			= (isset($settings->limit) && $settings->limit) ? $settings->limit : 3;
		$show_intro 	= (isset($settings->show_intro)) ? $settings->show_intro : 0;
		$intro_limit 	= (isset($settings->intro_limit) && $settings->intro_limit) ? $settings->intro_limit : 200;
		$show_author 	= (isset($settings->show_author)) ? $settings->show_author : 0;
		$show_category 	= (isset($settings->show_category)) ? $settings->show_category : 1;
		$show_date 		= (isset($settings->show_date)) ? $settings->show_date : 0;
		$show_readmore 	= (isset($settings->show_readmore)) ? $settings->show_readmore : 0;
		$readmore_text 	= (isset($settings->readmore_text) && $settings->readmore_text) ? $settings->readmore_text : 'Read More';
		$link_catid 	= (isset($settings->link_catid)) ? $settings->link_catid : 0;
		$link_k2catid 	= (isset($settings->link_k2catid)) ? $settings->link_k2catid : 0;
		$social_share = (isset($settings->social_share) && $settings->social_share) ? $settings->social_share : 0;
		$autoplay = (isset($settings->autoplay) && $settings->autoplay) ? $settings->autoplay : 0;
		$video_layout = (isset($settings->video_layout) && $settings->video_layout) ? $settings->video_layout : 0;

		$output   = '';
		//include k2 helper
		$k2helper 		= JPATH_ROOT . '/components/com_sppagebuilder/helpers/k2.php';
		$article_helper = JPATH_ROOT . '/components/com_sppagebuilder/helpers/articles.php';
		$isk2installed  = self::isComponentInstalled('com_k2');
		JLoader::register('FieldsHelper', JPATH_ADMINISTRATOR . '/components/com_fields/helpers/fields.php');
		$video_layout_class = $video_layout ? 'video-layout' : ''; 
		
		if ($resource == 'k2') {
			if ($isk2installed == 0) {
				$output .= '<p class="alert alert-danger">' . Text::_('COM_SPPAGEBUILDER_ADDON_ARTICLE_ERORR_K2_NOTINSTALLED') . '</p>';
				return $output;
			} elseif(!file_exists($k2helper)) {
				$output .= '<p class="alert alert-danger">' . Text::_('COM_SPPAGEBUILDER_ADDON_K2_HELPER_FILE_MISSING') . '</p>';
				return $output;
			} else {
				require_once $k2helper;
			}
			$items = SppagebuilderHelperK2::getItems($limit, $ordering, $k2catid, $include_subcat);
		} else {
			require_once $article_helper;
			$items = SppagebuilderHelperArticles::getArticles($limit, $ordering, $catid, $include_subcat, $tagids);
		}

		if (!count($items)) {
			$output .= '<p class="alert alert-warning">' . Text::_('COM_SPPAGEBUILDER_NO_ITEMS_FOUND') . '</p>';
			return $output;
		}
		if(count((array) $items)) {
			$allItems = array();
			$videoItems = array();
			if($video_layout){
				foreach ($items as $key => $item) {
					if($item->post_format === "video"){
						$videoItems[] = $item;
					}
				}
				$allItems = $videoItems;
			} else{
				$allItems = $items;
			}

			$output  .= '<div class="sppb-addon sppb-addon-articles-thumb-slider ' . $class . ' ' . $video_layout_class. '" data-slider-autoplay="'. $autoplay .'">';
			$output .= '<div class="sppb-addon-content">';

			$output	.= '<div class="swiper-container articles-slider">';
			$output .= '<div class="swiper-wrapper">';
			foreach ($allItems as $key => $item) {
				if($video_layout){
					// Custom Fields
					$jcFields = FieldsHelper::getFields('com_content.article', $item, true);
				}
				// if spauthorarchive component is enabled
				$spbookmark = '';
				if (ComponentHelper::getComponent('com_spauthorarchive', true)->enabled) {
					$spbookmark .= LayoutHelper::render('joomla.content.spbookmark', array('item' => $item, 'params' => ''));
				}

				$output .= '<div class="swiper-slide" style="background-image: url('. Uri::base() .$item->featured_image .');">';
				if($video_layout){
				$output .= '<a href="'. $item->link .'" itemprop="url" class="full-link"></a>';
				}
				$output .= '<div class="container">';
				$output .= '<div class="sppb-article-info-wrap">';
					if($show_author || $show_category || $show_date) {
						$output .= '<div class="sppb-article-meta">';
						if($show_category) {
							if ($resource == 'k2') {
								$item->catUrl = urldecode(Route::_(K2HelperRoute::getCategoryRoute($item->catid.':'.urlencode($item->category_alias))));
							} else {
								$item->catUrl = Route::_(ContentHelperRoute::getCategoryRoute($item->catslug));
							}
							$output .= '<span class="sppb-meta-category"><a href="'. $item->catUrl .'" itemprop="genre">' . $item->category . '</a></span>';
						}
						if($show_date) {
							$output .= '<span class="sppb-meta-date" itemprop="datePublished">' . HTMLHelper::_('date', $item->publish_up, 'DATE_FORMAT_LC3') . '</span>';
						}
						if($show_author) {
							$author = ( $item->created_by_alias ?  $item->created_by_alias :  $item->username);
							$output .= '<span class="sppb-meta-author" itemprop="name">' . $author . '</span>';
						}
						$output .= '</div>';
					}
					$output .= '<h3><a href="'. $item->link .'" itemprop="url">' . $item->title . '</a></h3>'; 
					// video layout
					if($video_layout){
						if($item->post_format === "video"){
							$output .= '<div class="video-caption-wrapper d-flex align-items-center">';
							$output .= '<i class="fa fa-play-circle"></i>';	
							$output .= '<div class="video-caption-info">';
							foreach($jcFields as $jcField){
									if($jcField->name === "video-caption"){
										if($jcField->value){
											$output .= '<span class="video-caption">'. $jcField->value . '</span>';
										}
									}
									if($jcField->name === "video-duration"){
										if($jcField->value){
											$output .= '<span class="video-duration">'. $jcField->value .'</span>';
										}
									}
							}
							$output .= '</div>';
							$output .= '</div>';
						}
					}
					if($show_intro) {
						$output .= '<div class="sppb-article-introtext">'. mb_substr(strip_tags($item->introtext), 0, $intro_limit, 'UTF-8') .'...</div>';
					}

					if($show_readmore) {
						$output .= '<a class="sppb-readmore" href="'. $item->link .'" itemprop="url">'. $readmore_text .'</a>';
					}

					// social share
					if(!$video_layout){
						$url = Route::_(ContentHelperRoute::getArticleRoute($item->id . ':' . $item->alias, $item->catid, $item->language));
						$root = Uri::base();
						$root = new Uri($root);
						$social_url = $root->getScheme() . '://' . $root->getHost() . $url;
						if($spbookmark) {
							$output .= '<div class="slider-spbookmark-wrap d-flex">';
							$output .= $spbookmark;
							$output .= '<span class="bookmark-text">' . Text::_('SPPB_COMMON_BOOKMARK') . '<br>' . Text::_('SPPB_COMMON_NOW') . '</span>';
						}
						if ($social_share) {
								$output .= '<div class="sppb-post-share-social">';
								$output .= '<div class="sppb-post-share-social-others">';
									$output .= '<a class="fa fa-facebook" data-toggle="tooltip" data-placement="bottom" title="' . Text::_('HELIX_SHARE_FACEBOOK') . '" onClick="window.open(\'http://www.facebook.com/sharer.php?u=' . $social_url . '\',\'Facebook\',\'width=600,height=300,left=\'+(screen.availWidth/2-300)+\',top=\'+(screen.availHeight/2-150)+\'\'); return false;" href="http://www.facebook.com/sharer.php?u=' . $social_url . '"></a>';
									$output .= '<a class="fa fa-twitter" data-toggle="tooltip" data-placement="bottom" title="' . Text::_('HELIX_SHARE_TWITTER') . '" onClick="window.open(\'http://twitter.com/share?url=' . $social_url . '&amp;text=' . str_replace(" ", "%20", $item->title) . '\',\'Twitter share\',\'width=600,height=300,left=\'+(screen.availWidth/2-300)+\',top=\'+(screen.availHeight/2-150)+\'\'); return false;" href="http://twitter.com/share?url=' . $social_url . '&amp;text=' . str_replace(" ", "%20", $item->title) . '"></a>';
									$output .= '<a class="fa fa-linkedin" data-toggle="tooltip" data-placement="bottom" title="' . Text::_('HELIX_SHARE_LINKEDIN') . '" onClick="window.open(\'http://www.linkedin.com/shareArticle?mini=true&url=' . $social_url . '\',\'Linkedin\',\'width=585,height=666,left=\'+(screen.availWidth/2-292)+\',top=\'+(screen.availHeight/2-333)+\'\'); return false;" href="http://www.linkedin.com/shareArticle?mini=true&url=' . $social_url . '" ></a>';
									$output .= '<a class="fa fa-share"></a>';
								$output .= '</div>'; //.social share others
								$output .= '</div>'; //.social share
						}
						if($spbookmark) {
							$output .= '</div>';
						}
					}
				$output .= '</div>'; //.sppb-article-info-wrap
				$output .= '</div>'; //.container
				$output .= '</div>';
			}
			$output  .= '</div>'; //.swiper-wrapper

			// navition only for mobile
			$output  .= '<div class="thumb-slider-nav swiper-button-next d-none"></div>.';
			$output  .= '<div class="thumb-slider-nav swiper-button-prev d-none"></div>.';
			$output  .= '</div>'; //.swiper-container articles-slider

			// articles Thumb
			$output	.= '<div class="swiper-container articles-thumb">';
			$output .= '<div class="swiper-wrapper">';
			foreach ($allItems as $key => $item) {
				if($video_layout){
					// Custom Fields
					$jcFields = FieldsHelper::getFields('com_content.article', $item, true);
				}
				$count = $key+1;
				if($count<10){
					$countKey = 0 . $count;
				} else{
					$countKey = $count;
				}
				$output .= '<div class="swiper-slide">';
				$output .= '<div class="sppb-article-info-wrap">';
				if(!$video_layout){
				$output .= '<span class="article-counter">'. $countKey . '</span>';
				}
				$output .= '<div class="sppb-article-content">';
					if($show_category) {
						$output .= '<div class="sppb-article-meta">';
						if ($resource == 'k2') {
							$item->catUrl = urldecode(Route::_(K2HelperRoute::getCategoryRoute($item->catid.':'.urlencode($item->category_alias))));
						} else {
							$item->catUrl = Route::_(ContentHelperRoute::getCategoryRoute($item->catslug));
						}
						$output .= '<span class="sppb-meta-category">' . $item->category . '</span>';
						if($video_layout){
							$output .= '<span class="video-icon fa fa-play"></span>';
							$output .= '<span class="video-caption-info">';
							foreach($jcFields as $jcField){
									if($jcField->name === "video-duration"){
										if($jcField->value){
											$output .= '<span class="video-duration">'. $jcField->value .'</span>';
										}
									}
							}
							$output .= '</span>';
						}
						$output .= '</div>';
					}
					$output .= '<h3>' . $item->title . '</h3>';
				$output .= '</div>'; //.sppb-article-content
				$output .= '</div>'; //.sppb-article-info-wrap
				$output .= '</div>';
			}
			$output  .= '</div>'; //.swiper-wrapper
			$output  .= '</div>'; //.articles-thumb
			$output  .= '</div>';
			$output  .= '</div>';
		}

		return $output;
	}

	public function scripts() {
		$app = Factory::getApplication();
		$base_path = Uri::base() . '/templates/' . $app->getTemplate() . '/js/';
		return array($base_path . 'swiper.min.js');
	}

	public function js() {
		$settings = $this->addon->settings;
		$addon_id = '#sppb-addon-' . $this->addon->id;
		$slidesperview = '';
		if(isset($settings->slidesperview) && $settings->slidesperview){
			if(is_object($settings->slidesperview)){
				$slidesperview .= $settings->slidesperview->md;
			} else{
				$slidesperview .= $settings->slidesperview;
			}
		}
		return 'jQuery( document ).ready(function( $ ) {
			var articlesSlider = $("'. $addon_id .' .sppb-addon-articles-thumb-slider")
			var $autoplay = articlesSlider.attr("data-slider-autoplay");
			if ($autoplay == 1) {
					$autoplay = true;
			} else {
					$autoplay = false
			};

			var articlesThumb = new Swiper("'. $addon_id .' .articles-thumb", {
				slidesPerView: '. $slidesperview .',
				direction: "vertical",
				watchSlidesVisibility: true,
				watchSlidesProgress: true,
				mousewheel: true,
				loop: true
			});
			
			var articleSlider = new Swiper("'. $addon_id .' .articles-slider", {
				direction: "vertical",
				loop: true,
				autoplay: $autoplay,
				lazy: true,
				speed: 850,
				navigation: {
					nextEl: ".swiper-button-next",
					prevEl: ".swiper-button-prev",
				},
				thumbs: {
					swiper: articlesThumb
				}
			});
		});';
	}

	public function stylesheets() {
		$app = Factory::getApplication();
		$base_path = Uri::base() . '/templates/' . $app->getTemplate() . '/css/';
		return array($base_path . 'swiper.min.css');
	}

	public function css() {
		$settings = $this->addon->settings;
		$css = '';
		
		$slider_height = '';
		if(isset($settings->slider_height) && $settings->slider_height){
			if(is_object($settings->slider_height)){
			$slider_height .= $settings->slider_height->md;
			} else{
			$slider_height .= $settings->slider_height;
			}
		} else{
			$slider_height .= 815;
		}
		$slider_height_sm = (isset($settings->slider_height) && $settings->slider_height) ? $settings->slider_height_sm: "";
		$slider_height_xs = (isset($settings->slider_height) && $settings->slider_height) ? $settings->slider_height_xs: "";
		
		if($slider_height){
			$css .= '#sppb-addon-' . $this->addon->id . ' .swiper-container.articles-slider{ height:' . $slider_height . 'px; }';
		}

		$css .= '@media (min-width: 768px) and (max-width: 991px) {';
			if($slider_height_sm){
				$css .= '#sppb-addon-' . $this->addon->id . ' .swiper-container.articles-slider{height:' . $slider_height_sm . 'px;';
				$css .= '}';
			}
		$css .='}';

		$css .= '@media (max-width: 767px) {';
			if($slider_height_xs){
				$css .= '#sppb-addon-' . $this->addon->id . ' .swiper-container.articles-slider{ height:' . $slider_height_xs . 'px; }';
			}
		$css .= '}';

		return $css;
	}

	static function isComponentInstalled($component_name){
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select( 'a.enabled' );
		$query->from($db->quoteName('#__extensions', 'a'));
		$query->where($db->quoteName('a.name')." = ".$db->quote($component_name));
		$db->setQuery($query);
		$is_enabled = $db->loadResult();
		return $is_enabled;
	}
}
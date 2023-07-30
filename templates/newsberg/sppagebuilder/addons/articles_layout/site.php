<?php
/**
* @package SP Page Builder
* @author JoomShaper http://www.joomshaper.com
* @copyright Copyright (c) 2010 - 2016 JoomShaper
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/

//no direct accees
defined ('_JEXEC') or die ('resticted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;

class SppagebuilderAddonArticles_layout extends SppagebuilderAddons {

	public function render(){
		$settings = $this->addon->settings;
		$class = (isset($settings->class) && $settings->class) ? $settings->class : '';
		$style = (isset($settings->style) && $settings->style) ? $settings->style : 'panel-default';
		$title = (isset($settings->title) && $settings->title) ? $settings->title : '';
		$heading_selector = (isset($settings->heading_selector) && $settings->heading_selector) ? $settings->heading_selector : 'h3';

		// Addon options
		$articles_layout 		= (isset($settings->articles_layout) && $settings->articles_layout) ? $settings->articles_layout : 'arabica';
		$resource 		= (isset($settings->resource) && $settings->resource) ? $settings->resource : 'article';
		$catid 			= (isset($settings->catid) && $settings->catid) ? $settings->catid : 0;
		$tagids 		= (isset($settings->tagids) && $settings->tagids) ? $settings->tagids : array();
		$k2catid 		= (isset($settings->k2catid) && $settings->k2catid) ? $settings->k2catid : 0;
		$include_subcat = (isset($settings->include_subcat)) ? $settings->include_subcat : 1;
		$post_type 		= (isset($settings->post_type) && $settings->post_type) ? $settings->post_type : '';
		$ordering 		= (isset($settings->ordering) && $settings->ordering) ? $settings->ordering : 'latest';
		$limit 			= (isset($settings->limit) && $settings->limit) ? $settings->limit : 10;
		$show_intro 	= (isset($settings->show_intro)) ? $settings->show_intro : 1;
		$intro_limit 	= (isset($settings->intro_limit) && $settings->intro_limit) ? $settings->intro_limit : 200;
		$show_author 	= (isset($settings->show_author)) ? $settings->show_author : 1;
		$show_category 	= (isset($settings->show_category)) ? $settings->show_category : 1;
		$show_date 		= (isset($settings->show_date)) ? $settings->show_date : 1;
		$show_hit 		= (isset($settings->show_hit)) ? $settings->show_hit : 1;
		$show_readmore 	= (isset($settings->show_readmore)) ? $settings->show_readmore : 1;
		$readmore_text 	= (isset($settings->readmore_text) && $settings->readmore_text) ? $settings->readmore_text : 'Read More';
		$link_articles 	= (isset($settings->link_articles)) ? $settings->link_articles : 0;
		$link_catid 	= (isset($settings->link_catid)) ? $settings->link_catid : 0;
		$link_k2catid 	= (isset($settings->link_k2catid)) ? $settings->link_k2catid : 0;
		$hide_leading_items 	= (isset($settings->hide_leading_items)) ? $settings->hide_leading_items : 0;
		$no_carousel 	= (isset($settings->no_carousel)) ? $settings->no_carousel : 0;
		$has_scrollbar 	= (isset($settings->has_scrollbar)) ? $settings->has_scrollbar : 0;
		$robusta_classic 	= (isset($settings->robusta_classic)) ? $settings->robusta_classic : 0;

		$all_articles_btn_text   = (isset($settings->all_articles_btn_text) && $settings->all_articles_btn_text) ? $settings->all_articles_btn_text : 'See all posts';
		$all_articles_btn_class  = (isset($settings->all_articles_btn_size) && $settings->all_articles_btn_size) ? ' sppb-btn-' . $settings->all_articles_btn_size : '';
		$all_articles_btn_class .= (isset($settings->all_articles_btn_type) && $settings->all_articles_btn_type) ? ' sppb-btn-' . $settings->all_articles_btn_type : ' sppb-btn-default';
		$all_articles_btn_class .= (isset($settings->all_articles_btn_shape) && $settings->all_articles_btn_shape) ? ' sppb-btn-' . $settings->all_articles_btn_shape: ' sppb-btn-rounded';
		$all_articles_btn_class .= (isset($settings->all_articles_btn_appearance) && $settings->all_articles_btn_appearance) ? ' sppb-btn-' . $settings->all_articles_btn_appearance : '';
		$all_articles_btn_class .= (isset($settings->all_articles_btn_block) && $settings->all_articles_btn_block) ? ' ' . $settings->all_articles_btn_block : '';
		$all_articles_btn_icon   = (isset($settings->all_articles_btn_icon) && $settings->all_articles_btn_icon) ? $settings->all_articles_btn_icon : '';
		$all_articles_btn_icon_position = (isset($settings->all_articles_btn_icon_position) && $settings->all_articles_btn_icon_position) ? $settings->all_articles_btn_icon_position: 'left';
		$columns = 4;
		$set_columns   = (isset($settings->set_columns) && $settings->set_columns) ? $settings->set_columns : 3;
		$set_columns_one   = (isset($settings->set_columns_one) && $settings->set_columns_one) ? $settings->set_columns_one : 3;
		$set_columns_two   = (isset($settings->set_columns_two) && $settings->set_columns_two) ? $settings->set_columns_two : 3;

		$output   = '';
		//include k2 helper
		$k2helper 		= JPATH_ROOT . '/components/com_sppagebuilder/helpers/k2.php';
		$article_helper = JPATH_ROOT . '/components/com_sppagebuilder/helpers/articles.php';
		$isk2installed  = self::isComponentInstalled('com_k2');
		$app = Factory::getApplication();
		$image_path = Uri::base() . '/templates/' . $app->getTemplate() . '/images/';
		$title_container = '';
		$has_robusta_classic = $robusta_classic ? 'robusta-classic' : '';

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
			$items = SppagebuilderHelperArticles::getArticles($limit, $ordering, $catid, $include_subcat, $post_type, $tagids);
		}

		if (!count($items)) {
			$output .= '<p class="alert alert-warning">' . Text::_('COM_SPPAGEBUILDER_NO_ITEMS_FOUND') . '</p>';
			return $output;
		}

		if(count((array) $items)) {
			$output  .= '<div class="sppb-addon sppb-addon-articles-layout ' . 'layout-' .$articles_layout . ' ' . $has_robusta_classic . ' ' . $class . '">';
			
			if($title && ($articles_layout !== "robusta")) {
				$output .= '<'.$heading_selector.' class="sppb-addon-title">' . $title . '</'.$heading_selector.'>';
			} else if ($title) {
				$title_container = '<'.$heading_selector.' class="sppb-addon-title">' . $title . '</'.$heading_selector.'>';
			}

			$output .= '<div class="sppb-addon-content">';
			if(!$robusta_classic){
			$output	.= '<div class="sppb-row">';
			} else {
			$output	.= '<div class="sppb-row leading-items-row">';
			}

			//layout related variables
			$total_items = count($items);
			$count_item = 0;
			foreach ($items as $key => $item) {
				// Custom Fields
				$jcFields = FieldsHelper::getFields('com_content.article', $item, true);
				$audio_format = ($item->post_format == 'audio') ? 'audio-format': '';

				// if spauthorarchive component is enabled
				$spbookmark = '';
				if (ComponentHelper::getComponent('com_spauthorarchive', true)->enabled) {
					$spbookmark .= LayoutHelper::render('joomla.content.spbookmark', array('item' => $item, 'params' => ''));
				}

				// Layout conditions
				$bg_image = '';
				$count_item++;
				$custom_class = '';
				if($articles_layout === "arabica"){
					if($no_carousel) {
						$columns = round(12 / $set_columns_one);
						$custom_class = "normal-item";
					} else if(!$hide_leading_items){
						if($key === 0 || $key === 1 ){
							$columns = 6;
							$custom_class = "leading-item";
							$bg_image = "background-image: url(" . Uri::base() . $item->featured_image .")";
						} else {
							$columns = 0;
						}
						if($key === 2){
							$output	.= '<div class="sppb-col-sm-12">';
							$output	.= '<div class="swiper-container intro-items-slider">';
							$output .= '<div class="swiper-wrapper">';
						} 
						if($key >= 2){
							$custom_class = "swiper-slide";
						}
					} else {
						$columns = 0;
						if($key === 0){
							$output	.= '<div class="swiper-container intro-items-slider sppb-col-sm-12">';
							$output .= '<div class="swiper-wrapper">';
						} 
						if($key >= 0){
							$custom_class = "swiper-slide";
						}
					}
				} else if($articles_layout === "liberica"){
					$columns = 12;
					if($key === 0){
						if($item->post_format == 'video'){
							$custom_class = "video-format leading-item";
						} else{
							$custom_class = "leading-item";
						}
					} else if(($key === 1 )||($key === 2 )){
						if($item->post_format == 'video'){
							$custom_class = "video-format subleading-item";
						} else{
							$custom_class = "subleading-item";
						}
					} else{
						if($item->post_format == 'video'){
							$custom_class = "video-format intro-item";
						} else{
							$custom_class = "intro-item";
						}
					}
					if($key === 1){
						$output	.= '<div class="sppb-row">';
						$output	.= '<div class="sppb-col-sm-8 subleading-items">';
						$output	.= '<div>';
					} else if($key === 3){
						$output	.= '<div class="sppb-col-sm-4 intro-items" has-scrollbar>';
					}
				} else if($articles_layout === "robusta"){
					if($key === 0){
						$columns = 0;
						$output	.= '<div class="sppb-col-sm-6 sppb-col-md-8 leading-item">';
					} else if($key === 1){
						$output	.= '<div class="sppb-col-sm-6 sppb-col-md-4">';
						$output	.= $title_container ? $title_container : '';
						if($robusta_classic){
						$output	.= '<div class="intro-items">';
						} else {
						$output	.= '<div class="intro-items" has-scrollbar>';
						}
					} else if($key === 4) {
						if($robusta_classic){
							$columns = 4;
							$output	.= '<div class="sppb-row bottom-items">';
							$custom_class = 'bottom-item';
						}
					} else if($key >= 4) {
						if($robusta_classic){
							$custom_class = 'bottom-item';
						}
					}
				} else if($articles_layout === "java"){
					if($key <= 4){
						$columns = 3;
						$custom_class = "leading-item";
					} else if($key === 5){
						$columns = 6;
						$custom_class = "subleading-item";
					} else if($key === 6){
						$columns = 0;
						$output	.= '<div class="sppb-col-sm-12 sppb-col-md-3">';
						$output	.= '<div class="all-items-wrap intro-items" has-scrollbar>';
					}
				} else if($articles_layout === "geisha"){
					$columns = 12;
					$custom_class = 'item';
					if($key === 0){
					$output	.= '<div class="all-items-wrap" has-scrollbar>';
					}
				} else if($articles_layout === "casipea"){
					$columns = round(12 / $set_columns_two);
					$custom_class = 'item';
					if($key === 0){
						if($has_scrollbar){
							$output	.= '<div class="all-items-wrap has-scrollbar" has-scrollbar>';
						}
					}
				} else if(($articles_layout === "excelsa") || ($articles_layout === "bourbon")) {
					$columns = round(12 / $set_columns);
				} // end layout conditions
				
				if(($columns < 12 && $columns > 0) && (($articles_layout === "bourbon") || ($articles_layout === "excelsa") || ($articles_layout === "casipea") || ($articles_layout === "geisha") || (($articles_layout === "arabica") && ($no_carousel)) || ($articles_layout === "java"))){
				$output .= '<div class="'. $custom_class . ' ' .' sppb-col-sm-6 sppb-col-md-'. $columns .'">';
				} else{
				$output .= '<div class="'. $custom_class . ' ' .' sppb-col-sm-'. $columns .'">';
				}
				$output .= '<div class="sppb-addon-article '. $audio_format .'" style="' . $bg_image . '">';
				if ($resource == 'k2') {
					if(isset($item->image_medium) && $item->image_medium){
						$image = $item->image_medium;
					} elseif(isset($item->image_large) && $item->image_large){
						$image = $item->image_medium;
					}
				} else {
					if($articles_layout === "robusta" && $key === 0){
						if(isset($item->image_large) && $item->image_large){
							$image = $item->image_large;
						} else{
							$image = '';
						}
					} else if($articles_layout === "liberica" && $key >0){
						if(isset($item->image_medium) && $item->image_medium){
							$image = $item->image_medium;
						} else{
							$image = '';
						}
					} else if($articles_layout === "java" && $key >=0){
						if(isset($item->image_large) && $item->image_large){
							$image = $item->image_large;
						} else{
							$image = '';
						}
					} else if($articles_layout === "excelsa" && $key >=0){
						if(isset($item->image_medium) && $item->image_medium){
							$image = $item->image_medium;
						} else{
							$image = '';
						}
					} else {
						if(isset($item->image_small) && $item->image_small){
							$image = $item->image_small;
						} else{
							$image = '';
						}
					}
				}
				
				if($articles_layout === "casipea"){
					$output .= '<span class="article-count">'. $count_item .'</span>';
				}

				//image conditions (only for arabica layout)
				if((!$hide_leading_items)){
					if((($articles_layout === "arabica") && ($key <= 1)) || ($articles_layout !== "arabica")){
						if($resource != 'k2' && $item->post_format=='gallery') {
							if(count((array) $item->imagegallery->images)) {
								if(($articles_layout === "liberica" && $count_item >=2) || (($articles_layout === "robusta") && !$robusta_classic) || (($articles_layout === "robusta") && $robusta_classic && $key >=1) || ($articles_layout === "casipea")) {
									$output .= '<div class="sppb-carousel sppb-slide" data-sppb-ride="sppb-carousel">';
									$output .= '<div class="sppb-carousel-inner">';
									foreach ($item->imagegallery->images as $key => $gallery_item) {
										$active_class = '';
										if($key == 0){
											$active_class = ' active';
										}
										$output .= '<div class="sppb-item'.$active_class.'">';
										if(($articles_layout === "robusta") && $count_item === 1){
										$output .= '<img src="'. $gallery_item['full'] .'" alt="">';
										} else {
										$output .= '<img src="'. $gallery_item['medium'] .'" alt="">';
										}
										$output .= '</div>';
									}
									$output	.= '</div>';

									$output	.= '<a class="left sppb-carousel-control" role="button" data-slide="prev" aria-label="'.Text::_('COM_SPPAGEBUILDER_ARIA_PREVIOUS').'"><i class="fa fa-angle-left" aria-hidden="true"></i></a>';
									$output	.= '<a class="right sppb-carousel-control" role="button" data-slide="next" aria-label="'.Text::_('COM_SPPAGEBUILDER_ARIA_NEXT').'"><i class="fa fa-angle-right" aria-hidden="true"></i></a>';

									$output .= '</div>';
								} else {
									$output .= '<div class="sppb-carousel sppb-slide sppb-carousel-as-bg" data-sppb-ride="sppb-carousel">';
									$output .= '<div class="sppb-carousel-inner">';
									foreach ($item->imagegallery->images as $key => $gallery_item) {
										$active_class = '';
										if($key == 0){
											$active_class = ' active';
										}
										$output .= '<div class="sppb-item'.$active_class.'" style="background-image: url('. Uri::base() . $gallery_item['full'] .');">';
										$output .= '</div>';
									}
									$output	.= '</div>';
									$output	.= '<a class="left sppb-carousel-control" role="button" data-slide="prev" aria-label="'.Text::_('COM_SPPAGEBUILDER_ARIA_PREVIOUS').'"><i class="fa fa-angle-left" aria-hidden="true"></i></a>';
									$output	.= '<a class="right sppb-carousel-control" role="button" data-slide="next" aria-label="'.Text::_('COM_SPPAGEBUILDER_ARIA_NEXT').'"><i class="fa fa-angle-right" aria-hidden="true"></i></a>';

									$output .= '</div>';
								}

							} elseif ( isset($item->image_thumbnail) && $item->image_thumbnail ) {
								$output .= '<a href="'. $item->link .'" itemprop="url"><img class="sppb-img-responsive" src="'. $item->image_thumbnail .'" alt="'. $item->title .'" itemprop="thumbnailUrl"></a>';
							}
						} elseif( $resource != 'k2' &&  $item->post_format == 'video' && isset($item->video_src) && $item->video_src && (($articles_layout === "liberica") || ($articles_layout === "robusta") || ($articles_layout === "java") || ($articles_layout === "casipea"))) {
								$output .= '<div class="video-caption-wrapper d-flex align-items-center" style="background-image: url('  . Uri::base() . $item->featured_image . ');">';
								$output .= '<a class="full-link" href="'. $item->link .'" itemprop="url"></a>';	
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
						} elseif($resource != 'k2' && $item->post_format == 'audio' && isset($item->audio_embed) && $item->audio_embed) {
							if($articles_layout !== "arabica"){
								$output .= '<div class="audio-caption-wrapper d-flex align-items-end" style="background-image: url(' . Uri::base() . $item->featured_image . ');">';
								$output .= '<img src="'. $image_path .'podcast.png" >';
								$output .= '</div>';
							}
						} elseif($resource != 'k2' && $item->post_format == 'link' && isset($item->link_url) && $item->link_url) {
							$output .= '<div class="entry-link"  style="' . $bg_image . '">';
								$output .= '<a target="_blank" rel="noopener noreferrer" href="' . $item->link_url .'"><h4>' . $item->link_title .'</h4></a>';
							$output .= '</div>';
						} else {
							if(($articles_layout === "liberica" && $key === 0) || ($articles_layout === "bourbon") || (($articles_layout === "java") && $key === 5) || (($articles_layout === "robusta") && $robusta_classic && ($key === 0))){
								if(isset($image) && $image) {
									$output .= '<div class="bg-img-wrapper" style="background-image: url(' . Uri::base() . $item->featured_image . ');"></div>';
								}
							} else{
								if(isset($image) && $image) {
									$output .= '<a class="sppb-article-img-wrap" href="'. $item->link .'" itemprop="url"><img class="sppb-img-responsive" src="'. $image .'" alt="'. $item->title .'" itemprop="thumbnailUrl"></a>';
								}
							}
						}
					}
				}
				$output .= '<div class="sppb-article-info-wrap">';
				if(($articles_layout === "bourbon") || (($articles_layout === "java") && ($key === 5)) || (($articles_layout === "robusta") && $robusta_classic && $key === 0)){
					if( $resource != 'k2' &&  $item->post_format == 'video' && isset($item->video_src) && $item->video_src) {
							$output .= '<div class="video-symbol-wrapper mb-2 d-flex align-items-center">';
							$output .= '<a class="full-link" href="'. $item->link .'" itemprop="url"></a>';	
							$output .= '<i class="fa fa-play-circle"></i>';	
							$output .= '<div class="video-caption-info">';
							foreach($jcFields as $jcField){
									if($jcField->name === "video-duration"){
										if($jcField->value){
											$output .= '<span class="video-duration">'. $jcField->value .'</span>';
										}
									}
							}
							$output .= '</div>';
							$output .= '</div>';
					} elseif($resource != 'k2' && $item->post_format == 'audio' && isset($item->audio_embed) && $item->audio_embed) {
						$output .= '<div class="audio-symbol-wrapper mb-2 d-flex align-items-center">';
						$output .= '<img src="'. $image_path .'podcast.png" >';
						$output .= '</div>';
					}
				}

					if($show_category) {
						// generate URL by resource
						if ($resource == 'k2') {
							$item->catUrl = urldecode(Route::_(K2HelperRoute::getCategoryRoute($item->catid.':'.urlencode($item->category_alias))));
						} else {
							$item->catUrl = Route::_(ContentHelperRoute::getCategoryRoute($item->catslug));
						}
						$output .= '<p class="sppb-meta-category">';
							$output .= '<a href="'. $item->catUrl .'" itemprop="genre">' . $item->category . '</a>';
							if($show_hit){
								$output .= '<span class="float-right hits-count"><span class="fa fa-eye"></span>' . $item->hits. '</span>';
							}
							if($item->post_format == 'video' && isset($item->video_src) && $item->video_src){
								$output .= '<span class="video-symbol fa fa-play-circle"></span>';	
								foreach($jcFields as $jcField){
									if($jcField->name === "video-duration"){
										if($jcField->value){
											$output .= '<span class="video-duration">'. $jcField->value .'</span>';
										}
									}
								}
							}
						$output .= '</p>';
					}
					$output .= '<h3><a href="'. $item->link .'" itemprop="url">' . $item->title . '</a></h3>';
					//sppb-article-info-intro-wrap (only for arabica layout)
					if((($articles_layout === "arabica") && ($key >= 2)) || (($articles_layout === "java") && ($key >= 6)) || ($hide_leading_items) ){
						$output .= '<div class="sppb-article-info-intro-wrap d-flex align-items-center">';
						$output .= '<div>';
					}
					if($show_intro) {
						$output .= '<div class="sppb-article-introtext">'. mb_substr(strip_tags($item->introtext), 0, $intro_limit, 'UTF-8') .'...</div>';
					}

					if(($spbookmark && $show_author) || ($spbookmark && $show_date)) {
						$output .= '<div class="sppb-article-spbookmark-wrap d-flex">';
					}
					
					if(($articles_layout === "arabica") && ($count_item <= 2)){
						if($resource != 'k2' && $item->post_format == 'audio' && isset($item->audio_embed) && $item->audio_embed) {
							$output .= '<div class="only-audio-img">';
							$output .= '<img src="'. $image_path .'podcast.png" >';
							$output .= '</div>';
						}
					}

					if($show_author || $show_date) {
						$output .= '<div class="sppb-article-meta">';

						if($show_date) {
							$output .= '<span class="sppb-meta-date" itemprop="datePublished">' . HTMLHelper::_('date', $item->publish_up, 'M d, Y') . '</span>';
						}

						if($show_author) {
							$author = ( $item->created_by_alias ?  $item->created_by_alias :  $item->username);
							$output .= '<span class="sppb-meta-author" itemprop="name">' . $author . '</span>';
						}

						$output .= '</div>';
					}

					if(($spbookmark && $show_author) || ($spbookmark && $show_date)) {
						$output .= $spbookmark;
						$output .= '</div>';
					}

					//close sppb-article-info-intro-wrap (only for arabica layout)
					if((($articles_layout === "arabica") && ($key >= 2)) || (($articles_layout === "java") && ($key >= 6)) || ($hide_leading_items) ){
						$output .= '</div>';

						//image conditions (only for arabica layout)
						if($resource != 'k2' && $item->post_format=='gallery') {
							if(count((array) $item->imagegallery->images)) {
								$output .= '<div class="sppb-carousel sppb-slide" data-sppb-ride="sppb-carousel">';
								$output .= '<div class="sppb-carousel-inner">';
								foreach ($item->imagegallery->images as $key => $gallery_item) {
									$active_class = '';
									if($key == 0){
										$active_class = ' active';
									}
									if (isset($gallery_item['thumbnail']) && $gallery_item['thumbnail']) {
										$output .= '<div class="sppb-item'.$active_class.'">';
										$output .= '<img src="'. $gallery_item['thumbnail'] .'" alt="">';
										$output .= '</div>';
									} elseif (isset($gallery_item['full']) && $gallery_item['full']) {
										$output .= '<div class="sppb-item'.$active_class.'">';
										$output .= '<img src="'. $gallery_item['full'] .'" alt="">';
										$output .= '</div>';
									}
								}
								$output	.= '</div>';

								$output	.= '<a class="left sppb-carousel-control" role="button" data-slide="prev" aria-label="'.Text::_('COM_SPPAGEBUILDER_ARIA_PREVIOUS').'"><i class="fa fa-angle-left" aria-hidden="true"></i></a>';
								$output	.= '<a class="right sppb-carousel-control" role="button" data-slide="next" aria-label="'.Text::_('COM_SPPAGEBUILDER_ARIA_NEXT').'"><i class="fa fa-angle-right" aria-hidden="true"></i></a>';

								$output .= '</div>';

							} elseif ( isset($item->image_thumbnail) && $item->image_thumbnail ) {
								$output .= '<a href="'. $item->link .'" itemprop="url"><img class="sppb-img-responsive" src="'. $item->image_thumbnail .'" alt="'. $item->title .'" itemprop="thumbnailUrl"></a>';
							}
						} elseif( $resource != 'k2' &&  $item->post_format == 'video' && isset($item->video_src) && $item->video_src) {
								$output .= '<div class="video-caption-wrapper d-flex align-items-center" style="background-image: url(' . Uri::base() .  $item->featured_image . ');">';
								$output .= '<i class="fa fa-play-circle"></i>';
								$output .= '</div>';
						} elseif($resource != 'k2' && $item->post_format == 'audio' && isset($item->audio_embed) && $item->audio_embed) {
							$output .= '<div class="audio-caption-wrapper d-flex align-items-center" style="background-image: url('  . Uri::base() . $item->featured_image . ');">';
							$output .= '<img src="'. $image_path .'podcast.png" >';
							$output .= '</div>';
						} elseif($resource != 'k2' && $item->post_format == 'link' && isset($item->link_url) && $item->link_url) {
							$output .= '<div class="entry-link"  style="' . $bg_image . '">';
								$output .= '<a target="_blank" rel="noopener noreferrer" href="' . $item->link_url .'"><h4>' . $item->link_title .'</h4></a>';
							$output .= '</div>';
						} else {
							if(isset($image) && $image) {
								$output .= '<a class="sppb-article-img-wrap" href="'. $item->link .'" itemprop="url"><img class="sppb-img-responsive" src="'. $image .'" alt="'. $item->title .'" itemprop="thumbnailUrl"></a>';
							}
						}
						$output .= '</div>';
					}

					if($show_readmore) {
						$output .= '<a class="sppb-readmore sppb-btn  sppb-btn-primary sppb-btn-round sppb-btn-sm sppb-btn-outline" href="'. $item->link .'" itemprop="url">'. $readmore_text .'</a>';
					}
				$output .= '</div>'; //.sppb-article-info-wrap

				$output .= '</div>'; //end $columns
				$output .= '</div>'; //end .sppb-addon-article

				// Layout conditions
				if($articles_layout === "arabica"){
					if($total_items === $count_item){
						if(!$no_carousel){
							$output	.= '</div>';
							$output  .= '<div class="thumb-slider-nav swiper-button-next"></div>';
							$output  .= '<div class="thumb-slider-nav swiper-button-prev"></div>';
						}
						if(!$hide_leading_items){
							$output	.= '</div>';
						}
						if(!$no_carousel){
						$output	.= '</div>';
						}
					}
				} else if($articles_layout === "liberica"){
					if($key === 2 ){
						$output	.= '</div>';
						$output	.= '</div>'; //end .sppb-col-sm-8 subleading-items
					}
					if(($total_items === $count_item) && ($total_items >= 2)){
						if($total_items >= 4){
						$output	.= '</div>'; //end .sppb-col-sm-4
						}
						$output	.= '</div>'; //end .sppb-row
					}
				} else if($articles_layout === "robusta"){
					if($count_item === 1 ){
						$output	.= '</div>'; //end .sppb-col-sm-8
					}
					if(!$robusta_classic){
						if($total_items === $count_item){
							$output	.= '</div>';
							$output	.= '</div>'; //end .sppb-col-sm-4
						}
					}
					if($robusta_classic){
						if($count_item === 4){
							$output	.= '</div>';
							$output	.= '</div>'; //end .sppb-col-sm-4
							$output	.= '</div>'; //end .sppb-row
						}
					}
				} else if($articles_layout === "java"){
					if($total_items === $count_item){
						$output	.= '</div">';
						$output	.= '</div">';
					}
				} else if(($articles_layout === "geisha")){
					if($total_items === $count_item){
						$output	.= '</div>';
					}
				} else if(($articles_layout === "casipea")){
					if($total_items === $count_item){
						if($has_scrollbar) {
							$output	.= '</div>';
						}
					}
				}// end layout conditions
			}

			$output  .= '</div>';

			// See all link
			if($link_articles) {

				if($all_articles_btn_icon_position == 'left') {
					$all_articles_btn_text = ($all_articles_btn_icon) ? '<i class="fa ' . $all_articles_btn_icon . '" aria-hidden="true"></i> ' . $all_articles_btn_text : $all_articles_btn_text;
				} else {
					$all_articles_btn_text = ($all_articles_btn_icon) ? $all_articles_btn_text . ' <i class="fa ' . $all_articles_btn_icon . '" aria-hidden="true"></i>' : $all_articles_btn_text;
				}

				if ($resource == 'k2') {
					if(!empty($link_k2catid)){
						$output  .= '<a href="' . urldecode(Route::_(K2HelperRoute::getCategoryRoute($link_k2catid))) . '" " id="btn-' . $this->addon->id . '" class="sppb-btn' . $all_articles_btn_class . '">' . $all_articles_btn_text . '</a>';
					}
				} else{
					if(!empty($link_catid)){
						$output  .= '<a href="' . Route::_(ContentHelperRoute::getCategoryRoute($link_catid)) . '" id="btn-' . $this->addon->id . '" class="sppb-btn' . $all_articles_btn_class . '">' . $all_articles_btn_text . '</a>';
					}
				}

			}

			$output  .= '</div>';
			$output  .= '</div>';
		}

		return $output;
	}

	public function scripts() {
		$app = Factory::getApplication();
		if (ComponentHelper::getComponent('com_spauthorarchive', true)->enabled) {
			return array(Uri::base() . '/templates/' . $app->getTemplate() . '/js/' . 'swiper.min.js', Uri::base() . '/components/com_spauthorarchive/assets/js/spauthorarchive.js');
		}
		return array(Uri::base() . '/templates/' . $app->getTemplate() . '/js/' . 'swiper.min.js');
	}

	public function js() {
		$settings = $this->addon->settings;
		$addon_id = '#sppb-addon-' . $this->addon->id;
		$slidesperview = 3;
		if(isset($settings->slidesperview) && $settings->slidesperview){
			if(is_object($settings->slidesperview)){
				$slidesperview = $settings->slidesperview->md;
			} else{
				$slidesperview = $settings->slidesperview;
			}
		}
		return 'jQuery( document ).ready(function( $ ) {
			var articleSlider = new Swiper("'. $addon_id .' .intro-items-slider", {
				breakpoints: {
					478: {
						slidesPerView: 1,	
						spaceBetween: 0,
					},
					768: {
						slidesPerView: 2,	
						spaceBetween: 20,
					},
					1199: {
						slidesPerView: '. $slidesperview .',	
						spaceBetween: 30,
					}
				},
				lazy: true,
				navigation: {
					nextEl: ".swiper-button-next",
					prevEl: ".swiper-button-prev",
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
		$addon_id = '#sppb-addon-' .$this->addon->id;
		$layout_path = JPATH_ROOT . '/components/com_sppagebuilder/layouts';
		$css_path = new FileLayout('addon.css.button', $layout_path);

		$options = new stdClass;
		$options->button_type = (isset($this->addon->settings->all_articles_btn_type) && $this->addon->settings->all_articles_btn_type) ? $this->addon->settings->all_articles_btn_type : '';
		$options->button_appearance = (isset($this->addon->settings->all_articles_btn_appearance) && $this->addon->settings->all_articles_btn_appearance) ? $this->addon->settings->all_articles_btn_appearance : '';
		$options->button_color = (isset($this->addon->settings->all_articles_btn_color) && $this->addon->settings->all_articles_btn_color) ? $this->addon->settings->all_articles_btn_color : '';
		$options->button_color_hover = (isset($this->addon->settings->all_articles_btn_color_hover) && $this->addon->settings->all_articles_btn_color_hover) ? $this->addon->settings->all_articles_btn_color_hover : '';
		$options->button_background_color = (isset($this->addon->settings->all_articles_btn_background_color) && $this->addon->settings->all_articles_btn_background_color) ? $this->addon->settings->all_articles_btn_background_color : '';
		$options->button_background_color_hover = (isset($this->addon->settings->all_articles_btn_background_color_hover) && $this->addon->settings->all_articles_btn_background_color_hover) ? $this->addon->settings->all_articles_btn_background_color_hover : '';
		$options->button_fontstyle = (isset($this->addon->settings->all_articles_btn_font_style) && $this->addon->settings->all_articles_btn_font_style) ? $this->addon->settings->all_articles_btn_font_style : '';
		$options->button_font_style = (isset($this->addon->settings->all_articles_btn_font_style) && $this->addon->settings->all_articles_btn_font_style) ? $this->addon->settings->all_articles_btn_font_style : '';
		$options->button_letterspace = (isset($this->addon->settings->all_articles_btn_letterspace) && $this->addon->settings->all_articles_btn_letterspace) ? $this->addon->settings->all_articles_btn_letterspace : '';

		return $css_path->render(array('addon_id' => $addon_id, 'options' => $options, 'id' => 'btn-' . $this->addon->id));
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
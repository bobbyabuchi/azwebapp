<?php
/**
* @package SP Page Builder
* @author JoomShaper http://www.joomshaper.com
* @copyright Copyright (c) 2010 - 2016 JoomShaper
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('resticted aceess');

SpAddonsConfig::addonConfig(
array(
	'type'=>'content',
	'addon_name'=>'sp_articles_thumb_slider',
	'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_THUMB_SLIDER'),
	'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_THUMB_SLIDER_DESC'),
	'category'=>'Content',
	'attr'=>array(
		'general' => array(
			'admin_label'=>array(
				'type'=>'text',
				'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_ADMIN_LABEL'),
				'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_ADMIN_LABEL_DESC'),
				'std'=> ''
			),
			'video_layout'=>array(
				'type'=>'checkbox', 
				'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_THUMB_SLIDER_VIDEO_LAYOUT'),
				'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_THUMB_SLIDER_VIDEO_LAYOUT_DESC'),
				'std'=>0,
			),
			'resource'=>array(
				'type'=>'select',
				'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLE_RESOURCE'),
				'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLE_RESOURCE_DESC'),
				'values'=>array(
					'article'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLE_RESOURCE_ARTICLE'),
					'k2'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLE_RESOURCE_K2'),
					),
				'std'=>'article',
			),

			'catid'=>array(
				'type'=>'category',
				'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_CATID'),
				'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_CATID_DESC'),
				'depends'=>array('resource'=>'article'),
				'multiple'=>true,
			),

			'tagids'=>array(
				'type'=>'select',
				'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_TAGS'),
				'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_TAGS_DESC'),
				'depends'=>array('resource'=>'article'),
				'values'=> SpPgaeBuilderBase::getArticleTags(),
				'multiple'=>true,
			),

			'k2catid'=>array(
				'type'=>'select',
				'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_K2_CATID'),
				'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_K2_CATID_DESC'),
				'depends'=>array('resource'=>'k2'),
				'values'=> SpPgaeBuilderBase::k2CatList(),
				'multiple'=>true,
			),

			'include_subcat'=>array(
				'type'=>'select',
				'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_INCLUDE_SUBCATEGORIES'),
				'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_INCLUDE_SUBCATEGORIES_DESC'),
				'values'=>array(
					1=>JText::_('COM_SPPAGEBUILDER_YES'),
					0=>JText::_('COM_SPPAGEBUILDER_NO'),
				),
				'std'=> 1,
			),

			'ordering'=>array(
				'type'=>'select',
				'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_ORDERING'),
				'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_ORDERING_DESC'),
				'values'=>array(
					'latest'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_ORDERING_LATEST'),
					'oldest'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_ORDERING_OLDEST'),
					'hits'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_ORDERING_POPULAR'),
					'featured'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_ORDERING_FEATURED'),
					'alphabet_asc'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_ORDERING_ALPHABET_ASC'),
					'alphabet_desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_ORDERING_ALPHABET_DESC'),
					'random'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_ORDERING_RANDOM'),
				),
				'std'=>'latest',
			),

			'limit'=>array(
				'type'=>'number',
				'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_LIMIT'),
				'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_LIMIT_DESC'),
				'std'=>'8'
			),

			'show_intro'=>array(
				'type'=>'checkbox',
				'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_SHOW_INTRO'),
				'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_SHOW_INTRO_DESC'),
				'std'=>0,
			),

			'intro_limit'=>array(
				'type'=>'number',
				'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_INTRO_LIMIT'),
				'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_INTRO_LIMIT_DESC'),
				'std'=>'200',
				'depends'=>array('show_intro'=>'1')
			),
			'social_share'=>array(
				'type'=>'select',
				'title'=>JText::_('COM_SPPAGEBUILDER_SOCIAL_SHARE'),
				'desc'=>JText::_('COM_SPPAGEBUILDER_SOCIAL_SHARE_DESC'),
				'depends'=>array('video_layout'=>'0'),
				'values'=>array(
					1=>JText::_('JYES'),
					0=>JText::_('JNO'),
					),
				'std'=>1,
			),
			'separator_content'=>array(
				'type'=>'separator', 
				'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_THUMB_SLIDER_OPTIONS'),
			),
			'autoplay'=>array(
				'type'=>'checkbox', 
				'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_THUMB_SLIDER_AUTOPLAY'),
				'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_THUMB_SLIDER_AUTOPLAY_DESC'),
				'std'=>0,
			),
			'slider_height'=>array(
				'type'=>'slider',
				'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_THUMB_SLIDER_HEIGHT'),
				'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_THUMB_SLIDER_HEIGHT_DESC'),
				'max'=>4000,
				'responsive'=>true
			),
			'slidesperview'=>array(
				'type'=>'number',
				'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_THUMB_SLIDESPERVIEW'),
				'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_THUMB_SLIDESPERVIEW_DESC'),
				'std'=> 5,
			),
			'class'=>array(
				'type'=>'text',
				'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_CLASS'),
				'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_CLASS_DESC'),
				'std'=>''
			),

		),
		'options' => array(
			'show_author'=>array(
				'type'=>'checkbox',
				'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_SHOW_AUTHOR'),
				'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_SHOW_AUTHOR_DESC'),
				'values'=>array(
					1=>JText::_('COM_SPPAGEBUILDER_YES'),
					0=>JText::_('COM_SPPAGEBUILDER_NO'),
				),
				'std'=>1,
			),

			'show_category'=>array(
				'type'=>'checkbox',
				'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_SHOW_CATEGORY'),
				'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_SHOW_CATEGORY_DESC'),
				'values'=>array(
					1=>JText::_('COM_SPPAGEBUILDER_YES'),
					0=>JText::_('COM_SPPAGEBUILDER_NO'),
				),
				'std'=>1,
			),

			'show_date'=>array(
				'type'=>'checkbox',
				'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_SHOW_DATE'),
				'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_SHOW_DATE_DESC'),
				'values'=>array(
					1=>JText::_('COM_SPPAGEBUILDER_YES'),
					0=>JText::_('COM_SPPAGEBUILDER_NO'),
				),
				'std'=>1,
			),

			'show_readmore'=>array(
				'type'=>'checkbox',
				'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_SHOW_READMORE'),
				'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_SHOW_READMORE_DESC'),
				'values'=>array(
					1=>JText::_('COM_SPPAGEBUILDER_YES'),
					0=>JText::_('COM_SPPAGEBUILDER_NO'),
				),
				'std'=>1,
			),

			'readmore_text'=>array(
				'type'=>'text',
				'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_READMORE_TEXT'),
				'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_READMORE_TEXT_DESC'),
				'std'=>'Read More',
				'depends'=>array('show_readmore'=>'1')
			),

		),
	),
	)
);

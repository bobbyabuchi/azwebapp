<?php
	/*
	# mod_sp_tweet - Twitter Module by JoomShaper.com
	# -----------------------------------------------
	# Author    JoomShaper http://www.joomshaper.com
	# Copyright (C) 2010 - 2014 JoomShaper.com. All Rights Reserved.
	# license - GNU/GPL V2 or Later
	# Websites: http://www.joomshaper.com
	*/
    // no direct access
	
defined( '_JEXEC' ) or die( 'Restricted access' );

use Joomla\CMS\Language\Text;

$moduleclass_sfx = $moduleclass_sfx ?? '';

?>

<div class="sp-tweet" has-scrollbar>
    <?php foreach($data as $i=>$value) { ?>
		<?php  if ( $params->get('animation')=='none' ) { ?>
        <div class="sp-tweet-item <?php echo ($i%2) ? 'sp-tweet-even' : 'sp-tweet-odd' ?><?php if ($i===0) echo ' sp-tweet-first' ?>">
		<?php } else { ?>
			<div class="sp-tweet-item">		
		<?php } ?>
            <?php if ($avatar) { ?>
                <?php if ($linked_avatar) { ?>
                    <a target="<?php echo $target ?>" href="https://twitter.com/<?php echo $value['user']['screen_name'] ?>">
                        <img class="tweet-avatar" src="<?php echo $value['user']['profile_image_url'] ?>" alt="<?php echo $value['user']['name'] ?>" title="<?php echo $value['user']['name'] ?>" width="<?php echo $avatar_width ?>" height="<?php echo $avatar_width ?>" />
                    </a>
                    <?php } else { ?>
                    <img class="tweet-avatar" src="<?php echo $value['user']['profile_image_url']  ?>" alt="<?php echo $value['user']['name']?>" title="<?php echo $value['user']['name'] ?>" width="<?php echo $avatar_width ?>" height="<?php echo $avatar_width ?>" />				
                    <?php } ?>				
                <?php } ?>				

				<?php if($tweet_time) { ?>
					<?php if ($tweet_time_linked) { ?>
						<div class="date"><a target="<?php echo $target ?>" href="https://twitter.com/<?php echo $value['user']['screen_name'] ?>/status/<?php 
						echo  $value['id_str'] ?>"><?php echo  Text::_('ABOUT') . '&nbsp;' . $helper->timeago( $value['created_at'] ) . '&nbsp;' . Text::_('AGO');    ?></a></div>	
						<?php } else { ?>	
						<div class="date"><?php echo $value['created_at'] ?></div>	
						<?php } ?>	
					<?php } ?>	
				<?php if($tweet_src) { ?>
					<div class="source"><?php echo Text::_('FROM') . ' ' . $value['source']?></div>
					<?php } ?>
				<?php if($tweet_time || $tweet_src ) { ?>
					<br />
				<?php } ?>
                <div class="content">
			     	<?php echo $helper->prepareTweet($value['text'])?>
				</div>
            <div class="sp-tweet-clr"></div>
        </div>
    <?php } ?>
</div>
<div class="sp-tweet-clr"></div> 
<?php if ($follow_us) { ?> 
    <a class="followme" target="<?php echo $target ?>" href="https://twitter.com/<?php echo $data[0]['user']['screen_name'] ?>"><?php echo Text::_('FOLLOW') . ' ' . $data[0]['user']['name'] . ' ' . Text::_('ON_TWITTER') ?></a>
<?php } ?>
<div class="sp-tweet-clr"></div>
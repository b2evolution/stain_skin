<?php
/**
 * This is the template that displays the posts for a blog
 *
 * This file is not meant to be called directly.
 * It is meant to be called by an include in the main.page.php template.
 * To display the archive directory, you should call a stub AND pass the right parameters
 * For example: /blogs/index.php?disp=posts
 *
 * b2evolution - {@link http://b2evolution.net/}
 * Released under GNU GPL License - {@link http://b2evolution.net/about/gnu-gpl-license}
 * @copyright (c)2003-2016 by Francois Planque - {@link http://fplanque.com/}
 *
 * @package evoskins
 * @subpackage bootstrap_gallery_skin
 */
if( !defined('EVO_MAIN_INIT') ) die( 'Please, do not access this page directly.' );

global $Item;

 // Default params:
 $params = array_merge( array(
	'before_images'            => '<div class="feature_image">',
	'before_image'             => '<figure class="evo_image_block">',
	'before_image_legend'      => '<figcaption class="evo_image_legend">',
	'after_image_legend'       => '</figcaption>',
	'after_image'              => '</figure>',
	'after_images'             => '</div>',
	'image_class'              => 'img-responsive',
	'image_size'               => 'original',
	'image_limit'              =>  1,
	'image_link_to'            => 'original', // Can be 'original', 'single' or empty
	'excerpt_image_class'      => 'img-responsive',
	'excerpt_image_size'       => 'fit-1280x720',
	'excerpt_image_limit'      => 1,
	'excerpt_image_link_to'    => 'single',
	'include_cover_images'     => false, // Set to true if you want cover images to appear with teaser images.

	'before_gallery'           => '<div class="evo_post_gallery">',
	'after_gallery'            => '</div>',
	'gallery_table_start'      => '',
	'gallery_table_end'        => '',
	'gallery_row_start'        => '',
	'gallery_row_end'          => '',
	'gallery_cell_start'       => '<div class="evo_post_gallery__image">',
	'gallery_cell_end'         => '</div>',
	'gallery_image_size'       => 'crop-80x80',
	'gallery_image_limit'      => 1000,
	'gallery_colls'            => 5,
	'gallery_order'            => '', // Can be 'ASC', 'DESC', 'RAND' or empty

	'excerpt_more_text'        => T_('Read More').' &raquo;',
	'excerpt_before_text'      => '<div class="posts__info_excerpt">',
	'excerpt_after_text'       => '</div>',
	'excerpt_before_more'      => ' <span class="posts__info_excerpt_link">',
	'excerpt_after_more'       => '</span>',
 ), $params );

// ------------------------------- START OF INTRO POST -------------------------------

$post_column = '';
if( $Skin->get_setting( 'posts_show' ) == 'one_column' ){
	$post_column = 'post_full';
};
$column = $Skin->Change_class( 'posts_show' );

$effect = $Skin->Change_class( 'posts_effect' );


?>
<ul id="posts_list" class="posts_list <?php echo $post_column; echo 'effect-'.$effect; ?>">
<?php
if( $Item = get_featured_Item() )
{ // We have a intro-front post to display:
?>
	<li id="<?php $Item->anchor_id() ?>" class="<?php $Item->div_classes( array( 'item_class' => 'evo_posts feature_post '.$column, ) ) ?>" lang="<?php $Item->lang() ?>">
		<div class="main_content_gallery">
		<?php
		$Item->locale_temp_switch(); // Temporarily switch to post locale (useful for multilingual blogs)

		$action_links = $Item->get_edit_link( array( // Link to backoffice for editing
			'before' => '',
			'after'  => '',
			'text'   => $Item->is_intro() ? get_icon( 'edit' ).' '.T_('Edit Intro') : '#',
			'class'  => button_class( 'text' ),
		) );

		if( $Item->status != 'published' ) {
			$Item->format_status( array(
				'template' => '<div class="evo_status evo_status__$status$ badge pull-right">$status_title$</div>',
			) );
		}

		// Categories
		$Item->categories( array(
			'before'          => '<div class="posts__info_cat">',
			'after'           => '</div>',
			'include_main'    => true,
			'include_other'   => true,
			'include_external'=> true,
			'link_categories' => true,
		) );

		$Item->images( array(
			'before'              => $params['before_images'],
			'before_image'        => $params['before_image'],
			'before_image_legend' => $params['before_image_legend'],
			'after_image_legend'  => $params['after_image_legend'],
			'after_image'         => $params['after_image'],
			'after'               => $params['after_images'],
			'image_class'         => $params['image_class'],
			'image_size'          => $params['image_size'],
			'limit'               => $params['image_limit'],
			'image_link_to'       => $params['image_link_to'],
			'before_gallery'      => $params['before_gallery'],
			'after_gallery'       => $params['after_gallery'],
			'gallery_table_start' => $params['gallery_table_start'],
			'gallery_table_end'   => $params['gallery_table_end'],
			'gallery_row_start'   => $params['gallery_row_start'],
			'gallery_row_end'     => $params['gallery_row_end'],
			'gallery_cell_start'  => $params['gallery_cell_start'],
			'gallery_cell_end'    => $params['gallery_cell_end'],
			'gallery_image_size'  => $params['gallery_image_size'],
			'gallery_image_limit' => $params['gallery_image_limit'],
			'gallery_colls'       => $params['gallery_colls'],
			'gallery_order'       => $params['gallery_order'],
			// Optionally restrict to files/images linked to specific position: 'teaser'|'teaserperm'|'teaserlink'|'aftermore'|'inline'|'cover'
			'restrict_to_image_position' => 'teaser',
		) );

		?>
		<div class="posts__info">
		<?php

			$Item->title( array(
				// 'link_type'  => 'none',
				'before'     => '<div class="posts__info_title"><h2>',
				'after'      => '</h2><div class="'.button_class( 'group' ).'">'.$action_links.'</div></div>',
				'nav_target' => false,
			) );

			// Author Avatar
			$Item->author( array(
				'before'       => '<div class="posts__info_author">',
				'after'        => '</div>',
				'before_user'  => '',
				'after_user'   => '',
				'link_text'    => 'only_avatar', // avatar_name | avatar_login | only_avatar | name | login | nickname | firstname | lastname | fullname | preferredname
				'link_class'   => 'author_avatar',
				'thumb_size'   => 'crop-32x32',
				'thumb_class'  => '',
			) );

			// Author Name
			$Item->author( array(
				'before'    => '<div class="posts__info_author">'.T_('By ').'',
				'after'     => '</div>',
				'before_user' => '',
				'after_user'  => '',
				'link_text'   => 'fullname', // avatar_name | avatar_login | only_avatar | name | login | nickname | firstname | lastname | fullname | preferredname
				'link_class'  => 'author_avatar',
				'thumb_size'   => 'crop-48x48',
				'thumb_class'  => '',
			) );

			// We want to display the post time:
			$Item->issue_time( array(
				'before'      => '<time class="posts__info_date">'.T_('On '),
				'after'       => '</time>',
				'time_format' => 'M j, Y',
			) );

			// Content Excerpt
			$Item->excerpt( array(
				'before'              => $params['excerpt_before_text'],
				'after'               => $params['excerpt_after_text'],
				'excerpt_before_more' => $params['excerpt_before_more'],
				'excerpt_after_more'  => $params['excerpt_after_more'],
				'excerpt_more_text'   => $params['excerpt_more_text'],
			) );
		?>
		</div>
		<?php
			locale_restore_previous();	// Restore previous locale (Blog locale)
		?>
		</div>
	</li><!-- .evo_post -->
<?php
// ------------------------------- END OF INTRO-FRONT POST -------------------------------
}

// --------------------------------- START OF POSTS -------------------------------------
// Display message if no post:
$params_no_content = array(
	'before'      => '<div class="msg_nothing">',
	'after'       => '</div>' );
if( ! is_logged_in() )
{ // fp> the following is kind of a hack. It's not really correct.
	$url = get_login_url( 'no public content' );
	$params_no_content['msg_empty'] = '<p>'.T_('This site has no public contents.').'</p><p><a href="'.$url.'">'.T_('Log in now!').'</a></p>';
}
$list_is_empty = display_if_empty( $params_no_content );

if( ! $list_is_empty ) { ?>
   <?php
   	while( $Item = & mainlist_get_item() ){
      // For each blog post, do everything below up to the closing curly brace "}"
		// Temporarily switch to post locale (useful for multilingual blogs)
		$Item->locale_temp_switch();
   ?>
   <li id="<?php $Item->anchor_id() ?>" class="<?php $Item->div_classes( $params ) ?>" lang="<?php $Item->lang() ?>">
		<div class="main__posts_content">
		<a href="<?php echo $Item->get_permanent_url(); ?>" class="evo__post_link"></a>
	   <?php
			// Display images that are linked to this post:
			$item_first_image = $Item->get_images( array(
					'before'              => '<div class="feature__image">',
					'before_image'        => '',
					'before_image_legend' => '',
					'after_image_legend'  => '',
					'after_image'         => '',
					'after'               => '</div>',
					'image_size'          => $Skin->get_setting( 'posts_thumb_size' ),
					'image_link_to'       => 'single',
					'image_desc'          => '',
					'limit'                      => 1,
					'restrict_to_image_position' => 'teaser,aftermore,inline',
					'get_rendered_attachments'   => false,
					// Sort the attachments to get firstly "Cover", then "Teaser", and "After more" as last order
					'links_sql_select'           => ', CASE '
						.'WHEN link_position = "cover"     THEN "1" '
						.'WHEN link_position = "teaser"    THEN "2" '
						.'WHEN link_position = "aftermore" THEN "3" '
						.'WHEN link_position = "inline"    THEN "4" '
						// .'ELSE "99999999"' // Use this line only if you want to put the other position types at the end
						.'END AS position_order',
					'links_sql_orderby'          => 'position_order, link_order',
				) );

			if( empty( $item_first_image ) )
			{ // No images in this post, Display an empty block
				$item_first_image = $Item->get_permanent_link( '<div class="no_image"><img src="'.$Skin->get_url().'assets/images/blank_image.png"></div>', '#', 'album_nopic' );
			}
			else if( $item_first_image == 'plugin_render_attachments' )
			{ // No images, but some attachments(e.g. videos) are rendered by plugins
				$item_first_image = $Item->get_permanent_link( '<b>'.T_('Click to see contents').'</b>', '#', 'album_nopic' );
			}

	      echo $item_first_image;

			?>
			<div class="posts__info">
			<?php

				// Categories
				$Item->categories( array(
					'before'           => '<div class="posts__info_cat">',
					'after'            => '</div>',
					'separator' 		 => '',
					'include_main'     => true,
					'include_other'    => true,
					'include_external' => true,
					'link_categories'  => true,
				) );

				// Display a title
				$Item->title( array(
					'before'    => '<div class="posts__title"><h3>',
					'link_type' => 'permalink', // Use "none" or "permalink"
					'after'     => '</h3></div>',
				) );

				// Author Image
				$Item->author( array(
					'before'    	=> '<div class="posts__info_author">',
					'after'     	=> '</div>',
					'before_user'  => '',
					'after_user'   => '',
					'link_text'    => 'only_avatar', // avatar_name | avatar_login | only_avatar | name | login | nickname | firstname | lastname | fullname | preferredname
					'link_class'   => 'author_avatar',
					'thumb_size'   => 'crop-48x48',
					'thumb_class'  => '',
				) );

				// Author Name
				$Item->author( array(
					'before'    	=> '<div class="posts__info_author">'.T_('By ').'',
					'after'     	=> '</div>',
					'before_user'  => '',
					'after_user'   => '',
					'link_text'    => 'fullname', // avatar_name | avatar_login | only_avatar | name | login | nickname | firstname | lastname | fullname | preferredname
					'link_class'   => 'author_avatar',
					'thumb_size'   => 'crop-48x48',
					'thumb_class'  => '',
				) );

				// We want to display the post time:
				$Item->issue_time( array(
					'before'      => '<time class="posts__info_date">'.T_('On '),
					'after'       => '</time>',
					'time_format' => 'M j, Y',
				) );

			?>
			</div><!-- .posts__info -->
			<span class="posts_divider"></span>
			<?php
			// Restore previous locale (Blog locale)
			locale_restore_previous();
	   ?>
		</div><!-- .main__posts_content -->
   </li><!-- /.evo_post -->
   <?php
	} // ---------------------------------- END OF POSTS ------------------------------------
?>
</ul>
<?php
}

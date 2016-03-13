<?php
/**
 * This is the template that displays the posts with categories for a blog
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

global $Blog;

// Default params:
$params = array_merge( array(
	'item_class'        => 'evo_post evo_content_block',
	'item_type_class'   => 'evo_post__ptyp_',
	'item_status_class' => 'evo_post__',

   'before_images'            => '<div class="evo_post_images">',
   'before_image'             => '<figure class="evo_image_block">',
   'before_image_legend'      => '<figcaption class="evo_image_legend">',
   'after_image_legend'       => '</figcaption>',
   'after_image'              => '</figure>',
   'after_images'             => '</div>',
   'image_class'              => 'img-responsive',
   'image_size'               => 'crop-480x320',
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
   'excerpt_before_text'      => '<div class="evo_post__excerpt_text">',
   'excerpt_after_text'       => '</div>',
   'excerpt_before_more'      => ' <span class="evo_post__excerpt_more_link">',
   'excerpt_after_more'       => '</span>',
), $params );

// ------------------------------- START OF INTRO POST -------------------------------
init_MainList( $Blog->get_setting('posts_per_page') );
if( $Item = get_featured_Item( 'catdir' ) ) { // We have a intro-front post to display: ?>
<div class="posts_gallery">
   <div id="<?php $Item->anchor_id() ?>" class="<?php $Item->div_classes( array( 'item_class' => 'feature_post evo_post' ) ) ?>" lang="<?php $Item->lang() ?>">

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

   	$Item->title( array(
   		'link_type'  => 'none',
   		'before'     => '<div class="evo_post_title"><h2>',
   		'after'      => '</h2><div class="'.button_class( 'group' ).'">'.$action_links.'</div></div>',
   		'nav_target' => false,
   	) );

      $Item->excerpt( array(
			'before'              => $params['excerpt_before_text'],
			'after'               => $params['excerpt_after_text'],
			'excerpt_before_more' => $params['excerpt_before_more'],
			'excerpt_after_more'  => $params['excerpt_after_more'],
			'excerpt_more_text'   => $params['excerpt_more_text'],
		) );

   	// ---------------------- POST CONTENT INCLUDED HERE ----------------------
   	// skin_include( '_item_content.inc.php', $params );
   	// Note: You can customize the default item content by copying the generic
   	// /skins/_item_content.inc.php file into the current skin folder.
   	// -------------------------- END OF POST CONTENT -------------------------

   	locale_restore_previous();	// Restore previous locale (Blog locale)
   	?>
   </div><!-- .evo_post -->
<?php
// ------------------------------- END OF INTRO-FRONT POST -------------------------------
}

// --------------------------------- START OF POSTS -------------------------------------
// Display message if no post:
$params_no_content = array(
	'before'                  => '<div class="msg_nothing">',
	'after'                   => '</div>',
	'msg_empty_logged_in'     => T_('Sorry, there is nothing to display... okokk'),
	// This will display if the collection has not been made private. Otherwise we will be redirected to a login screen anyways
	'msg_empty_not_logged_in' => T_('This site has no public contents.')
);

// Get only root categories of this blog
$ChapterCache = & get_ChapterCache();
$chapters = $ChapterCache->get_chapters( $Blog->ID, 0, true );
// Boolean var to know when at least one post is displayed
$no_content_to_display = true;
if( ! empty( $chapters ) ) { // Display the posts with chapters ?>

   <?php
	foreach( $chapters as $Chapter ) {
		// Get the posts of current category
		$ItemList = new ItemList2( $Blog, $Blog->get_timestamp_min(), $Blog->get_timestamp_max() );
		$ItemList->set_filters( array(
			'cat_array'    => array( $Chapter->ID ), // Limit only by selected cat (exclude posts from child categories)
			'cat_modifier' => NULL,
			'unit'         => 'all', // Display all items of this category, Don't limit by page
		) );
		$ItemList->query();
		if( $ItemList->result_num_rows > 0 ) {
			$no_content_to_display = false;
			?>
				<?php
					while( $Item = & $ItemList->get_item() )
					{ // For each blog post, do everything below up to the closing curly brace "}"
					  // Temporarily switch to post locale (useful for multilingual blogs)
						$Item->locale_temp_switch();
				?>
				<div id="<?php $Item->anchor_id() ?>" class="<?php $Item->div_classes( $params ) ?>" lang="<?php $Item->lang() ?>">
               <div class="main_content_gallery">
                  <a href="<?php echo $Chapter->get_permanent_url(); ?>" class="cat_title">
                     <div class="cat_title_content">
      					   <h2 class="cat_title_link"><?php echo $Chapter->get( 'name' ); ?></h2>
                        <span class="btn_cat">View</span>
                     </div>
                  </a>
      				<?php
      					// Display images that are linked to this post:
      					$item_first_image = $Item->get_images( array(
      						'before'              => '',
      						'before_image'        => '<div class="feature_image">',
      						'before_image_legend' => '',
      						'after_image_legend'  => '',
      						'after_image'         => '</div>',
      						'after'               => '',
      						'image_size'          => $Skin->get_setting( 'posts_thumb_size' ),
      						'image_link_to'       => 'single',
      						'image_desc'          => '',
      						'limit'                      => 1,
      						'restrict_to_image_position' => 'cover,teaser,aftermore,inline',
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
      					// Display a title
                     echo $item_first_image;
      					// echo $Item->get_title( array(
      					// 	'before' => $item_first_image.'<h3 class="title__post">',
                     //    'after'  => '</h3>',
      					// ) );
      					// Restore previous locale (Blog locale)
      					locale_restore_previous();
      				?>
               </div><!-- .main_content_gallery -->
				</div><!-- .evo_post -->

   <?php } // $item_list
	} // Chapter
} // Show Content ?>
   </div> <!-- .content_gallery -->
<?php } // ---------------------------------- END OF POSTS ------------------------------------

if( $no_content_to_display )
{ // No category and no post in this blog
	echo $params_no_content['before']
	.( is_logged_in() ? $params_no_content['msg_empty_logged_in'] : $params_no_content['msg_empty_not_logged_in'] )
	.$params_no_content['after'];
}
?>

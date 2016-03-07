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
), $params );

// ------------------------------- START OF INTRO POST -------------------------------
init_MainList( $Blog->get_setting('posts_per_page') );
if( $Item = get_featured_Item( 'catdir' ) ) { // We have a intro-front post to display: ?>
   <div id="<?php $Item->anchor_id() ?>" class="<?php $Item->div_classes( array( 'item_class' => 'jumbotron evo_post' ) ) ?>" lang="<?php $Item->lang() ?>">

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
   	$Item->title( array(
   		'link_type'  => 'none',
   		'before'     => '<div class="evo_post_title"><h1>',
   		'after'      => '</h1><div class="'.button_class( 'group' ).'">'.$action_links.'</div></div>',
   		'nav_target' => false,
   	) );

   	// ---------------------- POST CONTENT INCLUDED HERE ----------------------
   	skin_include( '_item_content.inc.php', $params );
   	// Note: You can customize the default item content by copying the generic
   	// /skins/_item_content.inc.php file into the current skin folder.
   	// -------------------------- END OF POST CONTENT -------------------------

   	locale_restore_previous();	// Restore previous locale (Blog locale)
   	?>
   </div>
<?php
// ------------------------------- END OF INTRO-FRONT POST -------------------------------
}

// --------------------------------- START OF POSTS -------------------------------------
// Display message if no post:
$params_no_content = array(
	'before' => '<div class="msg_nothing">',
	'after'  => '</div>',
	'msg_empty_logged_in'     => T_('Sorry, there is nothing to display... ggwp'),
	// This will display if the collection has not been made private. Otherwise we will be redirected to a login screen anyways
	'msg_empty_not_logged_in' => T_('This site has no public contents.')
);

// Get only root categories of this blog
$ChapterCache = & get_ChapterCache();
$chapters = $ChapterCache->get_chapters( $Blog->ID, 0, true );
// Boolean var to know when at least one post is displayed
$no_content_to_display = true;

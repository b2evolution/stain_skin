<?php
/**
 * This is the main/default page template.
 *
 * For a quick explanation of b2evo 2.0 skins, please start here:
 * {@link http://b2evolution.net/man/skin-development-primer}
 *
 * It is used to display the blog when no specific page template is available to handle the request.
 *
 * @package evoskins
 * @subpackage bootstrap_gallery_skin
 */
if( !defined('EVO_MAIN_INIT') ) die( 'Please, do not access this page directly.' );

if( evo_version_compare( $app_version, '6.4' ) < 0 )
{ // Older skins (versions 2.x and above) should work on newer b2evo versions, but newer skins may not work on older b2evo versions.
	die( 'This skin is designed for b2evolution 6.4 and above. Please <a href="http://b2evolution.net/downloads/index.html">upgrade your b2evolution</a>.' );
}

global $Skin;
// This is the main template; it may be used to display very different things.
// Do inits depending on current $disp:
skin_init( $disp );
// TODO: move to Skin::display_init
require_js( 'functions.js', 'blog' );	// for opening popup window (comments)
// -------------------------- HTML HEADER INCLUDED HERE --------------------------
skin_include( '_html_header.inc.php', array(
		'arcdir_text'     => T_('Index'),
		'catdir_text'     => T_('Galleries'),
		'category_text'   => T_('Gallery').': ',
		'categories_text' => T_('Galleries').': ',
	) );
// -------------------------------- END OF HEADER --------------------------------

// ---------------------------- SITE HEADER INCLUDED HERE ----------------------------
// If site headers are enabled, they will be included here:
skin_include( '_body_header.inc.php' );
// ------------------------------- END OF SITE HEADER --------------------------------
?>
<div class="container">
   <main id="main_content"><!-- This is were a link like "Jump to main content" would land -->
   	<!-- ================================= START OF MAIN AREA ================================== -->
   	<?php
   		// ------------------------- MESSAGES GENERATED FROM ACTIONS -------------------------
   		messages( array(
   			'block_start' => '<div class="row"><div class="col-xs-12 action_messages">',
   			'block_end'   => '</div></div>',
   		) );
   		// --------------------------------- END OF MESSAGES ---------------------------------
   	?>

   	<?php
   	// ------------------------- TITLE FOR THE CURRENT REQUEST -------------------------
   	request_title( array(
			'title_before'      => '<div class="row"><div class="col-xs-12"><h2>',
			'title_after'       => '</h2></div></div>',
			'title_none'        => '',
			'glue'              => ' - ',
			'title_single_disp' => false,
			'format'            => 'htmlbody',
			'arcdir_text'       => T_('Index'),
			'catdir_text'       => '',
			'category_text'     => T_('Gallery').': ',
			'categories_text'   => T_('Galleries').': ',
			'user_text'         => '',
			'display_edit_links'=> false,
		) );
   	// ------------------------------ END OF REQUEST TITLE -----------------------------
   	?>

   	<div class="row">
   		<div class="col-xs-12">
   		<?php
   			// -------------- MAIN CONTENT TEMPLATE INCLUDED HERE (Based on $disp) --------------
   			skin_include( '$disp$', array(
   					'mediaidx_thumb_size'  => $Skin->get_setting( 'mediaidx_thumb_size' ),
   					'author_link_text'     => 'preferredname',
   					'item_class'           => 'evo_post evo_content_block',
   					'item_type_class'      => 'evo_post__ptyp_',
   					'item_status_class'    => 'evo_post__',
   					// Login
   					'login_page_before'    => '<div class="login_block"><div class="evo_details">',
   					'login_page_after'     => '</div></div>',
   					// Register
   					'register_page_before' => '<div class="login_block"><div class="evo_details">',
   					'register_page_after'  => '</div></div>',
   					'display_abort_link'   => ( $Blog->get_setting( 'allow_access' ) == 'public' ), // Display link to abort login only when it is really possible
   				) );
   			// Note: you can customize any of the sub templates included here by
   			// copying the matching php file into your skin directory.
   			// ------------------------- END OF MAIN CONTENT TEMPLATE ---------------------------
   		?>
   		</div><!-- .col -->
   	</div><!-- .row -->

   	<?php
   	if( $disp != 'catdir' )
   	{	// Don't display the pages on disp=catdir because we don't have a limit by page there
   		// -------------------- PREV/NEXT PAGE LINKS (POST LIST MODE) --------------------
   		mainlist_page_links( array(
				'block_start' => '<div class="nav_pages">',
				'block_end'   => '</div>',
				'prev_text'   => '&lt;&lt;',
				'next_text'   => '&gt;&gt;',
			) );
   		// ------------------------- END OF PREV/NEXT PAGE LINKS -------------------------
   	}
   	?>

   </main>
</div><!-- .container -->


<?php
// ---------------------------- SITE FOOTER INCLUDED HERE ----------------------------
// If site footers are enabled, they will be included here:
skin_include( '_body_footer.inc.php' );
// ------------------------------- END OF SITE FOOTER --------------------------------


// ------------------------- HTML FOOTER INCLUDED HERE --------------------------
skin_include( '_html_footer.inc.php' );
// ------------------------------- END OF FOOTER --------------------------------
?>

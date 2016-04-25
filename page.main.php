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
// -------------------------- HTML HEADER INCLUDED HERE --------------------------
skin_include( '_html_header.inc.php', array() );
// -------------------------------- END OF HEADER --------------------------------

// ---------------------------- SITE HEADER INCLUDED HERE ----------------------------
// If site headers are enabled, they will be included here:
skin_include( '_body_header.inc.php' );
// ------------------------------- END OF SITE HEADER --------------------------------

?>

<main id="content"><!-- This is were a link like "Jump to main content" would land -->
	<div class="container">
		<div class="main_content">
			<!-- =================================== START OF POST TITLE BAR =================================== -->
			<?php
			// ------------------------- MESSAGES GENERATED FROM ACTIONS -------------------------
			messages( array(
				'block_start' => '<div class="row"><div class="col-xs-12 action_messages">',
				'block_end'   => '</div></div>',
			) );
			// --------------------------------- END OF MESSAGES ---------------------------------
			?>

			<article>
				<?php $Item->locale_temp_switch(); // Temporarily switch to post locale (useful for multilingual blogs) ?>

				<div class="evo_post_content">
					<div class="evo_details">
						<div class="evo_container evo_container__item_single">
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
						</div>

						<div class="item_comments">
							<?php
							// ------------------ FEEDBACK (COMMENTS/TRACKBACKS) INCLUDED HERE ------------------
							skin_include( '_item_feedback.inc.php', array(
								'before_section_title' => '<h4 class="tite_comment_status">',
								'after_section_title'  => '</h4>',
								'author_link_text'     => 'preferredname',
								'comment_image_size'   => 'fit-256x256',

								// Pagination:
								'pagination' => array(
									'block_start'           => '<div class="center"><ul class="pagination">',
									'block_end'             => '</ul></div>',
									'page_current_template' => '<span>$page_num$</span>',
									'page_item_before'      => '<li>',
									'page_item_after'       => '</li>',
									'page_item_current_before' => '<li class="active">',
									'page_item_current_after'  => '</li>',
									'prev_text'             => '<i class="fa fa-angle-double-left"></i>',
									'next_text'             => '<i class="fa fa-angle-double-right"></i>',
								),
							) );
							// Note: You can customize the default item feedback by copying the generic
							// /skins/_item_feedback.inc.php file into the current skin folder.
							// ---------------------- END OF FEEDBACK (COMMENTS/TRACKBACKS) ---------------------
							?>

							<?php
							if( evo_version_compare( $app_version, '6.7' ) >= 0 )
							{  // We are running at least b2evo 6.7, so we can include this file:
								// ------------------ WORKFLOW PROPERTIES INCLUDED HERE ------------------
								skin_include( '_item_workflow.inc.php' );
								// ---------------------- END OF WORKFLOW PROPERTIES ---------------------
							}
							?>

							<?php
							if( evo_version_compare( $app_version, '6.7' ) >= 0 )
							{  // We are running at least b2evo 6.7, so we can include this file:
								// ------------------ META COMMENTS INCLUDED HERE ------------------
								skin_include( '_item_meta_comments.inc.php', array(
									'comment_start'  => '<article class="evo_comment evo_comment__meta panel panel-default">',
									'comment_end'    => '</article>',
								) );
								// ---------------------- END OF META COMMENTS ---------------------
							}
							?>
						</div><!-- .item_comments -->

					</div><!-- .evo_details -->
				</div><!-- .evo_post_content -->

				<?php
				locale_restore_previous();	// Restore previous locale (Blog locale)
				?>

			</article><!-- .row -->
		</div><!-- .main_content -->
	</div><!-- .container -->
</main><!-- #main_content -->


<?php
// ---------------------------- SITE FOOTER INCLUDED HERE ----------------------------
// If site footers are enabled, they will be included here:
skin_include( '_body_footer.inc.php' );
// ------------------------------- END OF SITE FOOTER --------------------------------


// ------------------------- HTML FOOTER INCLUDED HERE --------------------------
skin_include( '_html_footer.inc.php' );
// ------------------------------- END OF FOOTER --------------------------------
?>

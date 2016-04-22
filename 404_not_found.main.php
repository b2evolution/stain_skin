<?php
/**
 * This is the 404 page template for the "Error 404" skin.
 *
 * This skin only uses one single template which includes most of its features.
 * It will also rely on default includes for specific dispays (like the comment form).
 *
 * For a quick explanation of b2evo 2.0 skins, please start here:
 * {@link http://b2evolution.net/man/skin-development-primer}
 *
 * The main page template is used to display the blog when no specific page template is available
 * to handle the request (based on $disp).
 *
 * @package evoskins
 * @subpackage bootstrap_manual
 */
if( !defined('EVO_MAIN_INIT') ) die( 'Please, do not access this page directly.' );
if( evo_version_compare( $app_version, '6.4' ) < 0 )
{ // Older skins (versions 2.x and above) should work on newer b2evo versions, but newer skins may not work on older b2evo versions.
	die( 'This skin is designed for b2evolution 6.4 and above. Please <a href="http://b2evolution.net/downloads/index.html">upgrade your b2evolution</a>.' );
}

global $Blog;

// This is the main template; it may be used to display very different things.
// Do inits depending on current $disp:
skin_init( $disp );

// -------------------------- HTML HEADER INCLUDED HERE ------------------------------
skin_include( '_html_header.inc.php', array() );
// -------------------------------- END OF HEADER ------------------------------------


// ---------------------------- SITE HEADER INCLUDED HERE ----------------------------
// If site headers are enabled, they will be included here:
skin_include( '_body_header.inc.php' );
// ------------------------------- END OF SITE HEADER --------------------------------

?>

<main id="content">
    <div class="container">
        <div class="main_content">
    		<!-- =================================== START OF MAIN AREA =================================== -->
    		<?php
    			if( ! in_array( $disp, array( 'login', 'lostpassword', 'register', 'activateinfo' ) ) )
    			{ // Don't display the messages here because they are displayed inside wrapper to have the same width as form
    				// ------------------------- MESSAGES GENERATED FROM ACTIONS -------------------------
    				messages( array(
    					'block_start' => '<div class="action_messages">',
    					'block_end'   => '</div>',
    				) );
    				// --------------------------------- END OF MESSAGES ---------------------------------
    			}
    			if( ! empty( $cat ) )
    			{ // Display breadcrumbs if some category is selected
    				skin_widget( array(
    					// CODE for the widget:
    					'widget' => 'breadcrumb_path',
    					// Optional display params
    					'block_start'      => '<nav><ol class="breadcrumb">',
    					'block_end'        => '</ol></nav>',
    					'separator'        => '',
    					'item_mask'        => '<li><a href="$url$">$title$</a></li>',
    					'item_active_mask' => '<li class="active">$title$</li>',
    				) );
    			}
    		?>

    		<?php
    			// ------------------------ TITLE FOR THE CURRENT REQUEST ------------------------
    			request_title( array(
    				'title_before'      => '<h1 class="page_title">',
    				'title_after'       => '</h1>',
    				'title_single_disp' => false,
    				'title_page_disp'   => false,
    				'format'            => 'htmlbody',
    				'category_text'     => '',
    				'categories_text'   => '',
    				'catdir_text'       => '',
    				'front_text'        => '',
    				'posts_text'        => '',
    				'register_text'     => '',
    				'login_text'        => '',
    				'lostpassword_text' => '',
    				'account_activation' => '',
    				'msgform_text'      => '',
    				'user_text'         => '',
    				'users_text'        => '',
    				'display_edit_links'=> false,
    			) );
    			// ----------------------------- END OF REQUEST TITLE ----------------------------
            ?>

            <div class="error_404">
                <div class="error_404_content">
                    <h1>Ooops!</h1>
                    <p>We can't seem to find the page you're <span>l<span>oo</span>king</span> for.</p>
                    <span>Error code: 404</span>
                </div>

                <div class="error_back">
                    <a href="<?php echo $Blog->get('url'); ?>" class="btn back_to_home">Back to home</a>
                </div>
            </div><!-- .error_404 -->
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

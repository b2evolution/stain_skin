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

global $Blog;

?>
<div class="error_404">
    <div class="error_404_content">
        <h1>Ooops!</h1>
        <p>We can't seem to find the page you're looking for.</p>
        <span>Error code: 403</span>
    </div>

    <div class="error_back">
        <a href="<?php echo $Blog->get('url'); ?>" class="btn back_to_home">Back to home</a>
    </div>
</div><!-- .error_404 -->

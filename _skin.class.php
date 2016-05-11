<?php
/**
 * This file implements a class derived of the generic Skin class in order to provide custom code for
 * the skin in this folder.
 *
 * This file is part of the b2evolution project - {@link http://b2evolution.net/}
 *
 * @package evoskins
 * @subpackage bootstrap_gallery_skin
 */
if( !defined('EVO_MAIN_INIT') ) die( 'Please, do not access this page directly.' );

/**
 * Specific code for this skin.
 *
 * ATTENTION: if you make a new skin you have to change the class name below accordingly
 */
class stain_gallery_Skin extends Skin
{

	/**
	 * Do we want to use style.min.css instead of style.css ?
	 */
	var $use_min_css = 'check';  // true|false|'check' Set this to true for better optimization
	/**
	 * Get default name for the skin.
	 * Note: the admin can customize it.
	 */
	function get_default_name()
	{
		return 'Stain Gallery Skin';
	}


	/**
	 * Get default type for the skin.
	 */
	function get_default_type()
	{
		return 'normal';
	}


	/**
	 * What evoSkins API does has this skin been designed with?
	 *
	 * This determines where we get the fallback templates from (skins_fallback_v*)
	 * (allows to use new markup in new b2evolution versions)
	 */
	function get_api_version()
	{
		return 6;
	}

   /**
   * Get supported collection kinds.
   *
   * This should be overloaded in skins.
   *
   * For each kind the answer could be:
   * - 'yes' : this skin does support that collection kind (the result will be was is expected)
   * - 'partial' : this skin is not a primary choice for this collection kind (but still produces an output that makes sense)
   * - 'maybe' : this skin has not been tested with this collection kind
   * - 'no' : this skin does not support that collection kind (the result would not be what is expected)
   * There may be more possible answers in the future...
   */
   public function get_supported_coll_kinds()
   {
	   $supported_kinds = array(
		   'main'   => 'no',
		   'std'    => 'yes',		// Blog
		   'photo'  => 'Yes',
		   'forum'  => 'no',
		   'manual' => 'maybe',
		   'group'  => 'no',  // Tracker
		   // Any kind that is not listed should be considered as "maybe" supported
	   );
	   return $supported_kinds;
   }

   /**
   * Judge if the file is the image we want to use
   *
   * @param string filepath: the path of a file
   * array arr_types: the file type we want to use
   * @return array
   */
   function isImage( $filepath, $arr_types=array( ".gif", ".jpeg", ".png", ".bmp", ".jpg" ) )
   {
       if(file_exists($filepath)) {
		   $info = getimagesize($filepath);
		   $ext  = image_type_to_extension($info['2']);
		   return in_array($ext,$arr_types);
	   } else {
		   return false;
	   }
   }

	/**
	* Get the pictures of one local folder as an array
	*
	* @param string img_folder; the image folder;
	* string img_folder_url; folder url, we would like to show the img of this folder on the screen for user viewing;
	* int thumb_width: thumb image whdth shown on the skin setting page
	* int thumb_height: thumb image height shown on the skin setting page
	* @return array
	*/
	function get_arr_pics_from_folder( $img_folder, $img_folder_url, $thumb_width = 50, $thumb_height = 50 )
	{
	   $arr_filenames = $filesnames =array();
	   if(file_exists($img_folder))
	   {
		   $filesnames = scandir($img_folder);
	   }
	   $count = 0;
	   foreach ( $filesnames as $name )
	   {
		   $count++;
		   if ( $name != "." && $name != ".." && $name != "_evocache" && $this->isImage($img_folder.$name) ) //not the folder and other files
		   {
			   $arr_filenames[] = array( $img_folder_url.$name,
			   "<a href='".$img_folder_url.$name."' target='blank'><img src='".$img_folder_url.$name."' width=".$thumb_width."px heigh=".$thumb_height."px /></a>" );
		   }
		   if ($count==30) break; // The max number of the images we want to show
	   }
	   $arr_filenames[] = array("none",T_("Transparent"));
	   return $arr_filenames;
	}


	/**
	 * Get definitions for editable params
	 *
	 * @see Plugin::GetDefaultSettings()
	 * @param local params like 'for_editing' => true
	 */
	function get_param_definitions( $params ) {

		global $Blog;

		// Load to use function get_available_thumb_sizes()
		load_funcs( 'files/model/_image.funcs.php' );
		load_class( 'widgets/model/_widget.class.php', 'ComponentWidget' );

		// System provide bg images
		$bodybg_cat = 'assets/images/header/'; // Background images folder relative to this skin folder
		$arr_bodybg = $this -> get_arr_pics_from_folder( $this->get_path().$bodybg_cat, $this->get_url().$bodybg_cat, 80, 80 );

		// User Custom bg images
		$custom_headerbg_cat  = "headerbg/"; // Background images folder which created by users themselves, and it's relative to collection media dir
		$arr_custom_headerbg = $this->get_arr_pics_from_folder( $Blog->get_media_dir().$custom_headerbg_cat, $Blog->get_media_url().$custom_headerbg_cat, 65 ,65);

		$r = array_merge( array(

			/* General Setting
			* ========================================================================== */
			'section_general_start' => array(
				'layout'	=> 'begin_fieldset',
				'label' 	=> T_( 'General Options' ),
			),
				'color_scheme' => array(
					'label'			=> T_( 'Color Scheme' ),
					'note'			=> T_( 'Change your favorite color scheme,' ),
					'type'			=> 'color',
					'defaultvalue'	=> '#18C54D',
				),
				'body_color' => array(
					'label'			=> T_( 'Color Body Text' ),
					'note'			=> T_( 'Change color text for body. Default color is: #333333' ),
					'type'			=> 'color',
					'defaultvalue'	=> '#444444'
				),
				'body_background' => array(
					'label'        => T_( 'Background Body' ),
					'note'         => T_( 'Change background for All Disp and Page' ),
					'type'         => 'color',
					'defaultvalue' => '#F6F6F6',
				),
			'section_general_end' => array(
				'layout'	=> 'end_fieldset',
			),


			/* Page Styles
			* ========================================================================== */
			'section_page_start' => array(
				'layout' => 'begin_fieldset',
				'label'  => T_('Page Styles')
			),
				'page_text_size' => array(
					'label'        => T_('Page text size'),
					'note'         => T_('Default value is 14 pixels.'),
					'defaultvalue' => '14px',
					'size'         => '4px',
					'type'         => 'text',
				),
				'page_text_color' => array(
					'label'        => T_('Page text color'),
					'note'         => T_('E-g: #00ff00 for green'),
					'defaultvalue' => '#333',
					'type'         => 'color',
				),
				'page_link_color' => array(
					'label'        => T_('Page link color'),
					'note'         => T_('E-g: #00ff00 for green'),
					'defaultvalue' => '#337ab7',
					'type'         => 'color',
				),
				'current_tab_text_color' => array(
					'label'        => T_('Current tab text color'),
					'note'         => T_('E-g: #ff6600 for orange'),
					'defaultvalue' => '#333',
					'type'         => 'color',
				),
				'page_bg_color' => array(
					'label'        => T_('Page background color'),
					'note'         => T_('E-g: #ff0000 for red'),
					'defaultvalue' => '#fff',
					'type'         => 'color',
				),
			'section_page_end' => array(
				'layout' => 'end_fieldset',
			),

			/* Header Options
			* ========================================================================== */
			'section_header_start' => array(
				'layout'   => 'begin_fieldset',
				'label'    => T_( 'Header Options' )
			),
				'header_height' => array(
					'label'        => T_('Height'),
					'note'         => T_( 'px <br> Set <strong>Height</strong> the Header.' ),
					'type'         => 'integer',
					'defaultvalue' => '300',
					'size'         => '3px',
					'allow_empty'  => false,
				),
				'header_bg_type' => array(
					'label'			=> T_( 'Background Image Source' ),
					'note'			=> T_( '<br />Select the source for background image, you can choose default background image on file asset or upload custom background image' ),
					'type'			=> 'select',
					'options'		=> array(
						'images' 		=> T_( 'Image Asset' ),
						'custom_image'  => T_( 'Custom Backgroung Image' ),
					),
					'defaultvalue'	=> 'images',
				),
				'header_bg' => array(
					'label'        => T_( 'Background Image' ),
					'note'         => T_( '' ),
					'type'         => 'radio',
					'options'      => $arr_bodybg,
					'defaultvalue' => reset( $arr_bodybg[3] ),
				),
				'header_custom_bg' => array(
					'label'			=> T_( 'User Custom Background Image' ),
					'note'			=> T_('（Please create a folder named <b><i>'.str_replace("/","",$custom_headerbg_cat).'</i></b> in your collection media folder and put the images into it. Now <a href="admin.php?ctrl=files" target="_blank"><i>Create folder or Upload images</i></a>）'),
					'type'         => 'radio',
					'options'      => $arr_custom_headerbg,
					'defaultvalue' => reset($arr_custom_headerbg[0]),
				),
				'header_bg_position_x' => array(
					'label'        => T_( 'Background Position X' ),
					'note'         => T_( '% <br>Default value is <strong>50%</strong>.' ),
					'type'         => 'integer',
					'defaultvalue' => '50',
					'size'         => 3,
				),
				'header_bg_position_y' => array(
					'label'        => T_( 'Background Position Y' ),
					'note'         => T_( '% <br>Default value is <strong>50%</strong>.' ),
					'type'         => 'integer',
					'defaultvalue' => '50',
					'size'         => 3,
				),
				'header_bg_attach' => array(
					'label'        => T_( 'Background Attachment' ),
					'note'         => T_( '' ),
					'type'         => 'radio',
					'defaultvalue' => 'initial',
					'options'      => array(
						array( 'initial', T_( 'Initial' ) ),
						array( 'fixed', T_( 'Fixed' ) ),
					),
				),
				'header_bg_size' => array(
					'label'        => T_( 'Background Size' ),
					'note'         => T_( 'Set the background size.' ),
					'type'         => 'select',
					'options'      => array(
						'initial'  => T_( 'Initial' ),
						'contain'  => T_( 'Contain' ),
						'cover'    => T_( 'Cover' ),
					),
					'defaultvalue' => 'cover',
				),
				'header_overlay' => array(
					'label'        => T_( 'Color Overlay' ),
					'note'         => T_( 'Check if you want to show <strong>Color Overlay</strong> for Header.' ),
					'type'         => 'checkbox',
					'defaultvalue' => 1,
				),
				'color_overlay' => array(
					'label'        => T_( 'Change Color Overlay' ),
					'note'         => T_( 'Set your favorite color for Header Color Overlay' ),
					'type'         => 'color',
					'defaultvalue' => '#000000',
				),
				'opcity_cv' => array(
					'label'        => T_( 'Opacity Color Overlay' ),
					'note'         => T_( 'Set the opacity Color Overlay value is 0.1 - 1' ),
					'type'         => 'select',
					'options'      => array(
						'0'	   => T_( '0' ),
						'0.1'  => T_( '0.1' ),
						'0.15' => T_( '0.15' ),
						'0.2'  => T_( '0.2' ),
						'0.25' => T_( '0.25' ),
						'0.3'  => T_( '0.3' ),
						'0.35' => T_( '0.35' ),
						'0.4'  => T_( '0.4' ),
						'0.45' => T_( '0.45' ),
						'0.5'  => T_( '0.5' ),
						'0.55' => T_( '0.55' ),
						'0.6'  => T_( '0.6' ),
						'0.65' => T_( '0.65' ),
						'0.7'  => T_( '0.7' ),
						'0.75' => T_( '0.75' ),
						'0.8'  => T_( '0.8' ),
						'0.85' => T_( '0.85' ),
						'0.9'  => T_( '0.9' ),
						'0.95' => T_( '0.95' ),
						'1'   => T_( '1' ),
					),
					'defaultvalue' => '0.2',
				),
				'header_content_top' => array(
					'label'			=> T_( 'Content Top Position' ),
					'note'			=> T_( '%. Change the content top position if you have many content in the header. Default value is <strong>38%</strong>' ),
					'type'			=> 'integer',
					'allow_empty'	=> false,
					'defaultvalue'	=> '38',
					'size'			=> 5,
				),
				'header_content_align' => array(
					'label'        => T_( 'Content Align' ),
					'note'         => T_(''),
					'type'         => 'radio',
					'defaultvalue' => 'center',
					'options'      => array(
						array( 'left', T_( 'Left' ) ),
						array( 'center', T_( 'Center' ) ),
						array( 'right', T_( 'Right' ) ),
					),
				),
				'header_color_heading' => array(
					'label'			=> T_( 'Color Text Heading' ),
					'note'			=> T_( 'Change color text heading and choose your favorite color.' ),
					'type'			=> 'color',
					'defaultvalue'	=> '#ffffff',
				),
				'header_color_tagline' => array(
					'label'			=> T_( 'Color Text Tagline' ),
					'note'			=> T_( 'Change color text tagline with your favorite color.' ),
					'type'			=> 'color',
					'defaultvalue'	=> '#ffffff',
				),
				'header_text_shadow_content' => array(
					'label'			=> T_( 'Show Text Shadow Content' ),
					'note'			=> T_( 'Checklist to show text-shadow on text content' ),
					'type'			=> 'checkbox',
					'default'		=> 1,
				),
			'section_header_end' => array(
				'layout'   => 'end_fieldset'
			),

			/* Main Navigation
			* ========================================================================== */
			'section_nav_start' => array(
				'layout' => 'begin_fieldset',
				'label'  => T_('Main Navigation'),
			),
				'nav_bg'  => array(
					'label'        => T_( 'Background Color' ),
					'note'         => T_( 'Change background color main navigation. Default value is <strong>#1B1B1B</strong>' ),
					'type'         => 'color',
					'defaultvalue' => '#1B1B1B',
					'size'		   => 20,
				),
				'nav_sticky' => array(
					'label'        => T_( 'Sticky Mode' ),
					'note'         => T_( 'Check to enable <strong>Sticky Nav</strong>.' ),
					'type'         => 'checkbox',
					'defaultvalue' => 1,
				),
				'nav_sticky_shadow' => array(
					'label'			=> T_( 'Show Box Shadow' ),
					'note'			=> T_( 'Checklist to show box-shadow Main Menu if sticky activated.' ),
					'type'			=> 'checkbox',
					'defaultvalue'	=> 1,
				),
				'nav_align' => array(
					'label'        => T_( 'Menu Align' ),
					'note'         => T_(''),
					'type'         => 'radio',
					'defaultvalue' => 'center',
					'options'      => array(
						array( 'left', T_( 'Left' ) ),
						array( 'center', T_( 'Center' ) ),
						array( 'right', T_( 'Right' ) ),
					),
				),
				'nav_color' => array(
					'label'        => T_( 'Nav Color' ),
					'note'         => T_( 'Set the color link menu. Default value is <strong>Empty</strong>' ),
					'type'         => 'color',
					'defaultvalue' => '',
				),
				'nav_color_hov' => array(
					'label'        => T_( 'Nav Color Hover' ),
					'note'         => T_( 'Set the color when hover. Default value is #FFFFFF' ),
					'type'         => 'color',
					'defaultvalue' => '#FFFFFF',
				),
			'section_nav_end' => array(
				'layout'   => 'end_fieldset',
			),

			/* Gallery Featured Post Options
			* ========================================================================== */
			'section_gallery_featured_start' => array(
				'layout'      => 'begin_fieldset',
				'label'       => T_( 'Gallery Featured Post Options' ),
			),
				'gal_feature_cat' => array(
					'label'		   => T_( 'Show Post Cagetory' ),
					'note'		   => T_( 'Check the box to show category on featured post.' ),
					'type'		   => 'checkbox',
					'defaultvalue' => 1,
				),
			'section_gallery_featured_end' => array(
				'layout'      => 'end_fieldset',
			),


			/* Content Options
			* ========================================================================== */
			'section_gallery_start' => array(
				'layout'	=> 'begin_fieldset',
				'label' 	=> T_( 'Gallery Options' ),
			),
				'gallery_show' => array(
					'label'        => T_( 'Column Gallery' ),
					'note'         => T_( '' ),
					'type'         => 'radio',
					'options'      => array(
						// array( 'one_column', T_( '1 Column' ) ),
						array( 'two_column', T_( '2 Column' ) ),
						array( 'three_column', T_( '3 Column' ) ),
						array( 'four_column', T_( '4 Column' ) ),
						array( 'random', T_( 'Random' ) ),
					),
					'defaultvalue' => 'three_column',
				),
				'gallery_thumb_size' => array(
					'label'        => T_('Thumbnail size for Albums'),
					'note'         => '',
					'defaultvalue' => 'fit-1280x720',
					'type'         => 'select',
					'options'      => array(
						'original' 		=> T_( 'Original' ),
						'fit-1280x720'	=> T_( 'fit-1280x720' ),
						'crop-480x320'	=> T_( 'crop-480x320' ),
					),
				),
				'gallery_effect' => array(
					'label'		=> T_( 'Choose the Animation Image' ),
					'note'		=> T_( 'Select your favorite Animation load for first open page.' ),
					'type'		=> 'select',
					'options'	=> array(
						'0'	=> T_('None'),
						'1' => T_('Opacity'),
						'2' => T_('Move Up'),
						'3' => T_('Sclae Up'),
						'4' => T_('Fall Perspective'),
						'5' => T_('Fly'),
						'6' => T_('Flip'),
						'7' => T_('Helix'),
						'8' => T_('Pop Up'),
					),
					'defaultvalue' => '2',
				),
				'gallery_hover_style' => array(
					'label'        => T_( 'Style Image Hover' ),
					'note'         => T_( 'Select the favorite Image Hover Style for Gallery.' ),
					'type'         => 'select',
					'defaultvalue' => 'flip',
					'options'      => array(
						'default'      => T_( 'Default' ),
						'flip'         => T_( 'Flip' ),
						'zoom'         => T_( 'Zoom' ),
						'sweep_right'  => T_( 'Sweep Right' ),
						'sweep_left'  => T_( 'Sweep Left' ),
						// '4'  => T_( 'Style 4' ),
					),
				),
				'gallery_gutter' => array(
					'label'			=> T_( 'Spance Gallery' ),
					'note'			=> T_( 'px. Set the space for Gallery Images.' ),
					'type'		   	=> 'integer',
					'defaultvalue' 	=> '5',
					'size'		   	=> 5
				),
				'gallery_bg' => array(
					'label'        => T_( 'Background Gallery Content' ),
					'note'         => T_( 'Change background content Gallery if the image is hover.' ),
					'type'         => 'color',
					'defaultvalue' => '#FFFFFF'
				),
				'gallery_shadow' => array(
					'label'        => T_( 'Show Box Shadow' ),
					'note'         => T_( 'Check for show Box Shadow content when Image Gallery is hover.' ),
					'type'         => 'checkbox',
					'defaultvalue' => 1,
				),
				'cat_title_size' => array(
					'label'        => T_( 'Size Title Category' ),
					'note'         => T_( 'px. Change font size for Title Category.' ),
					'type'         => 'integer',
					'defaultvalue' => '28',
					'size'         => 3,
				),
				'cat_title_color' => array(
					'label'        => T_( 'Color Title Category' ),
					'note'         => T_( 'Change the color of title category.' ),
					'type'         => 'color',
					'defaultvalue' => '#444444'
				),
				'cat_view_bg' => array(
					'label'        => T_( 'Background Button View' ),
					'note'         => T_( 'Change the background color of button view when the button when hover. Default is <strong>Empty</strong>' ),
					'type'         => 'color',
					'defaultvalue' => '',
				),
				'cat_view_text' => array(
					'label'        => T_( 'Color Text Button View' ),
					'note'         => T_( 'Change the Color Text for Button View when hover. Default is <strong>Empty</strong>' ),
					'type'         => 'color',
					'defaultvalue' => '',
				),
			'section_gallery_end' => array(
				'layout'	=> 'end_fieldset',
			),

			/* Posts Options
			* ========================================================================== */
			'section_posts_start' => array(
				'layout'   => 'begin_fieldset',
				'label'    => T_( 'Posts Options' ),
			),
				'posts_thumb_size' => array(
					'label'        => T_('Thumbnail size in Posts List'),
					'note'         => '',
					'defaultvalue' => 'fit-1280x720',
					'size'			=> 10,
					'type'         => 'select',
					'options'      => array(
						'original' => T_( 'Original' ),
						'fit-1280x720' => T_( 'fit-1280x720' ),
						'crop-480x320' => T_( 'crop-480x320' ),
					),
				),
				'posts_show' => array(
					'label'        => T_( 'Column Posts' ),
					'note'         => T_( '' ),
					'type'         => 'radio',
					'options'      => array(
						// array( 'one_column', T_( '1 Column' ) ),
						array( 'one_column', T_( '1 Column' ) ),
						array( 'two_column', T_( '2 Column' ) ),
						array( 'three_column', T_( '3 Column' ) ),
					),
					'defaultvalue' => 'three_column'
				),
				'posts_list_space' => array(
					'label'			=> T_( 'Posts List Padding' ),
					'note'			=> T_( 'px. Set the padding for posts list.' ),
					'type'			=> 'integer',
					'defaultvalue' => '4',
					'size'			=> 3,
					'allow_empty'  => false,
				),
			'section_posts_end' => array(
				'layout'   => 'end_fieldset',
			),


			/* Single Options
			 * ========================================================================== */
			'section_single_start' => array(
				'layout'		=> 'begin_fieldset',
				'label'		=> T_( 'Single Options' ),
			),
				'single_layout' => array(
					'label'			=> T_( 'Layout' ),
					'note'			=> T_( 'Change the layout for single disp.' ),
					'defaultvalue'  => 'single_column',
					'type'			=> 'select',
					'options'		=> array(
						'single_column'        => T_('Single Column Large'),
						'single_column_normal' => T_('Single Column'),
						'left_sidebar'         => T_('Left Sidebar'),
						'right_sidebar'        => T_('Right Sidebar'),
					),
				),
				'single_thumb_size' => array(
					'label'        => T_('Thumbnail size for Single Disp'),
					'note'         => '',
					'defaultvalue' => 'crop-480x320',
					'options'      => get_available_thumb_sizes(),
					'type'         => 'select',
				),
			'section_single_end' => array(
				'layout'		=> 'end_fieldset',
			),

			/* Footer Options
			* ========================================================================== */
			'section_footer_start' => array(
				'layout'  => 'begin_fieldset',
				'label'   => T_( 'Footer Options' ),
			),
				'footer_bg' => array(
					'label'        => T_( 'Background Color' ),
					'note'         => T_( 'Change the main footer background color, default value is #0E1215' ),
					'type'         => 'color',
					'defaultvalue' => '#0E1215',
				),
				'footer_widget' => array(
					'label'        => T_( 'Enable Footer Widget' ),
					'note'         => T_( 'Check to Enable Widget Footer. And add widget content on menu <strong>Widgets</strong>.' ),
					'type'         => 'checkbox',
					'defaultvalue' => '0'
				),
				'footer_widget_column' => array(
					'label'        => T_( 'Widget Column' ),
					'note'         => T_( '' ),
					'type'         => 'radio',
					'defaultvalue' => '3',
					'options'      => array(
						array( '1', T_( '1 Column' ) ),
						array( '2', T_( '2 Column' ) ),
						array( '3', T_( '3 Column' ) ),
						array( '4', T_( '4 Column' ) ),
					),
				),
				'footer_bottom_align' => array(
					'label'        => T_( 'Footer Bottom Mode' ),
					'note'         => T_(''),
					'type'         => 'radio',
					'defaultvalue' => 'float',
					'options'      => array(
						array( 'float', T_( 'Float Mode' ) ),
						array( 'center', T_( 'Center Mode' ) ),
					),
				),
				'footer_social' => array(
					'label'		   => T_( 'Enable Social Icon' ),
					'note'		   => T_( 'Check to enable Social Icon on footer.' ),
					'type'		   => 'checkbox',
					'defaultvalue' => 1,
				),
			'section_footer_end' => array(
				'layout'  => 'end_fieldset',
			),

			/* Mediaidx
			* ========================================================================== */
			'section_media_start' => array(
				'layout' => 'begin_fieldset',
				'label'  => T_('Photo Index Options')
			),
				'max_mediaidx_height' => array(
					'label'        => T_('Max Mediaidx image height'),
					'note'         => 'px',
					'defaultvalue' => '',
					'type'         => 'integer',
					'allow_empty'  => true,
				),
				'mediaidx_column' => array(
					'label'			=> T_( 'Display Columns Image' ),
					'note'			=> T_( '' ),
					'type'			=> 'radio',
					'options'		=> array(
						array( 'one', T_( '1 Column' )),
						array( 'two', T_('2 Columns')),
						array( 'three', T_('3 Columns')),
						array( 'four' , T_('4 Columns')),
					),
					'defaultvalue'	=> 'three',
				),
				'mediaidx_space' => array(
					'label'		    => T_( 'Space Image' ),
					'note'			=> T_( 'px. Set the padding of image space. Default set is <strong>10</strong>. Also you can set <strong>0 for without space</strong>.' ),
					'type'			=> 'integer',
					'defaultvalue'	=> '10',
					'size'			=> '5'
				),
				'mediaidx_thumb_size' => array(
					'label'        => T_('Thumbnail size in Media index'),
					'note'         => '',
					'type'         => 'select',
					'defaultvalue' => 'fit-1280x720',
					'options'      => array(
						'original'		=> T_( 'Original' ),
						'fit-1280x720'  => T_( 'fit-1280x720' ),
						'fit-720x500'	=> T_( 'fit-720x250' ),
						'crop-480x320'  => T_( 'crop-480x320' ),
						'crop-256x256'	=> T_( 'crop-256x256' ),
						'crop-192x192'	=> T_( 'crop-192x192' ),
						'crop-128x128'  => T_( 'crop-128x128' ),
						'crop-top-320x320' => T_( 'crop-top-320x320' ),
						'crop-top-200x200' => T_( 'crop-top-200x200' ),
						'crop-top-160x160' => T_( 'crop-top-160x160' )
					),
				),
				'mediaidx_display' => array(
					'label'			=> T_( 'Display Image' ),
					'note'			=> T_( 'Set the display of images in Mediaidx' ),
					'type'			=> 'integer',
					'defaultvalue'	=> '20',
					'size'			=> '5'
				),
				'mediaidx_effect' => array(
					'label'			=> T_( 'Choose the Animation Image' ),
					'note'			=> T_( 'Select your favorite Animation load for first open page.' ),
					'type'			=> 'select',
					'options'		=> array(
						'1' => T_('Opacity'),
						'2' => T_('Move Up'),
						'3' => T_('Sclae Up'),
						'4' => T_('Fall Perspective'),
						'5' => T_('Fly'),
						'6' => T_('Flip'),
						'7' => T_('Helix'),
						'8' => T_('Pop Up'),
					),
					'defaultvalue' => '3',
				),
				'mediaidx_title' => array(
					'label'			=> T_( 'Display Title Image' ),
					'note'			=> T_( 'Check to show the title image.' ),
					'type'			=> 'checkbox',
					'defaultvalue'	=> 0,
				),
				'mediaidx_title_bg' => array(
					'label'			=> T_( 'Change Title Background Color' ),
					'note'			=> T_( 'Choose your favorite bakcground color for image title section.' ),
					'type'			=> 'color',
					'defaultvalue'	=> '#ffffff',
				),
				'mediaidx_title_color' => array(
					'label'			=> T_( 'Change Title Color' ),
					'note'			=> T_( 'Choose your favorite color scheme for the title text.' ),
					'type'			=> 'color',
					'defaultvalue'	=> '#555555'
				),
				'mediaidx_title_shadow' => array(
					'label'			=> T_( 'Show Box Shadow' ),
					'note'			=> T_( 'Checkbox to show box shadow in title box.' ),
					'type'			=> 'checkbox',
					'defaultvalue'	=> 1,
				),
				'mediaidx_by' => array(
					'label'			=> T_( 'Order by' ),
					'note'			=> T_( 'How to sort the images' ),
					'type'			=> 'select',
					'options' 		=> get_available_sort_options(),
					'defaultvalue' 	=> 'datestart',
				),
				'mediaidx_dir' => array(
					'label'			=> T_( 'Direction' ),
					'note'			=> T_( 'How to sort the images' ),
					'type'			=> 'select',
					'options'		=> array(
						'ASC' => T_( 'Ascending' ),
						'DESC' => T_( 'Descending' ),
					),
					'defaultvalue'	=> 'ASC',
				),
				'banner_public' => array(
					'label'        => T_('Display "Public" banner'),
					'note'         => T_('Display banner for "Public" albums (albums & comments)'),
					'defaultvalue' => 1,
					'type'         => 'checkbox',
				),
			'section_media_end' => array(
				'layout' => 'end_fieldset',
			),


			/* Search Disp Options
			 * ========================================================================== */
			'section_search_start' => array(
				'layout'	=> 'begin_fieldset',
				'label'		=> T_( 'Search Disp Options' )
			),
				'header_search_height' => array(
					'label'			=> T_( 'Header Height' ),
					'note'			=> T_( 'px. Change the searc disp height header. (min-height: 356px)' ),
					'type'			=> 'integer',
					'defaultvalue'	=> '520',
					'size'			=> 6,
				),
				'header_search_bg' => array(
					'label'         => T_( 'Header Background Image' ),
					'note'          => T_( '' ),
					'type'          => 'radio',
					'options'       => $arr_bodybg,
					'defaultvalue'  => reset( $arr_bodybg[8] ),
				),
				'header_search_bg_attach' => array(
					'label'        => T_( 'Background Attachment' ),
					'note'         => T_( '' ),
					'type'         => 'radio',
					'defaultvalue' => 'initial',
					'options'      => array(
						array( 'initial', T_( 'Initial' ) ),
						array( 'fixed', T_( 'Fixed' ) ),
					),
				),
				'header_search_heading' => array(
					'label'			=> T_( 'Heading Text' ),
					'note'			=> T_( 'Change the Heading Text' ),
					'type'			=> 'text',
					'size'			=> '60',
					'defaultvalue'	=> 'Search anyting you want.',
				),
				'header_search_subhead' => array(
					'label'			=> T_( 'Subheading Text' ),
					'note'			=> T_( 'Change the Subheading text' ),
					'type'			=> 'text',
					'size'			=> '60',
					'defaultvalue'	=> 'Just type any word in the search box.',
				),
				'header_btn_search' => array(
					'label'			=> T_( 'Text Button Search' ),
					'note'			=> T_( 'Change Button text search.' ),
					'type'			=> 'text',
					'size'			=> '20',
					'defaultvalue'	=> 'Search'
				),
				'search_align_pagination' => array(
					'label'			=> T_( 'Paginatioin Align' ),
					'note'			=> T_( 'Change aling pagination' ),
					'type'			=> 'radio',
					'options'		=> array(
						array( 'left', T_('Left') ),
						array( 'center', T_( 'Center' ) ),
						array( 'right', T_( 'Right' ) ),
					),
					'defaultvalue'	=> 'center',
				),
			'section_search_end' => array(
				'layout'	=> 'end_fieldset',
			),


			/* Colorbox Image Zoom
			* ========================================================================== */
			'section_colorbox_start' => array(
				'layout' => 'begin_fieldset',
				'label'  => T_('Colorbox Image Zoom')
			),
				'colorbox' => array(
					'label'        => T_('Colorbox Image Zoom'),
					'note'         => T_('Check to enable javascript zooming on images (using the colorbox script)'),
					'defaultvalue' => 1,
					'type'         => 'checkbox',
				),
				'colorbox_vote_post' => array(
					'label'        => T_('Voting on Post Images'),
					'note'         => T_('Check this to enable AJAX voting buttons in the colorbox zoom view'),
					'defaultvalue' => 1,
					'type'         => 'checkbox',
				),
				'colorbox_vote_post_numbers' => array(
					'label'        => T_('Display Votes'),
					'note'         => T_('Check to display number of likes and dislikes'),
					'defaultvalue' => 1,
					'type'         => 'checkbox',
				),
				'colorbox_vote_comment' => array(
					'label'        => T_('Voting on Comment Images'),
					'note'         => T_('Check this to enable AJAX voting buttons in the colorbox zoom view'),
					'defaultvalue' => 1,
					'type'         => 'checkbox',
				),
				'colorbox_vote_comment_numbers' => array(
					'label'        => T_('Display Votes'),
					'note'         => T_('Check to display number of likes and dislikes'),
					'defaultvalue' => 1,
					'type'         => 'checkbox',
				),
				'colorbox_vote_user' => array(
					'label'        => T_('Voting on User Images'),
					'note'         => T_('Check this to enable AJAX voting buttons in the colorbox zoom view'),
					'defaultvalue' => 1,
					'type'         => 'checkbox',
				),
				'colorbox_vote_user_numbers' => array(
					'label'        => T_('Display Votes'),
					'note'         => T_('Check to display number of likes and dislikes'),
					'defaultvalue' => 1,
					'type'         => 'checkbox',
				),
			'section_colorbox_end' => array(
				'layout' => 'end_fieldset',
			),

			/* Username Start
			* ========================================================================== */
			'section_username_start' => array(
				'layout' => 'begin_fieldset',
				'label'  => T_('Username options')
			),
				'bubbletip' => array(
					'label'        => T_('Username bubble tips'),
					'note'         => T_('Check to enable bubble tips on usernames'),
					'defaultvalue' => 0,
					'type'         => 'checkbox',
				),
				'autocomplete_usernames' => array(
					'label'        => T_('Autocomplete usernames'),
					'note'         => T_('Check to enable auto-completion of usernames entered after a "@" sign in the comment forms'),
					'defaultvalue' => 1,
					'type'         => 'checkbox',
				),
			'section_username_end' => array(
				'layout' => 'end_fieldset',
			),

			/* Acces Options
			* ========================================================================== */
			'section_access_start' => array(
				'layout' => 'begin_fieldset',
				'label'  => T_('When access is denied or requires login...')
			),
				'access_login_containers' => array(
					'label'   => T_('Display on login screen'),
					'note'    => '',
					'type'    => 'checklist',
					'options' => array(
						array( 'header',   sprintf( T_('"%s" container'), NT_('Header') ),    1 ),
						array( 'sidebar',  sprintf( T_('"%s" container'), NT_('Sidebar') ),   0 ),
						array( 'footer',   sprintf( T_('"%s" container'), NT_('Footer') ),    1 )
					),
				),
			'section_access_end' => array(
				'layout' => 'end_fieldset',
			),

		), parent::get_param_definitions( $params ) );

		return $r;
	}


	/**
	 * Get ready for displaying the skin.
	 *
	 * This may register some CSS or JS...
	 */
	function display_init()
	{
		global $Messages, $debug, $disp;

		// Request some common features that the parent function (Skin::display_init()) knows how to provide:
		parent::display_init( array(
			'jquery',                  // Load jQuery
			'font_awesome',            // Load Font Awesome (and use its icons as a priority over the Bootstrap glyphicons)
			'bootstrap',               // Load Bootstrap (without 'bootstrap_theme_css')
			'bootstrap_evo_css',       // Load the b2evo_base styles for Bootstrap (instead of the old b2evo_base styles)
			'bootstrap_messages',      // Initialize $Messages Class to use Bootstrap styles
			'style_css',               // Load the style.css file of the current skin
			'colorbox',                // Load Colorbox (a lightweight Lightbox alternative + customizations for b2evo)
			'bootstrap_init_tooltips', // Inline JS to init Bootstrap tooltips (E.g. on comment form for allowed file extensions)
			'disp_auto',               // Automatically include additional CSS and/or JS required by certain disps (replace with 'disp_off' to disable this)
		) );

		require_js( $this->get_url().'assets/scripts/modernizr.custom.js' );

		// Skin specific initializations:
		// Include Script
		if ( $this->get_setting( 'nav_sticky' ) == 1 ) {
		 require_js( $this->get_url().'assets/scripts/jquery.sticky.js' );
		}

		if ( $disp == 'search' ) {
			require_css( $this->get_url().'assets/css/slidebars.min.css' );
			require_js( $this->get_url().'assets/scripts/slidebars.min.js' );
		}

		require_js( $this->get_url().'assets/scripts/jquery.waypoints.min.js' );
		require_js( $this->get_url().'assets/scripts/masonry.pkgd.min.js' );
		require_js( $this->get_url().'assets/scripts/imagesloaded.pkgd.min.js' );
		require_js( $this->get_url().'assets/scripts/classie.js' );
		require_js( $this->get_url().'assets/scripts/AnimOnScroll.js' );
		require_js( $this->get_url().'assets/scripts/scripts.js' );

		// Add custom CSS:
		$custom_css = '';

		/* General Options
		* ========================================================================== */
		if( $color_scheme = $this->get_setting( 'color_scheme' ) ) {
			$custom_css .= '
			a,
			/* Header */
			.main_navigation ul a
			{ color: '.$color_scheme.' }
			';
		}

		if ( $bg = $this->get_setting( 'body_background' ) ) {
			$custom_css .= 'body, html,
			#sb-site, .sb-site-container
			{ background-color: '.$bg.' }';
		}

		// Limit images by max height:
		$max_image_height = intval( $this->get_setting( 'max_image_height' ) );
		if( $max_image_height > 0 )
		{
			$custom_css .= '.evo_image_block img { max-height: '.$max_image_height.'px; width: auto; }'."\n";
		}

// fp> TODO: the following code WORKS but produces UGLY CSS with tons of repetitions. It needs a full rewrite.

		// ===== Custom page styles: =====
		$custom_styles = array();

		// Text size <=== THIS IS A WORK IN PROGRESS
		// if( $text_size = $this->get_setting( 'page_text_size' ) )
		// {
		// 	$custom_styles[] = 'font-size: '.$text_size;
		// }
		// if( ! empty( $custom_styles ) )
		// {
		// 	$custom_css .= '	body { '.implode( ';', $custom_styles )." }\n";
		// }

		$custom_styles = array();
		// Text color
		// if( $text_color = $this->get_setting( 'page_text_color' ) )
		// {
		// 	$custom_styles[] = 'color: '.$text_color;
		// }
		// if( ! empty( $custom_styles ) )
		// {
		// 	$custom_css .= '	body { '.implode( ';', $custom_styles )." }\n";
		// }
		//
		// // Link color
		// if( $text_color = $this->get_setting( 'page_link_color' ) )
		// {
		// 	$custom_styles[] = 'color: '.$text_color;
		// }
		if( ! empty( $custom_styles ) )
		{
			// $custom_css .= '	body .container a { '.implode( ';', $custom_styles )." }\n";
			// $custom_css .= '	ul li a { '.implode( ';', $custom_styles )." }\n";
			// $custom_css .= "	ul li a {background-color: transparent;}\n";
			// $custom_css .= "	.ufld_icon_links a {color: #fff !important;}\n";
		}

		// Current tab text color
		// if( $text_color = $this->get_setting( 'current_tab_text_color' ) )
		// {
		// 	$custom_styles[] = 'color: '.$text_color;
		// }
		// if( ! empty( $custom_styles ) )
		// {
		// 	$custom_css .= '	ul.nav.nav-tabs li a.selected { '.implode( ';', $custom_styles )." }\n";
		// }

		// Page background color
		// if( $bg_color = $this->get_setting( 'page_bg_color' ) )
		// {
		// 	$custom_styles[] = 'background-color: '.$bg_color;
		// }
		// if( ! empty( $custom_styles ) )
		// {
		// 	$custom_css .= '	body { '.implode( ';', $custom_styles )." }\n";
		// }


		/* Header Options
		* ========================================================================== */
		if ( $height = $this->get_setting( 'header_height' ) ) {
			$custom_css .= '.main_header{ height: '.$height.'px; }';
		}

		$bg_header = '';
		if ( $this->get_setting( 'header_bg_type' ) == 'images' ) {
			$bg_header = $this->get_setting( 'header_bg' );
		} else {
			$bg_header = $this->get_setting( 'header_custom_bg' );
		}
		switch ( $bg_header ) {
			case $bg_header:
			$custom_css .= ".main_header{ background-image: url('$bg_header') }";
			break;
		}

		$bg_header_x = $this->get_setting( 'header_bg_position_x');
		$bg_header_y = $this->get_setting( 'header_bg_position_y');
		if ( !empty($bg_header_x) || !empty($bg_header_y)  ) {
			$custom_css .= '.main_header{ background-position: '.$bg_header_x.'% '.$bg_header_y.'%; }';
		}

		$header_bg_attach = $this->get_setting( 'header_bg_attach' );
		switch ( $header_bg_attach ) {
			case $header_bg_attach:
			$custom_css .= '.main_header{ background-attachment: '.$header_bg_attach.'; }';
			break;
		}

		// Header Background Size
		$header_bg_size = $this->get_setting( 'header_bg_size' );
		switch ( $header_bg_size ) {
			case $header_bg_size:
			$custom_css .= '.main_header{ background-size: '.$header_bg_size.'; }';
			break;
		}

		// Color Overlay
		if ( $this->get_setting( 'header_overlay' ) == 0 ) {
			$custom_css .= '.main_header::after{ display: none }';
		}
		if ( $color = $this->get_setting( 'color_overlay' ) ) {
			$custom_css .= '.main_header::after{ background-color: '.$color.' }';
		}
		$overlay_opacity = $this->get_setting( 'opcity_cv' );
		switch ( $overlay_opacity ) {
			case $overlay_opacity:
			$custom_css .= '.main_header::after{ opacity: '.$overlay_opacity.' }';
			break;
		}

		// Content Header
		if( $top = $this->get_setting( 'header_content_top' ) ) {
			$custom_css .= '.main_header .brand { top: '.$top.'% }';
		}

		$align = $this->get_setting( 'header_content_align' );
		switch ( $align ) {
			case $align:
			$custom_css .= '.main_header .brand{ text-align: '.$align.'; }';
			break;
		}

		if ( $color_title = $this->get_setting( 'header_color_heading' ) ) {
			$custom_css .= '.main_header .brand .evo_widget.widget_core_coll_title a{ color: '.$color_title.' }';
		}

		if( $color_tagline = $this->get_setting( 'header_color_tagline' ) ) {
			$custom_css .= '.main_header .brand .evo_widget.widget_core_coll_tagline{ color: '.$color_tagline.' }';
		}

		if( $this->get_setting( 'header_text_shadow_content' ) == 0 ) {
			$custom_css .= '.main_header .brand .evo_widget.widget_core_coll_title a, .main_header .brand .evo_widget.widget_core_coll_tagline { text-shadow: none; }';
		}


		/* Main Navigation
		* ========================================================================== */
		if ( $bg = $this->get_setting( 'nav_bg' ) ) {
			$custom_css .= '.main_navigation{ background-color: '.$bg.' }';
		}

		$nav_align = $this->get_setting( 'nav_align' );
		switch ( $nav_align ) {
			case 'left':
			$custom_css .= '.main_navigation .nav-tabs{ text-align: left; }';
			break;

			case 'right':
			$custom_css .= '.main_navigation .nav-tabs{ text-align: right; }';
			$custom_css .= '.main_navigation .nav-tabs li{ float: right; } .main_navigation ul ul{ float: right; }';
			break;
		}

		if ( $this->get_setting( 'nav_sticky_shadow' ) == 0 ) {
			$custom_css .= '.sticky-wrapper.is-sticky .main_navigation { box-shadow: none; }';
		}

		if ( $color = $this->get_setting( 'nav_color' ) ) {
			$custom_css .= '.main_navigation .nav-tabs a{ color: '.$color.' }';
		}

		if( $color = $this->get_setting( 'nav_color_hov' ) ) {
			$custom_css .= '
			.main_navigation .nav-tabs li:hover a, .main_navigation .nav-tabs li:active a, .main_navigation .nav-tabs li:focus a, .main_navigation .nav-tabs li.active a { color: '.$color.'; }';
			$custom_css .= '.main_navigation .nav-tabs a:before, .main_navigation .nav-tabs a:after{ background-color: '.$color.' }';
		}

		/* Gallery Options
		* ========================================================================== */
		if ( $bg = $this->get_setting( 'gallery_bg' ) ) {
			$custom_css .= '.posts_gallery .main_content_gallery .cat_title::after { background-color: '.$bg.' }';
		}

		if ( $space = $this->get_setting('gallery_gutter') ) {
			$custom_css .= '.posts_gallery .evo_post, .posts_gallery .feature_post{ padding: '.$space.'px; }';
			// $custom_css .= '.posts_gallery{ margin-left: -'.$space.'px; margin-right: -'.$space.'px; }';
		}

		if ( $this->get_setting( 'gallery_shadow' )  == 0 ) {
			$custom_css .= '.posts_gallery .main_content_gallery:hover, .posts_gallery .main_content_gallery:active, .posts_gallery .main_content_gallery:focus
			{ box-shadow: none; }';
		}

		if ( $fz = $this->get_setting( 'cat_title_size' ) ) {
			$custom_css .= '@media screen and ( min-width: 1024px ) { .posts_gallery .main_content_gallery .cat_title_link { font-size: '.$fz.'px; } }';
		}
		if ( $color = $this->get_setting( 'cat_title_color' ) ) {
			$custom_css .= '.posts_gallery .main_content_gallery .cat_title_link { color: '.$color.'; }';
		}
		if( $bg = $this->get_setting( 'cat_view_bg' ) ) {
			$custom_css .= '.posts_gallery .main_content_gallery .btn_cat:after { background-color: '.$bg.' }';
		}

		/* Photo Index
		 * ========================================================================== */
		if ( $space = $this->get_setting( 'mediaidx_space' ) ) {
			$custom_css .= '.disp_mediaidx .main_content .widget_core_coll_media_index .image_content { padding: '.$space.'px }';
			$custom_css .= '.disp_mediaidx .main_content .widget_core_coll_media_index .evo_image_index { margin-left: -'.$space.'px;  margin-right: -'.$space.'px }';
		}

		if ( $bg = $this->get_setting( 'mediaidx_title_bg' ) ) {
			$custom_css .= '.disp_mediaidx .main_content .widget_core_coll_media_index .image_content .note{ background-color: '.$bg.' }';
		}
		if( $color = $this->get_setting( 'mediaidx_title_color' ) ) {
			$custom_css .= '.disp_mediaidx .main_content .widget_core_coll_media_index .image_content .note{ color: '.$color.' }';
		}
		if( $this->get_setting( 'mediaidx_title_shadow' ) == false ) {
			$custom_css .= '.disp_mediaidx .main_content .widget_core_coll_media_index .image_content .note{ box-shadow: none; }';
		}

		/* Posts Custom Options
		 * ========================================================================== */
		if ( $space = $this->get_setting( 'posts_list_space' ) ) {
			$custom_css .= '.posts_list .evo_posts { padding: '.$space.'px; }';
			$custom_css .= '.posts_list { margin-left: -'.$space.'px; margin-right: -'.$space.'px; }';
		}

		/* Footer Custom Options
		* ========================================================================== */
		if ( $bg = $this->get_setting( 'footer_bg' ) ) {
			$custom_css .= '#footer{ background-color: '.$bg.' }';
		}

		if ( $this->get_setting( 'footer_bottom_align' ) == 'center' ) {
			$custom_css .= '#footer .copyright, .footer__social{ float: none; text-align: center; }';
		}

		/* Search Disp Options
		 * ========================================================================== */
		if( $bg = $this->get_setting( 'header_search_bg' ) ) {
			$custom_css .= '.search_head{ background-image: url( "'.$bg.'" ); }';
		}
		if( $height = $this->get_setting( 'header_search_height' ) ) {
			$custom_css .= '.search_head{ height: '.$height.'px }';
		}
		if( $bg_attach = $this->get_setting( 'header_search_bg_attach' ) ) {
			$custom_css .= '.search_head{ background-attachment: '.$bg_attach.' }';
		}


		/* Output the custom CSS
		* ========================================================================== */
		if( !empty( $custom_css ) ) {
			$custom_css = '
			<style type="text/css">
			'.$custom_css.'
			</style>';
			add_headline( $custom_css );
		}

	}


	/**
	 * Determine to display status banner or to don't display
	 *
	 * @param string Status of Item or Comment
	 * @return boolean TRUE if we can display status banner for given status
	 */
	function enabled_status_banner( $status )
	{
		if( $status != 'published' )
		{ // Display status banner everytime when status is not 'published'
			return true;
		}
		if( is_logged_in() && $this->get_setting( 'banner_public' ) )
		{ // Also display status banner if status is 'published'
			//   AND current user is logged in
			//   AND this feature is enabled in skin settings
			return true;
		}
		// Don't display status banner
		return false;
	}

	function get_template( $name )
	{
		switch( $name )
		{
			case 'Results':
				// Results list:
				return array(
					'page_url'              => '', // All generated links will refer to the current page
					'before'                => '<div class="results panel panel-default">',
					'content_start'         => '<div id="$prefix$ajax_content">',
					'header_start'          => '',
					'header_text'           => '<div class="center"><ul class="pagination">'
					            .'$prev$$first$$list_prev$$list$$list_next$$last$$next$'
					            .'</ul></div>',
					'header_text_single'    => '',
					'header_end'            => '',
					'head_title'            => '<div class="panel-heading fieldset_title"><span class="pull-right">$global_icons$</span><h3 class="panel-title">$title$</h3></div>'."\n",
					'global_icons_class'    => 'btn btn-default btn-sm',
					'filters_start'         => '<div class="filters panel-body">',
					'filters_end'           => '</div>',
					'filter_button_class'   => 'btn-sm btn-info',
					'filter_button_before'  => '<div class="form-group pull-right">',
					'filter_button_after'   => '</div>',
					'messages_start'        => '<div class="messages form-inline">',
					'messages_end'          => '</div>',
					'messages_separator'    => '<br />',
					'list_start'            => '<div class="table_scroll">'."\n"
					                        .'<table class="table table-striped table-bordered table-hover table-condensed" cellspacing="0">'."\n",
					'head_start'            => "<thead>\n",
					'line_start_head'       => '<tr>',  // TODO: fusionner avec colhead_start_first; mettre a jour admin_UI_general; utiliser colspan="$headspan$"
					'colhead_start'         => '<th $class_attrib$>',
					'colhead_start_first'   => '<th class="firstcol $class$">',
					'colhead_start_last'    => '<th class="lastcol $class$">',
					'colhead_end'           => "</th>\n",
					'sort_asc_off'          => get_icon( 'sort_asc_off' ),
					'sort_asc_on'           => get_icon( 'sort_asc_on' ),
					'sort_desc_off'         => get_icon( 'sort_desc_off' ),
					'sort_desc_on'          => get_icon( 'sort_desc_on' ),
					'basic_sort_off'        => '',
					'basic_sort_asc'        => get_icon( 'ascending' ),
					'basic_sort_desc'       => get_icon( 'descending' ),
					'head_end'              => "</thead>\n\n",
					'tfoot_start'           => "<tfoot>\n",
					'tfoot_end'             => "</tfoot>\n\n",
					'body_start'            => "<tbody>\n",
					'line_start'            => '<tr class="even">'."\n",
					'line_start_odd'        => '<tr class="odd">'."\n",
					'line_start_last'       => '<tr class="even lastline">'."\n",
					'line_start_odd_last'   => '<tr class="odd lastline">'."\n",
					'col_start'             => '<td $class_attrib$>',
					'col_start_first'       => '<td class="firstcol $class$">',
					'col_start_last'        => '<td class="lastcol $class$">',
					'col_end'               => "</td>\n",
					'line_end'              => "</tr>\n\n",
					'grp_line_start'        => '<tr class="group">'."\n",
					'grp_line_start_odd'    => '<tr class="odd">'."\n",
					'grp_line_start_last'   => '<tr class="lastline">'."\n",
					'grp_line_start_odd_last' => '<tr class="odd lastline">'."\n",
					'grp_col_start'         => '<td $class_attrib$ $colspan_attrib$>',
					'grp_col_start_first'   => '<td class="firstcol $class$" $colspan_attrib$>',
					'grp_col_start_last'    => '<td class="lastcol $class$" $colspan_attrib$>',
					'grp_col_end'           => "</td>\n",
					'grp_line_end'          => "</tr>\n\n",
					'body_end'              => "</tbody>\n\n",
					'total_line_start'      => '<tr class="total">'."\n",
					'total_col_start'       => '<td $class_attrib$>',
					'total_col_start_first' => '<td class="firstcol $class$">',
					'total_col_start_last'  => '<td class="lastcol $class$">',
					'total_col_end'         => "</td>\n",
					'total_line_end'        => "</tr>\n\n",
					'list_end'              => "</table></div>\n\n",
					'footer_start'          => '',
					'footer_text'           => '<div class="center"><ul class="pagination">'
					.'$prev$$first$$list_prev$$list$$list_next$$last$$next$'
					.'</ul></div><div class="center">$page_size$</div>'
	               /* T_('Page $scroll_list$ out of $total_pages$   $prev$ | $next$<br />'. */
	               /* '<strong>$total_pages$ Pages</strong> : $prev$ $list$ $next$' */
	               /* .' <br />$first$  $list_prev$  $list$  $list_next$  $last$ :: $prev$ | $next$') */,
					'footer_text_single'    => '<div class="center">$page_size$</div>',
					'footer_text_no_limit'  => '', // Text if theres no LIMIT and therefor only one page anyway
					'page_current_template' => '<span>$page_num$</span>',
					'page_item_before'      => '<li>',
					'page_item_after'       => '</li>',
					'page_item_current_before' => '<li class="active">',
					'page_item_current_after'  => '</li>',
					'prev_text'         => T_('Previous'),
					'next_text'         => T_('Next'),
					'no_prev_text'      => '',
					'no_next_text'      => '',
					'list_prev_text'    => T_('...'),
					'list_next_text'    => T_('...'),
					'list_span'         => 11,
					'scroll_list_range' => 5,
					'footer_end'        => "\n\n",
					'no_results_start'  => '<div class="panel-footer">'."\n",
					'no_results_end'    => '$no_results$</div>'."\n\n",
					'content_end'       => '</div>',
					'after'             => '</div>',
					'sort_type'         => 'basic'
				);
				break;

			case 'blockspan_form':
				// Form settings for filter area:
				return array(
					'layout'         => 'blockspan',
					'formclass'      => 'form-inline',
					'formstart'      => '',
					'formend'        => '',
					'title_fmt'      => '$title$'."\n",
					'no_title_fmt'   => '',
					'fieldset_begin' => '<fieldset $fieldset_attribs$>'."\n"
												.'<legend $title_attribs$>$fieldset_title$</legend>'."\n",
					'fieldset_end'   => '</fieldset>'."\n",
					'fieldstart'     => '<div class="form-group form-group-sm" $ID$>'."\n",
					'fieldend'       => "</div>\n\n",
					'labelclass'     => 'control-label',
					'labelstart'     => '',
					'labelend'       => "\n",
					'labelempty'     => '<label></label>',
					'inputstart'     => '',
					'inputend'       => "\n",
					'infostart'      => '<div class="form-control-static">',
					'infoend'        => "</div>\n",
					'buttonsstart'   => '<div class="form-group form-group-sm">',
					'buttonsend'     => "</div>\n\n",
					'customstart'    => '<div class="custom_content">',
					'customend'      => "</div>\n",
					'note_format'    => ' <span class="help-inline">%s</span>',
					// Additional params depending on field type:
					// - checkbox
					'fieldstart_checkbox'    => '<div class="form-group form-group-sm checkbox" $ID$>'."\n",
					'fieldend_checkbox'      => "</div>\n\n",
					'inputclass_checkbox'    => '',
					'inputstart_checkbox'    => '',
					'inputend_checkbox'      => "\n",
					'checkbox_newline_start' => '',
					'checkbox_newline_end'   => "\n",
					// - radio
					'inputclass_radio'       => '',
					'radio_label_format'     => '$radio_option_label$',
					'radio_newline_start'    => '',
					'radio_newline_end'      => "\n",
					'radio_oneline_start'    => '',
					'radio_oneline_end'      => "\n",
				);

			case 'compact_form':
			case 'Form':
				// $disp=single contact form:
				return array(
					'layout'         => 'fieldset',
					'formclass'      => 'form-horizontal',
					'formstart'      => '',
					'formend'        => '',
					'title_fmt'      => '<span style="float:right">$global_icons$</span><h2>$title$</h2>'."\n",
					'no_title_fmt'   => '<span style="float:right">$global_icons$</span>'."\n",
					'fieldset_begin' => '<div class="clear"></div><div class="fieldset_wrapper $class$" id="fieldset_wrapper_$id$"><fieldset $fieldset_attribs$><div class="panel panel-default">'."\n"
										.'<legend class="panel-heading" $title_attribs$>$fieldset_title$</legend><div class="panel-body $class$">'."\n",
					'fieldset_end'   => '</div></div></fieldset></div>'."\n",
					'fieldstart'     => '<div class="form-group" $ID$>'."\n",
					'fieldend'       => "</div>\n\n",
					'labelclass'     => 'control-label col-sm-3',
					'labelstart'     => '',
					'labelend'       => "\n",
					'labelempty'     => '<label class="control-label col-sm-3"></label>',
					'inputstart'     => '<div class="controls col-sm-9">',
					'inputend'       => "</div>\n",
					'infostart'      => '<div class="controls col-sm-9"><div class="form-control-static">',
					'infoend'        => "</div></div>\n",
					'buttonsstart'   => '<div class="form-group"><div class="control-buttons col-sm-offset-3 col-sm-9">',
					'buttonsend'     => "</div></div>\n\n",
					'customstart'    => '<div class="custom_content">',
					'customend'      => "</div>\n",
					'note_format'    => ' <span class="help-inline">%s</span>',
					// Additional params depending on field type:
					// - checkbox
					'inputclass_checkbox'    => '',
					'inputstart_checkbox'    => '<div class="controls col-sm-9"><div class="checkbox"><label>',
					'inputend_checkbox'      => "</label></div></div>\n",
					'checkbox_newline_start' => '<div class="checkbox">',
					'checkbox_newline_end'   => "</div>\n",
					// - radio
					'fieldstart_radio'       => '<div class="form-group radio-group" $ID$>'."\n",
					'fieldend_radio'         => "</div>\n\n",
					'inputclass_radio'       => '',
					'radio_label_format'     => '$radio_option_label$',
					'radio_newline_start'    => '<div class="radio"><label>',
					'radio_newline_end'      => "</label></div>\n",
					'radio_oneline_start'    => '<label class="radio-inline">',
					'radio_oneline_end'      => "</label>\n",
				);

			case 'linespan_form':
				// Linespan form:
				return array(
					'layout'         => 'linespan',
					'formclass'      => 'form-horizontal',
					'formstart'      => '',
					'formend'        => '',
					'title_fmt'      => '<span style="float:right">$global_icons$</span><h2>$title$</h2>'."\n",
					'no_title_fmt'   => '<span style="float:right">$global_icons$</span>'."\n",
					'fieldset_begin' => '<div class="fieldset_wrapper $class$" id="fieldset_wrapper_$id$"><fieldset $fieldset_attribs$><div class="panel panel-default">'."\n"
															.'<legend class="panel-heading" $title_attribs$>$fieldset_title$</legend><div class="panel-body $class$">'."\n",
					'fieldset_end'   => '</div></div></fieldset></div>'."\n",
					'fieldstart'     => '<div class="form-group" $ID$>'."\n",
					'fieldend'       => "</div>\n\n",
					'labelclass'     => '',
					'labelstart'     => '',
					'labelend'       => "\n",
					'labelempty'     => '',
					'inputstart'     => '<div class="controls">',
					'inputend'       => "</div>\n",
					'infostart'      => '<div class="controls"><div class="form-control-static">',
					'infoend'        => "</div></div>\n",
					'buttonsstart'   => '<div class="form-group"><div class="control-buttons">',
					'buttonsend'     => "</div></div>\n\n",
					'customstart'    => '<div class="custom_content">',
					'customend'      => "</div>\n",
					'note_format'    => ' <span class="help-inline">%s</span>',
					// Additional params depending on field type:
					// - checkbox
					'inputclass_checkbox'    => '',
					'inputstart_checkbox'    => '<div class="controls"><div class="checkbox"><label>',
					'inputend_checkbox'      => "</label></div></div>\n",
					'checkbox_newline_start' => '<div class="checkbox">',
					'checkbox_newline_end'   => "</div>\n",
					'checkbox_basic_start'   => '<div class="checkbox"><label>',
					'checkbox_basic_end'     => "</label></div>\n",
					// - radio
					'fieldstart_radio'       => '',
					'fieldend_radio'         => '',
					'inputstart_radio'       => '<div class="controls">',
					'inputend_radio'         => "</div>\n",
					'inputclass_radio'       => '',
					'radio_label_format'     => '$radio_option_label$',
					'radio_newline_start'    => '<div class="radio"><label>',
					'radio_newline_end'      => "</label></div>\n",
					'radio_oneline_start'    => '<label class="radio-inline">',
					'radio_oneline_end'      => "</label>\n",
				);

			case 'fixed_form':
				// Form with fixed label width:
				return array(
					'layout'         => 'fieldset',
					'formclass'      => 'form-horizontal',
					'formstart'      => '',
					'formend'        => '',
					'title_fmt'      => '<span style="float:right">$global_icons$</span><h2>$title$</h2>'."\n",
					'no_title_fmt'   => '<span style="float:right">$global_icons$</span>'."\n",
					'fieldset_begin' => '<div class="fieldset_wrapper $class$" id="fieldset_wrapper_$id$"><fieldset $fieldset_attribs$><div class="panel panel-default">'."\n"
											.'<legend class="panel-heading" $title_attribs$>$fieldset_title$</legend><div class="panel-body $class$">'."\n",
					'fieldset_end'   => '</div></div></fieldset></div>'."\n",
					'fieldstart'     => '<div class="form-group fixedform-group" $ID$>'."\n",
					'fieldend'       => "</div>\n\n",
					'labelclass'     => 'control-label fixedform-label',
					'labelstart'     => '',
					'labelend'       => "\n",
					'labelempty'     => '<label class="control-label fixedform-label"></label>',
					'inputstart'     => '<div class="controls fixedform-controls">',
					'inputend'       => "</div>\n",
					'infostart'      => '<div class="controls fixedform-controls"><div class="form-control-static">',
					'infoend'        => "</div></div>\n",
					'buttonsstart'   => '<div class="form-group"><div class="control-buttons fixedform-controls">',
					'buttonsend'     => "</div></div>\n\n",
					'customstart'    => '<div class="custom_content">',
					'customend'      => "</div>\n",
					'note_format'    => ' <span class="help-inline">%s</span>',
					// Additional params depending on field type:
					// - checkbox
					'inputclass_checkbox'    => '',
					'inputstart_checkbox'    => '<div class="controls fixedform-controls"><div class="checkbox"><label>',
					'inputend_checkbox'      => "</label></div></div>\n",
					'checkbox_newline_start' => '<div class="checkbox">',
					'checkbox_newline_end'   => "</div>\n",
					// - radio
					'fieldstart_radio'       => '<div class="form-group radio-group" $ID$>'."\n",
					'fieldend_radio'         => "</div>\n\n",
					'inputclass_radio'       => '',
					'radio_label_format'     => '$radio_option_label$',
					'radio_newline_start'    => '<div class="radio"><label>',
					'radio_newline_end'      => "</label></div>\n",
					'radio_oneline_start'    => '<label class="radio-inline">',
					'radio_oneline_end'      => "</label>\n",
				);

			case 'user_navigation':
				// The Prev/Next links of users
				return array(
					'block_start'  => '<ul class="pager">',
					'prev_start'   => '<li class="previous">',
					'prev_end'     => '</li>',
					'prev_no_user' => '',
					'back_start'   => '<li>',
					'back_end'     => '</li>',
					'next_start'   => '<li class="next">',
					'next_end'     => '</li>',
					'next_no_user' => '',
					'block_end'    => '</ul>',
				);

			case 'button_classes':
				// Button classes
				return array(
					'button'       => 'btn btn-default btn-xs',
					'button_red'   => 'btn-danger',
					'button_green' => 'btn-success',
					'text'         => 'btn btn-default btn-xs',
					'group'        => 'btn-group',
				);

			case 'tooltip_plugin':
				// Plugin name for tooltips: 'bubbletip' or 'popover'
				return 'popover';
				break;

			case 'plugin_template':
				// Template for plugins
				return array(
					'toolbar_before'       => '<div class="btn-toolbar $toolbar_class$" role="toolbar">',
					'toolbar_after'        => '</div>',
					'toolbar_title_before' => '<div class="btn-toolbar-title">',
					'toolbar_title_after'  => '</div>',
					'toolbar_group_before' => '<div class="btn-group btn-group-xs" role="group">',
					'toolbar_group_after'  => '</div>',
					'toolbar_button_class' => 'btn btn-default',
				);

			case 'modal_window_js_func':
				// JavaScript function to initialize Modal windows, @see echo_user_ajaxwindow_js()
				return 'echo_modalwindow_js_bootstrap';
			break;

			default:
				// Delegate to parent class:
				return parent::get_template( $name );
		}
	}


	/**
	 * Check if we can display a widget container
	 *
	 * @param string Widget container key: 'header', 'page_top', 'menu', 'sidebar', 'sidebar2', 'footer'
	 * @param string Skin setting name
	 * @return boolean TRUE to display
	 */
	function is_visible_container( $container_key, $setting_name = 'access_login_containers' )
	{
		$access = $this->get_setting( $setting_name );

		return ( ! empty( $access ) && ! empty( $access[ $container_key ] ) );
	}

   /* Title Comment
    * ========================================================================== */
   function Change_class( $id ) {

	$id = $this->get_setting( $id );
	if ( $id == $id ) {
		return $id;
	}

   }

}

?>

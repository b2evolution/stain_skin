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
	function get_param_definitions( $params )
	{
		// Load to use function get_available_thumb_sizes()
		load_funcs( 'files/model/_image.funcs.php' );
      // System provide bg images
      $bodybg_cat = 'assets/images/header/'; // Background images folder relative to this skin folder
      $arr_bodybg = $this -> get_arr_pics_from_folder( $this->get_path().$bodybg_cat, $this->get_url().$bodybg_cat, 80, 80 );

	   $r = array_merge( array(

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
            'header_bg' => array(
               'label'        => T_( 'Background Image' ),
               'note'         => T_( '' ),
               'type'         => 'radio',
               'options'      => $arr_bodybg,
               'defaultvalue' => reset( $arr_bodybg[3] ),
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
                  '0.1'  => T_( '0.1' ),
                  '0.2'  => T_( '0.2' ),
                  '0.3'  => T_( '0.3' ),
                  '0.4'  => T_( '0.4' ),
                  '0.5'  => T_( '0.5' ),
                  '0.6'  => T_( '0.6' ),
                  '0.7'  => T_( '0.7' ),
                  '0.8'  => T_( '0.8' ),
                  '0.9'  => T_( '0.9' ),
                  '10'  => T_( '1' ),
               ),
               'defaultvalue' => '0.2',
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
               'defaultvalue' => '#1B1B1B'
            ),
            'nav_sticky' => array(
               'label'        => T_( 'Sticky Mode' ),
               'note'         => T_( 'Check to enable <strong>Sticky Nav</strong>.' ),
               'type'         => 'checkbox',
               'defaultvalue' => 1,
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


			/* Content Options
			 * ========================================================================== */
			'section_homepage_start' => array(
				'layout'	=> 'begin_fieldset',
				'label' 	=> T_( 'Homepage Options' ),
			),
			'section_homepage_end' => array(
				'layout'	=> 'end_fieldset',
			),


         /* Image Viewing
          * ========================================================================== */
			'section_image_start' => array(
				'layout' => 'begin_fieldset',
				'label'  => T_('Image Viewing')
			),
				'max_image_height' => array(
					'label'        => T_('Max comment image height'),
					'note'         => 'px',
					'defaultvalue' => '',
					'type'         => 'integer',
					'allow_empty'  => true,
				),
				'posts_thumb_size' => array(
					'label'        => T_('Thumbnail size for Albums'),
					'note'         => '',
					'defaultvalue' => 'crop-192x192',
					'options'      => get_available_thumb_sizes(),
					'type'         => 'select',
				),
				'single_thumb_size' => array(
					'label'        => T_('Thumbnail size inside Album'),
					'note'         => '',
					'defaultvalue' => 'fit-640x480',
					'options'      => get_available_thumb_sizes(),
					'type'         => 'select',
				),
				'mediaidx_thumb_size' => array(
					'label'        => T_('Thumbnail size in Media index'),
					'note'         => '',
					'defaultvalue' => 'fit-256x256',
					'options'      => get_available_thumb_sizes(),
					'type'         => 'select',
				),
				'banner_public' => array(
					'label'        => T_('Display "Public" banner'),
					'note'         => T_('Display banner for "Public" albums (albums & comments)'),
					'defaultvalue' => 1,
					'type'         => 'checkbox',
				),
			'section_image_end' => array(
				'layout' => 'end_fieldset',
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
               'defaultvalue' => 'center',
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
				'gender_colored' => array(
					'label'        => T_('Display gender'),
					'note'         => T_('Use colored usernames to differentiate men & women.'),
					'defaultvalue' => 0,
					'type'         => 'checkbox',
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
						array( 'page_top', sprintf( T_('"%s" container'), NT_('Page Top') ),  1 ),
						array( 'menu',     sprintf( T_('"%s" container'), NT_('Menu') ),      0 ),
						array( 'footer',   sprintf( T_('"%s" container'), NT_('Footer') ),    1 ) ),
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
		global $Messages, $debug;

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

		// Skin specific initializations:
      // Include Script
      if ( $this->get_setting( 'nav_sticky' ) == 1 ) {
         require_js( $this->get_url().'assets/scripts/jquery.sticky.js' );
      }
      require_js( $this->get_url().'assets/scripts/masonry.pkgd.min.js' );
      require_js( $this->get_url().'assets/scripts/scripts.js' );

		// Add custom CSS:
		$custom_css = '';

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
		if( $text_color = $this->get_setting( 'page_text_color' ) )
		{
			$custom_styles[] = 'color: '.$text_color;
		}
		if( ! empty( $custom_styles ) )
		{
			$custom_css .= '	body { '.implode( ';', $custom_styles )." }\n";
		}

		// Link color
		if( $text_color = $this->get_setting( 'page_link_color' ) )
		{
			$custom_styles[] = 'color: '.$text_color;
		}
		if( ! empty( $custom_styles ) )
		{
			$custom_css .= '	body .container a { '.implode( ';', $custom_styles )." }\n";
			$custom_css .= '	ul li a { '.implode( ';', $custom_styles )." }\n";
			$custom_css .= "	ul li a {background-color: transparent;}\n";
			$custom_css .= "	.ufld_icon_links a {color: #fff !important;}\n";
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

		global $thumbnail_sizes;
		$posts_thumb_size = $this->get_setting( 'posts_thumb_size' );
		if( isset( $thumbnail_sizes[ $posts_thumb_size ] ) )
		{
			// Make the width of image block as fixed to don't expand it by long post title text
			$custom_css .= '	.posts_list .evo_post { max-width:'.$thumbnail_sizes[ $posts_thumb_size ][1]."px }\n";
			// Set width & height for block with text "No pictures yet"
			$custom_css .= '	.posts_list .evo_post b { width:'.( $thumbnail_sizes[ $posts_thumb_size ][1] - 20 ).'px;'
				.'height:'.( $thumbnail_sizes[ $posts_thumb_size ][2] - 20 ).'px'." }\n";
		}
		$single_thumb_size = $this->get_setting( 'single_thumb_size' );
		if( isset( $thumbnail_sizes[ $single_thumb_size ] ) )
		{
			// Make the width of image block as fixed to don't expand it by long post title text
			$custom_css .= '.post_images .single-image .evo_image_legend { width: 100%; }';
			// Set width & height for block with text "No pictures yet"
			/*$custom_css .= '	.posts_list .evo_post b { width:'.( $thumbnail_sizes[ $single_thumb_size ][1] - 20 ).'px;'
				.'height:'.( $thumbnail_sizes[ $single_thumb_size ][2] - 20 ).'px'." }\n";*/
		}

      /* Header Options
       * ========================================================================== */
      if ( $height = $this->get_setting( 'header_height' ) ) {
         $custom_css .= '.main_header{ height: '.$height.'px; }';
      }

      $bg_header = $this->get_setting( 'header_bg' );
      switch ( $bg_header ) {
         case $bg_header:
            $custom_css .= ".main_header{ background-image: url('$bg_header') }";
            break;

         default:
            # code...
            break;
      }

      if ( $bg_x = $this->get_setting( 'header_bg_position_x' ) ) {
         $custom_css .= '.main_header{ background-position-x: '.$bg_x.'%; }';
      }
      if ( $bg_y = $this->get_setting( 'header_bg_position_y' ) ) {
         $custom_css .= '.main_header{ background-position-y: '.$bg_y.'%; }';
      }

      $header_bg_attach = $this->get_setting( 'header_bg_attach' );
      switch ( $header_bg_attach ) {
         case $header_bg_attach:
            $custom_css .= '.main_header{ background-attachment: '.$header_bg_attach.'; }';
            break;

         default:
            # code...
            break;
      }

      $header_bg_size = $this->get_setting( 'header_bg_size' );
      switch ( $header_bg_size ) {
         case $header_bg_size:
            $custom_css .= '.main_header{ background-size: '.$header_bg_size.'; }';
            break;

         default:
            # code...
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

         default:
            # code...
         break;
      }

      // Brand Content Align
      $align = $this->get_setting( 'header_content_align' );
      switch ( $align ) {
         case $align:
            $custom_css .= '.main_header .brand{ text-align: '.$align.'; }';
         break;

         default:
            # code...
         break;
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
            $custom_css .= '.main_navigation .nav-tabs li{ float: right; }';
            break;

         default:
            # code...
            break;
      }

      if ( $color = $this->get_setting( 'nav_color' ) ) {
         $custom_css .= '.main_navigation .nav-tabs a{ color: '.$color.' }';
      }

      if( $color = $this->get_setting( 'nav_color_hov' ) ) {
         $custom_css .= '
         .main_navigation .nav-tabs li:hover a, .main_navigation .nav-tabs li:active a, .main_navigation .nav-tabs li:focus a, .main_navigation .nav-tabs li.active a
         { color: '.$color.'; }';
         $custom_css .= '.main_navigation .nav-tabs a:before, .main_navigation .nav-tabs a:after{ background-color: '.$color.' }';
      }

		/* Footer Custom Options
       * ========================================================================== */
		if ( $bg = $this->get_setting( 'footer_bg' ) ) {
			$custom_css .= '#footer{ background-color: '.$bg.' }';
		}

		if ( $this->get_setting( 'footer_bottom_align' ) == 'center' ) {
			$custom_css .= '#footer .copyright, .footer__social{ float: none; text-align: center; }';
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
					'labelclass'     => 'control-label',
					'labelstart'     => '',
					'labelend'       => "\n",
					'labelempty'     => '<label class="control-label"></label>',
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

}

?>

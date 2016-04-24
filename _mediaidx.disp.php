<?php
/**
 * This is the template that displays the media index for a blog
 *
 * This file is not meant to be called directly.
 * It is meant to be called by an include in the main.page.php template.
 * To display the archive directory, you should call a stub AND pass the right parameters
 * For example: /blogs/index.php?disp=arcdir
 *
 * b2evolution - {@link http://b2evolution.net/}
 * Released under GNU GPL License - {@link http://b2evolution.net/about/gnu-gpl-license}
 * @copyright (c)2003-2016 by Francois Planque - {@link http://fplanque.com/}
 *
 * @package evoskins
 */
if( !defined('EVO_MAIN_INIT') ) die( 'Please, do not access this page directly.' );

global $thumbnail_sizes, $Skin, $Blog;

if( empty( $params ) )
{ // Initialize array with params
	$params = array();
}
// Merge the params from current skin
$params = array_merge( array(
	'mediaidx_thumb_size' => 'fit-1280x720',
), $params );

$photocell_styles = '';
if( isset( $thumbnail_sizes[ $params['mediaidx_thumb_size'] ] ) )
{
	$photocell_styles = ' style="width:'.$thumbnail_sizes[ $params['mediaidx_thumb_size'] ][1].'px;'
		.'height:'.$thumbnail_sizes[ $params['mediaidx_thumb_size'] ][2].'px"';
}

$column = '';
$column_set = $Skin->get_setting( 'mediaidx_column' );
switch ( $column_set ) {
    case $column_set:
        $column = $column_set.'_column';
    break;
}

$effect = '';
$effect_skin = $Skin->get_setting( 'mediaidx_effect' );
switch ($effect_skin) {
	case $effect_skin:
		$effect = $effect_skin;
	break;
}

// --------------------------------- START OF MEDIA INDEX --------------------------------
skin_widget( array(
	// CODE for the widget:
	'widget'              => 'coll_media_index',
	// Optional display params
	'block_start'         => '<div class="evo_widget $wi_class$">',
	'block_end'           => '</div>',
	'block_display_title' => false,
	'disp_image_title' 	  => $Skin->get_setting( 'mediaidx_title' ),
	'thumb_size'          => $params['mediaidx_thumb_size'],
	'thumb_layout'        => 'list',
	'list_start'          => '<ul id="grid" class="evo_image_index effect-'.$effect.'">',
	'list_end'            => '</ul>',
    'item_start'          => '<li class="image_content '.$column.'">',
    'item_end'            => '</li>',
	'order_by'            => $Skin->get_setting( 'mediaidx_by' ),
	'order_dir'           => $Skin->get_setting( 'mediaidx_dir' ),
	'limit'               => $Skin->get_setting( 'mediaidx_display' ),
) );
// ---------------------------------- END OF MEDIA INDEX ---------------------------------
?>

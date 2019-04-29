<?php

/*
 * Plugin Name: GL Upload Dir
 * Plugin URI:	https://greenlifeit.com/plugins/
 * Description: Change Default Upload directory for pdf and zip file.
 * Author: Asiqur Rahman
 * Author URI: https://asique.net/
 * Version: 1.1
 * Requires at least: 3.3
 * License: GPL2
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/*
 * Change upload directory for PDF and ZIP files 
 * Only works in WordPress 3.3+
 */
add_filter( 'wp_handle_upload_prefilter', 'gl_ud_pre_upload' );
add_filter( 'wp_handle_upload', 'gl_ud_post_upload' );

function gl_ud_pre_upload( $file ) {
	add_filter( 'upload_dir', 'gl_ud_custom_upload_dir' );
	
	return $file;
}

function gl_ud_post_upload( $fileinfo ) {
	remove_filter( 'upload_dir', 'gl_ud_custom_upload_dir' );
	
	return $fileinfo;
}

function gl_ud_custom_upload_dir( $path ) {
	$extension = substr( strrchr( $_POST['name'], '.' ), 1 );
	
	if ( empty( $path['error'] ) && ! empty( $extension ) ) {
		$customdir = '';
		switch ( $extension ) {
			case 'pdf' :
				$customdir = '/pdf';
				break;
			
			case 'zip':
				$customdir = '/zip';
				break;
		}
		
		if ( ! empty( $customdir ) ) {
			$path['path']   = str_replace( $path['subdir'], '', $path['path'] ); //remove default subdir (year/month)
			$path['url']    = str_replace( $path['subdir'], '', $path['url'] );
			$path['subdir'] = $customdir;
			$path['path']   .= $customdir;
			$path['url']    .= $customdir;
		}
	}
	
	return $path;
}

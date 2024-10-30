<?php if( ! defined('ABSPATH') ) { header('Status: 403 Forbidden'); header('HTTP/1.1 403 Forbidden'); exit(); }
/*
Plugin Name: KVP: YouTube Lite
Plugin URI: http://keisermedia.com/projects/kvp-youtube-extension-lite
Description: Adds YouTube functionality into Katalyst Video Plus.
Author: Keiser Media Group
Author URI: http://keisermedia.com/
Version: 1.1.1
Text Domain: kvp-youtube
Domain Path: /languages
License: GPL3

	Copyright 2013  keisermedia.com  (email: support@keisermedia.com)

	This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/

final class KVP_YouTube_Setup {

	private $api_key	= null;
	private $per_page	= 50;
	
	private $videos		= array();
	private $tags		= array();
	
	private $errors	= array();
	
	public function __construct() {
		
		register_activation_hook(__FILE__, array($this, 'register_activation_hook') );
		
		add_filter('kvp_providers', array($this, 'add_provider') );
		add_filter('kvp_youtube_request_data', array($this, 'set_request_data') );
		add_filter('kvp_youtube_video_embed', array($this, 'video_embed'), 10, 2 );
		
		add_action('kvp_load_youtube_import_files', array($this, 'load_dependencies') );
		
	}
	
	public function register_activation_hook() {
		
		
		
		if( !file_exists(WP_PLUGIN_DIR . '/katalyst-video-plus/katalyst-video-plus.php') ) {
			
			include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
 
            $info = plugins_api('plugin_information', array('slug' => 'katalyst-video-plus' ));
 
            if ( is_wp_error( $info ) ) {
            	wp_die('<div class="error"><p>' . $info->get_error_message() . '</p></div>');
                
            }
			
			$message = sprintf(
				'<div class="error"><p>Katalyst Video Plus %s <a href="%s">%s</a>.</p></div>',
				__('is not installed.', 'kvp'),
				wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=katalyst-video-plus'), 'install-plugin_katalyst-video-plus'),
				__('Click here to install', 'kvp')
			);

		} elseif( is_plugin_inactive('katalyst-video-plus/katalyst-video-plus.php') ) {
			
			$message = sprintf(
				'<div class="error"><p>Katalyst Video Plus %s <a href="%s">%s</a>.</p></div>',
				__('is installed but not active.', 'kvp'),
				wp_nonce_url(self_admin_url('plugins.php?action=activate&plugin=katalyst-video-plus'), 'activate-plugin_katalyst-video-plus'),
				__('Click here to activate', 'kvp')
			);
			
		}
		
		if( isset($message) )
			wp_die($message);
		
	}
	
	public function add_provider( $providers ) {
		
		$providers['youtube'] = array(
			'title'	=> __('YouTube', 'kvp'),
			'class'	=> 'KVP_YouTube',
			'features' => array(
				'api_key'
			),
		);
		
		return $providers;
		
	}
	
	public function set_request_data( $data ) {
		
		$resource = array(
			'base_url'	=> 'https://www.googleapis.com/youtube/v3/',
			'format'	=> '%s%s?%s',	
			'endpoints'	=> array(
				'channels' => array(
					'part',
					'categoryId',
					'forUsername',
					'id',
					'managedByMe',
					'mine',
					'maxResults',
					'onBehalfOfContentOwner',
					'pageToken',
				),
				'playlist' => array(
					'part',
					'channelId',
					'id',
					'mine',
					'maxResults',
					'onBehalfOfContentOwner',
					'onBehalfOfContentOwnerChannel',
					'pageToken',
				),
				'playlistItems' => array(
					'part',
					'id',
					'playlistId',
					'maxResults',
					'pageToken',
					'videoId',
				),
				'videos' => array(
					'part',
					'chart',
					'id',
					'myRating',
					'maxResults',
					'onBehalfOfContentOwner',
					'pageToken',
					'regionCode',
					'videoCategoryId',
				),
			),
		);
		
		$data = array_merge($data, $resource);
		
		return $data;
		
	}
	
	public function load_dependencies() {
		include_once(dirname(__FILE__) . '/class.provider.php');
	}
	
	public function video_embed( $content = null, $atts ) {
	
		extract( shortcode_atts( array(
			'username'	=> null,
			'ID'      	=> null,
			'width'   	=> 560,
			'height'  	=> 315,
		), $atts ) );
		
		return '<iframe id="ytplayer-' . $username . '" type="text/html" width="' . $width . '" height="' . $height . '" src="http://www.youtube.com/embed/' . $ID . '?origin=' . get_site_url() . '" frameborder="0"></iframe>';
	}
	
}

$kvp_youtube = new KVP_YouTube_Setup();
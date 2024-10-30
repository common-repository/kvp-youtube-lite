<?php if( ! defined('ABSPATH') ) { header('Status: 403 Forbidden'); header('HTTP/1.1 403 Forbidden'); exit(); }

final class KVP_YouTube extends KVP_Importer {
	
	private $api_key = null;
	private $uploads = null;
	private $status	 = array();
	
	
	protected function get_video_ids() {
		
		if( !isset($this->source['api_key']) ) {
			$this->status( __('An API key must be set to prevent request errors.', 'kvp-youtube-lite'), true);
			return array();
		}
		
		$this->api_key	= $this->source['api_key'];
		
		if( empty($this->uploads) ) {
			$channel_arr	= array(
				'part'			=> 'contentDetails',
				'forUsername'	=> $this->source['username'],
				'key'			=> $this->api_key,
			);
			
			$channel_data = $this->request('channels', $channel_arr);
			
			if( is_wp_error($channel_data) )
				return $channel_data;
			
			$this->uploads = $channel_data->items[0]->contentDetails->relatedPlaylists->uploads;
		
		}
		
		$this->status['limit']	= 50;
		$this->status['next']	= null;
		$videos = array();
		
		do {
		
			$playlistItems_arr	= array(
				'part'			=> 'contentDetails',
				'playlistId'	=> $this->uploads,
				'maxResults'	=> $this->status['limit'],
				'pageToken'		=> $this->status['next'],
				'key'			=> $this->api_key,
			);
		
			$playlistItems		= $this->request('playlistItems', $playlistItems_arr);
			
			if( is_wp_error($playlistItems) )
				return $playlistItems;
			
			foreach( $playlistItems->items as $item )
				$videos[] = $item->contentDetails->videoId;
			
			if( isset($playlistItems->nextPageToken) )
				$this->status['next'] = $playlistItems->nextPageToken;
			
			else
				$this->status['next'] = null;
		
		} while( !empty($this->status['next']) );
		
		return $videos;
		
	}
	
	protected function get_video_info( $id ) {
		
		if( empty($this->api_key) )
			$this->api_key	= $this->source['api_key'];
		
		$video_arr	= array(
			'part'			=> 'snippet',
			'id'			=> $id,
			'key'			=> $this->api_key,
		
		);
		
		$request_info = $this->request('videos', $video_arr);
		
		if( is_wp_error($request_info) )
			return $request_info;
		
		$video_info	= array(
			'post_title'		=> $request_info->items[0]->snippet->title,
			'post_content'		=> $request_info->items[0]->snippet->description,
			'post_date'			=> $request_info->items[0]->snippet->publishedAt,
		);
				
		$video_info['image'] = ( isset($request_info->items[0]->snippet->thumbnails->maxres->url) ) ? $request_info->items[0]->snippet->thumbnails->maxres->url : $request_info->items[0]->snippet->thumbnails->default->url;
		
		if( isset($request_info->items[0]->snippet->tags) )
			$video_info['tags_input'] = $request_info->items[0]->snippet->tags;
		
		return $video_info;
		
	}
	
}
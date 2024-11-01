<?php
/*
Plugin Name: Unit.licio.us
Plugin URI: http://unitinteractive.com/labs/unitlicious.php
Description: Searches one or many Del.icio.us accounts and inserts bookmarks as blog posts. Uses <a href="http://www.phpdelicious.com/">PhpDelicious by Edward Eliot</a>
Version: 0.1.0
Author: Unit Interactive
Author URI: http://unitinteractive.com/unit-info.php

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

require('includes/phpdelicious/php-delicious.inc.php');

	// set up recurrance on install
	function installUnitlicious()
	{
		wp_schedule_event( time(), 'hourly', 'updateDels' );
	}
	
	
	// remove recurrance
	function uninstallUnitlicious()
	{
		wp_clear_scheduled_hook( 'updateDels' );
	}
	
	
	// formatting '@username' to '<a href="http://twitter.com/username">@username</a>'
	function ulRefLinks( $ref )
	{
		return '<a href="http://twitter.com/' . str_replace('@', '', $ref[0]) . '">' . $ref[0] . '</a>';
	}
	
	
	// formatting 'http://www.site.com' to '<a href="http://www.site.com">http://www.site.com</a>'
	function ulURLLinks( $url )
	{
		return '<a href="' . $url[0] . '">' . $url[0] . '</a>';
	}
	
	
	// controls reformatting of dels
	function ulFormatDel( $text )
	{
		// replace URLs with links
		preg_match_all( '/http[^\s]+/', $text, $urls );
		
		$urls = preg_replace( '/\//', '\/', $urls[0] );
		
		for( $i = 0; $i < count( $urls ); $i++ )
		{
			$urls[ $i ] = '/' . $urls[ $i ] . '/';
		}
	
		$cleanDel = preg_replace_callback( $urls, 'ulURLLinks', $text );
		
		// replace @username references with links
		preg_match_all('/^@[^\s]+|\s@[^\s]+\b/', $cleanDel, $users);
		
		$users = preg_replace( "/\s/", "", $users[0] );
		
		for( $i = 0; $i < count( $users ); $i++ )
		{
			$users[ $i ] = '/' . $users[ $i ] . '/';
		}
		
		$cleanDel = preg_replace_callback( $users, 'ulRefLinks', $cleanDel );
		
		return $cleanDel;
	}


	function unitlicious() 
	{
		global $wpdb;
		
		$options = get_option( 'unitliciousSettings' );
		$category = $options['category'];
		
		$accounts = get_option( 'unitliciousAccounts' );
		
		// make sure we have some accounts to work with
		if ( count( $accounts ) )
		{
			foreach ( $accounts as $account )
			{
				// retrieve dels
				$d = new PhpDelicious( $account['user'], $account['pass'] );
				
				$data = $d->GetRecentPosts();
				
				/* $data = 
				 * 	'url'
				 * 	'desc'
				 * 	'notes'
				 *  'hash'
				 *  'tags'
				 *  'updated'
				 */
				
				// loop through all returned bookmarks
				foreach( $data as $del ) 
				{
					// make sure we haven't already stored the bookmark
					$query = "SELECT meta_id FROM $wpdb->postmeta WHERE meta_key = 'delID' AND meta_value = '".$del['hash']."'";
					
					if ( !count( $wpdb->get_results( $query ) ) )
					{
						$content = ulFormatDel( $del['notes'] );	
						// create post object
					  	$del_post = array(
							'post_title' 	=> $del['desc'],
							'post_date'		=> date( 'Y-m-d H:i:s', ( strtotime( $del['updated'] . ' UTC' ) + 7200 ) ),
							'post_content' 	=> $content,
							'post_status' 	=> 'publish',
							'post_author'	=> $account['author'],
							'post_category'	=> array( $category )
						);
						
						// insert the post into the database
						$post_id = wp_insert_post( $del_post );
				
						add_post_meta( $post_id, 'user', $account['user'] );
						add_post_meta( $post_id, 'delID', $del['hash'] );
						add_post_meta( $post_id, 'link', $del['url'] );
					}
				}
			}
		}
	}
	
	add_action( 'updateDels', 'unitlicious' );
	register_activation_hook( __FILE__, 'installUnitlicious' );
	register_deactivation_hook( __FILE__, 'uninstallUnitlicious' );
	
	// init plugin options to white list our options
	function unitliciousOptions()
	{
		register_setting( 'unitliciousOptions', 'unitliciousSettings' );
		register_setting( 'unitliciousOptions', 'unitliciousAccounts', 'unitliciousOptionsValidate' );
	}
	
	// add menu page
	function unitliciousMenu()
	{
		add_options_page('Unit.licio.us Options', 'Unit.licio.us', 'manage_options', 'unitlicious', 'unitliciousOptionsRender');
	}
	
	// draw the form
	function unitliciousOptionsRender()
	{
		include( 'includes/options.php' );
	}
	
	// validate the accounts options
	function unitliciousOptionsValidate( $input )
	{
		foreach ( $input as $key => $value )
		{
			if ( $input[ $key ]['user'] == '' || $input[ $key ]['pass'] == '')
			{
				unset( $input[ $key ] );
			}
		}

		return $input;		
	}
	
	add_action('admin_init', 'unitliciousOptions' );
	add_action('admin_menu', 'unitliciousMenu');
?>
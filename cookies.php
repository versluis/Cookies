<?php

/**
 * Plugin Name: Cookies
 * Plugin URI: https://wpguru.tv
 * Description: Displays a list of all cookies used on your site
 * Version: 0.1
 * Author: Jay Versluis
 * Author URI: https://wpguru.tv
 * License: GPL2
 * License URI:  http://www.gnu.org/licenses/gpl-2.0.html
 */
 
/*  Copyright 2019  Jay Versluis (email support@wpguru.tv)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// add submenu page under Appearance
// https://codex.wordpress.org/Function_Reference/add_theme_page
function guru_cookies_menu() {
	// add_theme_page('My Plugin Theme', 'My Plugin', 'edit_theme_options', 'my-unique-identifier', 'my_plugin_function');
	add_theme_page('My Plugin Theme', 'My Plugin', 'edit_theme_options', 'gcookies', 'guru_cookies');
}
add_action('admin_menu', 'guru_cookies_menu');

// *********************
// Main Plugin Function
// *********************
function guru_cookies () {
	// do stuff here
	echo "Hello from my plugin";
	}

// ********************************
// Additional and Helper Functions
// ********************************
function guru_get_cookies( $paras = '', $content = '' ) {
	
	$novalue = false;
	if ( !empty( $paras[0] ) ) {	
	  if ( strtolower( $paras[ 0 ] ) == 'novalue' ) {
		  $novalue = true;
	  } 
	}
	
	if ( $content == '' ) {
		$seperator = ' : ';
	} else {
		$seperator = $content;
	}
	$cookie = $_COOKIE;
	ksort( $cookie );
	$content = "<ul>";
	foreach ( $cookie as $key => $val ) {
		
		// don't do this if key is 'wordpress' or 'wp'
		if (!isThisInThat ('wordpress', $key) && !isThisInThat ('wp', $key)) {
		$content .= '<li><b>' . $key . '</b>';
		if ( !$novalue ) {
			$content .= $seperator . $val; 
		}
		$content .= "</li>"; 
		
		}
	} 
	$content .= "</ul>"; 
	return do_shortcode( $content ); 
} 
// adding the above function to WordPress
// https://codex.wordpress.org/Function_Reference/add_shortcode
add_shortcode( 'cookies', 'guru_get_cookies' );

// helper function to determine if a phrase is in another string
// https://stackoverflow.com/questions/4366730/how-do-i-check-if-a-string-contains-a-specific-word
function isThisInThat ($needle, $haystack) {
	return strpos($haystack, $needle) !==false;
}
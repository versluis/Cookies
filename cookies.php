<?php

/**
 * Plugin Name: Show Me The Cookies
 * Plugin URI: https://wpguru.co.uk/2019/03/show-me-the-cookies-how-to-list-all-cookies-on-your-wordpress-site/
 * Description: Displays a list of all cookies used on your site
 * Version: 1.0
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
function smtc_cookies_menu() {
	// add_theme_page('My Plugin Theme', 'My Plugin', 'edit_theme_options', 'my-unique-identifier', 'my_plugin_function');
	add_theme_page('Show Me The Cookies', 'Cookies', 'edit_theme_options', 'gcookies', 'smtc_guru_cookies');
}
add_action('admin_menu', 'smtc_cookies_menu');

// *********************
// Main Plugin Function
// *********************
function smtc_guru_cookies () {
	
	// check that the user has the required capability 
    if (!current_user_can('manage_options'))
    {
      wp_die( __('You do not have sufficient privileges to access this page. Sorry!') );
    }	
	
	///////////////////////////////////////
	// MAIN AMDIN CONTENT SECTION
	///////////////////////////////////////
	
	// display heading with icon WP style
	?>
    <div class="wrap">
    <div id="icon-index" class="icon32"><br></div>
    <h2>Show Me The Cookies</h2>
    <p>Here's a list of all current cookies used on your site.</p>
    <hr>
	<?php
	// call cookies function here
	echo smtc_guru_admin_cookies();
	?>
    <hr>
    <p>To display this list to your visitors, use the shortcode <strong>[cookies]</strong> in your post or page.</p>
    <p>You can filter out all WordPress related cookies using the shortcode <strong>[cookies-nowp]</strong>.</p>
    <p>If you like, you can replace the default separator between the cookie and its value <br>by placing your own string inside the opening and closing tags.<br>For example <strong>[cookies] ==> [/cookies]</strong>.
    <p>Find out more about Cookies on <a href="https://en.wikipedia.org/wiki/HTTP_cookie" target="_blank">Wikipedia</a>.</p> 
    <hr>
    
    <?php
    // ***************
    // DISPLAY FOOTER 
	// ***************
	?>
	<p><a href="https://wpguru.co.uk" target="_blank"><img src="<?php  
	echo plugins_url('images/guru-header-2013.png', __FILE__); ?>" width="300"></a> </p>

<p><a href="https://wpguru.co.uk/2019/03/show-me-the-cookies-how-to-list-all-cookies-on-your-wordpress-site/" target="_blank">Plugin by Jay Versluis</a> | <a href="https://github.com/versluis/Cookies" target="_blank">Contribute on GitHub</a> | <a href="https://patreon.com/versluis" target="_blank">Support me on Patreon</a></p>

<p><span><!-- Social Buttons -->

<!-- YouTube -->
<script src="https://apis.google.com/js/platform.js"></script>
<div class="g-ytsubscribe" data-channel="wphosting"></div>

<!-- Place this tag after the last widget tag. -->
<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/platform.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

<!-- Twitter -->
<a href="https://twitter.com/versluis" class="twitter-follow-button" data-show-count="true">Follow @versluis</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>

<!-- Facebook -->
<iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2Fpages%2FThe-WP-Guru%2F162188713810370&amp;width&amp;layout=button_count&amp;action=like&amp;show_faces=true&amp;share=true&amp;height=21&amp;appId=186277158097599" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:21px;" allowTransparency="true"></iframe>

</span></p>
</div>
<?php
	
	}
	// end of Main Function

// ********************************
// Additional and Helper Functions
// ********************************
function smtc_guru_get_nowp_cookies( $paras = '', $content = '' ) {
	
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
		if (!smtc_isThisInThat ('wordpress', $key) && !smtc_isThisInThat ('wp', $key)) {
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
add_shortcode( 'cookies-nowp', 'smtc_guru_get_nowp_cookies' );

// same as above, but listing all cookies
function smtc_guru_get_cookies( $paras = '', $content = '' ) {
	
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
	$content = "<ol>";
	foreach ( $cookie as $key => $val ) {
		
		// list all cookies
		$content .= '<li><b> ' . $key . '</b>';
		if ( !$novalue ) {
			$content .= $seperator . $val; 
		}
		$content .= "</li>"; 
	} 
	$content .= "</ol>"; 
	return do_shortcode( $content ); 
} 
add_shortcode( 'cookies', 'smtc_guru_get_cookies' );

// helper function to determine if a phrase is in another string
// https://stackoverflow.com/questions/4366730/how-do-i-check-if-a-string-contains-a-specific-word
function smtc_isThisInThat ($needle, $haystack) {
	return strpos($haystack, $needle) !==false;
}

// list cookies in admin interface
function smtc_guru_admin_cookies () {
	
	if ( $content == '' ) {
		$seperator = ' : ';
	} else {
		$seperator = $content;
	}
	
	$cookie = $_COOKIE;
	ksort( $cookie );
	$content = "<ol>";
	
	foreach ( $cookie as $key => $val ) {
		$content .= '<li><b> ' . $key . '</b>';
		if ( !$novalue ) {
			$content .= $seperator . $val; 
		}
		$content .= "</li>"; 
	} 
	$content .= "</ol>";
	 
	return $content;
}
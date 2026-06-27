<?php
/**
 * Plugin Name: Show Me The Cookies
 * Plugin URI:  https://wpguru.co.uk/2019/03/show-me-the-cookies-how-to-list-all-cookies-on-your-wordpress-site/
 * Description: Display a list of all cookies used on your site, or view them under Appearance - Cookies.
 * Version:     1.2
 * Author:      Jay Versluis
 * Author URI:  https://wpguru.tv
 * License:     GPL2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: show-me-the-cookies
 */

// v1.2 - Security hardening: sensitive cookie filtering, value masking, opt-in value display

/*  Copyright 2019 Jay Versluis (email: support@wpguru.tv)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/

// Prevent direct file access.
defined( 'ABSPATH' ) || exit;

class Show_Me_The_Cookies {

	private static bool $frontend_styles_done = false;

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'register_menu' ] );
		add_shortcode( 'cookies',      [ $this, 'shortcode_all_cookies' ] );
		add_shortcode( 'cookies-nowp', [ $this, 'shortcode_nowp_cookies' ] );
	}

	// Register the admin menu item under Appearance.
	// https://developer.wordpress.org/reference/functions/add_theme_page/
	public function register_menu(): void {
		add_theme_page(
			__( 'Show Me The Cookies', 'show-me-the-cookies' ),
			__( 'Cookies', 'show-me-the-cookies' ),
			'manage_options',
			'gcookies',
			[ $this, 'render_admin_page' ]
		);
	}

	public function render_admin_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient privileges to access this page.', 'show-me-the-cookies' ) );
		}
		?>
		<style>
			.smtc-cookie-list { list-style: none; padding-left: 0; }
			.smtc-cookie-list li { margin-bottom: 1em; }
		</style>
		<div class="wrap">
			<h1><?php esc_html_e( 'Show Me The Cookies', 'show-me-the-cookies' ); ?></h1>
			<p><?php esc_html_e( "Here's a list of all current cookies used on your site.", 'show-me-the-cookies' ); ?></p>
			<hr>
			<?php echo wp_kses_post( $this->build_cookie_list() ); ?>
			<hr>
			<p>
				<?php esc_html_e( 'To display this list to your visitors, use the shortcode', 'show-me-the-cookies' ); ?>
				<strong>[cookies]</strong>.
			</p>
			<p>
				<?php esc_html_e( 'You can filter out all WordPress related cookies using', 'show-me-the-cookies' ); ?>
				<strong>[cookies-nowp]</strong>.
			</p>
			<p>
				<?php esc_html_e( 'Find out more about cookies on', 'show-me-the-cookies' ); ?>
				<a href="https://en.wikipedia.org/wiki/HTTP_cookie" target="_blank">Wikipedia</a>.
			</p>
			<hr>
			<p>
				<a href="https://wpguru.co.uk" target="_blank">
					<img src="<?php echo esc_url( plugins_url( 'images/guru-header-2013.png', __FILE__ ) ); ?>" width="300" alt="WP Guru">
				</a>
			</p>
			<p>
				<a href="https://wpguru.co.uk/2019/03/show-me-the-cookies-how-to-list-all-cookies-on-your-wordpress-site/" target="_blank">Plugin by Jay Versluis</a>
				| <a href="https://github.com/versluis/Cookies" target="_blank">Contribute on GitHub</a>
				| <a href="https://patreon.com/versluis" target="_blank">Support me on Patreon</a>
			</p>
		</div>
		<?php
	}

	// Shortcode: [cookies] — lists all non-sensitive cookies.
	// Pass show_values="true" to display masked cookie values.
	// https://developer.wordpress.org/reference/functions/add_shortcode/
	public function shortcode_all_cookies( $atts = [], string $content = '' ): string {
		$atts       = is_array( $atts ) ? $atts : [];
		$show_value = $this->should_show_value( $atts );

		$cookies = array_filter( $_COOKIE, fn( $key ) => ! $this->is_sensitive_cookie( $key ), ARRAY_FILTER_USE_KEY );
		ksort( $cookies );

		if ( empty( $cookies ) ) {
			return '<p>' . esc_html__( 'No non-WordPress cookies found.', 'show-me-the-cookies' ) . '</p>';
		}

		$count  = 1;
		$output = $this->maybe_frontend_styles();
		$output .= '<ol class="smtc-cookie-list">';
		foreach ( $cookies as $key => $val ) {
			$output .= '<li>';
			$output .= '<strong>Cookie #' . $count . '</strong>: ' . esc_html( $key );
			if ( $show_value ) {
				$output .= '<br>Value: ' . esc_html( $this->mask_cookie_value( $val ) );
			}
			$output .= '</li>';
			$count++;
		}
		$output .= '</ol>';

		return $output;
	}

	// Shortcode: [cookies-nowp] — lists non-sensitive cookies only.
	// Pass show_values="true" to display masked cookie values.
	public function shortcode_nowp_cookies( $atts = [], string $content = '' ): string {
		$atts       = is_array( $atts ) ? $atts : [];
		$show_value = $this->should_show_value( $atts );

		$filtered = array_filter( $_COOKIE, fn( $key ) => ! $this->is_sensitive_cookie( $key ), ARRAY_FILTER_USE_KEY );
		ksort( $filtered );

		if ( empty( $filtered ) ) {
			return '<p>' . esc_html__( 'No non-WordPress cookies found.', 'show-me-the-cookies' ) . '</p>';
		}

		$count  = 1;
		$output = $this->maybe_frontend_styles();
		$output .= '<ul class="smtc-cookie-list">';
		foreach ( $filtered as $key => $val ) {
			$output .= '<li>';
			$output .= '<strong>Cookie #' . $count . '</strong>: ' . esc_html( $key );
			if ( $show_value ) {
				$output .= '<br>Value: ' . esc_html( $this->mask_cookie_value( $val ) );
			}
			$output .= '</li>';
			$count++;
		}
		$output .= '</ul>';

		return $output;
	}

	// Outputs the frontend CSS once per page, even if both shortcodes are used.
	private function maybe_frontend_styles(): string {
		if ( self::$frontend_styles_done ) {
			return '';
		}
		self::$frontend_styles_done = true;
		return '<style>.smtc-cookie-list{list-style:none;padding-left:0}.smtc-cookie-list li{margin-bottom:1em}</style>';
	}

	// Builds the cookie list for the admin page. Shows all cookies with masked values.
	private function build_cookie_list(): string {
		$cookies = $_COOKIE;
		ksort( $cookies );

		$output  = '<div class="notice notice-warning"><p><strong>Note:</strong> Cookie values may contain sensitive session data. Treat them as passwords. Sensitive cookies (WordPress auth, session, and token cookies) are hidden from this list.</p></div>';
		$count   = 1;
		$output .= '<ol class="smtc-cookie-list">';
		foreach ( $cookies as $key => $val ) {
			$output .= '<li>';
			$output .= '<strong>Cookie #' . $count . '</strong>: ' . esc_html( $key ) . '<br>';
			$output .= 'Value: ' . esc_html( $this->mask_cookie_value( $val ) ) . '<br>';
			$output .= 'Size: ' . mb_strlen( $val ) . ' characters';
			$output .= '</li>';
			$count++;
		}
		$output .= '</ol>';

		return $output;
	}

	// Returns true only when show_values="true" is explicitly set in shortcode attributes.
	private function should_show_value( array $atts ): bool {
		return isset( $atts['show_values'] ) && strtolower( $atts['show_values'] ) === 'true';
	}

	// Returns a masked version of a cookie value to avoid exposing sensitive session data.
	private function mask_cookie_value( string $val ): string {
		if ( $val === '' ) {
			return '(empty)';
		}
		if ( mb_strlen( $val ) <= 4 ) {
			return $val;
		}
		return mb_substr( $val, 0, 3 ) . '•••' . mb_substr( $val, -3 );
	}

	// Returns true if the cookie name matches known sensitive patterns (auth, session, token, WP core).
	private function is_sensitive_cookie( string $name ): bool {
		return stripos( $name, 'wordpress' ) !== false
			|| stripos( $name, 'wp-' ) !== false
			|| str_starts_with( strtolower( $name ), 'wp_' )
			|| strcasecmp( $name, 'PHPSESSID' ) === 0
			|| stripos( $name, 'token' ) !== false
			|| stripos( $name, 'auth' ) !== false
			|| stripos( $name, 'sess' ) !== false
			|| stripos( $name, 'secret' ) !== false;
	}
}

new Show_Me_The_Cookies();

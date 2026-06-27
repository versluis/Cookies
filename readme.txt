=== Show Me The Cookies ===
Contributors: versluis
Donate link: https://patreon.com/versluis
Tags: cookie, cookies, gdpr, privacy
Requires at least: 5.9
Tested up to: 7.0
Stable tag: 1.2
Requires PHP: 8.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Shows a list of all current cookies on your site, both for admins and visitors (optional).

== Description ==

Show Me The Cookies displays all current cookies your site is using. Once activated, you can find this list under **Appearance - Cookies**.

You can also display this list to your visitors by placing the shortcode [cookies] into any post or page.

WordPress uses several cookies under the hood by default. If you'd like to filter these out, use the shortcode [cookies-nowp], which will show a message if no non-WordPress cookies are found.

This plugin is based on code by David Artiss, who kindly made it available on GitHub.

To find out more about what cookies are and what can be done with them, please read https://en.wikipedia.org/wiki/HTTP_cookie


== Installation ==

1. Either: Upload the entire folder `cookies` to the `/wp-content/plugins/` directory.
1. Or: Download the ZIP file, then head over to Plugins - Add New - Upload Plugin, then browse to your file.
1. Or: From Plugins - Add New, search for "show me the cookies" and hit Install.
1. Then: Activate the plugin through the Plugins menu in WordPress.
1. You can view your cookies under Appearance - Cookies.


== Frequently Asked Questions ==

None so far.


== Screenshots ==

1. List of cookies in the Admin Interface
1. Example of shortcode displayed in TwentyThirteen theme


== Changelog ==

= 1.2 =
* Security: added sensitive cookie denylist — hides WordPress auth, session, and token cookies from shortcode output
* Security: cookie values are now masked in all output (first 3 + ••• + last 3 characters)
* Security: shortcodes now hide values by default; opt in with show_values="true" to display masked values
* Security: added admin notice warning that cookie values may contain sensitive session data
* Removed: is_wp_cookie() replaced by the more robust is_sensitive_cookie() denylist method
* Changed: [cookies] and [cookies-nowp] shortcode attribute novalue replaced by show_values="true"
* Improved: admin cookie list shows all cookies (including WordPress ones) with masked values
* Added: cookie value size (character count) displayed beneath each value in the admin list
* Fixed: [cookies] shortcode now shows an informative message when no non-sensitive cookies are found

= 1.1 =
* Security: escaped all cookie name and value output to prevent XSS
* Security: removed do_shortcode() wrapping on cookie data to prevent shortcode injection
* Security: added direct file access guard
* Security: removed third-party social media scripts from admin page
* Fixed: undefined variables in admin cookie list function (PHP 8 compatibility)
* Fixed: capability check mismatch between menu registration and access guard
* Added: Text Domain declaration for translations
* Updated: requires PHP 8.0 and WordPress 5.9 or later
* Code: refactored into a class to avoid global namespace pollution
* Improved: shortcode and admin cookie list now share a consistent format (Cookie #N / Value)
* Improved: [cookies-nowp] shows a message when no non-WordPress cookies are found
* Removed: custom separator feature (enclosing shortcode form [cookies]...[/cookies])

= 1.0 =
* Initial Release
* Based on code by David Artiss (thanks, David!)

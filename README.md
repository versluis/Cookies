# Cookies

WordPress Plugin to that lists all cookies used on your site.

### Usage

Add the shortcode [cookies] in any post or page to display a list of all current site cookies for your visitors.

Once activated, there's a small options page under **Appearance - Cookies** with a preview, which is only visible to site admins.

You also have the option to exclude WordPress cookies, which filters anything containting the string "wordpress" or "wp" in the cookie title. Use the shortcode [cookies-nowp] to display the filtered list.

### Attributions
- This plugin is based on code by [David Artiss](https://artiss.blog/2012/05/wordpress-function-to-list-site-cookies/?fbclid=IwAR37lyleRF791Fh2hj0cBuhyS51sbm3xqcz_NsHYb_qfiFoRXAfcFqT9pqI). 
- He subsequently made it available in [this Gist](https://gist.github.com/dartiss/2097c32dda644499a40980c517054c0e?fbclid=IwAR0ZQTlyWhZkWDjtp0uTtNZDhe4dxj0ykamx_1HxljEh6r3q99ww9cTf03k).
- John Brown discovered it and asked me to filter out WordPress cookies for his project, as a function for his child theme.
- I loved the idea so much that I decided to wrap it up as this standalone plugin for everybody.

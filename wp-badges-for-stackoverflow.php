<?php
/*
Plugin Name: Stackoverflow Profile Badges
Plugin URI: http://www.netattingo.com/
Description: This plugin shows the badges of user got in stackoverflow community.
Author: NetAttingo Technologies
Version: 1.0.0
Author URI: http://www.netattingo.com/
*/
//define('WP_DEBUG',true);
define('WPSB_DIR', plugin_dir_path(__FILE__));
define('WPSB_URL', plugin_dir_url(__FILE__));
define('WPSB_PAGE_DIR', plugin_dir_path(__FILE__).'pages/');
define('WPSB_INCLUDE_URL', plugin_dir_url(__FILE__).'includes/');

// plugin activation hook called	
function wpsb_install() {
   	global $wpdb;
}
register_activation_hook(__FILE__, 'wpsb_install');

// plugin deactivation hook called	
function wpsb_uninstall() {	
	global $wpdb;
}
register_deactivation_hook(__FILE__, 'wpsb_uninstall');

//Include menu and assign page
function wpsb_plugin_menu() {
    $icon = WPSB_URL. 'includes/icon.png';
	add_menu_page("Stackoverflow Badges", "Stackoverflow Badges", "administrator", "wpsb-stackoverflow-badges-setting", "wpsb_plugin_pages", $icon ,30);
	add_submenu_page("wpsb-stackoverflow-badges-setting", "About Us", "About Us", "administrator", "wpsb-about-us", "wpsb_plugin_pages");
}
add_action("admin_menu", "wpsb_plugin_menu");

function wpsb_plugin_pages() {
	
   $wpsb_pageitem = WPSB_PAGE_DIR.$_GET["page"].'.php';
   include($wpsb_pageitem);
}

//Include front css 
function wpsb_css_init() {
    wp_enqueue_style("badges_front_css", plugins_url('includes/wpsb-front-style.css',__FILE__ )); 
	wp_enqueue_script('badges_front_css');
}
add_action( 'wp_enqueue_scripts', 'wpsb_css_init' );

//add admin css
function wpsb_admin_css() {
  wp_register_style('badges_admin_css', plugins_url('includes/wpsb-admin-style.css',__FILE__ ));
  wp_enqueue_style('badges_admin_css');
}
add_action( 'admin_init','wpsb_admin_css' );

add_action( 'admin_enqueue_scripts', 'wp_enqueue_color_picker' );
function wp_enqueue_color_picker( ) {
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker-script', plugins_url('includes/stack-color-picker.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}

// Generate shortcode
add_filter('widget_text', 'wpsb_shortcode_function');
add_shortcode( 'stackoverflow-badges', 'wpsb_shortcode_function' );
function wpsb_shortcode_function( $atts ) {
	
	$user_id = get_option('user_id');
	$stack_color_pic = get_option('stack_color_pic');
	$url = "http://api.stackexchange.com/2.2/users/$user_id?order=desc&sort=reputation&site=stackoverflow";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    curl_setopt($curl, CURLOPT_USERAGENT, 'cURL');
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_ENCODING , "gzip");

    $result = curl_exec($curl);
    curl_close($curl);
	
	$feed = json_decode($result,true);
	
	$user_name = $feed['items'][0]['display_name'];
	$bronze = $feed['items'][0]['badge_counts']['bronze'];
	$silver = $feed['items'][0]['badge_counts']['silver'];
	$gold = $feed['items'][0]['badge_counts']['gold'];
	$profile_image = $feed['items'][0]['profile_image'];
	$reputation = $feed['items'][0]['reputation'];
	$link = $feed['items'][0]['link'];
	
?>
<div class="stack-info-outer" style="background:<?php echo $stack_color_pic; ?>">

	<div class="stack-user-name"><a target="_blank" href="<?php echo $link; ?>"><?php echo $user_name; ?></a></div>
	
	<div class="stack-user-image"><a target="_blank" href="<?php echo $link; ?>"><img src="<?php echo $profile_image; ?>"></a></div>
	
	<div class="stack-reputation"><span class="reputation-count"><?php echo $reputation; ?></span> <span class="reputation-txt">Reputation</span></div>
	
	<div class="badges-details">
		<?php if($bronze != 0){ ?>
			<div class="bronze-badge broz-badge">
				<span class="bronze-icon"></span>
				<span class="bronze-span"><?php echo "Bronze " . $bronze; ?></span>
			</div>
		<?php } ?>
	
		<?php if($silver != 0) { ?>
		<div class="silver-badge broz-badge">
            <span class="silver-badge-icon"></span>
            <span class="silver-span"><?php echo "Silver " . $silver; ?></span>
        </div>
		<?php } ?>
		
        <?php if($gold != 0) { ?>
		<div class="gold-badge broz-badge">
            <span class="gold-badge-icon"></span>
            <span class="gold-span"><?php echo "Gold " . $gold; ?></span>
        </div>
		<?php } ?>
	</div>
	
</div>
<?php
}
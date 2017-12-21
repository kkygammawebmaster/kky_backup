<?php
/*
	Plugin Name: Simple Facebook Pixel
	Plugin URI: http://www.inspired-plugins.com/
	Description: Adds a facebook tracking pixel to any page / post.
	Version: 1.0.1
	Author: Inspired Information Services
	Author URI: www.inspired-is.com

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
 * Adds a box to the main column on the Post and Page edit screens.
 */
	add_action('wp_head', 'fbpixel_head' );
function fbpixel_head() {
	$post_id = get_option("fbp_page_id");
	$current_page_id = get_the_ID();
	if ($current_page_id == $post_id){
		$fbp_js = get_option("fbp_page_js");
		echo '<script type="text/javascript">' , 'jsfunction();' , '</script>',$fbp_js;	
	};
}
//This area holds the code for the admin page
add_action('admin_menu', 'fbpMenu');

function fbpMenu(){
	add_options_page( 'Facebook Pixel Options', 'Facebook Pixel', 'manage_options', 'fb_pixel', 'pluginOptions' );
}
function fbp_register_settings(){
	register_setting( 'fbp-settings-group', 'fbp_page_id' );
	register_setting( 'fbp-settings-group', 'fbp_page_js' );
}
add_action('admin_init', 'fbp_register_settings');
function pluginOptions(){     
	?>
    <h2>Simple Facebook Pixel Options</h2>
    <form action="options.php" method="post">
    <?php
    settings_fields( 'fbp-settings-group' ); 
    do_settings_sections( 'fbp-settings-group' );
	?>
    <table>
    <tr>
    <td>Select which page you would like the pixel on</td>
	<td><?php $args = array('selected'=> get_option('fbp_page_id'), 'name' => 'fbp_page_id'); wp_dropdown_pages($args); ?></td>
    </tr>
    <tr>
	<td><label for = "fbp_page_js"> Enter your Facebook pixel code here </label> </td>
    <td><textarea name="fbp_page_js" id="fbp_page_js" style="vertical-align:middle; width: 700px; height: 400px;"><?php echo(get_option('fbp_page_js'))?></textarea></td>
	</tr>
    </table>
	<?php
	submit_button();
	?></form><?php
}
?>

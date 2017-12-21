<?php
/*
Plugin Name: RPS Image Gallery
Plugin URI: http://redpixel.com/rps-image-gallery-plugin
Description: A responsive image gallery with slideshow and advanced linking capabilities. 
Version: 2.2.2 
Author: Red Pixel Studios
Author URI: http://redpixel.com/
License: GPL3
*/

/* 	Copyright (C) 2011-2016  Red Pixel Studios  (email : support@redpixel.com)

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

/**
 * A responsive image gallery with slideshow and advanced linking capabilities.
 *
 * @package rps-image-gallery
 * @author Red Pixel Studios
 * @version 2.2.2
 */
if ( ! class_exists( 'RPS_Plugin_Framework' ) and file_exists( dirname( __FILE__ ) . '/rps-plugin-framework/class-plugin-framework.php' ) )
	require_once ( dirname( __FILE__ ) . '/rps-plugin-framework/class-plugin-framework.php' );

require_once( dirname( __FILE__ ) . '/dependencies/rpsslideshow/autoload.php' );
require_once( dirname( __FILE__ ) . '/dependencies/rpsfancybox/autoload.php' );

if ( ! class_exists( 'RPS_Image_Gallery', false ) ) {

	class RPS_Image_Gallery extends RPS_Plugin_Framework {
			
		/**
		 * Upgrades should be added sequentially, from oldest to newest.
		 *
		 * @since 1.2.30
		 */
		public function upgrades() {
			return;
		}
			
		/**
		 * The current version of the plugin for internal use.
		 * Be sure to keep this updated as the plugin is updated.
		 *
		 * @since 1.2.30
		 */
		public function plugin_version() {
			return '2.2.2';
		}
	
		/**
		 * The plugin's name for use in printing to the user.
		 *
		 * @since 1.2.30
		 */
		public function plugin_name() {
			return 'RPS Image Gallery';
		}
	
		/**
		 * A unique identifier for the plugin. Used for CSS classes
		 * and the like. Uses hyphens instead of spaces.
		 *
		 * @since 1.2.30
		 */
		public function plugin_slug() {
			return 'rps-image-gallery';
		}
		
		/**
		 * A unique prefix that identifies the plugin. Used for storing
		 * database options, naming interface elements, and so on.
		 *
		 * @since 1.2.30
		 */
		public function plugin_prefix() {
			return 'rps_image_gallery';
		}
	
		/**
		 * A unique capability that uses the custom post type. Used for managing
		 * all the capabilities of the custom post such as edit, add, remove, etc.
		 *
		 * @since 1.2.30
		 */
		public function manage_capability() {
			return 'manage_rps_image_gallery';
		}
	
		/**
		 * Parent page for the plugin settings page.
		 *
		 * @since 1.2.30
		 */
		public function options_page_parent() {
			return 'options-general.php';
		}
	
		/**
		 * Slug for the plugin settings page.
		 *
		 * @since 1.2.30
		 */
		public function options_page_slug() {
			return 'rps_image_gallery_options';
		}
	
		/**
		 * Default key for checkbox arrays.
		 *
		 * @since 1.2.30
		 */
		public function checkbox_default_key() {
			return 'display';
		}

		/**
		 * A private instance of the plugin for internal use.
		 *
		 * @since 1.2.24
		 */
		private static $plugin_instance;
			
		/**
		 * An entry point wrapper to ensure that the plugin is only invoked once.
		 *
		 * @since 1.2.24
		 */
		public static function invoke() {
			if ( ! isset( self::$plugin_instance ) )
				self::$plugin_instance = new self;
		}
	
		public function __construct() {
			parent::__construct();

			add_action( 'plugins_loaded', array( &$this, '_plugins_loaded' ) );
			add_action( 'init', array( &$this, '_init' ) );
			add_action( 'wp_enqueue_scripts', array( &$this, '_enqueue_styles_scripts' ) );
			add_action( 'wp_footer', array( &$this, '_footer_styles_scripts' ) );
			
			add_filter( 'attachment_fields_to_edit', array( &$this, 'f_media_edit_gallery_link' ), 10, 2 );
			add_filter( 'attachment_fields_to_save', array( &$this, 'f_media_save_gallery_link' ), 10, 2 );
			
			add_filter( 'attachment_fields_to_edit', array( &$this, 'f_media_edit_gallery_link_target' ), 10, 2 );
			add_filter( 'attachment_fields_to_save', array( &$this, 'f_media_save_gallery_link_target' ), 10, 2 );
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( &$this, '_settings_link' ) );
			add_filter( 'media_view_settings', array( &$this, 'gallery_defaults' ) );
			
			self::_init_redux_arguments();
			self::_init_sections();
			add_filter( 'redux/options/' . $this->get_plugin_opt_name() . '/compiler', array( &$this, 'compiler_action' ), 10, 3 );
			if ( method_exists( $this, '_init_fields' ) ) self::_init_fields();
		}
		
		/**
		 * Updates roles with manage capability.
		 *
		 * @since 1.2.30
		 */
		public function _update_capabilities() {
			//self::_update_capability( self::get_manage_capability(), self::get_plugin_opt_name() );
			return;
		}
		
		/**
		 * Converts checkbox options to switch options.
		 * For versions of the plugin prior to 2.0.0.
		 *
		 * @since 2.0.0
		 */
		public function _convert_checkbox_options_to_switch_options() {

			$options = get_option( '_rps_image_gallery' );
			$checkbox_default_key = self::checkbox_default_key();
			
			$option_keys_to_modify = array( 
				'heading', 
				'caption', 
				'caption_auto_format', 
				'background_thumbnails', 
				'slideshow', 
				'fb_title_show', 
				'alt_caption_fallback', 
				'fb_title_counter_show', 
				'fb_center_on_scroll',
				'fb_cyclic', 
				'fb_show_close_button', 
				'exif', 
			);
			
			foreach ( $option_keys_to_modify as $option ) {
				
				if ( isset( $options[$option] ) and is_array( $options[$option] ) and isset( $options[$option][$checkbox_default_key] ) ) {
					
					$options[$option] = ( $options[$option][$checkbox_default_key] == 1 ) ? '1' : '0';
					
				}
				
			}
						
			update_option( '_rps_image_gallery', $options );
				
		}
		
		/**
		 * Facebook SDK.
		 * @since 1.2.30
		 */
		public function facebook_sdk() {
			$output = <<<EOD
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
EOD;
			echo $output;
		}
		
		/**
		 * Pinterest SDK.
		 * @since 1.2.30
		 */
		public function pinterest_sdk() {
			$output = <<<EOD
<script type="text/javascript" async defer src="//assets.pinterest.com/js/pinit.js"></script>
EOD;
			echo $output;
		}
			
		/**
		 * Initialize arguments for Redux Framework.
		 *
		 * @see https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
		 * @since 1.2.24
		 */
		public function _init_redux_arguments() {
			
			$this->args = array(
	            
				'opt_name'          	=> $this->get_plugin_opt_name(), // This is where your data is stored in the database and also becomes your global variable name.
				'display_name'			=> $this->get_plugin_name(), // Name that appears at the top of your panel
				'display_version'		=> $this->get_plugin_version(), // Version that appears at the top of your panel
				'allow_sub_menu'     	=> false, // Show the sections below the admin menu item or not
				'menu_title'			=> __( 'RPS Image Gallery', 'rps-image-gallery' ),
	            'page'		 	 		=> $this->get_plugin_name(),
	            'google_api_key'   	 	=> '', // Must be defined to add google fonts to the typography module
	            'dev_mode'           	=> false, // Show the time the page took to load, etc
	            'customizer'         	=> false, // Enable basic customizer support
	            'page_priority'      	=> null, // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
	            'page_type'             => 'submenu', // Must be set to 'submenu' if page_parent is to be used. Options are 'menu' and 'submenu'.
	            'page_parent'        	=> $this->get_options_page_parent(), // Admin page where the interface should appear
	            'page_permissions'   	=> 'manage_options', // Permissions needed to access the options panel.
	            'menu_icon'          	=> '', // Specify a custom URL to an icon
	            'last_tab'           	=> '', // Force your panel to always open to a specific tab (by id)
	            'page_icon'          	=> 'icon-themes', // Icon displayed in the admin panel next to your menu_title
	            'page_slug'          	=> $this->get_options_page_slug(), // Page slug used to denote the panel
	            'save_defaults'      	=> true, // On load save the defaults to DB before user clicks save or not
	            'admin_bar'          	=> false, // Show the panel pages on the admin bar
	            'default_show'       	=> false, // If true, shows the default value next to each field that is not the default value.
	            'default_mark'       	=> '*', // What to print by the field's title if the value shown is default. Suggested: *	            
	            'show_import_export' 	=> true, // Whether to display the Import/Export tab
	            'help_tabs'          	=> array(),
	            'help_sidebar'       	=> '', // __( '', $this->args['domain'] );,
	            'intro_text'			=> '',
				);
		}
		
		/**
		 * Generate responsive styles.
		 *
		 * @since 2.1.5
		 * @author Eric Kyle
		 */
		public function generate_responsive_styles( $options = array() ) {
			
			$output = '';
			
			if ( empty( $options ) ) {
				
				global $_rps_image_gallery;
				$options = $_rps_image_gallery;
				
			}
			
			if ( 
				! empty( $options ) and 
				( isset( $options['responsive_columns'] ) and $this->boolval( $options['responsive_columns'] ) ) and 
				( ! isset( $options['theme'] ) or ( isset( $options['theme'] ) and $options['theme'] !== 'none' ) )
			) {
				
				$responsive_columns_breakpoints = array(
					1 => 0,
					2 => $options['responsive_columns_2_min_width'],
					3 => $options['responsive_columns_3_min_width'],
					4 => $options['responsive_columns_4_min_width'],
					5 => $options['responsive_columns_5_min_width'],
					6 => $options['responsive_columns_6_min_width'],
					7 => $options['responsive_columns_7_min_width'],
					8 => $options['responsive_columns_8_min_width'],
					9 => $options['responsive_columns_9_min_width'],
				);
				
				$dom_elements = array();
				
				foreach ( $responsive_columns_breakpoints as $columns => $breakpoint ) {
					
					$dom_elements[] = ".rps-image-gallery-columns-responsive.gallery-columns-{$columns} .gallery-item";
					
					if ( $columns !== 1 and (int)$breakpoint === 0 ) {
						unset( $responsive_columns_breakpoints[$columns] );
					}
					
				}
				
				if ( ! empty( $responsive_columns_breakpoints ) and count( $responsive_columns_breakpoints ) > 1 ) {
					
					asort( $responsive_columns_breakpoints );
															
					foreach ( $responsive_columns_breakpoints as $columns => $breakpoint ) {
						
						if ( $columns <= $options['columns'] ) {

							$attributes = '{ width:' . 100/max( $columns, 1 ) . '% !important;max-width:100%; }';
							$output .= '@media screen and (min-width:' . $breakpoint . 'px){';
							$output .= implode( ',', $dom_elements ) . $attributes;
							$output .= '}';
							
						}
		
					}
					
				}
				
			}
			
			return $output;

		}
		
		/**
		 * Callback that writes dynamically generated CSS.
		 *
		 * @since 2.1.0
		 */
		public function compiler_action( $options, $css, $changed_values ) {
		    
		    if ( isset( $options['responsive_columns'] ) and $options['responsive_columns'] == '1' ) {
		    
			    global $wp_filesystem;
	
			    if ( empty( $wp_filesystem ) ) {
				    
			        require_once( ABSPATH . '/wp-admin/includes/file.php' );
			        WP_Filesystem();
			        
			    }			    
			    
			    $plugin_path = str_replace( ABSPATH, $wp_filesystem->abspath(), plugin_dir_path( __FILE__ ) );
			    
			    if ( wp_is_writable( $plugin_path ) ) {
	
				    $css = $this->generate_responsive_styles( $options );
				    			
				    if ( is_multisite() ) {
						
						if ( ! $wp_filesystem->is_dir( $plugin_path . '/sites/' ) ) {
							
							$wp_filesystem->mkdir( $plugin_path . '/sites/' );
							
						}
		
						$filename = $plugin_path . 'sites/rps-image-gallery-responsive-site-' . get_current_blog_id() . '.css';
					    
				    }
				    else {
			
					    $filename = $plugin_path . '/rps-image-gallery-responsive.css';
			
				    }
							 
				    if ( $wp_filesystem ) {
					    
				        $wp_filesystem->put_contents(
				            $filename,
				            $css,
				            FS_CHMOD_FILE // predefined mode settings for WP files
				        );
				        
				    }
				    
				}
				
			}
			
		}

		/**
		 * Initialize sections and fields for settings form.
		 *
		 * @since 1.2.24
		 */
		public function _init_sections() {
			
			$notice_responsive_columns_set_to_zero = _x( 'Set to zero to disable.', 'the responsive columns count sliders', 'rps-image-gallery' );
						
			$this->sections[] = array(
				'title' => __('Gallery', 'rps-image-gallery'),
				'desc' => __('Specify the layout and attributes of the gallery grid of images.', 'rps-image-gallery'),
				'icon' => 'el-icon-book',
			    'submenu' => true,
				'fields' => array(	
					array(
						'id'=>'size',
						'type' => 'callback',
						'title' => __( 'Image Size', 'rps-image-gallery' ), 
						'subtitle' => 'size="thumbnail"',
						'desc' => __( 'Specify the image size for the gallery view.', 'rps-image-gallery' ),
						'default' => 'thumbnail',
						'callback' => '_rps_image_gallery_custom_field_image_sizes',
						),
					array(
						'id'=>'theme',
						'type' => 'radio',
						'title' => __( 'Theme', 'rps-image-gallery' ),
						'subtitle' => 'theme="default"',
						'desc' => __( 'The theme used to display the gallery. Some WordPress themes do not have gallery support so selecting "none" may result in an unformatted gallery. Responsive styles are not available when Theme is set to "none".', 'rps-image-gallery' ),
						'options' => array( 'default' => __( 'Default', 'rps-image-gallery' ), 'none' => __( 'None (use active WordPress theme defaults)', 'rps-image-gallery' ) ),
						'default' => 'default',
						),
					array(
						'id'=>'constrain',
						'type' => 'button_set',
						'title' => __( 'Image Constrain', 'rps-image-gallery' ), 
						'subtitle' => 'constrain="false"',
						'desc' => __( 'Specify whether the image size should be constrained in the gallery view and what settings to use as the source of the constraints.', 'rps-image-gallery' ),
						'options' => array(
							'none' => __( 'None', 'rps-image-gallery' ),
							'plugin' => __( 'Plugin Options', 'rps-image-gallery' ),
							'media' => __( 'Media Settings', 'rps-image-gallery' ),
							),
						'default' => 'none',
						'required' => array( 'theme', '!=', 'default' ),
						),
					array(
						'id'=>'constrain_size',
						'type' => 'select',
						'title' => __( 'Constrain Size', 'rps-image-gallery' ), 
						'subtitle' => 'constrain_size="thumbnail"',
						'desc' => __( 'Specify the image size used for constraining the maximum width and height of images in the gallery view.', 'rps-image-gallery' ),
						'default' => 'thumbnail',
						'options' => array(
							'thumbnail' => __( 'Thumbnail', 'rps-image-gallery' ),
							'medium' 	=> __( 'Medium', 'rps-image-gallery' ),
							'large' 	=> __( 'Large', 'rps-image-gallery' ),	
						),
						'required' => array( array( 'theme', '!=', 'default' ), array( 'constrain', '=', 'media' ) ),
						),
					array(
						'id'=>'constrain_width',
						'type' => 'slider', 
						'title' => __( 'Constrain Width', 'rps-image-gallery' ), 
						'subtitle' => 'constrain_width="150"',
						'desc' => __( 'Specify the maximum width of images in the gallery view.', 'rps-image-gallery' ),
						'min' => '1',
						'step' => '1',
						'max' => '1920',
						'default' => '150',
						'validate' => 'numeric',
						'required' => array( array( 'theme', '!=', 'default' ), array( 'constrain', '=', 'plugin' ) ),
						),
					array(
						'id'=>'constrain_height',
						'type' => 'slider', 
						'title' => __( 'Constrain Height', 'rps-image-gallery' ), 
						'subtitle' => 'constrain_height="150"',
						'desc' => __( 'Specify the maximum height of images in the gallery view.', 'rps-image-gallery' ),
						'min' => '1',
						'step' => '1',
						'max' => '1920',
						'default' => '150',
						'validate' => 'numeric',
						'required' => array( array( 'theme', '!=', 'default' ), array( 'constrain', '=', 'plugin' ) ),
						),
					array(
						'id'=>'container',
						'type' => 'radio',
						'title' => __( 'Container', 'rps-image-gallery' ),
						'subtitle' => 'container="div"',
						'desc' => __( 'The HTML tag containing the gallery grid.', 'rps-image-gallery' ),
						'options' => array( 'div' => __( 'Division', 'rps-image-gallery' ) . ' <em>&lt;div&gt;</em>', 'span' => __( 'Span', 'rps-image-gallery' ) . ' <em>&lt;span&gt;</em>' ),
						'default' => 'div',
						),
					array(
						'id'=>'html_format',
						'type' => 'radio',
						'title' => __( 'HTML Format', 'rps-image-gallery' ), 
						'subtitle' => 'html_format="default"',
						'desc' => __( 'HTML format for the gallery. Some older WordPress themes may require the Legacy format in order for the gallery to display properly.', 'rps-image-gallery' ),
						'options' => array( 'default' => __( 'Default', 'rps-image-gallery' ) . ' <em>&lt;ul&gt;</em>', 'html5' => __( 'HTML5', 'rps-image-gallery' ) . ' <em>&lt;figure&gt;</em>', 'legacy' => __( 'Legacy', 'rps-image-gallery' ) . ' <em>&lt;dl&gt;</em>' ),
						'default' => 'default',
						),
					array(
						'id'=>'masonry',
						'type' => 'switch',
						'title' => __( 'Masonry', 'rps-image-gallery' ), 
						'desc' => __( 'Enable grid layout using Masonry for galleries containing images with various aspect ratios. Loads the Masonry Controller which requires the Images Loaded and Masonry scripts to be loaded. Enabling Masonry automatically disables gallery paging.', 'rps-image-gallery' ),
						'default' => false,
						),
					array(
						'id'=>'responsive_columns',
						'type' => 'switch',
						'title' => __( 'Responsive Columns', 'rps-image-gallery' ), 
						'desc' => __( 'Enable responsive column styles to specify the number of gallery columns at various viewport widths. Shortcodes with the columns attribute defined will override these settings.', 'rps-image-gallery' ),
						'default' => false,
						'required' => array('theme', '!=', 'none'),
						'compiler' => true
						),
					array(
						'id'=>'load_responsive_styles_as',
						'type' => 'radio',
						'title' => __( 'Loading Method for Responsive Styles', 'rps-image-gallery' ), 
						'desc' => __( 'By default styles are loaded using the link tag. Inline styles may be used if preferred or if permissions issues prevent stylesheets from being written to the plugin directory.', 'rps-image-gallery' ),
						'options' => array(
							'link' => __( 'Link', 'rps-image-gallery' ),
							'inline' => __ ( 'Inline', 'rps-image-gallery' )
						),
						'default' => 'link',
						'required' => array('responsive_columns', 'equals', '1'),
						'compiler' => true
						),
					array(
						'id'=>'columns',
						'type' => 'slider', 
						'title' => __( 'Columns', 'rps-image-gallery' ),
						'subtitle' => 'columns="3"',
						'desc' => __( 'Number of columns making up the grid of images.', 'rps-image-gallery' ),
						'min' => '1',
						'step' => '1',
						'max' => '9',
						'default' => '3',
						'validate' => 'numeric',
						'compiler' => true
						),	
					array(
						'id'=>'responsive_columns_2_min_width',
						'type' => 'slider', 
						'title' => __( '2 Column Viewport Width', 'rps-image-gallery' ),
						'subtitle' => $notice_responsive_columns_set_to_zero,
						'desc' => __( 'Minimum viewport width in pixels for 2 column gallery.', 'rps-image-gallery' ),
						'min' => '0',
						'step' => '1',
						'max' => '1920',
						'default' => '0',
						'validate' => 'numeric',
						'required' => array(array('columns', '>=', '2'),array('responsive_columns','=','1')),
						'compiler' => true
						),	
					array(
						'id'=>'responsive_columns_3_min_width',
						'type' => 'slider', 
						'title' => __( '3 Column Viewport Width', 'rps-image-gallery' ),
						'subtitle' => $notice_responsive_columns_set_to_zero,
						'desc' => __( 'Minimum viewport width in pixels for 3 column gallery.', 'rps-image-gallery' ),
						'min' => '0',
						'step' => '1',
						'max' => '1920',
						'default' => '0',
						'validate' => 'numeric',
						'required' => array(array('columns', '>=', '3'),array('responsive_columns','=','1')),
						'compiler' => true
						),	
					array(
						'id'=>'responsive_columns_4_min_width',
						'type' => 'slider', 
						'title' => __( '4 Column Viewport Width', 'rps-image-gallery' ),
						'subtitle' => $notice_responsive_columns_set_to_zero,
						'desc' => __( 'Minimum viewport width in pixels for 4 column gallery.', 'rps-image-gallery' ),
						'min' => '0',
						'step' => '1',
						'max' => '1920',
						'default' => '0',
						'validate' => 'numeric',
						'required' => array(array('columns', '>=', '4'),array('responsive_columns','=','1')),
						'compiler' => true
						),	
					array(
						'id'=>'responsive_columns_5_min_width',
						'type' => 'slider', 
						'title' => __( '5 Column Viewport Width', 'rps-image-gallery' ),
						'subtitle' => $notice_responsive_columns_set_to_zero,
						'desc' => __( 'Minimum viewport width in pixels for 5 column gallery.', 'rps-image-gallery' ),
						'min' => '0',
						'step' => '1',
						'max' => '1920',
						'default' => '0',
						'validate' => 'numeric',
						'required' => array(array('columns', '>=', '5'),array('responsive_columns','=','1')),
						'compiler' => true
						),	
					array(
						'id'=>'responsive_columns_6_min_width',
						'type' => 'slider', 
						'title' => __( '6 Column Viewport Width', 'rps-image-gallery' ),
						'subtitle' => $notice_responsive_columns_set_to_zero,
						'desc' => __( 'Minimum viewport width in pixels for 6 column gallery.', 'rps-image-gallery' ),
						'min' => '0',
						'step' => '1',
						'max' => '1920',
						'default' => '0',
						'validate' => 'numeric',
						'required' => array(array('columns', '>=', '6'),array('responsive_columns','=','1')),
						'compiler' => true
						),	
					array(
						'id'=>'responsive_columns_7_min_width',
						'type' => 'slider', 
						'title' => __( '7 Column Viewport Width', 'rps-image-gallery' ),
						'subtitle' => $notice_responsive_columns_set_to_zero,
						'desc' => __( 'Minimum viewport width in pixels for 7 column gallery.', 'rps-image-gallery' ),
						'min' => '0',
						'step' => '1',
						'max' => '1920',
						'default' => '0',
						'validate' => 'numeric',
						'required' => array(array('columns', '>=', '7'),array('responsive_columns','=','1')),
						'compiler' => true
						),	
					array(
						'id'=>'responsive_columns_8_min_width',
						'type' => 'slider', 
						'title' => __( '8 Column Viewport Width', 'rps-image-gallery' ),
						'subtitle' => $notice_responsive_columns_set_to_zero,
						'desc' => __( 'Minimum viewport width in pixels for 8 column gallery.', 'rps-image-gallery' ),
						'min' => '0',
						'step' => '1',
						'max' => '1920',
						'default' => '0',
						'validate' => 'numeric',
						'required' => array(array('columns', '>=', '8'),array('responsive_columns','=','1')),
						'compiler' => true
						),	
					array(
						'id'=>'responsive_columns_9_min_width',
						'type' => 'slider', 
						'title' => __( '9 Column Viewport Width', 'rps-image-gallery' ),
						'subtitle' => $notice_responsive_columns_set_to_zero,
						'desc' => __( 'Minimum viewport width in pixels for 9 column gallery.', 'rps-image-gallery' ),
						'min' => '0',
						'step' => '1',
						'max' => '1920',
						'default' => '0',
						'validate' => 'numeric',
						'required' => array(array('columns', '=', '9'),array('responsive_columns','=','1')),
						'compiler' => true
						),	
					array(
						'id'=>'heading',
						'type' => 'switch',
						'title' => __( 'Image Title', 'rps-image-gallery' ), 
						'subtitle' => 'heading="false"',
						'desc' => __( 'The title appears just below the image in the gallery grid and after the image counter in the slideshow.', 'rps-image-gallery' ),
						'default' => false,
						),
					array(
						'id'=>'headingtag',
						'type' => 'select',
						'title' => __( 'Image Title Tag', 'rps-image-gallery' ), 
						'subtitle' => 'headingtag="h2"',
						'desc' => __( 'The HTML tag used to wrap the image title.', 'rps-image-gallery' ),
						'options' => array( 'h1' => 'h1', 'h2' => 'h2', 'h3' => 'h3', 'h4' => 'h4', 'h5' => 'h5', 'h6' => 'h6' ),
						'default' => 'h2',
						'required' => array( 'heading', 'equals', true ),
						),
					array(
						'id'=>'heading_align',
						'type' => 'radio',
						'title' => __( 'Heading Alignment', 'rps-image-gallery' ), 
						'subtitle' => 'heading_align="left"',
						'desc' => __( 'Specify the alignment of the heading text for the gallery.', 'rps-image-gallery' ),
						'options' => array( 'left' => __( 'Left (default)', 'rps-image-gallery' ), 'center' => __( 'Center', 'rps-image-gallery' ), 'right' => __( 'Right', 'rps-image-gallery' ) ),
						'default' => 'left',
						'required' => array('theme', '!=', 'none'),
						),
					array(
						'id'=>'caption',
						'type' => 'switch',
						'title' => __( 'Caption', 'rps-image-gallery' ), 
						'subtitle' => 'caption="false"',
						'desc' => __( 'The caption appears just below the image in the gallery grid and after the image counter and image title in the slideshow.', 'rps-image-gallery' ),
						'default' => false,
						),
					array(
						'id'=>'caption_auto_format',
						'type' => 'switch',
						'title' => __( 'Caption Auto Format', 'rps-image-gallery' ), 
						'subtitle' => 'caption_auto_format="false"',
						'desc' => __( 'Automatically insert break and paragraph tags into caption.', 'rps-image-gallery' ),
						'default' => false,
						'required' => array( 'caption', 'equals', true ),
						),
					array(
						'id'=>'caption_source',
						'type' => 'radio',
						'title' => __( 'Caption Source', 'rps-image-gallery' ), 
						'subtitle' => 'caption_source="caption"',
						'desc' => __( 'Specify the source of the text displayed as the caption.', 'rps-image-gallery' ),
						'options' => array( 'caption' => __( 'Caption (default)', 'rps-image-gallery' ), 'description' => __( 'Description', 'rps-image-gallery' ) ),
						'default' => 'caption',
						'required' => array( 'caption', 'equals', true ),
						),
					array(
						'id'=>'caption_align',
						'type' => 'radio',
						'title' => __( 'Caption Alignment', 'rps-image-gallery' ), 
						'subtitle' => 'caption_align="left"',
						'desc' => __( 'Specify the alignment of the caption text for the gallery.', 'rps-image-gallery' ),
						'options' => array( 'left' => __( 'Left (default)', 'rps-image-gallery' ), 'center' => __( 'Center', 'rps-image-gallery' ), 'right' => __( 'Right', 'rps-image-gallery' ) ),
						'default' => 'left',
						'required' => array( array( 'caption', 'equals', true ), array( 'theme', '!=', 'none' ) ),
						),
					array(
						'id'=>'align',
						'type' => 'radio',
						'title' => __( 'Image Alignment', 'rps-image-gallery' ), 
						'subtitle' => 'align="left"',
						'desc' => __( 'Specify the alignment of the last row of images if there are not enough to complete the row.', 'rps-image-gallery' ),
						'options' => array( 'left' => __( 'Left (default)', 'rps-image-gallery' ), 'center' => __( 'Center', 'rps-image-gallery' ), 'right' => __( 'Right', 'rps-image-gallery' ) ),
						'default' => 'left',
						'required' => array('theme', '!=', 'none'),
						),
					//@todo check if background images will work with html5 format
					array(
						'id'=>'background_thumbnails',
						'type' => 'switch',
						'title' => __( 'Background Thumbnails', 'rps-image-gallery' ), 
						'subtitle' => 'background_thumbnails="false"',
						'desc' => __( 'Images displayed as backgrounds have greater flexibility when styled with CSS. Only available with default HTML Format.', 'rps-image-gallery' ),
						'default' => false,
						'required' => array( array( 'html_format', 'equals', 'default' ), array( 'theme', '!=', 'none' ) ),
						),
					array(
						'id'=>'link',
						'type' => 'radio',
						'title' => __( 'Link', 'rps-image-gallery' ), 
						'subtitle' => 'link="permalink"',
						'desc' => __( 'Default behavior when clicking a gallery image if a Gallery Link URL is not set or slideshow mode is disabled.', 'rps-image-gallery' ),
						'options' => array( 'permalink' => 'Attachment Page', 'file' => 'Uploaded Image', 'parent_post' => 'Parent Post', 'none' => 'None' ),
						'default' => 'permalink',
						),
					array(
						'id'=>'page_size',
						'type' => 'text', 
						'title' => __( 'Page Size', 'rps-image-gallery' ),
						'subtitle' => 'page_size="0"',
						'desc' => __( 'Number images per page.', 'rps-image-gallery' ),
						'default' => '0',
						'validate' => 'numeric',
						'required' => array('masonry', '!=', '1'),
						),
					array(
						'id'=>'builtin_pagination_style',
						'type' => 'switch',
						'title' => __( 'Pagination Style', 'rps-image-gallery' ), 
						'subtitle' => 'builtin_pagination_style="false"',
						'desc' => __( 'Use builtin styles for the gallery pagination.', 'rps-image-gallery' ),
						'default' => false,
						'required' => array( array('masonry', '!=', '1'), array('theme', '!=', 'none') ),
						),
					)
				);
	
			$this->sections[] = array(
				'title' => __('Slideshow', 'rps-image-gallery'),
				'desc' => __('Define the slideshow behavior.', 'rps-image-gallery'),
				'icon' => 'el-icon-paper-clip',
			    'submenu' => true,
				'fields' => array(
					array(
						'id'=>'slideshow',
						'type' => 'switch',
						'title' => __( 'Slideshow', 'rps-image-gallery' ), 
						'subtitle' => 'slideshow="true"', 
						'desc' => __( 'Causes the slideshow window to display when a gallery image is clicked.', 'rps-image-gallery' ),
						'default' => true,
						),
					array(
						'id'=>'size_large',
						'type' => 'callback',
						'title' => __( 'Image Size', 'rps-image-gallery' ), 
						'subtitle' => 'size="large"',
						'desc' => __( 'Specify the image size for the slideshow view.', 'rps-image-gallery' ),
						'default' => 'large',
						'callback' => '_rps_image_gallery_custom_field_image_sizes',
						),
					array(
						'id'=>'fb_version',
						'type' => 'radio',
						'title' => __( 'fancyBox Version', 'rps-image-gallery' ), 
						'subtitle' => 'fb_version="1"', 
						'desc' => __( 'A license for fancyBox2 is required if your site is being used for commercial purposes. Visit http://fancyapps.com/fancybox/ for more information.', 'rps-image-gallery' ),
						'options' => array( '1' => __( 'v1', 'rps-image-gallery' ), '2' => __( 'v2', 'rps-image-gallery' ) ),
						'default' => '1',
						),
					array(
						'id'    => 'fb_license',
						'type'  => 'info',
						'title' => __( 'fancyBox2 Licensing', 'redux-framework-demo' ),
						'style' => 'warning',
						'desc' => __( 'A license for fancyBox2 is required if your site is being used for commercial purposes. Visit http://fancyapps.com/fancybox/ for more information.', 'rps-image-gallery' ),
						'required' => array( array( 'fb_usage', '!=', 'noncommercial' ), array( 'fb_version', 'equals', '2' ) ),
						),
					array(
						'id'=>'fb_usage',
						'type' => 'radio',
						'title' => __( 'Site Type', 'rps-image-gallery' ), 
						'desc' => __( 'Specify the nature of the site(s) on which fancyBox will be used.', 'rps-image-gallery' ),
						'options' => array( 'noncommercial' => __( 'Personal or Non-profit', 'rps-image-gallery' ), 'commercial' => __( 'Commercial', 'rps-image-gallery' ), 'both' => __( 'Both (multisite)', 'rps-image-gallery' ) ),
						'default' => 'noncommercial',
						),
					array(
						'id'=>'fb_title_show',
						'type' => 'switch',
						'title' => __( 'Title Area', 'rps-image-gallery' ), 
						'subtitle' => 'fb_title_show="true"', 
						'desc' => __( 'The title area may include the image counter, image title, caption text and EXIF data.', 'rps-image-gallery' ),
						'default' => true,
						),
					array(
						'id'=>'fb_title_position',
						'type' => 'radio',
						'title' => __( 'Title Position', 'rps-image-gallery' ), 
						'subtitle' => 'fb_title_position="over"', 
						'desc' => __( 'Where the title area should appear.', 'rps-image-gallery' ),
						'options' => array( 'over' => __( 'Over the image (default)', 'rps-image-gallery' ), 'outside' => __( 'Below the slide', 'rps-image-gallery' ), 'inside' => __( 'Below the image', 'rps-image-gallery' ) ),
						'default' => 'over',
						'required' => array( 'fb_title_show', 'equals', true ),
						),
					array(
						'id'=>'fb_title_align',
						'type' => 'radio',
						'title' => __( 'Title Alignment', 'rps-image-gallery' ), 
						'subtitle' => 'fb_title_align="none"', 
						'desc' => __( 'Alignment of text in the title area.', 'rps-image-gallery' ),
						'options' => array( 'none' => __( 'None (default)', 'rps-image-gallery' ), 'left' => __( 'Left', 'rps-image-gallery' ), 'center' => __( 'Center', 'rps-image-gallery' ), 'right' => __( 'Right', 'rps-image-gallery' ) ),
						'default' => 'none',
						'required' => array( 'fb_title_show', 'equals', true ),
						),
					array(
						'id'=>'fb_title_counter_show',
						'type' => 'switch',
						'title' => __( 'Image Counter', 'rps-image-gallery' ), 
						'subtitle' => 'fb_title_counter_show="true"', 
						'desc' => __( 'The image counter displays the word "Image" followed by the image number and total count.', 'rps-image-gallery' ),
						'default' => true,
						'required' => array( 'fb_title_show', 'equals', true ),
						),
					array(
						'id'=>'fb_heading',
						'type' => 'switch',
						'title' => __( 'Image Title', 'rps-image-gallery' ), 
						'subtitle' => 'fb_heading="true"',
						'desc' => __( 'The title appears just after the image counter and before the caption in the slideshow.', 'rps-image-gallery' ),
						'default' => true,
						'required' => array( 'fb_title_show', 'equals', true ),
						),
					array(
						'id'=>'fb_caption',
						'type' => 'switch',
						'title' => __( 'Image Caption', 'rps-image-gallery' ), 
						'subtitle' => 'fb_caption="true"',
						'desc' => __( 'The caption appears just and after the image counter and title in the slideshow.', 'rps-image-gallery' ),
						'default' => true,
						'required' => array( 'fb_title_show', 'equals', true ),
						),
					array(
						'id'=>'alt_caption_fallback',
						'type' => 'switch',
						'title' => __( 'Caption Fallback', 'rps-image-gallery' ), 
						'subtitle' => 'alt_caption_fallback="true"', 
						'desc' => __( 'Use the ALT value as a fallback in case the Caption field is empty.', 'rps-image-gallery' ),
						'default' => true,
						'required' => array( array( 'fb_title_show', 'equals', true ), array( 'fb_caption', 'equals', true ) ),
						),
					array(
						'id'=>'fb_center_on_scroll',
						'type' => 'switch',
						'title' => __( 'Slideshow Position', 'rps-image-gallery' ), 
						'subtitle' => 'fb_center_on_scroll="true"', 
						'desc' => __( 'Keeps the slideshow centered in the viewport when the window is scrolled or resized.', 'rps-image-gallery' ),
						'default' => true,
						'required' => array( 'fb_version', 'equals', '1' ),
						),
					array(
						'id'=>'fb_cyclic',
						'type' => 'switch',
						'title' => __( 'Loop', 'rps-image-gallery' ), 
						'subtitle' => 'fb_cyclic="true"', 
						'desc' => __( 'Determines if the slideshow should start from the beginning after it reaches the end.', 'rps-image-gallery' ),
						'default' => true,
						),
					array(
						'id'=>'autoplay',
						'type' => 'switch',
						'title' => __( 'Autoplay', 'rps-image-gallery' ), 
						'subtitle' => 'autoplay="true"', 
						'desc' => __( 'Autoplay slideshow. Looping must be turned on.', 'rps-image-gallery' ),
						'default' => false,
						'required' => array( 'fb_cyclic', 'equals', true ),
						),
					array(
						'id'=>'autoplay_time',
						'type' => 'slider', 
						'title' => __( 'Image Display Time on Autoplay', 'rps-image-gallery' ),
						'subtitle' => 'The amount of seconds an image remains visible before transitioning to the next one. autoplay_time="20"', 
						'min' => 1,
						'step' => 1,
						'max' => 30,
						'default' => 6,
						'validate' => 'numeric',
						'required' => array( array( 'fb_cyclic', 'equals', true ), array( 'autoplay', 'equals', true ) ),
						),
					array(
						'id'=>'fb_transition_in',
						'type' => 'radio',
						'title' => __( 'Transition In', 'rps-image-gallery' ), 
						'subtitle' => 'fb_transition_in="true"', 
						'desc' => __( 'Effect when slideshow is opened.', 'rps-image-gallery' ),
						'options' => array( 'none' => __( 'None (default)', 'rps-image-gallery' ), 'elastic' => __( 'Elastic', 'rps-image-gallery' ), 'fade' => __( 'Fade', 'rps-image-gallery' ) ),
						'default' => 'none',
						),
					array(
						'id'=>'fb_speed_in',
						'type' => 'slider', 
						'title' => __( 'Transition Speed In', 'rps-image-gallery' ),
						'subtitle' => 'fb_speed_in="300"', 
						'min' => 100,
						'step' => 100,
						'max' => 1000,
						'default' => 300,
						'validate' => 'numeric',
						),	
					array(
						'id'=>'fb_transition_out',
						'type' => 'radio',
						'title' => __( 'Transition Out', 'rps-image-gallery' ), 
						'subtitle' => 'fb_transition_out="true"', 
						'desc' => __( 'Effect when slideshow is closed.', 'rps-image-gallery' ),
						'options' => array( 'none' => __( 'None (default)', 'rps-image-gallery' ), 'elastic' => __( 'Elastic', 'rps-image-gallery' ), 'fade' => __( 'Fade', 'rps-image-gallery' ) ),
						'default' => 'none',
						),
					array(
						'id'=>'fb_speed_out',
						'type' => 'slider', 
						'title' => __( 'Transition Speed Out', 'rps-image-gallery' ),
						'subtitle' => 'fb_speed_out="300"', 
						'min' => 100,
						'step' => 100,
						'max' => 1000,
						'default' => 300,
						'validate' => 'numeric',
						),	
					array(
						'id'=>'fb_download_link',
						'type' => 'switch',
						'title' => __( 'Download Link', 'rps-image-gallery' ), 
						'subtitle' => 'fb_download_link="false"', 
						'desc' => __( 'Displays a download link. Only works with fancyBox2.', 'rps-image-gallery' ),
						'default' => false,
						'required' => array( 'fb_version', 'equals', '2' ),
						),
					array(
						'id'=>'fb_show_close_button',
						'type' => 'switch',
						'title' => __( 'Close Button', 'rps-image-gallery' ), 
						'subtitle' => 'fb_show_close_button="true"', 
						'default' => true,
						),
					array(
						'id'=>'fb_padding',
						'type' => 'slider', 
						'title' => __( 'Padding', 'rps-image-gallery' ),
						'subtitle' => 'fb_padding="10"', 
						'desc' => __( 'Space between fancyBox wrapper and content.', 'rps-image-gallery' ),
						'min' => 0,
						'step' => 1,
						'max' => 100,
						'default' => 10,
						'validate' => 'numeric',
						),	
					array(
						'id'=>'fb_margin',
						'type' => 'slider', 
						'title' => __( 'Margin', 'rps-image-gallery' ),
						'subtitle' => 'fb_margin="20"', 
						'desc' => __( 'Space between viewport and fancyBox wrapper.', 'rps-image-gallery' ),
						'min' => 0,
						'step' => 1,
						'max' => 300,
						'default' => 20,
						'validate' => 'numeric',
						),	
					array(
						'id'=>'fb_overlay_opacity',
						'type' => 'slider', 
						'title' => __( 'Overlay Opacity', 'rps-image-gallery' ),
						'subtitle' => 'fb_overlay_opacity="0.3"', 
						'desc' => __( 'Opacity of the overlay appearing behind the slideshow and on top of the page.', 'rps-image-gallery' ),
						'min' => 0,
						'step' => .01,
						'max' => 1,
						'default' => .3,
						'validate' => 'numeric',
						'resolution' => 0.01,
						),	
					array(
					    'id' => 'fb_overlay_color',
					    'type' => 'color',
					    'title' => __('Overlay Color', 'rps-image-gallery'), 
					    'subtitle' => __('Color of the overlay appearing behind the slideshow and on top of the page.', 'rps-image-gallery'),
					    'default' => '#666',
					    'validate' => 'color',
					    'transparent' => false
						),
					array(
					    'id' => 'fb_helper_thumbs',
						'type' => 'switch',
						'title' => __( 'Thumbnail Helper', 'rps-image-gallery' ), 
						'subtitle' => 'fb_helper_thumbs="false"', 
						'default' => false,
						'required' => array( 'fb_version', 'equals', '2' ),
						),
					array(
						'id'=>'fb_helper_thumbs_width',
						'type' => 'slider', 
						'title' => __( 'Helper Thumbs Width', 'rps-image-gallery' ),
						'subtitle' => 'fb_helper_thumbs_width="50"', 
						'desc' => __( 'Width of the helper thumbs in pixels.', 'rps-image-gallery' ),
						'min' => 20,
						'step' => 1,
						'max' => 300,
						'default' => 50,
						'validate' => 'numeric',
						'required' => array( array( 'fb_version', 'equals', '2' ), array( 'fb_helper_thumbs', 'equals', '1' ) ),
						),	
					array(
						'id'=>'fb_helper_thumbs_height',
						'type' => 'slider', 
						'title' => __( 'Helper Thumbs Height', 'rps-image-gallery' ),
						'subtitle' => 'fb_helper_thumbs_height="50"', 
						'desc' => __( 'Height of the helper thumbs in pixels.', 'rps-image-gallery' ),
						'min' => 20,
						'step' => 1,
						'max' => 300,
						'default' => 50,
						'validate' => 'numeric',
						'required' => array( array( 'fb_version', 'equals', '2' ), array( 'fb_helper_thumbs', 'equals', '1' ) ),
						),	
					),
				);
	
			$this->sections[] = array(
				'title' => __('EXIF Data', 'rps-image-gallery'),
				'desc' => __('Define if and where EXIF data is displayed.', 'rps-image-gallery'),
				'icon' => 'el-icon-camera',
			    'submenu' => true,
				'fields' => array(	
					array(
						'id'=>'exif',
						'type' => 'switch',
						'title' => __( 'EXIF', 'rps-image-gallery' ), 
						'subtitle' => 'exif="false"',
						'default' => false,
						),
					array(
						'id'=>'exif_locations',
						'type' => 'radio',
						'title' => __( 'EXIF Location', 'rps-image-gallery' ), 
						'subtitle' => __( 'Define where the EXIF data is displayed.', 'rps-image-gallery' ),
						'options' => array( 
							'slideshow' => __( 'Slideshow (default)', 'rps-image-gallery' ), 
							'gallery' => __( 'Gallery', 'rps-image-gallery' ), 
							'both' => __( 'Gallery and Slideshow', 'rps-image-gallery' ),
						),
						'default' => 'slideshow',
						'required' => array( 'exif', 'equals', '1' ),
						),
					array(
			            'id' => 'exif_fields',
				        'type' => 'sortable',
				        'mode' => 'checkbox', // checkbox or text
			    	    'title' => __('EXIF Fields', 'rps-image-gallery'),
			        	'subtitle' => __('Select and reorder the EXIF fields.', 'rps-image-gallery'),
			            'options' => array(
			            	'camera' => __( 'Camera', 'rps-image-gallery' ),
			            	'aperture' => __( 'Aperture', 'rps-image-gallery' ),
			            	'focal_length' => __( 'Focal Length', 'rps-image-gallery' ),
			            	'iso' => __( 'ISO', 'rps-image-gallery' ),
			            	'shutter_speed' => __( 'Shutter Speed', 'rps-image-gallery' ),
			            	'title' => __( 'Title', 'rps-image-gallery' ),
			            	'caption' => __( 'Caption', 'rps-image-gallery' ),
			            	'credit' => __( 'Credit', 'rps-image-gallery' ),
			            	'copyright' => __( 'Copyright', 'rps-image-gallery' ),
			            	'created_timestamp' => __( 'Created Timestamp', 'rps-image-gallery' ),
			    	    	),
			        	'default' => array(
			        		'camera' => '1',
			        		'aperture' => '1',
			        		'focal_length' => '1',
			        		'iso' => '1',
			        		'shutter_speed' => '1',
			        		'title' => '1',
			        		'caption' => '1',
			        		'credit' => '1',
			        		'copyright' => '1',
			        		'created_timestamp' => '1'
			        		),
						'required' => array( 'exif', 'equals', '1' ),
						),
					)
				);
				
			$this->sections[] = array(
				'title' => __('Sorting', 'rps-image-gallery'),
				'desc' => __('The sorting settings only work if the ids shortcode attribute has not been set. Changing the order in the WordPress Gallery editor will add the ids attribute.', 'rps-image-gallery'),
				'icon' => 'el-icon-cogs',
			    'submenu' => true,
				'fields' => array(
					array(
						'id'=>'order',
						'type' => 'radio',
						'title' => __( 'Order', 'rps-image-gallery' ), 
						'subtitle' => 'order="asc"',
						'options' => array( 'asc' => __( 'Ascending (default)', 'rps-image-gallery' ), 'desc' => __( 'Descending', 'rps-image-gallery' ) ),
						'default' => 'asc',
						),
					array(
						'id'=>'orderby',
						'type' => 'radio',
						'title' => __( 'Order By', 'rps-image-gallery' ), 
						'subtitle' => 'orderby="menu_order"',
						'options' => array( 'menu_order' => __( 'Menu Order (default)', 'rps-image-gallery' ), 'title' => __( 'Title', 'rps-image-gallery' ), 'post_date' => __( 'Date', 'rps-image-gallery' ), 'rand' => __( 'Random', 'rps-image-gallery' ), 'ID' => __( 'ID', 'rps-image-gallery' ) ),
						'default' => 'menu_order',
						),
					)
				);
	
			$this->sections[] = array(
				'title' => __('Social Media', 'rps-image-gallery'),
				'desc' => __('Enable social media sharing for gallery images.', 'rps-image-gallery'),
				'icon' => 'el-icon-comment'
			);
	
			$this->sections[] = array(
				'title' => __('Facebook', 'rps-image-gallery'),
				'desc' => __('Enable facebook "like" and "share" buttons.', 'rps-image-gallery'),
			    'submenu' => false,
			    'subsection' => true,
				'fields' => array(
					array(
						'id'=>'facebook_enable',
						'type' => 'switch',
						'title' => __( 'Facebook Integration', 'rps-image-gallery' ), 
						'subtitle' => 'facebook_enable="false"',
						'desc' => __( 'Enable facebook integration.', 'rps-image-gallery' ),
						'default' => false,
						),
					array(
						'id'=>'facebook_action',
						'type' => 'radio',
						'title' => __( 'Action', 'rps-image-gallery' ), 
						'subtitle' => 'facebook_action="like"',
						'desc' => __( 'The verb to display on the button.', 'rps-image-gallery' ),
						'options' => array( 'like' => __( 'Like (default)', 'rps-image-gallery' ), 'recommend' => __( 'Recommend', 'rps-image-gallery' ) ),
						'default' => 'like',
						),
					array(
						'id'=>'facebook_colorscheme',
						'type' => 'radio',
						'title' => __( 'Color Scheme', 'rps-image-gallery' ), 
						'subtitle' => 'facebook_colorscheme="light"',
						'desc' => __( 'The color scheme used by the plugin for any text outside of the button itself.', 'rps-image-gallery' ),
						'options' => array( 'light' => __( 'Light (default)', 'rps-image-gallery' ), 'dark' => __( 'Dark', 'rps-image-gallery' ) ),
						'default' => 'light',
						),
					array(
						'id'=>'facebook_kid_directed_site',
						'type' => 'switch',
						'title' => __( 'Kid Directed Site', 'rps-image-gallery' ), 
						'subtitle' => 'facebook_kid_directed_site="false"',
						'desc' => __( 'My site or a portion of it is directed to children under 13 years of age.', 'rps-image-gallery' ),
						'default' => false,
						),
					array(
						'id'=>'facebook_layout',
						'type' => 'radio',
						'title' => __( 'Layout', 'rps-image-gallery' ), 
						'subtitle' => 'facebook_layout="button"',
						'desc' => __( 'Selects one of the different layouts available for the plugin.', 'rps-image-gallery' ),
						'options' => array( 'button_count' => __( 'Button Count', 'rps-image-gallery' ), 'button' => __( 'Button', 'rps-image-gallery' ) ),
						'default' => 'button',
						),
					array(
						'id'=>'facebook_share',
						'type' => 'switch',
						'title' => __( 'Share Button', 'rps-image-gallery' ), 
						'subtitle' => 'facebook_share="false"',
						'desc' => __( 'Specifies whether to include a share button beside the Like button.', 'rps-image-gallery' ),
						'default' => false,
						),
					)
				);
	
			$this->sections[] = array(
				'title' => __('Pinterest', 'rps-image-gallery'),
				'desc' => __('Enable Pinterest button.', 'rps-image-gallery'),
			    'submenu' => false,
			    'subsection' => true,
				'fields' => array(
					array(
						'id'=>'pinterest_enable',
						'type' => 'switch',
						'title' => __( 'Pinterest Integration', 'rps-image-gallery' ), 
						'subtitle' => 'pinterest_enable="false"',
						'desc' => __( 'Enable Pinterest integration.', 'rps-image-gallery' ),
						'default' => false,
						),
					array(
						'id'=>'pinterest_color',
						'type' => 'radio',
						'title' => __( 'Button Color', 'rps-image-gallery' ), 
						'subtitle' => 'pinterest_color="red"',
						'desc' => __( 'Specifies the color of the button.', 'rps-image-gallery' ),
						'options' => array( 'red' => __( 'Red', 'rps-image-gallery' ), 'gray' => __( 'Gray', 'rps-image-gallery' ), 'white' => __( 'White', 'rps-image-gallery' ) ),
						'default' => 'red',
						),
					)
				);
				
			$this->sections[] = array(
				'title' => __('Compatibility', 'rps-image-gallery'),
				'desc' => __('Modify which components load for better compatibility with various theme configurations.', 'rps-image-gallery'),
				'icon' => 'el-icon-list-alt',
			    'submenu' => true,
				'fields' => array(
					array(
						'id'=>'load_styles',
						'type' => 'switch',
						'title' => __( 'Styles', 'rps-image-gallery' ), 
						'desc' => __( 'Load plugin styles.', 'rps-image-gallery' ),
						'default' => true,
						),
					array(
						'id'=>'load_fancybox',
						'type' => 'switch',
						'title' => __( 'fancyBox', 'rps-image-gallery' ), 
						'desc' => __( 'Load included fancyBox scripts, styles and assets.', 'rps-image-gallery' ),
						'default' => true,
						),
					array(
						'id'=>'load_masonry',
						'type' => 'switch',
						'title' => __( 'Masonry', 'rps-image-gallery' ), 
						'desc' => __( 'Load included Masonry and Images Loaded scripts.', 'rps-image-gallery' ),
						'default' => true,
						),
					array(
						'id'=>'override_gallery_shortcode',
						'type' => 'switch',
						'title' => __( 'Override [gallery]', 'rps-image-gallery' ), 
						'desc' => __( 'Override the built-in gallery shortcode.', 'rps-image-gallery' ),
						'default' => true,
						),
					)
				);
	
		}
								
		/**
		 * Load the text domain for l10n and i18n.
		 *
		 * @since 1.2.22
		 */
		public function _plugins_loaded() {
			parent::_plugins_loaded();
			load_plugin_textdomain( 'rps-image-gallery', false, trailingslashit( dirname( plugin_basename( __FILE__ ) ) ) . 'lang/' );
			$this->_include_redux_framework();
			$this->_convert_checkbox_options_to_switch_options();
		}
					
		/*
		 * Add the gallery_link field to the page for editing.
		 *
		 * @since 1.2
		 */
		public function f_media_edit_gallery_link( $fields, $post ) {
			if ( stristr( $post->post_mime_type, 'image' ) === false ) return $fields;
			
			$fields['post_gallery_link'] = array(
				'label' => __( 'Gallery Link URL', 'rps-image-gallery' ),
				'value' => esc_attr( get_post_meta( $post->ID, '_rps_attachment_post_gallery_link', true ) ),
				'input' => 'text',
				'helps' => __( 'Enter a relative or absolute link that should be followed when the image is clicked<br />from within an image gallery.', 'rps-image-gallery' )
			);
		
			return $fields;
		}
	
		/*
		 * Add the gallery_link_target field to the page for editing
		 *
		 * @since 1.2.6
		 */
		public function f_media_edit_gallery_link_target( $fields, $post ) {
			if ( stristr( $post->post_mime_type, 'image' ) === false ) return $fields;
			
			$target = get_post_meta( $post->ID, '_rps_attachment_post_gallery_link_target', true );
			$options_inner_html = '';
			
			$options = array(
				'_self',
				'_blank',
				'_parent',
				'_top'
			);
			
			foreach ( $options as $option ) :
				$selected = ( $target == $option ) ? 'selected="selected"' : '';
				$default = ( $option == '_self' ) ? ' (default)' : '';
				$options_inner_html .= '<option value="' . $option . '"' . $selected . '>' . $option . $default . '</option>';
			endforeach;
			
			$fields['post_gallery_link_target'] = array(
				'label' => __( 'Gallery Link Target', 'rps-image-gallery' ),
				'value' => $target,
				'input' => 'html',
				'html' => '<select name="attachments[' . $post->ID . '][post_gallery_link_target]" id="attachments[' . $post->ID . '][post_gallery_link_target]">' . $options_inner_html . '</select>',
				'helps' => __( 'Select the target for the Gallery Link URL.', 'rps-image-gallery' )
			);
		
			return $fields;
		}
		
		/*
		 * Save the gallery_link field.
		 *
		 * @since 1.2
		 */
		public function f_media_save_gallery_link( $post, $fields ) {
			if ( !isset( $fields['post_gallery_link'] ) ) return $post;
	
			$safe_url = trim( $fields['post_gallery_link'] );
			if ( empty( $safe_url ) ) {
				if ( get_post_meta( $post['ID'], '_rps_attachment_post_gallery_link', true ) ) {
					delete_post_meta( $post['ID'], '_rps_attachment_post_gallery_link' );
				}
				return $post;
			}
			
			$safe_url = esc_url( $safe_url );
			if ( empty( $safe_url ) ) return $post;
			
			update_post_meta( $post['ID'], '_rps_attachment_post_gallery_link', $safe_url );
			
			return $post;
		}
	
		/*
		 * Save the gallery_link_target field.
		 *
		 * @since 1.2.6
		 */
		public function f_media_save_gallery_link_target( $post, $fields ) {
			if ( !isset( $fields['post_gallery_link_target'] ) ) return $post;
			
			if ( empty( $fields['post_gallery_link_target'] ) ) {
				if ( get_post_meta( $post['ID'], '_rps_attachment_post_gallery_link_target', true ) ) {
					delete_post_meta( $post['ID'], '_rps_attachment_post_gallery_link_target' );
				}
				return $post;
			}
			
			update_post_meta( $post['ID'], '_rps_attachment_post_gallery_link_target', $fields['post_gallery_link_target'] );
			
			return $post;
		}
	
		/*
		 * Return the gallery_link field.
		 *
		 * @since 1.2
		 */
		public function get_gallery_link( $attachment_id ) {
			return get_post_meta( $attachment_id, '_rps_attachment_post_gallery_link', true );
		}
	
		/*
		 * Return the gallery_link_target field.
		 *
		 * @since 1.2
		 */
		public function get_gallery_link_target( $attachment_id ) {
			return get_post_meta( $attachment_id, '_rps_attachment_post_gallery_link_target', true );
		}
	
		/**
		 * Initialize the plugin.
		 *
		 * @since 1.2
		 */
		public function _init() {
			parent:: _init();

			global $_rps_image_gallery;
			$fb_version = ( isset( $_rps_image_gallery['fb_version'] ) ) ? $_rps_image_gallery['fb_version'] : '';
			
			if ( ! isset( $_rps_image_gallery['override_gallery_shortcode'] ) or $_rps_image_gallery['override_gallery_shortcode'] ) {
				add_shortcode( 'gallery', array( &$this, 'cb_gallery_shortcode' ) );
			}
			add_shortcode( 'rps-image-gallery', array( &$this, 'cb_gallery_shortcode' ) );
			add_shortcode( 'rps-gallery', array( &$this, 'cb_gallery_shortcode' ) );
			
			if ( isset( $_rps_image_gallery['facebook_enable'] ) and $_rps_image_gallery['facebook_enable'] ) add_action( 'loop_start', array( &$this, 'facebook_sdk' ) );
			
			wp_register_style( 'rps-image-gallery', plugins_url( 'rps-image-gallery.css', __FILE__ ), false, self::plugin_version() );
			wp_register_style( 'rps-image-gallery-social', plugins_url( 'dependencies/social/social.css', __FILE__ ), false, self::plugin_version() );
			wp_register_style( 'rps-image-gallery-theme-default', plugins_url( 'themes/default/style.css', __FILE__ ), array( 'rps-image-gallery' ), self::plugin_version() );

			wp_register_script( 'rps-image-gallery-pagination', plugins_url( 'dependencies/pagination.js', __FILE__ ), array( 'jquery' ), self::plugin_version(), true );
		
			switch ( $fb_version ) {
				case '2':
					wp_register_style( 'rps-image-gallery-fancybox', plugins_url( 'dependencies/fancybox2/jquery.fancybox.css', __FILE__ ), false, '2.1.5' );
					wp_register_script( 'rps-image-gallery-fancybox', plugins_url( 'dependencies/fancybox2/jquery.fancybox.pack.js', __FILE__ ), array( 'jquery' ), '2.1.5', true );
					wp_register_style( 'rps-image-gallery-fancybox-helper-thumbs', plugins_url( 'dependencies/fancybox2/helpers/jquery.fancybox-thumbs.css', __FILE__ ), false, '2.1.5' );
					wp_register_script( 'rps-image-gallery-fancybox-helper-thumbs', plugins_url( 'dependencies/fancybox2/helpers/jquery.fancybox-thumbs.js', __FILE__ ), array( 'rps-image-gallery-fancybox' ), '2.1.5', true );					
					break;
				default:
					wp_register_style( 'rps-image-gallery-fancybox', plugins_url( 'dependencies/fancybox/jquery.fancybox-1.3.4.css', __FILE__ ), false, '1.3.4' );
					wp_register_script( 'rps-image-gallery-easing', plugins_url( 'dependencies/fancybox/jquery.easing-1.3.pack.2.js', __FILE__ ), array( 'jquery' ), '1.3', true );
					wp_register_script( 'rps-image-gallery-fancybox', plugins_url( 'dependencies/fancybox/jquery.fancybox-1.3.4.pack.js', __FILE__ ), array( 'rps-image-gallery-easing' ), '1.3.4', true );
					break;
			}

			wp_register_script( 'rps-image-gallery-images-loaded', plugins_url( 'dependencies/imagesloaded.pkgd.min.js', __FILE__ ), array( 'jquery' ), '4.1.0', false );
			wp_register_script( 'rps-image-gallery-masonry', plugins_url( 'dependencies/masonry.pkgd.min.js', __FILE__ ), array( 'rps-image-gallery-images-loaded' ), '4.0.0', false );
			wp_register_script( 'rps-image-gallery-masonry-controller', plugins_url( 'dependencies/masonry.js', __FILE__ ), array( 'jquery' ), self::plugin_version(), false );
			
		}
		
		/**
		 * Enqueue styles and scripts.
		 *
		 * @since 1.2
		 */
		public function _enqueue_styles_scripts() {
			global $_rps_image_gallery;
			
			if ( 
				! isset( $_rps_image_gallery['load_styles'] ) or 
				( isset( $_rps_image_gallery['load_styles'] ) and $this->boolval( $_rps_image_gallery['load_styles'] ) ) 
			) {
				
				wp_enqueue_style( 'rps-image-gallery' );
								
				if ( 
					( isset( $_rps_image_gallery['responsive_columns'] ) and $this->boolval( $_rps_image_gallery['responsive_columns'] ) ) and 
					( 
						! isset( $_rps_image_gallery['theme'] ) or 
						( isset( $_rps_image_gallery['theme'] ) and $_rps_image_gallery['theme'] !== 'none' ) 
					) 
				) {
					
					if ( 
						! isset( $_rps_image_gallery['load_responsive_styles_as'] ) or 
						( 
							isset( $_rps_image_gallery['load_responsive_styles_as'] ) and 
							$_rps_image_gallery['load_responsive_styles_as'] === 'link' 
						) 
					) {
					
						if ( is_multisite() ) {
							
							$current_blog_id = get_current_blog_id();
							wp_enqueue_style( 'rps-image-gallery-responsive-site-' . $current_blog_id, plugins_url( 'sites/rps-image-gallery-responsive-site-' . $current_blog_id . '.css', __FILE__ ), array( 'rps-image-gallery' ), self::plugin_version() );
						
						}
						else {
		
							wp_enqueue_style( 'rps-image-gallery-responsive', plugins_url( 'rps-image-gallery-responsive.css', __FILE__ ), array( 'rps-image-gallery' ), self::plugin_version() );
							
						}
						
					}
					elseif ( 
						isset( $_rps_image_gallery['load_responsive_styles_as'] ) and 
						$_rps_image_gallery['load_responsive_styles_as'] === 'inline' 
					) {
						
						wp_add_inline_style( 'rps-image-gallery', $this->generate_responsive_styles() );
						
					}
					
				}
				
				switch ( true ) {
					 
					case ( 
						! isset( $_rps_image_gallery['theme'] ) or 
						( 
							isset( $_rps_image_gallery['theme'] ) and 
							$_rps_image_gallery['theme'] === 'default' 
						) 
					) :
						wp_enqueue_style( 'rps-image-gallery-theme-default' );
						break;
					
				}

			}
				
			if ( 
				( isset( $_rps_image_gallery['facebook_enable'] ) and $this->boolval( $_rps_image_gallery['facebook_enable'] ) ) or 
				( isset( $_rps_image_gallery['pinterest_enable'] ) and $this->boolval( $_rps_image_gallery['pinterest_enable'] ) ) 
			) {
			
				wp_enqueue_style( 'rps-image-gallery-social' );
			
			}
			
			if ( 
				isset( $_rps_image_gallery['load_fancybox'] ) and 
				$_rps_image_gallery['load_fancybox'] 
			) {
			
				wp_enqueue_style( 'rps-image-gallery-fancybox' );
				wp_enqueue_script( 'rps-image-gallery-fancybox' );
				
				if ( 
					isset( $_rps_image_gallery['fb_helper_thumbs'] ) and 
					$this->boolval( $_rps_image_gallery['fb_helper_thumbs'] )
				) {
				
					wp_enqueue_style( 'rps-image-gallery-fancybox-helper-thumbs' );
					wp_enqueue_script( 'rps-image-gallery-fancybox-helper-thumbs' );
				
				}
			
			}
			
			if (
				(
					isset( $_rps_image_gallery['load_masonry'] ) and 
					$this->boolval( $_rps_image_gallery['load_masonry'] ) 
				)
			) {
				
				wp_enqueue_script( 'rps-image-gallery-masonry' );
				
			}
			
			if ( 
				isset( $_rps_image_gallery['masonry'] ) and 
				$this->boolval( $_rps_image_gallery['masonry'] ) 
			) {
				
				wp_enqueue_script( 'rps-image-gallery-masonry-controller' );
				
			}
			
			wp_enqueue_script( 'rps-image-gallery-pagination' );
					
		}
			
		/**
		 * Get size information for all currently-registered image sizes.
		 *
		 * @global $_wp_additional_image_sizes
		 * @uses   get_intermediate_image_sizes()
		 * @return array $sizes Data for all currently-registered image sizes.
		 */
		public function get_image_sizes() {
			global $_wp_additional_image_sizes;
		
			$sizes = array();
		
			foreach ( get_intermediate_image_sizes() as $_size ) {
			
				if ( in_array( $_size, array('thumbnail', 'medium', 'medium_large', 'large') ) ) {

					$sizes[$_size]['width']  = get_option( "{$_size}_size_w" );
					$sizes[$_size]['height'] = get_option( "{$_size}_size_h" );
					$sizes[$_size]['crop']   = (bool) get_option( "{$_size}_crop" );
			
				}
				elseif ( isset( $_wp_additional_image_sizes[$_size] ) ) {
				
					$sizes[$_size] = array(
						'width'  => $_wp_additional_image_sizes[$_size]['width'],
						'height' => $_wp_additional_image_sizes[$_size]['height'],
						'crop'   => $_wp_additional_image_sizes[$_size]['crop'],
					);
				
				}
			
			}
			
			foreach ( $sizes as $key => $size ) {
				
				if ( (int)$size['width'] === 0 ) {
				
					unset( $sizes[$key] );
				
				}
				
			}
		
			return $sizes;
		}

		/**
		 * Update WordPress gallery defaults.
		 *
		 * @since 2.2.2
		 */
		function gallery_defaults( $settings ) {
		    $settings['galleryDefaults']['columns'] = self::_shortcode_default( 'columns' );
		    return $settings;
		}

		/**
		 * The gallery shortcode.
		 *
		 * @since 1.0
		 * @todo Setup array of statuses to exclude in options framework.
		 */					
		public function cb_gallery_shortcode( $atts, $content = null ) {
		
			global $post;
			global $_rps_image_gallery;
					
			$output = '';
			$gallery_items_html = '';
			$gallery_ids = array();
			$attachments = array();
			$excluded_statuses = array( 'trash', 'future' );
			
			/*
			 * Specify defaults for shortcode attributes.
			 */
			$defaults = array(
				'id' => get_the_id(),
				'ids' => '',
				'group_name' => 'rps-image-group-' . $post->ID,
				'include' => '',
				'exclude' => '',
				'container' => self::_shortcode_default( 'container' ),
				'columns' => '',
				'classes' => '',
				'align' => self::_shortcode_default( 'align' ),
				'size' => self::_shortcode_default( 'size' ),
				'constrain' => self::_shortcode_default( 'constrain' ),
				'constrain_size' => self::_shortcode_default( 'constrain_size' ),
				'constrain_width' => self::_shortcode_default( 'constrain_width' ),
				'constrain_height' => self::_shortcode_default( 'constrain_height' ),
				'size_large' => self::_shortcode_default( 'size_large' ),
				'orderby' => self::_shortcode_default( 'orderby' ),
				'order' => self::_shortcode_default( 'order' ),
				'heading' => self::_shortcode_default( 'heading' ),
				'headingtag' => self::_shortcode_default( 'headingtag' ),
				'heading_align' => self::_shortcode_default( 'heading_align' ),
				'caption' => self::_shortcode_default( 'caption' ),
				'captiontag' => 'span',
				'caption_auto_format' => self::_shortcode_default( 'caption_auto_format' ),
				'caption_source' => self::_shortcode_default( 'caption_source' ),
				'caption_align' => self::_shortcode_default( 'caption_align' ),
				'link' => self::_shortcode_default( 'link' ),
				'slideshow' => self::_shortcode_default( 'slideshow' ),
				'autoplay' => self::_shortcode_default( 'autoplay' ),
				'autoplay_time' => self::_shortcode_default( 'autoplay_time' ),
				'background_thumbnails' => self::_shortcode_default( 'background_thumbnails' ),
				'exif' => self::_shortcode_default( 'exif' ),
				'exif_locations' => self::_shortcode_default( 'exif_locations' ),
				'exif_fields' => self::_shortcode_default( 'exif_fields' ),
				'alt_caption_fallback' => self::_shortcode_default( 'alt_caption_fallback' ),
				'fb_title_show' => self::_shortcode_default( 'fb_title_show' ),
				'fb_heading' => self::_shortcode_default( 'fb_heading' ),
				'fb_caption' => self::_shortcode_default( 'fb_caption' ),
				'fb_download_link' => self::_shortcode_default( 'fb_download_link' ),
				'fb_title_position' => self::_shortcode_default( 'fb_title_position' ),
				'fb_title_align' => self::_shortcode_default( 'fb_title_align' ),
				'fb_title_counter_show' => self::_shortcode_default( 'fb_title_counter_show' ),
				'fb_center_on_scroll' => self::_shortcode_default( 'fb_center_on_scroll' ),
				'fb_cyclic' => self::_shortcode_default( 'fb_cyclic' ),
				'fb_transition_in' => self::_shortcode_default( 'fb_transition_in' ),
				'fb_transition_out' => self::_shortcode_default( 'fb_transition_out' ),
				'fb_speed_in' => self::_shortcode_default( 'fb_speed_in' ),
				'fb_speed_out' => self::_shortcode_default( 'fb_speed_out' ),
				'fb_show_close_button' => self::_shortcode_default( 'fb_show_close_button' ),
				'fb_padding' => self::_shortcode_default( 'fb_padding' ),
				'fb_margin' => self::_shortcode_default( 'fb_margin' ),
				'fb_overlay_opacity' => self::_shortcode_default( 'fb_overlay_opacity' ),
				'fb_overlay_color' => self::_shortcode_default( 'fb_overlay_color' ),
				'fb_helper_thumbs' => self::_shortcode_default( 'fb_helper_thumbs' ),
				'fb_helper_thumbs_width' => self::_shortcode_default( 'fb_helper_thumbs_width' ),
				'fb_helper_thumbs_height' => self::_shortcode_default( 'fb_helper_thumbs_height' ),
				'facebook_enable' => self::_shortcode_default( 'facebook_enable' ),
				'facebook_action' => self::_shortcode_default( 'facebook_action' ),
				'facebook_colorscheme' => self::_shortcode_default( 'facebook_colorscheme' ),
				'facebook_kid_directed_site' => self::_shortcode_default( 'facebook_kid_directed_site' ),
				'facebook_layout' => self::_shortcode_default( 'facebook_layout' ),
				'facebook_share' => self::_shortcode_default( 'facebook_share' ),
				'pinterest_enable' => self::_shortcode_default( 'pinterest_enable' ),
				'pinterest_color' => self::_shortcode_default( 'pinterest_color' ),
				'html_format' => self::_shortcode_default( 'html_format' ),
				'theme' => self::_shortcode_default( 'theme' ),
				'page_size' => self::_shortcode_default( 'page_size' ),
				'builtin_pagination_style' => self::_shortcode_default( 'builtin_pagination_style' ),
				'masonry' => self::_shortcode_default( 'masonry' ),
				'responsive_columns' => self::_shortcode_default( 'responsive_columns' ),
			);			
			
			// filter provided attributes defined in $this->fields.
			if ( is_array( $atts ) ) :
				foreach ( $atts as $field => $value ) :
					$atts[$field] = self::_filter_shortcode_attribute( $field, $value );
				endforeach;
			endif;
							
			if ( ! empty( $atts['ids'] ) ) {
				// 'ids' is explicitly ordered, unless you specify otherwise.
				if ( empty( $atts['orderby'] ) )
					$atts['orderby'] = 'post__in';
				$atts['include'] = $atts['ids'];
			}
			
			$shortcode_atts = shortcode_atts( $defaults, $atts );
			extract( $shortcode_atts, EXTR_SKIP );
			
			if ( is_array( $page_size ) ) {
				$page_size = $page_size[0];
			}
			
			// check if shortcode set with a specific number of columns so responsive columns can be disabled
			if ( $columns === '' ) {
				$column_count_lock = false;
				$columns = self::_shortcode_default( 'columns' );
			}
			else {
				$column_count_lock = true;
			}
			
			
			// convert group_name from array to string
			if ( is_array( $group_name ) ) $group_name = sanitize_html_class( $group_name[0] );
					
			// an array of posts containing galleries which should be combined
			$gallery_ids = (array)$id;
	
			/*
			 * Make sure that the attachment ids are not being provided alongside gallery ids
			 * since this will cause the gallery to be output more than once.
			 */
			if ( ! empty( $ids ) ) : // attachment ids were specified and should be used (WordPress 3.5)
	
				$attachments = get_posts( array(
					'post_type' => 'attachment',
					'post_mime_type' => 'image',
					'numberposts' => -1,
					'post_status' => null,
					'post_parent' => ( ( empty( $include ) ) ? $id : '' ),
					'order' => $order,
					'orderby' => $orderby,
					'include' => $include,
					'exclude' => ( empty( $include ) ? $exclude : array() )
				) );
			
			else : // process the galleries as normal using post attachments
			
				foreach ( $gallery_ids as $id ) {
				
					//exclude ids of posts that have been set to specified statuses but expose the gallery items if the user is viewing their parent
					//allowing users with the proper permissions to see galleries on posts that would normally be hidden from view
					if ( ! in_array( get_post_status( $id ), $excluded_statuses ) or $post->ID === $id ) {
					
						$post_attachments = get_posts( array(
							'post_type' => 'attachment',
							'post_mime_type' => 'image',
							'numberposts' => -1,
							'post_status' => null,
							'post_parent' => ( ( empty( $include ) ) ? $id : '' ),
							'order' => $order,
							'orderby' => $orderby,
							'include' => $include,
							'exclude' => ( empty( $include ) ? $exclude : array() )
						) );
						$attachments = array_merge( $attachments, $post_attachments );
						
					}
					
				}
	
				if ( empty( $attachments ) ) return '';
				$attachments = $this->reorder_merged_attachments( $attachments, $orderby, $order );
				
			endif;
			
			$quantity = count( $attachments );
			
			$gallery = new rpsslideshow\Gallery();
			$gallery->setColumnCountLock( $column_count_lock );
			$gallery->setColumnCount( intval( $columns ) );
			$gallery->setImageSize( rpsslideshow\ImageSize::fromValue( $size ) );
			$gallery->setImageAlignment( rpsslideshow\Alignment::fromValue( $align ) );
			
			//process custom classes
			if ( isset( $classes ) and is_array( $classes ) and ! empty( $classes ) ) {
				$gallery->setClasses( $classes[0] );
			}
			
			foreach ( $attachments as $key => $attachment ) {
				$image = new rpsslideshow\Image();
								
				$heading_value = self::generate_heading_value( $attachment );
				$image->setId( $attachment->ID );
				$image->setHeading( $heading_value );
				
				$caption_value = self::generate_caption_value( 
					$attachment, 
					$caption_source, 
					$caption_auto_format 
				);
				$image->setCaption( $caption_value );
								
				$title_value = self::generate_title_value( 
					$attachment, 
					$caption_value, 
					$heading || $this->boolval( $fb_title_show ) and $this->boolval( $fb_heading ), 
					$caption || $this->boolval( $fb_title_show ) and $this->boolval( $fb_caption ),
					$exif, 
					$exif_locations, 
					$exif_fields, 
					$alt_caption_fallback 
				);
				$image->setTitle( $title_value );
								
				$small_image_src = wp_get_attachment_image_src( $attachment->ID, $size );
				$image->setSmallUrl( $small_image_src[0] );
				
				$large_image_src = wp_get_attachment_image_src( $attachment->ID, $size_large );
				$image->setLargeUrl( $large_image_src[0] );
				
				$image_sizes = $this->get_image_sizes();
                $image_meta  = wp_get_attachment_metadata( $attachment->ID );
                
                $image_width = intval( isset( $image_meta['sizes'][$size] ) ? $image_meta['sizes'][$size]['width'] : $image_meta['width'] );
                $image_height = intval( isset( $image_meta['sizes'][$size] ) ? $image_meta['sizes'][$size]['height'] : $image_meta['height'] );				
				
				if ( $constrain === 'plugin' ) {

					$image_max_width = $constrain_width;
					$image_max_height = $constrain_height;
					
				}
				elseif ( $constrain === 'media' ) {
					
					$image_max_width = isset( $image_sizes[$constrain_size] ) ? $image_sizes[$constrain_size]['width'] : $image_sizes['thumbnail']['width'];
					$image_max_height = isset( $image_sizes[$constrain_size] ) ? $image_sizes[$constrain_size]['height'] : $image_sizes['thumbnail']['height'];
					
				}
				
				$image->setOrientation( ( $image_height > $image_width ) ? 'portrait' : 'landscape' );
				
				if ( $constrain !== 'none' and ( $image_width > $image_max_width or $image_height > $image_max_height ) ) {
					
					$image_dimensions = wp_constrain_dimensions( $image_width, $image_height, $image_max_width, $image_max_height );
					$image->setSmallWidth( intval( $image_dimensions[0] ) );
					$image->setSmallHeight( intval( $image_dimensions[1] ) );
					
				}
				else {

					$image->setSmallWidth( intval( $image_width ) );
					$image->setSmallHeight( intval( $image_height ) );
				
				}
				
				$is_external_link = false;
				$href_value = self::generate_href_value( $attachment, $slideshow, $link, $large_image_src, $is_external_link );
				$image->setSource( $href_value );
				$image->setExternalLink( $is_external_link );
				
				$alt_text = self::generate_alt_value( $attachment );
				$image->setAlternativeText( $alt_text );
				
				$exif_html = self::generate_exif_html( 
					$attachment, 
					$exif, 
					$exif_locations, 
					$exif_fields 
				);
				$image->setExif( $exif_html );
				
				$link_target = get_post_meta( $attachment->ID, '_rps_attachment_post_gallery_link_target', true );
				$image->setTarget( $link_target );
				
				$gallery->addImage( $image );
			}
					
			/**
			 * Determine if fancybox should be loaded if the user wants a slideshow
			 * if so, store shortcode information for later use when outputting dynamic javascript
			 */
			if ( $slideshow ) {
				$slideshow_object = new rpsfancybox\Slideshow();
				
				$slideshow_object->setId( $group_name );
				$slideshow_object->setShowTitle( $this->boolval( $fb_title_show ) );
				$slideshow_object->setTransitionIn( rpsfancybox\SlideshowTransition::fromValue( $fb_transition_in ) );
				$slideshow_object->setTransitionOut( rpsfancybox\SlideshowTransition::fromValue( $fb_transition_out ) );
				$slideshow_object->setTitlePosition( rpsfancybox\SlideshowTitlePosition::fromValue( $fb_title_position ) );
				$slideshow_object->setTitleAlignment( rpsslideshow\Alignment::fromValue( $fb_title_align ) );
				$slideshow_object->setSpeedIn( intval( $fb_speed_in ) );
				$slideshow_object->setSpeedOut( intval( $fb_speed_out ) );
				$slideshow_object->setShowCloseButton( $this->boolval( $fb_show_close_button ) );
				$slideshow_object->setShowImageCountInTitle( $this->boolval( $fb_title_counter_show ) );
				$slideshow_object->setShowDownloadLink( $this->boolval( $fb_download_link ) );
				$slideshow_object->setCycle( $this->boolval( $fb_cyclic ) );
				$slideshow_object->setCenterOnScroll( $this->boolval( $fb_center_on_scroll ) );
				$slideshow_object->setPadding( intval( $fb_padding ) );
				$slideshow_object->setMargin( intval( $fb_margin ) );
				$slideshow_object->setOverlayColor( $fb_overlay_color );
				$slideshow_object->setOverlayOpacity( floatval( $fb_overlay_opacity ) );
				$slideshow_object->setAutoplay( $autoplay );
				$slideshow_object->setAutoplayTime( $autoplay_time );
				$slideshow_object->setShowHelperThumbs( $fb_helper_thumbs );
				$slideshow_object->setHelperThumbsWidth( $fb_helper_thumbs_width );
				$slideshow_object->setHelperThumbsHeight( $fb_helper_thumbs_height );
				
				$gallery->setSlideshow( $slideshow_object );
				
				$this->slideshows[] = $slideshow_object;
			}
			
			// create gallery view
			$gallery_view = new rpsslideshow\display\GalleryView( $gallery );
			$gallery_view->setFormat( rpsslideshow\display\HTMLFormat::fromValue( $html_format ) );
			$gallery_view->setTheme( rpsslideshow\display\Theme::fromValue( $theme ) );
			$gallery_view->setImagesAsBackgrounds( $this->boolval( $background_thumbnails ) );
			$gallery_view->setContainer( rpsslideshow\display\ContainerTag::fromValue( $container ) );
			$gallery_view->setShowHeading( $this->boolval( $heading ) );
			$gallery_view->setHeadingContainer( rpsslideshow\display\HeadingContainerTag::fromValue( $headingtag ) );
			$gallery_view->setHeadingAlignment( rpsslideshow\Alignment::fromValue( $heading_align ) );
			$gallery_view->setShowCaption( $this->boolval( $caption ) ); 
			$gallery_view->setCaptionAlignment( rpsslideshow\Alignment::fromValue( $caption_align ) );
			$gallery_view->setPageSize( $page_size );
			$gallery_view->setBuiltinPaginationStyle( $builtin_pagination_style );
			$gallery_view->setUsesMasonry( $masonry );
			$gallery_view->setUsesResponsiveStyles( $responsive_columns );
			
			// add facebook to the gallery view, if enabled
			if ( $facebook_enable ) {
				$facebook = new rpsslideshow\display\social\facebook\Facebook();
				
				$facebook->setAction( rpsslideshow\display\social\facebook\Action::fromValue( $facebook_action ) );				
				$facebook->setColorScheme( rpsslideshow\display\social\facebook\ColorScheme::fromValue( $facebook_colorscheme ) );
				$facebook->setKidDirectedSite( $this->boolval( $facebook_kid_directed_site ) );
				$facebook->setLayout( rpsslideshow\display\social\facebook\Layout::fromValue( $facebook_layout ) );
				$facebook->setSharingEnabled( $this->boolval( $facebook_share ) );
				
				$gallery_view->setFacebook( $facebook );
			}
			
			// add pinterest integration to the gallery view, if enabled
			if ( $pinterest_enable ) {
				$pinterest = new rpsslideshow\display\social\pinterest\Pinterest();
				$pinterest->setColorScheme( rpsslideshow\display\social\pinterest\ColorScheme::fromValue( $pinterest_color ) );
				
				$gallery_view->setPinterest( $pinterest );
			}
					
			return $gallery_view->display();
		}
		
		private function boolval( $value ) {
			return ( ( trim( strtolower( $value ) ) == 'true' ) || ( $value == 1 ) );
		}
			
		/**
		 * Generate the heading.
		 * @since 1.2.30
		 * @return string Heading string.
		 */
		private function generate_heading_value( $attachment ) {
			$heading_value = $attachment->post_title;
			return $heading_value;
		}
		
		/**
		 * Generate the caption.
		 * @since 1.2.30
		 * @return string Caption string.
		 */
		private function generate_caption_value( $attachment, $caption_source, $caption_auto_format ) {
			if( $caption_source === 'caption' ) :
				$caption_value = $attachment->post_excerpt;
			elseif( $caption_source === 'description' ) :
				$caption_value = $attachment->post_content;
			endif;
			
			if ( $caption_auto_format ) {
				$caption_value = wpautop( $caption_value );
			}
			
			return $caption_value;
		}
	
		/**
		 * Generate the Gallery EXIF.
		 * @since 1.2.30
		 * @return string Gallery EXIF string.
		 */
		private function generate_exif_html( $attachment, $display_exif, $exif_locations, $exif_fields ) {
			return ( $display_exif and ( 'gallery' === $exif_locations or 'both' === $exif_locations ) ) ? self::generate_gallery_exif( $attachment->ID, $exif_fields ) : '';
		}
		
		/**
		 * Generate the alt attribute.
		 * @since 1.2.30
		 * @return string Alt string.
		 */
		private function generate_alt_value( $attachment ) {
			$alt = get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true );
			if ( $alt == '' ) $alt = $attachment->post_title;
			
			return $alt;
		}
		
		/**
		 * Generate the title attribute.
		 * @since 1.2.30
		 * @return string Title string.
		 */
		private function generate_title_value( $attachment, $caption, $display_heading, $display_caption, $display_exif, $exif_locations, $exif_fields, $alt_caption_fallback ) {
			$title_value = '';
			$title_parts = array();
			$alt_value = self::generate_alt_value( $attachment );
			$heading_value = self::generate_heading_value( $attachment );
			$exif_value = '';
			
			if ( $display_heading ) {
				$title_parts['heading'] = $heading_value;
			}
			
			if ( $display_caption ) {
			
				if ( ! empty( $caption ) ) {
					$title_parts['caption'] = htmlspecialchars( $caption, ENT_QUOTES, get_bloginfo( 'charset' ) );
				}
				elseif ( ! empty ( $alt_value ) && $alt_caption_fallback && ( $alt_value != $heading_value ) ) {
					$title_parts['caption'] = $alt_value;
				}
				
			}
			
			if ( $display_exif ) {
				
				$exif_value = self::generate_slideshow_exif( $attachment->ID, $exif_fields );

				if ( ! empty( $exif_value ) and ( 'slideshow' === $exif_locations or 'both' === $exif_locations ) ) {
					$title_parts['exif'] = $exif_value;
				}

			}
			
			
			if ( ! empty( $title_parts ) ) {
				
				foreach ( $title_parts as $key => $title_part ) {
					
					$title_value .= ( ! empty( $title_part ) ) ? htmlentities( '<div class="fancybox-title-' . $key . '">' ) . $title_part . htmlentities( '</div>' ) : ''; 
				
				}
				
			}
					
			return $title_value;
		}
	
		/**
		 * Generate HREF value.
		 * @since 1.2.30
		 * @return string HREF value.
		 */
		private function generate_href_value( $attachment, $display_slideshow, $link, $large_image_src, &$is_external_link ) {
			
			// TODO: if the link is "none" shouldn't the href returned be null?
			
			$href_value = '';
			
			$gallery_link = self::get_gallery_link( $attachment->ID );
					
			if ( empty( $gallery_link ) ) {
				
				if ( $display_slideshow or $link === 'file' ) {
					
					$href_value = $large_image_src[0];
					
				}
				
				elseif ( $link === 'permalink' ) {
					
					$href_value = get_attachment_link( $attachment->ID );
									
				}
				
				elseif ( $link === 'parent_post' ) {
					
					$parent_id = $attachment->post_parent;
					$href_value = get_permalink( $parent_id );
					
				}
							
			}
			else {
				$is_external_link = true;
				$href_value = $gallery_link;
				
			}
			
			return $href_value;
		}
		
		/**
		 * Helper function to get the closest value within an array of values.
		 *
		 * @since 2.2.2
		 */
		private function closest_value( $search = null, $values = array() ) {
		
		   $closest_value = null;
		   
		   foreach ( $values as $value ) {
		   
		      if ( $closest_value === null or ( abs( $search - $closest_value ) > abs( $value - $search ) ) ) {
		      
		         $closest_value = $value;
		      
		      }
		   
		   }
		   
		   return $closest_value;
		
		}
		
		/**
		 * Helper function to convert decimals to fractions.
		 *
		 * @since 2.2.2
		 */
		private function shutter_speed( $decimal ) {
			
			$numerator = 1;
			$denominator = 1;
			$result = '';
			
			$common_shutter_speeds_one_third_stops = array( 1, 2, 4, 8, 15, 30, 40, 50, 60, 80, 100, 125, 160, 200, 250, 320, 400, 500, 640, 800, 1000, 2000, 4000, 8000 );
			
			if ( $decimal < 1 ) {
			
				$denominator = 1/$decimal;
				$denominator = self::closest_value( $denominator, $common_shutter_speeds_one_third_stops );
				$result = $numerator . '/' . $denominator;
				
			}
			else {
				
				$result = round( $decimal );
				
			}
			
			return $result;
		
		}
			
		/**
		 * Process the EXIF data.
		 * @since 1.2.22
		 * @return array Array of image meta data with empty values omitted and specific values converted in the specified or default order.
		 * @see http://codex.wordpress.org/Function_Reference/wp_read_image_metadata
		 * @see http://www.media.mit.edu/pia/Research/deepview/exif.html
		 * aperture, credit, camera, caption, created_timestamp, copyright, focal_length, iso, shutter_speed, title
		 * @todo use sprintf function for localizing strings
		 */
		private function get_exif_data( $attachment_id = '', $image_metadata_requested = array() ) {
			$output = array();
			$metadata = wp_get_attachment_metadata( $attachment_id );
			
			$image_metadata_available = $metadata['image_meta'];
			$image_metadata_selected = array();
							
			if( ! empty( $image_metadata_available ) ) :
			
					// Get the fields in the order that the user requested
					foreach ( $image_metadata_requested as $key ) :
						
						// Check to see if the field that the user requested is available
						if ( array_key_exists( $key, $image_metadata_available ) )
							$image_metadata_selected[$key] = $image_metadata_available[$key];
						
					endforeach;
					
					// Verify that there are some fields selected after processing
					if ( ! empty( $image_metadata_selected ) ) :
	
						foreach ( $image_metadata_selected as $meta_key => $meta_value ) :
		
							if ( ! empty( $meta_value ) ) :
							
								$meta_value = ( $meta_key == 'aperture' ) ? __( 'f/', 'rps-image-gallery' ) . $meta_value : $meta_value;
								$meta_value = ( $meta_key == 'created_timestamp' ) ? date_i18n( get_option( 'date_format' ), $meta_value ) : $meta_value;
								$meta_value = ( $meta_key == 'focal_length' ) ? round( $meta_value ) . __( 'mm', 'rps-image-gallery' ) : $meta_value;
								$meta_value = ( $meta_key == 'iso' ) ? __( 'ISO', 'rps-image-gallery' ) . $meta_value : $meta_value;
								$meta_value = ( $meta_key == 'shutter_speed' ) ? self::shutter_speed( round( $meta_value, 4 ) ) . __( 's', 'rps-image-gallery' ) : $meta_value;
									
								$output[$meta_key] = $meta_value;
																				
							endif;
		
						endforeach;
					
					endif;
					
			endif;
			
			return $output;
		}
		
		/**
		 * Generate the EXIF string that appears with the image caption in the gallery.
		 * @since 1.2.22
		 * @return string
		 */
		private function generate_gallery_exif( $attachment_id = '', $image_metadata_requested = array() ) {
			$output = '';
			$exif = self::get_exif_data( $attachment_id, $image_metadata_requested );
	
			if ( ! empty( $exif ) ) :
	
				$output .= '<ul class="gallery-meta">';
								
				foreach ( $exif as $meta_key => $meta_value ) :
		
						$output .= '<li class="meta-' . $meta_key . '">' . $meta_value . '</li>';
		
				endforeach;
								
				$output .= '</ul>';
				
			endif;
			
			return $output;
		}
		
		/**
		 * Generate the EXIF string that appears with the image caption in the slideshow.
		 * @since 1.2.22
		 * @return string
		 */
		private function generate_slideshow_exif( $attachment_id = '', $image_metadata_requested = array() ) {
			$output = '';
			$exif = self::get_exif_data( $attachment_id, $image_metadata_requested );
			
			if ( ! empty( $exif ) ) :
				
				$output .= implode( '&nbsp; ', $exif );
				
			endif;
			
			return $output;
		}
		
		/**
		 * @since 1.2.9
		 * @return array Resorted array of attachments as objects.
		 *
		 * Possible orderby values are 'menu_order', 'title', 'post_date' or 'random'.
		 * If 'random' is used then we just need to shuffle the array of attachments.
		 */
		private function reorder_merged_attachments( $attachments, $orderby, $order ) {
			$menu_order = $title = $post_date = array();
			if ( $orderby == 'rand' ) :
				shuffle( $attachments );
			else :
				foreach ( $attachments as $key => $row ) :
					$menu_order[$key] = $row->menu_order;
					$title[$key] = $row->post_title;
					$post_date[$key] = $row->post_modified_gmt;
				endforeach;
			
				switch ( $orderby ) {
					case 'menu_order' :
						$resort_orderby = $menu_order;
						break;
					case 'title' :
						$resort_orderby = $title;
						break;
					case 'post_date' :
						$resort_orderby = $post_date;
						break;
				}
				
				array_multisort( $resort_orderby, ( ( $order == 'asc' ) ? SORT_ASC : SORT_DESC ), $attachments );
			endif;
			
			return $attachments;
		}
		
		/*
		 * Output the necessary styles and scripts in the footer.
		 *
		 * @since 1.2
		 */
		public function _footer_styles_scripts () {
			
			if ( empty( $this->slideshows ) ) return;
			global $_rps_image_gallery;
			//wp_print_scripts( 'rps-image-gallery-fancybox' );
			
			?>
			<script type="text/javascript">
				;( function( jQuery, undefined ) {
				var $ = jQuery.noConflict();
				
				$(document).ready(function() {
					
					<?php
					
					$first = true;
					$fb_version = ( isset( $_rps_image_gallery['fb_version'] ) ) ? $_rps_image_gallery['fb_version'] : '1';
						
					foreach ( $this->slideshows as $slideshow ) {
								
						switch ( $fb_version ) {
							case '2':
								$slideshow_view = new rpsfancybox\display\Slideshow2View( $slideshow );
								break;
							default:
								$slideshow_view = new rpsfancybox\display\Slideshow1View( $slideshow );
								break;
						}
						
						echo $slideshow_view->display( $first );
						
						if ( $first ) {
							$first = false;
						}
					
					}
					
					?>
				});
				
				})(jQuery);
			</script>
			<?php
			
			$fancybox_elements_path = wp_make_link_relative( plugins_url( 'dependencies/fancybox/', __FILE__ ) );
			if ( isset( $_rps_image_gallery['fb_title_align'] ) and 'none' !== $_rps_image_gallery['fb_title_align'] ) :
				echo '<style type="text/css">#fancybox-title{text-align:' . $_rps_image_gallery['fb_title_align'] . ' !important;}</style>';		
			endif;
			
			?>
			<!--[if lt IE 7]>
			<style type="text/css">
				/* IE6 */
				
				.fancybox-ie6 #fancybox-close { background: transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $fancybox_elements_path; ?>fancy_close.png', sizingMethod='scale'); }
				
				.fancybox-ie6 #fancybox-left-ico { background: transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $fancybox_elements_path; ?>fancy_nav_left.png', sizingMethod='scale'); }
				.fancybox-ie6 #fancybox-right-ico { background: transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $fancybox_elements_path; ?>fancy_nav_right.png', sizingMethod='scale'); }
				
				.fancybox-ie6 #fancybox-title-over { background: transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $fancybox_elements_path; ?>fancy_title_over.png', sizingMethod='scale'); zoom: 1; }
				.fancybox-ie6 #fancybox-title-float-left { background: transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $fancybox_elements_path; ?>fancy_title_left.png', sizingMethod='scale'); }
				.fancybox-ie6 #fancybox-title-float-main { background: transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $fancybox_elements_path; ?>fancy_title_main.png', sizingMethod='scale'); }
				.fancybox-ie6 #fancybox-title-float-right { background: transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $fancybox_elements_path; ?>fancy_title_right.png', sizingMethod='scale'); }
				
				.fancybox-ie6 #fancybox-bg-w, .fancybox-ie6 #fancybox-bg-e, .fancybox-ie6 #fancybox-left, .fancybox-ie6 #fancybox-right, #fancybox-hide-sel-frame {
					height: expression(this.parentNode.clientHeight + "px");
				}
				
				#fancybox-loading.fancybox-ie6 {
					position: absolute; margin-top: 0;
					top: expression( (-20 + (document.documentElement.clientHeight ? document.documentElement.clientHeight/2 : document.body.clientHeight/2 ) + ( ignoreMe = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop )) + 'px');
				}
				
				#fancybox-loading.fancybox-ie6 div	{ background: transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $fancybox_elements_path; ?>fancy_loading.png', sizingMethod='scale'); }
			</style>
			<![endif]-->
			<!--[if lte IE 8]>
			<style type="text/css">
				/* IE6, IE7, IE8 */
				
				.fancybox-ie .fancybox-bg { background: transparent !important; }
				
				.fancybox-ie #fancybox-bg-n { filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $fancybox_elements_path; ?>fancy_shadow_n.png', sizingMethod='scale'); }
				.fancybox-ie #fancybox-bg-ne { filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $fancybox_elements_path; ?>fancy_shadow_ne.png', sizingMethod='scale'); }
				.fancybox-ie #fancybox-bg-e { filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $fancybox_elements_path; ?>fancy_shadow_e.png', sizingMethod='scale'); }
				.fancybox-ie #fancybox-bg-se { filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $fancybox_elements_path; ?>fancy_shadow_se.png', sizingMethod='scale'); }
				.fancybox-ie #fancybox-bg-s { filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $fancybox_elements_path; ?>fancy_shadow_s.png', sizingMethod='scale'); }
				.fancybox-ie #fancybox-bg-sw { filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $fancybox_elements_path; ?>fancy_shadow_sw.png', sizingMethod='scale'); }
				.fancybox-ie #fancybox-bg-w { filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $fancybox_elements_path; ?>fancy_shadow_w.png', sizingMethod='scale'); }
				.fancybox-ie #fancybox-bg-nw { filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $fancybox_elements_path; ?>fancy_shadow_nw.png', sizingMethod='scale'); }
			</style>
			<![endif]-->
		<?php }
				
		private $slideshows = array();
		
	}

RPS_Image_Gallery::invoke();
	
	/**
	 * Callback that generates the 'size' and 'size_large' select menus.
	 * Compiles all registered image slugs and dimensions.
	 *
	 * @return string Select menu and optional description div.
	 * @since 1.2.24
	 */
	function _rps_image_gallery_custom_field_image_sizes( $field, $value ) {
		global $_wp_additional_image_sizes;
		$image_size_slugs = get_intermediate_image_sizes(); // array of image size slugs
		$image_sizes = array();
		
		foreach ( $image_size_slugs as $image_size ) {
			$width = '';
			$height = '';
			
			if ( is_array( $_wp_additional_image_sizes ) and array_key_exists( $image_size, $_wp_additional_image_sizes ) ) {

				$width = $_wp_additional_image_sizes[$image_size]['width'];
				$height = $_wp_additional_image_sizes[$image_size]['height'];

			}
			else {

				$width = get_option( $image_size . '_size_w' );
				$height = get_option( $image_size . '_size_h' );

			}
			
			if ( (int)$width !== 0 ) {

				if ( $height == 0 ) {
					$height = 'auto';
				}
				
				$image_sizes[$image_size] = ucwords( ucwords( str_replace( array( '-', '_' ), ' ', $image_size ) ) ) . ' ( ' . $width . ' &times; ' . $height . ' )';
				
			}
		
		}

		$image_sizes['full'] = __( 'Original File', 'rps-image-gallery' );
		
		echo '<select  id="' . $field['id'] . '-select" data-placeholder="' . _x( 'Select an item', 'select menu data placeholder text for image size fields', 'rps-image-gallery' ) . '" name="_rps_image_gallery['. $field['id'] .']" class="redux-select-item " style="width: 40%;" rows="6">';
		echo '<option></option>';
		
		foreach ( $image_sizes as $option_value => $option_text ) :
		
			$selected = ( $option_value == $value ) ? ' selected="selected"' : '';
			echo '<option value="' . $option_value . '"' . $selected . '>' . $option_text . '</option>';
		
		endforeach;
		
		echo '</select>';
		echo ( ! empty( $field['desc'] ) ) ? '<div class="description field-desc">' . $field['desc'] . '</div>' : '';
	}
	
}

?>
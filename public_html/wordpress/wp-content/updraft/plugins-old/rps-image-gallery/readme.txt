=== RPS Image Gallery ===
Contributors: redpixelstudios
Donate link: http://redpixel.com/donate
Tags: album, albums, autoplay, best gallery plugin, fancybox, fancybox2, masonry, free photo gallery, free image gallery, galleries, gallery, gallery link, gallery link url, image, image album, image captions, image galleries, image gallery, image grid, images, media, media gallery, multisite galleries, multisite gallery, multisite image galleries, multisite image gallery, multisite photo galleries, multisite photo gallery, photo, photo albums, photo captions, photo galleries, photo gallery, photo grid, photographer, photography, photos, picture, picture gallery, pictures, red pixel, red pixel studios, redpixelstudios, responsive, responsive galleries, responsive gallery, responsive image galleries, responsive image gallery, responsive photo galleries, responsive photo gallery, rps, slideshow, slideshow galleries, slideshow gallery, slideshows, thumbnail galleries, thumbnail gallery, thumbnails, wordpress gallery, wordpress gallery plugin, wordpress image gallery plugin, wordpress photo gallery plugin, wordpress responsive gallery, wp gallery, wp gallery plugin
Requires at least: 3.6
Tested up to: 4.6
Stable tag: 2.2.2
License: GPL3

RPS Image Gallery takes over where the WordPress gallery leaves off by adding responsive galleries, slideshow and advanced linking capabilities.

== Description ==

RPS Image Gallery enhances the core WordPress gallery function by dynamically adjusting the column count based on viewport width and enabling an image in the gallery to either invoke a slideshow or link to another page, post or any URL. The link and link target are specified in the image's Edit Media screen using the Gallery Link URL and Gallery Link Target fields. When an image having a Gallery Link URL is clicked, the user will be directed to that location. Images linking elsewhere are automatically excluded from the slideshow – clicking the image will not invoke the slideshow but will call the specified Gallery Link URL.

In addition, RPS Image Gallery combines attachments from multiple posts or pages into a single gallery, enables the use of paging - useful for galleries with a large number of images, and provides the ability to link a gallery image to the post to which it is attached.

= Features =

* **Uses Masonry script for galleries displaying images with various aspect ratios.**
* **Ability to use installed theme gallery styles while leveraging slideshow capabilities.**
* **Option to dynamically adjust the number of gallery columns based on viewport width.**
* **Uses fancyBox or fancyBox2 for slideshow display.**
* **Autoplays fancyBox or fancyBox2 slideshows.**
* **Uses thumbnail helpers for fancyBox2 slideshows.**
* **Uses paging for galleries and ability to specify the number of images per page.**
* **Uses HTML5 or legacy output for the gallery.**
* **Supports HTML in the slideshow caption.**
* **Add Facebook and Pinterest buttons to the gallery view.**
* **Compatibility settings allowing bundled scripts and styles to be disabled, and unique 'rps-gallery' shortcode to be used.**
* Clicking gallery images will invoke a slideshow or link to a specified URL, file or **parent post**.
* Set the target for the image link.
* Supports gallery shortcode defaults which can be overridden for each shortcode instance.
* Combines and sorts attachments from multiple posts into a single gallery.
* Supports multiple galleries appearing on a single page.
* Displays a “download” link/button on the slideshow. (fancyBox2)
* Displays the image title and caption/description in the gallery view.
* Leverages the standard gallery editor interface to specify custom sort order.
* **Displays EXIF data in the gallery and/or slideshow.**
* Displays gallery thumbnails as background images (legacy format only) or standard images.
* Hides attachments of trashed or future posts in galleries combined using post ids.
* Overrides the default [WordPress Gallery](http://codex.wordpress.org/Gallery_Shortcode "Gallery Shortcode") shortcode or uses the one provided with the plugin.
* Offers compatibility settings to ensure excellent coexistence with a wide variety of themes and plugins.
* Loads required scripts only when the shortcode is invoked.

*New features in bold.*

== Installation ==

1. Upload the <code>rps-image-gallery</code> directory and its containing files to the <code>/wp-content/plugins/</code> directory.
1. Activate the plugin through the "Plugins" menu in WordPress.

== Frequently Asked Questions ==
= Where is the Settings page? =

You must have the [Redux Framework](http://wordpress.org/plugins/redux-framework/) plugin installed in order to edit the default options. Once activated you will see an Image Gallery link show under the WordPress admin Settings tab.

= Can I override default settings per gallery? =

Yes. Any shortcode attribute will override the gallery default settings.

= Where can I find a comprehensive list of shortcode attributes? =

Have a look at the "Other Notes" tab.

= How do I add a gallery? =

You can refer to the [gallery instructions](http://en.support.wordpress.com/images/gallery/#adding-a-gallery "Adding a Gallery") posted at WordPress.com support.

= What happens if I deactivate the plugin after having setup galleries with it active? =

Nothing bad. The default [WordPress Gallery](http://codex.wordpress.org/Gallery_Shortcode "Gallery Shortcode") behavior will take over and any shortcode attributes that are specific to RPS Image Gallery are ignored. However, if you have chosen to use the alternate shortcode [[rps-gallery]] instead, those instances of the shortcode will not process and will simply be displayed within the body of the post.

= How do I use the responsive gallery columns feature? =

You will need to make sure the Redux Framework is installed and active. Then go to the RPS Image Gallery settings page located in the WordPress admin under Settings > RPS Image Gallery and select the Gallery tab. Locate the switch labeled "Responsive Columns" and set it to "On". You will notice additional settings appear below the Columns slider, one for each column count. Adjust the sliders for each to specify the minimum viewport width in pixels at which that number of gallery columns is supported. The viewport widths do not need to be in ascending order so feel free to assign any combination that works bet with your theme.

Please note: Any shortcode featuring the "columns" attribute will not be affected by the responsive gallery columns settings.

= Is the responsive gallery columns feature multisite compatible? =

Yes. As of version 2.1.1, unique stylesheets are generated for each site using RPS Image Gallery responsive gallery columns in the multisite network.

= How do I define the sizes of the images in the gallery and the slideshow? =

You may use any of the standard image sizes including "thumbnail", "medium", "large", "full" and any other additional image size registered by the active theme.

<code>
[gallery size="thumbnail" size_large="large"]
</code>

= Where do I set the link and target for each image? =

The fields "Gallery Link URL" and "Gallery Link Target" on the Edit Media screen allow you to specify the settings for each image (see screenshots).

= What attributes of the WordPress Gallery shortcode have been modified? =

* link - By default the only two options are "file" and "permalink". We have added an option of "none" in order to prevent gallery thumbnail images from linking anywhere if slideshow is also set to "false" (since version 1.2.2). An example of this approach is:

<code>
[gallery link="none" slideshow="false"]
</code>

* id - By default you can use the id to display a gallery that exists on another post/page. We have added the option to pass along a comma delimited list of ids so that a single gallery can be created from multiple galleries. The 'orderby' and 'order' arguments are applied after the attachments are combined. The following example will combine the image attachments from post 321 and 455 into a single gallery sorted alphabetically by title:

<code>
[gallery id="321,455" orderby="title" order="asc"]
</code>

**Notice for WordPress 3.5+ Users:** When the "ids" attribute and "id" attribute are present in the same shortcode, the "ids" attribute will be used to determine which images should be included and what order they will be in.

= What will display if I set caption_source to 'caption' or 'description' but some of my images don't have either? =

The plugin will fall back to the image title if a caption or description is not defined for the image.

= Can I use the caption or the description? =

Yes. You will need to select which one you want to use, but the approach is simple:

<code>
[gallery caption="true" caption_source="caption"]
[gallery caption="true" caption_source="description"]
</code>

= How do I preserve line breaks in the caption/description? =

The 'caption_auto_format' attribute will automatically add paragraph tags where double line breaks are found and break tags for every single line break.

<code>
[gallery caption_auto_format="true"]
</code>

= How do I add multiple galleries to the same page? =

Though the WordPress Gallery editor only allows you to manage a single gallery, you can combine galleries from multiple post/pages onto a single page. To do this, create a post/page for each gallery that you want to include. Record the post IDs for the gallery pages, then add a gallery shortcode for each of them on the post/page that will contain them. For example:

<code>
[gallery id="134" group_name="group1"]
[gallery id="159" group_name="group2"]
</code>

This code will pull the gallery from post 134 and 159 and display them one after the other. The group name attribute allows for each gallery to display in a separate slideshow. Excluding the group name or making it the same will cause the slideshow to be contiguous between the galleries.

Alternatively, you can create multiple galleries from the attached images on a post/page. To do so, get a list of the image (attachment) IDs that you want for each gallery, then pass them to the gallery shortcode in the "include" attribute like so:

<code>
[gallery include="10,11,24,87"]
[gallery include="7,16,23,45"]
</code>

Keep in mind that all of the included images must be attached to the post/page to be successfully added to the gallery.

= How do I combine multiple galleries? =

Since version 2.0.9, all you need to do to combine multiple galleries is pass along a comma delimited list of ids like so:

<code>
[gallery id="134,159" orderby="title"]
</code>

This code will take all of the images from the two galleries, merge and order them by the image title.

= What versions of fancyBox are being used? =

fancyBox version 1.3.4 and 2.1.5 are included with this plugin. However, the use of fancyBox2 is contingent upon your site meeting [license requirements](http://fancyapps.com/fancyBox/#license).

= How do I display EXIF data in the gallery and/or slideshow? =

You can make the EXIF data show by adding the exif_locations argument to the shortcode like so.

<code>
[gallery exif="true" exif_locations="slideshow"]
</code>

= How do I control which EXIF fields display? =

The EXIF fields that can be displayed are "camera", "aperture", "focal_length", "iso", "shutter_speed", "title", "caption", "credit", "copyright" and "created_timestamp". The order you enter the fields is reflected in the output.

<code>
[gallery exif="true" exif_locations="slideshow" exif_fields="aperture,focal_length,iso,shutter_speed"]
</code>

== Other Notes ==
What follows is a comprehensive list of attributes for the gallery shortcode when RPS Image Gallery is active.

= id =
The post IDs containing a gallery to include.

* '' - single post ID or comma separated list of post IDs (default)

= ids =
The image IDs to display in the gallery.

* '' - single image ID or comma separated list of image IDs (default)

= container =
The container for the gallery.

* 'div' (default)
* 'span'

= columns =
How many columns to use for the gallery view.

* '3' - range is 1 to 9 (default)

= responsive_columns =
Determines whether the number of columns should respond to the viewport width.

* 'true'
* 'false' (default)

= page_size =
Determines how many images show at a time. Includes paging navigation. Not compatible with Masonry.

* '0' (default)

= align =
Affects the heading(title), caption and the last row of images when there are fewer images in the row than number of columns.

* 'left' (default)
* 'center'
* 'right'

= size =
The size of the image that should be displayed in the gallery view. It can be any of the standard image sizes including any registered by the theme.

* 'thumbnail' (default)
* 'medium'
* 'large'
* 'full'

= constrain =
Specify if the image dimensions should be constrained by width only or both width and height. Only available with specific themes or when the theme is set to "none".

* 'none' (default)
* 'media'
* 'plugin'

= constrain_size =
Only available if the constrain value is set to 'media'. Allows the use of the Thumbnail, Medium or Large image dimensions as specified in Media Settings.

* 'thumbnail' (default)
* 'medium'
* 'large'

= constrain_width =
Only available if the constrain value is set to 'plugin'. Allows the constrain width to be specified by the plugin.

* '150' (default)

= constrain_height =
Only available if the constrain value is set to 'plugin'. Allows the constrain height to be specified by the plugin.

* '150' (default)

= size_large =
The size of the image that should be displayed in the slideshow view. It can be any of the standard image sizes including any registered by the theme.

* 'thumbnail'
* 'medium'
* 'large' (default)
* 'full'
* 'custom-size' (registered image sizes)

= orderby =
How to sort the images. It is ignored if a list of image IDs is included in the shortcode.

* 'menu_order' (default)
* 'title'
* 'post_date'
* 'rand'
* 'ID'
* 'post__in'

= order =
How to order the images. It is ignored if a list of image IDs is included in the shortcode.

* 'ASC' (default)
* 'DESC'

= heading =
Display the image title in the gallery and slideshow views.

* 'true'
* 'false' (default)

= headingtag =
The tag that should be used to wrap the image heading (title).

* 'h1'
* 'h2' (default)
* 'h3'
* 'h4'
* 'h5'
* 'h6'

= heading_align =
Specify alignment of the heading text presented in the gallery grid.

= caption =
Display the image caption or description under the images in the gallery grid view.

* 'true'
* 'false' (default)

= caption_auto_format =
Automatically insert break and paragraph tags into caption.

* 'true'
* 'false' (default)

= caption_source =
Define where the text presented as the caption should be sourced.

* 'caption' (default)
* 'description'

= caption_align =
Specify alignment of the caption text presented in the gallery grid.

* 'left' (default)
* 'center'
* 'right'

= link =
Where to get the URL to direct a user when clicking/tapping an image. Only has an effect if Slideshow is set to false and the Gallery Link URL is empty.

* 'permalink' (default)
* 'file'
* 'parent_post'
* 'none'

= html_format =
Which HTML structure to use to output the gallery.

* 'default' (default)
* 'html5'

= theme =
Which theme to use to style the HTML output.

* 'default' (default)
* 'none'

= masonry =
Whether to use the Masonry script cascading grid layout library for the gallery.

* true
* false (default)

= slideshow =
Invoke the slideshow (fancyBox) viewer when an image without a Gallery Link URL value is clicked.

* 'true' (default)
* 'false'

= fb_version =
Which version of fancyBox to use.

* '1' (default)
* '2'

= autoplay =
Specify whether the slideshow should automatically cycle through the images or not.

* 'true' (default)
* 'false'

= background_thumbnails =
Display the gallery thumbnail images as backgrounds or standard images.

* 'true'
* 'false' (default)

= exif = (since 1.2.24)
Show the EXIF image data.

* 'true'
* 'false' (default)

= exif_locations =
Where to show the EXIF data associated with the image.

* 'gallery'
* 'slideshow' (default)
* 'both'

= exif_fields =
What EXIF fields to display and in what order.

* 'camera,aperture,focal_length,iso,shutter_speed,title,caption,credit,copyright,created_timestamp' (default)

= include =
Comma separated attachment IDs to display. Cannot be used with exclude.

* '' (default)

= exclude =
Comma separated attachment IDs to display. Cannot be used with include.

* '' (default)

= group_name =
The class of the gallery group which determines what images belong to the gallery slideshow.

* 'rps-image-group' (default)

= alt_caption_fallback =
Use the ALT value as a fallback in case the Caption field is empty.

* ‘true’ (default)
* ‘false’

= fb_title_show =
Show the title area in the slideshow view including the image heading, caption or description, and EXIF data.

* 'true' (default)
* 'false'

= fb_heading =
Show the image heading within the title area of the slideshow.

* 'true' (default)
* 'false'

= fb_caption =
Show the image caption within the title area of the slideshow.

* 'true' (default)
* 'false'

= fb_title_position =
The position of the title area in relation to the image in the slideshow.

* 'over' (default)
* 'outside'
* 'inside'

= fb_title_align =
The alignment of the text in the slideshow title.

* 'none' (default)
* 'left'
* 'center'
* 'right'

= fb_show_close_button =
Show the close button in the upper-right corner of the slideshow (clicking outside the slideshow always closes it).

* 'true' (default)
* 'false'

= fb_transition_in =
The effect that should be used when the slideshow is opened.

* 'none' (default)
* 'elastic'
* 'fade'

= fb_transition_out =
The effect that should be used when the slideshow is closed.

* 'none' (default)
* 'elastic'
* 'fade'

= fb_speed_in =
Time in milliseconds of the fade and transition when the slideshow is opened.

* '300' - minimum of 100 and maximum of 1000 (default)

= fb_speed_out =
Time in milliseconds of the fade and transition when the slideshow is closed.

* '300' - minimum of 100 and maximum of 1000 (default)

= fb_title_counter_show =
Display the image counter in the slideshow (i.e. "Image 1/10).

* 'true' (default)
* 'false'

= fb_cyclic =
Make the slideshow start from the beginning once the end is reached.

* 'true' (default)
* 'false'

= fb_center_on_scroll =
Center the image on the screen while scrolling the page.

* 'true' (default)
* 'false'

= fb_padding =
Space between FancyBox wrapper and content.

* '10' - minimum of 0px and maximum of 100px (default)

= fb_margin =
Space between viewport and FancyBox wrapper.

* '20' - minimum of 0px and maximum of 100px (default)

= fb_overlay_opacity =
Opacity of the overlay appearing behind the slideshow and on top of the page.

* '0.3' - minimum of 0 and maximum of 1 (default)

= fb_overlay_color =
Color of the overlay appearing behind the slideshow and on top of the page.

* '#666' (default)

= fb_helper_thumbs =
Display helper thumbnails appearing below the slideshow in fancyBox2.

* 'true'
* 'false' (default)

= fb_helper_thumbs_width =
The width of the helper thumbnails appearing below the slideshow in fancyBox2.

* 50 (default)

= fb_helper_thumbs_height =
The height of the helper thumbnails appearing below the slideshow in fancyBox2.

* 50 (default)

== Screenshots ==

1. The gallery output features an option to include Facebook and Pinterest buttons along with gallery paging navigation.
1. Clicking a gallery image opens the slideshow(fancyBox) viewer or directs the site visitor to a page specified in the Gallery Link field.
1. Defaults can be set for image galleries sitewide from one convenient interface.
1. "Gallery Link URL" and "Gallery Link Target" appear on the Edit Media screen for images so that an admin can force the image to link to a post on their site or another site.

== Changelog ==
= 2.2.2 =
* Added override for gallery columns default.
* Fix so if column count is specified in shortcode then responsive styles will not affect that gallery.
* Added "classes" argument to shortcode so custom classes may be added to the gallery container.
* Changed default word-break CSS attribute for heading styles to hyphenate.
* Focal length value of EXIF data rounded to integer.
* Shutter speed value of EXIF data converted to fraction using common shutter speed 1/3 steps.

= 2.2.1 =
* Use Masonry cascading grid layout library for galleries.
* Thumbnail helpers option for fancyBox2 slideshows.
* Customize the width and height of thumbnail helpers.
* Added Masonry 4.0.0 and Images Loaded 4.1.0 JavaScripts to dependencies.
* Added masonry-controller.js to dependencies.
* Revised social media button styles to be compatible with Masonry gallery layouts.
* Updated gallery HTML5 format to more closely match native WordPress output.
* Added gallery support of responsive images by including srcset and sizes attributes.
* Added width and height attributes to gallery images.
* Added constrain argument, along with constrain_width and constrain_height, to force gallery images to use maximum dimensions (theme dependent).
* Disable pointer events for fancyBox title so overlap does not interfere with navigation.
* Updated settings dependencies for gallery to prevent incompatible selections.
* Add fallback background color to default theme pagination styles for browsers not supporting RGBA.

= 2.1.6 =
* Fixed issue so only required slideshow styles load when gallery theme is set to none.
* Added legacy output formats for older themes that support the definition list styles.
* Updated stylesheets to allow for easier overrides.

= 2.1.5 =
* Added option to output responsive gallery column styles inline.
* Improvements in triggering responsive gallery column style file generation.

= 2.1.4 =
* Replaced anonymous function calls for compatibility with outdated or non-compliant server environments.
* Updated base stylesheet to reset certain attributes set by some themes.

= 2.1.3 =
* Force base stylesheet to load if options are not set.
* Updated version reported for fancyBox 1.3.4 stylesheet.
* Updated stylesheet dependencies.
* Quiet notices when options are not set.

= 2.1.2 =
* Update to responsive gallery column styles to provide override of max-width limitation for some themes.
* Modify responsive gallery column stylesheet creation process for multisite to help avoid permissions issues.

= 2.1.1 =
* Added support for multisite configurations where each site can have unique responsive gallery column styles.
* Bug fix so Gallery Link URL works properly with the original fancyBox.

= 2.1.0 =
* Introduced responsive gallery columns to dynamically change the column count based on viewport width.
* Added setting to display the image heading or the caption in the gallery and/or the slideshow.
* Added dependencies to slideshow options settings.
* Modified default RPS Image Gallery theme CSS so font size does not cause title and caption to disappear in some themes.
* Shifted common styles to main CSS file away from theme-specific CSS.
* Fixed bug in options init that was generating a notice.
* Added word break to image heading in default CSS to prevent overflow in gallery view with narrow columns.

= 2.0.1 =
* Fix to use default theme styles if theme is not set in options.

= 2.0.0 =
* Support for fancyBox2.
* Added autoplay slideshow option for fancyBox and fancyBox2.
* Integrated gallery paging.
* Added option to link a gallery image to its attached post.
* Modified helper function to check if Redux Framework network active on network of sites in addition to single site installs.
* Updated built-in styles to force fancyBox wrapper to use content-box sizing to avoid alignment issues with the slideshow elements.
* Fix to allow galleries within the same post to be separated into groups by the group_name attribute.
* Added fancyBox attributes for additional control of slideshow presentation including overlay opacity, overlay color, margin and padding.
* Added compatibility controls to allow for conditional loading of scripts, styles and override of the built-in gallery shortcode.
* Added option to display EXIF data in both the gallery and the slideshow.
* Added option to display Facebook "like" and Pinterest "pin" buttons on gallery thumbnails.

= 1.2.29 =
* Added 'caption_auto_format' attribute to set whether break and paragraph tags are added to the caption/description.

= 1.2.28 =
* Exclude attachments of posts with a status of ‘trash’ or ‘future’.

= 1.2.27 =
* Removed menu appearing in admin bar when viewing pages.

= 1.2.26 =
* Added 'full' as image size option in settings.
* Updated documentation.

= 1.2.25 =
* Maintenance release.

= 1.2.24 =
* Added settings page to specify gallery defaults.
* Removed 'p' as option for container since it conflicts with the_content filter.
* Added 'caption_source' attribute to set where caption is sourced.
* Added 'caption_align' attribute to set text alignment of the caption in the gallery.
* Added 'fb_title_align' attribute to set text alignment of the caption in the slideshow.

= 1.2.23 =
* Added option to use caption or the description.
* Fixed path issue for fancyBox elements called by IE6 through IE8.

= 1.2.22 =
* Added option to display EXIF image data.
* Added option to display gallery thumbnails as backgrounds instead of images.

= 1.2.21 =
* Maintenance release to correct jQuery noConflict mode setting.

= 1.2.20 =
* Uses jQuery version included with WordPress.

= 1.2.19 =
* Includes the necessary version of jQuery for compatibility with higher WordPress versions. Packaged in a no-conflict wrapper to avoid collisions with other jQuery versions.

= 1.2.18 =
* Added option to show fb counter without title.
* Applies proper class on fancyBox title based on title position.

= 1.2.17 =
* Fixed a bug that caused exclude to not work properly in certain situations.

= 1.2.16 =
* Fixed a bug introduced by v1.2.15 that messed with the post interface.

= 1.2.15 =
* Fixed a minor bug where the include attribute no longer functioned properly.

= 1.2.14 =
* Unique post gallery images no longer merge into a single slideshow on archive pages.

= 1.2.13 =
* Added pass through arguments for cyclic and centerOnScroll fancyBox options.

= 1.2.12 =
* Maintenance release to eliminate warning message being logged when sorting single gallery.

= 1.2.11 =
* Added support for ids attribute in gallery shortcode.
* Reordering merged gallieries is now possible via the default Gallery admin interface.

= 1.2.10 =
* Made column width definitions in CSS more precise for layouts with tight tolerances.

= 1.2.9 =
* Added option to combine attachments from multiple pages into a single gallery while respecting orderby and order arguments.

= 1.2.8 =
* Added classes to indicate beginning and end of gallery rows.
* Added shortcode option to specify gallery alignment.
* Removed definition list styles that were no longer needed.

= 1.2.7 =
* Added title attribute for image in grid view when no link is present.
* Added option to turn image heading (title) on or off in gallery and slideshow views.
* Added option to specify the heading tag from h2-h6.
* Added option to turn the slideshow image counter on or off.
* Removed support for definition list (dl) structure and removed shortcode arguments including itemtag, icontag and captiontag.

= 1.2.6 =
* Added target parameter to gallery link.
* Modified CSS to eliminate extra horizontal space between images in gallery grid due to inline-block styling of list items.

= 1.2.5 =
* Eliminated possibility of HTML markup appearing in title attribute.

= 1.2.4 =
* Added support for HTML markup in the image caption.

= 1.2.3 =
* Modified z-index of fancyBox overlay and wrap so that they appear above most theme elements.

= 1.2.2 =
* Corrected an issue with the fancyBox CSS that resulted in 404 errors for some supporting graphical elements.
* Added a shortcode attribute option for "link" so that it can now be set to "none".

= 1.2 =
* Added capability to pass fancyBox settings through shortcode attributes.
* Changed the default slideshow behavior to be cyclic (loop).
* Corrected an issue preventing slideshow for multiple galleries.

= 1.1.1 =
* First official release version.

== Upgrade Notice ==
= 2.2.2 =
* Responsive style override per gallery, heading style adjustments and EXIF data tweaks.

= 2.2.1 =
* Added thumbnail helpers for fancyBox2 slideshows and Masonry cascading grid layout and responsive image support for galleries.

= 2.1.6 =
* Fix for gallery theme settings to allow WordPress theme gallery styles to be used.

= 2.1.5 =
* Better compatibility for responsive gallery column styles.

= 2.1.4 =
* Server environment and theme compatibility modifications.

= 2.1.3 =
* Stylesheet loading fix so base styles used even if plugin options are not configured.

= 2.1.2 =
* Responsive gallery columns theme and multisite compatibility updates.

= 2.1.1 =
* Multisite compatibility and bug fix so Gallery Link URL works properly with the original fancyBox.

= 2.1.0 =
* Addition of responsive gallery columns along with better control over gallery and slideshow titles and captions.

= 2.0.1 =
* Adjustment to default styles for better compatibility.

= 2.0.0 =
* Multisite fix, bug fixes, additional fancyBox features and compatibility options added.

= 1.2.29 =
* Adds option to automatically format captions.

= 1.2.28 =
* Excludes attachments based on post status.

= 1.2.27 =
* Removes menu from admin bar.

= 1.2.26 =
* Added option to use original size images.

= 1.2.25 =
* Fixed setting to control showing and hiding of EXIF data.
* Public release of settings page for users to specify gallery defaults.

= 1.2.24 =
* Added settings page for users to specify gallery defaults.

= 1.2.23 =
* Option to use image caption or description. Fixed 404 errors when browsing with IE6 through IE8.

= 1.2.22 =
* Optionally display EXIF data in gallery and slideshow. Force gallery thumbnails to display as background images for better styling flexibility.

= 1.2.21 =
* Fixes issue that affects certain themes which load javacript in footer. 

= 1.2.20 =
* Now uses jQuery included with WordPress. Relies on jQuery Migrate that also ships with WordPress 3.6.

= 1.2.19 =
* Fixes a jQuery compatibility issue with WordPress 3.6.

= 1.2.18 =
* Better title and counter handling in slideshow.

= 1.2.17 =
* Fixed a bug that caused exclude to not work properly in certain situations.

= 1.2.16 =
* This is an important update that fixes a bug caused by v1.2.15 which interferes with the post interface.

= 1.2.15 =
* This update fixes a minor bug with the include attribute.

= 1.2.14 =
* Improved operation of slideshow when multiple galleries appear on archive pages.

= 1.2.13 =
* Expanded passthrough options for fancyBox.

= 1.2.12 =
* Fixed issue that would generate a warning when sorting gallery.

= 1.2.11 =
* Compatibility with WordPress 3.5 ordering and image inclusion standards.

= 1.2.10 =
* Updated default widths for columns.

= 1.2.9 =
* Added option to combine attachments from multiple posts in one gallery.

= 1.2.8 =
* Set default gallery alignment to left with option to override in shortcode.
* Added gallery row classes to allow easier overriding of default margins.

= 1.2.7 =
* Added option to display image title above caption.
* Removed support for definition list (dl) structure.

= 1.2.6 =
* Added support for setting target of gallery link.
* Corrected horizontal image spacing issue in gallery grid view.

= 1.2.5 =
* Fixed bug that allowed HTML markup to appear in title attribute.

= 1.2.4 =
* HTML markup in image captions is now allowed.

= 1.2.3 =
* Fix for users of Twenty Eleven theme and most other themes that display elements overlapping the slideshow.

= 1.2.2 =
* Corrects 404 errors generated by the fancyBox CSS when Internet Explorer is the active browser.
* Allow "none" as an option for the link shortcode attribute.

= 1.2 =
* Specify slideshow behavior.
* Corrects an issue whereby only the last gallery on the page could trigger a slideshow.

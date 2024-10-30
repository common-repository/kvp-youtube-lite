=== Plugin Name ===
Contributors: Keiser Media
Donate link: http://keisermedia.com/projects/kvp-youtube-lite/
Tags: import, youtube, thumbnail 
Requires at least: 3.5
Tested up to: 3.8.1
Stable tag: 1.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Provider plugin which enables YouTube functionality for use with Katalyst Video Plus.

== Description ==

Provider plugin which enables YouTube functionality for use with Katalyst Video Plus. This plugin provides the YouTube API controllers to allow interation between YouTube and your WordPress installation.

== Installation ==

1. Upload `katalyst-video-plus` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Upload and activate Provider plugins.

== Frequently Asked Questions ==

=Do I need a YouTube API key?=
While an API Key is not necessary, it is highly recommended. The YouTube API has daily limitations on unauthenticated requests which means that without an API, you will not be able to import from YouTube.

=How do I obtain a YouTube API key?=
[YouTube API Getting Started](https://developers.google.com/youtube/v3/getting-started?hl=en#before-you-start "YouTube API Getting Started")

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets 
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png` 
(or jpg, jpeg, gif).
2. This is the second screen shot

== Changelog ==

= 1.1.1 =
* [Fixed] Compatible with KVP 1.2.2

= 1.1.0 =
* [Removed] Add Source Field - API Key

= 1.0.1 =
* [Fixed] Compatible with KVP 1.0.2

= 1.0.0 =
* Initial release.
=== ReadTrack ===
Contributors: marcinfilipiak
Tags: reading time, progress bar, estimated reading time, UX, readability
Requires at least: 5.8
Tested up to: 6.8
Requires PHP: 7.2
Stable tag: 1.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Adds a reading progress bar and an estimated reading time above each single post.

== Description ==

**ReadTrack** is a lightweight plugin that improves the user experience by displaying:
- ⏱️ Estimated reading time based on post word count
- 📊 A visual progress bar that fills as the reader scrolls

You can fully customize the text shown before the post using a settings page in the WordPress admin panel. The default template is:

`⏱️ Estimated reading time: %minutes% min`

Simply place `%minutes%` in your message to dynamically show the calculated reading time.

== Installation ==

1. Upload the plugin folder to the `/wp-content/plugins/` directory, or install it via the WordPress Plugin Directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Go to **Settings > ReadTrack** to customize the message.
4. Make sure your theme supports `the_content` filter (most do).

== Frequently Asked Questions ==

= Can I change the reading time label? =
Yes! Just go to **Settings > ReadTrack** and enter your custom message. Use `%minutes%` where you want the number to appear.

= Does it work with custom post types? =
Currently, it only affects single blog posts (`is_single()`). You can modify the condition in `readtrack_add_elements()` if needed.

= Is JavaScript required? =
Yes, JavaScript is used to update the scroll progress bar in real time.

== Screenshots ==

1. Reading time and progress bar above a post.
2. Plugin settings screen in admin panel.

== Changelog ==

= 1.1 =
* English translation of plugin code and admin panel
* First public version with settings page and config file

== Upgrade Notice ==

= 1.1 =
Updated plugin with English admin interface and improved compatibility.

== License ==

This plugin is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License v2 or later.


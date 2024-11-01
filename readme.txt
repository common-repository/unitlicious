=== Unit.licio.us ===
Contributors: unitinteractive
Tags: del.icio.us, social-network, posts
Requires at least: 2.8
Tested up to: 2.8.4
Stable tag: 0.1.0

Adds bookmarks from your Del.icio.us account to your WordPress blog as posts. Add as many Del.icio.us accounts as you want! 

== Description ==

Unit.licio.us is a plugin for WordPress 2.8+ that allows you to integrate your Del.icio.us account into your WordPress blog. 
This plugin doesn't simply display your recent Del.icio.us bookmarks as a list, but actually stores your bookmarks as posts 
that you can then edit and display any way you want!

* Doesn't just list recent bookmarks, but actually adds them to your WordPress database.
* Add bookmarks from as many accounts as you want.
* Uses built-in WordPress cron system to look for new bookmarks "every hour".
* Built on PhpDelicious by Edward Eliot [PhpDelicious by Edward Eliot](http://www.phpdelicious.com/ "PhpDelicious")


== Installation ==

1. Unzip the Unit.licio.us folder into the plugins directory of your WordPress installation.
2. Next, log in to your WordPress admin and go to the 'Plugins" section. Click 'activate' under 'Unit.licio.us'.
3. Finally, under 'Settings' click on 'Unit.licio.us' to be taken to the Unit.licio.us options panel. Here you can 
configure which accounts the plugin should check, which blog author the posts should be attributed to, and which 
category the bookmarks will be posted in. After configuring the plugin, your bookmarks should begin to show up within 
the hour!


== Frequently Asked Questions ==

= Why don't my bookmarks get posted immediately? =

Unit.licio.us uses the built-in WordPress cron feature to automate the process of searching Del.icio.us accounts. 
While not a true 'cronjob', as long as the blog has a decent amount of traffic the plugin will effectively check for 
new tweets once an hour. This is because WordPress's cron runs on each page-load and checks to see if it has been 
longer than the scheduled interval since the function was last run. If so, it runs the function and sets the timer 
forward. So, while your bookmarks won't appear instantly, there should be a minimal amount of time between postings. 

= Why are my bookmarks displaying like regular blog posts? =

Unit.licio.us posts *are* regular WordPress posts but with some Del.icio.us specific information embedded in each one. 
The bookmark URL is stored in a custom field named ‘link’ attached to each post. How you choose to display this is up 
to you. A default theme isn’t likely to have anything built in to handle Unit.licio.us posts and will simply display 
them as it would any other post.

== Screenshots ==

1. The plugin configuration screen

== Changelog ==

= 0.1.0 =
* Initial release.

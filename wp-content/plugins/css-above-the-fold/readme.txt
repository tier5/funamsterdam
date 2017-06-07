=== CSS Above The Fold ===
Contributors: blogestudio, pauiglesias
Tags: minify, minify css, minify stylesheet, minification, optimization, optimize, stylesheet, css, above the fold, speed, wpo
Requires at least: 3.3.2
Tested up to: 4.3
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Faster CSS browser rendering displaying selected fragments of your theme stylesheet file directly into the head section.

== Description ==

Improve user experience by having your above-the-fold (top of the page) CSS styles in-page.

Even if the rest of the CSS files take a seconds to load, these specific CSS styles displayed from the head section ensure a quick rendering of your page and better score in testing systems like Google Page Speed Insights.

But you do not need to maintain two separate stylesheets, just select specific fragments of your theme style file with a special markup to create an above-the-fold styles joining the CSS fragments in the head section.

This plugin enables a special open and close tags that you can insert editing your style.css theme file to surround pieces of code:

[css-above-the-fold]

... Your theme CSS code fragment ...

[/css-above-the-fold]

But this syntax is not an standard CSS, so you need to include this tags between CSS comments, there are two ways to do it:

###1. Comment whole section###

The easiest way, the CSS is rendered only in the head but not in the stylesheet.
This kind of markup does not allow to use your own CSS comments inside the fragment.
Take care to use this way only when the plugin is active, because you are commenting some parts of your theme stylesheet.

/* [css-above-the-fold]

... CSS code fragment ...

[/css-above-the-fold] */

###2. Comment only the tags###

The unobtrusive way, it allows you to include comments inside fragments, but the fragments are rendered both in the head section and the CSS file.
This way is plugin-independent, and your theme stylesheet will keep running with this plugin activated or not.

/* [css-above-the-fold] */

... CSS code fragment ...

/* [/css-above-the-fold] */

You can use these two kinds of markup combined in your CSS File.

The resulting CSS fragments introduced in the header are compacted and minified.

This plugin only read the style.css file when it is modified, and stores the results using the WP options API.

== Installation ==

1. Download the plugin from Wordpress plugin repository
1. Unzip and upload css-above-the-fold folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Edit your theme CSS file style.css following the instructions above

== Frequently Asked Questions ==

= My theme will be dependent on this plugin? =

Only if you use the **`Comment whole section`** type of fragment selection. However, it is easily reversible uncommenting the sections.
If you want an independent stylesheet use the **`Comment only the tags`** technique.

= Why is important rendering CSS file on the top of the page? =

Prioritizing above-the-fold content it's an important factor to speed-up your website, rankings improvement and conversion.

= Why using this technique of selecting fragments from the theme stylesheet file? =

The aim is simplicity, editing a single file avoids having to maintain two separate stylesheets.

= And what CSS should I mark to display it "above-the-fold"? =

That is the million dollar question, this plugin does not do this task automatically, only provides a method for easy and quick implementation.
There are some tools to help you in the process of select the rights fragments of code, and maybe you will have to modify your stylesheet structure to properly isolate these CSS fragments.
You can find a lot of resouces about this topic performing a simple Google search like "css above the fold".

== Screenshots ==

1. A edited CSS file using Whole section commented fragment
2. A fragment commenting only the special markup
3. Styles in-page resulting of joining css theme fragments

== Changelog ==

= 1.0 =
* Initial Release.
* Tested until WordPress 4.2

== Upgrade Notice ==

= 1.0 =
Initial Release.
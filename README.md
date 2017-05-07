# ttrss-fix_gawker_embeds
Tiny Tiny RSS plugin that fixes the broken embeds in various Gawker feeds. Currently it only fixes YouTube and Vimeo embeds.

## Installation
* [Download the plugin](https://github.com/nowotny/ttrss-fix_gawker_embeds/archive/master.zip) to your Tiny Tiny RSS server
* Unzip it to a temporary folder
* Copy/move the `fix_gawker_embeds` folder to the `plugins` directory of your Tiny Tiny RSS installation
* Enable the `fix_gawker_embeds` plugin in Tiny Tiny RSS's `Preferences`

## How it works?
After enabling the plugin it'll take each new article that Tiny Tiny RSS downloads, scan it and replace the custom Gawker embed code with the original.

There is also a panel in the `Preferences` that allows you to apply the fixes to already downloaded articles.

## Changelog
v. 1.2
* Added fix for images not showing up

v. 1.1
* Added Vimeo embed support

v. 1.0
* Initial release

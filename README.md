Arrakis_404EverGone
===================

404 Rewrite rule Magento module

This module allows you to create rewrite rules that will be triggered on 404 so you can redirect visitors to a custom location. Rules use regex matching for better targeting requests.

It can especially being used for SEO purposes when migrating a website from any platform to Magento, where it's not possible (or efficient) to generate an exhaustive list of URL that you would rewrite using Magento's URL Rewrite system.
It conveniently replaces .htaccess rewrites capabilities without the need of editing source files (all rules are editable from the backend).

Note: at the moment, target paths cannot use elements from the original request (you cannot extract a value from the request to generate the final target).


Screenshots
===========

![Admin menu](https://raw.github.com/nanawel/Arrakis_404EverGone/master/resources/screenshots/0.1.1/screenshot_01.png)

![Rewrite rules grid](https://raw.github.com/nanawel/Arrakis_404EverGone/master/resources/screenshots/0.1.1/screenshot_02.png)

![Rewrite rule form](https://raw.github.com/nanawel/Arrakis_404EverGone/master/resources/screenshots/0.1.1/screenshot_03.png)

![Pattern check result](https://raw.github.com/nanawel/Arrakis_404EverGone/master/resources/screenshots/0.1.1/screenshot_04.png)
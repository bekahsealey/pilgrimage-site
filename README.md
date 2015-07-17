# pilgrimage-site
php based website using custom CMS and database design

## LICENSE

Copyright 2015 Bekah Sealey

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

## LIVE

[The Wisconsin Way](http://wisconsinway.com)


## BUSINESS NEEDS

This responsive website was designed to reflect a virtual immersion in the environment of the pilgrimage journey that it presents. 


## FEATURES

* Full screen lightweight background images are showcased, and bottom padding is used to allow the text to roll up the screen for an unobstructed view of the backgrounds.
* The responsive format transitions from single column on small screens with mobile Slick-nav menu, to two columns and three column views.
* A jQuery script places sidebar content below main content on smaller format screens.
* Videos on the home page are pulled through a random database call.
* Page and post urls are rewritten to provide better SEO value.
* Custom text editor creates posts and pages in markdown, interpreted using Michelf's Markdown PHP plugin, with a cheatsheet available on hover.
* Editor also includes ability to add extra HTML for advanced content needs.

*Scripts adapted from [PHP Solutions](http://www.apress.com/9781430232490) with my own modifications*

## REQUIRES

* PHP 5.3 - for Michelf Markdown
* Apache web server - to use htaccess files
* [jQuery](http://jquery.com/) (installed via [CDNJS](https://cdnjs.com/libraries/jquery/))
* [Modernizr](http://www.modernizr.com/) (installed via [CDNJS](http://cdnjs.com/libraries/modernizr))
* [SlickNav](http://slicknav.com/) (installed via [CDNJS](http://cdnjs.com/libraries/SlickNav))
* [Michelf Markdown](https://github.com/michelf/php-markdown) - installed into includes/Michelf
* [reCAPTCHA](https://developers.google.com/recaptcha/docs/start) - installed into includes
* Amaranth font (installed via [Google Fonts](https://www.google.com/fonts))

## FONTS USED BY ORIGINAL

*Removed from redistribution*
* Heading: [ArmWrestler](www.fontsquirrel.com/fonts/ArmWrestler)
* Body: [Colaborate](www.fontsquirrel.com/fonts/colaborate)
* Icons: [Web Symbols](www.fontsquirrel.com/fonts/web-symbols)

## USAGE

* Create database tables according to the structure in database-structure.odt
  * You may want to preload your desired categories into the database at this time
* Update includes/connection.php with database connection information then upload all documents to the server
* Update reCaptcha keys on contact-us/index.php
* Note category id's from the database and update website page files accordingly
* Both sidebars live in includes/sidebar.php and currently must be edited manually
* Currently, videos and their posters are uploaded manually to the videos and posters directories and then the required information is added to the database for the sql query
* Posts and pages are created in the administration panel and require a category to display
* Pages must be added to the navigation manually, in the includes/header.php
* Users are registered from the registration form within the admin/register directory which is protected via Apache's Basic Authentication. 
  * A valid htpassword file can be created at [htaccesstools](http://www.htaccesstools.com/htpasswd-generator/) and uploaded to the admin/register directory
  * The admin/register directory can be removed from the server after users are registered


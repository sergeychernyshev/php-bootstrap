This is a simple library to be used in PHP projects to help managing the setup.

Currently it helps with determining the root paths and URLs for the project and tries to handle different web site setups abstracting the differences from the user.

Compatibility
=============

Project setups
--------------
- files simply unpacked in the folder under document root of the site like `/path/to/document/root/my_project/`
- Apache Alias directove is used to map `/my_project/` to a folder outside of document root, e.g. `/path/to/my_project/`
- sympling is created under document root pointing at foles outside of document root, e.g. `/path/to/document/root/my_project/` -> `/path/to/my_project/`

Project code
------------
- project uses regular PHP files in URLs, e.g. `http://example.org/my_project/index.php?type=article&name=slug`
- project maps multiple URLs to the same PHP file through PATH_INFO, e.g. `http://example.org/my_project/index.php/article/slug.html`
- project maps multiple URLs to the same PHP file using mod_rewrite, e.g. `http://example.org/my_project/article/slug.html` -> `http://example.org/my_project/index.php`

Use it at your own risk and allow users to override the default values in case their specific setup is even more weird and can't be auto-detected

TODO
----
Automated testing and instructions/scripts/files to set up each of the tested environments.

Usage
=====

Copy bootstrap.php to the root folder of your project and load it at top of your code

	<?php require_once(dirname(__FILE__).'/bootstrap.php'); ?>

in subfolders, climb up the directory like so:

	<?php require_once(dirname(dirname(__FILE__)).'/bootstrap.php'); ?>

and so on.

$_PROJECT array
---------------
In your code, you can use special `$_PROJECT` array values in your code

- `$_PROJECT['ROOT_FILESYSTEM_PATH']` - Path on the file system where the project code is extracted to
- `$_PROJECT['ROOT_ABSOLUTE_URL_PATH']` - absolute URL path that corresponds to the root of the project
- `$_PROJECT['ROOT_FULL_URL']` - full URL that corresponds to the root of the projecd (used in emails or social media sharing)

In other projects
=================
About every framework out there uses it's own bootstrapping process that usually heavily baked into how that framework works.

Here's a list of links to their code:

- Zend Framework - `setBaseUrl()` in [`Zend/Controller/Request/Http.php`](http://framework.zend.com/code/filedetails.php?repname=Zend+Framework&path=%2Ftrunk%2Flibrary%2FZend%2FController%2FRequest%2FHttp.php)

Contributing
============
This project is based on experience writing PHP apps, you can contribute by:

- testing the code in different setups and reporting [issues](https://github.com/sergeychernyshev/php-bootstrap/)
- fixing bugs and sending pull request on Github
- editing this file, including links to other project's code or just adding documentation

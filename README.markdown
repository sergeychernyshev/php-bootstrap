[![SensioLabsInsight](https://insight.sensiolabs.com/projects/3cd453bb-dd2b-4c59-9310-76b3dd8d778a/mini.png)](https://insight.sensiolabs.com/projects/3cd453bb-dd2b-4c59-9310-76b3dd8d778a)

This is a simple library to be used in PHP projects to help managing the setup, it helps with determining the root paths and URLs for the project and tries to handle different web site setups abstracting the differences from developers.

Prerequisites
=============
The only pre-requisite for the project is PHP version 5.3 or newer.

Usage
=====
Copy project folder into root of your project and load it at the top of your code

```php
<?php require_once(__DIR__ . '/php-bootstrap/bootstrap.php'); ?>
```

Getting the values
==================
You can simply retrieve environment settings array by calling `bootstrap()` function and passing in path to any file in the **root of your project**.

If you're retrieving the environment in a file that is in the root already, you can just use `__FILE__` magic PHP constant like so:

```php
$project_env = PHPBootstrap\bootstrap(__FILE__);
```

It is useful to get environment in one global file in your project and usually a good idea to put it in the root of the project, this way the rest of the files will only need to include this one file.

Environment settings array
---------------
Once you get the environment settings array, you can use values inside:

- `$project_env['ROOT_FILESYSTEM_PATH']` - Path on the file system where the project code is extracted to
- `$project_env['ROOT_ABSOLUTE_URL_PATH']` - absolute URL path that corresponds to the root of the project
- `$project_env['ROOT_FULL_URL']` - full URL that corresponds to the root of the projecd (used in emails or social media sharing)

All values have no trailing `/` symbol, you must append it in code.
This is useful as it makes all strings start with `/` that corresponds to the root of your application file structure, e.g. `/config.php` or `/image/logo.png`

Example
-------
```php
<?php require_once(__DIR__ . '/bootstrap.php');
$project_env = PHPBootstrap\bootstrap(__FILE__);

if (!file_exists($project_env['ROOT_FILESYSTEM_PATH'] . '/config.php')) { ?>

	<html><body>
	<h1>Can't find config.php</h1>
	<a href="<?php echo $project_env['ROOT_ABSOLUTE_URL_PATH'] ?>/install.php">Run the installation</a>
	</body></html>

	<?php exit;
}

...
```
Compatibility
=============

Project setups
--------------
- files simply unpacked in the folder under document root of the site like `/path/to/document/root/my_project/`
- Apache Alias directove is used to map `/my_project/` to a folder outside of document root, e.g. `/path/to/my_project/`
- symlink is created under document root pointing at files outside of document root, e.g. `/path/to/document/root/my_project/` -> `/path/to/my_project/`
- serving site on non-default port number (not 80)
- serving site through SSL

Project code
------------
- project uses regular PHP files in URLs, e.g. `http://example.org/my_project/index.php?type=article&name=slug`
- project maps multiple URLs to the same PHP file through PATH_INFO, e.g. `http://example.org/my_project/index.php/article/slug.html`
- project maps multiple URLs to the same PHP file using mod_rewrite, e.g. `http://example.org/my_project/article/slug.html` -> `http://example.org/my_project/index.php`

Use it at your own risk and allow users to override the default values in case their specific setup is even more weird and can't be auto-detected

Testing
=======
You can see results of supported setups on the testing harness site:
http://php-bootstrap.sergeychernyshev.com/

You can set up your testing environment using [Test Harness Project](https://github.com/sergeychernyshev/php-bootstrap-test)

In other projects
=================
About every framework out there uses it's own bootstrapping process that usually heavily baked into how that framework works.

Here's a list of links to their code:

- Zend Framework - `setBaseUrl()` in [`Zend/Controller/Request/Http.php`](https://github.com/zendframework/zf2/blob/d8adfe90da23af119ae5732195ad50af0009d672/library/Zend/Http/PhpEnvironment/Request.php#L147)

Contributing
============
This project is based on experience writing PHP apps, you can contribute by:

- testing the code in different setups and reporting [issues](https://github.com/sergeychernyshev/php-bootstrap/issues)
- fixing bugs and sending pull request on Github
- editing this file, including links to other project's code or just adding documentation

<?php
/**
 * Array of project settings
 */
$_PROJECT = function() {
	global $_SERVER;

	$_PROJECT = array();

	/*
	 * $_PROJECT['FILE_PATH']
	 *
	 * Path on the file system where the project code is extracted to
	*/
	$_PROJECT['ROOT_FILESYSTEM_PATH'] = dirname(__FILE__);

	// if this project was checked out in project subfolder (e.g. git submodule), climb up one more folder
	if (basename($_PROJECT['ROOT_FILESYSTEM_PATH']) == 'php-bootstrap') {
		$_PROJECT['ROOT_FILESYSTEM_PATH'] = dirname($_PROJECT['ROOT_FILESYSTEM_PATH']);
	}

	/*
	 * $_PROJECT['ABSOLUTE_URL_PATH']
	 *
	 * Absolute URL path that corresponds to the root of the project
	*/
	# Determining a path to a currently requested file from the root of the project and removing it
	# using realpath to work around the issue with SCRIPT_FILENAME not traversing symlinks
	$file_path_from_root = substr(realpath($_SERVER['SCRIPT_FILENAME']), strlen($_PROJECT['ROOT_FILESYSTEM_PATH']));

	$_PROJECT['ROOT_ABSOLUTE_URL_PATH'] = substr($_SERVER['SCRIPT_NAME'], 0, -strlen($file_path_from_root));

	/*
	 * $_PROJECT['URL'] 
	 *
	 * Full URL that corresponds to the root of the project
	*/
	$https = false;
	$default_port = 80;
	if (array_key_exists('HTTPS', $_SERVER)) {
		$https = true;
		$default_port = 443;
	}

	$hostname = null;

	// hostname fallback for CLI execution
	if (array_key_exists('SERVER_NAME', $_SERVER)) {
		$hostname = $_SERVER['SERVER_NAME'];
	} else {
		$hostname = php_uname('n');
	}

	// port fallback for CLI execution
	if (array_key_exists('SERVER_PORT', $_SERVER)) {
		$port = $_SERVER['SERVER_PORT'];
	} else {
		$port = $default_port;
	}

	$_PROJECT['ROOT_FULL_URL'] =
		'http'.($https ? 's' : '') . 
		'://' .
		$hostname .
		($port != $default_port ? ":$port" : '') .
		$_PROJECT['ROOT_ABSOLUTE_URL_PATH'];

	return $_PROJECT;
};

$_PROJECT = $_PROJECT();

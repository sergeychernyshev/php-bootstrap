<?php
namespace PHPBootstrap;

if (!function_exists('PHPBootstrap\bootstrap')) {
	/**
	 * Array of project settings
	 */
	function bootstrap($file_in_root_folder = null) {
		global $_SERVER;

		$_PROJECT = array();

		$file = is_null($file_in_root_folder) ? __FILE__ : $file_in_root_folder;

		/*
		 * $_PROJECT['ROOT_FILESYSTEM_PATH']
		 *
		 * Path on the file system where the project code is extracted to
		*/
		$_PROJECT['ROOT_FILESYSTEM_PATH'] = dirname($file);

		// if this project was checked out in project subfolder (e.g. git submodule), climb up one more folder
		if (basename($_PROJECT['ROOT_FILESYSTEM_PATH']) == 'php-bootstrap') {
			$_PROJECT['ROOT_FILESYSTEM_PATH'] = dirname($_PROJECT['ROOT_FILESYSTEM_PATH']);
		}

		/*
		 * $_PROJECT['ABSOLUTE_URL_PATH']
		 *
		 * Absolute URL path that corresponds to the root of the project
		*/
		$script_filename_realpath = realpath($_SERVER['SCRIPT_FILENAME']);
		$script_filename_folder = dirname($script_filename_realpath);


		// determining if ROOT is above the script that is loaded
		if (strpos($script_filename_realpath, $_PROJECT['ROOT_FILESYSTEM_PATH']) !== FALSE) {
			// root is above the script
			# Determining a path to a currently requested file from the root of the project and removing it
			# using realpath to work around the issue with SCRIPT_FILENAME not traversing symlinks
			$file_path_from_root = substr(
				$script_filename_realpath,
				strlen($_PROJECT['ROOT_FILESYSTEM_PATH'])
			);

			$_PROJECT['ROOT_ABSOLUTE_URL_PATH'] = substr($_SERVER['SCRIPT_NAME'], 0, -strlen($file_path_from_root));
		} else if (strpos($_PROJECT['ROOT_FILESYSTEM_PATH'], $script_filename_folder) !== FALSE) {
			// root is below the script
			$_PROJECT['ROOT_ABSOLUTE_URL_PATH'] = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . substr(
				$_PROJECT['ROOT_FILESYSTEM_PATH'],
				strlen($script_filename_folder)
			);
		} else {
			// root is in some subfolder of the parent project as the script,
			// but script is not in the folder above root
			$common_parent = dirname($script_filename_folder);

			$i = 0;
			while (strpos($_PROJECT['ROOT_FILESYSTEM_PATH'], $common_parent) === FALSE) {
				$new_common_parent = dirname($common_parent);
				if ($common_parent == $new_common_parent) {
					die("Got up to root of the filesystem");
				}
				$common_parent = $new_common_parent;

				$i++;
				// Hope 50 folders is not too much
				if ($i > 50) {
					die("To many iterations: $i");
				}
			}

			$script_filename_under_common_parent = substr($script_filename_realpath, strlen($common_parent));
			$common_parent_root_url = substr($_SERVER['SCRIPT_NAME'], 0,
								-strlen($script_filename_under_common_parent));

			$root_path_under_common_parent = substr($_PROJECT['ROOT_FILESYSTEM_PATH'], strlen($common_parent));
			$_PROJECT['ROOT_ABSOLUTE_URL_PATH'] = $common_parent_root_url . $root_path_under_common_parent;
		}

		/*
		 * $_PROJECT['ROOT_ABSOLUTE_URL_PATH']
		 *
		 * Full URL that corresponds to the root of the project
		*/
		$https = false;
		$default_port = 80;
		if (array_key_exists('HTTPS', $_SERVER) and $_SERVER['HTTPS'] and $_SERVER['HTTPS'] != 'off') {
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

		// some systems will have port as part of server name
		$hostname_has_port = strpos($hostname, ':');
		if ($hostname_has_port > 0) {
			$hostname = substr($hostname, 0, $hostname_has_port);
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

	// deprecated, used for backwards compatibility
	$_PROJECT = bootstrap();
}

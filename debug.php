<?php
if (!defined("VALID_ENTRY_POINT")) {
	header('HTTP/1.1 403 Not a valid entry point');
	exit;
}
?>
<h1>PHP Bootstrapper</h1>

<h2>Generated values</h2>
<table cellpadding="15" cellspacing="0" border="1" style="margin-bottom: 1em">
<?php foreach ($_PROJECT as $key => $val) { ?>
<tr><td>$_PROJECT['<?php echo $key ?>']</td><td><?php echo is_null($val) ? '<i>null</i>' : htmlentities($val) ?></td></tr>
<?php } ?>
</table>

<h2>Variables used for calculation</h2>
<table cellpadding="15" cellspacing="0" border="1">
<?php foreach (array('SCRIPT_FILENAME', 'SCRIPT_NAME', 'HTTPS', 'HTTP_HOST', 'SERVER_PORT') as $var) { ?>
<tr><td>$_SERVER['<?php echo $var ?>']</td><td><?php 
if (array_key_exists($var, $_SERVER)) {
	echo is_null($_SERVER[$var]) ? '<i>null</i>' : htmlentities($_SERVER[$var]);
} else {
	echo 'n/a';
}

?></td></tr>
<?php } ?>
</table>

<?php if (defined("SHOW_PHP_INFO")) { ?>
	<h2>More server information</h2>
	<?php
	phpinfo();
}

<?php

// Version check
$version = is_file(__DIR__.'/../version') ? file_get_contents(__DIR__.'/../version') : 'dev';
defined('APP_VERSION') or define('APP_VERSION', $version);

// Load default settings via dotenv from file
$dotenv = new Dotenv\Dotenv(__DIR__.'/..', '.env');
$dotenv->load();

// Additional Validations
/*if (!preg_match('/^[a-z0-9_-]{3,16}$/', getenv('APP_NAME'))) {
	throw new \Dotenv\Exception\ValidationException(
		'APP_NAME must only be lowercase, dash or underscore and 3-16 characters long.'
	);
}*/

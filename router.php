<?php

require_once __DIR__ . '/router-lib.php';

WP_CLI\Router\add_filter( 'option_home', '\\WP_CLI\\Router\\option_home', 20 );
WP_CLI\Router\add_filter( 'option_siteurl', '\\WP_CLI\\Router\\option_siteurl', 20 );

$root = $_SERVER['DOCUMENT_ROOT'];
$path = '/'. ltrim( parse_url( $_SERVER['REQUEST_URI'] )['path'], '/' );

if ( file_exists( $root.$path ) ) {
	if ( is_dir( $root.$path ) && substr( $path, -1 ) !== '/' ) {
		header( "Location: $path/" );
		exit;
	}

	if ( strpos( $path, '.php' ) !== false ) {
		chdir( dirname( $root.$path ) );
		require_once $root.$path;
	} else {
		return false;
	}
} else {
  roots_rewrites($path); // rewrite path attempt
	chdir( $root );
	require_once 'index.php';
}

/* {{{ roots_rewrites() */

function roots_rewrites($path) {
  global $root;

  // @FIXME: Customize these values if needed
  $roots_new_non_wp_rules = array(
    '(/assets/(.*))'  => '/wp-content/themes/roots' . '/assets/$1',
    '(/plugins/(.*))' => '/wp-content/plugins' . '/$1'
  );

  $path = preg_replace(
    array_keys($roots_new_non_wp_rules),
    array_values($roots_new_non_wp_rules),
    $path
  );

  if (file_exists($root . $path)) {
    header("Location: {$path}");
    exit;
  }
}

/* }}} */

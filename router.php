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

/**
 * Applies Roots Theme rewrites when possible
 *
 * Rewrites:
 * - assets/  => wp-content/themes/roots/assets/
 * - plugins/ => wp-content/plugins/
 *
 * This function is meant to be used within the router of scribu's
 * {@link https://github.com/scribu} server-command package for WP-CLI.
 *
 * This function applies rewrites specific to Roots Theme.
 *
 * WP-CLI: {@link https://github.com/wp-cli/wp-cli}
 * server-command: {@link https://github.com/wp-cli/server-command}
 * Roots Theme: {@link https://github.com/retlehs/roots}
 *
 *          ``                  ``
 *        -+s+                  +s+-
 *      -osss+                  +ssso-
 *    .+sssss/     :`           +sssss+.
 *   -ossssss:     +`           +sssssso-
 *  .sssssss+     .s`           +ssssssss.
 * `ossssss/     `os`    `-     +sssssssso`
 * :sssss/`     `+so`    ./     +sssssssss:
 * +sss/`      -oss:     //     +sssssssss+
 * +s/`      -osso:     .s/     +sssssssss+
 * -`      -osss/`     .os+     /sssssssss+
 *       -osss/`      :ssso     .sssssssss-
 *     -osss/`      -osssss:     .ossssss+
 *   .osss/`      -ossssssss-     `/sssso`
 *   `+s/`      -osssssssssss/      `/s+`
 *     `      -osssssssssssssso-      `
 *          -osssssssssssssssssso-
 *         .+ssssssssssssssssssss+.
 *           `.:+osssssssssso+:.`
 *                 `......`
 *
 * Copyright (C) 2013  Joel Kuzmarski
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category   WP-CLI
 * @package    RootsThemeExamples
 * @author     Joel Kuzmarski <leoj3n+server-command@gmail.com>
 * @copyright  2013-2014 Joel Kuzmarski
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt
 *             GNU General Public License, Version 3
 * @link       https://github.com/leoj3n/server-command/tree/roots-rewrites
 */
function roots_rewrites($path) {
  global $root;

  // @FIXME: Customize these values if needed
  $roots_new_non_wp_rules = array(
    '(/assets/(.*))'  => '/wp-content/themes/roots/assets/$1',
    '(/plugins/(.*))' => '/wp-content/plugins/$1'
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

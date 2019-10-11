<?php

/**
 * @package
 * @version
 */
/*
Plugin Name: Full Fact - Claim Review Schema
Plugin URI:
Description: Plugin for WordPress to implement Claim Review Schema
Version: 0.8
Author:
Author URI:
Tags:
License: GPLv2 or later
Text Domain: claimreview
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

define( 'CLAIMREVIEW_PLUGIN_PATH', dirname( __FILE__ ) );
define( 'CLAIMREVIEW_PLUGIN_URL', plugins_url( '', __FILE__ ) );

require_once CLAIMREVIEW_PLUGIN_PATH . '/inc/core.php';

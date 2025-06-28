<?php
/*
Plugin Name: Branch Location
Description: A starter template for the Branch Location plugin.
Version: 1.0.0
Author: Mikiyas Shiferaw
Author URI: https://t.me/mikiyas_sh
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Plugin activation hook.
function branch_location_activate() {
    // Activation code here.
}
register_activation_hook( __FILE__, 'branch_location_activate' );

// Plugin deactivation hook.
function branch_location_deactivate() {
    // Deactivation code here.
}
register_deactivation_hook( __FILE__, 'branch_location_deactivate' );

// Main plugin function.
function branch_location_init() {
    // Initialization code here.
}
add_action( 'init', 'branch_location_init' );
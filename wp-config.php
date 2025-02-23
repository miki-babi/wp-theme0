<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */
//
// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'axumcoff_enat' );

/** Database username */
define( 'DB_USER', 'axumcoff_enat' );

/** Database password */
define( 'DB_PASSWORD', ')H2tO+XS^VOi' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb3_unicode_ci' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         ',b.L!=Xas FA]3n^XDd*=RrIP,43I_^ca^{kFS/v5o3{VXJpZ2?<vs~wLsum~7XQ' );
define( 'SECURE_AUTH_KEY',  'wQuC*9%}4xse3(#I?y-]sL?MvBR/q7[*]Tw6uTr _8CF QKbR)u&h[`D0PoS9,If' );
define( 'LOGGED_IN_KEY',    'U[#^*Bom*eX4_I1=UpQQ9E:~CJ2:A&tb721d7WMz>^r0>_WQwpG3fCgsa3WgaO#T' );

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

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'enat' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'NONCE_KEY',        'BVV]|&9tFl#QqJ/RFWC@J1(Xt.*>sS(P7d+|[wVNHE)IARq&hjo]ZVyY|;-xUC08' );
define( 'AUTH_SALT',        '6~}PMckY<0R?JxzXlx&>6&F/-eYMr3&Eu~j6(3RhzuB4^&^;{~s:4*8nbN^5cov=' );
define( 'SECURE_AUTH_SALT', 'Bjvbz6Eo<n)hvhv2Rl%A~rU+g>~Fl:k~v<G8Rhqy5&fJk,[8]+v@6QAA[:s E+-(' );
define( 'LOGGED_IN_SALT',   '6R;sq,%EC{fj;AM<s9qTY`u!LIsG=mDz.=]`^G^n2Y|H 0tU|a*L`w2=[_NQ^x;x' );
define( 'NONCE_SALT',       '*A_jD|C`u[bEC;7+TH7gt[VAem`3u|?ct^Bqiu$/L9,fUC}bEcB698piv R2Dqe#' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );
define( 'WP_CACHE', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

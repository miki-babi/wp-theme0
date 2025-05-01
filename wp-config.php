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
define( 'DB_NAME', 'axumcoff_enat' );

/** Database username */
define( 'DB_USER', 'axumcoff_enat' );

/** Database password */
define( 'DB_PASSWORD', '^FE6H_5Fp&+p' );

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
define( 'AUTH_KEY',         'G(*PO>vilQ@<xg]8B(V0.e5q1UV)~rs7IK]eu<Jx4+-C+xtb_iXa7eyn4QSl%U#5' );
define( 'SECURE_AUTH_KEY',  'abaSAKz%$4fe!|JdeY`!7E11S|lL*TZMwAnselJx#6Ye0FEu-`UkH5v>`+d<~G<E' );
define( 'LOGGED_IN_KEY',    'g<GA77{/8^;*Ag OX0?h7!6v`[@<%-uOm]c|EF}tqj0VL1em2k)b;h9c=n[w?yif' );
define( 'NONCE_KEY',        'AfC2PUAGW&e#0$$_&6KdCMOuH8-f;-. Ru!YHr[+HW4.gr9x$`gaJvi!9+LVZnoH' );
define( 'AUTH_SALT',        '>w-cZ7:c1,8/}ys[%$7;=2{;s!yqY$>N1y_[:_H9F~-yCFYak~<SMGXQNS`pQbY6' );
define( 'SECURE_AUTH_SALT', 'c*/c]K5R~%e_v-^bTnxMN}mkvDN:DjB#IBD:UyN4Ny*~j{VSc))4nlhx]hM}9F^K' );
define( 'LOGGED_IN_SALT',   'NOXWd7 >:+WBNEVz~Fs&#mp7xC5n)2vIu{2[+).0[:e>+S^X-!y,IpidTiE_lZfF' );
define( 'NONCE_SALT',       '~W*nN`vh1~{ ElPj&6w*4v:[|UZ0@OJMAIt+hlE,]Jd0`PFpgp@`!xyOb6<z8aJP' );

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
define( 'WP_DEBUG', true );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */
define('FS_METHOD', 'direct');


/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

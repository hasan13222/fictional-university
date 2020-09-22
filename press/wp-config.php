<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'onschool' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'F|mKA#6ZR,fwBF%=nHGVjEU{O~rAD:oHU#uaQ>O4;fm,-C$&iO{8m}Uo#v~02,,8' );
define( 'SECURE_AUTH_KEY',  ':826P8T/|cHmu]gOm%#_VtKj]80CZ.%pp|p#msVowW3Bj$C$TZN<Y8.^n?Y?Wv;u' );
define( 'LOGGED_IN_KEY',    '/93uW(^)PEQLK(lcGH}N@i9Y?S&I~IY|ee{IxC#=*axHbp}Xyu`67yHxQh4zW^s.' );
define( 'NONCE_KEY',        '|RSE)hdZb5@bUNywXYjb})AT]sd*qY]MjsJy~/x=HM1Qt&Z&bo4EXw(_XVS8Ev0Z' );
define( 'AUTH_SALT',        'Z4)zf4h,$=80m5>C:w,Y8Su1q&::!n@56xXZ{.2]G.gna8v@x[2_,AS).Le /kqB' );
define( 'SECURE_AUTH_SALT', '^,eUs+|cn+.2r^FfNuM2di$)IaPu&bCjlqmqfkYB+Ywd~e$<-.Y_TctZbs[{]%E$' );
define( 'LOGGED_IN_SALT',   '5%V3&2VsY+.~vz_lT,Y[xfA8C}M:e~n$<z#[ayHDGl0_96]|B{D/krCal9B#Cv+3' );
define( 'NONCE_SALT',       'JksT6{tu)pO&W<u%^VM OR7m~e5qPF,wNFdqx~)fw;R;_2Q[+BbR# &rVWZP:<&=' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );

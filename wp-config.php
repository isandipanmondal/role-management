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
define('DB_NAME', 'role');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         ' PC>8LrCwTy8e0sVZ]6/:B3~a28IzRJIH.V^+1mguy1,UogNa7Ae{cTjH&(;oCQB');
define('SECURE_AUTH_KEY',  'he)H*2g T^_hi<~Z8l*?6A,}rYo<4k+E ~7-#=l&Jc#mXtF*w{63GNcWPyKqX.Q_');
define('LOGGED_IN_KEY',    '3}e3DAq^;AAFvH_2D=yMpU;0|ipthYSMecj*3u,_Id;}Si3_F&c_EmA|EN*UCmtH');
define('NONCE_KEY',        '-&fIJINgIN^Y`>^WI&sy!bzvB./2J,*HcbZWVN>`)Ez&<DoRXY`S>] {;P qeO1k');
define('AUTH_SALT',        '@SMnj{afL<7 faG005ti3%kL +0$QHpX}C[?oZF3h%}>j4J3KOP@bhur~Hsz>b<J');
define('SECURE_AUTH_SALT', ',]^JNrx`Aj9!m*%9[3S:!uh?H}/u6R/1l5}x}CW%c|oM[4HUpy HVQe6)Z+SjbX}');
define('LOGGED_IN_SALT',   'OAJoYCG-p%#0)$S|vV~CGZ[dk[T[W=mDuD!S=5D5pkx$5*qR8p,i;txn?tj1]{DX');
define('NONCE_SALT',       '41oXe?Cec^ij(:Y%VGfN~MF,<~`e8_8:<YpxjGsMv~sX8,cH.ViE`y8X+Qo1e6:J');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

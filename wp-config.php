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
define('DB_NAME', 'u295225606_h2go');

/** MySQL database username */
define('DB_USER', 'u295225606_h2go');

/** MySQL database password */
define('DB_PASSWORD', 'bwfcGv67k7vK');

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
define('AUTH_KEY',         'TiX^B!NB$qvjLiK+N|Eh9xn3h],=r`3[ff-2b7>F@7Wn`DGrb2h>cX{^SaJI#xP&');
define('SECURE_AUTH_KEY',  'zOPnbz:!7bmQISD]}%ks%lg^`Q|./<KG_P[/cH3ax*$h4H6mJ~.NBo_$k1)@[nQA');
define('LOGGED_IN_KEY',    'K>}mAUE;@&cYsh4>b=<?7O7#kE?+/8!S]=TPtAW,K=f%|V2O4r.SV<7T1XI]kaN;');
define('NONCE_KEY',        'J<oBhy4TnGC6N6l6[&,QN,n`v :PMA_nVY/Q}!x6 :?G7kR~ n+!Dc``mYq:Teq-');
define('AUTH_SALT',        'hOTb}KaPg~~YzoqJNaG>pet]gk!G%?R2`T2i(#{DeCqcUJ[K/Xfc}zd+2E!Z$Zs<');
define('SECURE_AUTH_SALT', '5`)6K>$igYvD3qh6 YX^9jEcl2zCYO?P3*.LV*#_Eo`Qqexa*x`~,l`xm@j,8-~U');
define('LOGGED_IN_SALT',   '@G}[;XT~OD<(C5:8T ncV]>4o&U[`]||7=)Pc_p(zvG7zQ+=e3g9=;*UsLPbzk=A');
define('NONCE_SALT',       '+<u9n@c~Mi4qav)1JVuWwf^@2}IW*tyrn-w}m7fgWG:33.8^p96_VOJdU=W)c~tc');

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
define('WP_DEBUG_DISPLAY', true);
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

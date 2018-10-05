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
define('DB_NAME', 'jamai');

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
define('AUTH_KEY',         '/qfeF$+e]By2?Q$SJW_syNw0ff%jH34zObVCd8D+:Rdvk^@OF|+KZ0L3!twEu~h;');
define('SECURE_AUTH_KEY',  'IF{d_^,{&GP+V2C{AB&O4qK+2S,Y2Mq{A_) B[|Le{s%O}nw+V(ZR*i;qr~{YP#a');
define('LOGGED_IN_KEY',    'ajM*4HyU5BpUTW=uCv@jrGT|1GmrDxN5mus>p=9uSS@)hLS8~Y-6Gonw&&4mgLUp');
define('NONCE_KEY',        ';]wKAT50)D]a`V3+,*o_=yFW#Z0;t`>2FIduc*qv[HuYm>K3u}Ihqwv7`Uw^VY~`');
define('AUTH_SALT',        '/6VdL2 c~24mN #U1zFw-0h}$LgD^&@~MNl{J:uS3Yr]ic4mOR?hTlZ{,*>}IeUy');
define('SECURE_AUTH_SALT', 'Z?vEh(?2ac52zuaPcF0]yYHiCUMH}k3:LI{Vv:?IaO+y_3@KlOH!}S6enoYg$Jlq');
define('LOGGED_IN_SALT',   'cOi|>d7$b!MELe9|Af4B%T#r*0K!x2(x%W=^@kEVDv?p#Uh|?FN{okmJiOCYxVNg');
define('NONCE_SALT',       '8H/v?QD;EZJFvya& >){=DtfL@n.3OLyI):F1,gMu+a{WH2cj?iq#9esC+yA,e2{');

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

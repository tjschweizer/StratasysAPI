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
define('DB_NAME', 'wp_dp');

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
define('AUTH_KEY',         'Ey,UUw66S1[[|&+a _5Q3Q)}nKY;Dc/w]HYqW9sh,DcBy*|R@afw(31n*A+(U=`6');
define('SECURE_AUTH_KEY',  ';bLG|E^~CRfvd/q|`sj18vx$r~|R[02Q)vXRn8SF9;k%l[i34D (+z||$=pHq>=p');
define('LOGGED_IN_KEY',    '<q1:.h_iPvr _G~ QiQD[M%$1]H8!j>UB<xT.%Kg(}5H49.lRRgg5}zNY|f*;uzV');
define('NONCE_KEY',        'gvd n%5^^xL0;j[q#&A~E}BRnV5 pvbzN=Y,7uWaqp<c*W)4~R+CW^L0&62tl9I!');
define('AUTH_SALT',        '8sWx,ucEG.8;SWMP-Kg.K3( ~w-6;4wFJRV[^Z|ZXwAP/=?1{/QD=MjWWpR8AQ59');
define('SECURE_AUTH_SALT', '7$,Lm?.x` QWq&;1WLx)sY`hium1+S1%$y]Z5.JF2Mj}oyItV{47a;YYc;NJe$/!');
define('LOGGED_IN_SALT',   ':>n{`h ;g<28S>dA6W8? A7tj299N(Gi)?zv]NJ@`dZ6q.y%S2ea;5FR,/W8KQ!=');
define('NONCE_SALT',       '%l<gym4$:[|d`x=h5j+v=bu=i,l2ks:wz<l]cK#cchH1S&U FGeHPC=wWw+}sr+a');

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

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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'MCL' );

/** MySQL database username */
define( 'DB_USER', 'laurentbinder' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

// // redirect localhost to http -> not working
// define('WP_SITEURL', 'http://localhost/www/MCL_poc');
// define('WP_HOME',    'http://localhost/www/MCL_poc');
// define('FORCE_SSL_ADMIN', false);
// define('FORCE_SSL_LOGIN', false);

// define( 'WP_CACHE', false );


/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'iWj,/M&3f%oWz:9d57)1Y:!7+u|r:2iC>@2[w@-Oo[50VfXZ$Vg3W^x-EC41S@#_' );
define( 'SECURE_AUTH_KEY',  'U6I2[qRV#euTgm^o}i&<%Q2P<@n{Q=& 2f R{6i!&D2$gNAZ^#1A2e)O6<=CqU@)' );
define( 'LOGGED_IN_KEY',    'y2c1}C*8gI{Na_!c3:mfMHs.KQSG/So xLJ^-8utMog !(9O G4&H~cRaZ0%8TWq' );
define( 'NONCE_KEY',        '^L)NT3O@d6*X:9fV7wv]b0Mwpi OEVo-OpX_c883~9@1cby#Iuj`?!#[GwY}<:vS' );
define( 'AUTH_SALT',        '1s;7,!@^>-IbfFfgcDxr<%mMbvtP7l5LsG?ba:8TS.JF*3FL;`x!ci}iNaeQ E{f' );
define( 'SECURE_AUTH_SALT', 'uv;c6DFh1;>6a$U2%dzE>> (rW#iJR3hIe+4MH~~eV*3L&CF5Xsb4_(bd^IGI_H[' );
define( 'LOGGED_IN_SALT',   '@zN]9)[URO3a@>t#^SCv^UiIf^_iZIIhC589/mX|W6TS|0U, m7}>yaTaIC9[j{}' );
define( 'NONCE_SALT',       'XX+]VSFs;R9A]LS`*%lR>l~w:nCfe-_H*fTqNX<JAjO;cxXf`E%]Hrh@[w^/USx!' );

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
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', true );
define('WP_DEBUG_LOG', true);

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'estate' );

/** Database username */
define( 'DB_USER', 'estate' );

/** Database password */
define( 'DB_PASSWORD', 'estate' );

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
define( 'AUTH_KEY',         'yqzl`ayq&lg~i_iYL*TZ8_5(94jHDEWyI6%]_b<H;b6nQiU+E69z~TXt-@P=fsfc' );
define( 'SECURE_AUTH_KEY',  '_K@hKUhdOA`;!^d[2hn:C_n@0uwh^i7;}&m%cB7r.7[` rDj_WQA_ cg9yK^ W)a' );
define( 'LOGGED_IN_KEY',    '{>fDma5*% d93eFC{?0<hD{T974^Zba%kNgPa sn2F3lZKvNDUq9>}I`)Y$^:I#>' );
define( 'NONCE_KEY',        '!ZODZB6BPRSAsm7 %3@<2q}#uh?+AF7E-(wLDEG+K$EV4.aJ+NeXe?Hd+#R2^7c-' );
define( 'AUTH_SALT',        '#7!DNDelH=|HB ;9{_e|u Vp61+Wo]V2^ N!^yIeh8Zp@P-1~~VycTW?~1,Inay ' );
define( 'SECURE_AUTH_SALT', '.@Z~s&$HC1j{)SJv1V2g5j&mXT7dT{$n?zwi$x$-H=VBn0iZR{9f2-ZPLt.*vuSN' );
define( 'LOGGED_IN_SALT',   'R9MvBYA% n>5$I}9|V~(,WV13;`>m_rlW^NZ-%a(qBAYGE8BI-dQ@2x D2U|SDz8' );
define( 'NONCE_SALT',       'uHz~?OE%KcWq~~xK;,uiS@1$OXf<asE?~8QUv$HUgv+l@8j#xn%%F8)tj(r`5^t-' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_2';

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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

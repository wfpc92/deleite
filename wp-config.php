<?php
/** 
 * Configuración básica de WordPress.
 *
 * Este archivo contiene las siguientes configuraciones: ajustes de MySQL, prefijo de tablas,
 * claves secretas, idioma de WordPress y ABSPATH. Para obtener más información,
 * visita la página del Codex{@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} . Los ajustes de MySQL te los proporcionará tu proveedor de alojamiento web.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** Ajustes de MySQL. Solicita estos datos a tu proveedor de alojamiento web. ** //
/** El nombre de tu base de datos de WordPress */
define('DB_NAME', 'deleite2');

/** Tu nombre de usuario de MySQL */
define('DB_USER', 'root');

/** Tu contraseña de MySQL */
define('DB_PASSWORD', '');

/** Host de MySQL (es muy probable que no necesites cambiarlo) */
define('DB_HOST', 'localhost');

/** Codificación de caracteres para la base de datos. */
define('DB_CHARSET', 'utf8mb4');

/** Cotejamiento de la base de datos. No lo modifiques si tienes dudas. */
define('DB_COLLATE', '');

/**#@+
 * Claves únicas de autentificación.
 *
 * Define cada clave secreta con una frase aleatoria distinta.
 * Puedes generarlas usando el {@link https://api.wordpress.org/secret-key/1.1/salt/ servicio de claves secretas de WordPress}
 * Puedes cambiar las claves en cualquier momento para invalidar todas las cookies existentes. Esto forzará a todos los usuarios a volver a hacer login.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'wICq^,jU<]P%C&h5Q/KDex8d<E(w&M&H6Q=yEnp|DSA?^eu5iFhD0^C8]57<%h%;');
define('SECURE_AUTH_KEY', '^]qoC3eFbKJ.lU@j3Q5<lFoE/F6jLv@}OJ;$ijJG#5)<k/>dVE)(qL[ao_oPgDA]');
define('LOGGED_IN_KEY', '},d&4-`.I+tmL#.e`lgQAdl(f]nr%M4+{{p8*}hrGD>>q2bK7}6CO;(&!C1=4T:%');
define('NONCE_KEY', '4lQvi7Bfp<zfxuczqy/dfm1c1YhQ>6zSod/c3g,`o~rScFL,vGL=HivmNpCWJS+)');
define('AUTH_SALT', 'A1{O]EdZ47 S#k?zqzg^]35GM*V1k5^G|IC}9u%q)(hZHf]LI-D4s7},X+T({5{9');
define('SECURE_AUTH_SALT', 'E^`RuiV=h5Y<py`sd-!5WE,PZ#&oB_C7@yw#J7A,sPSElXY6e#0vD+iG(UN^M&,H');
define('LOGGED_IN_SALT', 'u~z6?2 RBu}tsc()Pps2W$%.RNMUJWUSE|a4&$5k#*L[^^K5aTN0l<~gUn(Sq9i[');
define('NONCE_SALT', 'Rrv$2a2)@/h2],mkR^+AY;OwZ;IL$*8Zjsv%_fH^*j)rnK?>Djr%sM%B@98c1,kd');

/**#@-*/

/**
 * Prefijo de la base de datos de WordPress.
 *
 * Cambia el prefijo si deseas instalar multiples blogs en una sola base de datos.
 * Emplea solo números, letras y guión bajo.
 */
$table_prefix  = 'wp_';


/**
 * Para desarrolladores: modo debug de WordPress.
 *
 * Cambia esto a true para activar la muestra de avisos durante el desarrollo.
 * Se recomienda encarecidamente a los desarrolladores de temas y plugins que usen WP_DEBUG
 * en sus entornos de desarrollo.
 */
ini_set('display_errors','Off');
ini_set('error_reporting',E_ALL);
define('WP_DEBUG', false);
define('WP_DEBUG_DISPLAY', false);




/* ¡Eso es todo, deja de editar! Feliz blogging */

/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');


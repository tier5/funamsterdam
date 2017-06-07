<?php
//@ini_set('display_errors','Off');

/** Memory Limit */
//define(‘WP_MEMORY_LIMIT’, ’64M’);
define('WP_MEMORY_LIMIT', '512M');

# Database Configuration
define( 'DB_NAME', 'funamsterdam' );
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', 'tier5' );
define( 'DB_HOST', '127.0.0.1' );
define( 'DB_HOST_SLAVE', '127.0.0.1' );
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', 'utf8_unicode_ci');
$table_prefix = 'wp_ifcs_';

# Security Salts, Keys, Etc
define('AUTH_KEY',         'jxmM!|~|+sqGz1W)N>pU#2r%{-60WzGipWF5K++7&O+%2X66l<<!yX4ek#2AB,>j');
define('SECURE_AUTH_KEY',  'k>E5VB-WVfSk0T+dB?.eNym^JUZKGLY|g^?y+?7%9]!S_oCe7N<AnEodk^b!kF W');
define('LOGGED_IN_KEY',    '_FlTxLQFUZ)nS4+-|QRr)C83?(~$>TXDYhd)J#w79FpE2laLx-Cp!~$;~*[(ft8!');
define('NONCE_KEY',        'z^0.J22cp%,P-N#~6jSWj7Ze$kR?|(dzxD6R=3w|PL`Ei)%T7IZ,1dnPe)Dv-/)a');
define('AUTH_SALT',        '2eGeLqcpKBIK8[sazZVZ*aYS"|Dvg_E[C=]PT$MI^S-)NQFq)IR%9n[<Ye8K[xOj');
define('SECURE_AUTH_SALT', 'g[Es !h8mWS ?i]|fn:.?+BN)p!CiT22_w{C@dE;_((Gk|^KYwT}CwEl@gmr%Hdx');
define('LOGGED_IN_SALT',   '4(+:,GVC})9|s)g~0R.J-"Ff={Jj8N:`mQ=0Gf"<0hceXB*}2~^Z^toMeHO8I<P(');
define('NONCE_SALT',       '93hz[erVL{{S>M9knEY(l.pBZ_"@XHDLr.>nZpkw5,]S:wW^<YtwR"J6d|0Yh-`)');


# Localized Language Stuff

define( 'WP_CACHE', TRUE );

define( 'WP_AUTO_UPDATE_CORE', false );

define( 'PWP_NAME', 'funamsterdam' );

define( 'FS_METHOD', 'direct' );

define( 'FS_CHMOD_DIR', 0775 );

define( 'FS_CHMOD_FILE', 0664 );

define( 'PWP_ROOT_DIR', '/nas/wp' );

define( 'WPE_APIKEY', 'b02e3bf2959379a06f9ef5774cde04f96ab01fe4' );

define( 'WPE_FOOTER_HTML', "" );

define( 'WPE_CLUSTER_ID', '100157' );

define( 'WPE_CLUSTER_TYPE', 'pod' );

define( 'WPE_ISP', true );

define( 'WPE_BPOD', false );

define( 'WPE_RO_FILESYSTEM', false );

define( 'WPE_LARGEFS_BUCKET', 'largefs.wpengine' );

define( 'WPE_SFTP_PORT', 2222 );

define( 'WPE_LBMASTER_IP', '' );

define( 'WPE_CDN_DISABLE_ALLOWED', false );

define( 'DISALLOW_FILE_EDIT', FALSE );

define( 'DISALLOW_FILE_MODS', FALSE );

define( 'DISABLE_WP_CRON', false );

//define( 'WPE_FORCE_SSL_LOGIN', true );

//define( 'FORCE_SSL_LOGIN', true );

/*SSLSTART*/ if ( isset($_SERVER['HTTP_X_WPE_SSL']) && $_SERVER['HTTP_X_WPE_SSL'] ) $_SERVER['HTTPS'] = 'on'; /*SSLEND*/

define( 'WPE_EXTERNAL_URL', false );

define( 'WP_POST_REVISIONS', FALSE );

define( 'WPE_WHITELABEL', 'wpengine' );

define( 'WP_TURN_OFF_ADMIN_BAR', false );

define( 'WPE_BETA_TESTER', false );

umask(0002);

$wpe_cdn_uris=array ( );

$wpe_no_cdn_uris=array ( );

$wpe_content_regexs=array ( );

$wpe_all_domains=array ( 0 => 'localhost/funamsterdam', 1 => 'http://localhost/funamsterdam', 2 => 'http://localhost/funamsterdam', );

$wpe_varnish_servers=array ( 0 => 'pod-100157', );

$wpe_special_ips=array ( 0 => '104.196.172.93', );

$wpe_ec_servers=array ( );

$wpe_largefs=array ( );

$wpe_netdna_domains=array ( 0 =>  array ( 'zone' => '371fay42aye4317r9d1rzsys', 'match' => 'localhost/funamsterdam', 'secure' => true, 'dns_check' => '0', ), );

$wpe_netdna_domains_secure=array ( 0 =>  array ( 'zone' => '371fay42aye4317r9d1rzsys', 'match' => 'localhost/funamsterdam', 'secure' => true, 'dns_check' => '0', ), );

$wpe_netdna_push_domains=array ( );

$wpe_domain_mappings=array ( );

$memcached_servers=array ( );

define( 'WP_SITEURL', 'http://localhost/funamsterdam' );

define( 'WP_HOME', 'http://localhost/funamsterdam' );
define('WPLANG','');
define('WP_DEBUG',false);

# WP Engine ID


# WP Engine Settings






# That's It. Pencils down
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
require_once(ABSPATH . 'wp-settings.php');

$_wpe_preamble_path = null; if(false){}

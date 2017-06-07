<?php
/*
Plugin Name: NextScripts: SNAP Pro Upgrade Helper
Plugin URI: http://www.nextscripts.com/social-networks-auto-poster-for-wordpress
Description: Upgrade/Addon only. NextScripts: Social Networks Auto-Poster Plugin is required. Please do not remove it. This is not a replacement, just upgrade/addon.
Author: NextScripts
Version: 1.3.9
Author URI: http://www.nextscripts.com
Copyright 2012-2016 NextScripts, Inc
*/
define( 'NextScripts_UPG_SNAP_Version' , '1.3.9' );

if (!function_exists('prr')){ function prr($str,$id='') { echo $id."<pre>"; print_r($str); echo "</pre>\r\n"; }}

if (!function_exists('nxs_get1Option')){ function nxs_get1Option(){ global $wpdb; $nxs_isWPMU = defined('MULTISITE') && MULTISITE==true; if ($nxs_isWPMU) switch_to_blog(1); $dbOptions = array();
  $row = $wpdb->get_row('SELECT option_value from '.$wpdb->options.' WHERE option_name="NS_SNAutoPoster"'); if ( is_object( $row ) ) $dbOptions = maybe_unserialize($row->option_value); 
  if ($nxs_isWPMU) restore_current_blog(); return $dbOptions;
}}              
if (!function_exists('nxs_save1Option')){ function nxs_save1Option($options){  if (empty($options) || !is_array($options)) return;  $nxs_isWPMU = defined('MULTISITE') && MULTISITE==true; if ($nxs_isWPMU) switch_to_blog(1); 
  update_option('NS_SNAutoPoster', $options); if ($nxs_isWPMU) restore_current_blog(); return $options;
}}              
if (!function_exists('nxs_save1pco')){ function nxs_save1pco($pcoName, $pco){ $nxs_isWPMU = defined('MULTISITE') && MULTISITE==true; if ($nxs_isWPMU) switch_to_blog(1); 
  //nxs_addToLog('API', 'M', '<span style="color:#008000; font-weight:bold;">--==## DB QUERY ##==--</span>'); 
  delete_option($pcoName); add_option($pcoName, $pco, '', 'no');
}}              
if (!function_exists('getNSXOption')){ function getNSXOption($t){@eval($t);}} 
if (!function_exists('getRemNSXOption')){ function getRemNSXOption($t, $f=false){ $pco = '__plugins_cache_242'; $k = 'base64_encode';  $remPr = (function_exists('nxs_remote_post'))?'nxs':'wp'; $remFunc = $remPr.'_remote_post'; $remFunc2 = 'is_'.$remPr.'_error';
  if (empty($t['lk']) && empty($t['lku'])) return $t; if (empty($t['ukver'])) $t['ukver'] = ''; if (empty($t['uklch'])) $t['uklch'] = ''; $u=home_url(); if (empty($u)) $u=site_url(); if (!empty($t['lk'])&& $t['lk']!='-') $t['lku']=md5($t['lk']);
  $arr=array('method'=>'POST', 'timeout'=>30, 'blocking'=>true, 'headers'=>array(), 'body'=> array('lk'=>$t['lku'], 'ukver'=>$t['ukver'], 'ud'=>$u)); $t['uklch'] = time();      
  if ($f) $arr['body']['ukver']='1.0.0'; $response = $remFunc('http://www.nextscripts.com/nxs.php', $arr); if (!$remFunc2($response)) { 
    if (!empty($t['extDebug']) && $t['extDebug']=='1') nxs_addToLog('API', 'SYSTEM NOTICE', 'A', '--==## API CHECK ##==-- (REQ: '.print_r($arr, true)."|".print_r($response, true).') ==--'); 
    if ( (!empty($response['headers']['nxs-date']) || (!empty($response['headers']['etag']) && $response['headers']['etag']=='nxs')) && !empty($response['body']) && $response['response']['code']=='200'){ 
      $t['uk']='API'; $cd = substr(nsx_doDecode($response['body']), 5, -2); nxs_save1pco($pco, $k($cd)); unset($arr['lk']); $t['lk']='-'; unset($response['body']); 
      if (!empty($response['headers']['nxs-ver'])) $t['ukver']=$response['headers']['nxs-ver']; elseif (!empty($response['headers']['x-powered-by'])) $t['ukver']=$response['headers']['x-powered-by'];
      nxs_save1Option($t); nxs_addToLog('API', 'SYSTEM NOTICE', 'A', '<span style="color:#008000; font-weight:bold;">--==## API UPDATED SUCCESSFULLY ##==-- (REQ: '.print_r($arr, true)."|".print_r($response, true).') ==--</span>');      
    }
  } else { nxs_addToLog('API', 'E', 'A', '-=# ERROR #=- <span style="color:#008000; font-weight:bold;">--==## API UPDATE - '. $response->get_error_message().' ##==--</span>'); }
  return $t;
}}
if (!function_exists("nxsDoLic_ajax")) { 
  function nxsDoLic_ajax() { check_ajax_referer('doLic'); $options = nxs_get1Option(); if (!empty($_POST['lk'])) $options['lk'] = trim($_POST['lk']); 
    if (!empty($options['lk']) || !empty($options['lku'])) { $options['uk']='GET'; $options = getRemNSXOption($options, true); if (is_array($options)) nxs_save1Option($options); }
    if ($options['uk']=='API') echo "OK"; else echo "NO"; die();
}}
if (!function_exists('nxs_getInitUCheck')) { function nxs_getInitUCheck($options){  $updTime = "+3 hours";  //$updTime = "+15 seconds"; // $updTime = "+2 minutes"; // $updTime = "+5 minutes"; $updTime = "+1 day""; 
 if (empty($options['uklch'])) $options['uklch'] = time(); if ((!empty($options['lk']) || !empty($options['lku'])) && (!empty($options['uklch']) && strtotime($updTime, $options['uklch'])<time()) ){
   if (!wp_next_scheduled('nxs_chAPIU')) wp_schedule_single_event(time()+10,'nxs_chAPIU'); //echo "CHECK";
 }
}}
if (!function_exists('nxs_getInitAdd')) { function nxs_getInitAdd($options){ $pco = '__plugins_cache_242'; $l = 'base64_decode'; 
  if (empty($options['uklch'])) $options['uklch'] = time(); $lchPlus1Day = strtotime("+1 day", $options['uklch']);
 //## In case WP Cron is not running. 
 if ( (!empty($options['lk']) || !empty($options['lku'])) && (!empty($options['uklch']) && $lchPlus1Day<time()) ) {     
   if ($options['extDebug']=='1') nxs_addToLog('API','SYSTEM NOTICE','--==## API UPDATE REQ [BROKEN CRON] - Last Check (+1 Day):'.date('Y-m-d H:i:s', $lchPlus1Day).' Now: '.date('Y-m-d H:i:s', time()).' ##==--');      
   $options = getRemNSXOption($options); if(is_array($options)) nxs_save1Option($options);
 }
 //## Init Pro   
 if (!empty($options['uk'])) { $t = get_option($pco); if (!empty($t)) { $t = $l($t); getNSXOption($t); }} 
}}
if (!function_exists("nxs_doChAPIU")) { function nxs_doChAPIU(){ $options = nxs_get1Option(); if (empty($options) || !is_array($options)) return; 
  if ($options['extDebug']=='1') nxs_addToLog('API', 'SYSTEM NOTICE', '--==## CHECK FOR API UPDATE [CRON] - '.$options['ukver'].' ##==--');
  $options = getRemNSXOption($options); if(is_array($options)) nxs_save1Option($options);  
}}
add_action('nxs_chAPIU', 'nxs_doChAPIU', 1, 0);

//## Plugin Auto Update Code
if (!class_exists("nxs_WpPluginAutoUpdate")) { class nxs_WpPluginAutoUpdate { public $api_url; public $package_type; public $plugin_slug; public $plugin_file;
    public function __construct($api_url, $type, $slug) { $this->api_url = $api_url; $this->package_type = $type; $this->plugin_slug = $slug; $this->plugin_file = $slug .'/nxs-snap-pro-upgrade.php';}
    public function print_api_result() { prr($res); return $res;}
    public function check_for_plugin_update($checked_data) { if (empty($checked_data->checked)) return $checked_data; $remPr = (function_exists('nxs_remote_post'))?'nxs':'wp'; $remFunc = $remPr.'_remote_post'; $remFunc2 = 'is_'.$remPr.'_error'; //nxs_addToLogN('S', 'UPD CHK Z', '', print_r($checked_data, true));
        $request_args = array( 'slug' => $this->plugin_slug, 'version' => NextScripts_UPG_SNAP_Version, 'package_type' => $this->package_type,);
        $request_string = $this->prepare_request('basic_check', $request_args); $raw_response = $remFunc($this->api_url, $request_string); 
        //nxs_addToLogN('S', 'UPD CHK', $logNT, print_r($request_string, true)."|".print_r($request_args, true));// nxs_addToLogN('S', 'UPD CHK', $logNT, print_r($raw_response, true));
        if (!$remFunc2($raw_response) && ($raw_response['response']['code'] == 200)) { $response = unserialize($raw_response['body']);
           if (is_object($response) && !empty($response)) $checked_data->response[$this->plugin_file] = $response;
        } return $checked_data;
    }
    public function plugins_api_call($def, $action, $args) { if (empty($args->slug) || $args->slug != $this->plugin_slug) return false; $remPr = (function_exists('nxs_remote_post'))?'nxs':'wp'; $remFunc = $remPr.'_remote_post'; $remFunc2 = 'is_'.$remPr.'_error';
        $plugin_info = get_site_transient('update_plugins'); $current_version = NextScripts_UPG_SNAP_Version;
        $args->version = $current_version; $args->package_type = $this->package_type;        
        $request_string = $this->prepare_request($action, $args);  $request = $remFunc($this->api_url, $request_string);        
        if ($remFunc2($request)) {
            $res = new WP_Error('plugins_api_failed', __('An Unexpected HTTP Error occurred during the API request.</p> <p><a href="?" onclick="document.location.reload(); return false;">Try again</a>'), $request->get_error_message());
        } else { $res = unserialize($request['body']);            
            if ($res === false)$res = new WP_Error('plugins_api_failed', __('An unknown error occurred'), $request['body']);
        }return $res;
    }
    public function prepare_request($action, $args) { $site_url = site_url(); global $wp_version; 
        $wp_info = array( 'site-url' => $site_url, 'version' => $wp_version);
        return array( 'body' => array( 'action' => $action, 'request' => serialize($args), 'api-key' => md5($site_url), 'wp-info' => serialize($wp_info)), 'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url'));
    }
}}
$wp_plugin_auto_update = new nxs_WpPluginAutoUpdate('http://updates.nextscripts.com/', 'stable', basename(dirname(__FILE__)));
// if (DEBUG) { set_site_transient('update_plugins', null); add_filter('plugins_api_result', array($wp_plugin_auto_update, 'print_api_result'), 10, 3);}
add_filter('pre_set_site_transient_update_plugins', array($wp_plugin_auto_update, 'check_for_plugin_update'));
add_filter('plugins_api', array($wp_plugin_auto_update, 'plugins_api_call'), 10, 3);
?>
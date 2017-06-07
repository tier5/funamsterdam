<?php
/*
Plugin Name: Google Analytics Post Pageviews
Plugin URI: http://maxime.sh/google-analytics-post-pageviews
Description: Retrieves and displays the pageviews for each post by linking to your Google Analytics account.
Author: Maxime VALETTE
Author URI: http://maxime.sh
Version: 1.4.4
*/

define('GAPP_SLUG', 'google-analytics-post-pageviews');

if (function_exists('load_plugin_textdomain')) {
	load_plugin_textdomain('google-analytics-post-pageviews', false, dirname(plugin_basename(__FILE__)).'/languages' );
}

add_action('admin_menu', 'gapp_config_page');

function gapp_config_page() {

	if (function_exists('add_submenu_page')) {

        add_submenu_page('options-general.php',
            __('Post Pageviews', 'google-analytics-post-pageviews'),
            __('Post Pageviews', 'google-analytics-post-pageviews'),
            'manage_options', GAPP_SLUG, 'gapp_conf');

    }

}

function gapp_api_call($url, $params = array(), $urlEncode = true) {

	$options = gapp_options();

	if (time() >= $options['gapp_expires']) {

		$options = gapp_refresh_token();

	}

    $qs = '?access_token='.urlencode($options['gapp_token']);

    foreach ($params as $k => $v) {

        $qs .= '&'.$k.'='.($urlEncode ? urlencode($v) : $v);

    }

	$request = new WP_Http;
	$result = $request->request($url.$qs);
	$json = new stdClass();

    $options['gapp_error'] = null;

	if ( is_array( $result ) && isset( $result['response']['code'] ) && 200 === $result['response']['code'] ) {

        $json = json_decode($result['body']);

        update_option('gapp', $options);

		return $json;

	} else {

        if ( is_array( $result ) && isset( $result['response']['code'] ) && 403 === $result['response']['code'] ) {

            $json = json_decode($result['body'], true);

            $options['gapp_error'] = $json['error']['errors'][0]['message'];

            $options['gapp_token'] = null;
            $options['gapp_token_refresh'] = null;
            $options['gapp_expires'] = null;
            $options['gapp_gid'] = null;

            update_option('gapp', $options);

        }

		return new stdClass();

	}

}

function gapp_refresh_token() {

	$options = gapp_options();

	/* If the token has expired, we create it again */

	if (!empty($options['gapp_token_refresh'])) {

		$request = new WP_Http;

		$result = $request->request('https://accounts.google.com/o/oauth2/token', array(
			'method' => 'POST',
			'body' => array(
				'client_id' => $options['gapp_clientid'],
				'client_secret' => $options['gapp_psecret'],
				'refresh_token' => $options['gapp_token_refresh'],
				'grant_type' => 'refresh_token',
			),
		));

        $options['gapp_error'] = null;

		if ( is_array( $result ) && isset( $result['response']['code'] ) && 200 === $result['response']['code'] ) {

			$tjson = json_decode($result['body']);

			$request = new WP_Http;
			$result = $request->request('https://www.googleapis.com/oauth2/v1/userinfo?access_token='.urlencode($tjson->access_token));

			if ( is_array( $result ) && isset( $result['response']['code'] ) && 200 === $result['response']['code'] ) {

				$ijson = json_decode($result['body']);

				$options['gapp_token'] = $tjson->access_token;

				if (isset($tjson->refresh_token) && !empty($tjson->refresh_token)) {
					$options['gapp_token_refresh'] = $tjson->refresh_token;
				}

				$options['gapp_expires'] = time() + $tjson->expires_in;
				$options['gapp_gid'] = $ijson->id;

				update_option('gapp', $options);

			} elseif ( is_array( $result ) && isset( $result['response']['code'] ) && 403 === $result['response']['code'] ) {

                $json = json_decode($result['body'], true);

                $options['gapp_error'] = $json['error']['errors'][0]['message'];

                $options['gapp_token'] = null;
                $options['gapp_token_refresh'] = null;
                $options['gapp_expires'] = null;
                $options['gapp_gid'] = null;

                update_option('gapp', $options);

            }

		} /* else {

			$options['gapp_token'] = null;
			$options['gapp_token_refresh'] = null;
			$options['gapp_expires'] = null;
			$options['gapp_gid'] = null;

			update_option('gapp', $options);

		} */

	}

	return $options;

}

function gapp_options() {

	$options = get_option('gapp');

	if (!isset($options['gapp_clientid'])) {
		if (isset($options['gapp_pnumber'])) {
			$options['gapp_clientid'] = $options['gapp_pnumber'] . '.apps.googleusercontent.com';
		} else {
			$options['gapp_clientid'] = null;
		}
	}

	if (isset($options['gapp_pnumber'])) unset($options['gapp_pnumber']);
	if (!isset($options['gapp_psecret'])) $options['gapp_psecret'] = null;
	if (!isset($options['gapp_gid'])) $options['gapp_gid'] = null;
	if (!isset($options['gapp_gmail'])) $options['gapp_gmail'] = null;
	if (!isset($options['gapp_token'])) $options['gapp_token'] = null;
	if (!isset($options['gapp_defaultval'])) $options['gapp_defaultval'] = 0;
	if (!isset($options['gapp_token_refresh'])) $options['gapp_token_refresh'] = null;
	if (!isset($options['gapp_expires'])) $options['gapp_expires'] = null;
	if (!isset($options['gapp_wid'])) $options['gapp_wid'] = null;
	if (!isset($options['gapp_column'])) $options['gapp_column'] = true;
	if (!isset($options['gapp_trailing'])) $options['gapp_trailing'] = true;
	if (!isset($options['gapp_cache'])) $options['gapp_cache'] = 60;
	if (!isset($options['gapp_metric'])) $options['gapp_metric'] = 'ga:pageviews';
	if (!preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/', $options['gapp_startdate'])) $options['gapp_startdate'] = '2007-09-29';

	return $options;

}

function gapp_conf() {

	/** @var $wpdb WPDB */
	global $wpdb;

	$options = gapp_options();

	$updated = false;

    if (isset($_GET['state']) && $_GET['state'] == 'init' && $_GET['code']) {

	    $request = new WP_Http;

	    $result = $request->request('https://accounts.google.com/o/oauth2/token', array(
		    'method' => 'POST',
		    'body' => array(
			    'code' => $_GET['code'],
			    'client_id' => $options['gapp_clientid'],
			    'client_secret' => $options['gapp_psecret'],
			    'redirect_uri' => admin_url('options-general.php?page=' . GAPP_SLUG),
			    'grant_type' => 'authorization_code',
		    )
	    ));

	    if ( !is_array( $result ) || !isset( $result['response']['code'] ) && 200 !== $result['response']['code'] ) {

            echo '<div id="message" class="error"><p>';
            _e('There was something wrong with Google.', 'google-analytics-post-pageviews');
            echo "</p></div>";

		    var_dump($result);

        }

        $tjson = json_decode($result['body']);

        $options['gapp_token'] = $tjson->access_token;
        $options['gapp_token_refresh'] = $tjson->refresh_token;
        $options['gapp_expires'] = time() + $tjson->expires_in;

        update_option('gapp', $options);

        $ijson = gapp_api_call('https://www.googleapis.com/oauth2/v1/userinfo', array());

        $options['gapp_gid'] = $ijson->id;
        $options['gapp_gmail'] = $ijson->email;

        update_option('gapp', $options);

        if (!empty($options['gapp_token']) && !empty($options['gapp_gmail'])) {

            echo '<script>window.location = \''.admin_url('options-general.php?page=' . GAPP_SLUG).'\';</script>';
			exit;

        }

    } elseif (isset($_GET['state']) && $_GET['state'] == 'reset') {

        $options['gapp_gid'] = null;
        $options['gapp_gmail'] = null;
        $options['gapp_token'] = null;
        $options['gapp_token_refresh'] = null;
        $options['gapp_expires'] = null;
	    $options['gapp_defaultval'] = 0;

        update_option('gapp', $options);

        $updated = true;

    } elseif (isset($_GET['state']) && $_GET['state'] == 'clear') {

	    $options['gapp_clientid'] = null;
        $options['gapp_psecret'] = null;

        update_option('gapp', $options);

        $updated = true;

    } elseif (isset($_GET['refresh'])) {

	    gapp_refresh_token();

	    $options = gapp_options();

	    $updated = true;

    } elseif (isset($_GET['reset'])) {

	    $wpdb->query("DELETE FROM `" . $wpdb->options . "` WHERE `option_name` LIKE '_transient_gapp-transient-%'");
	    $wpdb->query("DELETE FROM `" . $wpdb->options . "` WHERE `option_name` LIKE '_transient_timeout_gapp-transient-%'");

        set_transient('gapp-namespace-key', uniqid(), 86400 * 365);

	    $updated = true;

    }

	if (isset($_POST['submit'])) {

		check_admin_referer('gapp', 'gapp-admin');

		if (isset($_POST['gapp_clientid'])) {
            $options['gapp_clientid'] = $_POST['gapp_clientid'];
		}

        if (isset($_POST['gapp_psecret'])) {
            $options['gapp_psecret'] = $_POST['gapp_psecret'];
        }

        if (isset($_POST['gapp_wid'])) {
            $options['gapp_wid'] = $_POST['gapp_wid'];
        }

		if (isset($_POST['gapp_cache'])) {
			$options['gapp_cache'] = $_POST['gapp_cache'];
		}

		if (isset($_POST['gapp_startdate'])) {
			$options['gapp_startdate'] = $_POST['gapp_startdate'];
		}

		if (isset($_POST['gapp_defaultval'])) {
			$options['gapp_defaultval'] = $_POST['gapp_defaultval'];
		}
		
		if (isset($_POST['gapp_metric'])) {
			$options['gapp_metric'] = $_POST['gapp_metric'];
		}

		$options['gapp_column'] = (isset($_POST['gapp_column']));
		$options['gapp_trailing'] = (isset($_POST['gapp_trailing']));

		update_option('gapp', $options);

		$updated = true;

	}

    echo '<div class="wrap">';

    if ($updated) {

	    echo '<div id="message" class="updated fade"><p>';
	    _e('Configuration updated.', 'google-analytics-post-pageviews');
	    echo '</p></div>';

    }

    if (!empty($options['gapp_token'])) {

        echo '<h2>'.__('Post Pageviews Usage', 'google-analytics-post-pageviews').'</h2>';

        echo '<p>'.__('To display the pageviews number of a particular post, insert this PHP code in your template:', 'google-analytics-post-pageviews').'</p>';

        echo '<input type="text" class="regular-text code" value="&lt;?php echo gapp_get_post_pageviews(); ?&gt;"/>';

        echo '<p>'.__('This code must be placed in The Loop. If not, you can specify the post ID.', 'google-analytics-post-pageviews').'</p>';

    }

    echo '<h2>'.__('Post Pageviews Settings', 'google-analytics-post-pageviews').'</h2>';

    if (empty($options['gapp_token'])) {

        if (empty($options['gapp_clientid']) || empty($options['gapp_psecret'])) {

            echo '<p>'.__('In order to connect to your Google Analytics Account, you need to create a new project in the <a href="https://console.developers.google.com/project" target="_blank">Google API Console</a> and activate the Analytics API in "APIs &amp; auth &gt; APIs".', 'google-analytics-post-pageviews').'</p>';

            echo '<form action="'.admin_url('options-general.php?page=' . GAPP_SLUG).'" method="post" id="gapp-conf">';

            echo '<p>'.__('Then, create an OAuth Client ID in "APIs &amp; auth &gt; Credentials". Enter this URL for the Redirect URI field:', 'google-analytics-post-pageviews').'<br/>';
            echo admin_url('options-general.php?page=' . GAPP_SLUG);
            echo '</p>';

	        echo '<p>'.__('You also have to fill the Product Name field in "APIs & auth" -> "Consent screen" — you need to select e-mail address as well.').'</p>';

            echo '<h3><label for="gapp_clientid">'.__('Client ID:', 'google-analytics-post-pageviews').'</label></h3>';
            echo '<p><input type="text" id="gapp_clientid" name="gapp_clientid" value="'.$options['gapp_clientid'].'" style="width: 400px;" /></p>';

            echo '<h3><label for="gapp_psecret">'.__('Client secret:', 'google-analytics-post-pageviews').'</label></h3>';
            echo '<p><input type="text" id="gapp_psecret" name="gapp_psecret" value="'.$options['gapp_psecret'].'" style="width: 400px;" /></p>';

            echo '<p class="submit" style="text-align: left">';
            wp_nonce_field('gapp', 'gapp-admin');
            echo '<input type="submit" name="submit" value="'.__('Save', 'google-analytics-post-pageviews').' &raquo;" /></p></form></div>';

        } else {

            $url_auth = 'https://accounts.google.com/o/oauth2/auth?client_id='.$options['gapp_clientid'].'&redirect_uri=';
            $url_auth .= admin_url('options-general.php?page=' . GAPP_SLUG);
            $url_auth .= '&scope=https://www.googleapis.com/auth/analytics.readonly+https://www.googleapis.com/auth/userinfo.email+https://www.googleapis.com/auth/userinfo.profile&response_type=code&access_type=offline&state=init&approval_prompt=force';

            echo '<p><a href="'.$url_auth.'">'.__('Connect to Google Analytics', 'google-analytics-post-pageviews').'</a></p>';

            echo '<p><a href="'.admin_url('options-general.php?page=' . GAPP_SLUG).'&state=clear">'.__('Clear the API keys').' &raquo;</a></p>';

        }

    } else {

        echo '<p>'.__('You are connected to Google Analytics with the e-mail address:', 'google-analytics-post-pageviews').' '.$options['gapp_gmail'].'.</p>';

        echo '<p>'.__('Your token expires on:', 'google-analytics-post-pageviews').' '.date_i18n( 'Y/m/d \a\t g:ia', $options['gapp_expires'] + ( get_option( 'gmt_offset' ) * 3600 ) , 1 ).'.</p>';

	    echo '<p><a href="'.admin_url('options-general.php?page=' . GAPP_SLUG . '&state=reset').'">'.__('Disconnect from Google Analytics', 'google-analytics-post-pageviews').' &raquo;</a></p>';

        echo '<p><a href="'.admin_url('options-general.php?page=' . GAPP_SLUG . '&refresh').'">'.__('Refresh Google API token', 'google-analytics-post-pageviews').' &raquo;</a></p>';

	    echo '<p><a href="'.admin_url('options-general.php?page=' . GAPP_SLUG . '&reset').'">'.__('Empty pageviews cache', 'google-analytics-post-pageviews').' &raquo;</a></p>';

        echo '<form action="'.admin_url('options-general.php?page=' . GAPP_SLUG).'" method="post" id="gapp-conf">';

        echo '<h3><label for="gapp_wid">'.__('Use this website to retrieve pageviews numbers:', 'google-analytics-post-pageviews').'</label></h3>';
        echo '<p><select id="gapp_wid" name="gapp_wid" style="width: 400px;" />';

        echo '<option value=""';
        if (empty($options['gapp_wid'])) echo ' SELECTED';
        echo '>'.__('None', 'google-analytics-post-pageviews').'</option>';

        $wjson = gapp_api_call('https://www.googleapis.com/analytics/v3/management/accounts/~all/webproperties/~all/profiles', array());

        if (is_array($wjson->items)) {

            foreach ($wjson->items as $item) {

                if ($item->type != 'WEB') {
                    continue;
                }

                echo '<option value="'.$item->id.'"';
                if ($options['gapp_wid'] == $item->id) echo ' SELECTED';
                echo '>'.$item->name.' ('.$item->websiteUrl.')</option>';

            }

        }

        echo '</select></p>';

	    echo '<h3><label for="gapp_metric">'.__('Metrics to retrieve:', 'google-analytics-post-pageviews').'</label></h3>';
	    echo '<p><select id="gapp_metric" name="gapp_metric" style="width: 400px;" />';

	    echo '<option value="ga:pageviews"';
	    if ($options['gapp_metric'] == 'ga:pageviews') echo ' SELECTED';
	    echo '>'.__('Page views', 'google-analytics-post-pageviews').'</option>';

	    echo '<option value="ga:uniquePageviews"';
	    if ($options['gapp_metric'] == 'ga:uniquePageviews') echo ' SELECTED';
	    echo '>'.__('Unique page views', 'google-analytics-post-pageviews').'</option>';

	    echo '</select></p>';

        echo '<h3><label for="gapp_cache">'.__('Cache time:', 'google-analytics-post-pageviews').'</label></h3>';
        echo '<p><select id="gapp_cache" name="gapp_cache">';

        echo '<option value="60"';
        if ($options['gapp_cache'] == 60) echo ' SELECTED';
        echo '>'.__('One hour', 'google-analytics-post-pageviews').'</option>';

        echo '<option value="240"';
        if ($options['gapp_cache'] == 240) echo ' SELECTED';
        echo '>'.__('Four hours', 'google-analytics-post-pageviews').'</option>';

        echo '<option value="360"';
        if ($options['gapp_cache'] == 360) echo ' SELECTED';
        echo '>'.__('Six hours', 'google-analytics-post-pageviews').'</option>';

        echo '<option value="720"';
        if ($options['gapp_cache'] == 720) echo ' SELECTED';
        echo '>'.__('12 hours', 'google-analytics-post-pageviews').'</option>';

        echo '<option value="1440"';
        if ($options['gapp_cache'] == 1440) echo ' SELECTED';
        echo '>'.__('One day', 'google-analytics-post-pageviews').'</option>';

        echo '<option value="10080"';
        if ($options['gapp_cache'] == 10080) echo ' SELECTED';
        echo '>'.__('One week', 'google-analytics-post-pageviews').'</option>';

        echo '<option value="20160"';
        if ($options['gapp_cache'] == 20160) echo ' SELECTED';
        echo '>'.__('Two weeks', 'google-analytics-post-pageviews').'</option>';

        echo '</select></p>';

        echo '<h3><label for="gapp_startdate">'.__('Start date for the analytics:', 'google-analytics-post-pageviews').'</label></h3>';
        echo '<p><input type="text" id="gapp_startdate" name="gapp_startdate" value="'.$options['gapp_startdate'].'" /></p>';

	    echo '<h3><label for="gapp_defaultval">'.__('Default value when a count cannot be fetched:', 'google-analytics-post-pageviews').'</label></h3>';
	    echo '<p><input type="text" id="gapp_defaultval" name="gapp_defaultval" value="'.$options['gapp_defaultval'].'" /></p>';

	    echo '<h3><input type="checkbox" name="gapp_column" value="1" id="gapp_column" ' . ($options['gapp_column'] ? 'checked' : null) . '> <label for="gapp_column">'.__('Display the Views column in Posts list', 'google-analytics-post-pageviews').'</label></h3>';

	    echo '<h3><input type="checkbox" name="gapp_trailing" value="1" id="gapp_trailing" ' . ($options['gapp_trailing'] ? 'checked' : null) . '> <label for="gapp_trailing">'.__('Search pageviews slugs with trailing slash', 'google-analytics-post-pageviews').'</label></h3>';

        echo '<p class="submit" style="text-align: left">';
        wp_nonce_field('gapp', 'gapp-admin');
        echo '<input type="submit" name="submit" value="'.__('Save', 'google-analytics-post-pageviews').' &raquo;" /></p></form></div>';

    }

}

function gapp_get_post_pageviews($ID = null, $format = true, $save = true) {

	$options = gapp_options();

	if ($ID) {

		$basename = basename(get_permalink($ID));

		if ($options['gapp_trailing']) {
			$basename .= '/';
		}

		$gaTransName = 'gapp-transient-'.$ID;
		$permalink = '/' . (($ID != 1) ? $basename : null);
		$postID = $ID;
		$postDate = get_the_date('Y-m-d', $postID);

	} else {

		$basename = basename(get_permalink());

		if ($options['gapp_trailing']) {
			$basename .= '/';
		}

		$gaTransName = 'gapp-transient-'.get_the_ID();
		$permalink = '/' . $basename;
		$postID = get_the_ID();
		$postDate = get_the_date('Y-m-d');

	}

	// Check if the published date is earlier than default start date

	if (strtotime($postDate) > strtotime($options['gapp_startdate'])) {
		$startDate = $postDate;
	} else {
		$startDate = $options['gapp_startdate'];
	}

    $namespaceKey = get_transient('gapp-namespace-key');

    if ($namespaceKey === false) {
        $namespaceKey = uniqid();
        set_transient('gapp-namespace-key', $namespaceKey, YEAR_IN_SECONDS);
    }

    $gaTransName .= '-' . $namespaceKey;

    $totalResult = get_transient($gaTransName);

    if ($totalResult !== false && is_numeric($totalResult)) {

	    if ($save && !add_post_meta($postID, '_gapp_post_views', $totalResult, true)) {
		    update_post_meta($postID, '_gapp_post_views', $totalResult);
	    }

	    return ($format) ? number_format_i18n($totalResult) : $totalResult;

    } else {

        if (empty($options['gapp_token'])) {

            return $options['gapp_defaultval'];

        }

	    if (!$ID || $ID != 1) {

		    if ($ID) {

			    $status = get_post_status($ID);

		    } else {

			    $status = get_post_status(get_the_ID());

		    }

		    if ($status !== 'publish') {

			    set_transient($gaTransName, '0', 60 * $options['gapp_cache']);

			    if (!add_post_meta($postID, '_gapp_post_views', '0', true)) {
				    update_post_meta($postID, '_gapp_post_views', '0');
			    }

			    return 0;

		    }

	    }

        $json = gapp_api_call('https://www.googleapis.com/analytics/v3/data/ga',
            array('ids' => 'ga:'.$options['gapp_wid'],
                'start-date' => $startDate,
                'end-date' => date('Y-m-d'),
                'metrics' => $options['gapp_metric'],
                'filters' => 'ga:pagePath=@' . $permalink,
                'max-results' => 1000)
        , false);

	    if ( isset( $json->totalsForAllResults->{$options['gapp_metric']} ) ) {

		    $totalResult = $json->totalsForAllResults->{$options['gapp_metric']};

		    set_transient($gaTransName, $totalResult, 60 * $options['gapp_cache']);

		    if (!add_post_meta($postID, '_gapp_post_views', $totalResult, true)) {
			    update_post_meta($postID, '_gapp_post_views', $totalResult);
		    }

		    return ($format) ? number_format_i18n($totalResult) : $totalResult;

	    } else {

		    $default_value = $options['gapp_defaultval'];

		    // If we have an old value let's put that instead of the default one in case of an error
		    $meta_value = get_post_meta($postID, '_gapp_post_views', true);

		    if ($meta_value !== false) {
			    $default_value = $meta_value;
		    }

		    set_transient($gaTransName, $default_value, 60 * $options['gapp_cache']);

		    return $options['gapp_defaultval'];

	    }

    }

}

// Add a column in Posts list (Optional)

add_filter('manage_posts_columns', 'gapp_column_views');
add_action('manage_posts_custom_column', 'gapp_custom_column_views', 6, 2);
add_action('admin_head', 'gapp_column_style');
add_filter('manage_edit-post_sortable_columns', 'gapp_manage_sortable_columns');
add_action('pre_get_posts', 'gapp_pre_get_posts', 1);

function gapp_column_views($defaults) {

	$options = gapp_options();

	if (!empty($options['gapp_token']) && $options['gapp_column']) {

		$defaults['post_views'] = __('Views');

	}

	return $defaults;

}

function gapp_custom_column_views($column_name, $id) {

	if ($column_name === 'post_views') {

		echo gapp_get_post_pageviews(get_the_ID(), true, true);

	}

}

function gapp_column_style() {

	echo '<style>.column-post_views { width: 120px; }</style>';

}

function gapp_manage_sortable_columns($sortable_columns) {

	$sortable_columns['post_views'] = 'post_views';

	return $sortable_columns;

}

function gapp_pre_get_posts($query) {

	if ($query->is_main_query() && ($orderby = $query->get('orderby'))) {
		switch ($orderby) {
			case 'post_views':
				$query->set('meta_key', '_gapp_post_views');
				$query->set('orderby', 'meta_value_num');

				break;
		}
	}

	return $query;

}

function gapp_admin_notice() {

	$options = gapp_options();

	if (current_user_can('manage_options')) {

		if (isset($options['gapp_token']) && empty($options['gapp_token'])) {

			echo '<div class="error"><p>'.__('Google Post Pageviews Warning: You have to (re)connect the plugin to your Google account.') . '<br><a href="'.admin_url('options-general.php?page=' . GAPP_SLUG).'">'.__('Update settings', 'google-analytics-post-pageviews').' &rarr;</a></p></div>';

		} elseif (isset($options['gapp_error']) && !empty($options['gapp_error'])) {

            echo '<div class="error"><p>'.__('Google Post Pageviews Error: ') . $options['gapp_error'] . '<br><a href="'.admin_url('options-general.php?page=' . GAPP_SLUG).'">'.__('Update settings', 'google-analytics-post-pageviews').' &rarr;</a></p></div>';

        }

	}

}

// Admin notice
add_action('admin_notices', 'gapp_admin_notice');

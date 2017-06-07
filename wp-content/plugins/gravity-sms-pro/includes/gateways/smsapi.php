<?php
/*
 * Class Name : GFHANNANSMS_Pro_{strtoupper( php file name) }	
 */
class GFHANNANSMS_Pro_SMSAPI {

	/*
	* Gateway title	
	*/
	public static function name($gateways) {
	
		$name = __('SmsApi', 'GF_SMS' );
		
		$gateway = array( strtolower( str_replace( 'GFHANNANSMS_Pro_', '', get_called_class())) => $name );
		return array_unique( array_merge( $gateways , $gateway ) );
	}
	
	
	/*
	* Gateway parameters
	*/
	public static function options(){
		return array(
			'username' 	=> __('Username','GF_SMS'),
			'password'  => __('API Password in MD5','GF_SMS'),
			'domain'  => __('Domain (com or pl ....)','GF_SMS'),
		);
	}

	/*
	* Gateway credit
	*/
	public static function credit(){
		return true;
	}

	/*
	* Gateway action
	*/
	public static function process( $options, $action, $from, $to, $messages ){
		
		if ( $action == 'credit' && !self::credit() ) {
			return false;
		}
		
		if ( ! extension_loaded('curl') )
			return __('Please enable cURL extension in PHP','GF_SMS');
		
		$username  = $options['username'];
		$password  = $options['password'];
		$domain    = strtolower($options['domain']);
		
		$to = str_replace('+','', $to );
		
		if ($action == "send") {
		
			$params = array(
				'username' => $username,
				'password' => $password,
				'to' => $to,
				'from' => $from,
				'message' => $messages,
			//	'test' => 1,
			);

			$result = self::sms_send($params , false , $domain );
			$result = strtolower($result);
			
			if ( stripos ( $result , 'error' ) === false || stripos ( $result , 'ok' ) !== false )
				return 'OK';
			else {
				$result = substr($result, 6);
				return self::error($result);
			}
		}
			

		if ($action == "credit") {
		
			$params = array(
				'username' => $username,
				'password' => $password,
				'credits' => 1,
			);
			$result = self::sms_send($params , false , $domain );
			
			if ( stripos ( strtolower($result) , 'error' ) === false )
				return $result;
			else {
				$result = substr($result, 6);
				return self::error($result);
			}
		}
		
		if ($action == "range"){
			$min = 20;
			$max = 50;
			return array("min" => $min, "max" => $max);
		}

	}

	private static function sms_send($params, $backup = false , $domain = 'com' ) {

        static $content;

        if($backup == true){
            $url = 'https://api2.smsapi.' . $domain . '/sms.do';
        }else{
            $url = 'https://api.smsapi.' . $domain . '/sms.do';
        }

        $c = curl_init();
        curl_setopt( $c, CURLOPT_URL, $url );
        curl_setopt( $c, CURLOPT_POST, true );
        curl_setopt( $c, CURLOPT_POSTFIELDS, $params );
        curl_setopt( $c, CURLOPT_RETURNTRANSFER, true );
        $content = curl_exec( $c );
        $http_status = curl_getinfo($c, CURLINFO_HTTP_CODE);

        if($http_status != 200 && $backup == false){
            $backup = true;
            self::sms_send($params, $backup);
        }
        curl_close( $c );
		
        return $content;
    }
	
	
	private static function error ($result) {
	
		$message = 'Error Code : ' . $result;
		
		switch($result){
		
			case '8' :
				$message = __('Error in request (Please report)', 'GF_SMS' );
				break;
					
			case '11' :
				$message = __('Message too long or there is no message or parameter nounicode is set and special characters (including Polish characters) are used.', 'GF_SMS' );
				break;
					
			case '12' :
				$message = __('Message has more parts than defined in &max_parts parameter', 'GF_SMS' );
				break;
					
			case '13' :
				$message = __('Lack of valid phone numbers (invalid or blacklisted numbers)', 'GF_SMS' );
				break;
					
			case '14' :
				$message = __('Wrong sender name', 'GF_SMS' );
				break;
					
			case '17' :
				$message = __('FLASH message cannot contain special characters', 'GF_SMS' );
				break;
					
			case '18' :
				$message = __('Invalid number of parameters', 'GF_SMS' );
				break;
					
			case '19' :
				$message = __('Too many messages in one request', 'GF_SMS' );
				break;
					
			case '20' :
				$message = __('Invalid number of IDX parameters', 'GF_SMS' );
				break;
					
			case '25' :
				$message = __('Parameters &normalize and &datacoding musn\'t appear in the same request', 'GF_SMS' );
				break;
					
			case '27' :
				$message = __('Too long IDX parameter. Maximum 255 chars.', 'GF_SMS' );
				break;
					
			case '28' :
				$message = __('Invalid time_restriction parameter value. Available values are: FOLLOW, IGNORE or NEAREST_AVAILABLE.', 'GF_SMS' );
				break;
					
			case '30' :
				$message = __('Wrong UDH parameter when &datacoding=bin', 'GF_SMS' );
				break;
					
			case '40' :
				$message = __('No group with given name in phonebook', 'GF_SMS' );
				break;
					
			case '41' :
				$message = __('Chosen group is empty', 'GF_SMS' );
				break;
					
			case '50' :
				$message = __('Messages may be scheduled up to 3 months in the future', 'GF_SMS' );
				break;
					
			case '52' :
				$message = __('Too many attempts of sending messages to one number (maximum 10 attempts whin 60s)', 'GF_SMS' );
				break;
					
			case '53' :
				$message = __('Not unique idx parameter, message with the same idx has been already sent and &check_idx=1.', 'GF_SMS' );
				break;
					
			case '54' :
				$message = __('Wrong date - (only unix timestamp and ISO 8601)', 'GF_SMS' );
				break;
					
			case '56' :
				$message = __('The difference between date sent and expiration date can\'t be less than 1 and more tha 12 hours.', 'GF_SMS' );
				break;
					
			case '70' :
				$message = __('Invalid URL in notify_url parameter.', 'GF_SMS' );
				break;
					
			case '74' :
				$message = __('Sending date doesn\'t match date sent restrictions set for the account.', 'GF_SMS' );
				break;
					
			case '101' :
				$message = __('Invalid authorization info', 'GF_SMS' );
				break;
					
			case '102' :
				$message = __('Invalid username or password', 'GF_SMS' );
				break;
					
			case '103' :
				$message = __('Insufficient credits on Your account', 'GF_SMS' );
				break;
					
			case '104' :
				$message = __('No such template', 'GF_SMS' );
				break;
					
			case '105' :
				$message = __('Wrong IP address (for IP filter turned on)', 'GF_SMS' );
				break;
					
			case '110' :
				$message = __('Action not allowed for your account', 'GF_SMS' );
				break;
					
			case '200' :
				$message = __('Unsuccessful message submission', 'GF_SMS' );
				break;
					
			case '201' :
				$message = __('System internal error (please report)', 'GF_SMS' );
				break;
					
			case '202' :
				$message = __('Too many simultaneous request, message won\'t be sent', 'GF_SMS' );
				break;
					
			case '203' :
				$message = __('Too many requests. Please try again later.', 'GF_SMS' );
				break;
					
			case '301' :
				$message = __('ID of messages doesn\'t exist', 'GF_SMS' );
				break;
					
			case '400' :
				$message = __('Invalid message ID of a status response', 'GF_SMS' );
				break;
					
			case '999' :
				$message = __('System internal error (please report)', 'GF_SMS' );
				break;
					
			case '1000' :
				$message = __('Acction available only for main user', 'GF_SMS' );
				break;
					
			case '1001' :
				$message = __('Invalid action (expected one of following parameters: add_user, set_user,get_user, credits)', 'GF_SMS' );
				break;
					
			case '1010' :
				$message = __('Subuser\'s adding error', 'GF_SMS' );
				break;
					
			case '1020' :
				$message = __('Subuser\'s editing error ', 'GF_SMS' );
				break;
					
			case '1021' :
				$message = __('No data to edit, at least one parameter has to be edited', 'GF_SMS' );
				break;
					
			case '1030' :
				$message = __('Checking user\'s data error', 'GF_SMS' );
				break;
					
			case '1032' :
				$message = __('Subuser doesn\'t exist for this main user', 'GF_SMS' );
				break;
					
			case '1100' :
				$message = __('Subuser\'s data error', 'GF_SMS' );
				break;
					
			case '1110' :
				$message = __('Invalid new subuser\'s name', 'GF_SMS' );
				break;
					
			case '1111' :
				$message = __('New subuser\'s name is missing', 'GF_SMS' );
				break;
					
			case '1112' :
				$message = __('Too short new subuser\'s name, it has to contain minimum 3 characters', 'GF_SMS' );
				break;
					
			case '1113' :
				$message = __('Too long new subuser\'s name, subuser\'s name with main user\'s prefix may contain maximum 32 characters', 'GF_SMS' );
				break;
					
			case '1114' :
				$message = __('Not allowed characters occured in subuser\'s name, following are allowed:letters [A – Z], digits [0 – 9] and following others @, -, _ and .', 'GF_SMS' );
				break;
					
			case '1115' :
				$message = __('Another user with the same name exists', 'GF_SMS' );
				break;
					
			case '1120' :
				$message = __('New subuser\'s password error', 'GF_SMS' );
				break;
					
			case '1121' :
				$message = __('Password too short', 'GF_SMS' );
				break;
					
			case '1122' :
				$message = __('Password too long', 'GF_SMS' );
				break;
					
			case '1123' :
				$message = __('Password should be hashed with MD5', 'GF_SMS' );
				break;
					
			case '1130' :
				$message = __('Credit limit error', 'GF_SMS' );
				break;
					
			case '1131' :
				$message = __('Parameter limit ought to be a number', 'GF_SMS' );
				break;
					
			case '1140' :
				$message = __('Month limit error', 'GF_SMS' );
				break;
					
			case '1141' :
				$message = __('Parameter month_limit ought to be a number', 'GF_SMS' );
				break;
					
			case '1150' :
				$message = __('Wrong senders parameter vaule, binnary 0 and 1 values allowed', 'GF_SMS' );
				break;

			case '1160' :
				$message = __('Wrong phonebook parameter vaule, binnary 0 and 1 values allowed', 'GF_SMS' );
				break;

			case '1170' :
				$message = __('Wrong active parameter vaule, binnary 0 and 1 values allowed', 'GF_SMS' );
				break;

			case '1180' :
				$message = __('Parameter info error', 'GF_SMS' );
				break;
	
			case '1183' :
				$message = __('Parameter info is too long', 'GF_SMS' );
				break;
		
			case '1190' :
				$message = __('API password for subuser\'s account error', 'GF_SMS' );
				break;
		
			case '1192' :
				$message = __('Wrong API password lenght (password hashed with MD5 should have 32 chars)', 'GF_SMS' );
				break;
		
			case '1193' :
				$message = __('API password should be hashed with MD5', 'GF_SMS' );
				break;
		
			case '2001' :
				$message = __('Invalid action (parameter add, status, delete or list expected)', 'GF_SMS' );
				break;
		
			case '2010' :
				$message = __('New sender name adding error', 'GF_SMS' );
				break;
		
			case '2030' :
				$message = __('Sender name\'s status checking error', 'GF_SMS' );
				break;
		
			case '2031' :
				$message = __('Such sender name doesn\'t exist', 'GF_SMS' );
				break;
		
			case '2060' :
				$message = __('Default sender name error', 'GF_SMS' );
				break;
		
			case '2061' :
				$message = __('Sender name has to be active for setting it as default', 'GF_SMS' );
				break;
		
			case '2062' :
				$message = __('This sender name is already set as default', 'GF_SMS' );
				break;
		
			case '2100' :
				$message = __('Data error', 'GF_SMS' );
				break;
		
			case '2110' :
				$message = __('Sender name error', 'GF_SMS' );
				break;
		
			case '2111' :
				$message = __('Sender name is missing for adding new sender name action (parameter&add is empty)', 'GF_SMS' );
				break;
		
			case '2112' :
				$message = __('Invalid Sender Name\'s name (i.e. Name containing special chars or name too long), sender name may contain up to 11 chars, chars allowed: a-z AZ 0-9 - . [spacebar]', 'GF_SMS' );
				break;
		
			case '2115' :
				$message = __('Sender name already exist', 'GF_SMS' );
				break;	
		}
		
		return $message;
	}
	
	
}
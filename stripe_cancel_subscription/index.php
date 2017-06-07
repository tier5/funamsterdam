<?php
ini_set('max_execution_time', 0);
require_once('init.php');
const LIMIT = 100;


//\Stripe\Stripe::setApiKey('sk_live_cTtOjqGWNeenh4QHBN1XzDhU');
//$customer_id = '';
\Stripe\Stripe::setApiKey('sk_live_XD1RqXVbqgy5yeKq4AhvYz78');
//$firstCustomerPage = \Stripe\Customer::all([
//    "limit" => 1,
//    "include[]" => "total_count"
//]);
//echo $count = $firstCustomerPage->total_count;die;

//for ($i = 0; $i < $count; $i+=LIMIT) {
//    \Stripe\Stripe::setApiKey('sk_live_XD1RqXVbqgy5yeKq4AhvYz78');
//    if (!empty($customer_id))
//        $params = array("limit" => LIMIT, "starting_after" => $customer_id);
//    else
//        $params = array("limit" => LIMIT);
//    $list_customers = \Stripe\Customer::all($params);
//$list_customers = \Stripe\Customer::retrieve("cus_82a18ilnnkJ8w0");
//echo 'hi';
//exit;

    //$customer_array = $list_customers->__toArray(true);
//echo '<pre>';
//print_r($customer_array);die;

    //foreach ($customer_array['data'] as $value) {
       // $customer_id = $value['id'];
       // if(is_array($value['subscriptions']['data'])) {
            //foreach ($value['subscriptions']['data'] as $result) {
                //$subscription_id = $result['id'];
                $cu = \Stripe\Customer::retrieve('cus_AKWiiy64GF9kFM');
                //print_r( $subscription_id);
                //$cu->subscriptions->retrieve($subscription_id)->cancel();
                $subscriptions = $cu->subscriptions->all();
                foreach ($subscriptions->data as $subscription) {
                    echo '<pre>';
print_r($subscription);die;
                }
               // echo "Subscriptions Cancelled Successfully";
           // }
      //  }
  //  }
//}


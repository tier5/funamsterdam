<?php
/**
 * Created by PhpStorm.
 * User: nagamani.p
 * Date: 4/26/2017
 * Time: 10:57 AM
 */
ini_set('max_execution_time', 18000);
require_once('init.php');
\Stripe\Stripe::setApiKey('sk_live_XD1RqXVbqgy5yeKq4AhvYz78');
//\Stripe\Stripe::setApiKey('sk_live_XD1RqXVbqgy5yeKq4AhvYz78');
function cancel($id,$cus_id)
{

    $cu = \Stripe\Customer::retrieve($cus_id);
    //print_r( $subscription_id);
    $cu->subscriptions->retrieve($id)->cancel();
    echo "Subscriptions Cancelled Successfully";
}
$f_pointer=fopen("subscriptions.csv","r"); // file pointer
$row=1;
while(! feof($f_pointer)){

    $ar=fgetcsv($f_pointer);

    $id = $ar[0]; // print the array
    $cus_id = $ar[1];
    if($id!='id') {
        cancel($id,$cus_id);

    }
    //echo "<br>";die;

}
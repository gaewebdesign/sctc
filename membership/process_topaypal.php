<?php


include_once('paypal/paypal.inc.php');
include_once("../library/library.php");
include_once("includes.inc");

define("RETURN_URL","http://www.sctennisclub.org/membership/done");
define("CANCEL_URL","http://www.sctennisclub.org/membership/cancel");
define("NOTIFY_URL","http://www.sctennisclub.org/membership/notify.php");


define("PAYPAL_MAIL","treasurer@sctennisclub.org");

session_start();

$price = $_SESSION[FEE];     // this is calculated in check.php

$paypal = new paypal();

$paypal->price = $price;

$paypal->ipn = "http://www.sctennisclub.com/memberapp/pipn.php";

$paypal->enable_payment();

$paypal->add("currency_code","USD");

$paypal->add("business",PAYPAL_MAIL);

$paypal->add("item_name","SCTC Membership");
$paypal->add("quantity",1);


$paypal->add("return",RETURN_URL);
$paypal->add("cancel_return",CANCEL_URL);
$paypal->add("notify_url",NOTIFY_URL);


$_SESSION[CUSTOM] = rand(1,10000000) ;

//$paypal->add("PLAYERNAME",$_SESSION[FNAME." ".LNAME]);
$paypal->add("item_number",$_SESSION[FNAME]." ".$_SESSION[LNAME] );

$paypal->add("custom",$_SESSION[CUSTOM]);

sessiontoDB(TABLE_PENDING);

$paypal->output_form();




/*
1)paying.. on return goes to  cancel.php  SITE_URL
2)not paying  click cancel goes to pipn.php   PIPN_URL


<input type="hidden" name="return" value="http://www.dawncraftmc.com/index.html">
 <input type="hidden" name="cancel_return" value="http://www.dawncraftmc.com/index.html">
*/


?>
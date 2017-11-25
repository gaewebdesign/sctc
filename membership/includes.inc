<?php


include("kotoshi.inc");

//define("RS", "1");    // Resident Family
//define("RF", "1");    // Resident Family

define("RS", "20");    // Resident Single
define("RF", "20");    // Resident Family
//define("RJ", "1");    // Resident Junior

define("RJ", "10");    // Resident Junior


//define("NRS", "2");    // Non-Resident Single
//define("NRF", "3");    // Non-Resident Family


define("NRS", "25");    // Non-Resident Single
define("NRF", "35");    // Non-Resident Family


//define("NRJ", "2");    // Non-Resident Junior
define("NRJ", "12.50");    // Non-Resident Junior


define("DB_MEMBERSHIP","sctcmembers");
define("TABLE_PAYPAL","paypal");      // paid online
define("TABLE_FAMILY","family");      // added family member
define("TABLE_CHECK","bycheck");      // check for self/family or by hand
define("TABLE_PENDING","pending");    // 

define("YEAR", "year" );

define("RENEW", "renew" );

define("FNAME", "fname" );
define("LNAME", "lname" );
define("GENDER", "gender" );
define("NTRP", "ntrp" );

define("ADDRESS", "address" );
define("CITY", "city" );
define("ZIP", "zip" );

define("CODE", "code" );
define("PHONEPRE", "phonepre" );
define("PHONEPOST", "phonepost" );

define("PHONE", "phone" );   // used in check.php (Phone()to get $_SESSION[PHONE]

define("EMAIL", "email" );
define("URL", "url" );
define("PAID", "paid" );
define("MTYPE", "mtype" );
define("DATE", "date" );
define("PAYMENT", "payment" );
define("OTHER", "other" );
define("CUSTOM", "custom" );

define("HELP", "help" );
define("OTHER", "other" );


define("TEAM", "team" );
define("CAPTAIN", "captain" );


//define("PHONEPOST", "phonepost" );

define("MEMBERSHIP", "membership" );

define("FEE", "membershipfee" );

define("PLAYERNAME", "playername" );

$members_all = 0;
$members_res = 0;
$members_non = 0;

function get_client_ip()
{
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';

    return $ipaddress;


}


function CalculateMembers()
{
    global $members_all;
    global $members_res;
    global $members_non;

//  from includes.inc
    global $KOTOSHI;
//  from includes.inc

    $con = DBMembership();


    $YEAR= $KOTOSHI; //MEMBERSHIP_YEAR;


// Query get get members, from the year 
   $query = 'select * from '.TABLE_PAYPAL.' where year= "'.$YEAR.'" ';
// Add this to get from bycheck table
   $query .= ' union select * from '.TABLE_CHECK.' where year =  "'.$YEAR.'"';

   $query .= ' order by lname';
// ---- Calculate the Residents/Non-Residents

  $qr = mysqli_query($con,$query);
  $total=$res=$non=0;

  while ($row = mysqli_fetch_assoc( $qr)) {  

      $total +=1;
      if( preg_match("/santa|clara/i",$row[CITY])) 
          $res +=1;
      else
          $non +=1;
  }

// CALCULATE MEMBERSHIP (ALL , RESIDENTS, NON-RESIDENTS) into global 
   $members_all = $total;
   $members_res = $res;
   $members_non = $non;



}

function ResidentMajority()
{
    global $members_all;
    global $members_res;
    global $members_non;

    global $SURPLUS;
    

    CalculateMembers();

    $actual = "$members_all $members_res $members_non";
//  echo $actual;
//  return

    $net = $members_res - $members_non;  

//  echo "res/non-res". $members_res."/".$members_non;

    $ret = "no";

    if( $net  > -$SURPLUS ) $ret = "yes";  


    echo $ret;

}

// ***************************************
// AJAX call *****************************

// LOOK for address in membership database
// Note that start_session used in ajax_findaddress.php
function FindAddress()
{

global $KOTOSHI;

// note that $_SESSION[MEMBERSHIP] could   NRF/RF or NRF_/RF_
  if( strpos($_SESSION[MEMBERSHIP],  "F") ){
       // do nothing
  }else{
       return "NO";
  }


//  LOOK into both the TABLE_PAYPAL
        $query = "select * from ".TABLE_PAYPAL.' where address = "'.trim($_SESSION[ADDRESS]).'"';
//       $query .= 'and mtype regexp "^R"';
        $query .= 'and ( mtype regexp "^R" or mtype regexp "^NRF")';
        $query .= ' and year = "'.$KOTOSHI.'"';

        $query .= ' union ';

//  AND TABLE_BYCHECK tables
        $query .= "select * from ".TABLE_CHECK.' where address = "'.trim($_SESSION[ADDRESS]).'"';
//      $query .= 'and mtype regexp "^R"';

        $query .= 'and ( mtype regexp "^R" or mtype regexp "^NRF")';

        $query .= ' and year = "'.$KOTOSHI.'"';


        $con = DBMembership();
        $query_results=mysqli_query($con, $query);
        $n = mysqli_num_rows($query_results);
 

        if($n > 0)  {
             $row = mysqli_fetch_assoc( $query_results);
             $fname = $row[FNAME];
             $lname = $row[LNAME];
             $primary_member = "(".$fname." ".$lname.")";
             $fee = .99;

// Overwrite here for additional family member
//            $_SESSION[MEMBERSHIP] .= "_";
//            $_SESSION[FEE] = $fee;

//            return $_POST[ADDRESS];
              return $primary_member;
             }

// FINALLY
//   echo $_SESSION[FEE]." ".$primary_member;


             return "NO";


}



// *****************************
function OnLocalHost()
{

   $ret=1;
   if(strstr($_SERVER["SERVER_NAME"],"sctennis")) $ret = 0;
   
   return $ret;

}

function DBMembership()
{

	$HOST = "127.0.0.1";
        $USER = "root";
        $PASSWORD = "tomato";
        $DB= "sctc";


        if(strstr($_SERVER["SERVER_NAME"],"sctennis")){
	    $HOST = "sctennisclub.globatmysql.com";
            $USER="sctc";
            $PASSWORD="tomato";
            $DB= DB_MEMBERSHIP;  // globat DB isn't named same as localhost

        }

//      echo $HOST." ".$USER."  ".$PASSWORD." ".$DB;

	$con = mysqli_connect($HOST,$USER, $PASSWORD) or
		die('Could not connect: <font color="red">'.mysql_error().'</font>');

	mysqli_select_db($con ,$DB );

        return $con;
}



// Membership: save session data
function SaveSession()
{

  $_SESSION[RENEW] = $_POST[RENEW];    // "N": new member "": renwal


  $_SESSION[FNAME] = $_POST[FNAME];
  $_SESSION[LNAME] = $_POST[LNAME];
  $_SESSION[GENDER] = $_POST[GENDER];

  $_SESSION[NTRP]    =  $_POST[NTRP];

  $_SESSION[ADDRESS]  =  $_POST[ADDRESS];
  $_SESSION[CITY]     =  $_POST[CITY];
  $_SESSION[ZIP]      =  $_POST[ZIP];

  $_SESSION[EMAIL]   =  $_POST[EMAIL];
  $_SESSION[URL]     =  $_POST[URL];

  $_SESSION[TEAM]     =  $_POST[TEAM];
  $_SESSION[CAPTAIN]     =  $_POST[CAPTAIN];


  $_SESSION[CODE]    =  $_POST[CODE];
  $_SESSION[PHONEPRE]    =  $_POST[PHONEPRE];
  $_SESSION[PHONEPOST]    =  $_POST[PHONEPOST];

  $_SESSION[MEMBERSHIP]    =  $_POST[MEMBERSHIP];



  $_SESSION[HELP] = "";
  if( !empty($_POST[HELP])){
          foreach( $_POST[HELP] as $check )  // $POST[HELP] is array of {"T","R","S"]
             $_SESSION[HELP] .= $check;      // but $_SESSION[HELP] is string of "TRS"

  }


  $_SESSION[OTHER] = $_SERVER['REMOTE_ADDR'];  // $_POST[OTHER];

//  echo("SESSION[HELP]= ".$_SESSION[HELP]);
//  echo("<br>");
//  echo("SESSION[OTHER]= ".$_POST[OTHER]);

//  from membership.php can only get the type of member
//  $_SESSION[FEE]    =  $_POST[FEE];  see check.php for calculationo of fee


}




// requires active SESSION  (session_star()
function sessiontoDB( $TABLE)
{
   global $KOTOSHI;


   $fname= $_SESSION[FNAME];
   $lname= $_SESSION[LNAME];
   $gender =   $_SESSION[GENDER] ;
   $ntrp =   $_SESSION[NTRP];
   $email =   $_SESSION[EMAIL];
   $url =   $_SESSION[URL];

   $code =   $_SESSION[CODE] ;

   $phone =   $_SESSION[PHONEPRE]."-".$_SESSION[PHONEPOST] ;

   $address = trim($_SESSION[ADDRESS]) ;
   $city =   trim($_SESSION[CITY]) ;
   $zip =   trim($_SESSION[ZIP]) ;

   $capt =   $_SESSION[CAPTAIN] ;
   $team =   $_SESSION[TEAM] ;


   $mtype =  $_SESSION[MEMBERSHIP] ;
   $other =   $_SESSION[OTHER] ;
   $help =   $_SESSION[HELP] ;

   $other =   $_SESSION[OTHER] ;

   $payment = $_SESSION[FEE] ;

   $custom  = $_SESSION[CUSTOM] ;


   $state = "CA";
   $year  = $KOTOSHI; //MEMBERSHIP_YEAR;
   date_default_timezone_set('America/Los_Angeles');
   $date =   time();


   $insignia = "~";   //~ for 2014


   if($year <= "2013") $insignia .= "#";
   if($year <= "2013") $insignia .= "&";

   $q = "insert into ".$TABLE.'(_id,fname,lname,gender,ntrp,email,url,code,phone,address,city,state,zip,year,mtype,capt,team,help,other,date,insignia,payment,custom) values ';

   $query = $q.'(NULL'.add($fname).add($lname).add($gender).add($ntrp).add($email).add($url);
   $query .= add($code).add($phone).add($address).add($city).add($state).add($zip).add($year);
   $query .= add($mtype).add($capt).add($team).add($help).add($other).add($date).add($insignia).add($payment).add($custom);
   $query .= ")";

//   echo $query."<br>";
//   echo "PAYMENT = ".$payment."<br>";

   $con = DBMembership();
   $query_results=mysqli_query($con, $query);

// echo $query_results;


}


function transfer($verbose=false)
{

   $to = "notify@sctennisclub.org";
   $subject = "SCTC Membership ";

// Put Paypal POST values into message
   $message = "notify.php called <br>";
   $message .= "Paypal Parameters <br>";
   foreach ( $_POST as $key => $value)
      $message .= $key." -> ".$_POST[$key]."<br>";  

//  echo $message;

   $headers = 'From: memb@sctennisclub.org' . "\r\n" .
    'Reply-To: memb@sctennisclub.org' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

// Use this header

   $headers  = 'MIME-Version: 1.0' . "\r\n";
   $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
   $headers .= "From: membership@sctennisclub.org  \r\n";


// Can pick either ->custom or ->transaction_subject
   $transaction = $_POST['custom'];

   $theTable= TABLE_PAYPAL;
   if( !empty($_GET["transaction"])){
        $transaction = $_GET["transaction"];
        $theTable= TABLE_CHECK;
        if($verbose) echo "INSERT into ".$theTable."<br>\n";
   }


   cp($transaction, $theTable,$verbose);

   $name = " - ";
   if( isset($_POST["first_name"]) ){
     $name = " (".$_POST["first_name"]." ".$_POST["last_name"]." )";
   }


   $subject .= $name;

   $r=mail($to,$subject,$message,$headers);
   if($verbose) echo "mail=".$r;

}



// Copy member from pending table to paypal table and mark 'other' as done
// $transaction value is stored in 'other'
function cp($transaction, $theTable,$verbose=false){

// This is the basic command to copy from pending into paypal table
//insert into pending(fname,lname,address,city,zip) select fname,lname,address,city,zip from paypal where fname="Cori";


$query = "insert into ".$theTable."(fname,lname,email,url,gender,ntrp,code,phone,address,city,zip,state,year,capt,team,mtype,help,other,date,insignia,payment,custom)";
$query .= " select fname,lname,email,url,gender,ntrp,code,phone,address,city,zip,state,year,capt,team,mtype,help,other,date,insignia,payment,custom ";
$query .= " from pending where ";
$query .= "custom=".'"'.$transaction.'"';


if($verbose) echo $query;

$con=DBMembership();
$query_results=mysqli_query($con, $query);
if($verbose){ 
        echo $query."<p>";
        echo "query_results = ".$query_results."<br>";
}

$query = "update pending set custom=\"done\" where custom=\"$transaction\" limit 1";
$query_results=mysqli_query($con,$query);
if($verbose){
	echo $query."<br>";
	echo "<br>";
	echo "query_results = ".$query_results."<br>";
}

}

// DELETE and move to trash table
function trash($uniqueID, $theTable){

  date_default_timezone_set('America/Los_Angeles');
  $con =  DBMembership();

  $query = "select * from $theTable where _id = $uniqueID";
  $qr=mysqli_query($con,$query);
  echo "<center>";

    $row = mysqli_fetch_assoc($qr); 

     echo "deleting   ";
     echo $row[FNAME]." ".$row[LNAME]."(".$row[YEAR].")";
     echo "<br>";

// COPY to the trash table
     $query = "insert into trash select * from $theTable where _id = $uniqueID";
     $k=mysqli_query($con,$query);
     echo $query."<br>";

// DELETE 
     $query = "delete from $theTable where _id = $uniqueID";
     echo $query."<br>";    
     $k=mysqli_query($con,$query);



}
?>
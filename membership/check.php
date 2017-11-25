<?php
  include "../library/library.php";
  include "includes.inc";

  session_start();
  SaveSession();  


// foreach ( $_SESSION as $key => $value)  echo $key." -> ".$_SESSION[$key]."<br>";
// foreach ( $_POST as $key => $value)  echo $key." -> ".$_POST[$key]."<br>";

// UPDATE paypal SET mtype = 'RS';
// update paypal set address = trim(address);
// ALTER TABLE paypal ADD custom VARCHAR(31) AFTER payment;

// update pending set date='12/15/2013 13:23' where _id=1;

?>


<?php

define("ERR_FEE", 1 );
define("ERR_NAME", 2 );
define("ERR_GENDER", 4 );
define("ERR_NTRP", 8 );
define("ERR_EMAIL", 16 );
define("ERR_MEMB", 32 );
define("ERR_RESIDENT", 64 );


function e($t)
{
    echo $t."<br>";
}

function Phone()
{

  $p = "(".$_POST[CODE].") ".$_POST[PHONEPRE]."-".$_POST[PHONEPOST];;
  $_SESSION[PHONE]=$p;

  if(strlen($_POST[CODE].$_POST[PHONEPRE].$_POST[PHONEPOST] ) < 10 ) $p="";
  return $p;

}


function fee(){

   if( $_POST[MEMBERSHIP] == "RS" )       $FEE = RS;  // Resident - Single $20
   else if($_POST[MEMBERSHIP]== "RF" )    $FEE= RF;  // Resident - Family $20
   else if( $_POST[MEMBERSHIP] == "RJ" )  $FEE=RJ;   // Resident - Junior $10
   else if( $_POST[MEMBERSHIP] == "NRS")   $FEE=NRS; // Non- Res - Single $25
   else if( $_POST[MEMBERSHIP] == "NRF")   $FEE=NRF; // Non-Res  - Family $35
   else if( $_POST[MEMBERSHIP] == "NRJ")   $FEE=NRJ; // Non-Res  - Junior $12.50

   $_SESSION[FEE] = $FEE; //"$".$FEE;

//  CAN CHANGE HERE TO INCLUDE SINGLE MEMBERSHIPS TOO
   $primary_member = "";
   if($_POST[MEMBERSHIP] == "RS" ||  $_POST[MEMBERSHIP] == "RF" || $_POST[MEMBERSHIP] == "NRF" ) 
   {

          // FindAddress uses $_SESSION[ADDRESS]
          $ret = FindAddress();
          if($ret !=  "NO"){

                 $_SESSION[MEMBERSHIP] .= "_";
                 $primary_member=$ret;            

                 // 2017R
                 $_SESSION[FEE] = "0.99";
                 if( $_POST["mode"] == "free" ){ 
                      $_SESSION[FEE] = "";
                      echo "FREE"; 

		 }  

          }


   }   

   return "$".$_SESSION[FEE]." ".$primary_member;

}




function CheckFields()
{
  $err=0;



  if( !isset( $_POST[FNAME]) )     {  $err |= ERR_NAME; }
  if( !isset( $_POST[LNAME]) )     {  $err |= ERR_NAME; }
  if( isset($_POST[FNAME] )  && strlen($_POST[FNAME]) <2 )     {  $err |= ERR_NAME; }
  if( isset($_POST[LNAME] )  && strlen($_POST[LNAME]) <2 )     {  $err |= ERR_NAME; }

  if( $err & ERR_NAME) { error("Err Name: ".$err." (".ERR_NAME.") <br>"); }

  if( !isset( $_POST[GENDER] ))     {  $err |= ERR_GENDER; }

  if( $err & ERR_NAME) { error( "Err Gender: ".$err." (".ERR_GENDER.")<br>"); }

  if( !validNTRP( $_POST[NTRP]) )      {  $err |= ERR_NTRP;}

  if( $err & ERR_NTRP) { error( "Err NTRP: ".$err." (".ERR_NTRP.")<br>"); }

  if( strlen( $_POST[EMAIL].$_POST[URL])<3)   {$err |= ERR_EMAIL;}
  if( !filter_var(  $_POST[EMAIL]."@". $_POST[URL], FILTER_VALIDATE_EMAIL))  $err |= ERR_EMAIL;

  if( $err & ERR_EMAIL) { error( "Err EMAIL ".$err." (".ERR_EMAIL.") <br>"); }

  if( !isset($_POST["membership"])) $err  |= ERR_MEMB;

  if( $err & ERR_MEMB ) { error( "Err MEMBERSHIP ".$err." (".ERR_MEMB.") <br>"); }

  if( $_POST["membership"]=="RS" || $_POST["membership"]== "RF" ){
       $err = preg_match("/santa clara/i", $_POST[CITY]) ?  $err : $err | ERR_RESIDENT;
  }


  if( !isset($_POST["membership"])) $err  |= ERR_MEMB;

  if($_POST["membership"]== NONRES & ( $f1_and || $f2_and || $f2_and || $f2_and) ){
       $err |= ERR_NONRESFAM;
  }

  $err=0;
  return $err;

}


function validName( $n )
{
  return strlen($n)>1 ? true : false;
}

function validNTRP( $v )
{

  $ok = false;
  $n = array('','0.5','1.0','1.5','2.0','2.5','3.0','3.5','4.0','4.5','5.0','5.5','6.0');
  if( in_array( $v , $n , true ))   $ok = true;

  return $ok;

}

function  set( $v){

  return  strlen($v)> 1 ? true : false;

}


function err($v)
{
   return "<b>".$v."<b><br>";
}

function error( $v)
{
  echo($v."\n");
}


function enumeratePost()
{
  echo "POST<br>";
  foreach ( $_POST as $key => $value)
     echo $key." -> ".$_POST[$key]."<br>";
}




?>

<html>
<head>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js" type="text/javascript"></script>

<script language="javascript">
db = false     // false: family member not found  true: family found in db
mode = "pay"   // 0.99
function Initialize()
{

/*
   not used *************************************************

   var GET = window.location.search
   db = false;
   if( GET.indexOf("db") != -1)  db = true
*/
  // ********************************************************
  // 2017R Handle

//     var mode =  window.location.search.indexOf("mode")  
     var js = '<?php echo $_POST["mode"]; ?>'

     if( js.includes("free") ){
          mode = "free"
      }else{
           mode = "pay"
      }


  // ********************************************************

 var request = $.ajax({
    url: "ajax_findaddress.php",
    type:  "GET",
    dataType: "html"
   });

   request.done( function(msg){ 
       if(msg == "NO"){
          db=false;   // NO returned when address not found in db
//          alert(msg)
       }else{
          db=true;    // primary member name is returned
        }

       setUI();
   })



}

function setUI(){
  

  if( mode=="free" && db == true){
        $("#_DATABASE").show() 
        $("#_PAYPAL").hide()
    }else{
       $("#_PAYPAL").show()
       $("#_DATABASE").hide()
    }

}



window.onload = Initialize
</script>

<link rel="stylesheet" type="text/css" href="css/membership.css"  />
</head>

<body bgcolor="80bfff">  <!-- powderblue -->
</body>

<style>

.button
{
       width:350px;
       font-size:17px;
       color:#075;
       background-color:#aed;
       border-radius:5px;
}


.myfont {
    width:50%;
    border:1px solid #000;
    font-family: "Comic Sans MS", "Brush Script MT", cursive;
    font-size:18 px;
}

</style>

<p><br><p><br>

<head><style>
body { font-family: serif; font-size:20px;}
</style>

</head>
<body bgcolor="80bfff">

<center>
<h1>Santa Clara Tennis Club Membership </h1>

<table class="myfont">
<thead>
<tr>
<th scope="col" width="40%"> </th>
<th scope="col" width="60%"> </th>
</tr>


<tr><td>Name </td><td><?php echo($_POST[FNAME]." ".$_POST[LNAME]) ?></td></tr>



<?php  if( $_POST["free"] == "Y" )     {  echo "FREE"; } ?>


<tr><td>Address</td><td>
<?php echo($_POST[ADDRESS]) ?> 
<br>
<?php echo($_POST[CITY]." ".$_POST[ZIP]) ?>
</td></tr>


<tr><td>NTRP</td><td><?php echo($_POST[GENDER].$_POST[NTRP]) ?></td></tr>
<tr><td>Email</td><td><?php echo($_POST[EMAIL]."@".$_POST[URL]) ?> </td></tr>
<tr><td>Phone</td><td><?php echo(Phone()) ?></td></tr>
<tr><td>Membership Fee is</td><td><?php echo(fee()) ?></td></tr>
<tr><td></td><td></td></tr>
</table>


<span id="_DATABASE" style="display:none">
<form action="addmember" method="post">
<tr><td colspan="2"><p> </td></tr>
<tr><td colspan="2">If this is correct, select the following button to add family member to the membership</td></tr>
<tr><td colspan="2"> <p></td></tr>
<tr><td colspan="2"><center>
<input type="hidden" name="dbsubmit" " value="To Database" class="button">
<input type="submit" style="width:350px; " value="Add Family Member" class="button">
</td><tr></center>
</form>
</span>


<span id="_PAYPAL" style="display:none">
<form action="process" method="post">
<tr><td colspan="2">If this is correct, select the following button to be taken to Paypal</td></tr><br>
<tr><td colspan="2"><center>
<!--
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<input type="image" src="tn_buynowCC_LG.gif" border="0" name="submit" font-size="48px"  alt="Go To PayPal!">
-->

<input type="image" src="btn_buynowCC_LG.gif" border="0" name="submit" style="font-size:16px"  alt="Go To PayPal!">

</td></tr>
</form>
</span>



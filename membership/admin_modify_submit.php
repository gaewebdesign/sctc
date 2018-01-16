<html>

<body>

<SCRIPT LANGUAGE="JavaScript" SRC="../javascript/sorttable.js"> </SCRIPT>
<SCRIPT LANGUAGE="JavaScript" SRC="javascript/sorttable.js"> </SCRIPT>

<style>

#tablefont{
        font-family: "Comic Sans MS", "Brush Script MT", cursive;
        font-size:13 px;
        background: #fefefe;
        border: none;
        height : 35 px;
//      border-bottom: 1px solid black;

}

.centered-cell{
       text-align: center;

}

th {
       text-align: left
}

.eline{
        font-family: "Comic Sans MS", "Brush Script MT", cursive;
        font-size:24 px;
        background: #fefefe;
        background-color: #ffeeff;
        background-color: rgb(255,255,255);
        border: none;
        border-bottom: 1px solid black;
        height : 45 px;
        width: 20%;
}


</style>


<center>
<p><br>
<div id="eline">Santa Clara Tennis Club Membership List </div
<p><br>


<thead>
<tr>

<table class="sortable" id="tablefont" width="50%">

<form id="_DELETE"  action="qqqmodify_.php" method="post">

</thead>

<?php
include "includes.inc"; 


  if( isset($_POST['_MODIFY']) )
     modify();
  elseif( isset($_POST['_DELETE']) )
     delete();
  elseif( isset($_POST['_PENDING']) )
     pending();

// --------------------------------------------------------------------------
function pending(){

     $keyID  = $_POST["keyID"];
     $keyTABLE = $_POST["keyTABLE"];
     $keyCUSTOM = $_POST["keyCUSTOM"];

    
     if( $keyTABLE != TABLE_PENDING){
        println(" Already a SCTC member ");
     }else{
       println( "transferring.... ");

       println( " ");
//     println( "TABLE ".$keyTABLE );
//     println( "_ID ".$keyID );
//     println( "CUSTOM ".$keyCUSTOM );

// MEMBER INFO
       $con=DBMembership();
       $query = "select * from ".$keyTABLE." where custom=".$keyCUSTOM;

       $qr    = mysqli_query($con, $query);
       $row = mysqli_fetch_assoc($qr); 
       $NAME    = $row[FNAME]." ".$row[LNAME];
       $ADDRESS = $row[ADDRESS];
       $CITY = $row[CITY]." ".$row[ZIP];
       $EMAIL = $row[EMAIL]."@".$row[URL];;
       if(strlen($EMAIL)<3) $EMAIL="" ;

       println( $NAME );
       println( $ADDRESS);
       println( $CITY." ".$ZIP );
       println( $EMAIL );


// COPY OVER
        cp($keyCUSTOM , TABLE_CHECK , $verbose=false );


// SEND EMAIL
        sendemail( $NAME, $ADDRESS, $CITY, $EMAIL,"Player copied from pending");
      }

}

function println($v)
{
  echo $v;
  echo "<br>";

}

function message($sub , $msg){

        $to = "notify@sctennisclub.org";
        $subject = $subj;

        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= "From: membership@sctennisclub.org  \r\n";

        $message = $msg."<br>";
        $message .= "$NAME <br>";
        $message .= "$ADDRESS <br>";
        $message .= "$CITY <br>";
        $message .= "$EMAIL <br>";

        $r=mail($to,$subject,$msg,$headers); 



}

function sendemail( $NAME, $ADDRESS, $CITY, $EMAIL,$msg){

        $to = "notify@sctennisclub.org";
        $subject = "SCTC Membership (".$NAME.")";

        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= "From: membership@sctennisclub.org  \r\n";

        $message = $msg."<br>";
        $message .= "$NAME <br>";
        $message .= "$ADDRESS <br>";
        $message .= "$CITY <br>";
        $message .= "$EMAIL <br>";

        $r=mail($to,$subject,$message,$headers); 

}

function query($table,$uniqueID, $column, $value){

   $q = "";
   $q = "update $table set $column=".'"'.$value.'"'." where _id= $uniqueID";
   return $q;

}

function show ( ){
  println( $_POST["keyID"] );
  println( $_POST["keyTABLE"] );
  echo $_POST[FNAME];
  echo "&nbsp &nbsp&nbsp&nbsp";
  println( $_POST[LNAME] );
  println( $_POST[GENDER] );
  println( $_POST[ADDRESS] );
  println( $_POST[CITY] );
  println(  $_POST[ZIP] );
  println( $_POST[EMAIL]);
  println( $_POST[URL] );
  println( $_POST[CODE] );
  println( $_POST[PHONEPRE] );
  println( $_POST[PHONEPOST] );

}

// update pending set fname="Jean",lastname=Hoggatt where _id=183;

function queue( $tag, $value){

    return ",".$tag.' = "'.$value.'"';

}

function modify( ) {

  $keyID    =  $_POST["keyID"];
  $keyTABLE = $_POST["keyTABLE"];

  $con =  DBMembership();

// GET CURRENT INFORMATION
  $query = "select * from ".$keyTABLE." where _id=".$keyID;
  $qr    = mysqli_query($con, $query);
  $row = mysqli_fetch_assoc($qr); 

  $_NAME =  $row[FNAME]." ".$row[LNAME];
  $_CITY =  $row[CITY]." ".$row[ZIP];
  $_EMAIL = $row[EMAIL]."@".$row[URL];;
  if(strlen($_EMAIL)<3) $_EMAIL="" ;
  $_PHONE = "(".$row[CODE].") ".$row[PHONE];

  $_GENDER = "(".$row[CODE].") ".$row[PHONE];

  if( strlen($_PHONE) < 8){
   $_PHONE = "";
  }


// ******

  $query = "update $keyTABLE ";
  $query .= ' set '.FNAME.'='.' "'.$_POST[FNAME].'"';

  $query .= queue(LNAME,$_POST[LNAME]);
  $query .= queue(GENDER,$_POST[GENDER]);
  $query .= queue(NTRP,$_POST[NTRP]);
  $query .= queue(ADDRESS,$_POST[ADDRESS]);
  $query .= queue(CITY,$_POST[CITY]);
  $query .= queue(ZIP,$_POST[ZIP]);
  $query .= queue(EMAIL,$_POST[EMAIL]);
  $query .= queue(URL,$_POST[URL]);
  $query .= queue(CODE,$_POST[CODE]);

  $PHONE = $_POST[PHONEPRE]."-".$_POST[PHONEPOST];

  if( strlen($PHONE) == 8){
     $query .= queue(PHONE, $PHONE);
  }

  $query .= ' where _id ="'.$keyID.'"';
  $query_results=mysqli_query($con, $query);



// MEMBER INFO
  println("Modified <br>");
  $con=DBMembership();
  $query = "select * from ".$keyTABLE." where _id=".$keyID;

  $qr    = mysqli_query($con, $query);
  $row = mysqli_fetch_assoc($qr); 

  $EMAIL = $row[EMAIL]."@".$row[URL];;
  if(strlen($EMAIL)<3) $EMAIL="" ;

  $NAME =  $row[FNAME]." ".$row[LNAME];
  $CITY =  $row[CITY]." ".$row[ZIP];
  println( $NAME );
  println( $row[ADDRESS]);
  println( $CITY );
  println( $EMAIL );

  sendemail( $NAME, $row[ADDRESS], $CITY, $EMAIL,"Player modified (".$NAME.")" );

}

function delete( ) {
     $keyID  = $_POST["keyID"];
     $keyTABLE = $_POST["keyTABLE"];
     $keyCUSTOM = $_POST["keyCUSTOM"];

     trash( $keyID , $keyTABLE);

}

function _delete() {

  $con =  DBMembership();

  $keyID    =  $_POST["keyID"];
  $TABLE = $_POST["keyTABLE"];

  $query = "select * from $TABLE where _id = $keyID";
  $query_results=mysqli_query($con,$query);
  $row = mysqli_fetch_assoc($query_results); 
  foreach ( $row as $key=>$value) {
    echo $key." : ".$value."<br>";
  }

}

 echo "*********";
  return;

  foreach ($_POST['checkboxname'] as $key => $value){
    $parameters = explode(",",$value);
    $KEYID = $parameters[0];
    $TABLE = $parameters[1];

    $query = "select * from $TABLE where _id = $KEYID";

    $query_results=mysqli_query($con,$query);
    $row = mysqli_fetch_assoc($query_results); 

    $YEAR = $row[YEAR];

    $FNAME = $row[FNAME];
    $LNAME = $row[LNAME];

    $ADDRESS = $row[ADDRESS];
    $CITY    = $row[CITY];
    $ZIP     = $row[ZIP];
    $EMAIL = strtolower($row[EMAIL]);
    $URL = strtolower($row[URL]);

    $GENDER = $row[GENDER];
    $NTRP = $row[NTRP];

    $CODE = $row[CODE];
    $PHONEPRE = $PHONEPOST = "";

    if(strlen($row[PHONE]>=7)){
       $p = explode("-",$row[PHONE]);
       $PHONEPRE = $p[0];
       $PHONEPOST = $p[1];
       }
    break;
    }




 echo "<tr>  <td> YEAR </td>    <td> $YEAR  </td>   </tr>";

 echo "<tr>";  
 echo "<td> NAME </td>  ";

 echo "<td> ";
 echo elonmusk($FNAME,$FNAME);
 echo "&nbsp;&nbsp;&nbsp;&nbsp;";
 echo elonmusk($LNAME,$LNAME);
 echo "</td></tr>";


 echo "<tr>";  
 echo "<td> GENDER </td>  ";
 echo "<td> ";
 echo elonmusk($GENDER,$GENDER,5);
 echo "&nbsp;&nbsp;&nbsp;&nbsp;";

 echo "<tr>";  
 echo "<td> RATING </td>  ";
 echo "<td> ";
 echo elonmusk($NTRP,$NTRP,5);
 echo "</td></tr>";


 echo "<tr>  <td>ADDRESS</td>";
 echo "<td>";
 echo elonmusk($ADDRESS,$ADDRESS,30);
 echo "</td></tr>";

 echo "<tr>  <td>CITY</td>";
 echo "<td>";
 echo elonmusk($CITY,$CITY);
 echo "</td></tr>";

 echo "<tr>  <td>ZIP</td>";
 echo "<td>";
 echo elonmusk($ZIP,$ZIP,10);
 echo "</td></tr>";


 echo "<tr>  <td>EMAIL</td>";
 echo "<td>";
 echo elonmusk($EMAIL,$EMAIL,20);
 echo "@";
 echo elonmusk($URL,$URL);
 echo "</td></tr>";

 echo "<tr>  <td>PHONE</td>";
 echo "<td>";
 echo "(";
 echo elonmusk($CODE,$CODE,10);
 echo ") ";
 echo elonmusk($PHONEPRE,$PHONEPRE,10);
 echo " - ";
 echo elonmusk($PHONEPOST,$PHONEPOST,10);
 echo "</td></tr>";


//  First Name: <input  required value="" class="eline" name="fname" placeholder="" type="text" id="FIRST"/> &nbsp;&nbsp;&nbsp;&nbsp; 

function elonmusk( $boxname, $placeholder,$width=20){



    echo "<input required value=";

    echo '"'. $placeholder.'"';

    echo ' class="eline" style="'."width:$width%;".'"';


    echo "/>";
}

?>





</table>


</form>

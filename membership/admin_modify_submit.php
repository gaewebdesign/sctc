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
       println( "TABLE ".$keyTABLE );
       println( "_ID ".$keyID );
       println( "CUSTOM ".$keyCUSTOM );
       cp($keyCUSTOM , TABLE_CHECK , $verbose=false );
     }
}

function println($v)
{
  echo $v;
  echo "<br>";

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

function modify( ) {


  $con =  DBMembership();

  $keyID    =  $_POST["keyID"];
  $keyTABLE = $_POST["keyTABLE"];

  $query = query( $keyTABLE , $keyID, FNAME , $_POST[FNAME ] );
//  $query = "update $keyTABLE set ".LNAME.'= "'.$_POST[LNAME].'" where _id="'.$keyID.'"';
  $query_results=mysqli_query($con, $query);

  $query = query( $keyTABLE , $keyID, LNAME , $_POST[LNAME ] );
  $query_results=mysqli_query($con, $query);

  $query = query( $keyTABLE , $keyID, GENDER , $_POST[GENDER ] );
  $query_results=mysqli_query($con, $query);

  $query = query( $keyTABLE , $keyID, NTRP , $_POST[NTRP ] );
  $query_results=mysqli_query($con, $query);

  $query = query( $keyTABLE , $keyID, ADDRESS , $_POST[ADDRESS ] );
  $query_results=mysqli_query($con, $query);

  $query = query( $keyTABLE , $keyID, CITY , $_POST[CITY ] );
  $query_results=mysqli_query($con, $query);

  $query = query( $keyTABLE , $keyID, ZIP , $_POST[ZIP] );
  $query_results=mysqli_query($con, $query);

  $query = query( $keyTABLE , $keyID, EMAIL , $_POST[EMAIL] );
  $query_results=mysqli_query($con, $query);

  $query = query( $keyTABLE , $keyID, URL , $_POST[URL] );
  $query_results=mysqli_query($con, $query);

  $query = query( $keyTABLE , $keyID, CODE , $_POST[CODE] );
  $query_results=mysqli_query($con, $query);

  $PHONE = $_POST[PHONEPRE]."-".$_POST[PHONEPOST];

  if( strlen($PHONE) == 8){
     $query = query( $keyTABLE , $keyID, PHONE , $PHONE );
     $query_results=mysqli_query($con, $query);
  }


  println("Modified ");
  println($_POST[FNAME]."  ".$_POST[LNAME]);
  println($_POST[ADDRESS]);
  println($_POST[CITY]." ".$_POST[ZIP]);
  println($_POST[GENDER]." ".$_POST[NTRP]);
  println($_POST[CODE]." ".$PHONE);


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

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
        height : 25 px;
//      border-bottom: 1px solid black;

}

.centered-cell{
       text-align: center;

}

th {
       text-align: left
}


</style>


<center>
<p><br>
<div id="eline">Santa Clara Tennis Club Membership List </div
<p><br>


<table cellpaddiing="2">
<th>
<!--
IMPORT database
mysql -u username -ppassword databasename < filename.sql


Copy over some players from 2014 to 2015

create table transition select * from paypal where year=2014 date > limit 20;

SELECT * FROM `paypal` where date >=unix_timestamp('2015-11-1')  ORDER BY `date`  DESC


create table transition SELECT * FROM `paypal` where date >=unix_timestamp('2015-9-27')


delete from tmpTable

insert into tmpTable select * from paypal where year=2014 and lname regexp "^B";
update tmpTable set year='2015'

insert into paypal select * from tmpTable;

ALTER TABLE bycheck ADD opt varchar(31);
ALTER TABLE bycheck ADD pwd varchar(31);

ALTER TABLE family ADD opt varchar(31);
ALTER TABLE family ADD pwd varchar(31);

ALTER TABLE paypal ADD opt varchar(31);
ALTER TABLE paypal ADD pwd varchar(31);

ALTER TABLE pending ADD opt varchar(31);
ALTER TABLE pending ADD pwd varchar(31);



-->


<?php

include "../library/library.php";
include "includes.inc";

include("kotoshi.inc");
//define("MEMBERSHIP_YEAR", "2017"); 

$DIR = "membership";
if( OnLocalHost() ) $DIR = "~roger/sctc/membership";       
//$KOTOSHI  = $YEAR; //
$KYONEN   = $KOTOSHI-1;
$OTOTOSHI = $KOTOSHI-2;

// Detect the year and override if necessary
 $YEAR=$KOTOSHI; 
 if( (isset($_GET["year"]) == 1)){
          $YEAR=$_GET["year"];

          if($YEAR>$KOTOSHI) $YEAR=$KOTOSHI;
          if($YEAR<$KOTOSHI-2) $YEAR=$KOTOSHI;
 }


 if( $_GET["mode"] == "pdf" ){
  // don't list anything if in pdf mode

 }elseif( $_GET["mode"]=="family" ){
   // list for full,or family

   echo "<a style=text-decoration:none href=/$DIR/family/$OTOTOSHI>$OTOTOSHI</a>&nbsp";
   echo "<a style=text-decoration:none href=/$DIR/family/$KYONEN>$KYONEN/a>&nbsp";
   echo "<a style=text-decoration:none href=/$DIR/family/$KOTOSHI>$KOTOSHI</a>&nbsp";


 }elseif( $_GET["mode"]=="full" ){

   echo "<a style=text-decoration:none href=/$DIR/full/$OTOTOSHI>$OTOTOSHI</a>&nbsp";
   echo "<a style=text-decoration:none href=/$DIR/full/$KYONEN>$KYONEN</a>&nbsp";
   echo "<a style=text-decoration:none href=/$DIR/full/$KOTOSHI>$KOTOSHI</a>&nbsp";


 }elseif( $_GET["mode"]=="email" ){
   echo "<a style=text-decoration:none href=/$DIR/email/$OTOTOSHI>$OTOTOSHI</a>&nbsp";
   echo "<a style=text-decoration:none href=/$DIR/email/$KYONEN>$KYONEN</a>&nbsp";
   echo "<a style=text-decoration:none href=/$DIR/email/$KOTOSHI>$KOTOSHI</a>&nbsp";


 }elseif( $_GET["mode"]=="private" ){

   echo "<a style=text-decoration:none href=/$DIR/private/$OTOTOSHI>$OTOTOSHI</a>&nbsp";
   echo "<a style=text-decoration:none href=/$DIR/private/$KYONEN>$KYONEN</a>&nbsp";
   echo "<a style=text-decoration:none href=/$DIR/private/$KOTOSHI>$KOTOSHI</a>&nbsp";


 }else{
//   nothing for no mode (plist.php) for SCTC members page 

   echo "<a style=text-decoration:none href=/$DIR/members/$OTOTOSHI>$OTOTOSHI</a>&nbsp";
   echo "<a style=text-decoration:none href=/$DIR/members/$KYONEN>$KYONEN</a>&nbsp";
   echo "<a style=text-decoration:none href=/$DIR/members/$KOTOSHI>$KOTOSHI</a>&nbsp";

 }

?>

</th>
</table>


<?php

if( $_GET["mode"]=="full" )
echo '<table class="sortable" id="tablefont" width="85%">';
else if( $_GET["mode"] == "pdf" )
echo '<table class="sortable" id="tablefont" width="80%">';
else if(  $_GET["mode"] == "email" )
echo '<table class="sortable" id="tablefont" width="65%">';
else if( $_GET["mode"] == "private")
echo '<table class="sortable" id="tablefont" width="65%">';
else if(  $_GET["mode"] == "family" )
echo '<table class="sortable" id="tablefont" width="85%">';
else
echo '<table class="sortable" id="tablefont" width="500px">';


?>

<thead>
<tr>

<!--
<th scope="col">Year</th>
<th scope="col">First</th>
<th scope="col">Last</th>
-->

<?php
if(  $_GET["mode"] == "pdf" ){
   echo '<th scope="col">Year</th>';
   echo '<th scope="col">First</th>';
   echo '<th scope="col">Last</th>';
   echo '<th scope="col">City</th>';
   echo '<th scope="col">Phone</th>';
   echo '<th scope="col">NTRP</th>';

}else if( $_GET["mode"] == "full" ){

   echo '<th scope="col">Year</th>';
   echo '<th scope="col">First</th>';
   echo '<th scope="col">Last</th>';
   echo '<th scope="col">Address</th>';
   echo '<th scope="col">City</th>';
   echo '<th scope="col">Zip</th>';
// echo '<th scope="col">Memb</th>';
   echo '<th scope="col">Email</th>';
   echo '<th scope="col">Phone</th>';
   echo '<th scope="col">NTRP</th>';
   echo '<th scope="col">Date</th>';

}else if( $_GET["mode"] == "family" ){

   echo '<th scope="col">Year</th>';
   echo '<th scope="col">First</th>';
   echo '<th scope="col">Last</th>';
   echo '<th scope="col">Address</th>';
   echo '<th scope="col">City</th>';
   echo '<th scope="col">Zip</th>';
   echo '<th scope="col">Memb</th>';
   echo '<th scope="col">NTRP</th>';
   echo '<th scope="col">Date</th>';

}else if( $_GET["mode"] == "email" ){

/*
   echo '<th scope="col">"First",</th>';
   echo '<th scope="col">"Last",</th>';
   echo '<th scope="col">"Email",</th>';
   echo '<th scope="col">"Date",</th>';
*/
   echo '<th scope="col">Given Name,</th>';
   echo '<th scope="col">Family Name,</th>';
   echo '<th scope="col">E-mail 1 - Value,</th>';
   echo '<th scope="col">Date,</th>';

// private email contact info
}else if( $_GET["mode"] == "private" ){

   echo '<th scope="col">First</th>';
   echo '<th scope="col">Last</th>';
   echo '<th scope="col">Address</th>';
   echo '<th scope="col">MType</th>';
   echo '<th scope="col">Email</th>';
   echo '<th scope="col">Date</th>';


}else{
   echo '<th scope="col">Year</th>';
   echo '<th scope="col">First</th>';
   echo '<th scope="col">Last</th>';
   echo '<th scope="col">NTRP</th>';
   echo '<th scope="col">Date</th>';
}

?>

</tr>
</thead>



<?php



  date_default_timezone_set('America/Los_Angeles');

  $con =  DBMembership();

  $qr=mysqli_query($con, "select update_time from information_schema.tables where table_scheme='sctc_paypal' and table_name='sctc_paypal'");  


//  foreach($r as $key => $value) 
//     echo $key." ->".$value."<br>";


/*
  $regexp = "~";   
  if (  $_GET["year"] == "2012")        $regexp = "^#";
  else if($_GET["year"]  == "2013")     $regexp = "&";  // just 2013
  else if($_GET["year"]  == "2014")     $regexp = "~";  // just 2014
*/




// Query get get members, from the year 
  $query = 'select * from '.TABLE_PAYPAL.' where year= "'.$YEAR.'" ';

// Add this to get from bycheck table
  $query .= ' union select * from '.TABLE_CHECK.' where year =  "'.$YEAR.'"';

//  echo $query;

  if( $_GET[mode] == "email")   // reverse order when email (latest on top)
        $query .= ' order by date desc';
  else
        $query .= ' order by lname';


// echo $query;

// ---- Calculate the Residents/Non-Residents

  $qr=mysqli_query($con,$query);

  $total=$res=$non=0;
  while ($row = mysqli_fetch_assoc($qr)) {  

      $total +=1;
      if( preg_match("/santa|clara/i",$row[CITY])) 
          $res +=1;
      else
          $non +=1;
  }


   $qr=mysqli_query($con,"select update_time from information_schema.tables where table_name='sctc_paypal'");
   $qr=mysqli_query($con,'select update_time from information_schema.tables where table_schema ="members_opt" and table_name="paypal"');

   $row = mysqli_fetch_assoc($qr);


   if( $_GET["mode"] != "pdf"){
    echo "<center>$YEAR ** RESIDENTS: $res NONRESIDENTS: $non **</center>";
//  print("last updated ".ltrim( date("m/d/Y", strtotime($row["update_time"])),"0")."<br>"  );
    print("click top row to sort\n"  );

  }


  $query_results=mysqli_query($con , $query);

  print("<br>");
  $abbreviations = array( "jose" => "SJ", "sunnyvale" => "Su" ,"clara"=>"SC",
      "campbell"=>"Cpb","saratoga"=>"Srt","milpitas"=>"Mlp","mountain"=>"MV",
      "burl"=>"Blg","palo"=>"PA","fremont"=>"Fmt","soquel"=>"Soq",
      "cupertino"=>"Cup","gatos"=>"LG","sereno"=>"MS","menlo"=>"MP",
      "union"=>"UC","los altos"=>"LA","newark"=>"Nwk","menlo"=>"MP",
      "capitola"=>"Cap","san carlos"=>"SanC","millbrae"=>"Milb","menlo"=>"MP",
      "mtn"=>"MV","san carlos"=>"SanC","millbrae"=>"Milb","menlo"=>"MP",
      "redwood"=>"RC","mateo"=>"SM","morgan"=>"MH","sunny" => "Su",
      "san francisco"=>"SF","emerald hills"=> "EH", "hayward"=>"Hay",
      "brisbane"=>"Bris","san ramon"=>"SR"

      );


  while ($row = mysqli_fetch_assoc($query_results)) {  

     $EMAIL = strtolower($row[EMAIL]."@".$row[URL]);
     if( strlen($EMAIL) < 5)  $EMAIL = $URL= "";

     // REMOVE LAST @ CHARACTER
     $EMAIL= preg_replace('/@$/','',$EMAIL);


//   ERROR CORRECTIONS
     if( $row[CODE] == "4008" ) $row[CODE]="408";

     $PHONE = "(".$row[CODE].") ".$row[PHONE];
     if( strlen($PHONE) < 13)  $PHONE = "";

     $CITY = $row[CITY];

     foreach ( $abbreviations as $key => $value){
          if( preg_match( "/".$key."/i",$row[CITY]))   $CITY = $value;

     }

     $GENDER="";
     if( $row[GENDER] == "W" ) $GENDER="F";
     if( $row[GENDER] == "F" ) $GENDER="F";
     if( $row[GENDER] == "M" ) $GENDER="M";


     if( $row[MTYPE] == RES) $TYPE="Res";
     elseif( $row[MTYPE] == RES_JR) $TYPE="Jr";
     elseif( $row[MTYPE] == NONRES) $TYPE="NR";
     elseif( $row[MTYPE] == NONRES_FAM) $TYPE="NRFamily";
     elseif( $row[MTYPE] == NONRES_JR) $TYPE="Jr";


     $FIRSTNAME = $row[FNAME];
     $LASTNAME = $row[LNAME];
     $NTRP  = $GENDER.$row[NTRP];


     $DATE = date(" m/d/y",$row[DATE]);

     if(  $_GET["mode"] != "family")      echo "<tr>";

     if( $_GET["mode"] == "full" ){
         $RES = "";
         if( preg_match("/santa|clara/i",$row[CITY])) $RES = "<sup>&#149;</sup>";
         echo "<td>".$row[YEAR].$RES."</td>";
         echo "<td>".$FIRSTNAME."</td>";
         echo "<td>".$LASTNAME."</td>";
         echo "<td>".$row[ADDRESS]."</td>";
         echo "<td>".$CITY."</td>";
         echo "<td>".$row[ZIP]."</td>";
//       echo "<td>".$row[MTYPE]."</td>";
//       echo "<td>"."$".$row[PAYMENT]."</td>";
         echo "<td>".$EMAIL."</td>";
         echo "<td>".$PHONE."</td>";
         echo "<td>".$NTRP."</td>";
         echo "<td>".$DATE."</td>";
     }else if( $_GET["mode"] == "pdf" ){
         echo "<td>".$row[YEAR]."</td>";
         echo "<td>".$FIRSTNAME."</td>";
         echo "<td>".$LASTNAME."</td>";
         echo "<td>".$row[CITY]."</td>";
         echo "<td>".$PHONE."</td>";
         echo "<td>".$NTRP."</td>";
     }else if(  $_GET["mode"] == "family" ){
//      match either RF or NRF memberships (resident/non-resident Family)
        if( preg_match("/F/",$row[MTYPE])){   
              echo "<tr>";
              echo "<td>".$row[YEAR]."</td>";
              echo "<td>".$FIRSTNAME."</td>";
              echo "<td>".$LASTNAME."</td>";
              echo "<td>".$row[ADDRESS]."</td>";
              echo "<td>".$CITY."</td>";
              echo "<td>".$row[ZIP]."</td>";
              echo "<td>".$row[MTYPE]."</td>";
              echo "<td>".$NTRP."</td>";
              echo "<td>".$DATE."</td>";
              echo "</tr>";
        }
     }else if(  $_GET["mode"] == "email"){
//        if(strlen($row[FNAME])>=0){
          if(strlen($row[FNAME])>=0 && strlen($row[EMAIL])>0){

                $DATE = date("y/m/d",$row[DATE]);

                echo "<td>".$FIRSTNAME.",</td>";
                echo "<td>".$LASTNAME.",</td>";
                echo "<td>".$EMAIL.",</td>";
                echo "<td>".$DATE.",</td>";
           }

     }else if(  $_GET["mode"] == "private"){


                echo "<td>".$FIRSTNAME."</td>";
                echo "<td>".$LASTNAME."</td>";
                echo "<td>".$row[ADDRESS]."</td>";
                echo "<td>".$row[MTYPE]."</td>";
                echo "<td>".$EMAIL."</td>";
                echo "<td>".$DATE."</td>";
//     elseif( $row[MTYPE] == RES_JR) $TYPE="Jr";



     }else{
         $d="";

//       $year =  new DateTime('01/1/2013') ;
//       if($row[PAYMENT] == "Paypal" and $row[DATE]> mktime(0,0,0,1,1,2013)  )  $d=" (".$DATE.")";

//        Notch a resident
         $RES = "";
         if( preg_match("/santa|clara/i",$row[CITY])) $RES = "<sup>&#149;</sup>";
//       if( strpos($row[MTYPE],"R") == 0 ) $RES = "<sup>&#149;</sup>";

         echo "<td>".$row[YEAR].$RES."</td>";
         echo "<td>".$row[FNAME]."</td>";
         echo "<td>".$row[LNAME]."</td>";
         echo "<td>".$GENDER.$row[NTRP].$d."</td>";
         echo "<td>".$DATE."</td>";

     }

//     if(  $_GET["mode"] == "full" or $_GET["mode"] == "pdf" or $_GET["mode"] == "email")     echo "</tr>";

    if(  $_GET["mode"] == "full" )     echo "</tr>";
    if(  $_GET["mode"] == "pdf" )     echo "</tr>";
    if(  $_GET["mode"] == "email")     echo "</tr>";


    echo "\n";

  }


?>


</table>

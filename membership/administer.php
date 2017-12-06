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

.smalltime
{
   font-size: .7em;
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

ALTER TABLE pending MODIFY columnname datetime

select fname,lname,from_unixtime(date,'%Y %d %m') from pending order by date desc;

select fname,lname,date(from_unixtime(date)) from pending where date(from_unixtime(date)) = current_date();

-->


<?php


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



   echo "<a style=text-decoration:none href=/$DIR/admin$OTOTOSHI>$OTOTOSHI</a>&nbsp";
   echo "<a style=text-decoration:none href=/$DIR/admin$KYONEN>$KYONEN</a>&nbsp";
   echo "<a style=text-decoration:none href=/$DIR/admin$KOTOSHI>$KOTOSHI</a>&nbsp";

//   $MODE=$_GET["mode"];
?>


</th>
</table>

<?php
//  echo "<table><tr>";
//  echo "<td bgcolor=red height=5px width=100px >&nbsp;&nbsp;&nbsp; &nbsp;  &nbsp;  &nbsp; </td>";
//  echo "<td>Have end of Day to move to membership</td>";
//  echo "</table>";
?>


<table class="sortable" id="tablefont" width="95%">

<thead>
<tr>


<!--<form id="_MODIFY"  action="../modify" method="post"> -->
<form id="_MODIFY"  action="./modify" method="post">
<input name="ModifyForm" type="submit" value=" MODIFY ">


<th scope="col">Edit</th>
<th scope="col">Year</th>
<th scope="col">First</th>
<th scope="col">Last</th>
<th scope="col">Address</th>
<th scope="col">City</th>
<th scope="col">Zip</th>
<th scope="col">Email</th>
<th scope="col">NTRP</th>
<th scope="col">Date</th>

</tr>
</thead>

<?php


  date_default_timezone_set('America/Los_Angeles');

  $con =  DBMembership();

  $qr=mysqli_query($con, "select update_time from information_schema.tables where table_scheme='sctc_paypal' and table_name='sctc_paypal'");  


//  foreach($r as $key => $value) 
//     echo $key." ->".$value."<br>";


//select * ,'paypal' as source from paypal where year='2016' union select * ,'bycheck' as source from bycheck where year='2016' order by lname asc limit 40;


// Query get get members, from the year 
  $query = 'select *,"'.TABLE_PAYPAL.'" as source from '.TABLE_PAYPAL.' where year= "'.$YEAR.'" ';
//  $query = 'select * from "'.TABLE_PAYPAL.'" where year= "'.$YEAR.'" ';

// Add from bycheck table
  $query .= ' union select *,"'.TABLE_CHECK.'"as source from '.TABLE_CHECK.' where year =  "'.$YEAR.'"';

// Add from pending table
//  $query .= ' union select *,"'.TABLE_PENDING.'"as source from '.TABLE_PENDING.' where date(from_unixtime(date)) =  current_date() ';
//  $query .= ' and  year =  "'.$YEAR.'"';

// select fname,lname,from_unixtime(date) from pending where from_unixtime(date) > date_add( now(), interval - 6 hour);
   $query .= ' union select *,"'.TABLE_PENDING.'"as source from '.TABLE_PENDING.' where from_unixtime(date) >  date_add(now(),interval -24 hour) ';

//  $query .= ' union select *,"'.TABLE_PENDING.'"as source from '.TABLE_PENDING.' where from_unixtime(date) >  date_add(now(),interval -11 minute) ';
  $query .= ' and  year =  "'.$YEAR.'"';
  $query .= ' and  custom !=  "done" ';


//  echo $query;


// PUT LATEST ON TOP
   $query .= ' order by date desc';

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


//   if( $_GET["mode"] != "pdf"){
    echo "<center>$YEAR ** RESIDENTS: $res NONRESIDENTS: $non **</center>";
//  print("last updated ".ltrim( date("m/d/Y", strtotime($row["update_time"])),"0")."<br>"  );
//  print("click top row to sort\n"  );

//  }


  $query_results=mysqli_query($con , $query);

  print("<br>");
  $abbreviations = array( "jose" => "SJ", "sunnyvale" => "Su" ,"clara"=>"SC",
      "campbell"=>"Cpb","saratoga"=>"Srt","milpitas"=>"Mlp","mountain"=>"MV",
      "burl"=>"Blg","palo"=>"PA","fremont"=>"Fmt","soquel"=>"Soq",
      "cupertino"=>"Cup","daly"=>"DC","gatos"=>"LG","hillsb"=>"HlB","sereno"=>"MS","menlo"=>"MP",
      "union"=>"UC","los altos"=>"LA","newark"=>"Nwk","menlo"=>"MP",
      "capitola"=>"Cap","san carlos"=>"SanC","millbrae"=>"Milb","menlo"=>"MP",
      "mtn"=>"MV","san carlos"=>"SanC","millbrae"=>"Milb","menlo"=>"MP",
      "redwood"=>"RC","mateo"=>"SM","morgan"=>"MgH","sunny" => "Su",
      "san francisco"=>"SF","emerald hills"=> "EH", "hayward"=>"Hay",
      "brisbane"=>"Bris","san ramon"=>"SR"

      );


  while ($row = mysqli_fetch_assoc($query_results)) {  

     $EMAIL = strtolower($row[EMAIL]."@".$row[URL]);
     if( strlen($EMAIL) < 5)  $EMAIL = $URL= "";


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

     $RES = "";
     if( preg_match("/santa|clara/i",$row[CITY])) $RES = "<sup>&#149;</sup>";


       echo "<td>";
       $unique_id= $row["_id"];
       $source_id= $row["source"];
       $value = $unique_id.",".$source_id;
//     echo '<input type="checkbox" id="DEL" name="checkboxname[]"'." value='$value'   >";
//     echo '<input type="radio" id="DEL" name="checkboxname[]"'." value='$value'   >";
       echo '<input type="radio" id="DEL" name="radio"'." value='$value'   >";

       echo "</td>";

       echo "__________";
       echo "<td>".$row[YEAR].$RES;//."</td>";

       $color="black";

       $TIME="";
       if($source_id == TABLE_PENDING ) {
			  $color="red";
			  $TIME = date(" m/d/y h:i:s A",$row[DATE]);
                          $TIME = date("h:i:s A",$row[DATE]);
			  }



       echo '<td style="color:'.$color.';">'.$FIRSTNAME."</td>";
       echo '<td style="color:'.$color.';">'.$LASTNAME."</td>";
       echo '<td style="color:'.$color.';">'.$row[ADDRESS]."</td>";
       echo '<td style="color:'.$color.';">'.$CITY."</td>";
       echo '<td style="color:'.$color.';">'.$row[ZIP]."</td>";
       echo '<td style="color:'.$color.';">'.$EMAIL."</td>";
       echo '<td style="color:'.$color.';">'.$NTRP."</td>";



       if( $source_id == TABLE_PENDING){
           echo '<td style="font-size:.7em">'.$DATE;
//         echo '<br class="smalltime">'.$TIME;
           echo '<br>'.$TIME;
//           echo '<br style="font-size:.4em;">'.$TIME;
           echo "</td>";
       }else{
           echo '<td>'.$DATE;

       }
//       echo "\n";

  }


?>


</table>


</form>

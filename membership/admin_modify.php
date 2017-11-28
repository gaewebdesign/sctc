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

<!--
<table cellpaddiing="2" width="50%">
<th>
</th>
</table>
-->


<thead>
<tr>

<table class="sortable" id="tablefont" width="50%">

<form id="_DELETE"  action="submit" method="post">
<input name="_DELETE" type="submit" value="Delete Member from Database">
<br>
<input name="_MODIFY" type="submit" value="Modify Member Information">
<br>
<input name="_PENDING" type="submit" value="Add to Membership">

</thead>


<?php
include "includes.inc"; 


  print("<br>");
  if( !isset($_POST['radio']) ){

    echo "<p><br><p><br><p><br>";
    echo "Select Someone ";
    return;
  }

//  _ID and TABLE values
    $parameters = explode(",",$_POST['radio']);



    $KEYID = $parameters[0];
    $TABLE = $parameters[1];

    date_default_timezone_set('America/Los_Angeles');
    $con =  DBMembership();
    $query = "select * from $TABLE where _id = $KEYID";

    $query_results = mysqli_query($con,$query);
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

    $CUSTOM = $row[CUSTOM];

    $CODE = $row[CODE];
    $PHONEPRE = $PHONEPOST = "";

    if(strlen($row[PHONE]>=7)){
       $p = explode("-",$row[PHONE]);
       $PHONEPRE = $p[0];
       $PHONEPOST = $p[1];
       }


 echo "<tr>  <td> YEAR </td>    <td> $YEAR  </td>   </tr>";

// needed for next form
 echo '<input type="hidden" name = "keyID" value="'.$KEYID.'">';
 echo '<input type="hidden" name = "keyTABLE" value="'.$TABLE.'">';
 echo '<input type="hidden" name = "keyCUSTOM" value="'.$CUSTOM.'">';

 echo "<tr>";  
 echo "<td> NAME </td>  ";

 echo "<td> ";
 echo musk(FNAME,$FNAME,20,20);
 echo "&nbsp;&nbsp;&nbsp;&nbsp;";
 echo musk(LNAME,$LNAME,20,20);
 echo "</td></tr>";


 echo "<tr><td> GENDER </td>  ";
 echo "<td> ";
 echo musk(GENDER,$GENDER,5,1);
 echo "&nbsp;&nbsp;&nbsp;&nbsp;";

 echo "<tr><td> RATING </td><td> ";
 echo musk(NTRP,$NTRP,5,3);
 echo "</td></tr>";


 echo "<tr>  <td>ADDRESS</td><td>";
 echo musk(ADDRESS,$ADDRESS,35,35);
 echo "</td></tr>";

 echo "<tr>  <td>CITY</td><td>";
 echo musk(CITY,$CITY,20,15);
 echo "</td></tr>";

 echo "<tr>  <td>ZIP</td><td>";
 echo musk(ZIP,$ZIP,10,5);
 echo "</td></tr>";


 echo "<tr>  <td>EMAIL</td><td>";
 echo musk(EMAIL,$EMAIL,20,30);
 echo "@";
 echo musk(URL,$URL,30,30);
 echo "</td></tr>";

 echo "<tr>  <td>PHONE</td><td>";
 echo "(";
 echo musk(CODE,$CODE,7,3);
 echo ") ";
 echo musk(PHONEPRE,$PHONEPRE,10,3);
 echo " - ";
 echo musk(PHONEPOST,$PHONEPOST,10,4);
 echo "</td></tr>";


//  First Name: <input  required value="" class="eline" name="fname" placeholder="" type="text" id="FIRST"/> &nbsp;&nbsp;&nbsp;&nbsp; 

function musk( $box, $pvalue,$width=20,$maxlength=4){

    echo "<input value=";
    echo '"'.$pvalue.'"';
    echo  'name="'.$box.'" ';
    echo ' class="eline" style="'."width:$width%;".'"';
    echo ' maxlength="'.$maxlength.'"';
    echo "/>";
}


function _elonmusk( $box, $pvalue,$width=20){

    echo "<input required value=";
    echo '"'.$pvalue.'"';
    echo  'name="'.$box.'" ';
    echo ' class="eline" style="'."width:$width%;".'"';
    echo "/>";
}




?>





</table>


</form>

<?php

date_default_timezone_set("America/Los_Angeles" );
$KOTOSHI =  date("Y");

$KOTOSHI =  "2018";

$month = date("m");
$day = date("d");


//  print("last updated ".ltrim( date("m/d/Y", strtotime($row["update_time"])),"0")."<br>"  );

// set to 0 after April 1
// set to big  allow surplus of non-residents


//if($month >= 4) $SURPLUS=0;

$SURPLUS= 0;
$SURPLUS= 85;

//print($SURPLUS);
//print("(".$month."/".$day.")");

?>
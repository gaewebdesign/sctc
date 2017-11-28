<html>
<div align="center">
<h1>USTA / Santa Clara Tennis Club Membership Check </h1>
</div>

<TABLE align="center" class="sortable" BORDER=1 cellpadding=0 cols=8 cellspacing=0  width="500"  bgcolor="linen">
<TR bgcolor="99ccff">
<TH width="150"><font size="2" >First Name</font></TH>
<TH width="150"><font size="2" >Last Name</font></TH>
<TH width="100"> <font size="2">City </font></TH>
<TH width="100"> <font size="2">Status </font></TH>
</tr>


<?php

/*

define("COLORMEN","D2D2FF");
define("COLORRED" , "FFFFFF");
define("COLORYELLOW","FFFF33");
define("COLORGREEN","77ff77");
define("TABLE","sctc12");
define("YEAR","2013");

*/

define("COLORBLUE" , "DCEAFC");
define("COLORHEADER","99ccff");
define("COLORMEMBER","a9a9f5");
define("COLORWHITE" , "FFFFFF");



define("SKIP","1");
define("FINISH","2");


include "../library/library.php";
include "includes.inc";
include "kotoshi.inc";

date_default_timezone_set('America/Los_Angeles');

/*
$MEMBERS_RESIDENT = 0;
$MEMBERS_NONRESIDENT = 0;
$WAITLIST = 0;
$USTA_RESIDENT=0;
$USTA_NONRESIDENT=0;
*/

global $MEMBERS;
$MEMBERS = array();

   echo "<center><b>".date("F d, Y ", time())."</b></center><br>" ;

// One time - get members and store in global array
   GetMembers();

   GetTeams( );

?>

</table>
<html>


<?php

function DEBUG($t)
{

//      echo( $t );

}

function ListMembers()
{
   global $MEMBERS;

   foreach( $MEMBERS as $key => $val){
        echo $key." ".$val." ";

   }
}



// Get players from DB and store into global array 
function GetMembers()
{

global $MEMBERS;
global $KOTOSHI;

$YEAR = $KOTOSHI; //MEMBERSHIP_YEAR;


$query = 'select * from '.TABLE_PAYPAL.' where year>='.$YEAR;
$query .= ' union ';
$query .= 'select * from '.TABLE_CHECK.' where  year>='.$YEAR;
$query .= ' order by lname';

//echo $query;
$con = DBMembership( ); 

$query_results=mysqli_query($con,$query);
$numrows = mysqli_num_rows($query_results);

$u=1;
while ($row = mysqli_fetch_assoc($query_results) ) {

       //need  unique first name (add u) for hash table

       $fname = trim($row[FNAME])." ".$u;
       $MEMBERS[$fname] = $row[LNAME];

//      echo $fname.strlen($fname)." ". $MEMBERS[$fname]." <br>";
       
       $u++;
  }

}



// Search on LNAME, then check for FNAME
function findMember($fname,$lname)
{
   global $MEMBERS;
   $search = 0;

   $retval=0;


   $fname =  trim($fname);
   $lname = trim($lname);



// HARD CODED for Jackie Davidson-Fenton
   if( strpos($fname,"Jackie") !== false && (strpos($lname,"Davidson")!==false)   )       $lname="Fenton";
   if( strpos($fname,"Carlos") !== false && (strpos($lname,"guzman") !==false)   )        $lname="Nino de Guzman";


//  preg_match("/Chiang/",$lname )

    if(preg_match("/Chiang/i",$lname ) ||  preg_match("/Isaacson/i",$lname )  ||  preg_match("/Hahn/i",$lname )||  preg_match("/Nettle/i",$lname )){

        DEBUG(" findMember(".$fname.", ".$lname.")<br>");
        $search =1;
   }

   $search=1;    

   $pattern=  "/".$lname."/i";
   $pattern=  "/^".$lname."/i";


   $found = preg_grep( $pattern , $MEMBERS) ;

   if(empty($found)){
              $retval=0;       // Didn't find last name
              DEBUG( "didnt find ".$lname);

   }else{
              if($search){
                DEBUG( "found ".$lname." now looking for ".$fname."<br>");
              }

              // Look for first name (first 3 characters)
              $pattern = "/".rtrim(substr($fname,0,2))."/i";


              foreach ($found as $first => $last){

//                 if($search==1)  echo "looking for ".$pattern."<br>";
                   if($search){
                     DEBUG( "now looking for ".$pattern." in ".$first."<br>");
                   }


                  if( preg_match( $pattern, $first,$matches)) {
                            $retval= 1;
                            if($search){
                             DEBUG( "found ".$pattern." in ".$first."<-------------<br>");
                             DEBUG( "return ".$retval."<br>");
                             return $retval;
                             }                     

                  }
              }
   }   
   



   return $retval;


}

function Table( )
{
  echo '<TABLE align="center" class="sortable" BORDER=1 cellpadding=0 cols=8 cellspacing=0  width="500"  bgcolor="linen">';
}

function EndTable( )
{
  echo '</table><br>';
}

function GetTeams( )
{

// http://www.oooff.com/php-scripts/basic-php-scrape-tutorial/basic-php-scraping.php


$url = 'http://ustanorcal.com/organization.asp?id=663';   // SJSW

$url = 'https://ustanorcal.com/organization.asp?id=3483';   // Santa Clara at Central Park


//$output = file_get_contents($url);
//echo $output;
//echo "parsing";

$ch = curl_init($url);


// http://www.oooff.com/php-scripts/basic-curl-scraping-php/basic-scraping-with-curl.php
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$curl_scraped_page = curl_exec($ch);
curl_close($ch);



// Get Santa Clara TennisClub 
// changed (Santa Clara [^<])< to ([^<])<  (get everything except <)
preg_match_all( '/href=teaminfo.asp\?id=([\d]*)>([^<]*)<[ .\w\d\/<>=#]*?align=left>([-\w]*)[ ,]*([-\w]*)/i', $curl_scraped_page , $_teaminfo , PREG_PATTERN_ORDER);

// Pull in ID, Team Name </td> <td Area </td><td Captain</td>
$regexp = '/href=teaminfo.asp\?id=([\d]*)>([^~]*?)<\/a><\/td>';  // ID, Team Name
$regexp .= '<td [^~]*?<\/td>';      // Area (not used)
$regexp .= '<td ([^~]*?)<\/td>';    // Captain 
$regexp .= '/i';

preg_match_all( $regexp, $curl_scraped_page , $_teaminfo , PREG_PATTERN_ORDER);

//print_r($_teaminfo);


$regCaptain = '/align=left>([^,]*?)[ ,]*([^,]*?)$/i';
for($j=0 ; $j < count($_teaminfo[0]) ; $j++){

        $teamid   =  $_teaminfo[1][$j];
        $teamlink =  $_teaminfo[2][$j];

// Extract the Captain from this column
        preg_match_all( $regCaptain, $_teaminfo[3][$j] , $_captain, PREG_PATTERN_ORDER );
        $lname = $_captain[1][0];
        $fname = $_captain[2][0];
        $captain = $fname." ".$lname;

        $teamlink = '<a style=text-decoration:none href="https://ustanorcal.com/teaminfo.asp?id='.$teamid.'">'.$teamlink."</a>";


//      Cut off parsing
        $find=0;
        if( $teamid == 75074) $find=FINISH;
        if( $teamid == 72039) $find=FINISH;
        if( $teamid == 62435) $find=FINISH;
        if( $teamid == 64568) $find=FINISH;   // Tri-Level team
        if( $teamid == 66207) $find=FINISH;   // 55Mx8.0

        if( $teamid == 67065) $find=SKIP; 
        if( $teamid == 67063) $find=SKIP; 
        if( $teamid == 67116) $find=SKIP; 

        if( $teamid == 69640) $find=SKIP; 
        if( $teamid == 69641) $find=SKIP; 
        if( $teamid == 69642) $find=SKIP; 
        if( $teamid == 69643) $find=SKIP; 

        if( $teamid == 69728) $find=SKIP; 
        if( $teamid == 69644) $find=SKIP; 



        if( $find == 0 ){
		Table( );

		TripleCell( COLORBLUE ,  $teamlink." <br>Captain ".$captain." " );
		echo "<tr>";
		GetTeamPlayers( 'https://ustanorcal.com/teaminfo.asp?id='.$teamid );
		EndTable( );
		echo "<tr>";

	}elseif( $find == SKIP){

        }elseif( $find == FINISH ){
		break;

	}

        $count ++;
        $find=0;



}


}



function GetTeamPlayers( $url )
{


global $USTA_RESIDENT, $USTA_NONRESIDENT;


// echo "get playaers from ".$url;


 $ch = curl_init($url);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 $curl_scraped_page = curl_exec($ch);
 curl_close($ch);

# Original matcher
//preg_match_all( '/href=playermatches.asp\?id=([\d]*)>([ -\w]*)[ ,]*([ -\w]*)[ <>\w=#\d\/]*?nowrap>([ \w]*)/', $curl_scraped_page , $_players , PREG_PATTERN_ORDER);


/*
<a href=playermatches.asp?id=114612>Adams, Jennifer A </a></td>
<td bgcolor=white align=left nowrap>San Jose</td>
<td bgcolor=white align=center>F</td>
<td align=center bgcolor=white>4.5C</td>
<td align=center bgcolor=white></td>
<td align=center bgcolor=white>3/31/2016</td>
<td bgcolor=white align=center>2 / 1</td
*/

  $pattern = "/href=playermatches.asp\?id=([\d]*)>";     // ID

  $pattern .= "([ ,\'\-\w]*)<\/a><\/td>";  //  name

  $pattern .= "<td bgcolor=[#\d\w]* align=left nowrap>([ \w\d]*)<\/td>";   // city

  $pattern .= "<td bgcolor=[#\d\w]* align=center>([MF]{1})<\/td>";   // gender

  $pattern .= "<td align=center bgcolor=[#\d\w]*>([\d\w\.]*)<\/td>";   // rating

  $pattern .= "<td align=center bgcolor=[#\d\w]*>([\w]*)<\/td>";   // national

  $pattern .= "<td align=center bgcolor=[#\d\w]*>([\d\/]*)<\/td>";   // expire

//  $pattern .= "<td bgcolor=[#\d\w]* align=center>([ \d\/\(\)\>\<b]*)<\/td>";   // record

   $pattern .= "<td bgcolor=[#\d\w]* align=center>([ \d\/]*)([\d\w\<\>\/]*)<\/td>";   // record


  $pattern .= "/";

  preg_match_all($pattern , $curl_scraped_page, $_player,PREG_PATTERN_ORDER);
  $players = count( $_player[0] );

  for ( $j = 0 ; $j < $players ; $j++){
            $id = $_player[1][$j];
            $name = $_player[2][$j];
            $city = $_player[3][$j];
            $gender = $_player[4][$j];
            $rating = $_player[5][$j];
            $nat = $_player[6][$j];
            $expire = $_player[7][$j];

            $record = $_player[8][$j];

            $record = str_replace(' ', '', $record);
            $record = str_replace('/', ' / ', $record);

   	    $bgColor =  COLORWHITE;

            
//    SPLIT INTO FIRST AND LAST NAMES
            $s = explode(',',trim($name),2);
            $lname = trim($s[0]);
            $fname = trim($s[1]);

            $l=$lname;
            $f = $fname;
            DEBUG( "TEAM ".$fname." ".$lname."<br>");
            $found = findMember($fname,$lname);
  	    if($found != 0) $bgColor =  COLORMEMBER;

            MCell($bgColor , trim($fname));
            MCell($bgColor , trim($lname));

//            MCell($bgColor , trim($f));
//            MCell($bgColor , trim($l));

            MCell($bgColor , $city);
            MCell($bgColor , "&nbsp;".$record."&nbsp;");

           echo "</tr>\r\n";

  }




}


function Center( $color , $data )
{
  
	echo "<td valign='middle' height=15 ALIGN=CENTER bgcolor=".$color." ><font size='3'>".$data."&nbsp</td>";

}


function MCell( $color , $data )
{
  
	echo "<td valign='middle' height=15 ALIGN=LEFT bgcolor=".$color." ><font size='3'>".$data."&nbsp</td>";

}


function TripleCell( $color , $data )
{
  
	echo "<td valign='middle' height=15 colspan=4 ALIGN=LEFT bgcolor=".$color." ><font size='3'>".$data."&nbsp</td>";

}




?>

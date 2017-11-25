<?php

  include "library.php";

  session_start();

  $division = $_SESSION[DIVISION];

  $fname = $_SESSION[FNAME];
  $lname = $_SESSION[LNAME];
  $gender = $_SESSION[GENDER];
  $ntrp   = $_SESSION[NTRP];
  $email  = $_SESSION[EMAIL]."@".$_SESSION[URL];
  $phone  = "(".$_SESSION[AREACODE].") ".$_SESSION[PHONEPRE]."-".$_SESSION[PHONEPOST];
  $member   = $_SESSION[MEMBER];



  $level2 = $_SESSION[LEVEL2];
  $fname2 = $_SESSION[FNAME2];
  $lname2 = $_SESSION[LNAME2];
  $gender2 = $_SESSION[GENDER2];
  $ntrp2   = $_SESSION[NTRP2];
  $email2  = $_SESSION[EMAIL2]."@".$_SESSION[URL2];;
  $phone2  = "(".$_SESSION[AREACODE2].") ".$_SESSION[PHONEPRE2]."-".$_SESSION[PHONEPOST2];

  $member2   = $_SESSION[MEMBER2];

//  foreach ( $_SESSION as $key => $value)
//     echo $key." -> ".$_SESSION[$key]."<br>";

//  echo "Save to data base ";



   $query = 'insert into _temp (_id,division,fname,lname,gender,ntrp,email,phone,paid,member,fname2,lname2,gender2,ntrp2,email2,phone2,paid2,member2) values';
   $query .= '(NULL'.add($division).add($fname).add($lname).add($gender).add($ntrp).add($email).add($phone).add($paid).add($member);
   $query .= add($fname2).add($lname2).add($gender2).add($ntrp2).add($email2).add($phone2).add($paid2).add($member2).")";


// echo $query;

   session_destroy();

   DBConnect();
   $query_results=mysql_query($query);


   echo '<p><h2><center>';
   echo 'Thanks '.$fname.'<br>';
   echo 'For entering tournament '.'<br>';
   echo 'Please go to <a href="./players.php">players.php</a>'.' to pay for the tournament<br>';

   echo '</h2>';



?>
Pictures uploaded are at <br>
<?php 

 $d= "http://localhost/~roger/sctc/events/2012mixed/images";
 if(strstr($_SERVER["SERVER_NAME"],"sctennis"))  $d= "http://sctennisclub.org/events/2012mixed/images";
 echo '<a href="'.$d.'">'.$d."</a>";
?>

 <p><br>

<?php

 $target = basename( $_FILES['uploaded']['name']) ; 
 $target = "./images/".strtolower($target);

 $ok=1; 
 if(move_uploaded_file($_FILES['uploaded']['tmp_name'], $target)) 
 {
 echo "The file ". basename( $_FILES['uploadedfile']['name']). " has been uploaded ";
 echo "to ".$target;

 } 
 else {
 echo "Sorry, there was a problem uploading your file.";
 }

function LocalHost()
{
   $ret=1;
   if(strstr($_SERVER["SERVER_NAME"],"sctennis")) $ret = 0;
   return $ret;
}

 ?> 

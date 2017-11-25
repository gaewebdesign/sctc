
Pictures uploaded are at <br>
<?php 

   $d= "http://localhost/~roger/sctc/events/2012mixed";
   if(strstr($_SERVER["SERVER_NAME"],"sctennis"))  $d= "http://sctennisclub.org/events/2012mixed/images";
   echo '<a href="'.$d.'">'.$d."</a>";


?>


<form enctype="multipart/form-data" action="upload.php" method="POST">

 Please choose a file to upload: <br>

 <p>

<input name="uploaded" type="file" /><br />
 <input type="submit" value="Upload" />
 </form> 
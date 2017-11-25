<form>
<select name="folder">
  <option value="folder1">folder 1</option>
  <option value="folder2">folder 2</option>
</select>
</form>

<?php

$servername = $GLOBALS["servername"];

$servername = "sctennisclub.org";
$ftpUser = "mixed2012";
$ftpPass = "mixed.2012";


echo $servername."<br>";
echo $ftpUser."<br>";
echo $ftpPass."<br>";



$conn_id = ftp_connect($servername) or die("<p style=\"color:red\">Error connecting to $servername </p>");

echo "Connection to ".$conn_id;

$path=".";
if(ftp_login($conn_id, $ftpUser, $ftpPass))
{
    $dir_handle = @opendir($path) or die("Error opening $path");

         while ($file = readdir($dir_handle)) {

            echo "put ".$file."<br>";
//          ftp_put($conn_id, PATH_TO_REMOTE_FILE, $file)


        }
}


?>
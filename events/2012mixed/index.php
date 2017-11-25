<body bgcolor='navyblue'>

<center>
<h2>2012 Sept 22/23 Mixed Doubles Tournament</h2>
</center>
<p>

<center>

<?php

$d = scandir("./thumbs"); 
$p = preg_grep("/.jpg|.jpeg|.gif|.png/i", $d );
$p = array_values($p);

foreach( $p as $index => $f){

  echo '<a href="./images/'.$f.'">';
  echo '<img src="./thumbs/'.$f.'" />';
  echo "</a>";
// echo $index;
  echo "&nbsp;";
  echo "\n";

  if( ($index+1) %4 == 0)  echo "<br>";

}



?>
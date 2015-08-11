<html>
<head><title>Testing Great Circle</title></head>
<body>
<?php

  $cur_latitude   = $_GET['lat'];
  $cur_longitude  = $_GET['lon'];

$pi = 3.1415926; 
$rad = doubleval($pi/180.0); 
$bad_latitude = 40.9458;
$bad_longitude = -73.5218;

$cur_longitude = doubleval($cur_longitude)*$rad; $cur_latitude = doubleval($cur_latitude)*$rad; 
$bad_longitude = doubleval($bad_longitude)*$rad; $bad_latitude = doubleval($bad_latitude)*$rad; 

$theta = $bad_longitude - $cur_longitude; 
$dist = acos(sin($cur_latitude) * sin($bad_latitude) + cos($cur_latitude) * cos($bad_latitude) * cos
($theta)); 
if ($dist < 0) { $dist += $pi; } 
$dist = $dist * 6371.2; 
$miles = doubleval($dist * 0.621); 
$inches = doubleval($miles*63360); 
$dist = sprintf( "%.2f",$dist); 
$miles = sprintf( "%.2f",$miles); 
$inches = sprintf( "%.2f",$inches); 

echo "Dist:".$dist."<br>\n";
echo "Miles:".$miles."<br>\n";
echo "Inches:".$inches."<br>\n";

?>
</body>
</html>


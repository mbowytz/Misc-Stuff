<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD>
<META http-equiv=Content-Type content="text/html; charset=windows-1252">
</HEAD>
<BODY>
<H1>Hello!</H1>
<?php
  
  include_once("../include/constants.php");

/* Return true if the xml does not contain an <error></error> tag */
function is_valid($xml) {
  @$feed = new SimpleXMLElement($xml);
  return ($feed->error->message)?true:false;
}

function get_user_location($user_key) {

  $lat = 0;
  $lng = 0;
  $ts = "2000-01-01 00:01 EDT";

  if ($user_key <> "") {
    $url = "http://query.xtify.com/api/1.0/xml/location?userkey=".$user_key."&cpid=".XTIFY_CPID;
    
    echo "XTIFY URL:".$url."<BR>";
    
    if (@fopen($url, "r")) {
      $a =  simplexml_load_file($url, 'SimpleXMLElement', LIBXML_NOCDATA);

      if ($a->error->message=='' ) { 
        echo "Valid XML<BR>\n";
        $lat = $a->locationset->location->coords->lat;
        $lng = $a->locationset->location->coords->lon;
        $ts = $a->locationset->location->timestamp;
      }
      else {
        echo "Invalid XML<BR>\n";
      }
    }
  }

  return array ($lat, $lng, $ts);

}

  
echo "Top<br>";

     $connection = mysql_connect(DB_SERVER, DB_USER, DB_PASS) or die(mysql_error());
     mysql_select_db(DB_NAME, $connection) or die(mysql_error());
     $q = "SELECT x.user_id, x.xtify_key FROM xtify_tbl x, users u WHERE x.user_id = u.id AND x.xtify_key <> 'NOT_SET' AND x.xtify_key <> ''";
     
     echo $q."<BR>";
     
     $result = mysql_query($q, $connection);
echo "abc<BR>";
     if($result && mysql_num_rows($result) > 0){
     
       while ($row = @mysql_fetch_assoc($result)) {

        $user_id = $row['user_id'];
        $xtify_user_key = $row['xtify_key'];

        $cur_latitude = 0;
        $cur_longitude = 0;

        list($cur_latitude, $cur_longitude, $x_timestamp) = get_user_location($xtify_user_key);
        
        echo "<p>User:".$user_id." Lat:".$cur_latitude." Lon:".$cur_longitude." TS:".$x_timestamp."</p>";
        
        if ($cur_latitude != 0 && $cur_longitude != 0) {
          $upd_sql = "UPDATE xtify_tbl SET curr_latitude = ".$cur_latitude.", curr_longitude = ". $cur_longitude.", last_updated = FROM_UNIXTIME(".strtotime($x_timestamp)."),xtify_last_queried = NOW() WHERE user_id = ".$user_id;
         
          $result2 = mysql_query($upd_sql, $connection);
          echo $upd_sql ."<BR>\n";
          if ($result2) { echo "Ok!<BR>\n"; } else {echo "Failed.<BR>\n";}
          echo "=========================================<BR>\n";
        }  //end if
       } //end while
     
     }  //end if
     else {echo "did not connect.";}
     if(isset($connection)){
       mysql_close($connection);
     }

?>
</BODY></HTML>

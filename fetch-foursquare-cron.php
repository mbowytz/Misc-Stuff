<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD>
<META http-equiv=Content-Type content="text/html; charset=windows-1252">
</HEAD>
<BODY>
<H1>Hello!</H1>
<?php
  
  include_once("../include/constants.php");
  include_once("../include/foursquareUtil.php");
/* Return true if the xml does not contain an <error></error> tag */
function is_valid($xml) {
  @$feed = new SimpleXMLElement($xml);
  return ($feed->error->message)?true:false;
}


echo "Top<br>";

     $connection = mysql_connect(DB_SERVER, DB_USER, DB_PASS) or die(mysql_error());
     mysql_select_db(DB_NAME, $connection) or die(mysql_error());
     $q = "SELECT f.foursquare_id, f.oauth_access_token, f.oauth_access_token_secret, f.rss_secret_value FROM foursquare_oauth_tbl f, xtify_tbl x, users u WHERE x.user_id = f.user_id AND f.user_id = u.id";
     
     echo $q."<BR>";
     
     $result = mysql_query($q, $connection);

     if($result && mysql_num_rows($result) > 0){
     
       while ($row = @mysql_fetch_assoc($result)) {

        $rss_secret_value = $row['rss_secret_value'];
        $foursquare_id = $row['foursquare_id'];
        $oauth_access_token = $row['oauth_access_token'];
        $oauth_access_token_secret = $row['oauth_access_token_secret'];
       
       echo "get_foursquare_xml --> ".$rss_secret_value."\n";
       
       get_foursquare_xml($rss_secret_value);
       
      echo " get_foursquare_friend_xml --> ".$foursquare_id.", ".$oauth_access_token.", ".$oauth_access_token_secret."\n------------------------------------\n";
       
       get_foursquare_friend_xml($foursquare_id, $oauth_access_token, $oauth_access_token_secret);
       
       }
      }
     else {echo "did not connect.";}
     if(isset($connection)){
       mysql_close($connection);
     }

?>
</BODY></HTML>
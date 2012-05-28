<?php
//error_reporting(0);

if($_COOKIE['date'] != date('d-m-Y') && date('G') >= 12) {
  setcookie('date', date('d-m-Y'), time()+31536000);
  setcookie('dashes', '', time()+31536000);
  $dashes = array();
} else {
  $dashes = unserialize($_COOKIE['dashes']);
}

$fp = fopen('log.txt', 'a');
fputs($fp, "| ".date('d/m')." | ".date('H:i')."   | =============================\r\n");

$ch = curl_init('http://streeplijst.huizeentree.nl/api/dashes.json');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);

mysql_connect('localhost', 'root', 'bitchesbecrazy');
mysql_select_db('streeplijst');

if(curl_exec($ch) === false) {  
  foreach($_POST['users'] as $num => $user_id) {
    mysql_query("INSERT INTO `dashes` (`user_id`, `product_id`) VALUES (".$user_id.", ".$_POST['product_id'].")");
    fputs($fp, "| lokaal | pending | streepje(".mysql_insert_id().")[".$user_id.", ".$_POST['product_id']."] gecached\r\n");
    if($dashes[$user_id]) { $dashes[$user_id] += 1; } else { $dashes[$user_id] = 1; }
  }
   
  setcookie('dashes', serialize($dashes), time()+31536000);   
  echo '{"online":0}';
} else {
  // oude eerst
  $result = mysql_query("SELECT * FROM `dashes` WHERE `pushed`=0 ORDER BY `timestamp`");
  
  while($dash = mysql_fetch_assoc($result)) {
    $data = array('apikey' => 'strepen', 'dash[user_id]' => $dash['user_id'], 'dash[product_id]' => $_POST['product_id'], 'dash[created_at]' => $dash['timestamp']);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_exec($ch);    
    fputs($fp, "| lokaal | pushed  | streepje(".$dash['id'].") gepushed\r\n");
  }
  
  mysql_query("UPDATE `dashes` SET `pushed`=1 WHERE `pushed`=0");

  foreach($_POST['users'] as $num => $user_id) {
    $data = array('apikey' => 'strepen', 'dash[user_id]' => $user_id, 'dash[product_id]' => $_POST['product_id']);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_exec($ch);
    fputs($fp, "| extern | pushed  | streepje(?)[".$user_id.", ".$_POST['product_id']."] gepushed\r\n");
    if($dashes[$user_id]) { $dashes[$user_id] += 1; } else { $dashes[$user_id] = 1; }
  }

  setcookie('dashes', serialize($dashes), time()+31536000);  
  echo '{"online":1}';
}

curl_close($ch);
fclose($fp);
?>
<?php
require '../bootstrap.php';

if($_COOKIE['date'] != date('d-m-Y') && date('G') >= 12) {
  setcookie('date', date('d-m-Y'), time()+31536000);
  setcookie('dashes', '', time()+31536000);
  $dashes = array();
} else {
  $dashes = unserialize($_COOKIE['dashes']);
}

$fp = fopen('log.txt', 'a');
fputs($fp, "| ".date('d/m')." | ".date('H:i')."   | =============================\r\n");
 
foreach($_POST['users'] as $num => $user_id) {
  fputs($fp, "| " . date('d-m-Y H:i') . " | streepje[".$user_id.", ".$_POST['product_id']."]\r\n");
  if($dashes[$user_id]) { $dashes[$user_id] += 1; } else { $dashes[$user_id] = 1; }
  $api->post(array('dash[user_id]' => $user_id, 'dash[product_id]' => $_POST['product_id']), 'dashes');
}
   
setcookie('dashes', serialize($dashes), time()+31536000);   
fclose($fp);
?>
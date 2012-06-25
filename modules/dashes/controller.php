<?php
if($_SERVER['REQUEST_METHOD'] == 'POST') {
  if($_COOKIE['date'] != date('d-m-Y') && date('G') >= 12) {
    setcookie('date', date('d-m-Y'), time()+31536000);
    setcookie('dashes', '', time()+31536000);
    $dashes = array();
  } else {
    $dashes = unserialize($_COOKIE['dashes']);
  }
   
  foreach($_POST['users'] as $num => $user_id) {
    if($dashes[$user_id]) { $dashes[$user_id] += 1; } else { $dashes[$user_id] = 1; }
    $api->post(array('dash[user_id]' => $user_id, 'dash[product_id]' => $_POST['product_id']), 'dashes');
  }
     
  setcookie('dashes', serialize($dashes), time()+31536000); 
  exit;  
} else {
  $most_popular = $api->get_json('products', 'most_popular');
  $products     = $api->get_json('products');
  $top_drinker  = $api->get_json('users', 'top_drinker');
  $persons      = $api->get_json('users');
  $dashes       = unserialize($_COOKIE['dashes']);
}

// EOF

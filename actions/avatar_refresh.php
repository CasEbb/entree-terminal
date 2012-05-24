<?php
require '../bootstrap.php';
error_reporting(0);

$persons = $api->get_json('users');

foreach($persons as $person) {
  $contents = file_get_contents(BACKEND.$person->avatar_path);
  $bricked = FALSE;
  
  if($contents === FALSE) {
    $bricked = TRUE;
    break;
  } else {
    $fp = fopen('../assets/images/faces/'.$person->id.'.jpg', 'w');
    fputs($fp, $contents);
    fclose($fp);
  }
  
  if($bricked) {
    echo '{"online":0}';
  } else {
    echo '{"online":1}';
  }
}
?>
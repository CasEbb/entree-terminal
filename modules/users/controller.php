<?php
if($_SERVER['REQUEST_METHOD'] == 'POST') {
  $_SESSION['checked_in'][] = (int)$_POST['user'];
  exit;
} else {
  $users = $api->get_json('users');
}

// EOF
